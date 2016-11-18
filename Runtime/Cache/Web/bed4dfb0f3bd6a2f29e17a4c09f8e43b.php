<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/list.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript" src="/Public/Web/js/iscroll.js"></script>
    <script type="text/javascript">
        wxglobal.SetSize(function () {
            $("#htmlKeyword").width($(window).width() - 80 + 'px');
        });

        $(function () {

            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            //alert(location.href);
            $("#search").click(function () {
                var keyword = $("#htmlKeyword").val();
                var url = '<?php echo U("Web/Query/lists");?>?keyword=' + keyword;
                console.log(url);
                location.href = url;
            })
        });
    </script>

    <script type="text/javascript">

        var OFFSET = 5;
        var page = 1;	//页数
        var PAGESIZE = 10;//每页显示数据

        var myScroll,
			pullDownEl,
			pullDownOffset,
			pullUpEl,
			pullUpOffset,
			generatedCount = 0;

        var maxScrollY = 0;
        var hasMoreData = false;

        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, false);

        document.addEventListener('DOMContentLoaded', function () {
            $(document).ready(function () {
                loaded();
            });
        }, false);
        var template_all = '  <div class="queryGoodsItem">                                                                                                       ';
        template_all += '     <div class="image">                                                                                                                ';
        template_all += '          <a href="{productview_title}" target="_self"><img alt="" src="{thumb}" /></a>                                          ';
        template_all += '      </div>                                                                                                                            ';
        template_all += '      <div class="infos">                                                                                                               ';
        template_all += '          <div class="title"><a href="productItem.html" target="_self">{title}</a></div>                                         ';
        template_all += '          <div class="price">                                                                                                           ';
        template_all += '              <span class="priceNow">￥{nowprice}/{standard}</span>                                                                                     ';
        template_all += '          </div>                                                                                                                        ';
        template_all += '          <div class="count" {isread}>                                                                                                           ';
        template_all += '             <span><span class="goodsCountCut" {isCutdis} ></span><label class="goodsCountLab" id="{goods_id}" {isLabdis} >{cartnum}</label><span class="goodsCountAdd"></span></span> ';
        template_all += '          </div>                                                                                                                        ';
        template_all += '          <div class="count" {noread}>                                                                                                           ';
        template_all += '             <span style="float:right; margin-right:10px;">已过期</span>';
        template_all += '          </div>                                                                                                                        ';
        template_all += '          <div class="count" {noreads}>                                                                                                           ';
        template_all += '             <span style="float:right; color:red; margin-right:10px;">正在补货中</span>';
        template_all += '          </div>                                                                                                                        ';

        template_all += '      </div>                                                                                                                            ';
        template_all += ' </div>                                                                                                                                 ';


        function pageresp(response) {
          $.each(response, function (key, value) {
              var template = template_all;
              template = template.replace('{thumb}', value.thumb);
              template = template.replace('{title}', value.title);
              template = template.replace('{description}', value.description);
              template = template.replace('{nowprice}', value.nowprice);
              if (value.isover == '1') {
                  template = template.replace('{isread}', "style='display:none'");
              }
              else {
                  template = template.replace('{noread}', "style='display:none'");
              }
              if (value.stock == '0' && value.isover != '1') {
                  template = template.replace('{isread}', "style='display:none'");
              }
              else {
                  template = template.replace('{noreads}', "style='display:none'");
              }
              if (value.catnum <=0) {
                  template = template.replace('{isCutdis}', "style='display:none'");
                  template = template.replace('{isLabdis}', "style='display:none'");
              }
              template = template.replace('{oldprice}', value.oldprice);
              template = template.replace('{standard}', value.standard);
              template = template.replace('{goods_id}', value.id);
              template = template.replace('{cartnum}', value.catnum);
              template = template.replace('{productview_img}', "<?php echo ($goods_info_img); ?>");
              template = template.replace('{productview_title}', "<?php echo ($goods_info_title); ?>");
              template = template.replace('%7Bgoods_id%7D', value.id);
              template = template.replace('%7Bgoods_id%7D', value.id);
              $("#thelist").append(template);
          });
        }
        function loaded() {
            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;
            pullUpEl = document.getElementById('pullUp');
            pullUpOffset = pullUpEl.offsetHeight;
            hasMoreData = false;
            $("#pullUp").hide();
            pullDownEl.className = 'loading';
            pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
            page = 1;
            $.post(
				"<?php echo U('Web/Product/infolist');?>", {
				    "Page": page,
				    "catid": "<?php echo ($catid); ?>",
				    "keyword": "<?php echo I('get.keyword');?>"
				},
				function (response, status) {
				    if (status == "success") {
				        console.log(response);
				        $("#orderList").show();
                        if(response==null){
                            $("#pullDown").hide();
                            $("#pullUp").hide();
                        }
				        if (response.length < PAGESIZE) {
				            hasMoreData = false;
				            $("#pullUp").hide();
				        } else {
				            hasMoreData = true;
				            $("#pullUp").show();
				        }

				        myScroll = new iScroll('orderList', {
				            useTransition: true,
				            topOffset: pullDownOffset,
				            onRefresh: function () {
				                if (pullDownEl.className.match('loading')) {
				                    pullDownEl.className = 'idle';
				                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
				                    this.minScrollY = -pullDownOffset;
				                }
				                if (pullUpEl.className.match('loading')) {
				                    pullUpEl.className = 'idle';
				                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉刷新...';
				                }
				            },
				            onScrollMove: function () {
				                console.log(this.y);
				                console.log(this.OFFSET);
				                if (this.y > OFFSET && !pullDownEl.className.match('flip')) {
				                    pullDownEl.className = 'flip';
				                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '信息更新中...';
				                    this.minScrollY = 0;
				                } else if (this.y < OFFSET && pullDownEl.className.match('flip')) {
				                    pullDownEl.className = 'idle';
				                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉加载更多...';
				                    this.minScrollY = -pullDownOffset;
				                }
				                if (this.y < (maxScrollY - pullUpOffset - OFFSET) && !pullUpEl.className.match('flip')) {
				                    if (hasMoreData) {
				                        this.maxScrollY = this.maxScrollY - pullUpOffset;
				                        pullUpEl.className = 'flip';
				                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '信息更新中...';
				                    }
				                } else if (this.y > (maxScrollY - pullUpOffset - OFFSET) && pullUpEl.className.match('flip')) {
				                    if (hasMoreData) {
				                        this.maxScrollY = maxScrollY;
				                        pullUpEl.className = 'idle';
				                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
				                    }
				                }
				            },
				            onScrollEnd: function () {
				                if (pullDownEl.className.match('flip')) {
				                    pullDownEl.className = 'loading';
				                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
				                    refresh();
				                }
				                if (hasMoreData && pullUpEl.className.match('flip')) {
				                    pullUpEl.className = 'loading';
				                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
				                    nextPage();
				                }
				            }
				        });

				        $("#thelist").empty();
                pageresp(response);
				        myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)

				        if (hasMoreData) {
				            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
				        } else {
				            myScroll.maxScrollY = myScroll.maxScrollY;
				        }
				        maxScrollY = myScroll.maxScrollY;

				    };
				},
				"json");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = '无数据...';
        }

        function refresh() {
            page = 1;
            $.post(
				"<?php echo U('Web/Product/infolist');?>", {
				    "Page": page,
				    "catid": "<?php echo ($catid); ?>",
				    "keyword": "<?php echo I('get.keyword');?>"
				},
				function (response, status) {
				    if (status == "success") {
				        $("#thelist").empty();
				        myScroll.refresh();
				        if (response.length < PAGESIZE) {
				            hasMoreData = false;
				            $("#pullUp").hide();
				        } else {
				            hasMoreData = true;
				            $("#pullUp").show();
				        }
				        pageresp(response);
				        myScroll.refresh();
				        if (hasMoreData) {
				            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
				        } else {
				            myScroll.maxScrollY = myScroll.maxScrollY;
				        }
				        maxScrollY = myScroll.maxScrollY;
				    };
				},
				"json");
        }

        function nextPage() {
            page++;
            $.post(
				"<?php echo U('Web/Product/infolist');?>", {
				    "Page": page,
				    "catid": "<?php echo ($catid); ?>",
				    "keyword":"<?php echo I('get.keyword');?>"
				},
				function (response, status) {
				    if (status == "success") {
				        if (response.length < PAGESIZE) {
				            hasMoreData = false;
				            $("#pullUp").hide();
				        } else {
				            hasMoreData = true;
				            $("#pullUp").show();
				        }
				        pageresp(response);
				        myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)
				        if (hasMoreData) {
				            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
				        } else {
				            myScroll.maxScrollY = myScroll.maxScrollY;
				        }
				        maxScrollY = myScroll.maxScrollY;
				    };
				},
				"json");
        }
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="return" href="<?php echo U('Web/Query/index');?>" target="_self">
                </a>
        </div>
        <input id="htmlKeyword" name="htmlKeyword" type="text" value="<?php echo ($keyword); ?>" />
        <div class="r">
                <img alt="" src="/Public/Web/images/icon_search.png"  id="search"/>
        </div>
    </div>
    <div id="query" class="page_info">
        <div class="queryList" id="orderList">
            <div id="scroller">
                <div id="pullDown" class="idle">
                    <span class="pullDownIcon"></span>
                    <span class="pullDownLabel">下拉加载数据...</span>
                </div>
                <div id="thelist">
                </div>
                <div id="pullUp" class="idle">
                    <span class="pullUpIcon"></span>
                    <span class="pullUpLabel">上拉加载数据...</span>
                </div>
            </div>
        </div>
    </div>
    <div id="page_foot" class="page_foot">
    <img class="cartBox" alt="" src="/Public/Web/images/icon_dayuan.png" onclick="window.location.href='<?php echo U('Web/Cat/lists');?>';"/>
    <img class="cartImg" alt="" src="/Public/Web/images/icon_shopping_cart.png" onclick="window.location.href='<?php echo U('Web/Cat/lists');?>';"/>
    <span class="cartSz" onclick="window.location.href='<?php echo U('Web/Cat/lists');?>';"><?php echo ($cartcount); ?></span>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
      debug: false,
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone',
        'hideMenuItems'
      ]
  });
  wx.ready(function () {


  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareAppMessage({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {

      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','error');
      }
    });


  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareTimeline({
      title: '<?php echo ($share["content"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','error');
      }
    });


  // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口

    wx.onMenuShareQQ({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','error');
      }
    });

  
  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareWeibo({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','error');
      }
    });


  // 2.5 监听“分享到QZone”按钮点击、自定义分享内容及分享接口

    wx.onMenuShareQZone({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','error');
      }
    });
});

wx.error(function (res) {
  alert(res.errMsg);
});
function ajax_share(mid,sharetype,sharestatus){
    //$.ajax({
    //    type: "POST",
    //    url: "<?php echo U('Home/Index/ajax_share');?>",
    //    data: {'sharetype':sharetype,'sharestatus':sharestatus,'mid':mid},
    //    dataType: "json",
    //    success: function(data){
    //        if(sharestatus=='success'){
    //            window.location.href='/index.php/Index/order/mid/'+mid+'.html';
    //        }
                    
    //    }
    //});
}
</script>
    <script type="text/javascript">
        $("#thelist").on("click", ".goodsCountAdd", function () {
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) + 1);
            t.show();
            cat.show();
            $('.cartSz').show();

            var pnum = t.html();
            var pid = t.attr("id");
            $.post(
                "<?php echo U('Web/Cat/catupdate');?>", {
                    "num": pnum,
                    "pid": pid,
                },
                function (response, status) {
                    if (status == "success") {
                        console.log(response);
                        $('.cartSz').html(response);
                        if ($('.cartSz').html() == '') {
                            location.reload();
                        }
                    };
                },
                "json");
        });

        $("#thelist").on("click", ".goodsCountCut", function () {
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) - 1);
            if (parseInt(t.html()) < 0) {
                t.html(0);
            }
            if (parseInt(t.html()) == 0) {
                cat.hide();
                t.hide();
            }
            var pnum = t.html();
            var pid = t.attr("id");
            $.post(
                "<?php echo U('Web/Cat/catupdate');?>", {
                    "num": pnum,
                    "pid": pid,
                },
                function (response, status) {
                    if (status == "success") {
                      if (pnum == "0") {
        			            cat.hide();
        			            t.hide();
        			        }
                        if (pnum == "0" && $('.cartSz').html() == "0") {
                            $('.cartSz').hide();
                        }
                        else {
                            console.log(response);
                            if (response == null) {
                                $('.cartSz').hide();
                            } else {
                                $('.cartSz').html(response);
                                if ($('.cartSz').html() == '') {
                                    location.reload();
                                }
                            }
                        }
                    };
                },
                "json");
        });
    </script>
</body>
</html>
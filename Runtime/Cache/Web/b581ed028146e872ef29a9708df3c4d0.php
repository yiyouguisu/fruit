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
    <script type="text/javascript" src="/Public/Web/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/iscroll.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            $("#place").click(function () {
                location.href = "<?php echo U('Web/Public/modifyaddr');?>";
            })
            $("#otheradd").click(function () {
                location.href = "<?php echo U('Web/Public/modifyaddr');?>";
            });

            $("#classList ul li").eq(0).click();
            $("#classHead h2").html($("#classList ul").find('.hover').find('a').html());

            $("#search").click(function () {
                location.href = "<?php echo U('Web/Query/index');?>";
            })
            //if("<?php echo I('get.catid');?>" == ''){
            //    location.href = "<?php echo U('Web/Product/lists');?>?catid="+<?php echo ($firstcid); ?>;
            //}
        });
        wxglobal.SetSize(function () {
            $("#classList").height($(window).height() - 109 + 'px');
            $("#classHead").width($(window).width() - 91 + 'px');
            $("#goodsList").width($(window).width() - 91 + 'px');
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

        var template_all = '<div class="item">';
        template_all += '<div class="image"><a href="{productview_img}" target="_self"><img alt="" src="{thumb}" /></a></div>';
        template_all += '<div class="infos">';
        template_all += '   ';
        template_all += '   <a href="{productview_title}" target="_self"><div class="cp_title">{iconimg}{title}</div>';
        template_all += '   <div class="cp_marks">';
        template_all += '      {description}';
        template_all += '   </div>';
        template_all += '   <div class="cp_price">';
        template_all += '      <span class="priceNow">￥{nowprice}/{standard}{unit}</span>';
        template_all += '      <span class="priceOld">￥{oldprice}/{standard}{unit}</span>';
        template_all += '   </div></a>';
        template_all += '   <div class="cp_count" {isread}>';
        template_all += '        <span style="float:right; margin-right:10px;"><span class="goodsCountCut" {isCutdis}></span><label class="goodsCountLab" id="{goods_id}"  data-storeid="{storeid}" {isLabdis}>{cartnum}</label><span class="goodsCountAdd" "></span></span>';
        template_all += '   </div>';
        template_all += '   <div class="cp_count" {noread}>';
        template_all += '        <span style="float:right; margin-right:10px;">已过期</span>';
        template_all += '   </div>';
        template_all += '   <div class="cp_count" {noreads}>';
        template_all += '        <span style="float:right; margin-right:10px; color:red">正在补货中...</span>';
        template_all += '   </div>';

        template_all += ' </div>';
        template_all += '</div>';

        function pageresp(response) {
            $.each(response, function (key, value) {
                var template = template_all;
                template = template.replace('{thumb}', value.thumb);
                template = template.replace('{title}', value.title);
                template = template.replace('{description}', value.description);
                template = template.replace('{nowprice}', value.nowprice);
                template = template.replace('{standard}', value.standard);
                template = template.replace('{standard}', value.standard);
                template = template.replace('{unit}', value.unit);
                template = template.replace('{unit}', value.unit);
                template = template.replace('{oldprice}', value.oldprice);
                template = template.replace('{goods_id}', value.id);
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
                if (value.type == "4") {
                    template = template.replace('{iconimg}', '<img src="/Public/Web/images/orderStyleCz.png">');
                } else if (value.ishot == "1") {
                    template = template.replace('{iconimg}', '<img src="/Public/Web/images/orderStyleTh.png">');
                } else {
                    template = template.replace('{iconimg}', '');
                }

                template = template.replace('{cartnum}', value.catnum);
                template = template.replace('{productview_img}', "<?php echo ($goods_info_img); ?>");
                template = template.replace('{productview_title}', "<?php echo ($goods_info_title); ?>");
                template = template.replace('%7Bgoods_id%7D', value.id);
                template = template.replace('%7Bgoods_id%7D', value.id);
                template = template.replace('{storeid}', value.storeid);
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
				},
				function (response, status) {
				    if (status == "success") {
				        console.log(response);
				        $("#goodsList").show();
                        if(response==null){
                            $("#pullDown").hide();
                            $("#pullUp").hide();
                        }
				        if (response.length < PAGESIZE ) {
				            hasMoreData = false;
				            $("#pullUp").hide();
				        } else {
				            hasMoreData = true;
				            $("#pullUp").show();
				        }

				        myScroll = new iScroll('goodsList', {

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
            <a class="mylogo" href="javascript:" target="_self">
                <img alt="" src="/Public/Web/images/icon_logo.png" /></a>
        </div>
        <h1 id="place"><?php echo ($place); ?><a class="ondown" id="otheradd"></a></h1>
        <div class="r">
            <img alt="" src="/Public/Web/images/icon_search.png" id="search" />
        </div>
    </div>
    <div class="classList" id="classList">
        <ul>
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["id"] == $catid ): ?><li class="hover"><a href="<?php echo U('Web/Product/lists',array('catid'=>$vo['id']));?>" target="_self"><?php echo ($vo["catname"]); ?></a></li>
                <?php else: ?>
                    <li class="nomal"><a href="<?php echo U('Web/Product/lists',array('catid'=>$vo['id']));?>" target="_self"><?php echo ($vo["catname"]); ?></a></li><?php endif; ?>
        		<!--<li class="hover"><a href="<?php echo U('Web/Product/lists',array('catid'=>$vo['id']));?>" target="_self"><?php echo ($vo["catname"]); ?></a></li>--><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="classHead" id="classHead">
        <h2>优选水果</h2>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff">
        <div class="goodsList" id="goodsList">
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
       <ul>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Index/index');?>';"><img alt="" src="/Public/Web/images/icon_home_default.png" /><p>首页</p></li>
          <li class="hover" onclick="window.location.href='<?php echo U('Web/Product/lists');?>';"><img alt="" src="/Public/Web/images/icon_productsClass_click.png" /><p>分类</p></li>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Member/index');?>';"><img alt="" src="/Public/Web/images/icon_me_default.png" /><p>我的</p></li>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Cat/lists');?>';">
             <img class="cartBox" alt="" src="/Public/Web/images/icon_dayuan.png" />
             <img class="cartImg" alt="" src="/Public/Web/images/icon_shopping_cart.png" />
             <span class="cartSz"><?php echo ($cartcount); ?></span>
          </li>
       </ul>
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
</body>
<script type="text/javascript">
    $("#thelist").on("click", ".goodsCountAdd", function () {
        var uid='<?php echo ($uid); ?>';
        if(uid==''){
            alert("请先登录");
            window.location.href="<?php echo U('Web/Member/login');?>";
            return false;
        }
        var t = $(this).parent().find($('.goodsCountLab'));
        var cat = $(this).parent().find($('.goodsCountCut'));
        var catnum = $(".cartSz");
        t.html(parseInt(t.html()) + 1);
        t.show();
        cat.show();
        catnum.show();
        catnum.html(parseInt(catnum.html()) + 1);
        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        console.log(pnum);
        $.post(
			"<?php echo U('Web/Cat/catupdate');?>", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        //console.log(response);
			        if (response == 'stock')
			        {
			            alert('库存不足！');
			            t.html(parseInt(t.html()) - 1);
			            return false;
			        }
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
        if (parseInt(t.html()) == 0) {
            cat.hide();
            t.hide();
            //$(".cartSz").hide();
        }
        if (parseInt(t.html()) < 0) {
            t.html(0);
        }
        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        $.post(
			"<?php echo U('Web/Cat/catupdate');?>", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (pnum == "0") {
			            cat.hide();
			            t.hide();
			        }
			        if ($('.cartSz').html() == "0") {
			            $(".cartSz").hide();
			        }
			        else {
			            console.log(response);
			            if (response == null) {
			                $('.cartSz').html(0);
			                $(".cartSz").hide();
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
</html>
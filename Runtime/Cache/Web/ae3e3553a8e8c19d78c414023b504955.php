<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/swiper.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/swiper.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=61dG5IBV8LakyGZPhDNQAAT1DY9oFjRY"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
    <style type="text/css">
        /*内容*/
        .page_info {
            width: 100%;
            height: auto;
            overflow: visible;
            position:absolute;
            top:55px;
            padding-bottom:55px;
            z-index: 1;
        }
        /*底部*/
        .page_foot {
            width: 100%;
            height: 54px;
            color: #ffffff;
            background-color: #ffffff;
            text-align: center;
            position: fixed;
            left: 0px;
            bottom: 0px;
            z-index: 3;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            var mySwiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                lazyLoading: true,
                autoplay: 5000,
                slidesPerView: 1,
                paginationClickable: true,
                spaceBetween: 30,
                loop: true,
                onLazyImageLoad: function (swiper, slide, image) {
                }
            });
            $("#place").click(function () {
                location.href = "<?php echo U('Web/Public/modifyaddr');?>";
            })
            $("#otheradd").click(function () {
                location.href = "<?php echo U('Web/Public/modifyaddr');?>";
            });

            //      });
            if (supportsGeoLocation()) {
                //              alert("你的浏览器支持 GeoLocation.");
            } else {
                alert("不支持 GeoLocation.")
            }
            function supportsGeoLocation() {
                return !!navigator.geolocation;
            }
            function getLocation() {
                navigator.geolocation.getCurrentPosition(mapIt, locationError);
            }
            function mapIt(position) {
                console.log(position)
                var lon = position.coords.longitude;
                var lat = position.coords.latitude;
                    var storeid='<?php echo ($storeid); ?>';
                    if(storeid==''){
                        $.post(
                         "<?php echo U('Web/Index/getstore');?>", {
                             "lat": lat,
                             "lng": lon,
                         },
                         function (response, status) {
                             if (status == "success") {

                                 $("#place span").html(response.storename);

                                 if (response.storeid == '') {
                                     $("#place").html('选择店铺');
                                 } else {
                                    var id="<?php echo ($_GET['id']); ?>";
                                     if (id == "") {
                                         location.href = "<?php echo U('Web/Index/index');?>?id=" + response.storeid;
                                     } else {
                                         $(".indexNoShop span").html('亲，当前店铺卖空了哟~请选择其他店铺吧！');
                                     }
                                 }

                                 $(".indexNoShop span").html('亲，当前店铺卖空了哟~请选择其他店铺吧！');
                             };
                         },
                         "json");
                    }
                    
                //});
            }
            function locationError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }
            // 页面加载时执行getLocation函数
            window.onload = getLocation;
        });
        function bindcompany() {
            if (confirm('您还没有绑定企业帐号,确定要去绑定吗')) {
                location.href = '<?php echo U("Web/Company/bind");?>';
            }
        }

        function changeplace() {
            location.href = "<?php echo U('Web/Public/modifyaddr');?>";
        }

        function chosestore() {
            if (confirm('亲，当前没有商家呦～请选择其他店铺吧！')) {
                location.href = "<?php echo U('Web/Public/modifyaddr');?>";
            } else {
                return false;
            }
        }
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="mylogo" href="javascript:" target="_self">
                <img alt="" src="/Public/Web/images/icon_logo.png" /></a>
        </div>
        <h1 id="place"><span><?php echo ($place); ?></span><a class="ondown" id="otheradd"></a></h1>
        <?php if($place == '选择店铺'): ?><div class="r" style="top: 6px">
                <a class="search" onclick="chosestore()" target="_self">
                    <img alt="" src="/Public/Web/images/icon_search.png" />
                </a>
            </div>
        <?php else: ?>
            <div class="r" style="top: 6px">
                <a class="search" href="<?php echo U('Web/Query/index');?>" target="_self">
                    <img alt="" src="/Public/Web/images/icon_search.png" />
                </a>
            </div><?php endif; ?>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff;">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($adimglist)): $i = 0; $__LIST__ = $adimglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo[type]) == "1"): ?><div class="swiper-slide"><div class="flash-text"><span><?php echo ($vo["title"]); ?></span></div><a href="<?php echo ($vo["url"]); ?>"><img class="swiper-lazy" data-src="<?php echo ($vo["image"]); ?>" ></a></div><?php endif; ?>
                <?php if(($vo[type]) == "3"): ?><div class="swiper-slide"><div class="flash-text"><span><?php echo ($vo["title"]); ?></span></div><a href="<?php echo U('Web/Index/view',array('id'=>$vo['id']));?>"><img class="swiper-lazy" data-src="<?php echo ($vo["image"]); ?>" ></a></div><?php endif; ?>
                <?php if(($vo[type]) == "2"): ?><div class="swiper-slide"><div class="flash-text"><span><?php echo ($vo["title"]); ?></span></div><a href="<?php echo U('Web/Product/infoview',array('id'=>$vo['pid']));?>"><img class="swiper-lazy" data-src="<?php echo ($vo["image"]); ?>" ></a></div><?php endif; ?>
                 <!--<div class="swiper-slide"><img class="swiper-lazy" data-src="<?php echo ($vo["image"]); ?>"></div>--><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="swiper-pagination" style="text-align: right"></div>
        </div>
        <div class="indexMenus">
            <?php if($place == '选择店铺'): ?><ul>
                 <li onclick="chosestore()"><img alt="" src="/Public/Web/images/icon_group_buy.png" /><p>团购</p></li>
                 <li onclick="chosestore()"><img alt="" src="/Public/Web/images/icon_pre_order.png" /><p>预购</p></li>
                    <li onclick="chosestore()"><img alt="" src="/Public/Web/images/icon_prefecture.png" /><p>企业专区</p></li>
                 <li onclick="chosestore()"><img alt="" src="/Public/Web/images/icon_inquire.png" /><p>物流查询</p></li>
              </ul>
            <?php else: ?>
                  <ul>
                 <li onclick="window.location.href='<?php echo U('Web/Product/group');?>';  "><img alt="" src="/Public/Web/images/icon_group_buy.png" /><p>团购</p></li>
                 <li onclick="window.location.href='<?php echo U('Web/Product/reserve');?>';"><img alt="" src="/Public/Web/images/icon_pre_order.png" /><p>预购</p></li>
                  <?php if($userinfo['companyid'] == 0 ): ?><li onclick="bindcompany()"><img alt="" src="/Public/Web/images/icon_prefecture.png" /><p>企业专区</p></li>
                  <?php else: ?>
                    <li onclick="window.location.href='<?php echo U('Web/Product/company');?>';"><img alt="" src="/Public/Web/images/icon_prefecture.png" /><p>企业专区</p></li><?php endif; ?>
                 <li onclick="window.location.href='<?php echo U('Web/Order/logistics');?>'; "><img alt="" src="/Public/Web/images/icon_inquire.png" /><p>物流查询</p></li>
              </ul><?php endif; ?>
        </div>
        <div style="height: 20px; background-color: #f3f3f3">
            <?php if(($indeximglist) == ""): ?><div class="indexNoShop">
                    <img src="/Public/Web/images/icon_Stores.png" />
                   <span>亲，当前没有商家呦～请选择其他店铺吧！</span>
                   <button onclick="changeplace()">换店铺</button>
               </div><?php endif; ?>
        </div>
        <div class="indexTjian">
            <?php if(is_array($indeximglist)): $i = 0; $__LIST__ = $indeximglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><img style="display: block;width: 100%; margin-bottom:0px;    margin-top: 0px;" alt="" src="<?php echo ($vo1["extrathumb"]); ?>" onclick="window.location.href='<?php echo U('Web/Product/infoview',array('id'=>$vo1['id']));?>'"/><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div id="page_foot" class="page_foot">
       <ul>
          <li class="hover" onclick="window.location.href='<?php echo U('Web/Index/index');?>';"><img alt="" src="/Public/Web/images/icon_home_click.png" /><p>首页</p></li>
           <?php if($place == '选择店铺'): ?><li class="nomal" onclick="window.location.href='<?php echo U('Web/Product/pempty');?>';"><img alt="" src="/Public/Web/images/icon_productsClass_default.png" /><p>分类</p></li>
           <?php else: ?> 
                <li class="nomal" onclick="window.location.href='<?php echo U('Web/Product/lists');?>';"><img alt="" src="/Public/Web/images/icon_productsClass_default.png" /><p>分类</p></li><?php endif; ?>
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
</html>
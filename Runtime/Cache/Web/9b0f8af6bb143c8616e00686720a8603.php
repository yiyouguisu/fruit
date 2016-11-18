<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script>
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            $("#qiandao").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?php echo U('Web/Member/sign');?>",
                    dataType: "json",
                    success: function (data) {
                        alert(data.msg);
                        if (data.code == '200') {
                            location.reload();
                        }
                    }
                });
            });
        })
    </script>
</head>
<body>
    <div id="page_user" class="page_user">
        <div class="headmain">
            <img alt="" src="/Public/Web/images/mySelfBg.png" class="backImg" />
            <div class="qiandao">
                <!--<?php if(($qiandao) == "0"): ?><img alt="" src="/Public/Web/images/icon_registration.png" id="qiandao" /><?php endif; ?>
                <?php if(($qiandao) == "1"): ?><img alt="" src="/Public/Web/images/icon_registration_success.png"  /><?php endif; ?>-->
                <img alt="" src="/Public/Web/images/icon_registration.png" id="qiandao" />
                <!--签到-->
            </div>
            <div class="setting" onclick="window.location.href='<?php echo U('Web/Member/setup');?>';">
                <img alt="" src="/Public/Web/images/icon_set_up.png" /></div>
            <div class="jbInfos" onclick="window.location.href='<?php echo U('Web/Member/view');?>';"></div>
            <div class="yhPhoto" onclick="window.location.href='<?php echo U('Web/Member/view');?>';">
                <div class="headimg"><img alt="" src="<?php echo ($data["head"]); ?>" /></div>
                <div style="font-size:18px"><?php echo ($data["nickname"]); ?></div>
                <div><?php echo ($data["phone"]); ?></div>
                <span><?php echo ($data["level"]); ?></span>
            </div>
            <div class="footDiv">
                <ul>
                    <li onclick="window.location.href='<?php echo U('Web/Point/index');?>';">
                        <div><?php echo ($integral_total); ?></div>
                        <div>积分</div>
                    </li>
                    <li onclick="window.location.href='<?php echo U('Web/Wallet/index');?>';">
                        <div><?php echo ($account_total); ?></div>
                        <div>钱包</div>
                    </li>
                    <li onclick="window.location.href='<?php echo U('Web/Coupons/index');?>';">
                        <div><?php echo ($coupons_total); ?>张</div>
                        <div>优惠券</div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="userMenuList" style="background-color:#ffffff">
            <ul>
                <li onclick="window.location.href='<?php echo U('Web/Order/index');?>';">
                    <img class="icon" alt="" src="/Public/Web/images/icon_order_form.png" /><p>我的订单</p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                </li>
                <li onclick="window.location.href='<?php echo U('Web/Address/index');?>';">
                    <img class="icon" alt="" src="/Public/Web/images/icon_address.png" /><p>我的收货地址</p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                </li>
                <li>
                    <img class="icon" alt="" src="/Public/Web/images/icon_member.png" /><p>我的会员等级</p>
                    <!--<img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />--><span style="float: right; color: #ff0000; font-size: 15px; margin-top:10px;margin-right:10px;"><?php echo ($data["level"]); ?></span>
                    <!--<img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />-->
                </li>
                <li onclick="window.location.href='<?php echo U('Web/Focus/index');?>';">
                    <img class="icon" alt="" src="/Public/Web/images/icon_attention.png" /><p>我的关注</p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                </li>
                <li onclick="window.location.href='<?php echo U('Web/Message/index');?>';">
                    <img class="icon" alt="" src="/Public/Web/images/icon_information.png" /><p>消息中心</p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                    <?php if($wordcount != '0'): ?><span style="float: right; color: #ff0000; font-size: 15px; margin-top:10px; margin-right:5px">有新消息</span><?php endif; ?>
                    
                </li>
                <li onclick="window.location.href='<?php echo U('Web/Invoic/index');?>';" style=" border-bottom:0px">
                    <img class="icon" alt="" src="/Public/Web/images/icon_invoice.png" /><p>我的发票</p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                </li>
                <li style="height:20px; background-color:#f3f3f3; border-bottom:0px">
                </li>
                <li style=" border-bottom:0px">
                    <img class="icon" alt="" src="/Public/Web/images/icon_phone.png" /><p>客服电话<a href="tel:400-018-3358" target="_self">400-018-3358</a></p>
                    <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                </li>
                <li style="height:20px; background-color:#f3f3f3;border-bottom:0px">
            </ul>
        </div>
    </div>
    <div id="page_foot" class="page_foot">
       <ul>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Index/index');?>';"><img alt="" src="/Public/Web/images/icon_home_default.png" /><p>首页</p></li>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Product/lists');?>'"><img alt="" src="/Public/Web/images/icon_productsClass_default.png" /><p>分类</p></li>
          <li class="hover" onclick="window.location.href='<?php echo U('Web/Member/index');?>';"><img alt="" src="/Public/Web/images/icon_me_click.png" /><p>我的</p></li>
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
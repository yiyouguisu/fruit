<div id="page_foot" class="page_foot">
       <ul>
          <li class="nomal" onclick="window.location.href='{:U('Web/Index/index')}';"><img alt="" src="__IMG__/icon_home_default.png" /><p>首页</p></li>
          <li class="hover" onclick="window.location.href='{:U('Web/Product/lists')}';"><img alt="" src="__IMG__/icon_productsClass_click.png" /><p>分类</p></li>
          <li class="nomal" onclick="window.location.href='{:U('Web/Member/index')}';"><img alt="" src="__IMG__/icon_me_default.png" /><p>我的</p></li>
          <li class="nomal" onclick="window.location.href='{:U('Web/Cat/lists')}';">
             <img class="cartBox" alt="" src="__IMG__/icon_dayuan.png" />
             <img class="cartImg" alt="" src="__IMG__/icon_shopping_cart.png" />
             <span class="cartSz">{$cartcount}</span>
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
      title: '{$share.title}',
      desc: '{$share.content}',
      link: '{$share.link}',
      imgUrl: '{$share.image}',
      trigger: function (res) {

      },
      success: function (res) {
        alert('已分享');
        ajax_share('{$share.id}','ShareAppMessage','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('{$share.id}','ShareAppMessage','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('{$share.id}','ShareAppMessage','error');
      }
    });


  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareTimeline({
      title: '{$share.content}',
      desc: '{$share.content}',
      link: '{$share.link}',
      imgUrl: '{$share.image}',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
      },
      success: function (res) {
        alert('已分享');
        ajax_share('{$share.id}','ShareTimeline','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('{$share.id}','ShareTimeline','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('{$share.id}','ShareTimeline','error');
      }
    });


  // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口

    wx.onMenuShareQQ({
      title: '{$share.title}',
      desc: '{$share.content}',
      link: '{$share.link}',
      imgUrl: '{$share.image}',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('{$share.id}','ShareQQ','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('{$share.id}','ShareQQ','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('{$share.id}','ShareQQ','error');
      }
    });

  
  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareWeibo({
      title: '{$share.title}',
      desc: '{$share.content}',
      link: '{$share.link}',
      imgUrl: '{$share.image}',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('{$share.id}','ShareWeibo','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('{$share.id}','ShareWeibo','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('{$share.id}','ShareWeibo','error');
      }
    });


  // 2.5 监听“分享到QZone”按钮点击、自定义分享内容及分享接口

    wx.onMenuShareQZone({
      title: '{$share.title}',
      desc: '{$share.content}',
      link: '{$share.link}',
      imgUrl: '{$share.image}',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('{$share.id}','ShareQZone','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('{$share.id}','ShareQZone','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('{$share.id}','ShareQZone','error');
      }
    });
});

wx.error(function (res) {
  alert(res.errMsg);
});
function ajax_share(mid,sharetype,sharestatus){
    //$.ajax({
    //    type: "POST",
    //    url: "{:U('Home/Index/ajax_share')}",
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
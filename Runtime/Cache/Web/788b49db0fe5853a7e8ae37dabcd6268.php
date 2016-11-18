<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/swiper.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/Public/Web/js/swiper.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }

            //轮播图
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
        });
    </script>
</head>
<body>
    <div id="goodsItem" class="goodsItem">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($backimglist)): $i = 0; $__LIST__ = $backimglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img class="swiper-lazy" data-src="<?php echo ($vo); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="swiper-pagination" ></div>
        </div>
        <div class="cp_title">
            <div class="title"><?php echo ($data["title"]); ?></div>
            <?php if(($data[type]) == "0"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleQy.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "1"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleTh.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "2"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleTg.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "3"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleYg.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "4"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleCz.png" /></div><?php endif; ?>
        </div>
        <div class="cp_count">
            <div class="marks">
                <p><?php echo ($data["description"]); ?></p>
            </div>
        </div>
        <div class="cp_price">
            <span class="priceNow">￥<?php echo ($data["nowprice"]); ?>/<?php echo ($data["standard"]); echo ($data["unit"]); ?></span>
            <span class="priceOld">￥<?php echo ($data["oldprice"]); ?>/<?php echo ($data["standard"]); echo ($data["unit"]); ?></span>
            <br />
            <?php if(($data[type]) == "3"): if($data["issellover"] == '1'): ?><span class="saletime">已过期</span>
                <?php else: ?>
                    <span class="saletime">出售时间: <?php echo ($data["selltime"]); ?></span><?php endif; endif; ?>
            <?php if(($data[type]) == "2"): if($data["isexpireover"] == '1'): ?><span class="saletime">已过期</span>
                <?php else: ?>
                    <span class="saletime">结束时间: <?php echo ($data["expiretime"]); ?></span><?php endif; endif; ?>
        </div>
        <div class="cp_marks">
            <span class="title">品　　牌：</span><span class="marks"><?php echo ($data["brand"]); ?></span>
        </div>
        <div class="cp_marks">
            <span class="title">产品规格：</span><span class="marks"><?php echo ($data["standard"]); echo ($data["unit"]); ?></span>
        </div>
        <div class="cp_marks">
            <span class="title">商品评价：</span>
            <?php if(($isdiscuss) == "1"): ?><a href="<?php echo U('Web/Product/discuss',array('id'=>$data['id']));?>"><img src="/Public/Web/images/icon_arrow.png" style="float:right;padding-left:10px" /></a><?php endif; ?>
            <div class="mystar">
                <div style="font-size: 12px; color: #808080; text-align: center"><?php echo ($data["percent"]); ?>%果友给了</div>
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
            </div>
        </div>
        <div class="cp_marks">
            <span class="title">图文详情：</span>
        </div>
        <div class="cp_xqing">
            <?php if(is_array($imglist)): $i = 0; $__LIST__ = $imglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bgvo): $mod = ($i % 2 );++$i;?><img alt="" src="<?php echo ($bgvo); ?>" /><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div id="page_foot" class="page_foot">
       <ul>
          <li class="share"><img alt="" src="/Public/Web/images/login_logo.png" /></li>
          <li class="share" style="width:50%"><p>下载蔬果先生App,立享更多细致服务！</p></li>
          <li class="share" style="margin-top:15px; text-align:center; " onclick="window.location.href='<?php echo U('Web/Member/invitecode',array('uid'=>I('get.uid')));?>';">
             <span>立即体验</span>
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
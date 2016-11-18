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
    <script type="text/javascript">
        $(function () {
            $("#download").click(function () {
                if (isWeiXin()) {
                    alert('点击微信右上角，选择“在浏览器中打开”就可以下载');
                } else {
                    location.href = '<?php echo ($downurl); ?>';
                }
            })

            $("#page_info").click(function () {
                //location.href = "<?php echo U('Web/Member/index');?>";
            })
        });
        function isWeiXin() {
            var ua = window.navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                return true;
            } else {
                return false;
            }
        }
        
    </script>
</head>
<body>
    <!--<div id="page_head" class="page_head">
        <div class="l">
            <a class="return" href="javascript:history.go(-1)" target="_self"></a>
        </div>
        <h1>邀请码</h1>
    </div>-->
    <div id="page_info" class="page_info" style="background-image: url('/Public/Web/images/invitebg.png'); background-size: 100% auto" >
        <div class="invitecode">
            <div>下载蔬果先生App即可获得<span>15元</span>优惠券</div>
            <div>新用户注册时需输入推荐码</br>推荐ID：<?php echo ($data["tuijiancode"]); ?></div>
        </div>
        <div class="appdown">
            <img src="/Public/Web/images/click_to_download.png" id="download" />
        </div>
    </div>
</body>
    
</html>
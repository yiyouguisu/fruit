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
</head>
<body>
    <div class="login">
        <form action="<?php echo U('Web/Member/login');?>" method="post" onsubmit="return checkform();">
                <script type="text/javascript">
                    function checkform() {
                        var username = $("input[name='username']").val();
                        var password = $("input[name='password']").val();
                        if (username == '') {
                            alert("请输入账号或手机号");
                            $("input[name='username']").focus();
                            return false;
                        } else if (password == '') {
                            alert("请输入密码");
                            $("input[name='password']").focus();
                            return false;
                        } else {
                            return true;
                        }
                    }
                </script>
            <img alt="" src="/Public/Web/images/login_bg.png" class="backImg" />
            <img alt="" src="/Public/Web/images/login_logo.png" class="logoImg" />
            <div class="infoDiv">
                <div>
                    <img src="/Public/Web/images/login_username.png" style="left:20px ; position:absolute; margin-top:10px" />
                    <input id="username" name="username" type="text" class="username" placeholder="请输入账号或手机号"  value="" />
                </div>
                <div >
                    <img src="/Public/Web/images/login_password.png" style="left:20px ; position:absolute; margin-top:10px" />
                    <input id="password" name="password" type="password" class="password" placeholder="请输入密码"  value="" />
                </div>
                <div>
                    <input id="subbtn" type="submit" value="登录" class="toolSubmit" />
                </div>
                <div>
                    <input id="subbtn1" type="button" value="微信登录" class="toolSubmit" onclick="window.location.href='<?php echo U('Member/wxlogin');?>'" />
                </div>
                <div>
                    <a href="<?php echo U('Web/Member/reg');?>" target="_self" style="float: left; color: #ffffff;">注册账号<?php echo ($code); ?></a>
                    <a href="<?php echo U('Web/Member/forgot');?>" target="_self" style="float: right; color: #ffffff;">忘记密码</a>
                </div>
               <div style="text-align: center;color: #fff;margin-top: 68px;">
                    <span style="font-weight:bold">温馨提示：</span>
                    <span>首次注册赠送优惠券的活动，只有通过APP注册才能获得，微信端不参与此项活动</span>
                    <div class="clear"></div>
               </div> 
            </div>
        </form>
    </div>
</body>
</html>
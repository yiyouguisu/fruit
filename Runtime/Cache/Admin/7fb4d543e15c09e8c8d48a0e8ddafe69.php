<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
    <head>
        <meta charset="GBK" />
        <title>网站管理系统后台</title>
        <script language="javascript" type="text/javascript" src="/Public/Admin/js/jquery.js"></script>
<link rel="stylesheet" href="/Public/Admin/css/jquery.validator.css">
        <script type="text/javascript" src="/Public/Admin/js/jquery.validator.js"></script>
        <script type="text/javascript" src="/Public/Admin/js/zh_CN.js"></script>
        <link href="/Public/Admin/css/admin_login.css?v20130227" rel="stylesheet" />
        
         <script>
$(document).ready(function(){
var verifyimg = $(".verifyimg").attr("src");
            $(".reloadverify").click(function(){
                if( verifyimg.indexOf('?')>0){
                    $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                }else{
                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
                }
            });
			});
                     
</script>

    </head>
    <body>
        <div class="wrap">
            <h1><a href="">后台管理中心</a></h1>
             <form method="post"  action="<?php echo U('Admin/Public/login');?>">
                <div class="login">
                    <ul>
                        <li>
                            <input class="input"  id="username" name="username"type="text"  title="用户名" data-rule="required;username"  placeholder="用户名" />
                            <span class="msg-box n-right" style="position:absolute; left: 248px; top: 12px; " for="username"></span>
                        </li>
                        <li>
                            <input class="input" id="password" name="password" type="password"  title="密码"  data-rule="required;password" placeholder="密码"/>
                            <span class="msg-box n-right" style="position:absolute;left: 248px; top: 12px;" for="password"></span>
                        </li>
                         <li>
                            <input class="input" id="verify" name="verify" type="text" style="width:130px;"  title="密码" data-ok=" " placeholder="验证码" data-tip="输入验证码！" title="验证码" data-rule="required;text;remote[<?php echo U('Admin/Public/check_verify');?>]" />
                            <div class="yanzhengma_box" id="verifyshow">   <img class="verifyimg reloadverify" style=" cursor: pointer;" align="right"  src="<?php echo U('public/verify');?>" title="点击刷新"> </div>
                            <span class="msg-box n-right" style="position:absolute;left: 248px; top: 12px;" for="verify"></span>
                        </li>
                       
                    </ul>
                     <button type="submit" class="btn" id="subbtn">登录</button>
                   
                </div>
                </form>
        </div>

    </body>
</html>
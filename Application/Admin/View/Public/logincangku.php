<!doctype html>
<html>
    <head>
        <meta charset="GBK" />
        <title>网站管理系统后台</title>
        <script language="javascript" type="text/javascript" src="__JS__/jquery.js"></script>
<link rel="stylesheet" href="__CSS__/jquery.validator.css">
        <script type="text/javascript" src="__JS__/jquery.validator.js"></script>
        <script type="text/javascript" src="__JS__/zh_CN.js"></script>
        <link href="__CSS__/admin_login.css?v20130227" rel="stylesheet" />
        
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
             <form method="post"  action="{:U('Admin/Public/logincangku')}">
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
                            <input class="input" id="verify" name="verify" type="text" style="width:130px;"  title="密码" data-ok=" " placeholder="验证码" data-tip="输入验证码！" title="验证码" data-rule="required;text;remote[{:U('Admin/Public/check_verify')}]" />
                            <div class="yanzhengma_box" id="verifyshow">   <img class="verifyimg reloadverify" style=" cursor: pointer;" align="right"  src="{:U('public/verify')}" title="点击刷新"> </div>
                            <span class="msg-box n-right" style="position:absolute;left: 248px; top: 12px;" for="verify"></span>
                        </li>
                       
                    </ul>
                     <button type="submit" class="btn" id="subbtn">登录</button>
                   
                </div>
                </form>
        </div>

    </body>
</html>

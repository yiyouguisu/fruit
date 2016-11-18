<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/weixin.jquery.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="return" href="{:U('Web/Member/login')}" target="_self">
            </a>
        </div>
        <h1>找回密码</h1>
    </div>
    <form action="{:U('Web/Member/forgot')}" method="post" onsubmit="return checkform();">
        <script type="text/javascript">
            var InterValObj; //timer变量，控制时间
            var count = 60; //间隔函数，1秒执行
            var curCount;//当前剩余秒数
            function sendMessage() {
                var phone = $('#phone').val();

                if (phone == '') {
                    alert("手机号码不能为空");
                    $("#phone").focus();
                    return false;
                } else {
                    if (!/^1[3|4|5|7|8][0-9]{9}$/.test(phone)) {
                        alert("手机号码格式不正确");
                        $("#phone").focus();
                        return false;
                    } else {
                        curCount = count;
                        $("#btnSendCode").attr("disabled", "true");
                        $("#btnSendCode").val("重新发送(" + curCount + ")");
                        InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        $.ajax({
                            type: "GET", //用POST方式传输
                            dataType: "JSON", //数据格式:JSON
                            url: "{:U('Web/Public/sendchecknum_forgot')}", //目标地址
                            data: { "phone": phone },
                            success: function (status) {
                                // alert(status.phone);
                                console.log(status);
                            }
                        });

                    }

                }

            }
            //timer处理函数
            function SetRemainTime() {
                if (curCount == 0) {
                    window.clearInterval(InterValObj);//停止计时器
                    $("#btnSendCode").removeAttr("disabled");//启用按钮
                    $("#btnSendCode").val("重新发送");
                }
                else {
                    curCount--;
                    $("#btnSendCode").val("重新发送(" + curCount + ")");
                }
            }
            function checkform() {
                var phone = $('#phone').val();
                var telverify = $("input[name='telverify']").val();
                var password = $("input[name='password']").val();
                var pwdconfirm = $("input[name='pwdconfirm']").val();
                if (phone == '') {
                    alert("手机号码不能为空");
                    $("#phone").focus();
                    return false;
                } else if (!/^1[3|4|5|7|8][0-9]{9}$/.test(phone)) {
                    alert("手机号码格式不正确");
                    $("#phone").focus();
                    return false;
                } else if (telverify == '') {
                    alert("验证码不能为空");
                    $("input[name='telverify']").focus();
                    return false;
                } else if (password == '') {
                    alert("密码不能为空");
                    $("input[name='password']").focus();
                    return false;
                } else if (pwdconfirm == '') {
                    alert("确认密码不能为空");
                    $("input[name='pwdconfirm']").focus();
                    return false;
                } else if (pwdconfirm != password) {
                    alert("两次密码不一样");
                    $("input[name='pwdconfirm']").focus();
                    return false;
                } else {
                    return true;
                }

            }
        </script>
        <div id="page_info" class="page_info">
            <div class="nrongDiv">
                <input id="phone" name="phone" type="text" class="zhuce_input" placeholder="请输入手机号码" />
            </div>
            <div class="nrongDiv" style="margin-top: 20px">
                <input id="telverify" name="telverify" type="text" class="zhuce_input" placeholder="请输入验证码" />
                <input id="btnSendCode" type="button" class="zhuce_yanzm"  onclick="sendMessage()" value="获取验证码" />
            </div>
            <div class="nrongDiv" style="margin-top: 20px">
                <input id="password" name="password" type="password" class="zhuce_input" placeholder="请输入新密码" />
            </div>
            <div class="nrongDiv" style="margin-top: 20px">
                <input id="pwdconfirm" name="pwdconfirm" type="password" class="zhuce_input" placeholder="请输入确认密码" />
            </div>
            <div class="nrongDiv" style="margin-top: 20px">
                <input id="regsubmit" type="submit" value="完成" class="toolSubmit" />
            </div>
        </div>
    </form>
</body>
</html>

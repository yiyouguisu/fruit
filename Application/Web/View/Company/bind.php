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
    <script type="text/javascript" src="__JS__/jquery.validate.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#toolSubmit").click(function () {
                if ($("#htmlCompanyName").val() == '') {
                    alert('请输入企业代码！');
                    return false;
                }
                if ($("#htmlContactName").val() == '') {
                    alert('请输入申请人姓名！');
                    return false;
                }
                if ($("#telphone").val() == '') {
                    alert('请输入申请人手机！');
                    return false;
                }
            })
        });
    </script>
</head>
<body>
    <form method="post" action="{:U('Web/Company/bind')}">
        <div id="page_head" class="page_head">
            <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
            <h1>绑定企业账号</h1>
        </div>
        <div id="page_info" class="page_info" style="background-color: #ffffff;padding-top: 0px;">
            <div class="inputDiv">
                <div class="title">企业代码</div>
                <div class="input">
                    <input id="htmlCompanyName" name="companyid" type="text" required />
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">申请人姓名</div>
                <div class="input">
                    <input id="htmlContactName" name="name" type="text" required />
                </div>
            </div>
            <div class="inputDiv" style="border: 0">
                <div class="title">申请人手机</div>
                <div class="input">
                    <input id="telphone" name="phone" type="text" required />
                </div>
            </div>
            <div style="height: 60px; background-color: #f3f3f3"></div>
            <div class="nrongDiv" style="margin-top: 0px;background-color: #f3f3f3;height: 60px;">
                <input id="toolSubmit" name="toolSubmit" type="submit" value="提交" class="toolSubmit" />
            </div>
            <div style="height: 20px; background-color: #f3f3f3"></div>
            <div class="nrongDiv" style="background-color:#f3f3f3; margin:0; text-align:center;height:auto;border-radius:5px" onclick="window.location.href='Apply.html';">
                <span style="background-color:#ffffff;padding:10px 20px 10px 20px;color:#69a460">申请成为企业用户，获得企业代码</span>
            </div>
            <div style="height: 600px; background-color: #f3f3f3"></div>
        </div>
    </form>
</body>
</html>

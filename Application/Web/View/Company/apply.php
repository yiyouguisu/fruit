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
                    alert('请输入公司名称！');
                    return false;
                }
                if ($("#htmlContactName").val() == '') {
                    alert('请输入公司名称！');
                    return false;
                }
                if ($("#htmlCompanyName").val() == '') {
                    alert('请输入您的姓名！');
                    return false;
                }
                if ($("#email").val() == '') {
                    alert('请输入您的邮箱！');
                    return false;
                }
                if ($("#contact").val() == '') {
                    alert('请输入联系电话！');
                    return false;
                }
                if ($("#content").val() == '') {
                    alert('请输入合作意向！');
                    return false;
                }
            })
        });
    </script>
</head>
<body>
    <form method="post" action="{:U('Web/Company/apply')}">
        <div id="page_head" class="page_head">
            <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
            <h1>申请企业账号</h1>
        </div>
        <div id="page_info" class="page_info" style="background-color:#ffffff;padding-top: 0px;">
            <div class="nrongDiv" style="text-align:center;padding-bottom:10px;background-color: #f3f3f3;margin-top:0px;padding-top:10px">请填写下列信息，我们的工作人员会尽快与您联系</div>
            <div class="inputDiv">
                <div class="title">公司名称</div>
                <div class="input">
                    <input id="htmlCompanyName" name="company" type="text" required />
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">您的姓名</div>
                <div class="input">
                    <input id="htmlContactName" name="username" type="text" required />
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">您的邮箱</div>
                <div class="input">
                    <input id="email" name="email" type="text"  required/>
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">联系电话</div>
                <div class="input">
                    <input id="contact" name="contact" type="text" required/>
                </div>
            </div>
            <div class="inputDiv" style="border:0">
                <div class="title">合作意向</div>
                <div class="input">
                    <input id="content" name="content" type="text" required/>
                </div>
            </div>
            <div style="height: 60px; background-color: #f3f3f3"></div>
            <div class="nrongDiv" style="margin-top: 0px;background-color: #f3f3f3">
                <input id="toolSubmit" name="toolSubmit" type="submit" value="提交" class="toolSubmit" />
            </div>
            <div style="height: 120px; background-color: #f3f3f3"></div>
        </div>
    </form>
</body>
</html>

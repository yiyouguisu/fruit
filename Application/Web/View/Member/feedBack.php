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
    <form method="post" action="{:U('Web/Member/feedBack')}">
        <div id="page_head" class="page_head">
            <div class="l">
                <a class="return" href="setup.html" target="_self"></a>
            </div>
            <h1>意见反馈</h1>
        </div>
        <div id="page_info" class="page_info">
            <div class="nrongDiv">
                <textarea id="htmlContent" name="content" rows="10" cols="10" style="width: 100%; height: 150px; line-height: 16px; font-size: 13px; color: #000000; border: 1px #dddddd solid;"></textarea>
            </div>
            <div class="nrongDiv">
                <input id="toolSubmit" name="toolSubmit" type="submit" value="提交" class="toolSubmit" />
            </div>
        </div>
    </form>
</body>
</html>

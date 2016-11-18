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
    <style>
        .page_info{margin-top:55px}
    </style>
</head>
<body>
    <form method="post" action="{:U('Web/Member/feedBack')}">
        <div id="page_head" class="page_head">
            <div class="l">
                <a class="return" href="javascript:history.go(-1)" target="_self"></a>
            </div>
            <h1>关于蔬果先生</h1>
        </div>
        <div id="page_info" class="page_info">
            {$data}
        </div>
    </form>
</body>
</html>

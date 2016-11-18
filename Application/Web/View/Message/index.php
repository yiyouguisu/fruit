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
    <script type="text/javascript">
        $(function () {
            if ($("#wordcount").html() == '0') {
                $("#wordcount").hide();
            }
        })
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
       <h1>我的消息</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color:#f3f3f3;">
       <div class="messageType">
          <div class="item" onclick="window.location.href='{:U('Web/Message/wordsview')}';">
             <div class="image">
                <img alt="" src="__IMG__/icon_messageTzxx.png" /><label id="wordcount">{$wordcount}</label>
             </div>
             <div class="infos">
                <div class="title">通知消息</div>
                <div class="marks">{$words.title}</div>
             </div>
          </div>
          <div class="item" onclick="window.location.href='{:U('Web/Message/imageview')}';">
             <div class="image">
                <img alt="" src="__IMG__/icon_messageYhcx.png" /><!--<label>2</label>-->
             </div>
             <div class="infos">
                <div class="title">优惠促销</div>
                <div class="marks">{$imgview.title}</div>
             </div>
          </div>
       </div>
    </div>
</body>
</html>
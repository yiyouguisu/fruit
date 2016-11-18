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
        img{
            width:100%;
            height:auto;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
       <h1>{$data.title}</h1>
       <!--<div class="r"><a id="toolDelete" class="delete" href="javascript:" target="_self"></a></div>-->
    </div>
    <div id="page_info" class="page_info" style="background-color:#f3f3f3;">
       <!--<div class="nrongDiv" style="color:#666666; font-size:18px;">
          {$data.title}
       </div>
       <div class="nrongDiv" style="color:#999999; font-size:15px;">
          {$data.updatetime}
       </div>-->
       <div class="nrongDiv" style="color:#999999; font-size:15px;">
          {$data.content}
       </div>
    </div>
</body>
</html>
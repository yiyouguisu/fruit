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
    <div id="page_info" class="page_info" style="background-color:#f3f3f3;">
       <div class="orderStatus">
          <ul>
          <volist name="list" id="vo">
          		<if condition="$key eq (count($list)-1)">
          			<li><span class="new"></span>{$vo.title}{$vo.sendtime}</li>
          		<else />
          			<li><span class="old"></span>{$vo.title}{$vo.sendtime}</li>
          		</if>
          </volist>
          </ul>
       </div>
    </div>
</body>
</html>
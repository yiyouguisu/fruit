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
    <style type="text/css">
        .invoiceList{
            margin-top:65px;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
        <h1>我的发票</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div class="invoiceList">
            <ul class="invoiceTabs">
                <li class="nomal"><a href="{:U('Web/Invoic/index')}" target="_self">未申请</a></li>
                <li class="hover"><a href="{:U('Web/Invoic/isalready')}" target="_self">已申请</a></li>
            </ul>
            <volist name="list" id="vo">
       		<div class="item">
             <div class="infos">
                <div class="price">订单金额：<label>￥{$vo.money}</label></div>
                <div class="order">订单编号：<label>{$vo.orderid}</label></div>
             </div>
          </div>
       </volist>
        </div>
    </div>
</body>
</html>

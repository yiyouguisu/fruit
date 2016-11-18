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
    <script type="text/javascript" src="/JsPlug/layerMob/layer.m.js"></script>
    <style type="text/css">
        /*内容*/
        .page_info {
            width: 100%;
            height: auto;
            overflow: auto;
            position: absolute;
            left: 0px;
            top: 55px;
            z-index: 1;
        }
        /*底部*/
        .page_foot {
            width: 100%;
            height: 54px;
            color: #ffffff;
            background-color: #ffffff;
            text-align: center;
            position: fixed;
            left: 0px;
            bottom: 0px;
            z-index: 3;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
        <h1>我的钱包</h1>
        <div class="r" style="top: 6px"><a id="toolJpoint" class="wenzhi" href="{:U('Web/Wallet/point')}" target="_self">使用积分充值</a></div>
    </div>
    <div id="page_info" class="page_info">
        <div class="walletHead">
            <img alt="" src="__IMG__/walletBg.png" class="backImg" />
            <div class="money">
                <label class="count">{$total}</label>
                <label class="mtips">元</label>
            </div>
            <div class="mycur">当前余额</div>
        </div>
        <ul class="walletTabs">
            <li class="hover"><a href="{:U('Web/Wallet/index')}" target="_self">钱包记录</a></li>
            <li class="nomal"><a href="{:U('Web/Wallet/recharge')}" target="_self">立即充值</a></li>
        </ul>
        <div class="walletQbjl">
            <ul>
                <volist name="list" id="vo">
          		<li>
	                <div class="title"><span>{$vo.remark}</span><span><eq name="vo['dcflag']" value="1">+</eq><eq name="vo[dcflag]" value="2">-</eq>{$vo.money}元</span></div>
	                <div class="marks"><span style="float:left">{$vo.addtime}</span><eq name="vo['status']" value="1"><!--<span>交易成功</span>--></eq><eq name="vo['status']" value="0"><span>交易失败</span></eq></div>
             	</li>
          	</volist>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        $("#toolJpoint").bind("click", function () {
            wxglobal.OpenBox('积分兑换钱包', '/Myself/wallet_Point.html', $(window).width() * 0.9, 145);
        });
    </script>
</body>
</html>

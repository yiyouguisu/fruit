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
    <script type="text/javascript" src="__JS__/pingpp.js"></script>
    <script type="text/javascript">
        var charge = {$charge};
        //alert({$orderid});
        var url = '{:U("Web/Wallet/index")}';
        pingpp.createPayment(charge, function (result, error) {
            //alert(result);
            if (result == "success") {
                // 只有微信公众账号 wx_pub 支付成功的结果会在这里返回，其他的 wap 支付结果都是在 extra 中对应的 URL 跳转。
                //alert('支付成功');
                location.href=url;
            }else if (result == "fail") {
                // charge 不正确或者微信公众账号支付失败时会在此处返回
                //alert('支付失败');
                location.href=url;
            }else if (result == "cancel") {
                // 微信公众账号支付取消支付
                //alert('支付取消');
                location.href=url;
            }
        });
        //alert(1);
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <!-- <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>-->
        <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
        <h1>支付</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div id="charge" style="display: none"></div>
    </div>
</body>
</html>

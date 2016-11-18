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
            $("#htmlSubmit").click(function () {
                var money = $("#htmlPoint").val();
                if ((/(^[1-9]\d*$)/.test(money))) {
                    money = parseFloat(money) / 100;
                    $.ajax({
                        type: "GET",
                        url: "{:U('Web/Wallet/recharge_integral')}",
                        data: { 'money': money },
                        dataType: "json",
                        success: function (data) {
                            //console.log(data);
                            if (data.code == '200') {
                                alert('兑换成功');
                                location.href = "{:U('Web/Wallet/recharge')}";
                            }
                            else {
                                alert(data.msg);
                            }
                        }
                    });
                }else {
                    alert('请输入正确的数字');
                }

            })
        });
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a class="return" href="javascript:history.go(-1)" target="_self"></a></div>
       <h1>积分兑换</h1>
    </div>
    <div id="page_info" class="page_info">
        <div class="nrongDiv">
            <input id="htmlPoint" name="htmlPoint" type="text" value="" placeholder="请输入需要兑换的积分" />
        </div>
        <div class="nrongDiv" style="margin-top:10px">
            <span>您共有<label style="color: #ff0000;">{$total}</label>积分,100积分=1元</span>
        </div>
        <div class="nrongDiv" style="margin-top:10px">
            <input id="htmlSubmit" name="htmlSubmit" type="button" value="立即兑换" class="toolSubmit" />
        </div>
    </div>
</body>
</html>

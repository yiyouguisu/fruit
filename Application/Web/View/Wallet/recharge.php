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
    <script>
        $(function () {
            $("#htmlSubmit").click(function () {
                var money = parseFloat($("#htmlMoney").val());
                if (money == '' || money == 0) {
                    alert('充值金额不可以为空或者0');
                    return false;
                }else if(money<200){
                    alert('充值最小金额为200元');
                    return false;
                }else if(money%100!=0){
                    alert('充值的递增金额必须为100的倍数');
                    return false;
                }
                if (checkRate(money)) {
                    //alert(1);
                    $.ajax({
                        type: "GET",
                        url: "{:U('Web/Wallet/rechargeon')}",
                        data: { 'money': money },
                        dataType: "json",
                        success: function (data) {
                            //console.log(data);
                            //alert(data);
                            location.href = "{:U('Web/Pay/recharge')}";
                        }
                    });
                }
            })
        })

        function checkRate(input) {
            var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/  
            var money = parseFloat($("#htmlMoney").val());
            if (!re.test(money)) {
                alert("请输入数字");
                return false;
            }
            else {
                return true;
            }
        }

    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
        <h1>我的钱包</h1>
        <div class="r" style="top: 6px"><a id="toolJpoint" class="wenzhi" href="{:U('Web/Wallet/point')}" target="_self">使用积分充值</a></div>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff">
        <div class="walletHead">
            <img alt="" src="__IMG__/walletBg.png" class="backImg" />
            <div class="money">
                <label class="count">{$total}</label>
                <label class="mtips">元</label>
            </div>
            <div class="mycur">当前余额</div>
        </div>
        <ul class="walletTabs">
            <li class="nomal"><a href="{:U('Web/Wallet/index')}" target="_self">钱包记录</a></li>
            <li class="hover"><a href="{:U('Web/Wallet/recharge')}" target="_self">立即充值</a></li>
        </ul>
        <div class="clear" style="height: 10px"></div>
        <div class="nrongDiv" style="margin-bottom: 20px;">
            <input id="htmlMoney" name="htmlMoney" type="number" value="" min="200" step="100" placeholder="请输入充值金额" />
            <!--充值金额只能用于本系统消费不可提现!-->
        </div>
        <!--<div class="nrongDiv">
          <span>充值后得到：<label>303</label>元</span>
       </div>-->
        <div style="height: 20px; background-color: #f3f3f3"></div>
        <div class="walletCzfs" id="czfsList">
            <div class="item">
                <div class="imageLogo">
                    <img alt="" src="__IMG__/icon_weixin.png" />
                </div>
                <div class="infosBase">
                    <div class="title">微信支付</div>
                    <div class="marks">推荐已安装微信客户端的使用</div>
                </div>
                <div class="to_choose">
                    <span class="isSelect"></span>
                </div>
            </div>
        </div>
        <div class="nrongDiv">
            <input id="htmlSubmit" name="htmlSubmit" type="button" value="立即充值" class="toolSubmit" />
        </div>
    </div>

</body>
</html>

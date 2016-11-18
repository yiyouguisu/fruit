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
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            $("#qiandao").click(function () {
                $.ajax({
                    type: "POST",
                    url: "{:U('Web/Member/sign')}",
                    dataType: "json",
                    success: function (data) {
                        alert(data.msg);
                        if (data.code == '200') {
                            location.reload();
                        }
                    }
                });
            });
        })
    </script>
</head>
<body>
    <div id="page_user" class="page_user">
        <div class="headmain">
            <img alt="" src="__IMG__/mySelfBg.png" class="backImg" />
            <div class="qiandao">
                <!--<eq name="qiandao" value="0"><img alt="" src="__IMG__/icon_registration.png" id="qiandao" /></eq>
                <eq name="qiandao" value="1"><img alt="" src="__IMG__/icon_registration_success.png"  /></eq>-->
                <img alt="" src="__IMG__/icon_registration.png" id="qiandao" />
                <!--签到-->
            </div>
            <div class="setting" onclick="window.location.href='{:U('Web/Member/setup')}';">
                <img alt="" src="__IMG__/icon_set_up.png" /></div>
            <div class="jbInfos" onclick="window.location.href='{:U('Web/Member/view')}';"></div>
            <div class="yhPhoto" onclick="window.location.href='{:U('Web/Member/view')}';">
                <div class="headimg"><img alt="" src="__ROOT__{$data.head}" /></div>
                <div style="font-size:18px">{$data.nickname}</div>
                <div>{$data.phone}</div>
                <span>{$data.level}</span>
            </div>
            <div class="footDiv">
                <ul>
                    <li onclick="window.location.href='{:U('Web/Point/index')}';">
                        <div>{$integral_total}</div>
                        <div>积分</div>
                    </li>
                    <li onclick="window.location.href='{:U('Web/Wallet/index')}';">
                        <div>{$account_total}</div>
                        <div>钱包</div>
                    </li>
                    <li onclick="window.location.href='{:U('Web/Coupons/index')}';">
                        <div>{$coupons_total}张</div>
                        <div>优惠券</div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="userMenuList" style="background-color:#ffffff">
            <ul>
                <li onclick="window.location.href='{:U('Web/Order/index')}';">
                    <img class="icon" alt="" src="__IMG__/icon_order_form.png" /><p>我的订单</p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </li>
                <li onclick="window.location.href='{:U('Web/Address/index')}';">
                    <img class="icon" alt="" src="__IMG__/icon_address.png" /><p>我的收货地址</p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </li>
                <li>
                    <img class="icon" alt="" src="__IMG__/icon_member.png" /><p>我的会员等级</p>
                    <!--<img class="turn" alt="" src="__IMG__/icon_arrow.png" />--><span style="float: right; color: #ff0000; font-size: 15px; margin-top:10px;margin-right:10px;">{$data.level}</span>
                    <!--<img class="turn" alt="" src="__IMG__/icon_arrow.png" />-->
                </li>
                <li onclick="window.location.href='{:U('Web/Focus/index')}';">
                    <img class="icon" alt="" src="__IMG__/icon_attention.png" /><p>我的关注</p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </li>
                <li onclick="window.location.href='{:U('Web/Message/index')}';">
                    <img class="icon" alt="" src="__IMG__/icon_information.png" /><p>消息中心</p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                    <if condition="$wordcount neq '0'">
                        <span style="float: right; color: #ff0000; font-size: 15px; margin-top:10px; margin-right:5px">有新消息</span>
                    </if>
                    
                </li>
                <li onclick="window.location.href='{:U('Web/Invoic/index')}';" style=" border-bottom:0px">
                    <img class="icon" alt="" src="__IMG__/icon_invoice.png" /><p>我的发票</p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </li>
                <li style="height:20px; background-color:#f3f3f3; border-bottom:0px">
                </li>
                <li style=" border-bottom:0px">
                    <img class="icon" alt="" src="__IMG__/icon_phone.png" /><p>客服电话<a href="tel:400-018-3358" target="_self">400-018-3358</a></p>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </li>
                <li style="height:20px; background-color:#f3f3f3;border-bottom:0px">
            </ul>
        </div>
    </div>
    <include file="Common:foot3" />
</body>

</html>

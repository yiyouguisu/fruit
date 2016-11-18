<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>打印订单</title>
    <link rel="stylesheet" href="__CSS__/print.css">
    <script type="text/javascript">
        window.onload = function () {
            window.print();
        }
    </script>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <table class="main_1" style="border-collapse: collapse; margin-top: 3px;">
            <tr>
                <td>保留此包裹联时,代表您已经签收并确认商品信息，客户须知及退货流程请登录官网或致电客服400-018-3358    【下载安装APP “蔬果先生”有更多功能和细致服务】</td>
            </tr>
        </table>
        <table class="main_2" style="border-collapse: collapse;">
            <tr>
                <td>收件人：{$order.name}  {$order.tel}</td>
                <td>订单号：{$order.orderid} </td>
            </tr>
        </table>
        <table class="wx" style="border-collapse: collapse;">
            <tr>
                <td style="width: 50%;">
                    <img src="__IMG__/bg_01.png" alt="" style="width: 55px; height: 55px"><span>{$order.storename}</span>
                </td>
                <td>
                    <img src="{$order.ordercode}" alt="">
                </td>
            </tr>
        </table>
        <div style="min-height: 156px; border-collapse: collapse; border-left: 1px solid #999a94; border-right: 1px solid #999a94;">
            <table class="main_3" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td>商品编码</td>
                        <td>商品名称</td>
                        <td>规格</td>
                        <td>数量</td>
                        <td>称重重量</td>
                        <td>单价</td>
                        <td>小计</td>
                    </tr>
                </thead>
                <tbody>
                    <volist name="order_productinfo" id="vo">
                <tr>
                    <td>{$vo.productnumber|default="无"}</td>
                    <td>{$vo.productname}</td>
                    <td>{$vo.standard}{:getunit($vo['unit'])}</td>
                    <td>{$vo.nums}</td>
                    <td>{$vo.weigh}</td>
                    <td>{$vo.price}</td>
                    <td>{$vo.total}</td>
                </tr>
                </volist>
                </tbody>
            </table>
        </div>
        <table class="main_4" style="border-collapse: collapse;">
            <tr>
                <td>
                    付款方式：
                    <if condition="$order.paystyle eq 1"> 
                        <if condition="$order.paytype eq 1"> 支付宝</if>
						<if condition="$order.paytype eq 2"> 微信</if>
					</if>
                    <if condition="$order.paystyle eq 2"> 货到付款</if>
                    <if condition="$order.paystyle eq 3"> 钱包支付</if>
                </td>
            </tr>
        </table>
        <table class="main_5" style="border-collapse: collapse;">
            <tr>
                <td>优惠券抵扣：{$order.discount|default="0.00"} </td>
                <td>钱包抵扣：{$order.wallet|default="0.00"}</td>
                <td>实际支付金额：{$order.money|default="0.00"}</td>
            </tr>
        </table>
    <!-- </div>
    <div class="wrap main_bx_2"> -->
        <table class="main_6" style="border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top;">
                    贺卡留言:  </br>
                    {$order.cardremark}
                </td>
                <td>
                    配送时段：  
                    {:getordersendtime($order['orderid'],1)}
                </td>
            </tr>
            <tr>
                <td>
                    送货地址：</br>
                    {:getarea($order['area'])}{$order.areatext}{$order.address}</br>
                    {$order.name}  {$order.tel}
                </td>
                <td style="vertical-align: top;">
                    订单留言:  </br>
                    {$order.buyerremark}
                </td>
            </tr>
        </table>
        <!--<table class="main_7" style="border-collapse: collapse;">
            <tr>
                <td></td>
            </tr>
        </table>-->
    </div>
</body>
</html>

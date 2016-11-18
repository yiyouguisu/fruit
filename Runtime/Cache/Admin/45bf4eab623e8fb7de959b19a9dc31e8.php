<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>打印订单</title>
    <link rel="stylesheet" href="/Public/Admin/css/print.css">
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
                <td>收件人：<?php echo ($order["name"]); ?>  <?php echo ($order["tel"]); ?></td>
                <td>订单号：<?php echo ($order["orderid"]); ?> </td>
            </tr>
        </table>
        <table class="wx" style="border-collapse: collapse;">
            <tr>
                <td style="width: 50%;">
                    <img src="/Public/Admin/images/bg_01.png" alt="" style="width: 55px; height: 55px"><span><?php echo ($order["storename"]); ?></span>
                </td>
                <td>
                    <img src="<?php echo ($order["ordercode"]); ?>" alt="">
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
                    <?php if(is_array($order_productinfo)): $i = 0; $__LIST__ = $order_productinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ((isset($vo["productnumber"]) && ($vo["productnumber"] !== ""))?($vo["productnumber"]):"无"); ?></td>
                    <td><?php echo ($vo["productname"]); ?></td>
                    <td><?php echo ($vo["standard"]); echo getunit($vo['unit']);?></td>
                    <td><?php echo ($vo["nums"]); ?></td>
                    <td><?php echo ($vo["weigh"]); ?></td>
                    <td><?php echo ($vo["price"]); ?></td>
                    <td><?php echo ($vo["total"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
        <table class="main_4" style="border-collapse: collapse;">
            <tr>
                <td>
                    付款方式：
                    <?php if($order["paystyle"] == 1): if($order["paytype"] == 1): ?>支付宝<?php endif; ?>
						<?php if($order["paytype"] == 2): ?>微信<?php endif; endif; ?>
                    <?php if($order["paystyle"] == 2): ?>货到付款<?php endif; ?>
                    <?php if($order["paystyle"] == 3): ?>钱包支付<?php endif; ?>
                </td>
            </tr>
        </table>
        <table class="main_5" style="border-collapse: collapse;">
            <tr>
                <td>优惠券抵扣：<?php echo ((isset($order["discount"]) && ($order["discount"] !== ""))?($order["discount"]):"0.00"); ?> </td>
                <td>钱包抵扣：<?php echo ((isset($order["wallet"]) && ($order["wallet"] !== ""))?($order["wallet"]):"0.00"); ?></td>
                <td>实际支付金额：<?php echo ((isset($order["money"]) && ($order["money"] !== ""))?($order["money"]):"0.00"); ?></td>
            </tr>
        </table>
    <!-- </div>
    <div class="wrap main_bx_2"> -->
        <table class="main_6" style="border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top;">
                    贺卡留言:  </br>
                    <?php echo ($order["cardremark"]); ?>
                </td>
                <td>
                    配送时段：  
                    <?php echo getordersendtime($order['orderid'],1);?>
                </td>
            </tr>
            <tr>
                <td>
                    送货地址：</br>
                    <?php echo getarea($order['area']); echo ($order["areatext"]); echo ($order["address"]); ?></br>
                    <?php echo ($order["name"]); ?>  <?php echo ($order["tel"]); ?>
                </td>
                <td style="vertical-align: top;">
                    订单留言:  </br>
                    <?php echo ($order["buyerremark"]); ?>
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
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
</head>
<body>

    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div class="orderDetail">
            <div class="store">
                <span class="store"></span>
                <span class="title"><?php echo ((isset($data["storename"]) && ($data["storename"] !== ""))?($data["storename"]):"企业专区"); ?></span>
                <span class="phone"><?php echo ($data["worktel"]); ?></span>
            </div>
            <div class="goods">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="goodsItem" goods-id="<?php echo ($vo["pid"]); ?>">
                  <div class="image"><img alt="" src="<?php echo ($vo["thumb"]); ?>" /></div>
                  <div class="infos">
                      	<?php if(($vo[product_type]) == "1"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTh.png" /><?php echo ($vo["title"]); ?><span style="float:right; margin-right:10px; color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                   		<?php if(($vo[product_type]) == "2"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTg.png" /><?php echo ($vo["title"]); ?><span style="float:right;margin-right:10px;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                   		<?php if(($vo[product_type]) == "3"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleYg.png" /><?php echo ($vo["title"]); ?><span style="float:right;margin-right:10px;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                   		<?php if(($vo[product_type]) == "4"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleCz.png" /><?php echo ($vo["title"]); ?><span style="float:right;margin-right:10px;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                      <div class="marks"><?php echo ($vo["description"]); ?></div>
                      <div class="price">
                         <span class="price">￥<?php echo ($vo["nowprice"]); ?>/<?php echo ($vo["standard"]); echo ($vo["unit"]); ?></span>
                         <span class="count">X&nbsp;<?php echo ($vo["nums"]); ?></span>
                      </div>
                      <?php if($vo["product_type"] == '4'): if($vo["isweigh"] == '0'): ?><div class="iswidth">
                             <span class="iswidth">未称重</span>
                          </div>
                        <?php else: ?>
                          <div class="iswidth">
                             <span class="iswidth">称重完成</span>
                              <span class="iswidth">实际价格￥<?php echo ($vo["stotals"]); ?></span>
                              <span class="iswidth">实际重量<?php echo ($vo["weigh"]); echo ($vo["unit"]); ?></span>
                          </div><?php endif; endif; ?>
                  </div>
               </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="pinfo">
                <span class="payStyle">
                    <?php if($data['ordertype'] != 3): if(($data['paystyle']) == ""): if(($data[paytype]) == "1"): ?><label class="yellow">支付宝</label><?php endif; ?>
                            <?php if(($data[paytype]) == "2"): ?><label class="green">微信支付</label><?php endif; endif; ?>
                        <?php if(($data[paystyle]) == "2"): ?><label class="blak">货到付款</label><?php endif; ?>
                        <?php if(($data[paystyle]) == "3"): ?><label class="blak">钱包</label><?php endif; ?>
                        <?php if(($data[paystyle]) == "4"): ?><label class="blak">优惠券</label><?php endif; ?>
                        <?php else: ?>
                        企业专区<?php endif; ?>
                </span>
                <?php if($data["ordertype"] == '2' ): ?><span class="stotal">订单金额：<y>￥<?php echo ($data["total"]); ?></y></span><br />
                    <span class="stotal">还需付：<y>￥<?php echo ($data['wait_money']); ?></y></span>
                    <br />
                    <span class="stotal">已预付：<y>￥<?php echo ($data["yes_money"]); ?></y></span>
                 <?php else: ?>
                        <span class="numTotal">合计：<label>￥<?php echo ($data["tempprice"]); ?></label></span>
                        <span class="numGoods">共<?php echo ($data["pnums"]); ?>件商品</span><?php endif; ?>
            </div>
            <div class="zhifu">
                <ul>
                    <li>
                        <div class="title">优惠券抵扣</div>
                        <div class="price">￥<?php echo ($data["discount"]); ?></div>
                    </li>
                    <li>
                        <div class="title">钱包抵扣</div>
                        <div class="price">￥<?php echo ($data["wallet"]); ?></div>
                    </li>
                    <li>
                        <div class="title">实际支付</div>
                        <?php if($data["ordertype"] == '2'): if($data["pay_status"] == 1): ?><div class="price">￥<?php echo ($data['wait_money']); ?></div>
                        <?php else: ?>
                        <div class="price">￥<?php echo ($data['wait_money']); ?></div><?php endif; ?>
                        <?php else: ?>
                        <div class="price">￥<?php echo ($data["shijipay"]); ?></div><?php endif; ?>
                    </li>
                </ul>
            </div>
            <div style="height:20px;background-color:#f3f3f3"></div>
            <div class="addrs">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td style="width: 32px;">&nbsp;</td>
                            <td style="width: auto;">&nbsp;</td>
                            <td style="width: 50px;">&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" style="padding-bottom: 6px;"><span class="name">收货人：<?php echo ($data["name"]); ?></span><span class="type">公司</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img alt="" src="/Public/Web/images/icon_AddDetail.png" style="margin-left: 0px;" /></td>
                            <td>收货地址：<?php echo ($data["area"]); echo ($data["address"]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img alt="" src="/Public/Web/images/icon_AddMobile.png" style="margin-left: 3px;" /></td>
                            <td>联系电话：<?php echo ($data["tel"]); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
         <div style="height:20px;background-color:#f3f3f3"></div>
        <div class="orderDetail">
            <div class="inputDiv">
                <div class="title">订单号</div>
                <div class="input"><?php echo ($data["orderid"]); ?></div>
            </div>
            <div class="inputDiv">
                <div class="title">订单留言</div>
                <div class="input" style="height:auto;line-height:22px;text-align:left"><?php echo ($data["buyerremark"]); ?></div>
            </div>
            <div class="inputDiv">
                <div class="title">贺卡留言</div>
                <div class="input" style="height:auto;line-height: 22px;text-align:left"><?php echo ($data["cardremark"]); ?></div>
            </div>
        </div>
    </div>

</body>
</html>
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

    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div class="orderDetail">
            <div class="store">
                <span class="store"></span>
                <span class="title">{$data.storename|default="企业专区"}</span>
                <span class="phone">{$data.worktel}</span>
            </div>
            <div class="goods">
                <volist name='list' id="vo">
          	   <div class="goodsItem" goods-id="{$vo.pid}">
                  <div class="image"><img alt="" src="__ROOT__{$vo.thumb}" /></div>
                  <div class="infos">
                      	<eq name="vo[product_type]" value = "1"> 
                   			<div class="title"><img alt="" src="__IMG__/orderStyleTh.png" />{$vo.title}<span style="float:right; margin-right:10px; color:#f59c0c">￥{$vo.ttotal}</span></div>
                   		</eq>
                   		<eq name="vo[product_type]" value = "2"> 
                   			<div class="title"><img alt="" src="__IMG__/orderStyleTg.png" />{$vo.title}<span style="float:right;margin-right:10px;color:#f59c0c">￥{$vo.ttotal}</span></div>
                   		</eq>
                   		<eq name="vo[product_type]" value = "3"> 
                   			<div class="title"><img alt="" src="__IMG__/orderStyleYg.png" />{$vo.title}<span style="float:right;margin-right:10px;color:#f59c0c">￥{$vo.ttotal}</span></div>
                   		</eq>
                   		<eq name="vo[product_type]" value = "4"> 
                   			<div class="title"><img alt="" src="__IMG__/orderStyleCz.png" />{$vo.title}<span style="float:right;margin-right:10px;color:#f59c0c">￥{$vo.ttotal}</span></div>
                   		</eq>
                      <div class="marks">{$vo.description}</div>
                      <div class="price">
                         <span class="price">￥{$vo.nowprice}/{$vo.standard}{$vo.unit}</span>
                         <span class="count">X&nbsp;{$vo.nums}</span>
                      </div>
                      <if condition="$vo.product_type eq '4'">
                        <if condition="$vo.isweigh eq '0'">
                           <div class="iswidth">
                             <span class="iswidth">未称重</span>
                          </div>
                        <else />
                          <div class="iswidth">
                             <span class="iswidth">称重完成</span>
                              <span class="iswidth">实际价格￥{$vo.stotals}</span>
                              <span class="iswidth">实际重量{$vo.weigh}{$vo.unit}</span>
                          </div>
                        </if>
                      </if>
                  </div>
               </div>
          </volist>
            </div>
            <div class="pinfo">
                <span class="payStyle">
                    <if condition="$data['ordertype'] neq 3">
                        <eq name="data['paystyle']" valuee="1"> 
                            <eq name="data[paytype]" value="1"><label class="yellow">支付宝</label></eq>
                            <eq name="data[paytype]" value="2"><label class="green">微信支付</label></eq>
                        </eq>
                        <eq name="data[paystyle]" value="2"><label class="blak">货到付款</label></eq>
                        <eq name="data[paystyle]" value="3"><label class="blak">钱包</label></eq>
                        <eq name="data[paystyle]" value="4"><label class="blak">优惠券</label></eq>
                        <else />
                        企业专区
                    </if>
                </span>
                <if condition="$data.ordertype eq '2' ">
                     <span class="stotal">订单金额：<y>￥{$data.total}</y></span><br />
                    <span class="stotal">还需付：<y>￥{$data['wait_money']}</y></span>
                    <br />
                    <span class="stotal">已预付：<y>￥{$data.yes_money}</y></span>
                 <else />
                        <span class="numTotal">合计：<label>￥{$data.tempprice}</label></span>
                        <span class="numGoods">共{$data.pnums}件商品</span>
                    
                </if>
            </div>
            <div class="zhifu">
                <ul>
                    <li>
                        <div class="title">优惠券抵扣</div>
                        <div class="price">￥{$data.discount}</div>
                    </li>
                    <li>
                        <div class="title">钱包抵扣</div>
                        <div class="price">￥{$data.wallet}</div>
                    </li>
                    <li>
                        <div class="title">实际支付</div>
                        <if condition="$data.ordertype eq '2'">
                          <if condition="$data.pay_status eq 1">
                        <div class="price">￥{$data['wait_money']}</div>
                        <else />
                        <div class="price">￥{$data['wait_money']}</div>
                        </if>
                        <else />
                        <div class="price">￥{$data.shijipay}</div>
                         </if>
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
                            <td colspan="2" style="padding-bottom: 6px;"><span class="name">收货人：{$data.name}</span><span class="type">公司</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img alt="" src="__IMG__/icon_AddDetail.png" style="margin-left: 0px;" /></td>
                            <td>收货地址：{$data.area}{$data.address}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img alt="" src="__IMG__/icon_AddMobile.png" style="margin-left: 3px;" /></td>
                            <td>联系电话：{$data.tel}
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
                <div class="input">{$data.orderid}</div>
            </div>
            <div class="inputDiv">
                <div class="title">订单留言</div>
                <div class="input" style="height:auto;line-height:22px;text-align:left">{$data.buyerremark}</div>
            </div>
            <div class="inputDiv">
                <div class="title">贺卡留言</div>
                <div class="input" style="height:auto;line-height: 22px;text-align:left">{$data.cardremark}</div>
            </div>
        </div>
    </div>

</body>
</html>

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
                //open($('#pays'));

            var orderid = "{$orderid}";
            $("#ordercancel").click(function () {
                console.log(orderid);
                if (confirm("是否取订单？")) {
                    $.ajax({
                        type: "GET",
                        url: "{:U('Web/Order/closeorder')}",
                        data: { 'orderid': orderid },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            location.href = "{:U('Web/Order/index')}";
                        }
                    });
                }
            })

            $(".go_pay").click(function () {
                var orderid = $(this).attr('good-id');
                open($('#pays'));
                //alert(orderid);
                //$.post(
                //    "{:u('web/cat/chosepaystatus')}", {
                //        "orderid": orderid
                //    },
                //    function (response, status) {
                //        if (status == "success") {
                //            console.log(response);
                //        };
                //    },
                //    "json");

                //$.post(
                //    "{:u('web/cat/onlinpingpp')}", {
                //        "orderid": orderid
                //    },
                //    function (response, status) {
                //        if (status == "success") {
                //            //alert('1');
                //            //location.href = "{:U('Web/Pay/index')}";
                //        };
                //    },
                //    "json");
            })

            $("#pays").change(function () {
                var data = '';
                var orderid = '{$data.orderid}';
                var money = 0;
                var discount = '{$data.discount}';
                if ('{$data.ordertype}' == '2') {
                    money = '{$data.wait_money}';
                } else {
                    money = '{$data.money}';
                }

                //alert(money);
                if ($(this).val() == '1') {
                    $.post(
                        "{:u('web/cat/orderpayagain')}", {
                            "orderid": orderid,
                            "paystyle": "3",
                            "money": "0.0",
                            "wallet": money,
                            "discount": '0.0'
                        },
                        function (response, status) {
                            if (status == "success") {
                                if (response.code == '-200')
                                {
                                    alert('您钱包的余额不够,请使用其他支付方式!');
                                    location.href = '{:U("Web/Order/index")}';
                                }
                                else {
                                    alert('您的钱包余额足够，正使用钱包支付!');
                                    location.href = '{:U("Web/Order/index")}';
                                }
                            } else {
                                alert('您钱包的余额不够,请使用其他支付方式!');
                                location.href = '{:U("Web/Order/index")}';
                            };
                        },
                        "json");
                } else if ($(this).val() == '2') {
                    $.post(
                        "{:u('web/cat/onlinpingpp')}", {
                            "orderid": orderid
                        },
                        function (response, status) {
                            if (status == "success") {
                                location.href = "{:U('Web/Pay/index')}";
                            };
                        },
                        "json");
                } else if ($(this).val() == '3') {
                    $.post(
                        "{:u('web/cat/orderpayagain')}", {
                            "orderid": orderid,
                            "paystyle": "2",
                            "money": money,
                            "wallet": "0.00",
                            "discount": '0.00'
                        },
                        function (response, status) {
                            if (status == "success") {
                                location.href = '{:U("Web/Order/index")}';
                            };
                        },
                        "json");
                } else {
                    return false;
                }
            })

            $(".goodsItem").click(function () {
                var pid = $(this).attr('goods-id');
                //alert(pid);
                location.href = "{:U('Web/Product/infoview')}?id=" + pid;
            })

        });


        function open(elem) {
            if (document.createEvent) {
                var e = document.createEvent("MouseEvents");
                e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                elem[0].dispatchEvent(e);
            } else if (element.fireEvent) {
                elem[0].fireEvent("onmousedown");
            }
        }

        
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Order/index')}" target="_self"></a></div>
        <h1>订单详情</h1>
    </div>
    <div id="orderTabs" class="orderTabs" style="margin-top:0px">
        <ul>
            <li class="nomal"><a href="{:U('Web/Order/status',array('id'=>$orderid))}" target="_self">订单状态</a></li>
            <li class="hover"><a href="{:U('Web/Order/show',array('id'=>$orderid))}" target="_self">订单详情</a></li>
        </ul>
    </div>
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
    <div id="page_foot" class="page_foot">
        <span style="float: right; margin: 7px 10px 0px 0px;">
            <select id="pays" style="float: left; color: #ffffff; opacity: 0.01">
                <option value="0">请选择</option>
                <option value="1">钱包支付</option>
                <option value="2">微信支付</option>
                <option value="3">货到付款</option>
            </select>
            <input type="button" value="取消订单" id="ordercancel" class="order cancel" {$iscencalview} />
            <input type="button" value="去支付" id="gotopay"  class="order go_pay" good-id="{$data.orderid}" {$ispayview} />
        </span>
    </div>
</body>
</html>

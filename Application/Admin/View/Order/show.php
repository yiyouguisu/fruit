<include file="Common:Head" />
<style type="text/css">
    .cu, .cu-li li, .cu-span span {
        cursor: hand;
        !important;
        cursor: pointer;
    }

    tr.cu:hover td {
        background-color: #FF9966;
    }
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <div class="h_a">订单详细信息</div>
            <div class="table_full">
                <table width="100%" class="table_form contentWrap">
                    <tr>
                        <td width="50%" align="center">订单号</td>
                        <td width="50%" align="center">订单来源</td>
                        <td width="30%" align="center">支付方式</td>
                        <td width="30%" align="center">订单金额</td>
                    </tr>
                    <tr>
                        <td align="center"><eq name="vo['isfeedback']" value="1"><span style="color:green;">[已反馈]</span></eq>{$data.orderid}</td>
                        <td align="center">
                            <if condition=" $data.ordersource eq '1'"> [手机web]</if>
                            <if condition=" $data.ordersource eq '2'"> [App]</if>
                            <if condition=" $data.ordersource eq '3'"> [饿了么]</if>
                            <if condition=" $data.ordersource eq '4'"> [口碑外卖]</if>
                        </td>
                        <td align="center">
                            <if condition="$data.paystyle eq 1"> 
                                    <span style="color: green">在线支付</span>
                                    <if condition="$data.paytype eq 1"> <span style="color: green">(支付宝)</span></if>
						            <if condition="$data.paytype eq 2"> <span style="color: green">(微信)</span></if>
						        </if>
                            <if condition="$data.paystyle eq 2"> <span style="color: green">货到付款</span></if>
                        </td>
                        <td align="center">{$data.money}</br>配送费￥{$data.delivery|default="0.00"}</td>
                    </tr>
                    <tr>
                        <td width="30%" align="center">收货人信息</td>
                        <td width="70%" align="center">收货地址</td>
                        <td width="70%" align="center">订单时间</td>
                        <td width="70%" align="center">订单备注</td>
                    </tr>
                    <tr>
                        <td align="center">
                            {$data.name}<br />
                            {$data.tel}
                        </td>
                        <td align="center">
                            {:getarea($data['area'])}<br />
                            {$data.address}
                        </td>
                        <td align="center">
                            {$data.inputtime|date="Y-m-d",###}<br />
                            {$data.inputtime|date="H:i:s",###}
                        </td>
                        <td align="center">
                            {$order.buyer_remark}
                        </td>
                    </tr>

                    <tr id="product_{$data.id}">
                        <td colspan="4">
                            <table width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <td width="25%" align="center">产品名称</td>
                                        <td width="25%" align="center">产品价格</td>
                                        <td width="25%" align="center">购买数量</td>
                                        <td width="25%" align="center">商品类型</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <volist name="data['productinfo']" id="v">
                                            <tr>
                                                <td width="25%" align="center" >{$v.title}</td>
                                                <td width="25%" align="center" >{$v.price}元/{$v.standard}{:getunit($v['unit'])}</td>
                                                <td width="25%" align="center" >{$v.nums}</td>
                                                <td width="25%" align="center" >
                                                    <if condition=" $v.product_type eq '0'"> [企业商品]</if>
                                                    <if condition=" $v.product_type eq '1'"> [一般商品]</if>
                                                    <if condition=" $v.product_type eq '2'"> [团购商品]</if>
                                                    <if condition=" $v.product_type eq '3'"> [预购商品]</if>
                                                    <if condition=" $v.product_type eq '4'"> 
                                                        [称重商品]
                                                        <if condition=" $v.isweigh eq 1"> 
                                                            已称重[{$v.weightime|date="Y-m-d H:i:s",###}]
                                                            <else /> 
                                                            未称重
                                                        </if>
                                                    </if>
                                                </td>
                                            </tr>
                                        </volist>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                        <tr>
                            <td colspan="4" align="left">
                                {$data.error_content}</br>
                                <div id="layer-photos-demo" class="layer-photos-demo">
                                    <notempty name="data['error_thumb']">
                                        <volist name="data['error_thumb']" id="vo">
                                            <img layer-pid="" layer-src="{$vo}" src="{$vo}"  alt="订单异常处理图片"  width="60px" height="60px" style="margin: 5px;" />
                                        </volist>
                                    </notempty>
                                </div>
                                
                            </td>
                        </tr>
                </table>



            </div>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
    <script src="__JS__/layer/extend/layer.ext.js"></script>
    <script>
            //调用示例
            layer.ready(function(){ //为了layer.ext.js加载完毕再执行
              layer.photos({
                  photos: '#layer-photos-demo'
              });
            }); 
        
    </script>
</body>
</html>
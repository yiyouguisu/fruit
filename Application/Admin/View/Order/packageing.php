
<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Order/packageing')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> 
                    <span class="mr20">
                        下单时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">

                        订单来源：
                        <select class="select_2" name="ordersource" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordersource">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordersource eq '1'"> selected</if>>手机web</option>
                            <option value="2" <if condition=" $Think.get.ordersource eq '2'"> selected</if>>App</option>
                            <option value="3" <if condition=" $Think.get.ordersource eq '3'"> selected</if>>饿了么</option>
                            <option value="4" <if condition=" $Think.get.ordersource eq '4'"> selected</if>>口碑外卖</option>
                            <option value="5" <if condition=" $Think.get.ordersource eq '5'"> selected</if>>售后订单</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordertype">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordertype eq '1'"> selected</if>>一般订单</option>
                            <option value="2" <if condition=" $Think.get.ordertype eq '2'"> selected</if>>预购订单</option>
                            <option value="3" <if condition=" $Think.get.ordertype eq '3'"> selected</if>>企业订单</option>
                            <option value="4" <if condition=" $Think.get.ordertype eq '4'"> selected</if>>称重订单 </option>
                        </select>
                        <empty name="storeid">
                            店铺来源：
                            <select class="select_2" name="storeid" style="width:120px;">
                                <option value=""  <empty name="Think.get.storeid">selected</empty>>全部</option>
                                <volist name="store" id="vo">
                                    <option value="{$vo.id}" <if condition="$Think.get.storeid eq $vo['id']"> selected</if>>{$vo.title}</option>
                                </volist>
                            </select>
                        </empty>
                        第三方平台：
                        <select class="select_2" name="isthirdparty" style="width:85px;">
                            <option value=""  <empty name="Think.get.isthirdparty">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.isthirdparty eq '1'"> selected</if>>是</option>
                            <option value="0" <if condition=" $Think.get.isthirdparty eq '0'"> selected</if>>否</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$Think.get.keyword}" placeholder="请输入订单号...">
                        
                        <button class="btn">搜索</button>
                    </span>
                </div>
            </div>
        </form> 

        <form action="{:U('Admin/Order/del')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="12%" align="center" >订单号</td>
                            <td width="6%" align="center" >订单来源</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="8%"  align="center" >订单时间</td>
                            <td width="6%"  align="center" >支付方式</td>
                            <td width="6%"  align="center" >支付状态</td>
                            <td width="6%"  align="center" >派送状态</td>
                            <td width="10%"  align="center" >收货人信息</td>
                            <td width="14%"  align="center" >收货地址</td>
                            <td width="8%"  align="center" >派送时间</td>
                            <td width="16%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                 <?php $money_total1=0;?>
                    <volist name="data" id="vo">
                        <tr class="productshow" data-id="{$vo.id}">
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >[<a href="javascript:;" data-href="{:U('Admin/Order/download',array('orderid'=>$vo['orderid']))}" onclick="image_priview('{$vo.ordercode}');">查看二维码</a>]{$vo.orderid}</td>
                            <td align="center" >{:getordersource($vo['ordersource'])}</td>
                            <td align="center" >
                                ￥<?php $money_total1+=$vo['total'];?>
                                {$vo.total}<br/>配送费￥{$vo.delivery|default="0.00"}<a href="javascript:;" onclick="lalert({$vo['money']},{$vo['wallet']},{$vo['discount']},{$vo['total']},{$vo['wait_money']},{$vo['yes_money']},{$vo['yes_money_total']},{$vo['pay_status']},{$vo['ordertype']});" class="info"><img src="__IMG__/info.png" style="width: 20px;display: inline-block;margin-bottom: -5px;"></a>
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d",###}<br/>{$vo.inputtime|date="H:i:s",###}</td>
                            <td align="center" >{:getpaystyle($vo['orderid'])}</td>
                            <td align="center" >
                                {:getpaystatus($vo['pay_status'],$vo['orderid'])}
                            </td>
                            <td align="center" >
                                {:getdeliverystatus($vo['delivery_status'],$vo['orderid'])}
                            </td>
                            <td align="center" >{$vo.name}<br/>{$vo.tel}</td>
                            <td align="center" >{:getarea($vo['area'])}<br/>{$vo.areatext}{$vo.address}</td>
                            <td align="center" >{:getordersendtime($vo['orderid'])}</td>
                            <td align="center" > 
                                【{:getAuser($vo['puid'])}】</br>正在包装中</br>
                                <a href="{:U('Admin/Order/cancel',array('orderid'=>$vo['orderid']))}" class="cancel">取消订单</a></br>
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/orderprint',array('orderid'=>$vo['orderid']))}','打印订单',1,390,250)">打印</a>
                            </td>
                        </tr>
                        <tr id="product_{$vo.id}" style="color: rgb(24, 116, 237);background-color: rgb(230, 230, 230);display:none;" >
                            <td colspan="12">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <td width="25%" align="left" >产品名称</td>
                                            <td width="25%" align="center" >产品价格</td>
                                            <td width="25%" align="center" >购买数量</td>
                                            <td width="25%" align="center" >商品类型</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <volist name="vo['productinfo']" id="v">
                                            <tr>
                                                <td width="25%" align="left" >{$v.title}</td>
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
                    </volist>
                      <tr style="font-weight:bold">
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td align="center">小计:</th>
                        <td align="center">￥{$money_total1|default="0.00"}</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                      </tr>
                    </tbody>
                </table>
                <div class="p10">
                    <div class="pages"> {$Page} </div>
                    <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>   

                    <if condition="authcheck('Admin/Order/del')">
                        <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                    </if>
                    </form>
                </div>
            </div>

            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <form method="post" action="{:U('Admin/Orderexcel/excel')}">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="{$Think.get.start_time}" >
                            <input type="hidden"  name="end_time" value="{$Think.get.end_time}" >
                            <input type="hidden"  name="ordersource" value="{$Think.get.ordersource}" >
                            <input type="hidden"  name="ordertype" value="{$Think.get.ordertype}" >
                            <input type="hidden"  name="isthirdparty" value="{$Think.get.isthirdparty}" >
                            <input type="hidden"  name="issend" value="{$Think.get.issend}" >
                            <input type="hidden"  name="storeid" value="{$Think.get.storeid}" >
                            <input type="hidden"  name="keyword" value="{$Think.get.keyword}" >
                            <input type="hidden"  name="ispackageing" value="1" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form> 


                </div>
            </div>


    </div>

    <script src="__JS__/common.js?v"></script>
        <script src="__JS__/content_addtop.js"></script>
    <script>
        $(function () {
            $(".productshow a").click(function () {
                var href = $(this).attr("href");
                window.location.href = href;
                return false;
            })
            $(".productshow").click(function () {
                var obj = "#product_" + $(this).data("id");
                $(obj).toggle();
            })
            
        })
    </script>
</body>
</html>
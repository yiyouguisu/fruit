
<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Order/error')}">
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
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordertype">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordertype eq '1'"> selected</if>>一般订单</option>
                            <option value="2" <if condition=" $Think.get.ordertype eq '2'"> selected</if>>预购订单</option>
                            <option value="3" <if condition=" $Think.get.ordertype eq '3'"> selected</if>>企业订单</option>
                            <option value="4" <if condition=" $Think.get.ordertype eq '4'"> selected</if>>称重订单 </option>
                        </select>
                        店铺来源：
                        <select class="select_2" name="storeid" style="width:120px;">
                            <option value=""  <empty name="Think.get.storeid">selected</empty>>全部</option>
                            <volist name="store" id="vo">
                                <option value="{$vo.id}" <if condition="$Think.get.storeid eq $vo['id']"> selected</if>>{$vo.title}</option>
                            </volist>
                        </select>
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
                            <td width="11%" align="center" >订单号</td>
                            <td width="6%" align="center" >订单来源</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="8%"  align="center" >订单时间</td>
                            <td width="6%"  align="center" >支付方式</td>
                            <td width="6%"  align="center" >支付状态</td>
                            <td width="7%"  align="center" >包装状态</td>
                            <td width="6%"  align="center" >派送状态</td>
                            <td width="10%"  align="center" >收货人信息</td>
                            <td width="10%"  align="center" >收货地址</td>
                            <td width="8%"  align="center" >派送时间</td>
                            <td width="12%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                 <?php $money_total1=0;?>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >[<a href="javascript:;" data-href="{:U('Admin/Order/download',array('orderid'=>$vo['orderid']))}" onclick="image_priview('{$vo.ordercode}');">查看二维码</a>]{$vo.orderid}</td>
                            <td align="center" >
                                <if condition=" $vo.ordersource eq '1'"> [手机web]</if>
                                <if condition=" $vo.ordersource eq '2'"> [App]</if>
                                <if condition=" $vo.ordersource eq '3'"> [饿了么]</if>
                                <if condition=" $vo.ordersource eq '4'"> [口碑外卖]</if>
                            </td>
                            <td align="center" >
                                ￥<?php $money_total1+=$vo['total'];?>
                                {$vo.total}<br/>配送费￥{$vo.delivery|default="0.00"}
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d",###}<br/>{$vo.inputtime|date="H:i:s",###}</td>
                            <td align="center" >
						        <if condition="$vo.paystyle eq 1"> 
                                    <span style="color: green">在线支付</span>
                                    <if condition="$vo.paytype eq 1"> <span style="color: green">(支付宝)</span></if>
						            <if condition="$vo.paytype eq 2"> <span style="color: green">(微信)</span></if>
						        </if>
						        <if condition="$vo.paystyle eq 2"> <span style="color: green">货到付款</span></if>
                                <if condition="$vo.paystyle eq 3"> <span style="color: green">钱包支付</span></if>
						    </td>
                            <td align="center" >
                                {:getpaystatus($vo['pay_status'],$vo['orderid'])}
                            </td>
                            <td align="center" >
                                {:getpackagestatus($vo['package_status'],$vo['orderid'])}
                            </td>
                            <td align="center" >
                                {:getdeliverystatus($vo['delivery_status'],$vo['orderid'])}
                            </td>
                            <td align="center" >{$vo.name}<br/>{$vo.tel}</td>
                            <td align="center" >{:getarea($vo['area'])}<br/>{$vo.areatext}{$vo.address}</td>
                            <td align="center" >
                                <eq name="vo['ordertype']" value="2">
                                    预购订单
                                    <else />
                                    <eq name="vo['isspeed']" value="1">
                                        极速达订单
                                        <else />
                                        {$vo.start_sendtime|date="Y-m-d H:i:s",###}</br>
                                        {$vo.end_sendtime|date="Y-m-d H:i:s",###}
                                    </eq>
                                </eq>
                            </td>
                            <td align="center" > 
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/addorder',array('orderid'=>$vo['orderid']))}','新建订单',1,700,400)">新建订单</a></br>
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/show',array('orderid'=>$vo['orderid']))}','查看详情',1,700,400)">查看详情</a></br>
                                <if condition="$vo.error_status eq 2">
                                    已审核
                                    <else />
                                    <a href="{:U('Admin/Order/doerror',array('orderid'=>$vo['orderid']))}">异常审核</a>
                                </if>
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


                    <if condition="authcheck('Admin/Order/excel')">
                        <form method="post" action="{:U('Admin/Orderexcel/excel')}">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="{$Think.get.start_time}" >
                            <input type="hidden"  name="end_time" value="{$Think.get.end_time}" >
                            <input type="hidden"  name="ordersource" value="{$Think.get.ordersource}" >
                            <input type="hidden"  name="isthirdparty" value="{$Think.get.isthirdparty}" >
                            <input type="hidden"  name="issend" value="{$Think.get.issend}" >
                            <input type="hidden"  name="storeid" value="{$Think.get.storeid}" >
                            <input type="hidden"  name="keyword" value="{$Think.get.keyword}" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form> 
                    </if>


                </div>
            </div>


    </div>

    <script src="__JS__/common.js?v"></script>
        <script src="__JS__/content_addtop.js"></script>
</body>
</html>
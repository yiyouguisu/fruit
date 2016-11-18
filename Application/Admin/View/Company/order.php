
<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Company/order')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        支付方式：
                        <select class="select_2" name="paytype" style="width:85px;">
                            <option value=""  <empty name="Think.get.paytype">selected</empty>>全部</option>
                            {:paytype($_GET['paytype'])}
                        </select>
                        支付状态：
                        <select class="select_2" name="paystatus" style="width:85px;">
                            <option value=""  <empty name="Think.get.paystatus">selected</empty>>全部</option>
                            {:paystatus($_GET['paystatus'])}
                        </select>
                        配送状态：
                        <select class="select_2" name="deliverystatus" style="width:120px;">
                            <option value=""  <empty name="Think.get.deliverystatus">selected</empty>>全部</option>
                            {:deliverystatus($_GET['deliverystatus'])}
                        </select>
                        下单人：
                        <select class="select_2" name="uid" style="width:100px;">
                            <option value=""  <empty name="Think.get.uid">selected</empty>>全部</option>
                            <volist name="daili" id="daili">
                                <option value="{$daili.id}" <if condition="$Think.get.uid eq $daili.id"> selected</if>>{$daili.nickname}</option>
                            </volist>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入订单号...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="12%" align="center" >订单号</td>
                            <td width="15%" align="center" >订单金额</td>
                            <td width="10%" align="center" >下单人</td>
                            <td width="12%"  align="center" >订单创建时间</td>
                            <td width="12%"  align="center" >订单完成时间</td>
                            <td width="8%"  align="center" >支付方式</td>
                            <td width="5%"  align="center" >支付状态</td>
                            <td width="10%"  align="center" >配送状态</td>
                            <td width="8%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center" >[<a href="javascript:;" data-href="{:U('Admin/Order/download',array('orderid'=>$vo['orderid']))}" onclick="image_priview('{$vo.ordercode}');">查看二维码</a>]{$vo.orderid}</td>
                            <td align="center" >{$vo.total}</td>
                            <td align="center" >{:getuser($vo['uid'])}</td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center">
                              <empty name="vo.donetime">
                                订单还未完成
                                <else />
                                {$vo.donetime|date="Y-m-d H:i:s",###}
                              </empty>
                            </td>
                            <td align="center" >
                                <if condition="$vo.paystyle eq 1"> 
                                    <span style="color: green">在线支付</span></BR>
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
                                {:getdeliverystatus($vo['delivery_status'])}
                            </td>

                            <td align="center" > 
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Company/ordershow',array('id'=>$vo['id']))}','查看详情',1,700,400)">查看详情</a>
                                
                            </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
                <div class="p10">
                    <div class="pages"> {$Page} </div>
                </div>
            </div>

            <div class="btn_wrap">
                <div class="btn_wrap_pd">


                    <if condition="authcheck('Admin/Company/orderexcel')">
                        <form method="post" action="{:U('Admin/Company/orderexcel')}">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="{$Think.post.start_time}" >
                            <input type="hidden"  name="end_time" value="{$Think.post.end_time}" >
                            <input type="hidden"  name="paystatus" value="{$Think.post.paystatus}" >
                            <input type="hidden"  name="deliverystatus" value="{$Think.post.deliverystatus}" >
                            <input type="hidden"  name="dailiid" value="{$Think.post.dailiid}" >
                            <input type="hidden"  name="keyword" value="{$Think.post.keyword}" >
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
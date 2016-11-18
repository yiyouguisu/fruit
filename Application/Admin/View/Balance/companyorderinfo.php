<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="12%" align="center">订单号</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="12%" align="center" >下单时间</td>
                            <td width="12%" align="center" >订单完成时间</td>
                            <td width="12%" align="center" >门店名称</td>
                            <td width="10%" align="center" >下单用户</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center" >{$vo.orderid}</td>
                            <td align="center" >{$vo.total}</td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{$vo.donetime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{$vo.storename}</td>
                            <td align="center" >{:getuser($vo['uid'])}<br/>{:getuser($vo['uid'],'phone')}</td>
                            <td align="center" > 
                                <if condition="authcheck('Admin/Balance/productinfo')">
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Balance/productinfo',array('orderid'=>$vo['orderid']))}','查看商品明细',1,700,420)">查看商品明细</a>
                                    <else/>
                                    <font color="#cccccc">查看商品明细</font>
                                </if> 
                                </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> {$Page} </div>
            </div>
            </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
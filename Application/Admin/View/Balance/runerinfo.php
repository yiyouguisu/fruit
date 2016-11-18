<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td width="12%" align="center">订单号</td>
                        <td width="10%" align="center">订单金额</td>
                        <td width="12%" align="center">下单时间</td>
                        <td width="12%" align="center">订单完成时间</td>
                        <td width="12%" align="center">门店名称</td>
                        <td width="12%" align="center">下单用户</td>
                        <td width="10%" align="center">订单类型</td>
                        <td width="8%" align="center">应缴金额</td>
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
                            {:getordertype($vo['orderid'])}
                            </td>
                            <td align="center" >{$vo.money}</td>
                        </tr>
                    </volist>
                </tbody>
            </table>
            
            <div class="p10">
                <div class="pages">{$Page} </div>
            </div>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
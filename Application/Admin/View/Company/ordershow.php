<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <div class="common-form">
            <div class="h_a">订单信息</div>
            <div class="table_list">
                <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                    <tbody>
                        <tr>
                          <td width="130">订单编号：</td>
                          <td width="200">{$data.orderid}</td>
                          <td width="130">订单总价：</td>
                          <td width="200">{$data.money}</td>
                        </tr>
                        <tr>
            	            <td>订单创建时间：</td>
            	            <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
            	            <td>订单完成时间：</td>
            	            <td>
                                  <empty name="data.donetime">
                                    订单还未完成
                                    <else />
                                    {$data.donetime|date="Y-m-d H:i:s",###}
                                  </empty>
            	            </td>
                        </tr>
                         <tr>
            	            <td>订单状态：</td>
            	            <td colspan="3">
                                {:getorderstatus($data['status'])}
	                        </td>
	                    </tr> 
                    </tbody>
                </table>
                <div class="h_a">商品信息列表</div>
                <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                    <tbody>
                        <tr>
            	            <td>商品名称</td>
            	            <td>单    价</td>
            	            <td>数    量</td>
            	            <td>小    计</td>
                        </tr>
                        <volist name="pro" id="vo">
                            <tr>
            	                <td>{$vo.title}</td>
            	                <td>{$vo.price|default="0.00"}</td>
            	                <td>{$vo.nums|default="0"}</td>
            	                <td>{$vo.total|default="0.00"}</td>
                            </tr>
                        </volist>
                    </tbody>
                </table>
            </div>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="button" onclick="javascript:history.back(-1)">返回</button>
                </div>
            </div>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
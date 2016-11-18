<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav" />
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Balance/store')}">
            <div class="search_type cc mb10">
                <div class="mb10">
                    <span class="mr20">时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width: 80px;">
                        <button class="btn">搜索</button>
                    </span>
                </div>
            </div>
        </form>
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <volist name="data" id="vo">
                    <thead>
                        <tr>
                            <td width="6%" align="center">日期</td>
                            <td width="5%" align="center">销售总额</td>
                            <td width="8%" align="center">支付宝付款额</td>
                            <td width="8%" align="center">微信支付付款额</td>
                            <td width="8%" align="center">钱包付款额</td>
                            <td width="8%" align="center">货到付款付款额</td>
                            <td width="8%" align="center">抵用券额</td>
                            <td width="8%" align="center">订单数</td>
                            <td width="8%" align="center">新客户订单数</td>
                            <td width="10%" align="center">使用现金券订单数</td>
                            <td width="10%" align="center">货到付款订单数</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center" >{$vo.date|date="Y-m-d",###}</td>
                            <td align="center" >{$vo.total|default="0.00"}</td>
                            <td align="center" >{$vo.alipay_money|default="0.00"}</td>
                            <td align="center" >{$vo.wx_money|default="0.00"}</td>
                            <td align="center" >{$vo.wallet_money|default="0.00"}</td>
                            <td align="center" >{$vo.delivery_money|default="0.00"}</td>
                            <td align="center" >{$vo.discount_money|default="0.00"}</td>
                            <td align="center" >{$vo.total_order|default="0"}</td>
                            <td align="center" >{$vo.new_order|default="0"}</td>
                            <td align="center" >{$vo.discount_order|default="0"}</td>
                            <td align="center" >{$vo.delivery_order|default="0"}</td>
                        </tr>
                        
                    </volist>
                </tbody>
            </table>
            <div class="p10">
                <div class="pages">{$Page} </div>
            </div>
        </div>

        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <label class="mr20">
                    销售汇总：{$totalmoney|default="0.00"}元
                </label>
            </div>
        </div>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
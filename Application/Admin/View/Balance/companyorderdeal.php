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
                <div class="table_full">
                    <form class="J_ajaxForm" method="post" action="{:U('Admin/Balance/companyorderdeal')}">
                         <div class="bk10"></div>
                         <div class="h_a">结算信息</div>       
                        <table width="100%" class="table_form contentWrap">
                            <tbody>
                                <tr>
                                    <th width="100">结算账期</th>
                                    <td>{$data.year}年{$data.month}月</td>
                                </tr>
                                <tr>
                                    <th>结算企业</th>
                                    <td>{$data.title}</td>
                                </tr>
                                <tr>
                                    <th>企业联系人</th>
                                    <td>{$data.username}</td>
                                </tr>
                                <tr>
                                    <th>企业联系方式</th>
                                    <td>{$data.tel}</td>
                                </tr>
                                <tr>
                                    <th>订单汇总金额</th>
                                    <td>{$data.ordermoney}</td>
                                </tr>
                                <tr>
                                    <th>已结算金额</th>
                                    <td>{$data.yes_money}</td>
                                </tr>
                                <tr>
                                    <th>未结算金额</th>
                                    <td>{$data.no_money}</td>
                                </tr>
                                <tr>
                                    <th  width="80">本次结算金额</th>
                                    <td>
                                        <input type="text" class="input length_2" name="money" value="0.00">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="btn_wrap">
                            <div class="btn_wrap_pd">
                                <input type="hidden" name="id" value="{$data.id}">
                                <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">结算</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
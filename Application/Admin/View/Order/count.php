

<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">

                <div class="h_a">订单统计</div>
                <div class="table_full">
                    <table cellpadding="0" cellspacing="0" class="table_form contentWrap" width="100%">
                        <tbody>
                          <tr>
                                <th width="80">订单总金额</th>
                                <td>
                                   ￥{$total}
                                </td>
                            </tr>
                             <tr>
                                <th width="80">已支付金额</th>
                                <td> ￥{$money}
                                </td>
                            </tr>
                            <tr>
                                <th width="80">未支付金额</th>
                                <td> ￥{$unmoney}
                                </td>
                            </tr>
                              <tr>
                                <th width="80">已完成订单</th>
                                <td> {$finish}
                                </td>
                            </tr>
                               <tr>
                                <th width="80">配送中订单</th>
                                <td> {$ing}
                                </td>
                            </tr>
                             <tr>
                                <th width="80">未发货订单</th>
                                <td> {$uning}
                                </td>
                            </tr>
                             <tr>
                                <th width="80">已支付订单</th>
                                <td> {$pay}
                                </td>
                            </tr>
                             <tr>
                                <th width="80">未支付订单</th>
                                <td> {$unpay}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
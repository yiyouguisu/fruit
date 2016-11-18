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
    <div class="wrap J_check_wrap" style="padding-bottom:0">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Order/dealagain')}">
                <div class="h_a">重新派发</div>
                <div class="table_full">
                    <script type="text/javascript">
                        function load(parentid) {
                            $.ajax({
                                type: "POST",
                                url: "{:U('Admin/Order/ajax_getpuser')}",
                                data: { 'storeid': parentid },
                                dataType: "json",
                                success: function (data) {
                                    $('#puid').html('<option>请选择包装员</option>');
                                    $.each(data, function (no, items) {
                                        $('#puid').append('<option value="' + items.id + '">' + items.realname + '</option>');
                                    });
                                }
                            });
                        }
                        $(function () {
                            var storeid = '{$storeid}';
                            if (storeid != '') {
                                load(storeid);
                            } else {
                                load(0);
                            }
                        })
                    </script>
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <empty name="storeid">
                                <tr>
                                    <th width="100">门店</th>
                                    <td>
                                        <select class="select_2" style="width: 120px;"  onchange="load(this.value)">
                                            <option value="">全部门店</option>
                                            <volist name="store" id="vo">
                                                <option value="{$vo.id}">{$vo.title}</option>
                                            </volist>
                                        </select>
                                    </td>
                                </tr>
                            </empty>
                            <tr>
                                <th width="100">包装员</th>
                                <td>
                                    <select id="puid" class="select_2" name="puid" style="width: 120px;">
                                        <option value="">请选择包装员</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="orderid" value="{$orderid}">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
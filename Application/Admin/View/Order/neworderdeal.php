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
    <div class="wrap J_check_wrap" style="padding-bottom: 0">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Order/neworderdeal')}">
                <div class="h_a">派发</div>
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
                                        $('#puid').append('<option value="' + items.id + '">' + items.username + '</option>');
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
                        <button class="btn btn_submit mr10" type="button" id="tijiao">提交</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script>
        $(function () {
            $("#tijiao").click(function (e) {
                e.preventDefault();
                var btn = $(this),
                    form = btn.parents('form.J_ajaxForm');
                //批量操作 判断选项
                //ie处理placeholder提交问题
                if ($.browser.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }
                form.ajaxSubmit({
                    url: btn.data('action') ? btn.data('action') : form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                    dataType: 'json',
                    beforeSubmit: function (arr, $form, options) {
                        var text = btn.text();
                        //按钮文案、状态修改
                        btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                    },
                    success: function (data, statusText, xhr, $form) {
                        var text = btn.text();
                        //按钮文案、状态修改
                        btn.removeClass('disabled').text(text.replace('中...', '')).parent().find('span').remove();

                        if (data.status == 1) {
                            $('<span class="tips_success">' + data.info + '</span>').appendTo(btn.parent()).fadeIn('slow').delay(1000).fadeOut(function () {
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layer.close(index); //再执行关闭     
                            });
                        } else {
                            $('<span class="tips_error">' + data.info + '</span>').appendTo(btn.parent()).fadeIn('fast');
                            btn.removeProp('disabled').removeClass('disabled');
                        }
                    }
                });
            });
        })
    </script>
</body>
</html>
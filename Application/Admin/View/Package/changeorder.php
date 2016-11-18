<include file="Common:Head" />
<script src="__JS__/jquery.js"></script>
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
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Package/changeorder')}">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="100" style="vertical-align: top;">商品信息:</td>
                                <td>
                                    <table width="100%" class="table_form contentWrap">
                                        <volist name="order_productinfo" id="vo">
                                            <tr id="product_{$vo.id}">
                                                <td>
                                                    <input type='hidden' name='oldpid[]' class="oldpid" value='{$vo.pid}'>
                                                    <input type='hidden' name='newpid[]' class="newpid" value=''><span class="productname">{$vo.productname}</span>
                                                    <input type="number" min="1" name="num[]" class="input input_hd num" style="width:60px" placeholder="请输入商品数量" value="{$vo.nums}" style="margin-left: 28px;" readonly><span class="unit">{:getunit($vo['unit'])}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn_submit mr10 change" type="button" data-id="{$vo.id}">换货</button>
                                                </td>
                                            </tr>
                                        </volist>
                                    </table>
                                </td>
                            </tr>
                            <tr id="chage" style="display: none;">
                                <td width="100" style="vertical-align: top;">搜索商品:</td>
                                <td>
                                    <input type="text" id="keyword" placeholder="请输入商品编号或商品id" class="input input_hd" style="width:140px" value="" />
                                     <input type="number" min="1" id="num" class="input input_hd" style="width:60px" placeholder="请输入商品数量" value="1">
                                    <button class="btn btn_submit mr10 " id="getproduct" type="button">确定</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <script>
                        $(function () {
                            $("#save").prop('disabled', true).addClass('disabled');
                            $(".change").click(function () {
                                $("#chage").attr("data-id",$(this).data("id")).show();
                            })
                            $("#getproduct").click(function () {
                                var obj = $("#product_" + $("#chage").data("id"));
                                var keyword = $("#keyword").val();
                                if (keyword == '' || Number(keyword) <= 0) {
                                    alert("请输入商品编号或商品id");
                                    return false;
                                }
                                var num = $("#num").val();
                                if (num == '' || Number(num) <= 0) {
                                    alert("请输入购买商品数量");
                                    return false;
                                }
                                $.ajax({
                                    type: "POST",
                                    url: "{:U('Admin/Product/ajax_getproduct')}",
                                    data: { 'keyword': keyword },
                                    dataType: "json",
                                    success: function (data) {
                                        if (data.status == 1) {
                                            obj.find(".num").val(num);
                                            obj.find(".newpid").val(data.product.id);
                                            obj.find(".productname").text(data.product.title);
                                            obj.find(".unit").text(data.product.unit);
                                            $("#chage").hide();
                                            $("#save").removeProp('disabled').removeClass('disabled');
                                        } else {
                                            alert(data.msg);
                                            return false;
                                        }
                                    }
                                })

                            })
                        })
                    </script>
                </div>

                <div class="btn_wrap" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 999;">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="orderid" value="{$orderid}" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" id="save" type="submit" >提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
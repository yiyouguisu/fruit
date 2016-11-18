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
<script>
    $(function () {
        aa();
        getchildren(0, true);
        initvals();
        $(".jgbox").delegate("select", "change", function () {
            $(this).nextAll().remove();
            getchildren($(this).val(), true);
        });
        var i = 1;
        $(".ve_align:checkbox").click(function () {
            if ($(this).is(":checked")) {
                i = i + 1;
                changeprice(i);
            } else {
                i = i - 1;
                changeprice(i);
            }
        });
        $("#nums").blur(function () {
            var nums = parseInt($("#nums").val());
            if (nums < 1) {
                nums = 1;
                $("#nums").val(1);
            }

            var total = '';
            total = (parseFloat($("#price").val()) + parseFloat($("#delivery").val())) * parseInt($("#nums").val());
            $("#total").val(total);
        });


    })
    function changeprice(i) {
        if (i == 0) {
            alert("请至少选择一个阶段");
            $("#price").val(parseFloat($("#price1").val()));
        } else if (i == 2) {
            $("#price").val(parseFloat($("#price2").val()));
        } else if (i == 3) {
            $("#price").val(parseFloat($("#price3").val()));
        } else {
            $("#price").val(parseFloat($("#price1").val()));
        }
        aa();
    }

    function aa() {
        var total = '';
        total = (parseFloat($("#price").val()) + parseFloat($("#delivery").val())) * parseInt($("#nums").val());
        $("#total").val(total);
    }

    function getval() {
        var vals = "";
        $(".jgbox select").each(function () {
            var val = $(this).val();
            if (val != null && val != "") {
                vals += ',';
                vals += val;
            }
        });
        if (vals != "") {
            vals = vals.substr(1);
            $("#area").val(vals);
        }
    }
    function getchildren(a, b) {
        $.ajax({
            url: "{:U('admin/Expand/getchildren')}",
            async: false,
            data: { id: a },
            success: function (data) {
                data = eval("(" + data + ")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if (b) {
                        ahtml += "<option value=''>--请选择--</option>";
                    }
                    for (var i = 0; i < data.length; i++) {
                        ahtml += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                    }
                    ahtml += "</select>";
                    $(".jgbox").append(ahtml);
                }
            }
        });
        getval();
    }
    function initvals() {
        var vals = $("#area").val();
        if (vals != null && vals != "") {
            var arr = new Array();
            arr = vals.split(",");
            for (var i = 0; i < arr.length; i++) {
                if ($.trim(arr[i]) != "") {
                    $(".jgbox select").last().val(arr[i]);
                    getchildren(arr[i], true);
                }
            }
        }
    }

</script>
<script type="text/javascript">
    var filminfo = 0;
    $(function () {
        $(".qadd").live("click", function () {
            var obj = $(this).parents("tr").clone();
            obj.find(".qadd").remove();
            obj.find("input.productnumber").val("");
            obj.find("input.num").val("1");
            $("#last").before(obj);
        })
    });
</script>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Order/orderadd')}">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="100">商品信息:</td>
                                <td>
                                    <table width="100%" class="table_form contentWrap">
                                        <input type="text" name="product['productnumber']" class="input input_hd productnumber" placeholder="请输入商品编号" value="">
                                        <input type="number" min="1" name="product['num']" class="input input_hd num" placeholder="请输入商品数量" value="1">
                                        <button type="button" class="btn btn_submit mr10 qadd" type="button">添加商品</button>
                                    </table>
                                </td>
                            </tr>
                            <tr id="last">
                                <td width="100" style="vertical-align: top;">收货信息:</td>
                                <td>
                                    <ul class="switch_list cc " style="height: 100%;">
                                        <volist name="addresslist" id="vo">
                                            <li style="width:100%;float:none;">
                                                <label>
                                                    <input type='radio' name='addressid' value='{$vo.id}' <eq name="key" value="0">checked</eq>>
                                                    <span>{$vo.name}&nbsp;&nbsp;{$vo.tel}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{:getarea($vo['area'])}{$vo.areatext}{$vo.address}</span>
                                                </label>
                                            </li>
                                        </volist>
                                    </ul>
                                    <div class="h_a">其他</div>
                                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="80">收货人:</td>
                                                <td>
                                                    <input type="text" class="input" name="name" id="name" value="{$data.name}">
                                                    <font color="#FF0000">*</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>联系电话:</td>
                                                <td>
                                                    <input type="text" class="input" name="tel" id="tel" value="{$data.tel}">
                                                    <font color="#FF0000">*</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    收货地址:<input type="hidden" class="input" name="area" id="area" value="{$data.area}">
                                                </td>
                                                <td class="jgbox"></td>
                                            </tr>
                                            <tr>
                                                <td>详细地址</td>
                                                <td>
                                                    <textarea name="address" rows="5" cols="57">{$data.address}</textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="orderid" value="{$orderid}" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
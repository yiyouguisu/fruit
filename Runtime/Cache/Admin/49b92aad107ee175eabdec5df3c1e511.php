<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>网站系统管理后台</title>
<link href="/Public/Admin/css/admin_style.css" rel="stylesheet" />
<link href="/Public/Admin/js/artDialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript" src="/Public/Editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "",
    JS_ROOT: "/Public/Admin/js/",
    TOKEN: "d8a7e4212dd72764fc54360bc619692c_0be21a07a2313806c7f61fc129e26832"
};
</script>
<script src="/Public/Admin/js/wind.js"></script>
<script src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/layer/layer.js"></script>
<script src="/Public/Admin/js/jquery.cookie.js"></script>
<script>
    $(document).ready(function(){
        $('a.del').click(function(){
             if(confirm("您确定要删除此信息？")){
                 return true;
            }else{
                return false;
            }
        });
        $('a.cancel').click(function () {
            if (confirm("您确定要取消此订单？")) {
                return true;
            } else {
                return false;
            }
        });
        $('a.close').click(function () {
            if (confirm("您确定要关闭此订单？")) {
                return true;
            } else {
                return false;
            }
        });

        $("button.J_ajax_submit_btn").click(function(){
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement(); 
                return true;
            }
        })
    });
</script>
</head>
<script src="/Public/Admin/js/jquery.js"></script>
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
        getchildren(0, true);
        initvals();
        $(".jgbox").delegate("select", "change", function () {
            $(this).nextAll().remove();
            getchildren($(this).val(), true);
        });
    })
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
            url: "<?php echo U('admin/order/getchildren');?>",
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
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="<?php echo U('Admin/Order/addorder');?>">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="100" style="vertical-align: top;">商品信息:</td>
                                <td>
                                    <?php if(is_array($order_productinfo)): $i = 0; $__LIST__ = $order_productinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table width="100%" class="table_form contentWrap">
                                            <input type='checkbox' name='pid[]' value='<?php echo ($vo["pid"]); ?>'><?php echo ($vo["productname"]); ?>
                                            <input type="number" min="1" name="productinfo[<?php echo ($vo['pid']); ?>]" class="input input_hd num" placeholder="请输入商品数量" value="1" style="margin-left: 28px;"><?php echo getunit($vo['unit']);?>
                                        </table><?php endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                            </tr>
                            <tr id="last">
                                <td width="100" style="vertical-align: top;">收货信息:</td>
                                <td>
                                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="80">收货人:</td>
                                                <td>
                                                    <input type="text" class="input" name="name" id="name" value="<?php echo ($data["name"]); ?>">
                                                    <font color="#FF0000">*</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>联系电话:</td>
                                                <td>
                                                    <input type="text" class="input" name="tel" id="tel" value="<?php echo ($data["tel"]); ?>">
                                                    <font color="#FF0000">*</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    收货地址:<input type="hidden" class="input" name="area" id="area" value="<?php echo ($data["area"]); ?>">
                                                </td>
                                                <td class="jgbox"></td>
                                            </tr>
                                            <tr>
                                                <td>详细地址</td>
                                                <td>
                                                    <textarea name="address" rows="5" cols="57"><?php echo ($data["address"]); ?></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="100" style="vertical-align: top;">订单留言:</td>
                                <td>
                                    <textarea name="orderremark" style="width:100%;height:200px;"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="orderid" value="<?php echo ($orderid); ?>" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
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

<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="<?php echo U('Admin/Package/changeorder');?>">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="100" style="vertical-align: top;">商品信息:</td>
                                <td>
                                    <table width="100%" class="table_form contentWrap">
                                        <?php if(is_array($order_productinfo)): $i = 0; $__LIST__ = $order_productinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="product_<?php echo ($vo["id"]); ?>">
                                                <td>
                                                    <input type='hidden' name='oldpid[]' class="oldpid" value='<?php echo ($vo["pid"]); ?>'>
                                                    <input type='hidden' name='newpid[]' class="newpid" value=''><span class="productname"><?php echo ($vo["productname"]); ?></span>
                                                    <input type="number" min="1" name="num[]" class="input input_hd num" style="width:60px" placeholder="请输入商品数量" value="<?php echo ($vo["nums"]); ?>" style="margin-left: 28px;" readonly><span class="unit"><?php echo getunit($vo['unit']);?></span>
                                                </td>
                                                <td>
                                                    <button class="btn btn_submit mr10 change" type="button" data-id="<?php echo ($vo["id"]); ?>">换货</button>
                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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
                                    url: "<?php echo U('Admin/Product/ajax_getproduct');?>",
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
                        <input type="hidden" name="orderid" value="<?php echo ($orderid); ?>" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" id="save" type="submit" >提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
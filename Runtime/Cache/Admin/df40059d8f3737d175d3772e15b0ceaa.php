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
            <form class="J_ajaxForm" method="post" action="<?php echo U('Admin/Package/doout');?>">
                <div class="h_a">缺货处理</div>
                <div class="table_full">
                    <script type="text/javascript">
                        function load(parentid) {
                            $.ajax({
                                type: "POST",
                                url: "<?php echo U('Admin/Package/ajax_getouser');?>",
                                data: { 'storeid': parentid },
                                dataType: "json",
                                success: function (data) {
                                    $('#ouid').html('<option>请选择缺货管理员</option>');
                                    $.each(data, function (no, items) {
                                        $('#ouid').append('<option value="' + items.id + '">' + items.username + '</option>');
                                    });
                                }
                            });
                        }
                        $(function () {
                            var storeid = '<?php echo ($storeid); ?>';
                            if (storeid != '') {
                                load(storeid);
                            } else {
                                load(0);
                            }
                        })
                    </script>
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <?php if(empty($storeid)): ?><tr>
                                    <th width="100">门店</th>
                                    <td>
                                        <select class="select_2" style="width: 120px;"  onchange="load(this.value)">
                                            <option value="">全部门店</option>
                                            <?php if(is_array($store)): $i = 0; $__LIST__ = $store;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </td>
                                </tr><?php endif; ?>
                            <tr>
                                <th width="100">缺货管理员</th>
                                <td>
                                    <select id="ouid" class="select_2" name="ouid" style="width: 120px;">
                                        <option value="">请选择缺货管理员</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="orderid" value="<?php echo ($orderid); ?>">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
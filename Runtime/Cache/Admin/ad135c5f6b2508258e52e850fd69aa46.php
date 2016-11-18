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
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
       <div class="common-form">
            <form class="J_ajaxForm" method="post" id="form" action="<?php echo U('Admin/Package/weigh');?>" >
                <div class="h_a">商品详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">商品名称</th>
                                <td><?php echo ($data["title"]); ?></td>
                            </tr>
                            <tr>
                                <th>商品规格</th>
                                <td><?php echo ($data["standard"]); echo getunit($data['unit']);?></td>
                            </tr>
                            <tr>
                                <th>购买数量</th>
                                <td><?php echo ($data["nums"]); ?></td>
                            </tr>
                            <tr>
                                <th>商品单价</th>
                                <td><?php echo ($data["price"]); ?>元/<?php echo getunit($data['unit']);?></td>
                            </tr>
                            <tr>
                                <th>目前重量</th>
                                <td><input type="number" class="input"  name="weigh" value="" min="0"/><?php echo getunit($data['unit']);?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                
                    <div class="btn_wrap">
                        <div class="btn_wrap_pd">
                            <input type="hidden" name="id" value="<?php echo ($id); ?>">
                            <button class="btn btn_submit mr10 J_ajax_submit_btn" type="button" id="save">提交</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
<script src="/Public/Admin/js/common.js?v"></script>
<script>
    $(function(){
        $(".J_ajax_submit_btn").click(function(){
            if(confirm("请确认重量填写无误，提交后不可修改")){
                var weigh = $("input[name='weigh']").val();
                   
                if (weigh == '') {
                    alert("目前重量不能为空");
                    $("input[name='weigh']").focus();
                    return false;
                }else {
                    $("#form").submit();
                }
                
            }else{
                return false;
            }
        })
    })
</script>
</body>
</html>
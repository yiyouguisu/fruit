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
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td width="12%" align="center">订单号</td>
                        <td width="10%" align="center">订单金额</td>
                        <td width="12%" align="center">下单时间</td>
                        <td width="12%" align="center">订单完成时间</td>
                        <td width="12%" align="center">门店名称</td>
                        <td width="12%" align="center">下单用户</td>
                        <td width="10%" align="center">订单类型</td>
                        <td width="8%" align="center">应缴金额</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center" ><?php echo ($vo["orderid"]); ?></td>
                            <td align="center" ><?php echo ($vo["total"]); ?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["donetime"])); ?></td>
                            <td align="center" ><?php echo ($vo["storename"]); ?></td>
                            <td align="center" ><?php echo getuser($vo['uid']);?><br/><?php echo getuser($vo['uid'],'phone');?></td>
                            <td align="center" >
                            <?php echo getordertype($vo['orderid']);?>
                            </td>
                            <td align="center" ><?php echo ($vo["money"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            
            <div class="p10">
                <div class="pages"><?php echo ($Page); ?> </div>
            </div>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
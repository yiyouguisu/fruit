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
        <?php  $getMenu = \Admin\Controller\PublicController::getMenu(); if($getMenu) { ?>
<div class="nav">
  <ul class="cc">
    <?php
 foreach($getMenu as $r){ $name = $r['name']; $app=explode("/",$r['name']); $action=$app[1].$app[2]; ?>
    <li <?php echo $action==CONTROLLER_NAME.ACTION_NAME?'class="current"':""; ?>><a href="<?php echo U("".$name."");?>"><?php echo $r['title'];?></a></li>
    <?php
 } ?>
  </ul>
</div>
<?php } ?>
     
            <div class="table_list">
                <table width="100%">
                    <colgroup>
                        <col width="200">
                        <col width="200">
                        <col>
                        <col width="300">
                        <col width="300">
                    </colgroup>
                    <thead>
                        <tr>
                            <td align='center'>ID</td>
                            <td align='center'>名称</td>
                            <td align='center'>描述</td>
                            <td align='center'>状态</td>
                            <td align='center'>管理操作</td>
                        </tr>
                    </thead>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align='center'><?php echo ($vo["id"]); ?></td>
                            <td align='center'><?php echo ($vo["catname"]); ?></td>
                            <td align='center'><?php echo ($vo["description"]); ?></td>
                            <td align='center'><?php if($vo["status"] == 1): ?>启用<?php else: ?> <span style="color: red">禁用</span><?php endif; ?></td>
                            <td align='center'>
                                <a href="<?php echo U('Admin/Expand/index', array('catid' => $vo['id']));?>">管理子菜单</a>  |
                                <a href="<?php echo U('Admin/Expand/add', array('catid' => $vo['id']));?>">添加子菜单</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
                <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
            </div>
       
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
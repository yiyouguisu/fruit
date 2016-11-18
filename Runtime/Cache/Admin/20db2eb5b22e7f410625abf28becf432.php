<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>网站系统管理后台</title>
<link href="/fruit/trunk/fruit/Public/Admin/css/admin_style.css" rel="stylesheet" />
<link href="/fruit/trunk/fruit/Public/Admin/js/artDialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript" src="/fruit/trunk/fruit/Public/Editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "",
    JS_ROOT: "/fruit/trunk/fruit/Public/Admin/js/",
    TOKEN: "d8a7e4212dd72764fc54360bc619692c_0be21a07a2313806c7f61fc129e26832"
};
</script>
<script src="/fruit/trunk/fruit/Public/Admin/js/wind.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/jquery.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/layer/layer.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/jquery.cookie.js"></script>
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
<!-- <div class="nav">
  <ul class="cc">
        <li class="current"><a href="<?php echo U('Admin/Menu/index');?>">后台菜单管理</a></li>
        <li ><a href="<?php echo U('Admin/Menu/add');?>">添加菜单</a></li>
      </ul>
</div>-->
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
 <!-- -->
  <form action="<?php echo U('Admin/Menu/listorders');?>" method="post">
    <div class="table_list">
      <table width="100%">
        <colgroup>
        <col width="80">
        <col width="50">
        <col>
        <col width="200">
        <col width="150">
        <col width="100">
        <col width="80">
        <col width="200">
        </colgroup>
        <thead>
          <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>
            <td>规则</td>
            <td>表达式</td>
             <td>类型</td>
            <td>状态</td>
            <td>管理操作</td>
          </tr>
        </thead>
        <?php echo ($categorys); ?>
      </table>
      <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
     
    </div>
       <?php if(authcheck('Admin/Menu/listorders')): ?><div class="btn_wrap">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button>
      </div>
    </div><?php endif; ?>
    
  
  </form>
</div>
<script src="/fruit/trunk/fruit/Public/Admin/js/common.js?v"></script>
</body>
</html>
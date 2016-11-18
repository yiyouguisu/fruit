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
<div class="wrap jj">
<!--   <div class="nav">
  <ul class="cc">
        <li ><a href="<?php echo U('Admin/Menu/index');?>">后台菜单管理</a></li>
        <li class="current"><a href="<?php echo U('Admin/Menu/add');?>">添加菜单</a></li>
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
  <div class="common-form">
  <!---->
    <form method="post" action="<?php echo U('Admin/Manager/chanpass');?>" > 
     <div class="h_a">用户信息</div>
    <div class="table_full">
      <table width="100%">
        <col class="th" />
        <col/>
        <thead>
          <tr>
            <th>用户名</th>
            <td> <?php echo ($User["username"]); ?></td>
          </tr>
        </thead>
        <tr>
          <th>旧密码</th>
          <td><input name="password" type="password" class="input length_5" value=""></td>
        </tr>
        <tr>
          <th>新密码</th>
          <td><input name="new_password" type="password" class="input length_5" value="">
           <span id="J_reg_tip_new_password" role="tooltip"></span></td>
        </tr>
        <tr>
          <th>重复新密码</th>
          <td><input name="new_pwdconfirm" type="password" class="input length_5 " value=""></td>
        </tr>
      </table>
    </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="/Public/Admin/js/common.js?v"></script>

</body>
</html>
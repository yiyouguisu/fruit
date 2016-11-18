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
  <div class="h_a">搜索</div>
  <form method="post" action="<?php echo U('Admin/Logs/login');?>">
  <div class="search_type cc mb10">
    <div class="mb10"> <span class="mr20">
              搜索类型：
    <select class="select_2" name="status" style="width:70px;">
        <option value='' <?php if($_POST['status'] == ''): ?>selected<?php endif; ?>>不限</option>
                <option value="1" <?php if($_POST['status'] == '1'): ?>selected<?php endif; ?>>登录成功</option>
                <option value="2" <?php if($_POST['status'] == '2'): ?>selected<?php endif; ?>>登录失败</option>
              
      </select>
      用户名：<input type="text" class="input length_2" name="username" size='10' value="<?php echo ($_POST['username']); ?>" placeholder="用户名">
      IP：<input type="text" class="input length_2" name="ip" size='20' value="<?php echo ($_POST['ip']); ?>" placeholder="IP">
    时间：
      <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_POST['start_time']); ?>" style="width:80px;">
      -
      <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_POST['end_time']); ?>" style="width:80px;">
      <button class="btn">搜索</button>
      <a class="btn" name="del_log_4" href="<?php echo U('Admin/Logs/logindel');?>" >删除一月前数据</a>
      </span> </div>
      </form> 
  </div>
    <div class="table_list">
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td align="center" width="80">ID</td>
            <td  align="center">用户名</td>
            <td align="center">密码</td>
            <td align="center">状态</td>
            <td align="center">其他说明</td>
            <td align="center" width="120">时间</td>
            <td align="center" width="120">IP</td>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($logs)): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><?php echo ($vo["loginid"]); ?></td>
            <td align="center"><?php echo ($vo["username"]); ?></td>
            <td align="center"><?php echo ($vo["password"]); ?></td>
            <td align="center"><?php if($vo['status'] == 1): ?>登陆成功<?php else: ?><font color="#FF0000">登陆失败</font><?php endif; ?></td>
            <td align="center"><?php echo ($vo["info"]); ?></td>
            <td align="center"><?php echo ($vo["logintime"]); ?></td>
            <td align="center"><?php echo ($vo["loginip"]); ?></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
      <div class="p10">
        <div class="pages"> <?php echo ($Page); ?> </div>
      </div>
    </div>
  
</div>
<script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
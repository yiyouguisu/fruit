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
        <div class="h_a">邮箱配置</div>
        <div class="table_full">
            <form method='post' class="J_ajaxForm"  id="myform" action="<?php echo U('Admin/Config/email');?>">
                <table width="100%"  class="table_form">
                    <tr>
                        <th width="120">邮件发送模式</th>
                        <th class="y-bg"><input name="mail_type" checkbox="mail_type" value="1"  type="radio"  checked>
                            SMTP 函数发送 </th>
                    </tr>
                    <tbody id="smtpcfg" style="">
                        <tr>
                            <th>邮件服务器</th>
                            <th class="y-bg"><input type="text" class="input" name="mail_server" id="mail_server" size="30" value="<?php echo ($Site["mail_server"]); ?>"/></th>
                        </tr>
                        <tr>
                            <th>邮件发送端口</th>
                            <th class="y-bg"><input type="text" class="input" name="mail_port" id="mail_port" size="30" value="<?php echo ($Site["mail_port"]); ?>"/></th>
                        </tr>
                        <tr>
                            <th>发件人地址</th>
                            <th class="y-bg"><input type="text" class="input" name="mail_from" id="mail_from" size="30" value="<?php echo ($Site["mail_from"]); ?>"/></th>
                        </tr>
                        <tr>
                            <th>发件人名称</th>
                            <th class="y-bg"><input type="text" class="input" name="mail_fname" id="mail_fname" size="30" value="<?php echo ($Site["mail_fname"]); ?>"/></th>
                        </tr>
                        <tr>
                            <th>AUTH LOGIN验证</th>
                            <th class="y-bg"><input name="mail_auth" id="mail_auth" value="1" type="radio"  <?php if( $Site['mail_auth'] == '1' ): ?>checked<?php endif; ?>> 开启 
                    <input name="mail_auth" id="mail_auth" value="0" type="radio" <?php if( $Site['mail_auth'] == '0' ): ?>checked<?php endif; ?>> 关闭</th>
                    </tr>
                    <tr>
                        <th>验证用户名</th>
                        <th class="y-bg"><input type="text" class="input" name="mail_user" id="mail_user" size="30" value="<?php echo ($Site["mail_user"]); ?>"/></th>
                    </tr>
                    <tr>
                        <th>验证密码</th>
                        <th class="y-bg"><input type="password" class="input" name="mail_password" id="mail_password" size="30" value="<?php echo ($Site["mail_password"]); ?>"/></th>
                    </tr>
                    </tbody>
                </table>
                <div class="btn_wrap">
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
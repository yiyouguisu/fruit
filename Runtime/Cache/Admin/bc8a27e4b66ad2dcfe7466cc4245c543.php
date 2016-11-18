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
        <div class="h_a">搜索</div>
        <form method="get" action="<?php echo U('Admin/Balance/runerinvite');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：<input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width: 80px;">
                        配送员：
                        <select class="select_2" name="runerid" style="width:120px;">
                            <option value=""  <?php if(empty($_GET['runerid'])): ?>selected<?php endif; ?>>全部</option>
                            <?php if(is_array($runer)): $i = 0; $__LIST__ = $runer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ruid"]); ?>" <?php if($_GET['runerid']== $vo['ruid']): ?>selected<?php endif; ?>><?php echo ($vo["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                       <!-- 结算：
                       <select class="select_2" name="status" style="width:100px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="0" <?php if( $_GET['status']== '0'): ?>selected<?php endif; ?>>未结算</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>部分结算</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>完成结算</option>
                        </select>-->
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="12%" align="center" >用户名</td>
            <td width="8%" align="center" >性别</td>
            <td width="12%" align="center" >手机号</td>
            <td width="12%" align="center" >最后登录时间</td>
            <td width="12%"  align="center" >登陆次数</td>
            <td width="10%"  align="center" >注册时间</td>
            <td width="10%"  align="center" >邀请人</td>
            <td width="10%"  align="center" >邀请码</td>
          </tr>
        </thead>
        <tbody>
     
          
        <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
            <td align="center"><?php echo ($vo["username"]); ?></td>
            <td align="center">
              <?php if($vo["sex"] == 0): ?>未知<?php endif; ?>
              <?php if($vo["sex"] == 1): ?>男<?php endif; ?>
              <?php if($vo["sex"] == 2): ?>女<?php endif; ?>
            </td>
            <td align="center" ><?php echo ($vo["phone"]); ?></td>
            <td align="center">
              <?php if(empty($vo["lastlogin_time"])): ?>用户还未登录
                <?php else: ?>
                <?php echo (date("Y-m-d H:i:s",$vo["lastlogin_time"])); endif; ?>
            </td>
            <td align="center" ><?php echo ($vo["login_num"]); ?>次</td>
            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["reg_time"])); ?></td>
              <td align="center" ><?php echo ($vo["tuijianusername"]); ?></td>
              <td align="center" ><?php echo ($vo["tuijiancode"]); ?></td>
          </tr><?php endforeach; endif; ?>
        </tbody>
      </table>
                <div class="p10">
                    <div class="pages"> <?php echo ($Page); ?> </div>
                </div>
            </div>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script type="text/javascript" src="/Public/Admin/js/birthday.js"></script>
    <script>
        $(function () {
            $("#birthday_container").birthday();
        });
    </script>
</body>
</html>
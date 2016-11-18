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
<style>
.pop_nav{
  padding: 0px;
}
.pop_nav ul{
  border-bottom:1px solid green;
  padding:0 5px;
  height:25px;
  clear:both;
}
.pop_nav ul li.current a{
  border:1px solid green;
  border-bottom:0 none;
  color:#333;
  font-weight:700;
  background:#F3F3F3;
  position:relative;
  border-radius:2px;
  margin-bottom:-1px;
}

</style>
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
        <div class="pop_nav" style="margin-bottom:0px">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">注册协议</a></li>
              <li class=""><a href="javascript:;;">帮助中心</a></li>
              <li class=""><a href="javascript:;;">关于蔬果先生</a></li>
            </ul>
        </div>
        <form  id="myform" method="post" enctype="multipart/form-data"  action="<?php echo U('Admin/Config/service');?>">
            <div class="h_a"></div>
            <div class="J_tabs_contents" >
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="reg_service" id="reg_service"><?php echo ($Site["reg_service"]); ?></textarea>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="help" id="help"><?php echo ($Site["help"]); ?></textarea>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="about_us" id="about_us"><?php echo ($Site["about_us"]); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                </div>
            </div>
        </form>
    </div>
<script src="/Public/Admin/js/common.js?v"></script>
<script src="/Public/Admin/js/content_addtop.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('reg_service',{toolbar : 'Full'});
    CKEDITOR.replace('help',{toolbar : 'Full'});
    CKEDITOR.replace('about_us',{toolbar : 'Full'});
</script>
</body>
</html>
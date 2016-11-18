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
 <div class="h_a">分类应用</div>
  <form action="<?php echo U('Admin/Productcate/apply');?>" method="post" class="J_Form">
    <div class="table_full">
      <table width="100%" cellspacing="0" id="dnd-example">
        <tbody>
          <?php echo $categorys;?>
        </tbody>
      </table>
    </div>
    <div class="">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">应用</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function () {
    Wind.css('treeTable');
    Wind.use('treeTable', function () {
        $("#dnd-example").treeTable({
            indent: 20,
            expandable:false
        });
    });
});

function checknode(obj) {
    var chk = $("input[type='checkbox']");
    var count = chk.length;
    var num = chk.index(obj);
    var level_top = level_bottom = chk.eq(num).attr('level')
    for (var i = num; i >= 0; i--) {
        var le = chk.eq(i).attr('level');
        if (eval(le) < eval(level_top)) {
            chk.eq(i).attr("checked", true);
            var level_top = level_top - 1;
        }
    }
    for (var j = num + 1; j < count; j++) {
        var le = chk.eq(j).attr('level');
        if (chk.eq(num).attr("checked")) {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", true);
            else if (eval(le) == eval(level_bottom)) break;
        } else {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", false);
            else if (eval(le) == eval(level_bottom)) break;
        }
    }
}
</script>
<script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
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
    <form method="post" action="<?php echo U('Admin/Manager/myinfo');?>" id="J_bymobile_form">
     <input type="hidden" value="<?php echo ($data["id"]); ?>" name="id"/>
    <input type="hidden" value="<?php echo ($data["username"]); ?>" name="username"/>
    <div class="h_a">用户信息</div>
    <div class="table_full">
      <table width="100%">
        <col class="th" />
        <col/>
        <thead>
          <tr>
            <th>ID</th>
            <td><?php echo ($data["id"]); ?></td>
          </tr>
        </thead>
        <tr>
          <th>用户名</th>
          <td><?php echo ($data["username"]); ?></td>
        </tr>
        <tr>
          <th>姓名</th>
          <td><input name="nickname" type="text" class="input length_5 required" value="<?php echo ($data["nickname"]); ?>">
           <span id="J_reg_tip_nickname" role="tooltip"></span></td>
        </tr>
        <tr>
          <th>E-mail</th>
          <td><input name="email" type="text" class="input length_5" value="<?php echo ($data["email"]); ?>"></td>
        </tr>
        <tr>
          <th>备注</th>
          <td><textarea id="J_textarea" name="content" style="width:400px;height:100px;"><?php echo ($data["content"]); ?></textarea></td>
        </tr>
      </table>
    </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="/Public/Admin/js/common.js?v"></script>
<script type="text/jscript">
Wind.use('validate','ajaxForm', function(){
	//表单js验证开始
	$("#J_bymobile_form").validate({
		//当未通过验证的元素获得焦点时,并移除错误提示
		focusCleanup:true,
		//错误信息的显示位置
		errorPlacement:function(error, element){
			//错误提示容器
			$('#J_reg_tip_'+ element[0].name).html(error);
		},
		//获得焦点时不验证 
		focusInvalid : false,
		onkeyup: false,
		//设置验证规则
		rules:{
			nickname:{
				required:true,//验证条件：必填
				byteRangeLength: [3,15]
			}
		},
		//设置错误信息
		messages:{
			nickname:{
				required: "请填写用户名", 
				byteRangeLength: "用户名必须在3-15个字符之间(一个中文字算2个字符)"
			}
		}
	});
});
</script>
</body>
</html>
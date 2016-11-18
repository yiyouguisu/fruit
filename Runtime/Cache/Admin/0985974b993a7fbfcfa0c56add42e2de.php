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
 
  <form name="myform" id="myform" action="<?php echo U('Admin/Userupload/typeadd');?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="1">
    <div class="J_tabs_contents">
  
        <div class="h_a">基本属性</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
           
            <tr>
              <th width="200">上级栏目：</th>
              <td><select name="parentid" id="parentid">
                  <option value='0'>≡ 作为一级栏目 ≡</option>
                  <?php echo ($category); ?>
                </select></td>
            </tr>
            <tr id="normal_add">
              <th>类型名称：</th>
              <td><input type="text" name="catname" id="catname" class="input" value=""></td>
            </tr>
         
            <tr>
              <th>简介：</th>
              <td><textarea name="description" maxlength="255" style="width:300px;height:60px;"></textarea></td>
            </tr>
            
            <tr>
              <th>显示排序：</th>
              <td><input type="text" name="listorder" id="listorder" class="input" value="0"></td>
            </tr>
          </table>
        </div>
      </div>
     
    <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 " type="submit">提交</button>
      </div>
    </div>
  </form>
</div>
<script src="/Public/Admin/js/common.js?v"></script>
<link rel="stylesheet" type="text/css"  href="/Public/Public/uploadify/uploadify.css" />
<script type="text/javascript" src="/Public/Public/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/Public/Public/uploadify/uploadify.js"></script>
<script type="text/javascript">
$(function(){
	$("#uploadify").uploadify({
		'uploader'	: '/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
		'cancelImg'	: '/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
		'script'	: '/Public/Public/uploadify/uploadify.php',//实现上传的程序
		//'script'	: "<?php echo U('Admin/Public/upload');?>",//实现上传的程序
		'method'	: 'get',
		'folder'	: '/Uploads/images',//服务端的上传目录
		'auto'		: true,//自动上传
		'multi'		: true,//是否多文件上传
		'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
		'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
		'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
		'queueSizeLimit' :10, //可上传的文件个数
		'buttonImg'	: '/Public/Public/uploadify/add.gif',//替换上传钮扣
		'width'		: 80,//buttonImg的大小
		'height'	: 26,
		onComplete: function (evt, queueID, fileObj, response, data) {
			alert(response);
			getResult(response);//获得上传的文件路径
		}
	});
	var imgg = $("#image");
	function getResult(content){		
		imgg.val(content);
	}
});
</script>
</body>
</html>
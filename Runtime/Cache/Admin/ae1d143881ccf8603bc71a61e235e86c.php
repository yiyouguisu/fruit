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
         <div class="pop_nav">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">蔬果先生分享</a></li>
            </ul>
          </div>
        <form name="myform" class="J_ajaxForm" id="myform" action="<?php echo U('Admin/Config/share');?>" method="post" enctype="multipart/form-data">
           <div class="h_a">温馨提示</div>
            <div class="prompt_text">
            <p>1、文案信息不要太长，不要包含违法关键字</p>
            <p>2、<span class="gray"><font color="red">{#invitecode#}</font> 代表邀请码， <font color="red">{#money#}</font> 代表红包金额（数字） </span></p>
           </div>
    <div class="J_tabs_contents">
      <div class="tba">
        <div class="h_a">蔬果先生分享</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
                <th width="100">分享标题：</th>
                <td><input type="text" name="software_share_title" id="software_share_title" class="input length_5" value="<?php echo ($Site["software_share_title"]); ?>" ></td>
            </tr>
            <tr>
                <th>分享图片：</th>
                <td><input type="text" name="software_share_image" id="software_share_image" class="input length_5" value="<?php echo ($Site["software_share_image"]); ?>" style="float: left"  ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button upload" value="选择上传" id="uploadbtn_software_share_image"  data-id="software_share_image"> <span class="gray"> 双击文本框查看图片</span></td>
            </tr>
            <tr>
                <th>分享内容</th>
                <td>
                    <textarea  name="software_share_content" id="software_share_content" class="valid" style="width:500px;height:80px;"><?php echo ($Site["software_share_content"]); ?></textarea>
                </td>
            </tr>
            <tr>
                <th width="100">分享链接：</th>
                <td><input type="text" name="software_share_link" id="software_share_link" class="input length_5" value="<?php echo ($Site["software_share_link"]); ?>" ></td>
            </tr>
          </table>
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
     <link rel="stylesheet" type="text/css"  href="/Public/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="/Public/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="/Public/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
    $(function(){
        $(".upload").each(function(){
            inituploadify($(this));
        })
    })
   function inituploadify(a){
    a.uploadify({
        'uploader'  : '/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
        'cancelImg' : '/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
        //'script' : '/Public/Public/uploadify/uploadify.php',//实现上传的程序
        'script'    : "<?php echo U('Admin/Public/uploadone');?>",//实现上传的程序
        'method'    : 'post',
        'auto'      : true,//自动上传
        'multi'     : false,//是否多文件上传
        'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
        'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
        'sizeLimit': '',//限制上传文件的大小2m(比特b)
        'queueSizeLimit' :10, //可上传的文件个数
        'buttonImg' : '/Public/Public/uploadify/add.gif',//替换上传钮扣
        'width'     : 80,//buttonImg的大小
        'height'    : 26,
        onComplete: function (evt, queueID, fileObj, response, data) {
            var obj="#"+$(evt.currentTarget).attr("data-id");
            $(obj).val(response);
        }
    });
   }
   </script>
</body>
</html>
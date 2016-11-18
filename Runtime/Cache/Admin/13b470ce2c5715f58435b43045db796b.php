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

            <form method="post" action="<?php echo U('Admin/Store/packingadd');?>">
                <div class="h_a">会员基本信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">用户名称</th>
                                <td><input type="test" name="username" class="input" id="username" value="<?php echo ($data["username"]); ?>" ></td>
                            </tr>
                            <tr>
                                <th width="80">昵称</th>
                                <td>
                                    <input type="text" name="nickname" class="input" id="nickname" value="<?php echo ($data["nickname"]); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <th>图像</th>
                                <td>
                                    <img id="head" src="<?php echo ($data["head"]); ?>" width="80" height="80" />
                                    <input type="hidden" name="head" id="image" class="input" value="<?php echo ($data["head"]); ?>" style="float: left"  runat="server">&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" ></td>
                                </td>
                            </tr>
                            <tr>
                                <th>密码</th>
                                <td><input type="password" name="password" class="input" id="password" value="">
                                    <span class="gray">请输入密码</span></td>
                            </tr>
                            <tr>
                                <th>确认密码</th>
                                <td><input type="password" name="pwdconfirm" class="input" id="pwdconfirm" value="">
                                    <span class="gray">请输入确认密码</span></td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td><input type="text" name="phone" value="<?php echo ($data["phone"]); ?>" class="input" id="phone" size="30"></td>
                            </tr>
                            <tr>
                                <th>E-mail</th>
                                <td><input type="text" name="email" value="<?php echo ($data["email"]); ?>" class="input" id="email" size="30"></td>
                            </tr>
                            <tr>
                                <th>真实姓名</th>
                                <td><input type="text" name="realname" value="<?php echo ($data["realname"]); ?>" class="input" id="realname"></td>
                            </tr>
                            <tr>
                                <th>状态</td>
                                <td><select name="status">
                                        <option value="1" selected>开启</option>
                                        <option value="0" >禁止</option>
                                    </select></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="role" value="2" />
                        <input type="hidden" name="group_id" value="4" />
                        <input type="hidden" name="storeid" value="<?php echo ($store["id"]); ?>" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
    <link rel="stylesheet" type="text/css"  href="/Public/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="/Public/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="/Public/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#uploadify").uploadify({
                'uploader'  : '/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'    : '/Public/Public/uploadify/uploadify.php',//实现上传的程序
                'script': "<?php echo U('Admin/Public/uploadone');?>",//实现上传的程序
                'method': 'get',
                'folder': '/Uploads/images/pc',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '/Public/Public/uploadify/add.gif',//替换上传钮扣
                'width'     : 80,//buttonImg的大小
                'height'    : 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    //alert(response);
                    getResult(response);//获得上传的文件路径
                }
            });
          
            var imgg = $("#image");
            function getResult(content){        
                imgg.val(content);
                $("#head").attr("src",content);
            }
        });
    </script>
</body>
</html>
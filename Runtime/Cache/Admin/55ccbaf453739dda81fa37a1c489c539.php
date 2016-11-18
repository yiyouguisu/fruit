<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>网站系统管理后台</title>
<link href="/fruit/trunk/fruit/Public/Admin/css/admin_style.css" rel="stylesheet" />
<link href="/fruit/trunk/fruit/Public/Admin/js/artDialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript" src="/fruit/trunk/fruit/Public/Editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "",
    JS_ROOT: "/fruit/trunk/fruit/Public/Admin/js/",
    TOKEN: "d8a7e4212dd72764fc54360bc619692c_0be21a07a2313806c7f61fc129e26832"
};
</script>
<script src="/fruit/trunk/fruit/Public/Admin/js/wind.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/jquery.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/layer/layer.js"></script>
<script src="/fruit/trunk/fruit/Public/Admin/js/jquery.cookie.js"></script>
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
<script type="text/javascript" charset="utf-8" src="/fruit/trunk/fruit/Public/Editor/UEditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/fruit/trunk/fruit/Public/Editor/UEditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/fruit/trunk/fruit/Public/Editor/UEditor/lang/zh-cn/zh-cn.js"></script>

<script>
    $(function(){
        var url='<?php echo U('Admin/Ueditor/index');?>';
        var ue = UE.getEditor('content',{
            serverUrl :url,
            UEDITOR_HOME_URL:'/fruit/trunk/fruit/Public/Editor/UEditor/',
        });

        ue.ready(function(){
            ue.execCommand('serverparam', {
               'userid': '1',
               'username': 'admin',
            });
        });
    })
</script>
<style type="text/css">
            .col-auto {
                overflow: hidden;
                _zoom: 1;
                _float: left;
                border: 1px solid #c2d1d8;
            }
            .col-right {
                float: right;
                width: 210px;
                overflow: hidden;
                margin-left: 6px;
                border: 1px solid #c2d1d8;
            }

            body fieldset {
                border: 1px solid #D8D8D8;
                padding: 10px;
                background-color: #FFF;
            }
            body fieldset legend {
                background-color: #F9F9F9;
                border: 1px solid #D8D8D8;
                font-weight: 700;
                padding: 3px 8px;
            }
            .picList li{ float: left; margin-top: 2px; margin-right: 5px;}
            #calhead{  display: none;}
            #caldays{display: none;}
            #calweeks{display: none;}
        </style>
    <script type="text/javascript">
    $(function(){
        getchildren(0,true);
        initvals();
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
        $(".servicearea").delegate("input:checkbox","change",function(){
            var obj=$(this);
            var servicearea=$(this).val();
            if($(this).attr("checked")){
               $.ajax({
                   type: "POST",
                   url: "<?php echo U('Admin/Store/ajax_checkservicearea');?>",
                   data: {'servicearea':servicearea},
                   dataType: "json",
                   success: function(data){
                              if(data.status==0){
                                alert(data.msg);
                                obj.attr("checked",false);
                              }
                            }
                }); 
            }
            
        });
    })
     
    function getchildren(a,b) {
        $.ajax({
            url: "<?php echo U('admin/Expand/getchildren');?>",
            async: false,
            data: { id: a },
            success: function (data) {
                data=eval("("+data+")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if(b)
                    {
                        ahtml += "<option value=''>--请选择--</option>";
                    }
                    for (var i = 0; i < data.length; i++) {
                        ahtml += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                    }
                    ahtml += "</select>";
                    $(".jgbox").append(ahtml);

                    if(a!=0){
                        var bhtml = "<ul class='switch_list cc' style='height:auto;overflow:auto;'>";
                        for (var i = 0; i < data.length; i++) {
                            bhtml += "<li><label><input type='checkbox' name='servicearea[]' value='" + data[i].id + "'><span>" + data[i].name + "</span></label></li>";
                        }                     
                        bhtml += "</ul>";
                        $(".servicearea").html(bhtml);
                    }
                }
            }
        });
                    getval();
    }
    function getval()
    {
        var vals="";
        $(".jgbox select").each(function(){
            var val=$(this).val();
            if(val!=null&&val!="")
            {
                vals+=',';
                vals+=val;
            }
        });
        if(vals!="")
        {
            vals=vals.substr(1);        
            $("#area").val(vals);
        }
    }
    function initvals()
    {
        var vals=$("#area").val();
        if(vals!=null&&vals!="")
        {
            var arr=new Array();
            arr=vals.split(",");
            for(var i=0;i<arr.length;i++)
            {
                if($.trim(arr[i]) !="")
                {
                    $(".jgbox select").last().val(arr[i]);
                    getchildren(arr[i],true);
                }
            }
        }
    }
  
</script>
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
            <form method="post"  action="<?php echo U('Admin/Store/add');?>">
                <div class="h_a">连锁店信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">门店名称</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="<?php echo ($data["title"]); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th width="80">登录名</th>
                                <td><input type="test" name="loginname" class="input" id="loginname" value=""></td>
                            </tr>
                            <tr>
                                <th>登录密码</th>
                                <td><input type="password" name="password" class="input" id="password" value="">
                                    <span class="gray">请输入登录密码</span></td>
                            </tr>
                            <tr>
                                <th>确认密码</th>
                                <td><input type="password" name="pwdconfirm" class="input" id="pwdconfirm" value="">
                                    <span class="gray">请输入确认密码</span></td>
                            </tr>
                            <tr>
                                <th>门店LOGO：</th>
                                <td>
                                    <input type="text" name="thumb" id="image" class="input length_5" value="" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" > <span class="gray"> 双击文本框查看图片</span>
                                </td>
                            </tr>
                            <tr>
                                <th>门店地址<input type="hidden" class="input" name="area" id="area" value="<?php echo ($data["area"]); ?>" ></th>
                                <td class="jgbox"></td>
                            </tr>
                            <tr>
                                <th>详细地址</th>
                                <td>
                                    <input type="text" name="address" class="input length_6 input_hd" placeholder="请输入店铺地址" id="address" value="<?php echo ($data["address"]); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>服务区域</th>
                                <td class="servicearea">
                                    
                                </td>
                            </tr>
                            <tr>
                                <th>门店负责人</th>
                                <td>
                                    <input type="text" name="username" class="input length_6 input_hd" placeholder="请输入门店负责人" id="username" value="<?php echo ($data["username"]); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>门店负责人联系电话</th>
                                <td>
                                    <input type="text" name="contact" class="input length_6 input_hd" placeholder="请输入门店负责人联系电话" id="contact" value="<?php echo ($data["contact"]); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>营业时间</th>
                                <td>
                                    <input type="text" name="workstarttime" class="input length_2 J_time" value="<?php echo ($data["workstarttime"]); ?>" style="width:40px;">
                                    -
                                    <input type="text" class="input length_2 J_time" name="workendtime" value="<?php echo ($data["workendtime"]); ?>" style="width:40px;">
                                </td>
                            </tr>
                            <tr>
                                <th>店铺简介</th>
                                <td>
                                    <textarea  name="content" id="content" style="width:100%;height:500px;"><?php echo ($data["content"]); ?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="applyid" value="<?php echo ($data["id"]); ?>" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <script src="/fruit/trunk/fruit/Public/Admin/js/common.js?v"></script>
      <script src="/fruit/trunk/fruit/Public/Admin/js/content_addtop.js"></script>

    <link rel="stylesheet" type="text/css"  href="/fruit/trunk/fruit/Public/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="/fruit/trunk/fruit/Public/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="/fruit/trunk/fruit/Public/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#uploadify").uploadify({
                'uploader'	: '/fruit/trunk/fruit/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg'	: '/fruit/trunk/fruit/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'	: '/fruit/trunk/fruit/Public/Public/uploadify/uploadify.php',//实现上传的程序
                'script'	: "<?php echo U('Admin/Public/uploadone');?>",//实现上传的程序
                'method'	: 'get',
                'folder'	: '/Uploads/images',//服务端的上传目录
                'auto'		: true,//自动上传
                'multi'		: true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': "",//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg'	: '/fruit/trunk/fruit/Public/Public/uploadify/add.gif',//替换上传钮扣
                'width'		: 80,//buttonImg的大小
                'height'	: 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    //alert(response);
                    getResult(response);//获得上传的文件路径
                }
            });
          
            var imgg = $("#image");
            function getResult(content){		
                imgg.val(content);
            }

            var imgg1 = $("#license");
            function getResult1(content){        
                imgg1.val(content);
            }
            
               $("#uploadify1").uploadify({
                'uploader'  : '/fruit/trunk/fruit/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '/fruit/trunk/fruit/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'    : '/fruit/trunk/fruit/Public/Public/uploadify/uploadify.php',//实现上传的程序
                'script'  : "<?php echo U('Admin/Public/upload');?>",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/images',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': "",//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '/fruit/trunk/fruit/Public/Public/uploadify/add.gif',//替换上传钮扣
                'width'     : 80,//buttonImg的大小
                'height'    : 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    // alert(response);
                
                    getResult1(response);//获得上传的文件路径
                }
            });
			
        });
    </script>
</body>
</html>
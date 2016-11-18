<include file="Common:Head" />
<include file="Common:ueditor" />
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
                   url: "{:U('Admin/Store/ajax_checkservicearea')}",
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
            url: "{:U('admin/Expand/getchildren')}",
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
                        var servicearea='{$data.servicearea}';

                        var bhtml = "<ul class='switch_list cc' style='height:auto;overflow:auto;'>";
                        for (var i = 0; i < data.length; i++) {
                            if(servicearea.split(',').indexOf(data[i].id+'')!=-1){
                                bhtml += "<li><label><input type='checkbox' name='servicearea[]' value='" + data[i].id + "' checked><span>" + data[i].name + "</span></label></li>";
                            }else{
                                bhtml += "<li><label><input type='checkbox' name='servicearea[]' value='" + data[i].id + "'><span>" + data[i].name + "</span></label></li>";
                            }
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
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post"  action="{:U('Admin/Store/edit')}">
                <div class="h_a">店铺信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">门店名称</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
                                </td>
                            </tr>
                            <tr>
                                <th width="80">登录名</th>
                                <td><input type="test" name="loginname" class="input" id="loginname" value="{$storeuser.username}"></td>
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
                                    <input type="text" name="thumb" id="image" class="input length_5" value="{$data.thumb}" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" > <span class="gray"> 双击文本框查看图片</span>
                                </td>
                            </tr>
                            <tr>
                                <th>门店地址<input type="hidden" class="input" name="area" id="area" value="{$data.area}" ></th>
                                <td class="jgbox"></td>
                            </tr>
                            <tr>
                                <th>详细地址</th>
                                <td>
                                    <input type="text" name="address" class="input length_6 input_hd" placeholder="请输入店铺地址" id="address" value="{$data.address}">
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
                                    <input type="text" name="username" class="input length_6 input_hd" placeholder="请输入门店负责人" id="username" value="{$data.username}">
                                </td>
                            </tr>
                            <tr>
                                <th>门店负责人联系电话</th>
                                <td>
                                    <input type="text" name="contact" class="input length_6 input_hd" placeholder="请输入门店负责人联系电话" id="contact" value="{$data.contact}">
                                </td>
                            </tr>
                            <tr>
                                <th>营业时间</th>
                                <td>
                                    <input type="text" name="workstarttime" class="input length_2 J_time" value="{$data.workstarttime}" style="width:40px;">
                                    -
                                    <input type="text" class="input length_2 J_time" name="workendtime" value="{$data.workendtime}" style="width:40px;">
                                </td>
                            </tr>
                            <tr>
                                <th>店铺简介</th>
                                <td>
                                    <textarea  name="content" id="content" style="width:100%;height:500px;">{$data.content}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="id" value="{$data.id}">
                        <input type="hidden" name="uid" value="{$data.uid}">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <script src="__JS__/common.js?v"></script>
      <script src="__JS__/content_addtop.js"></script>
    <link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){

            $("#uploadify").uploadify({
                'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'  : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                'script'    : "{:U('Admin/Public/uploadone')}",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/images',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': "",//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
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
            }

            var imgg1 = $("#license");
            function getResult1(content){        
                imgg1.val(content);
            }
            
               $("#uploadify1").uploadify({
                'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'    : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                'script'  : "{:U('Admin/Public/upload')}",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/images',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': "",//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
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
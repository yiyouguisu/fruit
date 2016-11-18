<include file="Common:Head" />
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
        </style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
    
        <include file="Common:Nav"/>
        <div class="common-form">
            <!---->
            <form method="post"  action="{:U('Admin/Image/add')}">
                <div class="h_a">工程案例信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">类别</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <select class="select_2" name="catid">
                                        <option value="" >请选择类别</option>
                                        <volist name="category" id="vo">
                                            <option value="{$vo['id']}" >{$vo['catname']}</option>
                                        </volist>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th width="80">标题</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
                                </td>
                            </tr>

                            <tr>
                                <th>图片：</th>
                                <td><input type="text" name="thumb" id="image" class="input length_5" value="" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" > <span class="gray"> 双击文本框查看图片</span>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>摘要</th>
                                <td>
                                    <textarea  name="description" id="description" class="valid" style="width:500px;height:80px;">{$data.description}</textarea>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>属性</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='type' value='1' >
                      <span>推荐</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='type' value='0'  checked>
                      <span>不推荐</span></label>
                  </li>
                </ul>
                                </td>
                            </tr>
                              <tr>
                                <th>审核</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='status' value='1' checked>
                      <span>审核</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='status' value='0' >
                      <span>未审核</span></label>
                  </li>
                </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <script src="__JS__/common.js?v"></script>
      <script src="__JS__/content_addtop.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content',{toolbar : 'Full'});
    </script>
    <link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#uploadify").uploadify({
                'uploader'	: '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg'	: '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                'script'	: '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                //'script'	: "{:U('Admin/Public/upload')}",//实现上传的程序
                'method'	: 'get',
                'folder'	: '/Uploads/images',//服务端的上传目录
                'auto'		: true,//自动上传
                'multi'		: true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg'	: '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
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

            var imgg1 = $("#image2");
            function getResult1(content){        
                imgg1.val(content);
            }
            
               $("#uploadify2").uploadify({
                'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                'script'    : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                //'script'  : "{:U('Admin/Public/upload')}",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/images',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
                'width'     : 80,//buttonImg的大小
                'height'    : 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    // alert(response);
                
                    getResult1(response);//获得上传的文件路径
                }
            });
			 $("#uploadify1").uploadify({
                'uploader'	: '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg'	: '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                'script'	: '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                //'script'	: "{:U('Admin/Public/upload')}",//实现上传的程序
                'method'	: 'get',
                'folder'	: '/Uploads/images',//服务端的上传目录
                'auto'		: true,//自动上传
                'multi'		: true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg'	: '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
                'width'		: 80,//buttonImg的大小
                'height'	: 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    // alert(response);
                    getResultimglist(response,queueID);//获得上传的文件路径
                }
            });
            var albums = $("#albums");
            var str="";
            function getResultimglist(content,queueID){
                str= "<li id='"+queueID+"'><input type='text' name='imglist[]' value='"+content+"' style='width:310px;' class='input' ondblclick='image_priview(this.value);'><a href=\"javascript:remove_div('"+queueID+"')\">移除</a></li>";
                albums.append(str);
            }
            if ($("input[name='islink']:checkbox").attr("checked")) {
                $("#linktr").show();
                $("#contenttr").hide();
            } else {
                $("#contenttr").show();
                $("#linktr").hide();
            }
            $("input[name='islink']:checkbox").click(function() {
                if ($(this).attr("checked")) {
                    $("#linktr").show();
                    $("#contenttr").hide();
                } else {
                    $("#contenttr").show();
                    $("#linktr").hide();
                }
            })
        });
    </script>
</body>
</html>
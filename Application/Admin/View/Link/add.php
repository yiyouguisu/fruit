<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <!--   <div class="nav">
          <ul class="cc">
                <li ><a href="{:U('Admin/Menu/index')}">后台菜单管理</a></li>
                <li class="current"><a href="{:U('Admin/Menu/add')}">添加菜单</a></li>
              </ul>
        </div>-->
        <include file="Common:Nav"/>
        <div class="common-form">
            <!---->
            <form method="post"  action="{:U('Admin/Link/add')}">
                <div class="h_a">链接信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">链接类型</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <select class="select_2" name="catid">
                                        <option value="" >请选择链接类型</option>
                                        {$category}
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th width="80">标题</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
                                    <input name="type" type="checkbox" id="type"  value="2" >
                                    <font color="red">文字链接</font>
                                </td>
                            </tr>

                            <tr >
                                <th width="80">链接地址</th>
                                <td>
                                    <input type="text" name="url" class="input length_6 input_hd" placeholder="请输入链接" id="url" value="{$data.url}">
                                    <span class="gray">请填写带http://的链接</span>
                                </td>
                            </tr>

                            <tr id="logo">
                                <th>LOGO图片：</th>
                                <td><input type="text" name="image" id="image" class="input length_5" value="" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" >    <span class="gray"> 双击文本框查看图片</span></td>
                            </tr>
                            <tr>
                                <th>链接描述</th>
                                <td>
                                    <textarea  name="description" id="description" class="valid" style="width:500px;height:80px;">{$data.description}</textarea>
                                    
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
            if ($("input[name='type']:checkbox").attr("checked")) {
                $("#logo").hide();
            } else {
                $("#logo").show();
            }
            $("input[name='type']:checkbox").click(function() {
                if ($(this).attr("checked")) {
                    $("#logo").hide();
                } else {
                    $("#logo").show();
                }
            })
        });
    </script>
</body>
</html>
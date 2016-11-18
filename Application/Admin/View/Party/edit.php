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
        </style>
        <script type="text/javascript">
                            $(function(){
                                load('{$data.storeid}','{$data.pid}');
                            })
                            
                        
                            function load(parentid,subcatid) {
                                $.ajax({
                                  type:"POST",
                                  url:"{:U('Admin/Ad/ajax_getproduct')}",
                                  data:{'storeid':parentid},
                                  dataType:"json",
                                  success:function(data){
                                       $('#pid').html('<option>请选择商品</option>');
                                        $.each(data,function(no,items){
                                            if(items.id==subcatid){
                                                $('#pid').append('<option value="'+items.id+'" selected>'+items.title+'</option>');
                                            }else{
                                                $('#pid').append('<option value="'+items.id+'">'+items.title+'</option>');
                                            }
                                        });
                                  }
                                });
                                    
                                    
                                }
                            </script>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post"  action="{:U('Admin/Party/edit')}">
                <div class="h_a">活动信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">标题</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
                                </td>
                            </tr>
                            <tr>
                                <th width="80">标签</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="label" class="input length_6 input_hd" placeholder="请输入标签" id="label" value="{$data.label}">
                                </td>
                            </tr>
                            <tr id="img">
                                <th>活动图片：</th>
                                <td><input type="text" name="thumb" id="image" class="input length_5" value="{$data.thumb}" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" > <span class="gray"> 双击文本框查看图片</span></td>
                            </tr>
                            <tr id="shop">
                                <th width="80">广告链接店铺</th>
                                <td>
                                     <select name="storeid"  onchange="load(this.value)">
                                        <option value="">请选择店铺</option>
                                        <volist name="shop" id="vo">
                                            <option value="{$vo.id}" <if condition="$data['storeid'] eq $vo['id']">selected</if>>{$vo.title}</option>
                                        </volist>
                                    </select>
                                </td>
                            </tr>
                            <tr id="product">
                                <th width="80">广告链接商品</th>
                                <td>
                                     <select name="pid" id="pid">
                                        <option value="">请选择商品</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>活动摘要</th>
                                <td>
                                    <textarea  name="description" id="description" class="valid" style="width:500px;height:80px;">{$data.description}</textarea>
                                    <span class="gray">不填写会自动截取内容正文的前250个字符</span>
                                </td>
                            </tr>
                            <tr id="contenttr">
                                <th>活动详情</th>
                                <td>
                                    <textarea  name="content" id="content" style="width:100%;height:500px;">{$data.content}</textarea>
                                </td>
                            </tr>
                            
                              <tr>
                                <th>审核</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='status' value='1' <if condition="$data['status'] eq '1' ">checked</if>>
                      <span>审核</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='status' value='0' <if condition="$data['status'] eq '0' ">checked</if>>
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
                        <input type="hidden" name="id" value="{$data.id}">
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
                // 'script' : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                'script'    : "{:U('Admin/Public/uploadone')}",//实现上传的程序
                'method'    : 'post',
                'folder'    : '/Uploads/images/',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': '',//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
                'width'     : 80,//buttonImg的大小
                'height'    : 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    //alert(response);
                    $("#image").val(response);
                }
            });
        });
    </script>
</body>
</html>
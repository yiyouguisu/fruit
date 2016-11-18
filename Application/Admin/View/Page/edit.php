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
    <form method="post"  action="{:U('Admin/Page/edit')}">
      <div class="h_a">单页内容</div>
      <div class="table_full">
        <table width="100%" class="table_form contentWrap">
        <tbody>
          <tr>
            <th width="80">标题</th>
            <td> <input type="hidden" name="id" value="{$data.id}"/>
                   <input type="hidden" name="catid" value="{$data.catid}"/>
                <span class="must_red">*</span><input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
              </td>
          </tr>
            <tr>
                                <th>图片：</th>
                                <td><input type="text" name="thumb" id="image" class="input length_5" placeholder="双击文本框查看图片" ondblclick='image_priview(this.value);' value="{$data.thumb}" style="float: left"  runat="server">&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" style="float: left" ></td>
             </tr>
          <tr>
            <th>内容摘要</th>
            <td>
                <textarea  name="description" id="description" class="valid" style="width:500px;height:80px;">{$data.description}</textarea>
                 <span class="gray">不填写会自动截取内容正文的前250个字符</span>
             </td>
          </tr>
          <tr>
            <th>内容正文</th>
            <td><span class="must_red">*</span>
                <textarea  name="content" id="content">{$data.content}</textarea>
             </td>
          </tr>
          
        </tbody>
      </table>
      </div>
      <div class="btn_wrap">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
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
          
        });
    </script>
</body>
</html>
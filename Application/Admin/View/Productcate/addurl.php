<include file="Common:Head" />
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
        <include file="Common:Nav"/>
        <div class="pop_nav">
            <ul class="J_tabs_nav">
                <li class="current"><a href="javascript:;;">基本属性</a></li>
            </ul>
        </div>
        <script type="text/javascript" src="__Editor__/ckeditor/ckeditor.js"></script>
        <form name="myform" id="myform" action="{:U('Admin/Productcate/add')}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="2">
            <input type="hidden" name="child" value="1">
            <div class="J_tabs_contents">
                <div>
                    <div class="h_a">基本属性</div>
                    <div class="table_full">
                        <table width="100%" class="table_form ">
                            <tr>
                                <th width="200">上级栏目：</th>
                                <td><select name="parentid" id="parentid">
                                        <option value='0'>≡ 作为一级栏目 ≡</option>
                                        {$Productcate}
                                    </select></td>
                            </tr>
                            <tr id="normal_add">
                                <th>栏目名称：</th>
                                <td><input type="text" name="catname" id="catname" class="input" value=""></td>
                            </tr>
                            <tr id="catdir_tr">
                                <th>英文名称：</th>
                                <td><input type="text" name="encatname" id="encatname" class="input" value=""></td>
                            </tr>
                            <tr>
                                <th>栏目缩略图：</th>
                                <td><input type="text" name="image" id="image" class="input length_5" value=""  runat="server" style="float: left"  ondblclick='image_priview(this.value);' > &nbsp; <input type="button" class="button" value="选择上传" id="uploadify" ><span class="gray"> 双击文本框查看图片</span></td>
                            </tr>
                            <tr>
                                <th>链接地址：</th>
                                <td><input type="text" name="url" id="url" class="input length_6" value=""></td>
                            </tr>
                            <tr>
                                <th>栏目简介：</th>
                                <td><textarea name="description" maxlength="255" style="width:300px;height:60px;"></textarea></td>
                            </tr>
                            <tr>
                                <th >是否显示：</th>
                                <td><ul class="switch_list cc ">
                                        <li>
                                            <label>
                                                <input type='radio' name='ismenu' value='1' checked>
                                                <span>显示</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='ismenu' value='0'  >
                                                <span>不显示</span></label>
                                        </li>
                                    </ul></td>
                            </tr>
                            <tr>
                                <th>显示排序：</th>
                                <td><input type="text" name="listorder" id="listorder" class="input" value="0"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 " type="submit">提交</button>
                </div>
            </div>
        </form>
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
                  //  alert(response);
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
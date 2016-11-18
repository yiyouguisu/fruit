<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <div class="common-form">
            <!---->
            <form class="J_ajaxForm" method="post"  action="{:U('Admin/Store/storeimport')}">
                <div class="h_a">第三方订单导入</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">excel文件</th>
                                <td>
                                    <input type="text" name="file" id="image" class="input length_5" value="" style="float: left"  runat="server">&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="storeid" value="{$storeid}" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">导入</button>
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
                'folder'    : '/Uploads/files',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'All Files(*. *)',//对话框的文件类型描述
                'fileExt': '*.xls;*.xlsx;',//可上传的文件类型
                'sizeLimit': "",//限制上传文件的大小2m(比特b)
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
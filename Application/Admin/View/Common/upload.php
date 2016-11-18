<link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
    $(function(){
        $(".upload").each(function(){
            inituploadify($(this));
        })
    })
   function inituploadify(a){
    a.uploadify({
        'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
        'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
        //'script' : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
        'script'    : "{:U('Admin/Public/uploadone')}",//实现上传的程序
        'method'    : 'post',
        'auto'      : true,//自动上传
        'multi'     : false,//是否多文件上传
        'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
        'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
        'sizeLimit': '',//限制上传文件的大小2m(比特b)
        'queueSizeLimit' :10, //可上传的文件个数
        'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
        'width'     : 80,//buttonImg的大小
        'height'    : 26,
        onComplete: function (evt, queueID, fileObj, response, data) {
            var obj="#"+$(evt.currentTarget).attr("data-id");
            $(obj).val(response);
        }
    });
   }
   </script>
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
<div class="wrap J_check_wrap">
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
        <form class="J_ajaxForm" action="<?php echo U('Admin/CacheProductUnit/save');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0" >
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="5%" align="center" >排序</td>
                            <td width="10%" align="center" >单位名称</td>
                            <td width="5%" align="center" >值</td>
                            <td width="25%" align="center" >单位LOGO名称</td>
                            <td width="15%" align="center" >单位LOGO</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody id="ProductUnit_list" >
                    <?php if(is_array($ProductUnitConfig)): $i = 0; $__LIST__ = $ProductUnitConfig;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="list_<?php echo ($key); ?>">
                            <td align="center" ><?php echo ($key); ?></td>
                            <td align="center" ><input name='ProductUnit[<?php echo ($key); ?>][listorder]' class="input length_1 mr5"  type='number' size='3' value='<?php echo ($vo["listorder"]); ?>' align="center"></td>
                            <td align="center" ><input type="text" class="input" style="width:100px" name="ProductUnit[<?php echo ($key); ?>][title]" value="<?php echo ($vo["title"]); ?>" /></td>
                            <td align="center" ><input type="text" class="input" style="width:40px" name="ProductUnit[<?php echo ($key); ?>][value]" value="<?php echo ($vo["value"]); ?>" /></td>
                            <td align="center" ><input type="text" class="input" style="width:305px;float: left" name="ProductUnit[<?php echo ($key); ?>][image]" value="<?php echo ($vo["image"]); ?>" id="icnoname_<?php echo ($key); ?>"/> <input type="button" id="uploadbtn<?php echo ($key); ?>" class="button upload" value="选择上传" data-id="<?php echo ($key); ?>"></td>
                            <td align="center" ><img src="<?php echo ($vo["image"]); ?>" id="image_<?php echo ($key); ?>"   height="20px"/></td>
                            <td align="center" > 
                                <a href="javascript:;" onClick="delx(<?php echo ($key); ?>);">删除</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <button class="btn" type="button" style="margin-top:5px" id="add">添加一个商品单位</button>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit" name="submit">保存</button>
                </div>
            </div>
        </form>
    </div>

     <script src="/Public/Admin/js/common.js?v"></script>
<script type="text/javascript">
$(function(){
    var xss=parseInt(<?php echo ($key); ?>)||0;
    $("#add").click(function(){
        xss++;
        var htmladd="<tr id='list_"+xss+"''>";
            htmladd +="<td align=\"center\" >"+xss+"</td>";
            htmladd +="<td align=\"center\" ><input type=\"number\" class=\"input length_1 mr5\" size='3' name=\"ProductUnit["+xss+"][listorder]\" value='0'  align=\"center\"/></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:100px\" name=\"ProductUnit["+xss+"][title]\" value='' /></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:40px\" name=\"ProductUnit["+xss+"][value]\" value='' /></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:305px;float: left\" name=\"ProductUnit["+xss+"][image]\"  id='icnoname_"+xss+"' value=''/><input type=\"button\" id='uploadbtn"+xss+"' class=\"button upload\" value=\"选择上传\" data-id='"+xss+"'></td>";
            htmladd +="<td align=\"center\" ><img id=\"image_"+xss+"\"   height=\"20px\"/></td>";
            htmladd +="<td align=\"center\" >";
            htmladd +="<a href=\javascript:void(0);\" onclick=\"delx('"+xss+"');\">删除</a>";
            htmladd +="</td>";
            htmladd +="</tr>";
        var htmltr=  $(htmladd);
            htmltr.appendTo($("#ProductUnit_list"));
            inituploadify(htmltr.find(".upload"));
    })
})

function delx(id){
    if(confirm("删除后不可恢复，并且删除完要确定保存后才会生效，确定要删除吗?")) $("#list_"+id).remove();
}
</script>
<link rel="stylesheet" type="text/css"  href="/Public/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="/Public/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="/Public/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
    $(function(){
        $(".upload").each(function(){
            inituploadify($(this));
        })
    })
   function inituploadify(a){
    a.uploadify({
        'uploader'  : '/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
        'cancelImg' : '/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
        //'script' : '/Public/Public/uploadify/uploadify.php',//实现上传的程序
        'script'    : "<?php echo U('Admin/Public/uploadone');?>",//实现上传的程序
        'method'    : 'post',
        'auto'      : true,//自动上传
        'multi'     : false,//是否多文件上传
        'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
        'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
        'sizeLimit': '',//限制上传文件的大小2m(比特b)
        'queueSizeLimit' :10, //可上传的文件个数
        'buttonImg' : '/Public/Public/uploadify/add.gif',//替换上传钮扣
        'width'     : 80,//buttonImg的大小
        'height'    : 26,
        onComplete: function (evt, queueID, fileObj, response, data) {
            $("#image_"+$(evt.currentTarget).attr("data-id")).attr("src",response);
            $("#icnoname_"+$(evt.currentTarget).attr("data-id")).val(response);
        }
    });
   }
   </script>
</body>
</html>
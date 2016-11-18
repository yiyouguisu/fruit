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
        <!-- <div class="nav">
          <ul class="cc">
                <li class="current"><a href="<?php echo U('Admin/Menu/index');?>">后台菜单管理</a></li>
                <li ><a href="<?php echo U('Admin/Menu/add');?>">添加菜单</a></li>
              </ul>
        </div>-->
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
        <!-- -->
        <div class="h_a">附件配置</div>
        <div class="table_full">
             <form method='post'  class="J_ajaxForm" id="myform" action="<?php echo U('Admin/Config/attach');?>">
                <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                    <tr>
                        <th width="140">后台允许上传附件大小:</th>
                        <td><input type="text" class="input"  name="uploadASize" value="<?php echo ($Site["uploadASize"]); ?>" size="40">B</td>
                    </tr>
                    <tr>
                        <th width="140">后台允许上传附件类型:</th>
                        <td><input type="text" class="input"  name="uploadAType" value="<?php echo ($Site["uploadAType"]); ?>" size="40"> <span class="gray">多个用"|"隔开</span></td>
                    </tr>
                     <tr>
                        <th width="140">前台允许上传附件大小:</th>
                        <td><input type="text" class="input"  name="uploadHSize" value="<?php echo ($Site["uploadHSize"]); ?>" size="40">B</td>
                    </tr>
                    <tr>
                        <th width="140">前台允许上传附件类型:</th>
                        <td><input type="text" class="input"  name="uploadHType" value="<?php echo ($Site["uploadHType"]); ?>" size="40"> <span class="gray">多个用"|"隔开</span></td>
                    </tr>
                   <tr>
                        <th>是否开启缩略图</th>
                        <td>
                          <ul class="switch_list cc ">
                              <li>
                                <label>
                                  <input type='radio' name='thumbShow' value='0' <?php if( $Site['thumbShow'] == '0' ): ?>checked<?php endif; ?>>
                                  <span>关闭</span></label>
                              </li>
                              <li>
                                <label>
                                  <input type='radio' name='thumbShow' value='1'  <?php if( $Site['thumbShow'] == '1' ): ?>checked<?php endif; ?>>
                                  <span>开启</span></label>
                              </li>
                              
                            </ul>
                        </td>
                    </tr>
                     <tr>
                        <th width="140">缩略图-宽:</th>
                        <td><input type="text" class="input" id="thumbW"  name="thumbW" value="<?php echo ($Site["thumbW"]); ?>" size="5">px </td>
                    </tr>
                    <tr>
                        <th width="140">缩略图-高:</th>
                        <td><input type="text" class="input" id="thumbH" name="thumbH" value="<?php echo ($Site["thumbH"]); ?>" size="5">px </td>
                    </tr>
                    <tr>
                        <th width="140">缩略图类型:</th>
                        <td>
                            <div class="locate">
                                <ul class="cc" id="J_locate_list_thumb">
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '1' ): ?>current<?php endif; ?>"><a href="" data-value="1">等比例缩放</a></li>
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '2' ): ?>current<?php endif; ?>"><a href="" data-value="2">缩放后填充</a></li>
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '3' ): ?>current<?php endif; ?>"><a href="" data-value="3">居中裁剪</a></li>
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '4' ): ?>current<?php endif; ?>"><a href="" data-value="4">左上角裁剪</a></li>
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '5' ): ?>current<?php endif; ?>"><a href="" data-value="5">右下角裁剪</a></li>
                                    <li style="width: 104px;" class="<?php if($Site['thumbType'] == '6' ): ?>current<?php endif; ?>"><a href="" data-value="6">固定尺寸缩放</a></li>
                                </ul>
                                <input id="J_locate_input_thumb" name="thumbType" type="hidden" value="<?php echo ($Site["thumbType"]); ?>">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>水印设置</th>
                        <td>
                          <ul class="switch_list cc ">
                              <li>
                                <label>
                                  <input type='radio' name='waterShow' value='0' <?php if( $Site['waterShow'] == '0' ): ?>checked<?php endif; ?>>
                                  <span>无水印</span></label>
                              </li>
                              <li>
                                <label>
                                  <input type='radio' name='waterShow' value='1'  <?php if( $Site['waterShow'] == '1' ): ?>checked<?php endif; ?>>
                                  <span>文字水印</span></label>
                              </li>
                              <li>
                                <label>
                                  <input type='radio' name='waterShow' value='2'  <?php if( $Site['waterShow'] == '2' ): ?>checked<?php endif; ?>>
                                  <span>图片水印</span></label>
                              </li>
                            </ul>
                        </td>
                    </tr>
                     <tr>
                        <th width="140">图片水印-宽:</th>
                        <td><input type="text" class="input" id="waterW"  name="waterW" value="<?php echo ($Site["waterW"]); ?>" size="5">px </td>
                    </tr>
                    <tr>
                        <th width="140">图片水印-高:</th>
                        <td><input type="text" class="input" id="waterH" name="waterH" value="<?php echo ($Site["waterH"]); ?>" size="5">px </td>
                    </tr>
                    <tr>
                        <th width="140">水印图片:</th>
                        <td><img src="<?php echo ($Site["waterImg"]); ?>" id="waterImg" width="<?php echo ($Site["waterW"]); ?>" height="<?php echo ($Site["waterH"]); ?>"><input type="hidden" name="waterImg" id="image" class="input length_5" value="<?php echo ($Site["waterImg"]); ?>" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" > <span class="gray"> 双击文本框查看图片</span></td>
                    </tr>
                    <tr>
                        <th width="140">图片水印透明度:</th>
                        <td><input type="number" class="input" min="0" max="100" name="waterTran" value="<?php echo ($Site["waterTran"]); ?>" size="5">%</td>
                    </tr>
                    <tr>
                        <th width="140">水印文字:</th>
                        <td><input type="text" class="input"  name="waterText" value="<?php echo ($Site["waterText"]); ?>" ></td>
                    </tr>
                    <tr>
                        <th width="140">水印文字颜色:</th>
                        <td>
                            <span class="color_pick J_color_pick"><em style="background: <?php echo ($Site["waterColor"]); ?>;" class="J_bg"></em></span>
                            <input type="hidden" name="waterColor" id="style_color" class="J_hidden_color" value="<?php echo ($Site["waterColor"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th width="140">水印文字大小:</th>
                        <td><input type="text" class="input"  name="waterFontsize" value="<?php echo ($Site["waterFontsize"]); ?>" ></td>
                    </tr>
                    <tr>
                        <th width="140">水印位置:</th>
                        <td>
                            <div class="locate">
                                <ul class="cc" id="J_locate_list_water">
                                    <li class="<?php if($Site['waterPos'] == '1' ): ?>current<?php endif; ?>"><a href="" data-value="1">左上</a></li>
                                    <li class="<?php if($Site['waterPos'] == '2' ): ?>current<?php endif; ?>"><a href="" data-value="2">中上</a></li>
                                    <li class="<?php if($Site['waterPos'] == '3' ): ?>current<?php endif; ?>"><a href="" data-value="3">右上</a></li>
                                    <li class="<?php if($Site['waterPos'] == '4' ): ?>current<?php endif; ?>"><a href="" data-value="4">左中</a></li>
                                    <li class="<?php if($Site['waterPos'] == '5' ): ?>current<?php endif; ?>"><a href="" data-value="5">中心</a></li>
                                    <li class="<?php if($Site['waterPos'] == '6' ): ?>current<?php endif; ?>"><a href="" data-value="6">右中</a></li>
                                    <li class="<?php if($Site['waterPos'] == '7' ): ?>current<?php endif; ?>"><a href="" data-value="7">左下</a></li>
                                    <li class="<?php if($Site['waterPos'] == '8' ): ?>current<?php endif; ?>"><a href="" data-value="8">中下</a></li>
                                    <li class="<?php if($Site['waterPos'] == '9' ): ?>current<?php endif; ?>"><a href="" data-value="9">右下</a></li>
                                </ul>
                                <input id="J_locate_input_water" name="waterPos" type="hidden" value="<?php echo ($Site["waterPos"]); ?>">
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">             
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
$(function(){
    //水印位置
    $('#J_locate_list_water > li > a').click(function(e){
        e.preventDefault();
        var $this = $(this);
        $this.parents('li').addClass('current').siblings('.current').removeClass('current');
        $('#J_locate_input_water').val($this.data('value'));
    });
    //缩图类型
    $('#J_locate_list_thumb > li > a').click(function(e){
        e.preventDefault();
        var $this = $(this);
        $this.parents('li').addClass('current').siblings('.current').removeClass('current');
        $('#J_locate_input_thumb').val($this.data('value'));
    });
    
    $("#waterW").change( function() {
      $("#waterImg").css("width",$(this).val());
    });
    $("#waterH").change( function() {
      $("#waterImg").css("height",$(this).val());
    });
});
</script>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
    <link rel="stylesheet" type="text/css"  href="/Public/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="/Public/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="/Public/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#uploadify").uploadify({
                'uploader'  : '/Public/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '/Public/Public/uploadify/cancel.gif',//单个取消上传的图片
                // 'script' : '/Public/Public/uploadify/uploadify.php',//实现上传的程序
                'script'    : "<?php echo U('Admin/Public/uploadone');?>",//实现上传的程序
                'method'    : 'post',
                'folder'    : '/Uploads/images/',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': '',//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '/Public/Public/uploadify/add.gif',//替换上传钮扣
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
                $("#waterImg").attr("src",content);
            }

            
            
        });
    </script>
</body>
</html>
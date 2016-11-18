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
        <div class="h_a">搜索</div>
        <form method="get" action="<?php echo U('Admin/Attachment/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上传时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        附件类型：
                        <select class="select_2" name="ext">
                            <option value=""  <?php if(empty($_GET['ext'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="jpg" <?php if( $_GET['ext']== 'jpg'): ?>selected<?php endif; ?>>jpg</option>
                            <option value="png" <?php if( $_GET['ext']== 'png'): ?>selected<?php endif; ?>>png</option>
                            <option value="jpeg" <?php if( $_GET['ext']== 'jpeg'): ?>selected<?php endif; ?>>jpeg</option>
                            <option value="gif" <?php if( $_GET['ext']== 'gif'): ?>selected<?php endif; ?>>gif</option>
                            <option value="doc" <?php if( $_GET['ext']== 'doc'): ?>selected<?php endif; ?>>doc</option>
                            <option value="xls" <?php if( $_GET['ext']== 'xls'): ?>selected<?php endif; ?>>xls</option>
                            <option value="zip" <?php if( $_GET['ext']== 'zip'): ?>selected<?php endif; ?>>zip</option>
                            <option value="rar" <?php if( $_GET['ext']== 'rar'): ?>selected<?php endif; ?>>rar</option>
                        </select>
                        会员名：<select class="select_2" name="is_admin" style="width:60px;">
                            <option value=""  <?php if(empty($_GET['is_admin'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['is_admin']== '1'): ?>selected<?php endif; ?>>后台</option>
                            <option value="0" <?php if( $_GET['is_admin']== '0'): ?>selected<?php endif; ?>>前台</option>
                        </select>
                        <input type="text" class="input length_2" name="username" value="<?php echo ($_GET['username']); ?>" style="width:120px;">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >用户名</td>
                            <td width="10%" align="center" >上传类型</td>
                            <td width="20%" align="left" >附件名称</td>
                            <td width="10%" align="center" >附件大小</td>
                            <td width="15%"  align="center" >上传时间</td>
                            <td width="20%"  align="left" >说明</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="center" >
                                <?php if($vo["isadmin"] == 1): echo ($vo['username']); endif; ?>
                                <?php if($vo["isadmin"] == 0): echo getuserinfo($vo['uid']); endif; ?>
                            </td>
                            <td align="center" ><?php echo ($vo['catname']); ?></td>
                            <td align="left" ><img src="/Public/Admin/images/ext/<?php echo ($vo["ext"]); ?>.gif" />||<?php echo ($vo["name"]); ?></td>
                            <td align="center" ><?php echo format_bytes($vo['size']);?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="left" ><?php echo ($vo["info"]); ?></td>
                            <td align="center" > 
                                <a href="javascript:;" onClick="image_priview('<?php echo ($vo["url"]); ?>')">查看</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> <?php echo ($Page); ?> </div>
            </div>
            </div>
         
            
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
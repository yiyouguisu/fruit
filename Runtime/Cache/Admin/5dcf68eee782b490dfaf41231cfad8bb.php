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
        <form method="get" action="<?php echo U('Admin/User_upload/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上传时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        资料类型：
                        <select class="select_2" name="catid">
                            <option value="" >全部</option>
                            <?php echo ($category); ?>
                        </select>
                        会员名：<input type="text" class="input length_2" name="username" value="<?php echo ($_GET['username']); ?>" style="width:80px;">
                        真实姓名：<input type="text" class="input length_2" name="realname" value="<?php echo ($_GET['realname']); ?>" style="width:80px;">
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>待审核</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>审核成功</option>
                            <option value="3" <?php if( $_GET['status']== '3'): ?>selected<?php endif; ?>>审核失败</option>
                        </select>
                        
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
                            <td width="10%" align="center" >真实姓名</td>
                            <td width="20%" align="left" >所属分类</td>
                            <td width="20%" align="left" >资料名称</td>
                            <td width="10%"  align="center" >状态</td>
                            <td width="15%"  align="center" >上传时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="center" ><?php echo getuserinfo($vo['uid']);?></td>
                            <td align="center" ><?php echo getuser($vo['uid'],'realname');?></td>
                            <td align="left" ><?php echo ($vo["catname"]); ?></td>
                            <td align="left" >
                                <span title="<?php echo ($vo["filename"]); ?>"><?php echo ($vo["sortitle"]); ?>(<a href="javascript:;" onClick="image_priview('<?php echo ($vo["url"]); ?>')">查看</a>)</span>
                            </td>
                            <td align="center" >
                                <?php if($vo["status"] == 1): ?><span style="color: gray">待审核</span><?php endif; ?>
                                <?php if($vo["status"] == 2): ?><span style="color: green">审核成功</span><?php endif; ?>
                                <?php if($vo["status"] == 3): ?><span style="color: red">审核失败</span><?php endif; ?>
                            </td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" > 
                                <?php if(authcheck('Admin/Userupload/review')): ?><a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Userupload/review',array('id'=>$vo['id']));?>','资料审核',1,700,420)">审核</a>
                                <?php else: ?>
                                    <font color="#cccccc">审核</font><?php endif; ?>
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
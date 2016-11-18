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

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="<?php echo U('Admin/Company/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                              申请时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_POST['start_time']); ?>" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_POST['end_time']); ?>" style="width:80px;">
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>企业名称</option>
                         <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>企业负责人</option>
                         <option value='2' <?php if( $searchtype == '2' ): ?>selected<?php endif; ?>>负责人联系方式</option>
                         <option value='3' <?php if( $searchtype == '3' ): ?>selected<?php endif; ?>>绑定邮箱</option>
                         <option value='4' <?php if( $searchtype == '4' ): ?>selected<?php endif; ?>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
   <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="4%" align="center" >序号</td>
            <td width="12%" align="center" >企业名称</td>
            <td width="12%" align="center" >企业编号</td>
            <td width="15%" align="center" >企业LOGO</td>
            <td width="12%" align="center" >企业负责人</td>
            <td width="12%" align="center" >负责人联系方式</td>
            <td width="12%" align="center" >绑定邮箱</td>
            <td width="12%"  align="center" >申请时间</td>
            <td width="12%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
            <td align="center"><?php echo ($vo["id"]); ?></td>
            <td align="center"><?php echo ($vo["title"]); ?></td>
            <td align="center"><?php echo ((isset($vo["companynumber"]) && ($vo["companynumber"] !== ""))?($vo["companynumber"]):"未填写"); ?></td>
            <td align="center" >
              <?php if($vo['head'] == null): ?>暂无缩略图<?php else: ?><img width="80" height="80" src="<?php echo ($vo["head"]); ?>" /><?php endif; ?>
            </td>
            <td align="center"><?php echo ($vo["username"]); ?></td>
            <td align="center"><?php echo ($vo["tel"]); ?></td>
            <td align="center" ><?php echo ($vo["email"]); ?></td>
            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
            <td  align="center" >
                  <?php if(authcheck('Admin/Company/edit')): ?><a href="<?php echo U('Admin/Company/edit',array('id'=>$vo['id']));?>" >修改基本资料 </a>  |
                            <?php else: ?>
                            <font color="#cccccc">修改基本资料</font> |<?php endif; ?>  
                        <?php if(authcheck('Admin/Company/del')): ?><a href="<?php echo U('Admin/Company/del',array('id'=>$vo['id']));?>"  class="del">删除 </a> 
                            <?php else: ?>
                            <font color="#cccccc">删除 </font><?php endif; ?> 
            </td> 
          </tr><?php endforeach; endif; ?>
        </tbody>
      </table>
            <div class="p10">
                    <div class="pages"> <?php echo ($Page); ?> </div>
                </div>
   </div>
</div>
<script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
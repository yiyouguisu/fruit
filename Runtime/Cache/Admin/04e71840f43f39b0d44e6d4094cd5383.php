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
        <form method="post" action="<?php echo U('Admin/Integral/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                              可用积分：
                        <input type="text" name="start_integral" class="input length_2" value="<?php echo ($_POST['start_integral']); ?>" style="width:80px;">
                       -
                        <input type="text" class="input length_2" name="end_integral" value="<?php echo ($_POST['end_integral']); ?>" style="width:80px;">
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>用户名</option>
                         <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>手机号码</option>
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
            <td width="10%" align="center" >用户名</td>
            <td width="4%" align="center" >性别</td>
            <td width="8%" align="center" >可用积分</td>
            <td width="8%" align="center" >赠送积分</td>
            <td width="8%" align="center" >已用积分</td>
            <td width="8%" align="center" >累计积分</td>
            <td width="10%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
            <td align="center"><?php echo ($vo["id"]); ?></td>
            <td align="center"><?php echo getuserinfo($vo['id']);?></td>
            <td align="center" >
                 <?php if( $vo["sex"] == '0'): ?>未知<?php endif; ?>
                 <?php if( $vo["sex"] == '1'): ?>男<?php endif; ?>
                 <?php if( $vo["sex"] == '2'): ?>女<?php endif; ?>
            </td>
            <td align="center"><?php echo ($vo["useintegral"]); ?></td>
            <td align="center"><?php echo ($vo["giveintegral"]); ?></td>
            <td align="center"><?php echo ($vo["payed"]); ?></td>
            <td align="center"><?php echo ($vo["totalintegral"]); ?></td>
            <td  align="center" >
                <?php if(authcheck('Admin/Integral/edit')): ?><a href="<?php echo U('Admin/Integral/edit',array('id'=>$vo['id']));?>" >赠送 </a>  |
                            <?php else: ?>
                            <font color="#cccccc">赠送</font> |<?php endif; ?>  
                  <?php if(authcheck('Admin/Integral/log')): ?><a href="<?php echo U('Admin/Integral/log',array('id'=>$vo['id']));?>" >使用记录 </a>
                            <?php else: ?>
                            <font color="#cccccc">使用记录</font><?php endif; ?>  
                        <!-- <?php if(authcheck('Admin/Integral/del')): ?><a href="<?php echo U('Admin/Integral/del',array('id'=>$vo['id']));?>"  class="del">删除 </a> 
                            <?php else: ?>
                            <font color="#cccccc">删除 </font><?php endif; ?>  -->
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
<script src="/Public/Admin/js/content_addtop.js"></script> 
</body>
</html>
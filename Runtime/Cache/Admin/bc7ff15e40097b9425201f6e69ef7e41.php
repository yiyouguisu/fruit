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
        <form method="post" action="<?php echo U('Admin/feedback/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                            反馈时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_POST['start_time']); ?>" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_POST['end_time']); ?>" style="width:80px;">
                        状态：
                        <select class="select_2" name="status" style="width:80px;">
                            <option value=""  <?php if(empty($_POST['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_POST['status']== '1'): ?>selected<?php endif; ?>>处理中</option>
                            <option value="2" <?php if( $_POST['status']== '2'): ?>selected<?php endif; ?>>处理成功</option>
                            <option value="3" <?php if( $_POST['status']== '3'): ?>selected<?php endif; ?>>处理失败</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>内容</option>
                        <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>反馈人</option>
                         <option value='3' <?php if( $searchtype == '3' ): ?>selected<?php endif; ?>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="<?php echo U('Admin/feedback/del');?>" method="post" >
        <table width="100%" cellspacing="0">
         <thead>
            <tr>
              <td width="4%" align="center"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
              <td width="4%" align="center" >序号</td>
              <td width="15%" align="center" >用户名</td>
              <td width="30%" align="center" >内容</td>
              <td width="15%"  align="center" >发布时间</td>
              <td width="10%"  align="center" >是否处理</td>
              <td width="15%" align="center" >操作</td> 
            </tr>
          </thead>
          <tbody>
          <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
              <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
              <td align="center"><?php echo ($vo["id"]); ?></td>
              <td align="center"><?php echo ($vo["username"]); ?></td>
              <td align="center"><?php echo str_cut($vo['content'],30);?></td>
              <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
              <td align="center" >
                  <?php if($vo['status'] == 1): ?><font color="gray">处理中</font><?php endif; ?>
                  <?php if($vo['status'] == 3): ?><font color="red">处理失败</font><?php endif; ?>
                  <?php if($vo['status'] == 2): ?><font color="green">处理成功</font><?php endif; ?>
              </td>
              <td  align="center" >
                    <?php if(authcheck('Admin/Feedback/feedbackcheck')): ?><a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Feedback/feedbackcheck',array('id'=>$vo['id']));?>','处理反馈',1,600,400)">处理</a> | 
                              <?php else: ?>
                              <font color="#cccccc">处理</font> |<?php endif; ?>  
                          <?php if(authcheck('Admin/feedback/delete')): ?><a href="<?php echo U('Admin/feedback/delete',array('id'=>$vo['id']));?>"  class="del">删除 </a> 
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
        <div class="btn_wrap">
          <div class="btn_wrap_pd">
              <label class="mr20">
              <input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>     
            <?php if(authcheck('Admin/feedback/del')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button><?php endif; ?>
          </div>
        </div>
    </form>
 </div>
<script src="/Public/Admin/js/common.js?v"></script>
<script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
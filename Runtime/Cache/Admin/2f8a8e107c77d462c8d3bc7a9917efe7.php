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
        <form method="get" action="<?php echo U('Admin/apply/shop');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        
                        审核：
                        <select class="select_2" name="status" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>审核中</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>审核成功</option>
                            <option value="3" <?php if( $_GET['status']== '3'): ?>selected<?php endif; ?>>审核失败</option>
                        </select> 
                        关键字：
                        <select class="select_2" name="searchtype" style="width:70px;">
                            <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>联系人</option>
                            <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>联系方式</option>
                            <option value='2' <?php if( $searchtype == '2' ): ?>selected<?php endif; ?>>邮箱</option>
                            <option value='3' <?php if( $searchtype == '3' ): ?>selected<?php endif; ?>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="<?php echo U('Admin/apply/action');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="20%" align="left" >公司名称</td>
                            <td width="10%" align="center" >联系人</td>
                            <td width="10%" align="center" >联系方式</td>
                            <td width="15%" align="center" >邮箱</td>
                            <td width="10%" align="center" >状态</td>
                            <td width="15%"  align="center" >申请时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="left" ><?php echo ($vo["company"]); ?></td>
                            <td align="center" ><?php echo ($vo["username"]); ?></td>
                            <td align="center" ><?php echo ($vo["tel"]); ?></td>
                            <td align="center" ><?php echo ($vo["email"]); ?></td>
                            <td align="center" ><?php echo getreviewstatus($vo['status']);?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" > 
                               <?php if(($vo['isallot']) == "0"): if(($vo['status']) == "2"): if(authcheck('Admin/Store/add')): ?><a href="<?php echo U('Admin/Store/add',array('applyid'=>$vo['id']));?>" >分配门店</a> 
                <?php else: ?>
                 <font color="#cccccc">分配门店</font><?php endif; endif; endif; ?>
            <?php if(($vo['status']) == "1"): if(authcheck('Admin/apply/shopreview')): ?><a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/apply/shopreview',array('id'=>$vo['id']));?>','审核',1,700,400)">审核</a>
                <?php else: ?>
                 <font color="#cccccc">审核</font><?php endif; endif; ?>
                            <?php if(authcheck('Admin/apply/shopdelete')): ?><a href="<?php echo U('Admin/apply/shopdelete',array('id'=>$vo['id']));?>"  class="del">删除</a> 
                <?php else: ?>
                 <font color="#cccccc">删除</font><?php endif; ?> 
                                
                               
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> <?php echo ($Page); ?> </div>
            </div>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>   
                     
                        <?php if(authcheck('Admin/apply/shopdel')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="shopdel">删除</button><?php endif; ?>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
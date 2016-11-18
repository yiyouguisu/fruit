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
        <form method="get" action="<?php echo U('Admin/Product/search');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        搜索次数：
                        <select class="select_2" name="querytype" style="width:60px;">
                            <option value=""  <?php if(empty($_GET['querytype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="eq" <?php if( $_GET['querytype']== 'eq'): ?>selected<?php endif; ?>>等于</option>
                            <option value="gt" <?php if( $_GET['querytype']== 'gt'): ?>selected<?php endif; ?>>大于</option>
                            <option value="lt" <?php if( $_GET['querytype']== 'lt'): ?>selected<?php endif; ?>>小于</option>
                        </select><input type="number" min="0" class="input length_2" name="hit" style="width:60px;" value="<?php echo ($_GET['hit']); ?>">
                        关键字：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($_GET['keyword']); ?>" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="<?php echo U('Admin/Product/action');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center">排序</td>
                            <td width="5%" align="center" >ID</td>
                            <td width="30%" align="left" >关键字</td>
                            <td width="10%" align="center" >搜索次数</td>
                            <td width="15%"  align="center" >最后一次搜索时间</td>
                            <td width="15%"  align="center" >第一次搜索时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" ><input name='listorders[<?php echo ($vo["id"]); ?>]' class="input length_1 mr5"  type='number' size='3' value='<?php echo ($vo["listorder"]); ?>' align="center"></td>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="left" ><?php echo ((isset($vo["keyword"]) && ($vo["keyword"] !== ""))?($vo["keyword"]):"未填写"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["hit"]) && ($vo["hit"] !== ""))?($vo["hit"]):"0"); ?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["lastupdatetime"])); ?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" > 
                                <a href="<?php echo U('Admin/Product/searchdelete',array('id'=>$vo['id']));?>"  class="del">删除</a>  
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
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="searchlistorder" >排序</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="searchdel">删除</button>
                </div>
            </div>
        </form>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
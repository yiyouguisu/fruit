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
        <form method="get" action="<?php echo U('Admin/Evaluate/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        评价时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        
                        关键字：
                        <select class="select_2" name="searchtype" style="width:70px;">
                            <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>评论内容</option>
                            <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>用户名</option>
                            <option value='2' <?php if( $searchtype == '2' ): ?>selected<?php endif; ?>>手机号</option>
                            <option value='3' <?php if( $searchtype == '3' ): ?>selected<?php endif; ?>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="<?php echo U('Admin/Evaluate/action');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >评分</td>
                            <td width="45%" align="left" >评价内容</td>
                            <td width="15%"  align="center" >评价时间</td>
                            <td width="10%"  align="center" >评价用户</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="center" ><?php echo ($vo["total"]); ?></td>
                            <td align="left" >
                               <span title="<?php echo ($vo["content"]); ?>"><?php echo ($vo["sortitle"]); ?></span></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" ><?php echo ($vo["username"]); ?></td>
                            <td align="center" > 
                                <a href="<?php echo U('Admin/Evaluate/delete',array('id'=>$vo['id']));?>"  class="del">删除</a> 
                                <a href="javascript:;"  class="showpic" onclick="showpic('#layer-photos-demo_<?php echo ($vo["id"]); ?>');">查看图片</a> 
                               <div id="layer-photos-demo_<?php echo ($vo["id"]); ?>" class="layer-photos-demo" style="display:none;">
                                   <?php if(is_array($vo['imglist'])): $i = 0; $__LIST__ = $vo['imglist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><img layer-pid="" layer-src="<?php echo ($v); ?>" src="<?php echo ($v); ?>" alt="商品评论图片"><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
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
                        <?php if(authcheck('Admin/Evaluate/del')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button><?php endif; ?>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/layer/layer.js"></script>
    <script src="/Public/Admin/js/layer/extend/layer.ext.js"></script>
    <script>
        function showpic(obj) {
            //调用示例
            layer.ready(function(){ //为了layer.ext.js加载完毕再执行
              layer.photos({
                  photos: obj
              });
              $(obj).find("img:first").click();
            }); 

        }  
    </script>
</body>
</html>
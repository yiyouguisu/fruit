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
        <form method="get" action="<?php echo U('Admin/Product/storehouse');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上架时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        所属类别：
                        <select class="select_2" name="subcatid">
                            <option value="" <?php if(empty($_GET['subcatid'])): ?>selected<?php endif; ?>>全部</option>
                            <?php echo ($category); ?>
                        </select>
                        商品所属：
                        <select class="select_2" name="type" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['type'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['type']== '1'): ?>selected<?php endif; ?>>一般商品</option>
                            <option value="2" <?php if( $_GET['type']== '2'): ?>selected<?php endif; ?>>团购商品</option>
                            <option value="3" <?php if( $_GET['type']== '3'): ?>selected<?php endif; ?>>预购商品</option>
                            <option value="4" <?php if( $_GET['type']== '4'): ?>selected<?php endif; ?>>称重商品</option>
                        </select>
                        审核：
                        <select class="select_2" name="status" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>审核中</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>审核成功</option>
                            <option value="3" <?php if( $_GET['status']== '3'): ?>selected<?php endif; ?>>审核失败</option>
                        </select>   
                        库存：
                        <select class="select_2" name="stocktype" style="width:60px;">
                            <option value=""  <?php if(empty($_GET['stocktype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="eq" <?php if( $_GET['stocktype']== 'eq'): ?>selected<?php endif; ?>>等于</option>
                            <option value="gt" <?php if( $_GET['stocktype']== 'gt'): ?>selected<?php endif; ?>>大于</option>
                            <option value="lt" <?php if( $_GET['stocktype']== 'lt'): ?>selected<?php endif; ?>>小于</option>
                        </select><input type="number" min="0" class="input length_2" name="stock" style="width:60px;" value="<?php echo ($_GET['stock']); ?>">
                        关键字：
                        <select class="select_2" name="searchtype" style="width:120px;">
                            <option value='0' <?php if( $searchtype == '0' ): ?>selected<?php endif; ?>>商品名称</option>
                            <option value='1' <?php if( $searchtype == '1' ): ?>selected<?php endif; ?>>商品简介</option>
                            <!-- <option value='2' <?php if( $searchtype == '2' ): ?>selected<?php endif; ?>>用户名</option> -->
                            <option value='3' <?php if( $searchtype == '3' ): ?>selected<?php endif; ?>>ID</option>
                        </select>
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
                            <td width="8%" align="center" >产品编号</td>
                            <td width="15%" align="left" >产品名称</td>
                            <td width="12%" align="center" >所属类别</td>
                            <td width="6%" align="center" >产品规格</td>
                            <td width="6%" align="center" >产品库存</td>
                            <td width="6%" align="center" >产品售价</td>
                            <td width="12%"  align="center" >上架时间</td>
                            <td width="12%"  align="center" >所属店铺</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" ><input name='listorders[<?php echo ($vo["id"]); ?>]' class="input length_1 mr5"  type='number' size='3' value='<?php echo ($vo["listorder"]); ?>' align="center"></td>
                            <td align="center" ><?php echo ($vo["id"]); ?></td>
                            <td align="center" ><?php echo ((isset($vo["productnumber"]) && ($vo["productnumber"] !== ""))?($vo["productnumber"]):"未填写"); ?></td>
                            <td align="left" >
                                <?php if($vo["status"] == 1): ?><span style="color: gray">[审核中]</span><?php endif; ?>
                                <?php if($vo["status"] == 2): ?><span style="color: green">[审核成功]</span><?php endif; ?>
                                <?php if($vo["status"] == 3): ?><span style="color: red">[审核失败]</span><?php endif; ?>
                                <?php if($vo["isoff"] == 1): ?><span style="color: red">[下架]</span><?php endif; ?>
                                <?php if($vo["ishot"] == 1): ?><span style="color: red">[促销]</span><?php endif; ?>
                               <?php if($vo["isindex"] == 1): ?><span style="color: red">[置顶]</span><?php endif; ?>
                               <?php if($vo["isout"] == 1): ?><span style="color: red">[缺货]</span><?php endif; ?>
                                <span title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["sortitle"]); ?></span></td>
                            <td align="center" ><?php echo ($vo["catname"]); ?></td>
                            <td align="center" ><?php echo ($vo["standard"]); ?></td>
                            <td align="center" ><?php echo ($vo["stock"]); ?></td>
                            <td align="center" ><?php echo ($vo["nowprice"]); ?></td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["shelvestime"])); ?></td>
                            <td align="center" ><?php echo getstoreinfo($vo['storeid']);?></td>
                            <td align="center" > 
                                <!-- <?php if(authcheck('Admin/Product/review')): ?><a href="<?php echo U('Admin/Product/review',array('id'=>$vo['id']));?>" >审核</a>  |
                <?php else: ?>
                 <font color="#cccccc">审核</font> |<?php endif; ?>  -->
                               <a href="<?php echo U('Admin/Product/edit',array('id'=>$vo['id']));?>" >修改</a>  |
              <a href="<?php echo U('Admin/Product/delete',array('id'=>$vo['id']));?>"  class="del">删除</a> 
                                
                               
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
                     <?php if(authcheck('Admin/Product/listorder')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button><?php endif; ?> 
                         <?php if(authcheck('Admin/Product/pushs')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="pushs">置顶</button><?php endif; ?>
                      <?php if(authcheck('Admin/Product/unpushs')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="unpushs">取消置顶</button><?php endif; ?>
                        <?php if(authcheck('Admin/Product/off')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="off">下架</button><?php endif; ?>
                      <?php if(authcheck('Admin/Product/unoff')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="unoff">取消下架</button><?php endif; ?>
                        <?php if(authcheck('Admin/Product/del')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button><?php endif; ?>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?>
<!doctype html>
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
</head><body class="J_scroll_fixed">
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
        <form method="get" action="<?php echo U('Admin/Companyorder/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> 
                    <span class="mr20">
                        下单时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">

                        订单来源：
                        <select class="select_2" name="ordersource" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['ordersource'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['ordersource']== '1'): ?>selected<?php endif; ?>>手机web</option>
                            <option value="2" <?php if( $_GET['ordersource']== '2'): ?>selected<?php endif; ?>>App</option>
                            <option value="3" <?php if( $_GET['ordersource']== '3'): ?>selected<?php endif; ?>>饿了么</option>
                            <option value="4" <?php if( $_GET['ordersource']== '4'): ?>selected<?php endif; ?>>口碑外卖</option>
                        </select>
                        
                        店铺来源：
                        <select class="select_2" name="storeid" style="width:120px;">
                            <option value=""  <?php if(empty($_GET['storeid'])): ?>selected<?php endif; ?>>全部</option>
                            <?php if(is_array($store)): $i = 0; $__LIST__ = $store;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($_GET['storeid']== $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        第三方平台：
                        <select class="select_2" name="isthirdparty" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['isthirdparty'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['isthirdparty']== '1'): ?>selected<?php endif; ?>>是</option>
                            <option value="0" <?php if( $_GET['isthirdparty']== '0'): ?>selected<?php endif; ?>>否</option>
                        </select>
                        派发：
                        <select class="select_2" name="issend" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['issend'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['issend']== '1'): ?>selected<?php endif; ?>>已派发</option>
                            <option value="0" <?php if( $_GET['issend']== '0'): ?>selected<?php endif; ?>>未派发</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($_GET['keyword']); ?>" placeholder="请输入订单号...">
                        
                        <button class="btn">搜索</button>
                    </span>
                 </div>
            </div>
        </form> 

        <form action="<?php echo U('Admin/Companyorder/del');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="20%" align="center" >订单号</td>
                            <td width="10%" align="center" >订单来源</td>
                            <td width="8%" align="center" >订单金额</td>
                            <td width="12%"  align="center" >订单时间</td>
                            <td width="12%"  align="center" >收货人信息</td>
                            <td width="12%"  align="center" >收货地址</td>
                            <td width="12%"  align="center" >派送时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                 <?php $money_total1=0;?>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" >[<a href="javascript:;" data-href="<?php echo U('Admin/Order/download',array('orderid'=>$vo['orderid']));?>" onclick="image_priview('<?php echo ($vo["ordercode"]); ?>');">查看二维码</a>]<?php echo ($vo["orderid"]); ?></td>
                            <td align="center" ><?php echo getordersource($vo['ordersource']);?></td>
                            <td align="center" >
                                ￥<?php $money_total1+=$vo['total'];?>
                                <?php echo ($vo["total"]); ?><br/>配送费￥<?php echo ((isset($vo["delivery"]) && ($vo["delivery"] !== ""))?($vo["delivery"]):"0.00"); ?>
                            </td>
                            <td align="center" ><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" ><?php echo ($vo["name"]); ?><br/><?php echo ($vo["phone"]); ?></td>
                            <td align="center" ><?php echo getarea($vo['area']);?><br/><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></td>
                            <td align="center" ><?php echo getordersendtime($vo['orderid']);?></td>
                            <td align="center" > 
                                <?php if($vo['status'] != 5): if(empty($vo['storeid'])): if(authcheck('Admin/Companyorder/deal')): ?><a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Companyorder/deal',array('id'=>$vo['id']));?>','派发订单',1,700,400)">派发</a>
                                            <?php else: ?>
                                            <font color="#cccccc">派发</font><?php endif; ?> 
                                        <?php else: ?>
                                        已派发</br><?php echo getstoreinfo($vo['storeid']); endif; ?>
                                    <?php else: ?>
                                    订单已完成<br/><?php echo (date("Y-m-d H:i:s",$vo["donetime"])); endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                      <tr style="font-weight:bold">
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td align="center">小计:</th>
                        <td align="center">￥<?php echo ((isset($money_total1) && ($money_total1 !== ""))?($money_total1):"0.00"); ?></th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                        <td>&nbsp;</th>
                      </tr>
                    </tbody>
                </table>
                <div class="p10">
                    <div class="pages"> <?php echo ($Page); ?> </div>
                    <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>   

                    <?php if(authcheck('Admin/Companyorder/del')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button><?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="btn_wrap">
                <div class="btn_wrap_pd">


                    <?php if(authcheck('Admin/Companyorder/excel')): ?><form method="post" action="<?php echo U('Admin/Companyorder/excel');?>">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="<?php echo ($_GET['start_time']); ?>" >
                            <input type="hidden"  name="end_time" value="<?php echo ($_GET['end_time']); ?>" >
                            <input type="hidden"  name="ordersource" value="<?php echo ($_GET['ordersource']); ?>" >
                            <input type="hidden"  name="isthirdparty" value="<?php echo ($_GET['isthirdparty']); ?>" >
                            <input type="hidden"  name="issend" value="<?php echo ($_GET['issend']); ?>" >
                            <input type="hidden"  name="storeid" value="<?php echo ($_GET['storeid']); ?>" >
                            <input type="hidden"  name="keyword" value="<?php echo ($_GET['keyword']); ?>" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form><?php endif; ?>


                </div>
            </div>


    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
        <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
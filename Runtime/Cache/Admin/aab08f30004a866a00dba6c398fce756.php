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
        <form method="get" action="<?php echo U('Admin/Order/done');?>">
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
                            <option value="5" <?php if( $_GET['ordersource']== '5'): ?>selected<?php endif; ?>>售后订单</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['ordertype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['ordertype']== '1'): ?>selected<?php endif; ?>>一般订单</option>
                            <option value="2" <?php if( $_GET['ordertype']== '2'): ?>selected<?php endif; ?>>预购订单</option>
                            <option value="3" <?php if( $_GET['ordertype']== '3'): ?>selected<?php endif; ?>>企业订单</option>
                            <option value="4" <?php if( $_GET['ordertype']== '4'): ?>selected<?php endif; ?>>称重订单 </option>
                        </select>
                        <?php if(empty($storeid)): ?>店铺来源：
                            <select class="select_2" name="storeid" style="width:120px;">
                                <option value=""  <?php if(empty($_GET['storeid'])): ?>selected<?php endif; ?>>全部</option>
                                <?php if(is_array($store)): $i = 0; $__LIST__ = $store;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($_GET['storeid']== $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select><?php endif; ?>
                        第三方平台：
                        <select class="select_2" name="isthirdparty" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['isthirdparty'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['isthirdparty']== '1'): ?>selected<?php endif; ?>>是</option>
                            <option value="0" <?php if( $_GET['isthirdparty']== '0'): ?>selected<?php endif; ?>>否</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($_GET['keyword']); ?>" placeholder="请输入订单号...">
                        
                        <button class="btn">搜索</button>
                    </span>
                </div>
            </div>
        </form> 

        <form action="<?php echo U('Admin/Order/del');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="11%" align="center" >订单号</td>
                            <td width="6%" align="center" >订单来源</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="8%"  align="center" >订单时间</td>
                            <td width="6%"  align="center" >支付方式</td>
                            <td width="6%"  align="center" >支付状态</td>
                            <td width="7%"  align="center" >包装状态</td>
                            <td width="6%"  align="center" >派送状态</td>
                            <td width="10%"  align="center" >收货人信息</td>
                            <td width="10%"  align="center" >收货地址</td>
                            <td width="8%"  align="center" >派送时间</td>
                            <td width="12%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                 <?php $money_total1=0;?>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="productshow" data-id="<?php echo ($vo["id"]); ?>">
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" >
                                <?php if(($vo['isfeedback']) == "1"): ?><span style="color:green;">[已反馈]</span><?php endif; ?>
                                [<a href="javascript:;" data-href="<?php echo U('Admin/Order/download',array('orderid'=>$vo['orderid']));?>" onclick="image_priview('<?php echo ($vo["ordercode"]); ?>');">查看二维码</a>]<?php echo ($vo["orderid"]); ?>
                            </td>
                            <td align="center" ><?php echo getordersource($vo['ordersource']);?></td>
                            <td align="center" >
                                ￥<?php $money_total1+=$vo['total'];?>
                                <?php echo ($vo["total"]); ?><br/>配送费￥<?php echo ((isset($vo["delivery"]) && ($vo["delivery"] !== ""))?($vo["delivery"]):"0.00"); ?><a href="javascript:;" onclick="lalert(<?php echo ($vo['money']); ?>,<?php echo ($vo['wallet']); ?>,<?php echo ($vo['discount']); ?>,<?php echo ($vo['total']); ?>,<?php echo ($vo['wait_money']); ?>,<?php echo ($vo['yes_money']); ?>,<?php echo ($vo['yes_money_total']); ?>,<?php echo ($vo['pay_status']); ?>,<?php echo ($vo['ordertype']); ?>);" class="info"><img src="/Public/Admin/images/info.png" style="width: 20px;display: inline-block;margin-bottom: -5px;"></a>
                            </td>
                            <td align="center" ><?php echo (date("Y-m-d",$vo["inputtime"])); ?><br/><?php echo (date("H:i:s",$vo["inputtime"])); ?></td>
                            <td align="center" ><?php echo getpaystyle($vo['orderid']);?></td>
                            <td align="center" >
                                <?php echo getpaystatus($vo['pay_status'],$vo['orderid']);?>
                            </td>
                            <td align="center" >
                                <?php echo getpackagestatus($vo['package_status'],$vo['orderid']);?>
                            </td>
                            <td align="center" >
                                <?php echo getdeliverystatus($vo['delivery_status'],$vo['orderid']);?>
                            </td>
                            <td align="center" ><?php echo ($vo["name"]); ?><br/><?php echo ($vo["tel"]); ?></td>
                            <td align="center" ><?php echo getarea($vo['area']);?><br/><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></td>
                            <td align="center" ><?php echo getordersendtime($vo['orderid']);?></td>
                            <td align="center" > 
                                <a href="<?php echo U('Admin/Order/doclose',array('orderid'=>$vo['orderid']));?>" class="close">关闭订单</a></br>
                                <!-- <a href="<?php echo U('Admin/Order/show',array('orderid'=>$vo['orderid']));?>">查看详情</a> -->
                            </td>
                        </tr>
                        <tr id="product_<?php echo ($vo["id"]); ?>" style="color: rgb(24, 116, 237);background-color: rgb(230, 230, 230);display:none;" >
                            <td colspan="13">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <td width="25%" align="left" >产品名称</td>
                                            <td width="25%" align="center" >产品价格</td>
                                            <td width="25%" align="center" >购买数量</td>
                                            <td width="25%" align="center" >商品类型</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($vo['productinfo'])): $i = 0; $__LIST__ = $vo['productinfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                                <td width="25%" align="left" ><?php echo ($v["title"]); ?></td>
                                                <td width="25%" align="center" ><?php echo ($v["price"]); ?>元/<?php echo ($v["standard"]); echo getunit($v['unit']);?></td>
                                                <td width="25%" align="center" ><?php echo ($v["nums"]); ?></td>
                                                <td width="25%" align="center" >
                                                    <?php if( $v["product_type"] == '0'): ?>[企业商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '1'): ?>[一般商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '2'): ?>[团购商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '3'): ?>[预购商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '4'): ?>[称重商品]
                                                        <?php if( $v["isweigh"] == 1): ?>已称重[<?php echo (date("Y-m-d H:i:s",$v["weightime"])); ?>]
                                                            <?php else: ?> 
                                                            未称重<?php endif; endif; ?>
                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
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

                    <?php if(authcheck('Admin/Order/del')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button><?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="btn_wrap">
                <div class="btn_wrap_pd">


                    <form method="post" action="<?php echo U('Admin/Orderexcel/excel');?>">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="<?php echo ($_GET['start_time']); ?>" >
                            <input type="hidden"  name="end_time" value="<?php echo ($_GET['end_time']); ?>" >
                            <input type="hidden"  name="ordersource" value="<?php echo ($_GET['ordersource']); ?>" >
                            <input type="hidden"  name="ordertype" value="<?php echo ($_GET['ordertype']); ?>" >
                            <input type="hidden"  name="isthirdparty" value="<?php echo ($_GET['isthirdparty']); ?>" >
                            <input type="hidden"  name="issend" value="<?php echo ($_GET['issend']); ?>" >
                            <input type="hidden"  name="storeid" value="<?php echo ($_GET['storeid']); ?>" >
                            <input type="hidden"  name="keyword" value="<?php echo ($_GET['keyword']); ?>" >
                            <input type="hidden"  name="isdone" value="1" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form> 


                </div>
            </div>


    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
        <script src="/Public/Admin/js/content_addtop.js"></script>
    <script>
        $(function () {
            $(".productshow a").click(function () {
                var href = $(this).attr("href");
                window.location.href = href;
                return false;
            })
            $(".productshow").click(function () {
                var obj = "#product_" + $(this).data("id");
                $(obj).toggle();
            })
            
        })
    </script>
</body>
</html>
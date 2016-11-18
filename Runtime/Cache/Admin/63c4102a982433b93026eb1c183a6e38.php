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
        <form method="get" action="<?php echo U('Admin/Balance/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10">
                    <span class="mr20">时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width: 80px;">
                        <button class="btn">搜索</button>
                    </span>
                </div>
            </div>
        </form>
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><thead>
                        <tr>
                            <td width="6%" align="center">日期</td>
                            <td width="5%" align="center">销售总额</td>
                            <td width="8%" align="center">支付宝付款额</td>
                            <td width="8%" align="center">微信支付付款额</td>
                            <td width="8%" align="center">钱包付款额</td>
                            <td width="8%" align="center">货到付款付款额</td>
                            <td width="8%" align="center">抵用券额</td>
                            <td width="8%" align="center">订单数</td>
                            <td width="8%" align="center">新客户订单数</td>
                            <td width="10%" align="center">使用现金券订单数</td>
                            <td width="10%" align="center">货到付款订单数</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center"  rowspan="2"><?php echo (date("Y-m-d",$vo["date"])); ?></td>
                            <td align="center"  rowspan="2"><?php echo ((isset($vo["total"]) && ($vo["total"] !== ""))?($vo["total"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["alipay_money"]) && ($vo["alipay_money"] !== ""))?($vo["alipay_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["wx_money"]) && ($vo["wx_money"] !== ""))?($vo["wx_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["wallet_money"]) && ($vo["wallet_money"] !== ""))?($vo["wallet_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["delivery_money"]) && ($vo["delivery_money"] !== ""))?($vo["delivery_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["discount_money"]) && ($vo["discount_money"] !== ""))?($vo["discount_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["total_order"]) && ($vo["total_order"] !== ""))?($vo["total_order"]):"0"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["new_order"]) && ($vo["new_order"] !== ""))?($vo["new_order"]):"0"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["discount_order"]) && ($vo["discount_order"] !== ""))?($vo["discount_order"]):"0"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["delivery_order"]) && ($vo["delivery_order"] !== ""))?($vo["delivery_order"]):"0"); ?></td>
                        </tr>
                        <tr>
                            <td colspan="9">
                                <table width="100%"  cellspacing="0">
                                    <thead>
                                        <tr>
                                            <td width="10%" align="center">订单来源</td>
                                            <td width="10%" align="center">销售总额</td>
                                            <td width="10%" align="center">支付宝付款额</td>
                                            <td width="10%" align="center">微信支付付款额</td>
                                            <td width="10%" align="center">钱包付款额</td>
                                            <td width="10%" align="center">货到付款付款额</td>
                                            <td width="10%" align="center">抵用券额</td>
                                            <td width="8%" align="center">订单数</td>
                                            <td width="12%" align="center">使用现金券订单数</td>
                                            <td width="10%" align="center">货到付款订单数</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($vo['subdata'])): $i = 0; $__LIST__ = $vo['subdata'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                                <td align="center" ><?php echo ($v["name"]); ?></td>
                                                <td align="center" ><?php echo ((isset($v["total_money"]) && ($v["total_money"] !== ""))?($v["total_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["alipay_money"]) && ($v["alipay_money"] !== ""))?($v["alipay_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["wx_money"]) && ($v["wx_money"] !== ""))?($v["wx_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["wallet_money"]) && ($v["wallet_money"] !== ""))?($v["wallet_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["delivery_money"]) && ($v["delivery_money"] !== ""))?($v["delivery_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["discount_money"]) && ($v["discount_money"] !== ""))?($v["discount_money"]):"0.00"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["total_order"]) && ($v["total_order"] !== ""))?($v["total_order"]):"0"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["discount_order"]) && ($v["discount_order"] !== ""))?($v["discount_order"]):"0"); ?></td>
                                                <td align="center" ><?php echo ((isset($v["delivery_order"]) && ($v["delivery_order"] !== ""))?($v["delivery_order"]):"0"); ?></td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </table>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <div class="p10">
                <div class="pages"><?php echo ($Page); ?> </div>
            </div>
        </div>

        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <label class="mr20">
                    销售汇总：<?php echo ((isset($totalmoney) && ($totalmoney !== ""))?($totalmoney):"0.00"); ?>元
                </label>
            </div>
        </div>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
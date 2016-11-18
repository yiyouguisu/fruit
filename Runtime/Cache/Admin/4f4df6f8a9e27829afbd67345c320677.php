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
        <form method="get" action="<?php echo U('Admin/Balance/runer');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        结算账期：<input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width: 80px;">
                        配送员：
                        <select class="select_2" name="runerid" style="width:120px;">
                            <option value=""  <?php if(empty($_GET['runerid'])): ?>selected<?php endif; ?>>全部</option>
                            <?php if(is_array($runer)): $i = 0; $__LIST__ = $runer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ruid"]); ?>" <?php if($_GET['runerid']== $vo['ruid']): ?>selected<?php endif; ?>><?php echo ($vo["realname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        结算：
                       <select class="select_2" name="status" style="width:100px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="0" <?php if( $_GET['status']== '0'): ?>selected<?php endif; ?>>未结算</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>部分结算</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>完成结算</option>
                        </select>
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <div class="h_a">已结算金额￥<?php echo ((isset($totalyes_money) && ($totalyes_money !== ""))?($totalyes_money):"0.00"); ?> 未结算金额￥<?php echo ((isset($totalno_money) && ($totalno_money !== ""))?($totalno_money):"0.00"); ?></div>
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="10%" align="center">结算账期</td>
                            <td width="10%" align="center" >配送员</td>
                            <td width="10%" align="center" >订单汇总数</td>
                            <td width="10%" align="center" >订单汇总金额</td>
                            <td width="10%" align="center" >已结算金额</td>
                            <td width="10%" align="center" >未结算金额</td>
                            <td width="10%" align="center" >是否已结算</td>
                            <td width="10%" align="center" >最后打款时间</td>
                            <td width="10%" align="center" >管理操作</td>

                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center" ><?php echo (date("Y-m-d",$vo["date"])); ?></td>
                            <td align="center" ><?php echo ($vo["realname"]); ?><br/><?php echo ($vo["phone"]); ?></td>
                            <td align="center" ><?php echo ((isset($vo["ordernum"]) && ($vo["ordernum"] !== ""))?($vo["ordernum"]):"0"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["ordermoney"]) && ($vo["ordermoney"] !== ""))?($vo["ordermoney"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["yes_money"]) && ($vo["yes_money"] !== ""))?($vo["yes_money"]):"0.00"); ?></td>
                            <td align="center" ><?php echo ((isset($vo["no_money"]) && ($vo["no_money"] !== ""))?($vo["no_money"]):"0.00"); ?></td>
                            <td align="center" >
                                <?php if($vo["status"] == 0): ?>未结算<?php endif; ?>
                                <?php if($vo["status"] == 1): ?>部分结算<?php endif; ?>
                                <?php if($vo["status"] == 2): ?>完成结算<?php endif; ?>
                            </td>
                            <td align="center" >
                                <?php if(!empty($vo['last_pay_time'])): echo (date("Y-m-d H:i:s",$vo["last_pay_time"])); ?>
                                    <?php else: ?>
                                    尚未打款<?php endif; ?>
                            </td>
                            <td align="center" > 
                                <a href="<?php echo U('Admin/Balance/runerdeal',array('id'=>$vo['id']));?>" >结算</a> |
                                <a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Balance/runerinfo',array('id'=>$vo['id']));?>','查看详情',1,800,400)">查看详情</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> <?php echo ($Page); ?> </div>
            </div>
            </div>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/birthday.js"></script>
</body>
</html>
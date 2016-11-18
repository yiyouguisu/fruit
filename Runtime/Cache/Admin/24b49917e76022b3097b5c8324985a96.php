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
        <form method="get" action="<?php echo U('Admin/Bill/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        申请时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <?php if(empty($_GET['status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['status']== '1'): ?>selected<?php endif; ?>>申请中</option>
                            <option value="2" <?php if( $_GET['status']== '2'): ?>selected<?php endif; ?>>审核成功</option>
                            <option value="3" <?php if( $_GET['status']== '3'): ?>selected<?php endif; ?>>审核失败</option>
                        </select>
                        店铺：
                        <select class="select_2" name="storeid" style="width:120px;">
                            <option value=""  <?php if(empty($_GET['storeid'])): ?>selected<?php endif; ?>>全部</option>
                            <?php if(is_array($store)): $i = 0; $__LIST__ = $store;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($_GET['storeid']== $vo.id): ?>selected<?php endif; ?>><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="10%" align="center" >订单号</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="10%" align="center" >发票类型</td>
                            <td width="10%" align="center" >发票抬头</td>
                            <td width="10%" align="center" >发票地址</td>
                            <td width="10%" align="center" >申请人</td>
                            <td width="10%" align="center" >申请时间</td>
                            <td width="10%" align="center" >审核状态</td>
                            <td width="10%" align="center" >审核时间</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                            <td align="center" ><?php echo ($vo["orderid"]); ?></td>
                            <td align="center" ><?php echo ((isset($vo["money"]) && ($vo["money"] !== ""))?($vo["money"]):"0.00"); ?></td>
                            <td align="center" >
                                <?php if(($vo['billtype']) == "0"): ?>未选择<?php endif; ?>
                                <?php if(($vo['billtype']) == "1"): ?>普通发票<?php endif; ?>
                                <?php if(($vo['billtype']) == "2"): ?>增值发票<?php endif; ?>
                            </td>
                            <td align="center" ><?php echo ($vo["billtitle"]); ?></td>
                            <td align="center" ><?php echo getaddress($vo['billaddressid']);?></td>
                            <td align="center" ><?php echo getuser($vo['uid']);?></td>
                            <td align="center" >
                                <?php if(empty($vo['bill_apply_time'])): ?>未申请
                                    <?php else: ?>
                                    <?php echo (date("Y-m-d H:i:s",$vo["bill_apply_time"])); endif; ?>
                            </td>
                            <td align="center" >
                              <?php echo getreviewstatus($vo['bill_apply_status']);?>
                            </td>
                            <td align="center" >
                                <?php if(($vo['billtype']) == "0"): ?>未申请
                                    <?php else: ?>
                                    <?php if(empty($vo['bill_review_time'])): ?>审核中
                                        <?php else: ?>
                                        <?php echo (date("Y-m-d H:i:s",$vo["bill_review_time"])); endif; endif; ?>
                            </td>
                            <td align="center" > 
                                <?php if(($vo["bill_apply_status"]) == "2"): ?>已成功开票</br>
                                    <a href="javascript:;" onClick="laert(<?php echo ($vo['bill_review_remark']); ?>);">查看详情</a>
                                    <?php else: ?>
                                    <a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Bill/review',array('orderid'=>$vo['orderid']));?>','确认开票',1,700,420)">确认开票</a><?php endif; ?>
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
    <script>
        function laert($content) {
            if ($content == '') {
                $content = "暂无审核备注";
            }
            layer.alert($content, {
                closeBtn: 0
            })
        }
    </script>
    
</body>
</html>
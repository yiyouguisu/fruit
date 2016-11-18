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
<style type="text/css">
    .cu, .cu-li li, .cu-span span {
        cursor: hand;
        !important;
        cursor: pointer;
    }

    tr.cu:hover td {
        background-color: #FF9966;
    }
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
       <div class="common-form">
                <div class="table_full">
                    <form class="J_ajaxForm" method="post" action="<?php echo U('Admin/Balance/runerdeal');?>">
                         <div class="bk10"></div>
                         <div class="h_a">结算信息</div>       
                        <table width="100%" class="table_form contentWrap">
                            <tbody>
                                <tr>
                                    <th width="100">结算账期</th>
                                    <td><?php echo ($data["year"]); ?>年<?php echo ($data["month"]); ?>月</td>
                                </tr>
                                <tr>
                                    <th>配送员</th>
                                    <td><?php echo ($data["nickname"]); ?></td>
                                </tr>
                                
                                <tr>
                                    <th>联系方式</th>
                                    <td><?php echo ($data["phone"]); ?></td>
                                </tr>
                                <tr>
                                    <th>订单汇总金额</th>
                                    <td><?php echo ($data["ordermoney"]); ?></td>
                                </tr>
                                <tr>
                                    <th>已结算金额</th>
                                    <td><?php echo ($data["yes_money"]); ?></td>
                                </tr>
                                <tr>
                                    <th>未结算金额</th>
                                    <td><?php echo ($data["no_money"]); ?></td>
                                </tr>
                                <tr>
                                    <th  width="80">本次结算金额</th>
                                    <td>
                                        <input type="text" class="input length_2" name="money" value="0.00">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="btn_wrap">
                            <div class="btn_wrap_pd">
                                <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
                                <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">结算</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
<script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
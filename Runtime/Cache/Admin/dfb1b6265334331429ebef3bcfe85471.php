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
    <div class="wrap J_check_wrap" style="padding-bottom: 0">
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td width="10%" align="center">ID</td>
                        <td width="60%" align="left">详情</td>
                        <td width="15%" align="center">处理人</td>
                        <td width="15%" align="center">处理时间</td>

                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
                        <td width="10%" align="center"><?php echo ($vo["id"]); ?></td>
                        <td width="6%" align="left" ><?php echo ($vo["loginfo"]); ?></td>
                        <td width="15%" align="center"><?php echo ($vo["username"]); ?></td>
                        <td width="15%" align="center"><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></td>
                      </tr><?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <form class="J_ajaxForm" action="<?php echo U('Admin/Order/feedbacklog');?>" method="post">
                    <textarea type="text" name="loginfo" value=""  class="valid" style="width:100%;height:80px;"></textarea>
                    <input type="hidden" name="orderid" value="<?php echo ($orderid); ?>" />
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">新建记录</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
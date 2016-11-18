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
    .col-auto {
        overflow: hidden;
        _zoom: 1;
        _float: left;
        border: 1px solid #c2d1d8;
    }

    .col-right {
        float: right;
        width: 210px;
        overflow: hidden;
        margin-left: 6px;
        border: 1px solid #c2d1d8;
    }

    body fieldset {
        border: 1px solid #D8D8D8;
        padding: 10px;
        background-color: #FFF;
    }

        body fieldset legend {
            background-color: #F9F9F9;
            border: 1px solid #D8D8D8;
            font-weight: 700;
            padding: 3px 8px;
        }

    .picList li {
        float: left;
        margin-top: 2px;
        margin-right: 5px;
    }
</style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="<?php echo U('Admin/Feedback/feedbackcheck');?>">
                <div class="h_a">反馈详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">姓名</th>
                                <td><?php echo ($data["username"]); ?></td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td><?php echo ($data["phone"]); ?></td>
                            </tr>
                            <tr>
                                <th>反馈内容</th>
                                <td><?php echo ($data["content"]); ?></td>
                            </tr>
                            <tr>
                                <th>反馈时间</th>
                                <td><?php echo (date("Y-m-d H:i:s",$data["inputtime"])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                
                         <div class="bk10"></div>
                        <div class="h_a">审核信息</div>       
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                        <tr>
                            <th  width="80">审核</th>
                            <td>
                                <ul class="switch_list cc ">
                                    <li>
                                        <label>
                                            
                                            <input type='radio' name='status' value='2' <?php if($data['status'] == '2' ): ?>checked<?php endif; ?>>
                                            <span>审核通过</span></label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type='radio' name='status' value='3' <?php if($data['status'] == '3' ): ?>checked<?php endif; ?>>
                                            <span>审核不通过</span></label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                         <tr>
                            <th>审核备注</th>
                            <td>
                                <textarea  name="remark" class="valid" style="width:500px;height:80px;"><?php echo ($data["remark"]); ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">审核</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
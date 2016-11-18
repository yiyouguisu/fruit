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
        
        <form id="export-form" action="<?php echo U('Admin/Db/export');?>" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >表名</td>
                            <td width="8%" align="center" >引擎</td>
                            <td width="10%" align="center" >编码</td>
                            <td width="8%" align="center" >记录数</td>
                            <td width="8%" align="center" >数据大小</td>
                            <td width="15%" align="center" >最后更新时间</td>
                            <td width="10%" align="center" >备注</td>
                            <td width="10%" align="center" >备份状态</td>
                            <td width="25%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalsize=0; ?>
                    <?php if(is_array($tablelist)): $i = 0; $__LIST__ = $tablelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                          <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="tables[]" value="<?php echo ($vo["Name"]); ?>"></td>
                          <td align="center" ><?php echo ($key); ?></td>
                          <td align="center"><?php echo ($vo["Name"]); ?></td>
                          <td align="center"><?php echo ($vo["Engine"]); ?></td>
                          <td align="center"><?php echo ((isset($vo["Collation"]) && ($vo["Collation"] !== ""))?($vo["Collation"]):'--'); ?></td>
                          <td align="center"><?php echo ($vo["Rows"]); ?></td>
                          <td align="center"><?php echo (format_bytes($vo["Data_length"])); ?></td>
                          <td align="center"><?php echo ($vo["Update_time"]); ?></td>
                          <td align="center"><?php echo ($vo["Comment"]); ?></td>
                          <td align="center" class="info">未备份</td>
                          <td align="center" > 
                          <a href="<?php echo U('Admin/Db/showtable',array('table'=>$vo['Name']));?>" >查看表结构</a>|
                          <a href="<?php echo U('Admin/Db/optimize',array('tables'=>$vo['Name']));?>">优化表</a>|
                          <a href="<?php echo U('Admin/Db/repair',array('tables'=>$vo['Name']));?>">修复表</a>
                          </td>
                          <?php  $totalsize=$totalsize+$vo['Data_length']; ?>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <label class="mr20">
                    <input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>     
                    <?php if(authcheck('Admin/Db/export')): ?><button style="width: 155px;" class="btn btn_submit mr10 " type="submit" name="submit" id="export">立即备份</button>
                        <b style="margin-left: 20px;color: #999;font-weight: normal;">数据库总大小：<?php echo (format_bytes($totalsize)); ?>(尽量不要在访问量较大时备份)</b><?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
    <script type="text/javascript">
        (function ($) {
            var $form = $("#export-form"), $export = $("#export"), tables
            $optimize = $("#optimize"), $repair = $("#repair");

            $optimize.add($repair).click(function () {
                $.post(this.href, $form.serialize(), function (data) {
                    if (data.status) {
                        alert(data.info);
                    } else {
                        alert(data.info);
                    }
                    setTimeout(function () {
                        $('#top-alert').find('button').click();
                        $(this).removeClass('disabled').prop('disabled', false);
                    }, 1500);
                }, "json");
                return false;
            });

            $export.click(function () {
                $export.css("width", "270px");
                $export.parent().children().addClass("disabled");
                $export.html("正在发送备份请求...");
                $.post(
                    $form.attr("action"),
                    $form.serialize(),
                    function (data) {
                        if (data.status) {
                            tables = data.tables;
                            $export.html(data.info + "开始备份，请不要关闭本页面！");
                            backup(data.tab);
                            window.onbeforeunload = function () { return "正在备份数据库，请不要关闭！" }
                        } else {
                            alert(data.info);
                            $export.parent().children().removeClass("disabled");
                            $export.html("立即备份");
                            setTimeout(function () {
                                $('#top-alert').find('button').click();
                                $(this).removeClass('disabled').prop('disabled', false);
                            }, 1500);
                        }
                    },
                    "json"
                );
                return false;
            });

            function backup(tab, status) {
                status && showmsg(tab.id, "开始备份...(0%)");
                $.get($form.attr("action"), tab, function (data) {
                    if (data.status) {
                        showmsg(tab.id, data.info);

                        if (!$.isPlainObject(data.tab)) {
                            $export.parent().children().removeClass("disabled");
                            $export.html("备份完成，点击重新备份");
                            window.onbeforeunload = function () { return null }
                            return;
                        }
                        backup(data.tab, tab.id != data.tab.id);
                    } else {
                        alert(data.info);
                        $export.parent().children().removeClass("disabled");
                        $export.html("立即备份");
                        setTimeout(function () {
                            $('#top-alert').find('button').click();
                            $(this).removeClass('disabled').prop('disabled', false);
                        }, 1500);
                    }
                }, "json");

            }

            function showmsg(id, msg) {
                $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg).css('color', 'green');
            }
        })(jQuery);
    </script>
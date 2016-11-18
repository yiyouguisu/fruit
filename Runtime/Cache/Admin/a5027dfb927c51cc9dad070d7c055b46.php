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
<style type="text/css">
.table_full th.v2Menu{padding-left:15px}
.table_full td.v1Menu{padding:0px}
.table_full td.v1Menu td{padding:7px 10px 9px 15px}
</style>
<div class="wrap J_check_wrap">
  <div class="h_a">常用菜单</div>
  <form action="<?php echo U('Admin/Menu/public_changyong');?>" method="post">
    <div class="table_full J_check_wrap">
      <table width="100%">
        <col class="th" />
        <col width="400" />
        <col />
        <tr>
          <th>
            <label>
              <input disabled="true" checked id="J_role_custom" class="J_check_all" data-direction="y" data-checklist="J_check_custom" type="checkbox">
              <span>常用</span>
            </label>
            </th>
          <td>
            <ul data-name="custom" class="three_list cc J_ul_check">
              <li>
                <label>
                  <input disabled checked data-yid="J_check_custom" class="J_check" type="checkbox" >
                  <span>常用菜单</span>
                </label>
              </li>
            </ul>
          </td>
          <td><div class="fun_tips"></div></td>
        </tr>
        <?php foreach($data as $key1=>$leve1):;?>
          <tr>
            <th><label><span><?php echo ($leve1["title"]); ?></span></label></th>
            <td colspan="2" class="leve1Menu">
              <table width="100%">
                <?php foreach($leve1['child'] as $key2=>$leve2):;?>
          <tr>
                    <th width="20%" class="leve2Menu"><label><input id="J_role_<?php echo ($leve2["id"]); ?>" class="J_check_all" data-direction="y" data-checklist="J_check_<?php echo ($leve2["id"]); ?>" type="checkbox"><span><?php echo ($leve2["title"]); ?></span></label></th>
                    <td>
                  <ul data-name="<?php echo ($key2); ?>" class="three_list cc J_ul_check">
                <?php foreach($leve2['child'] as $key3=>$leve3):;?>
                    <li><label><input  name="menu[]" data-yid="J_check_<?php echo ($leve2["id"]); ?>" class="J_check" type="checkbox" value="<?php echo ($leve3["id"]); ?>" <?php if( in_array($leve3['id'],$panel) ): ?>checked<?php endif; ?>
><span><?php echo ($leve3["title"]); ?></span></label></li>
                <?php foreach($leve3['child'] as $key4=>$leve4):;?>
                    <li><label><input  name="menu[]" data-yid="J_check_<?php echo ($leve2["id"]); ?>" class="J_check" type="checkbox" value="<?php echo ($leve4["id"]); ?>" <?php if( in_array($leve4['id'],$panel) ): ?>checked<?php endif; ?>
><span><?php echo ($leve4["title"]); ?></span></label></li>
          <?php endforeach;?>
          <?php endforeach;?>
                  </ul>
                    </td>
                    </tr>
        <?php endforeach; ?>
                  </table>
              </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div class="btn_wrap">
    <div class="btn_wrap_pd">             
      <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
    </div>
    </div>

  </form>
</div>
<script src="/Public/Admin/js/common.js?v"></script>
</body>
</html>
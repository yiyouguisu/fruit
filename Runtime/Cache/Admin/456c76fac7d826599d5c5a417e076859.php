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
<style>
.pop_nav{
    padding: 0px;
}
.pop_nav ul{
    border-bottom:1px solid green;
    padding:0 5px;
    height:25px;
    clear:both;
}
.pop_nav ul li.current a{
    border:1px solid green;
    border-bottom:0 none;
    color:#333;
    font-weight:700;
    background:#F3F3F3;
    position:relative;
    border-radius:2px;
    margin-bottom:-1px;
}

</style>
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
        <div class="pop_nav">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">短信接口配置</a></li>
              <li class=""><a href="javascript:;;">极光推送接口配置</a></li>
              <li class=""><a href="javascript:;;">Ping++接口配置</a></li>
              <li class=""><a href="javascript:;;">地图接口配置</a></li>
            </ul>
        </div>
        <form name="myform" class="J_ajaxForm" id="myform" action="<?php echo U('Admin/Config/third');?>" method="post" enctype="multipart/form-data">
            <div class="h_a">温馨提示</div>
                <div class="prompt_text">
                <p>1、请将购买的第三方账号填写到相应位置</p>
                <p>2、Ping++接口key请填写相应环境（测试环境/正式环境）的key值</p>
            </div>
            <div class="J_tabs_contents">
              <div class="tba">
                <div class="h_a">短信接口配置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">云通讯主帐号:</th>
                                <td><input type="text" class="input"  name="ccpest_accountSid" value="<?php echo ($Site["ccpest_accountSid"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">云通讯主帐号Token:</th>
                                <td><input type="text" class="input"  name="ccpest_accountToken" value="<?php echo ($Site["ccpest_accountToken"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">云通讯应用Id:</th>
                                <td><input type="text" class="input"  name="ccpest_appId" value="<?php echo ($Site["ccpest_appId"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">云通讯请求端口:</th>
                                <td><input type="text" class="input"  name="ccpest_serverPort" value="<?php echo ($Site["ccpest_serverPort"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">云通讯请求地址:</th>
                                <td><input type="text" class="input"  name="ccpest_serverIP" value="<?php echo ($Site["ccpest_serverIP"]); ?>" size="40"></td>
                            </tr>
                            <!--<tr>
                                <th width="140">云通讯模板Id:</th>
                                <td><input type="text" class="input"  name="ccpest_tempId" value="<?php echo ($Site["ccpest_tempId"]); ?>" size="40"></td>
                            </tr>-->
                            
                            
                        </table>
                </div>
              </div>
              <div class="tba">
                <div class="h_a">极光推送接口配置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">极光推送用户端appkey:</th>
                                <td><input type="text" class="input"  name="jpush_member_appkey" value="<?php echo ($Site["jpush_member_appkey"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">极光推送用户端secret:</th>
                                <td><input type="text" class="input"  name="jpush_member_masterSecret" value="<?php echo ($Site["jpush_member_masterSecret"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">极光推送配送端appkey:</th>
                                <td><input type="text" class="input"  name="jpush_run_appkey" value="<?php echo ($Site["jpush_run_appkey"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">极光推送配送端secret:</th>
                                <td><input type="text" class="input"  name="jpush_run_masterSecret" value="<?php echo ($Site["jpush_run_masterSecret"]); ?>" size="40"></td>
                            </tr>
                        </table>
                        
                </div>
              </div>
              <div class="tba">
                <div class="h_a">Ping++接口配置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">Ping++APPID:</th>
                                <td><input type="text" class="input"  name="pingAppid" value="<?php echo ($Site["pingAppid"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">Ping++KEY:</th>
                                <td><input type="text" class="input"  name="pingKey" value="<?php echo ($Site["pingKey"]); ?>" size="40"></td>
                            </tr>
                            
                        </table>
                        
                </div>
              </div>
              <div class="tba">
                <div class="h_a">地图接口配置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">百度地图密钥:</th>
                                <td><input type="text" class="input"  name="baidumap_key" value="<?php echo ($Site["baidumap_key"]); ?>" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">高德地图密钥:</th>
                                <td><input type="text" class="input"  name="gaodemap_key" value="<?php echo ($Site["gaodemap_key"]); ?>" size="40"></td>
                            </tr>
                            
                        </table>
                        
                </div>
              </div>
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
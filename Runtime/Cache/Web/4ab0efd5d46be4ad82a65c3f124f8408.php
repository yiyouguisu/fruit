<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <style type="text/css">
        /*内容*/
        .page_info {
            width: 100%;
            height: auto;
            overflow: auto;
            position: absolute;
            left: 0px;
            top: 55px;
            z-index: 1;
        }
        /*底部*/
        .page_foot {
            width: 100%;
            height: 54px;
            color: #ffffff;
            background-color: #ffffff;
            text-align: center;
            position: fixed;
            left: 0px;
            bottom: 0px;
            z-index: 3;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
        <?php if(($addurl) == "1"): ?><div class="l"><a id="toolReturn" class="return" href="<?php echo U('Web/cat/submits');?>?id=<?php echo ($orderid); ?>" target="_self"></a></div><?php endif; ?>
        <?php if(($addurl) == "0"): ?><div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div><?php endif; ?>
        <?php if(($addurl) == "2"): ?><div class="l"><a id="toolReturn" class="return" href="<?php echo U('Web/Invoic/changeaddr');?>?id=<?php echo ($fapiaoid); ?>" target="_self"></a></div><?php endif; ?>

        <!--<div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>-->
        <h1>我的收货地址</h1>
        <?php if(($addurl) == "1"): ?><div class="r"><a id="toolInsert" class="insert" href="<?php echo U('Web/Address/edit');?>?addressid=<?php echo ($orderid); ?>" target="_self"></a></div><?php endif; ?>
        <?php if(($addurl) == "0"): ?><div class="r"><a id="toolInsert" class="insert" href="<?php echo U('Web/Address/edit');?>?id=0" target="_self"></a></div><?php endif; ?>
        <?php if(($addurl) == "2"): ?><div class="r"><a id="toolInsert" class="insert" href="<?php echo U('Web/Address/edit');?>?fapiaoid=<?php echo ($fapiaoid); ?>" target="_self"></a></div><?php endif; ?>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff">
        <div class="addressList">
            <?php if(is_array($addlist)): $i = 0; $__LIST__ = $addlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="addressItem" >
             <table width="100%" border="0" cellpadding="0" cellspacing="0">
             <thead>
                 <tr>
                     <td style="width:32px;">&nbsp;</td>
                     <td style="width:auto;">&nbsp;</td>
                     <td style="width:50px;">&nbsp;</td>
                 </tr>
             </thead>
             <tbody>
             <tr>
                 <td colspan="2" style="padding-bottom:6px;">
                 	<span class="name"><?php echo ($vo["name"]); ?></span>
                    <?php if(($vo[type]) == "1"): ?><span class="type">公司</span><?php endif; ?>
                    <?php if(($vo[type]) == "2"): ?><span  class="type">家</span><?php endif; ?>
                    <?php if(($dvo[type]) == "3"): ?><span  class="type">其他</span><?php endif; ?>
                 	
                 	</if>
                 </td>
             </tr>
             <tr>
                 <td><img alt="" src="/Public/Web/images/icon_AddDetail.png" style="margin-left:0px;" /></td>
                 <!--<td><?php echo ($vo["area"]); ?><br /><?php echo ($vo["address"]); ?></td>-->
                 <?php if(($addurl) == "0"): ?><td onclick="javascript:location.href='<?php echo ($vo["editurl"]); ?>'"><?php echo ($vo["area"]); ?><br /><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="/Public/Web/images/icon_arrow.png" onclick="javascript:location.href='<?php echo ($vo["editurl"]); ?>'" /></td><?php endif; ?>
                 <?php if(($addurl) == "1"): ?><td onclick="javascript:location.href='<?php echo ($vo["addressurl"]); ?>'"><?php echo ($vo["area"]); ?><br /><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="/Public/Web/images/icon_arrow.png" onclick="javascript:location.href='<?php echo ($vo["addressurl"]); ?>'" /></td><?php endif; ?>
                 <?php if(($addurl) == "2"): ?><td onclick="javascript:location.href='<?php echo ($vo["fapiaourl"]); ?>'"><?php echo ($vo["area"]); ?><br /><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="/Public/Web/images/icon_arrow.png" onclick="javascript:location.href='<?php echo ($vo["fapiaourl"]); ?>'" /></td><?php endif; ?>
             </tr>
             <tr>
                 <td><img alt="" src="/Public/Web/images/icon_AddMobile.png" style="margin-left:3px;"  /></td>
                 <td><?php echo ($vo["tel"]); ?></td>
                 <td>
                 	<?php if($vo["isdefault"] == 0): ?><span class="switchClose" id="<?php echo ($vo["id"]); ?>"></span>
                 	<?php else: ?>
                 		<span class="switchDakai" id="<?php echo ($vo["id"]); ?>"></span><?php endif; ?>
                 </td>
             </tr>
             </tbody>
             </table>
          </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</body>
<script type="text/javascript">
    $(".switchClose").click(function () {
        var id = $(this).attr('id');
        $.post(
			"<?php echo U('Web/Address/edit');?>", {
			    "id": id,
			    "isdefault": "1",
			},
			function (response, status) {
			    console.log(response);

			    location.href = "<?php echo U('Web/Address/index');?>";
			},
		"json");
    });
</script>
</html>
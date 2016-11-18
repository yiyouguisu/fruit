<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/weixin.jquery.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
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
        <eq name="addurl" value="1">
             <div class="l"><a id="toolReturn" class="return" href="{:U('Web/cat/submits')}?id={$orderid}" target="_self"></a></div>
            </eq>
        <eq name="addurl" value="0">
           <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
        </eq>
        <eq name="addurl" value="2">
           <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Invoic/changeaddr')}?id={$fapiaoid}" target="_self"></a></div>
        </eq>

        <!--<div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>-->
        <h1>我的收货地址</h1>
        <eq name="addurl" value="1">
             <div class="r"><a id="toolInsert" class="insert" href="{:U('Web/Address/edit')}?addressid={$orderid}" target="_self"></a></div>
        </eq>
        <eq name="addurl" value="0">
           <div class="r"><a id="toolInsert" class="insert" href="{:U('Web/Address/edit')}?id=0" target="_self"></a></div>
        </eq>
        <eq name="addurl" value="2">
           <div class="r"><a id="toolInsert" class="insert" href="{:U('Web/Address/edit')}?fapiaoid={$fapiaoid}" target="_self"></a></div>
        </eq>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff">
        <div class="addressList">
            <volist name="addlist" id="vo">
       		<div class="addressItem" >
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
                 	<span class="name">{$vo.name}</span>
                    <eq name="vo[type]" value="1">
	                	<span class="type">公司</span>
                	</eq>
                    <eq name="vo[type]" value="2">
	                	<span  class="type">家</span>
                	</eq>
                    <eq name="dvo[type]" value="3">
	                	<span  class="type">其他</span>
                	</eq>
                 	
                 	</if>
                 </td>
             </tr>
             <tr>
                 <td><img alt="" src="__IMG__/icon_AddDetail.png" style="margin-left:0px;" /></td>
                 <!--<td>{$vo.area}<br />{$vo.address}</td>-->
                 <eq name="addurl" value="0">
                     <td onclick="javascript:location.href='{$vo.editurl}'">{$vo.area}<br />{$vo.areatext}{$vo.address}</td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="__IMG__/icon_arrow.png" onclick="javascript:location.href='{$vo.editurl}'" /></td>
                 </eq>
                 <eq name="addurl" value="1">
                     <td onclick="javascript:location.href='{$vo.addressurl}'">{$vo.area}<br />{$vo.areatext}{$vo.address}</td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="__IMG__/icon_arrow.png" onclick="javascript:location.href='{$vo.addressurl}'" /></td>
                 </eq>
                 <eq name="addurl" value="2">
                     <td onclick="javascript:location.href='{$vo.fapiaourl}'">{$vo.area}<br />{$vo.areatext}{$vo.address}</td>
                 	<td style="text-align:right; vertical-align:middle;"><img alt="" src="__IMG__/icon_arrow.png" onclick="javascript:location.href='{$vo.fapiaourl}'" /></td>
                 </eq>
             </tr>
             <tr>
                 <td><img alt="" src="__IMG__/icon_AddMobile.png" style="margin-left:3px;"  /></td>
                 <td>{$vo.tel}</td>
                 <td>
                 	<if condition="$vo.isdefault eq 0">
                 		<span class="switchClose" id="{$vo.id}"></span>
                 	<else/>
                 		<span class="switchDakai" id="{$vo.id}"></span>
                 	</if>
                 </td>
             </tr>
             </tbody>
             </table>
          </div>
       	</volist>
        </div>
    </div>
</body>
<script type="text/javascript">
    $(".switchClose").click(function () {
        var id = $(this).attr('id');
        $.post(
			"{:U('Web/Address/edit')}", {
			    "id": id,
			    "isdefault": "1",
			},
			function (response, status) {
			    console.log(response);

			    location.href = "{:U('Web/Address/index')}";
			},
		"json");
    });
</script>
</html>

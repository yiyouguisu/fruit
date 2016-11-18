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
    <script>
        $(function () {
            $(".couponsItem").click(function () {
                var cid = $(this).attr('c-id');
                parent.window.location.reload();
            })
        })

    </script>
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
    <if condition="I('get.id') neq ''"> 
        <div id="page_head" class="page_head" style="background-color:white">
            <h1 style="color:black">选择优惠券</h1>
        </div>
    <else />
        <div id="page_head" class="page_head">
            <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
            <h1>我的优惠券</h1>
        </div>
    </if>
    
    <div id="page_info" class="page_info" style="background: #f3f3f3;">
        <div class="couponsList">
            <volist name="list" id="vo">
       	  		<div class="couponsItem" c-id="{$vo.id}">
		             <div class="price">{$vo.price}<label>元</label></div>
		             <div class="infos">
		                <div class="title">满{$vo.range}可使用{$vo.price}</div>
		                <div class="marks">
		                   有效期至{$vo.enddate}
		                </div>
                        <div class="marks">
		                   <if condition="$data.storename neq ''">
                                仅限{$data.storename}使用
                            <elseif condition="$data.product neq ''"/>
                                仅限{$data.productname}商品使用
                            <elseif condition="$data.catname neq ''"/>
                                仅限{$data.catname}类目使用
                            <else />
                               任何商品可使用（企业商品除外）
		                   </if>
		                </div>
		             </div>
		             <eq name="vo['status']" value="1">
		             	<div class="state"><label class="not">不<br />可<br />用</label></div>
		             </eq>
		             <eq name="vo['status']" value="0">
		             	<div class="state"><label class="yes">可<br />用</label></div>
		             </eq>
		         </div>
       	  </volist>
        </div>
    </div>
</body>
</html>

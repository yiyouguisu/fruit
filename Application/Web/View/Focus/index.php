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
    <script type="text/javascript" src="__JS__/jquery.min.js"></script>
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
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
        <h1>我的关注</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff;">
        <div class="focus">
            <!--<div class="goodDetail">
                <div class="image"><a href="{:U('Web/Product/infoview',array('id'=>$vo['pid']))}">
                    <img alt="" src="__ROOT__{$vo.data.thumb}" /></a></div>
                <div class="infos">
                    <a href="{:U('Web/Product/infoview',array('id'=>$vo['pid']))}">
                        <div class="title">
                            <img alt="" src="__IMG__/orderStyleTh.png" />{$vo.title}</div>
                    </a>
                    <div class="marks">{$vo.data.description}</div>
                    <div class="price">
                        <label class="priceNow">￥{$vo.data.nowprice}/{$vo.data.standard}</label><label class="priceOld">￥{$vo.data.oldprice}</label>
                    </div>
                </div>
            </div>-->
            <volist name="datalist" id="vo">
       		<div class="goodDetail">
	              <div class="image"><a href="{:u('web/product/infoview',array('id'=>$vo['pid']))}"><img alt="" src="__ROOT__{$vo.thumb}" /></a></div>
	              <div class="infos">
	              		<eq name="vo['type']" value = "1"> 
                   			<a href="{:u('web/product/infoview',array('id'=>$vo['pid']))}">
                   				<div class="title"><img alt="" src="__IMG__/orderstyleth.png" />{$vo.title}</div>
                   			</a>
                   		</eq>
                   		<eq name="vo['type']" value = "2"> 
                   			<a href="{:u('web/product/infoview',array('id'=>$vo['pid']))}">
                   				<div class="title"><img alt="" src="__IMG__/orderstyletg.png" />{$vo.title}</div>
                   			</a>
                   		</eq>
                   		<eq name="vo['type']" value = "3"> 
                   			<a href="{:u('web/product/infoview',array('id'=>$vo['pid']))}">
                   				<div class="title"><img alt="" src="__IMG__/orderstyleyg.png" />{$vo.title}</div>
                   			</a>
                   		</eq>
                   		<eq name="vo['type']" value = "4"> 
                   			<a href="{:u('web/product/infoview',array('id'=>$vo['pid']))}">
                   				<div class="title"><img alt="" src="__IMG__/orderstylecz.png" />{$vo.title}</div>
                   			</a>
                   		</eq>
	              <div class="marks">{$vo.description}</div>
	              <div class="price">
	                  <label class="pricenow">￥{$vo.nowprice}/{$vo.standard}{$vo.unit}</label><label class="priceold">￥{$vo.oldprice}/{$vo.standard}{$vo.unit}</label>
	              </div>
	           </div>
	        </div>
       	</volist>
        </div>
    </div>
</body>
</html>

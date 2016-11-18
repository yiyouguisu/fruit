<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/list.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/weixin.jquery.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript" src="__JS__/jquery.min.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            $("#otheradd").click(function () {
                location.href = "{:U('Web/Public/modifyaddr')}";
            })
        })

        function changeplace() {
            location.href = "{:U('Web/Public/modifyaddr')}";
        }

        function chosestore() {
            if (confirm('亲，当前没有商家呦～请选择其他店铺吧！')) {
                location.href = "{:U('Web/Public/modifyaddr')}";
            } else {
                return false;
            }
        }
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="mylogo" href="javascript:" target="_self">
                <img alt="" src="__IMG__/icon_logo.png" /></a>
        </div>
        <h1 id="place">选择店铺<a class="ondown" id="otheradd"></a></h1>
        <div class="r" onclick="chosestore()">
            <img alt="" src="__IMG__/icon_search.png" id="search"  />
        </div>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff">
        <div class="goodsEmpty">
            <div class="image"><img src="__IMG__/icon_Stores.png" /></div>
            <div class="title">亲，当前没有商家呦～请选择其他店铺吧！</div>
            <div class="change" onclick="changeplace()">换店铺</div>
        </div>
    </div>
    <include file="Common:foot2" />
</body>
</html>

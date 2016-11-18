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
            location.href = "{:U('Web/Product/lists')}";
        }
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a id="toolReturn" class="return"  target="_self" onclick="location.href='{:U('Web/Product/lists')}'"></a></div>
       <h1>购物车</h1>
    </div>
    <div id="page_info" class="page_info">
        <div class="goodsEmpty">
            <div class="image"><img src="__IMG__/icon_shopping_cart1.png" /></div>
            <div class="title">亲，购物车空空如也~先去逛逛吧！</div>
            <div class="change" onclick="changeplace()">去逛逛</div>
        </div>
    </div>
    <include file="Common:foot" />
</body>
</html>
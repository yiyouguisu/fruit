<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/swiper.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="__JS__/swiper.min.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript">
        wxglobal.SetSize(function () {
            $("#htmlSearchWord").width($(window).width() - 100 + 'px');
        });
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
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Index/index')}" target="_self"></a></div>
        <input id="htmlSearchWord" name="htmlSearchWord" type="text" class="search" placeholder="搜索店铺名" style="margin-right: 15px" />
        <div class="r" style="top: 9px"><a id="toolWenzhi" class="wenzhi" href="javascript:" target="_self">确定</a></div>
    </div>
    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div id="searcherEnd" class="searcherEnd">
            <volist name="list" id="vo">
       	      <div class="item" onclick="addr(this)" id="{$vo.storeid}">
                 <div class="image"><img src="{$vo.thumb}"/></div>  
                 <div class="title"><a href="{:U('Web/Index/index',array('id'=>$vo['storeid']))}">{$vo.storename}</a></div>
                 <div class="marks">{$vo.address}</div>
                 <div class="mit">{$vo.distance}km</div>
              </div>
            </volist>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#toolWenzhi").click(function () {
                var seartext = $("#htmlSearchWord").val();
                console.log(seartext);
                if (seartext != '' && seartext != null) {
                    location.href = "{:U('Web/Public/modifyaddr')}?keyword=" + seartext;
                }
                else {
                    location.href = "{:U('Web/Public/modifyaddr')}";
                }
            })
        });

        function addr(obj) {
            location.href = "{:U('Web/Index/index')}?id=" + obj.id;
        }
    </script>
</body>
</html>

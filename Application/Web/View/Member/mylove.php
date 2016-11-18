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
        wxglobal.SetSize(function () {
            $("#htmlKeyword").width($(window).width() - 80 + 'px');
        });

        $(function () {
            $("#search").click(function () {
                var keyword = $("#htmlKeyword").val();
                var url = '{:U("Web/Query/lists")}?keyword=' + keyword;
                console.log(url);
                location.href = url;
            })

            $("#toolSubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "{:U('Web/Query/dclear')}",
                    dataType: "json",
                    success: function (data) {
                        location.reload();
                    }
                });
            })

            $(".keySearch ul li").click(function () {
                if ($(this).hasClass('hover')) {
                    $(this).removeClass('hover');
                } else {
                    $(this).addClass('hover');
                }
                var data = '';
                $(".keySearch ul li").each(function () {
                    if ($(this).attr('class') == 'hover') {
                        data += $(this).find('a').attr('vid') + ',';
                    }
                });
                data = data.substring(0, data.length - 1)
                $.ajax({
                    type: "POST",
                    url: "{:U('Web/Member/edit')}",
                    data: { 'preference': data },
                    dataType: "json",
                    success: function (data) {
                        
                    }
                });
            })
        });
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
        <h1>个人偏好</h1>
    </div>
    <div id="page_info" class="page_info">
        <div class="keySearch">
            <ul>
                <volist name="data" id="vo">
                    <eq name="vo['islove']" value="1"><li class="hover" ><a vid ="{$vo.value}">{$vo.name}</a></li></eq>
                    <eq name="vo['islove']" value=""><li ><a vid ="{$vo.value}">{$vo.name}</a></li></eq>
                  
                </volist>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>

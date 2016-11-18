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
        });
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a class="return" href="{:U('Web/Index/index')}" target="_self"></a></div>
       <input id="htmlKeyword" name="htmlKeyword" type="text"  />
       <div class="r"><img alt="" src="__IMG__/icon_search.png"  id="search"/></div>
    </div>
    <div id="page_info" class="page_info">
       <div class="keySearch">
          <h2>热门搜索</h2>
          <ul>
              <volist name="data" id="vo">
                  <li><a href="{:U('Web/Query/lists')}?keyword={$vo.hotkey}" target="_self">{$vo.keyword}</a></li>
              </volist>
          </ul>
          <div class="clear"></div>
          <h2>历史搜索</h2>
          <ul>
              <volist name="keywordlist" id="keys">
                  <li><a href="{:U('Web/Query/lists')}?keyword={$keys.name}" target="_self">{$keys.value}</a></li>
              </volist>
          </ul>
          <div class="clear"></div>
       </div>
       <div class="nrongDiv">
          <input id="toolSubmit" name="toolSubmit" type="button" value="清空历史记录" class="toolSubmit" />
       </div>
    </div>
</body>
</html>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <style type="text/css">
        .page_head{
            position:relative;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $(".toolTuichu").click(function () {
                if (confirm("确定要推出当前帐号?")) {
                    window.location.href = "{:U('Web/Member/loginout')}";
                } else {
                    return false;
                }
            })
        })
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a id="toolReturn" class="return" href="index.html" target="_self"></a></div>
       <h1>设置</h1>
    </div>
    <div id="page_info" class="page_info">
       <img alt="" src="__IMG__/settingLogo.png" style="width:100px; height:100px; position:absolute; left:50%; top:20px; margin-left:-50px;" />
       <div class="userMenuList" style="margin-top:140px;">
          <ul>
             <!--<li onclick="alert('清除缓存成功')">
                <img class="icon" alt="" src="__IMG__/icon_clear.png" /><p>清空缓存</p>
                <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
             </li>-->
             <li onclick="window.location.href='about.html';">
                <img class="icon" alt="" src="__IMG__/icon_about.png" /><p>关于蔬果先生</p>
                <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
             </li>
             <li onclick="window.location.href='feedBack.html';" style="border:0">
                <img class="icon" alt="" src="__IMG__/icon_feedback.png" /><p>意见反馈</p>
                <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
             </li>
          </ul>
       </div>
       <div class="nrongDiv">
          <input id="toolSubmit" name="toolSubmit" type="button" value="退出当前账号" class="toolTuichu" />
       </div>
    </div>
</body>
</html>
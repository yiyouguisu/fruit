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

    <script type="text/javascript">
        $(function () {
            $("#qiandao").click(function () {
                //console.log(111);
                $.ajax({
                    type: "POST",
                    url: "{:U('Web/Member/sign')}",
                    dataType: "json",
                    success: function (data) {
                        //console.log(data);
                        //console.log(data.msg);
                        alert(data.msg);
                        location.reload();
                    }
                });
            });
        })
    </script>
</head>
<body>
    <div id="page_head" class="page_head"  >
       <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
       <h1>我的积分</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff;">
       <div class="pointHead">
          <img alt="" src="__IMG__/pointHeadBg.png" class="backImg" />
          <div style="top:10px;">
             <span class="pointNum">{$isusenum}</span>
             <span class="qian_dao">
             	<img alt="" src="__IMG__/icon_registration.png" id="qiandao" />
             	</span>
          </div>
          <div style="top:60px;">
             <span class="pointTip">当前可用积分</span>
             <span class="point_lv">*100积分=1元</span>
          </div>
       </div>
       <div class="pointList">
       		<volist name="list" id="vo">
       			<div class="pointItem">
		              <div class="title">
		                 <div class="bt">{$vo.content}</div>
		                 <div class="sj">{$vo.thedate}</div>
		              </div>
		              <div class="score"><eq name="vo[paytype]" value="1">+</eq><eq name="vo[paytype]" value="2">-</eq>{$vo.integral}</div>
		           </div>
       		</volist>
       </div>
    </div>
</body>
</html>
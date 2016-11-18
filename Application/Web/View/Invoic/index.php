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
            height: 100%;
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
            position:fixed;
            position:relative;
            left: 0px;
            bottom: 0px;
            _position:absolute;
            position:fixed; height:54px; text-align:center;  border-top:1px solid #CCC; left:0px; bottom:0px; _position:absolute;
            z-index:9999;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Member/index')}" target="_self"></a></div>
        <h1>我的发票</h1>
    </div>

    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <ul class="invoiceTabs">
            <li class="hover"><a href="{:U('Web/Invoic/index')}" target="_self">未申请</a></li>
            <li class="nomal"><a href="{:U('Web/Invoic/isalready')}" target="_self">已申请</a></li>
        </ul>
        <div style="background-color: #ffefbe; padding: 10px; color: #895c23; font-size: 15px; line-height: 20px;">
            <img alt="" src="__IMG__/icon_tip.png" style="margin-right: 10px;" />备注：因财务入账周期，仅为近7天内已成交的订单开具发票。
        </div>
        <div class="invoiceList">
            <volist name="list" id="vo">
       		<div class="item" invoice-id="{$vo.orderid}">
                 <div class="check"><span class="unSelect"></span></div>
                 <div class="infos">
                    <div class="price">订单金额：<label>￥{$vo.money}</label></div>
                    <div class="order">订单编号：<label>{$vo.orderid}</label></div>
                 </div>
              </div>
       	</volist>
        </div>
        <div id="page_foot" class="page_foot">
            <div style="width: auto; float: left;">
                <span class="unSelect" style="float: left; margin: 12px 12px 12px 10px;"></span>
                <span style="color: #666666; float: left; font-weight: 600;">全选</span>
            </div>
            <div style="width: 99px; float: right; margin-right: 10px;">
                <input id="toolSubmit" name="toolSubmit" type="button" value="申请" class="toolGorder" style="margin-bottom: 2px;" />
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#toolSubmit").click(function () {
                var data = '';
                $(".item").each(function () {
                    if ($(this).find('.check span').hasClass("isSelect")) {
                        data += $(this).attr('invoice-id') + ',';
                    }
                })
                //console.log(data);
                if (data != '') {
                    data = data.substring(0, data.length - 1);
                    $.post(
                    "{:U('Web/Invoic/cache')}",
                    {
                        "orderid": data
                    },
                    function (response, status) {
                        console.log(status);
                        if (status == "success") {
                            location.href = "{:U('Web/Invoic/changeaddr')}";
                        };
                    },
                    "json");
                }
                else {
                    alert('没有选择任何发票！');
                }
            })

            $(".invoiceList").on('click', '.unSelect', function () {
                $(this).attr('class', 'isSelect');
            })

            $(".invoiceList").on('click', '.isSelect', function () {
                $(this).attr('class', 'unSelect');
            })

            $(".page_foot").on('click', '.unSelect', function () {
                $(".invoiceList").find('.unSelect').attr('class', 'isSelect');
                $(this).attr('class', 'isSelect');
            })

            $(".page_foot").on('click', '.isSelect', function () {
                $(".invoiceList").find('.isSelect').attr('class', 'unSelect');
                $(this).attr('class', 'unSelect');
            })
        })
    </script>
</body>
</html>

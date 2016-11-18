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
            var orderid = "{$orderid}";
            $("#ordercancel").click(function () {
                if (confirm("是否取订单？")) {
                    console.log(orderid);
                    $.ajax({
                        type: "GET",
                        url: "{:U('Web/Order/closeorder')}",
                        data: { 'orderid': orderid },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            location.href = "{:U('Web/Order/index')}";
                        }
                    });
                } else {
                    return false;
                }


            })

            $(".go_pay").click(function () {
                var orderid = $(this).attr('good-id');
                //alert(orderid);
                $.post(
			        "{:u('web/cat/onlinpingpp')}", {
			            "orderid": orderid
			        },
			        function (response, status) {
			            if (status == "success") {
			                //alert('1');
			                location.href = "{:U('Web/Pay/index')}";
			            };
			        },
			        "json");
            })
        });
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
       <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Order/index')}" target="_self"></a></div>
       <h1>订单详情</h1>
    </div>
    <div id="orderTabs" class="orderTabs" style="margin-top:55px">
       <ul>
          <li class="hover"><a href="{:U('Web/Order/status',array('id'=>$orderid))}" target="_self">订单状态</a></li>
           <li class="nomal"><a href="{:U('Web/Order/show',array('id'=>$orderid))}" target="_self">订单详情</a></li>
       </ul>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff;">
       <div class="orderStatus">
          <ul>
          <volist name="list" id="vo">
          		<if condition="$key eq (count($list)-1)">
          			<li><span class="new"></span>{$vo.title}{$vo.sendtime}</li>
          		<else />
          			<li><span class="old"></span>{$vo.title}{$vo.sendtime}</li>
          		</if>
          </volist>
          </ul>
       </div>
    </div>
    <!--<div id="page_foot" class="page_foot">
       <span style="float:right; margin:7px 10px 0px 0px;">
          <input type="button" value="取消订单" id="ordercancel" class="order cancel" {$iscencalview}/>
          <input type="button" value="去支付"   class="order go_pay" good-id="{$orderid}" {$ispayview}/>
       </span>
    </div>-->
</body>
</html>
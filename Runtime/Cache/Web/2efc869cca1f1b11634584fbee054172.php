<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/jsplug/layerMob/layer.m.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <link rel="stylesheet" href="/Public/Web/css/weui.css">
    <link rel="stylesheet" href="/Public/Web/css/jquery-weui.css">
    <script>
        $(function () {
            var myDate = new Date();
            var hour = myDate.getHours();
            $("#sendtimes").hide();

            if ('<?php echo ($pricewait); ?>' == '(待称重)')
                alert('您的订单包含称重商品哦，需等待仓库称重完成才能继续支付，或者直接选择货到付款');

            $("#sendday").change(function () {
                $("#sendtimes").html("");
                $("#sendtimes").show();
                $("#sendtimes").append("<option value='1'>08:00至09:59</option>");
                $("#sendtimes").append("<option value='2'>10:00至11:59</option>");
                $("#sendtimes").append("<option value='3'>11:00至12:59</option>");
                $("#sendtimes").append("<option value='4'>12:00至13:59</option>");
                $("#sendtimes").append("<option value='5'>13:00至14:59</option>");
                $("#sendtimes").append("<option value='6'>14:00至15:59</option>");
                $("#sendtimes").append("<option value='7'>15:00至16:59</option>");
                $("#sendtimes").append("<option value='8'>16:00至17:59</option>");
                $("#sendtimes").append("<option value='9'>17:00至18:59</option>");
                $("#sendtimes").append("<option value='10'>18:00至19:59</option>");
                $("#sendtimes").append("<option value='11'>19:00至20:59</option>");
                $("#sendtimes").append("<option value='12'>20:00至21:59</option>");
                $("#sendtimes").append("<option value='13'>21:00至21:59</option>");
                if ($("#sendday  option:selected").text() == '极速达(付款后90分钟内)' || $("#sendday  option:selected").text() == '请选择') {
                    $("#sendtimes").hide();
                }
                if ($("#sendday  option:selected").text() != '明天') {
                    var hour = myDate.getHours();
                    if (hour >= 8 && hour < 10) {
                        gethourTo(1);
                    }
                    else if (hour >= 10 && hour < 11) {
                        gethourTo(2);
                    }
                    else if (hour >= 11 && hour < 12) {
                        gethourTo(3);
                    }
                    else if (hour >= 12 && hour < 13) {
                        gethourTo(4);
                    }
                    else if (hour >= 13 && hour < 14) {
                        gethourTo(5);
                    }
                    else if (hour >= 14 && hour < 15) {
                        gethourTo(6);
                    }
                    else if (hour >= 15 && hour < 16) {
                        gethourTo(7);
                    }
                    else if (hour >= 16 && hour < 17) {
                        gethourTo(8);
                    }
                    else if (hour >= 17 && hour < 18) {
                        gethourTo(9);
                    }
                    else if (hour >= 18 && hour < 19) {
                        gethourTo(10);
                    }
                    else if (hour >= 19 && hour < 20) {
                        gethourTo(11);
                    }
                    else if (hour >= 20 && hour < 21) {
                        gethourTo(12);
                    }
                    else if (hour >= 21) {
                        gethourTo(13);
                    }
                }
                if($("#sendday  option:selected").text() == '极速达(付款后90分钟内)'){
                    var ordertype = $("#ordertype").val();
                    var integral = '<?php echo ($integral["useintegral"]); ?>';
                    var ordertotal = parseFloat($("#orderPrice").html().replace('￥', ''));
                    if (ordertype != 2 && (ordertotal < 199 && integral < 500)) {
                        alert('当积分满500积分或者订单总额满199元才可使用极速达，请选择其他配送方式。');
                        $("#sendday  option:selected").removeAttr("selected");
                        return false;

                    } else if (ordertype != 2 && (ordertotal < 199 && integral >= 500)) {
                        if (confirm('订单金额未满199元，选择极速达服务需消耗500积分')) {
                            return true;
                        } else {
                            $("#sendday  option:selected").removeAttr("selected");
                            return false;
                        };
                    }
                    
                }else{
                    $(this).css("width","60px");
                }
            })


            function gethourTo(thisindex) {
                for (var i = 1; i < thisindex + 1; i++) {
                    $("#sendtimes option[value='" + i + "']").remove();
                }
            }

            $("#choosePayment").click(function () {
                open($("#paystyle"));
            })

            $("#choosePayment").change(function () {
                $("#choosePayment").find('span').html($(this).find("option:selected").text());
            })

            // $("#sendday").click(function () {
            //     open($("#sendday"));
            // })
            $("#choosecoupons").click(function () {
                $(".mask,.poup").show()
            })
     
           $(".mask,.pf_g").on("click", function () {
               $(".mask,.poup").hide();  
           })
           $(".poup-put,.poup-put .sure").on("click", function () {
               $(".mask,.poup").hide();  
           })

            $(".select").on("click", function () {
                $(this).find("span").removeClass('unSelect').addClass("isSelect");
                $(this).parent().siblings().find(".select span").addClass("unSelect");
            });

        })

        function open(elem) {
            if (document.createEvent) {
                var e = document.createEvent("MouseEvents");
                e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                elem[0].dispatchEvent(e);
            } else if (element.fireEvent) {
                elem[0].fireEvent("onmousedown");
            }
        }
       function changecoupons(obj,couponsid,couponstitle,couponsprice){
            $(obj).find(".select").find("span").removeClass('unSelect').addClass("isSelect");
            $(obj).siblings().find(".select span").addClass("unSelect");
            var ordertype = $("#ordertype").val();
                $("input[name='couponsid']").val(couponsid);
                $("#couponstitle").text("-￥"+couponsprice);
                var price=parseFloat(couponsprice);
                var tpay = parseFloat($("#tital").val());
                var qianbaodikou = parseFloat($("#qianbaodikou").val());
                var goodtital = parseFloat($("#goodtital").html().replace('￥', ''));
                if(ordertype==2){
                    if(price<goodtital){
                        if(price<goodtital-qianbaodikou){
                            $("#dicountdi").html('￥' + parseFloat(price));
                            $("#discountdikou").val(parseFloat(price));
                        }else{
                            $("#dicountdi").html('￥' + parseFloat((goodtital*100-qianbaodikou*100)/100));
                            $("#discountdikou").val(parseFloat((goodtital*100-qianbaodikou*100)/100));
                        }

                    }else{
                        $("#dicountdi").html('￥' + parseFloat(goodtital));
                        $("#discountdikou").val(parseFloat(goodtital));
                    }
                }else{
                    if(price<goodtital){
                        if(price<goodtital-qianbaodikou){
                            $("#dicountdi").html('￥' + parseFloat(price));
                            $("#discountdikou").val(parseFloat(price));
                            $("#tital").val(parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#shijipay").html('￥' + parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#orderPrice").html('￥' + parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#qianbaodi").html('￥' + parseFloat(qianbaodikou));
                            $("#qianbaodikou").val(parseFloat(qianbaodikou));
                            $("#qianbao").attr('src', '/Public/Web/images/icon_on.png');
                        }else{
                            $("#dicountdi").html('￥' + parseFloat((goodtital*100-qianbaodikou*100)/100));
                            $("#discountdikou").val(parseFloat((goodtital*100-qianbaodikou*100)/100));
                            $("#tital").val(parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#shijipay").html('￥' + parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#orderPrice").html('￥' + parseFloat((goodtital*100-qianbaodikou*100-price*100)/100));
                            $("#qianbaodi").html('￥' + parseFloat(qianbaodikou));
                            $("#qianbaodikou").val(parseFloat(qianbaodikou));
                            $("#qianbao").attr('src', '/Public/Web/images/icon_on.png');
                        }

                    }else{
                        $("#dicountdi").html('￥' + parseFloat(goodtital));
                        $("#discountdikou").val(parseFloat(goodtital));
                        $("#tital").val("0.00");
                        $("#shijipay").html('￥0.00');
                        $("#orderPrice").html('￥0.00');
                        $("#qianbaodi").html('￥0.00');
                        $("#qianbaodikou").val("0.00");
                        $("#qianbao").attr('src', '/Public/Web/images/icon_off.png');
                    }
                        

                    if ($("#goodsstatus").html() == '(待称重)') {
                        $("#shijipay").html('￥0.00');
                        $("#qianbaodi").html('￥0.00');
                        $("#orderPrice").html('￥0.00');
                    }

                    
                }
            }
    </script>
</head>
<body>
    <form action="<?php echo U('Web/cat/addorder');?>" method="post">
        <div id="page_head" class="page_head">
            <div class="l"><a class="return" href="<?php echo U('Web/Cat/lists');?>" target="_self"></a></div>
            <h1>确认订单</h1>
        </div>
        <div id="page_info" class="page_info" style="background-color: #ffffff;margin-top:0px;">
            <div class="orderInfo">
                <div class="infoDetail" style="padding-top: 10px;">
                    <div class="addrDetail">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <td style="width: 32px;">&nbsp;</td>
                                    <td style="width: auto;">&nbsp;</td>
                                    <td style="width: 50px;">&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 6px;">
                                        <span class="name">收件人：<?php echo ($address["name"]); ?></span>
                                        <?php if(($address['type']) == "1"): ?><span class="type">公司</span><?php endif; ?>
                                        <?php if(($address['type']) == "2"): ?><span class="type">家</span><?php endif; ?>
                                        <?php if(($address['type']) == "3"): ?><span class="type">其他</span><?php endif; ?>
                                    </td>
                                    <td rowspan="3" style="padding-bottom: 6px; text-align: right; vertical-align: middle;">
                                        <?php if($address["id"] != ''): ?><a href="<?php echo U('Web/Address/index',array('addressid'=>$address['id']));?>">
                                                <img alt="" src="/Public/Web/images/icon_arrow.png" id="changeaddress" />
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo U('Web/Address/index',array('addressid'=>'new'));?>">
                                                <img alt="" src="/Public/Web/images/icon_arrow.png" id="changeaddress" />
                                            </a><?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="/Public/Web/images/icon_AddDetail.png" style="margin-left: 0px;" /></td>
                                    <td>收货地址：<?php echo ($address["province"]); echo ($address["city"]); echo ($address["areas"]); echo ($address["areatext"]); ?>
                                        <?php echo ($address["address"]); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="/Public/Web/images/icon_AddMobile.png" style="margin-left: 3px;" /></td>
                                    <td>联系电话：<?php echo ($address["tel"]); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="addressid" value="<?php echo ($address["id"]); ?>">
                </div>
                <?php if($ordertype != '2'): ?><div style="height: 20px; background-color: #f3f3f3"></div>
                <div class="infoHeader" id="chooseAddDate">
                    <div class="title">收货时间</div>
                    <div class="infos">
                        <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" style="float:right" />
                        <select name="sendtime" id="sendtimes">
                        </select>
                        <select name="sendday" id="sendday">
                            <option value="0">请选择</option>
                            <?php if($ordertype != '3'): if(date('H')>=8 && date('H') < 20){ echo "<option value='1'>极速达(付款后90分钟内)</option>"; } endif; ?>
                            <?php  if(date('H')<=20){ echo "<option value='2'>今天</option>"; } ?>
                            
                            <option value="3">明天</option>
                        </select>
                        
                    </div>
                </div><?php endif; ?>
                <?php if($isdisplay == ''): ?><div style="height: 20px; background-color: #f3f3f3" ></div><?php endif; ?>
                <div class="infoHeader" id="choosePayment" <?php echo ($isdisplay); ?>>
                    <div class="title">支付方式</div>
                    <div class="infos">
                        <span>请选择</span>
                        <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" />
                        <select id="paystyle" name="paystyle" style="float: left; color: #ffffff; opacity: 0.01">
                            <!--<?php echo ($vo["type"]); ?>-->
                            <?php if($ordertype == '2'): ?><option value="0">请选择</option>
                                <option value="1">微信支付</option>
                            <?php elseif($ordertype == '3'): ?>

                             <?php else: ?> 
                                <option value="0">请选择</option>
                                <option value="2">货到付款</option>
                                <option value="1">微信支付</option><?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="infoHeader" id="choosecoupons" <?php echo ($isdisplay); ?>>
                    <div class="title">优惠券</div>
                    <div class="infos">
                        <?php if($couponstitle == ''): ?><span id="couponstitle">无可用优惠券</span>
                        <?php else: ?>
                             <span id="couponstitle"><?php echo ($couponstitle); ?></span>
                             <!-- <select id="coupons" name="couponsid" style="float: left; color: #ffffff; opacity: 0.01">
                                <option value="">可用优惠券</option>
                                <?php if(is_array($coupons)): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $couponsid): ?>selected<?php endif; ?>><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select> --><?php endif; ?>
                        <input type="hidden" name="couponsid" value="<?php echo ($couponsid); ?>">
                        <img class="turn" alt="" src="/Public/Web/images/icon_arrow.png" id="changecoup" />
                        
                    </div>
                </div>
                <div class="infoHeader" <?php echo ($isdisplay); ?>>
                    <div class="title" style="width:150px;">钱包<span style="color:#f59c0c;padding-left:10px;" id="accountqianbao">￥<?php echo ($accountmoney); ?></span></div>
                    <div class="infos">
                        <?php if($wallet <= 0): ?><img src="/Public/Web/images/icon_off.png" style="width:32px" id="qianbao"/>
                            <?php else: ?>
                            <img src="/Public/Web/images/icon_on.png" style="width:32px" id="qianbao"/><?php endif; ?>
                    </div>
                </div>
                <div style="height: 20px; background-color: #f3f3f3"></div>
                <div class="infoHeader">
                    <div class="title">商品列表</div>
                    <div class="infos">
                    </div>
                </div>
                <div class="infoDetail">
                    <?php if(is_array($goodslist)): $i = 0; $__LIST__ = $goodslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="goodDetail">
                         <div class="image"><img alt="" src="<?php echo ($vo["thumb"]); ?>" /></div>
                         <div class="infos">
                            <?php if(($vo[type]) == "0"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleQy.png" /><?php echo ($vo["title"]); ?><span style="float:right;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                            <?php if(($vo[type]) == "1"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTh.png" /><?php echo ($vo["title"]); ?><span style="float:right;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                            <?php if(($vo[type]) == "2"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTg.png" /><?php echo ($vo["title"]); ?><span style="float:right;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                            <?php if(($vo[type]) == "3"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleYg.png" /><?php echo ($vo["title"]); ?><span style="float:right;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                            <?php if(($vo[type]) == "4"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleCz.png" /><?php echo ($vo["title"]); ?><span style="float:right;color:#f59c0c">￥<?php echo ($vo["ttotal"]); ?></span></div><?php endif; ?>
                            <div class="marks"><?php echo ($vo["description"]); ?></div>
                            <div class="price">
                               <label class="price">￥<?php echo ($vo["nowprice"]); ?>/<?php echo ($vo["standard"]); echo ($vo["unit"]); ?></label>
                               <label class="count">X <?php echo ($vo["goodsnum"]); ?></label>
                            </div>
                         </div>
                      </div>
                      <input type="hidden" name="ordertype" value="<?php echo ($vo["type"]); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div style="height: 20px; background-color: #f3f3f3"></div>
                <div class="infoHeader">
                    <div class="title">商品总价</div>
                    <div class="infos">
                        <span style="color: #f59c0c;" id="goodtital">￥<?php echo ($goodstotaltemp); ?></span>
                    </div>
                </div>
                <?php if($ordertype == '2'): ?><div class="infoHeader">
                        <div class="title">预付定金</div>
                        <div class="infos">
                            <span style="color: #f59c0c;" id="yufu">￥<?php echo ($yufu); ?></span>
                        </div>
                    </div><?php endif; ?>
                
                <div class="infoHeader">
                    <div class="title">运费</div>
                    <div class="infos">
                        <span style="color: #f59c0c;">￥0.00</span>
                        <input type="hidden" name="delivery" value="0">
                    </div>
                </div>
                <?php if($ordertype != '3'): ?><div class="infoHeader">
                        <div class="title">优惠券抵扣</div>
                        <div class="infos">
                            <span style="color: #f59c0c;" id="dicountdi">￥<?php echo ($coupons_price); ?></span>
                        </div>
                    </div>
                    <div class="infoHeader">
                        <div class="title">钱包抵扣</div>
                        <div class="infos">
                            <span style="color: #f59c0c;" id="qianbaodi">￥<?php echo ($qianbaodikou); ?></span>
                        </div>
                    </div>
                    <div class="infoHeader">
                        <div class="title">实际支付</div>
                        <div class="infos">
                            <span style="color: #f59c0c;" id="shijipay">￥<?php echo ($goodstotal); ?></span><span style="color: #f59c0c;" id="goodsstatus"><?php echo ($pricewait); ?></span>
                        </div>
                    </div><?php endif; ?>
                <input type="hidden" name="wallet" value="<?php echo ($wallet); ?>" id="qianbaodikou">
                 <input type="hidden" name="discount" value="<?php echo ($coupons_price); ?>" id="discountdikou">
                <input type="hidden" name="money" value="<?php echo ($total); ?>" id="tital">
                <input type="hidden" name="iscontainsweigh" value="<?php echo ($isgoodswidth); ?>" />
                <div style="height: 20px; background-color: #f3f3f3"></div>
                <div class="infoHeader">
                    <div class="title" >无订单留言<img src="/Public/Web/images/icon_off.png"  id="nomessage"/> </div>
                    <div class="titler">订单留言<img src="/Public/Web/images/icon_on.png" id="yesmessage"/> </div>
                </div>
                <div class="infoDetail">
                    <textarea id="htmlOrderMessage" name="orderremark" rows="10" cols="10" class="areaRemark"></textarea>
                </div>
                <div style="height: 20px; background-color: #f3f3f3"></div>
                <div class="infoHeader">
                    <div class="title">贺卡留言<img src="/Public/Web/images/icon_off.png" id="hekamessage" /></div>
                </div>
                <div class="infoDetail" id="hekadetal" style="display:none">
                    <textarea id="htmlCardMessage" name="cardremark" rows="10" cols="10" class="areaRemark"></textarea>
                </div>
            </div>
        </div>
        <div id="page_foot" class="page_foot">
            <div style="width: auto; float: left;">
                <span id="orderPrice" style="margin-left: 10px; color: #f59c0c; font-size: 15px;">￥<?php echo ($goodstotal); ?></span><span style="color: #f59c0c; font-size: 15px;"><?php echo ($pricewait); ?></span>
            </div>
            <div style="width: 99px; float: right; margin-right: 10px;">
                <input type="hidden" id="ordertype" name="ordertype" value="<?php echo ($ordertype); ?>">
                <input type="hidden" id="iscardremark" name="iscardremark" value="0">
                <input type="hidden" id="isorderremark" name="isorderremark" value="1">
                <input id="toolSubmit" name="toolSubmit" type="submit" value="提交" class="toolGorder" style="margin-bottom: 2px;" />
            </div>
        </div>
    </form>
    <div class="mask"></div>
<div class="poup">
   <div class="poup-top">可用优惠券
         <div class="pf_g"><img src="/Public/Web/images/close.jpg"></div>
   </div>
   <div class="poup-btm">
       <ul>
        <?php if(is_array($coupons)): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li onclick="changecoupons(this,'<?php echo ($vo["id"]); ?>','<?php echo ($vo["title"]); ?>','<?php echo ($vo["price"]); ?>');"> 
                <div class="select"><span class="unSelect"></span></div>
                <div class="poupfr">
                    <div class="poup-b1"><?php echo ($vo["title"]); ?></div>
                    <div class="poup-b2"><?php echo ($vo["descripiton"]); ?></div>
                    <div class="poup-b3 ">-<?php echo ($vo["price"]); ?>元</div>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>  
            
            
       </ul>
   </div>

   <div class="poup-put">
        <a href="javascript:;" class="sure">确认</a>
   </div>
</div>
     <script src="/Public/Web/js/jquery-2.1.4.js"></script>
   <script src="/Public/Web/js/jquery-weui.js"></script>
    <script type="text/javascript">
        $("#changeaddress").click(function () {
            location.href = "<?php echo U('Web/Address/index');?>";
        });

        $("#nomessage").click(function () {
            $(this).attr('src', '/Public/Web/images/icon_on.png');
            $("#yesmessage").attr('src', '/Public/Web/images/icon_off.png');
            $("#isorderremark").val(0);
            $("#htmlOrderMessage").hide();
        })

        $("#yesmessage").click(function () {
            $(this).attr('src', '/Public/Web/images/icon_on.png');
            $("#nomessage").attr('src', '/Public/Web/images/icon_off.png');
            $("#isorderremark").val(1);
            $("#htmlOrderMessage").show();
            
        })

        $("#toolSubmit").click(function () {
            var ordertype = $("#ordertype").val();
            var integral = '<?php echo ($integral["useintegral"]); ?>';
            var ordertotal = parseFloat($("#orderPrice").html().replace('￥', ''));
            var sendday= $("#sendday").val();
            var paystyle=$("#paystyle").val();
            var iswallet=$("#qianbao").attr('src');
            var tpay = parseFloat($("#tital").val());

            if (sendday == '0' && ordertype!=2)
            {
                alert('请选择收货时间！！');
                return false;
            }

            if (tpay>0&&paystyle==0)
            {
                alert('请选择支付方式！！');
                return false;
            }
            
            if($("#isorderremark").val()==1&&$("#htmlOrderMessage").val()==''){

                alert('亲，请输入订单留言');
                return false;
            }
            if($("#iscardremark").val()==1&&$("#htmlCardMessage").val()==''){
                alert('亲，请输入贺卡留言');
                return false;
            }
        })

        $(".infoHeader").on('click', '#hekamessage', function () {
            if ($(this).attr('src') == '/Public/Web/images/icon_off.png') {
                $(this).attr('src', '/Public/Web/images/icon_on.png');
                $("#iscardremark").val(1);
                $("#hekadetal").show();
            } else {
                $(this).attr('src', '/Public/Web/images/icon_off.png');
                $("#iscardremark").val(0);
                $("#hekadetal").hide();
            }
        })

        // $(".infoHeader").on('click', '#qianbao', function () {
        //     var ordertype = $("#ordertype").val();
        //     if ($(this).attr('src') == '/Public/Web/images/icon_off.png') {
        //         $(this).attr('src', '/Public/Web/images/icon_on.png');
        //         var tpay = parseFloat($("#tital").val());
        //         var ymoney = parseFloat($("#accountqianbao").html().replace('￥', ''));
        //         var goodtital = parseFloat($("#goodtital").html().replace('￥', ''));
                
        //         //钱包抵扣的钱
        //         if (ymoney > goodtital) {
        //             if(ordertype==2){
        //                 var yufu=parseFloat($("#yufu").html().replace('￥', ''));
        //                 $("#qianbaodi").html('￥' + yufu);
        //                 $("#qianbaodikou").val(yufu);
        //                 var totalmon = tpay - yufu;
        //             }else{
        //                 $("#qianbaodi").html('￥' + goodtital);
        //                 $("#qianbaodikou").val(goodtital);
        //                 var totalmon = tpay - goodtital;
        //             }
                    
        //         } else {
        //             $("#qianbaodi").html('￥' + ymoney);
        //             $("#qianbaodikou").val(ymoney);
        //             var totalmon = tpay - ymoney;
        //         }

        //         $("#shijipay").html('￥' + totalmon);
        //         $("#orderPrice").html('￥' + totalmon);
        //         $("#tital").val(totalmon);
        //         if ($("#goodsstatus").html() == '(待称重)') {
        //             $("#shijipay").html('￥0.00');
        //             $("#qianbaodi").html('￥0.00');
        //         }
        //     } else {
        //         $(this).attr('src', '/Public/Web/images/icon_off.png');
        //         //实际支付的钱
        //         var tpay = parseFloat($("#tital").val());
        //         var ymoney = parseFloat($("#qianbaodi").html().replace('￥', ''));
        //         var totalmon = parseFloat(tpay + ymoney);
        //         var goodtital = parseFloat($("#goodtital").html().replace('￥', ''));
                
        //         if(ordertype==2){
        //             var yufu=parseFloat($("#yufu").html().replace('￥', ''));
        //             totalmon=yufu;
        //         }else{
        //             totalmon=goodtital;
        //         }

        //         $("#shijipay").html('￥' + totalmon);
        //         $("#orderPrice").html('￥' + totalmon);
        //         $("#tital").val(totalmon);
        //         //钱包抵扣的钱
        //         $("#qianbaodi").html('￥0.0');
        //         $("#qianbaodikou").val('0');
        //         if ($("#goodsstatus").html() == '(待称重)') {
        //             $("#shijipay").html('￥0.00');
        //             $("#qianbaodi").html('￥0.00');
        //         }
        //     }
        // })
        $(".infoHeader").on('click', '#qianbao', function () {
            var ordertype = $("#ordertype").val();
            if ($(this).attr('src') == '/Public/Web/images/icon_off.png') {
                var tpay = parseFloat($("#tital").val());
                var ymoney = parseFloat($("#accountqianbao").html().replace('￥', ''));
                var goodtital = parseFloat($("#goodtital").html().replace('￥', ''));
                var discountdi = parseFloat($("#discountdikou").val());
                if(tpay=='0.00'){
                    alert("优惠券抵扣充足");
                    return false;
                }
                $(this).attr('src', '/Public/Web/images/icon_on.png');
                
                if(discountdi>0){
                        if (ymoney > goodtital) {
                            if(ordertype==2){
                                var yufu=parseFloat($("#yufu").html().replace('￥', ''));
                                $("#qianbaodi").html('￥' + yufu);
                                $("#qianbaodikou").val(yufu);
                                var totalmon = tpay - yufu;
                            }else{
                                $("#qianbaodi").html('￥' + parseFloat(goodtital-discountdi));
                                $("#qianbaodikou").val(parseFloat(goodtital-discountdi));
                                var totalmon = tpay - parseFloat(goodtital-discountdi);
                            }
                        } else {
                            $("#qianbaodi").html('￥' + ymoney);
                            $("#qianbaodikou").val(ymoney);
                            var totalmon = parseFloat(goodtital - ymoney-discountdi);
                        }

                        $("#shijipay").html('￥' + totalmon);
                        $("#orderPrice").html('￥' + totalmon);
                        $("#tital").val(totalmon);
                        if ($("#goodsstatus").html() == '(待称重)') {
                            $("#shijipay").html('￥0.00');
                            $("#qianbaodi").html('￥0.00');
                            $("#orderPrice").html('￥0.00');
                        }
                    
                    
                }else{
                    //钱包抵扣的钱
                    if (ymoney > goodtital) {
                        if(ordertype==2){
                            var yufu=parseFloat($("#yufu").html().replace('￥', ''));
                            $("#qianbaodi").html('￥' + yufu);
                            $("#qianbaodikou").val(yufu);
                            var totalmon = tpay - yufu;
                        }else{
                            $("#qianbaodi").html('￥' + goodtital);
                            $("#qianbaodikou").val(goodtital);
                            var totalmon = tpay - goodtital;
                        }
                        
                    } else {
                        $("#qianbaodi").html('￥' + ymoney);
                        $("#qianbaodikou").val(ymoney);
                        var totalmon = parseFloat(tpay-ymoney);
                    }

                    $("#shijipay").html('￥' + totalmon);
                    $("#orderPrice").html('￥' + totalmon);
                    $("#tital").val(totalmon);
                    if ($("#goodsstatus").html() == '(待称重)') {
                        $("#shijipay").html('￥0.00');
                        $("#qianbaodi").html('￥0.00');
                        $("#orderPrice").html('￥0.00');
                    }
                }
                
                
            } else {
                $(this).attr('src', '/Public/Web/images/icon_off.png');
                //实际支付的钱
                var tpay = parseFloat($("#tital").val());
                var ymoney = parseFloat($("#qianbaodi").html().replace('￥', ''));
                var totalmon = parseFloat(tpay + ymoney);
                var goodtital = parseFloat($("#goodtital").html().replace('￥', ''));
                var discountdi = parseFloat($("#discountdikou").val());
                
                if(ordertype==2){
                    var yufu=parseFloat($("#yufu").html().replace('￥', ''));
                    totalmon=yufu;
                }else{
                    totalmon=goodtital-discountdi;
                }

                $("#shijipay").html('￥' + totalmon);
                $("#orderPrice").html('￥' + totalmon);
                $("#tital").val(totalmon);
                //钱包抵扣的钱
                $("#qianbaodi").html('￥0.0');
                $("#qianbaodikou").val('0');
                if ($("#goodsstatus").html() == '(待称重)') {
                    $("#shijipay").html('￥0.00');
                    $("#qianbaodi").html('￥0.00');
                    $("#orderPrice").html('￥0.00');
                }
            }
        })


        $("#changecoup").bind("click", function () {
            var money = '<?php echo ($goodstotaltemp); ?>';
            var url = "<?php echo U('Web/Coupons/index');?>?id=change&money=" + money;
            wxglobal.OpenBox(url, $(window).width(), $(window).height() - 155);
        });

    </script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
      debug: false,
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone',
        'hideMenuItems'
      ]
  });
  wx.ready(function () {


  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareAppMessage({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {

      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','error');
      }
    });


  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareTimeline({
      title: '<?php echo ($share["content"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','error');
      }
    });


  // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口

    wx.onMenuShareQQ({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','error');
      }
    });

  
  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareWeibo({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','error');
      }
    });


  // 2.5 监听“分享到QZone”按钮点击、自定义分享内容及分享接口

    wx.onMenuShareQZone({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','error');
      }
    });
});

wx.error(function (res) {
  alert(res.errMsg);
});
function ajax_share(mid,sharetype,sharestatus){
    //$.ajax({
    //    type: "POST",
    //    url: "<?php echo U('Home/Index/ajax_share');?>",
    //    data: {'sharetype':sharetype,'sharestatus':sharestatus,'mid':mid},
    //    dataType: "json",
    //    success: function(data){
    //        if(sharestatus=='success'){
    //            window.location.href='/index.php/Index/order/mid/'+mid+'.html';
    //        }
                    
    //    }
    //});
}
</script>
</body>
</html>
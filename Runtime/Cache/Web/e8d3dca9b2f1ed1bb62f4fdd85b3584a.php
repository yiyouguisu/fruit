<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/list.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript" src="/Public/Web/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/iscroll.js"></script>
    <style type="text/css">
        #orderList {
            top: 0px;
        }
    </style>
    <script type="text/javascript">
        var thestatus = "all";
        $(function () {
            thestatus = Request.QueryString('status');
            if (Request.QueryString('status') == 'waitpay') {
                $("#orderHead").find('.hover').removeClass('hover').addClass('nomal');
                $("#orderHead ul li").eq(1).removeClass('nomal');
                $("#orderHead ul li").eq(1).addClass('hover');
                changeimg(1);

            }
            if (Request.QueryString('status') == 'waitpackage') {
                $("#orderHead").find('.hover').removeClass('hover').addClass('nomal');
                $("#orderHead ul li").eq(2).removeClass('nomal');
                $("#orderHead ul li").eq(2).addClass('hover');
                changeimg(2);
            }
            if (Request.QueryString('status') == 'waitconfirm') {
                $("#orderHead").find('.hover').removeClass('hover').addClass('nomal');
                $("#orderHead ul li").eq(3).removeClass('nomal');
                $("#orderHead ul li").eq(3).addClass('hover');
                $(".r").show();
                changeimg(3);
            }
            if (Request.QueryString('status') == 'waitevaluate') {
                $("#orderHead").find('.hover').removeClass('hover').addClass('nomal');
                $("#orderHead ul li").eq(4).removeClass('nomal');
                $("#orderHead ul li").eq(4).addClass('hover');
                changeimg(4);
            }

            function changeimg(thisindex) {
                switch (thisindex) {
                    case 1:
                        $("#orderHead ul li").eq(0).find('img').attr('src', '/Public/Web/images/icon_whole_default.png');
                        $("#orderHead ul li").eq(1).find('img').attr('src', '/Public/Web/images/icon_payment.png');
                        $("#orderHead ul li").eq(2).find('img').attr('src', '/Public/Web/images/icon_package_default.png');
                        $("#orderHead ul li").eq(3).find('img').attr('src', '/Public/Web/images/icon_receiving_default.png');
                        $("#orderHead ul li").eq(4).find('img').attr('src', '/Public/Web/images/icon_evaluation_default.png');
                        break;
                    case 2:
                        $("#orderHead ul li").eq(0).find('img').attr('src', '/Public/Web/images/icon_whole_default.png');
                        $("#orderHead ul li").eq(1).find('img').attr('src', '/Public/Web/images/icon_payment_default.png');
                        $("#orderHead ul li").eq(2).find('img').attr('src', '/Public/Web/images/icon_package.png');
                        $("#orderHead ul li").eq(3).find('img').attr('src', '/Public/Web/images/icon_receiving_default.png');
                        $("#orderHead ul li").eq(4).find('img').attr('src', '/Public/Web/images/icon_evaluation_default.png');
                        break;
                    case 3:
                        $("#orderHead ul li").eq(0).find('img').attr('src', '/Public/Web/images/icon_whole_default.png');
                        $("#orderHead ul li").eq(1).find('img').attr('src', '/Public/Web/images/icon_payment_default.png');
                        $("#orderHead ul li").eq(2).find('img').attr('src', '/Public/Web/images/icon_package_default.png');
                        $("#orderHead ul li").eq(3).find('img').attr('src', '/Public/Web/images/icon_receiving.png');
                        $("#orderHead ul li").eq(4).find('img').attr('src', '/Public/Web/images/icon_evaluation_default.png');
                        break;
                    case 4:
                        $("#orderHead ul li").eq(0).find('img').attr('src', '/Public/Web/images/icon_whole_default.png');
                        $("#orderHead ul li").eq(1).find('img').attr('src', '/Public/Web/images/icon_payment_default.png');
                        $("#orderHead ul li").eq(2).find('img').attr('src', '/Public/Web/images/icon_package_default.png');
                        $("#orderHead ul li").eq(3).find('img').attr('src', '/Public/Web/images/icon_receiving_default.png');
                        $("#orderHead ul li").eq(4).find('img').attr('src', '/Public/Web/images/icon_evaluation.png');
                        break;
                }
            }


            $("#orderHead").on('click', '.nomal', function () {
                $(this).parent().find('.hover').removeClass('hover').addClass('nomal');
                $(this).removeClass('nomal').addClass('hover');

                if ($(this).index() == 0) {
                    //全部订单
                    location.href = "<?php echo U('Web/Order/index');?>";
                } else if ($(this).index() == 1) {
                    //待付款
                    location.href = "<?php echo U('Web/Order/index');?>?status=waitpay";
                }
                else if ($(this).index() == 2) {
                    //待包装
                    location.href = "<?php echo U('Web/Order/index');?>?status=waitpackage";
                }
                else if ($(this).index() == 3) {
                    //待收货
                    location.href = "<?php echo U('Web/Order/index');?>?status=waitconfirm";
                }
                else if ($(this).index() == 4) {
                    //待评价
                    location.href = "<?php echo U('Web/Order/index');?>?status=waitevaluate ";
                }
            })

            $("#thelist").delegate(".go_pay","click",function(){

                var orderid = $(this).parent().parent().find('.gourl').attr('id');
                //console.log($(this).parent().find('#pays').size())
                var orderid = $(this).data('orderid');
                var money = $(this).data('money');
                var discount = $(this).data('discount');
                $("#pays").attr("data-orderid",orderid);
                $("#pays").attr("data-money",money);
                $("#pays").attr("data-discount",discount);

                open($('#pays'));
                //alert(orderid);
                //location.href = "<?php echo U('Web/Order/show');?>?id=" + orderid+"&pay=trues";
            })



            $("#thelist").on('click', '.cancel', function () {
                var orderid = $(this).parent().parent().find('.gourl').attr('id');
                if (confirm('确定要取消订单吗')) {
                    $.post(
                    "<?php echo U('Web/Order/closeorder');?>", {
                        "orderid": orderid
                    },
                    function (response, status) {
                        if (status == "success") {
                            location.reload();
                        };
                    },
                    "json");
                }

            })

            $("#thelist").on('click', '.goods', function () {
                var id = $(this).parent().find('.gourl').attr('id');
                location.href = "<?php echo U('Web/Order/show');?>?id=" + id;
            })

            $(".r").click(function () {
                location.href = "<?php echo U('Web/Order/logistics');?>";
            })

        });


        var OFFSET = 5;
        var page = 1;   //页数
        var PAGESIZE = 10;//每页显示数据

        var myScroll,
            pullDownEl,
            pullDownOffset,
            pullUpEl,
            pullUpOffset,
            generatedCount = 0;

        var maxScrollY = 0;
        var hasMoreData = false;


        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, false);

        document.addEventListener('DOMContentLoaded', function () {
            $(document).ready(function () {
                loaded();
            });
        }, false);


        var template_all = '<div class="orderItem">                                                                      ';
        template_all += '    <div class="store">                                                                      ';
        template_all += '        <span class="store"></span>                                                          ';
        template_all += '        <span class="title">{storename}</span>                                                ';
        template_all += '        <span class="gourl" id={orderid} onclick="toview(this)"></span>                                                          ';
        template_all += '        <span class="state">{status}</span>                                                    ';
        template_all += '    </div>                                                                                   ';
        template_all += '    <div class="goods">             ';
        template_all += '        {itemslist}                                                              ';
        template_all += '    </div>                                                                                   ';
        template_all += '    <div class="pinfo">                                                                      ';
        template_all += '        <span class="payStyle"><label class="green">{paytype}</label></span>                                  ';
        template_all += '        <span class="numTotal" {noyustyle}>实付：<label id="tmoneys">￥{money}</label></span>                           ';
        template_all += '        <span class="numYuTotal" {yustyle}><lable>已付:￥{yifu} 还需付:￥{haixufu}</lable></span>                                              ';
        template_all += '        <span class="numGoods">共{totalnum}件商品</span>                                              ';

        template_all += '    </div>                                                                                   ';
        template_all += '    <div class="tools">                                                                      ';
        // template_all += '   <select id="pays"  style="float: left; color: #ffffff; opacity: 0.01" onchange="paychange(this)" data-orderid={thisorderid} data-money={thismoney} data-discount={thisdiscount}>                                '
        // template_all += '        <option value="0">请选择</option>         '
        // template_all += '        <option value="1">钱包支付</option>       '
        // template_all += '        <option value="2">微信支付</option>       '
        // template_all += '        <option value="3">货到付款</option>       '
        // template_all += '    </select>                                     '
        template_all += '        <buttom class="order cancel" {iscancel} >取消订单</buttom>                          ';
        template_all += '        <buttom class="order go_pay" {isgopay} data-orderid={thisorderid} data-money={thismoney} data-discount={thisdiscount}>去支付</buttom>                          ';
        template_all += '        <buttom class="order goeval" {iseval} onclick="goeval()">去评价</buttom>                          ';
        template_all += '    </div>                                                                                   ';
        template_all += '</div>                                                                                       ';

        template_smm = '        <div class="goodsItem">                                                              ';
        template_smm += '            <div class="image"><img alt="" src="{pimg}" /></div>                  ';
        template_smm += '            <div class="infos">                                                              ';
        template_smm += '                <div class="title"><img alt="" src="{typepng}" />{title}<span class="iswidth" {wditds}>{thiswidth}</span><span style="float:right;color:#f59c0c">{ttotal}</span></div> ';
        template_smm += '                <div class="marks">{guige}</div>                                              ';
        template_smm += '                <div class="price">                                                          ';
        template_smm += '                    <span class="price">￥{price}/{standard}{unit}</span>                                       ';
        template_smm += '                    <span class="count">X&nbsp;{num}</span>                                      ';
        template_smm += '                </div>                                                                       ';
        template_smm += '                <div class="iswidth" style="{widthstatus}">                                                          ';
        //template_smm += '                    <span class="iswidth">{gstatus}</span>                                       ';
        template_smm += '                    <span class="iswidth" {shijiprice}>{shijiprices}</span>                                       ';
        template_smm += '                    <span class="iswidth" {shijiwidth}>{shijiwidths}</span>                                       ';

        template_smm += '                </div>                                                                       ';
        template_smm += '            </div>                                                                           ';
        template_smm += '        </div>                                                                               ';

        //循环列
        function pageresp(response) {
            $.each(response, function (key, value) {
                var template = template_all;
                var temp_template;
                var totalnum = value.productinfo.length;
                var status = '';
                if (value.status == '0') {
                    status = '默认';
                    template = template.replace('{iseval}', "style='display:none'");
                }
                else if (value.status == '1') {
                    status = '确认成功';
                    template = template.replace('{iseval}', "style='display:none'");
                }
                else if (value.status == '2') {
                    status = '审核成功';
                    template = template.replace('{iseval}', "style='display:none'");
                    if (value.pay_status == '1') {
                        status = '支付完成';
                        template = template.replace('{iseval}', "style='display:none'");
                        template = template.replace('{iseval}', "style='display:none'");
                        template = template.replace('{isgopay}', "style='display:none'");
                        template = template.replace('{iscancel}', "style='display:none'");
                        //if(value.)
                        if (value.package_status == 0) {
                            status = '等待包装';
                            template = template.replace('{iseval}', "style='display:none'");
                            template = template.replace('{isgopay}', "style='display:none'");
                            template = template.replace('{iscancel}', "style='display:none'");

                        }
                        else if (value.package_status == 1) {
                            status = '包装中';
                            template = template.replace('{iseval}', "style='display:none'");
                            template = template.replace('{isgopay}', "style='display:none'");
                        }
                        else if (value.package_status == 2) {
                            status = '包装完成';
                            template = template.replace('{iseval}', "style='display:none'");
                            template = template.replace('{isgopay}', "style='display:none'");
                            template = template.replace('{iscancel}', "style='display:none'");
                            if (value.delivery_status == 1) {
                                status = '配送中';
                                template = template.replace('{iseval}', "style='display:none'");

                            }
                            else if (value.delivery_status == 2) {
                                status = '确认送达';
                                template = template.replace('{iseval}', "style='display:none'");
                            }
                            else if (value.delivery_status == 4) {
                                status = '交易完成';
                                template = template.replace('{iseval}', "style='display:none'");
                            }
                        }
                    }
                    else if (value.pay_status == '0') {
                        status = '等待支付';
                        if (value.wait_money != '0.00') {
                            template = template.replace('{iscancel}', "style='display:none'");
                        }
                        template = template.replace('{iseval}', "style='display:none'");
                        if (value.paystyle == '2') {

                            if (value.package_status == 0) {
                                status = '等待包装';
                                template = template.replace('{isgopay}', "style='display:none'");
                            }
                            else if (value.package_status == 1) {
                                status = '包装中';
                                template = template.replace('{isgopay}', "style='display:none'");
                            }
                            else if (value.package_status == 2) {
                                status = '包装完成';
                                template = template.replace('{isgopay}', "style='display:none'");
                                template = template.replace('{iscancel}', "style='display:none'");
                                if (value.delivery_status == 1) {
                                    status = '配送中';
                                    template = template.replace('{iseval}', "style='display:none'");
                                }
                                else if (value.delivery_status == 2) {
                                    status = '确认送达';
                                    template = template.replace('{iseval}', "style='display:none'");
                                }
                                else if (value.delivery_status == 4) {
                                    status = '交易完成';
                                    template = template.replace('{iseval}', "style='display:none'");
                                }
                            }
                        }else if(value.paystyle == '1'){
                            if(value.iscontainsweigh=='1'){

                                if (value.package_status == 0) {
                                    status = '等待包装';
                                    template = template.replace('{isgopay}', "style='display:none'");
                                }
                                else if (value.package_status == 1) {
                                    status = '包装中';
                                    //template = template.replace('{isgopay}', "style='display:none'");
                                }
                                else if (value.package_status == 2) {
                                    status = '包装完成';

                                    template = template.replace('{isgopay}', "style='display:none'");
                                    template = template.replace('{iscancel}', "style='display:none'");
                                    if (value.delivery_status == 1) {
                                        status = '配送中';
                                        template = template.replace('{iseval}', "style='display:none'");
                                    }
                                    else if (value.delivery_status == 2) {
                                        status = '确认送达';
                                        template = template.replace('{iseval}', "style='display:none'");
                                    }
                                    else if (value.delivery_status == 4) {
                                        status = '交易完成';
                                        template = template.replace('{iseval}', "style='display:none'");
                                    }
                                }
                            }

                        }
                    }
                }
                else if (value.status == '3') {
                    status = '订单取消';
                    template = template.replace('{iseval}', "style='display:none'");
                    template = template.replace('{isgopay}', "style='display:none'");
                    template = template.replace('{iscancel}', "style='display:none'");
                }
                else if (value.status == '4') {
                    status = '异常订单';
                    template = template.replace('{iseval}', "style='display:none'");
                    template = template.replace('{isgopay}', "style='display:none'");
                    template = template.replace('{iscancel}', "style='display:none'");
                }
                else if (value.status == '5') {
                    template = template.replace('{isgopay}', "style='display:none'");
                    template = template.replace('{iscancel}', "style='display:none'");
                    status = '待评价'
                }
                if (value.paystyle == '2') {
                    template = template.replace('{isgopay}', "style='display:none'");
                }
                template = template.replace('{orderid}', value.orderid);
                template = template.replace('{orderid}', value.orderid);
                template = template.replace('{thisorderid}', value.orderid);
                template = template.replace('{thisdiscount}', value.discount);
                if (value.ordertype == "2") {
                    template = template.replace('{thismoney}', value.wait_money);
                } else {
                    template = template.replace('{thismoney}', value.total-value.wallet-value.discount);
                }
                if(value.storename==null){
                    template = template.replace('{storename}', "企业专区");
                }else{
                    template = template.replace('{storename}', value.storename);
                }
                
                template = template.replace('{status}', status);
                //template = template.replace('{money}', value.total);
                template = template.replace('{yifu}', value.yes_money);
                template = template.replace('{haixufu}', value.wait_money-value.discount);

                template = template.replace('{totalnum}', totalnum);
                if(value.ordertype!=3){
                    if (value.paystyle == '2') {
                        template = template.replace('{paytype}', "货到付款");
                    } else if (value.paystyle == '3') {
                        template = template.replace('{paytype}', "钱包");
                    } else if (value.paystyle == '4') {
                        template = template.replace('{paytype}', "优惠券");
                    } else {
                        if (value.paytype == "1") {
                            template = template.replace('{paytype}', "支付宝");
                        } else {
                            template = template.replace('{paytype}', "微信支付");
                        }
                    }
                }else{
                     template = template.replace('{paytype}', "企业结算");
                }
                
                var template_items = '';
                var sss = '';
                var flags = 0;
                var prices = 0;
                $.each(value.productinfo, function (key, value) {
                    var gflag = 0;
                    //如果称重商品没有去称重的话，支付的按钮就隐藏掉 还要显示待称重等细节
                    template_items = template_smm.replace('{title}', value.title);
                    if (value.isweigh == '0' && value.product_type=='4') {
                        template_items = template_items.replace('{thiswidth}', "待称重");
                    } else if (value.isweigh == '1' && value.product_type == '4') {
                        template_items = template_items.replace('{thiswidth}', "称重完成");
                    } else {
                        template_items = template_items.replace('{wditds}', "style='display:none'");
                    }
                    
                    if (value.isweigh == '0' && value.product_type == '4') {
                        template = template.replace('{isgopay}', "style='display:none'");
                        template_items = template_items.replace('{gstatus}', "待称重");
                    } else if (value.isweigh == '1' && value.product_type == '4') {
                        template_items = template_items.replace('{gstatus}', "称重完成");
                        template = template.replace('{iscancel}', "style='display:none'");
                    }
                    
                    if (value.product_type == '1') {
                        template_items = template_items.replace('{widthstatus}', "display:none");
                        template = template.replace('{yustyle}', "style='display:none'");
                        template_items = template_items.replace('{typepng}', "/Public/Web/images/orderStyleTh.png");
                    } else if (value.product_type == '2') {
                        template_items = template_items.replace('{widthstatus}', "display:none");
                        template = template.replace('{yustyle}', "style='display:none'");
                        template_items = template_items.replace('{typepng}', "/Public/Web/images/orderStyleTg.png");
                    } else if (value.product_type == '3') {
                        template_items = template_items.replace('{widthstatus}', "display:none");
                        template = template.replace('{noyustyle}', "style='display:none'");
                        template_items = template_items.replace('{typepng}', "/Public/Web/images/orderStyleYg.png");
                    } else if (value.product_type == '4') {
                        template = template.replace('{yustyle}', "style='display:none'");
                        template_items = template_items.replace('{typepng}', "/Public/Web/images/orderStyleCz.png");
                        if (value.isweigh == '0') {
                            template_items = template_items.replace('{gstatus}', "待称重");
                            template_items = template_items.replace('{shijiprice}', "style='display:none'");
                            template_items = template_items.replace('{shijiwidth}', "style='display:none'");
                            flags = 1;
                        } else {
                            template_items = template_items.replace('{gstatus}', "称重完成");
                            template_items = template_items.replace('{shijiprices}', "实际价格￥" + (Math.round(parseFloat(value.price) * parseFloat(value.weigh) * 100) / 100));
                            template_items = template_items.replace('{shijiwidths}', "实际重量" + value.weigh + value.unit);
                            flags = 1;
                            gflag = 1;
                            prices += value.price * value.weigh;
                        }
                    } else {
                        template = template.replace('{yustyle}', "style='display:none'");
                        template_items = template_items.replace('{widthstatus}', "display:none");
                        template_items = template_items.replace('{typepng}', "/Public/Web/images/orderStyleQY.png");
                    }

                    if(value.isweights == '1' || value.yugoustatus == '1'){
                        template = template.replace('{isgopay}', "style='display:none'");
                    }
                    if (gflag == 0) {
                        prices += parseFloat(value.nowprice) * parseFloat(value.nums);
                    }
                    template_items = template_items.replace('{price}', value.nowprice);
                    template_items = template_items.replace('{standard}', value.standard);
                    template_items = template_items.replace('{unit}', value.unit);
                    template_items = template_items.replace('{ttotal}', '￥' + parseFloat(value.nowprice) * parseFloat(value.nums));

                    template_items = template_items.replace('{pimg}', value.thumb);
                    template_items = template_items.replace('{guige}', value.description);
                    template_items = template_items.replace('{num}', value.nums);
                    sss += template_items;
                });
                if (flags == 1) {
                    template = template.replace('{money}', Math.round((prices-value.discount-value.wallet) * 100) / 100);
                } else {
                    template = template.replace('{money}', value.total);
                }
                template = template.replace('{itemslist}', sss);

                $("#thelist").append(template);
            });
        }

        function loaded() {
            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;
            pullUpEl = document.getElementById('pullUp');
            pullUpOffset = pullUpEl.offsetHeight;
            hasMoreData = false;
            $("#pullUp").hide();
            pullDownEl.className = 'loading';
            pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
            page = 1;

            $.post(
                "<?php echo U('Web/Order/init');?>", {
                    "Page": page,
                    "type": thestatus
                },
                function (response, status) {
                    if (status == "success") {
                        console.log(response);
                        $("#orderList").show();
                        if(response==null){
                            $("#pullDown").hide();
                            $("#pullUp").hide();
                        }
                        if (response.length < PAGESIZE) {
                            hasMoreData = false;
                            $("#pullUp").hide();
                        } else {
                            hasMoreData = true;
                            $("#pullUp").show();
                        }

                        myScroll = new iScroll('orderList', {
                            useTransition: true,
                            topOffset: pullDownOffset,
                            onRefresh: function () {
                                if (pullDownEl.className.match('loading')) {
                                    pullDownEl.className = 'idle';
                                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
                                    this.minScrollY = -pullDownOffset;
                                }
                                if (pullUpEl.className.match('loading')) {
                                    pullUpEl.className = 'idle';
                                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉刷新...';
                                }
                            },
                            onScrollMove: function () {
                                console.log(this.y);
                                console.log(this.OFFSET);
                                if (this.y > OFFSET && !pullDownEl.className.match('flip')) {
                                    pullDownEl.className = 'flip';
                                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '信息更新中...';
                                    this.minScrollY = 0;
                                } else if (this.y < OFFSET && pullDownEl.className.match('flip')) {
                                    pullDownEl.className = 'idle';
                                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉加载更多...';
                                    this.minScrollY = -pullDownOffset;
                                }
                                if (this.y < (maxScrollY - pullUpOffset - OFFSET) && !pullUpEl.className.match('flip')) {
                                    if (hasMoreData) {
                                        this.maxScrollY = this.maxScrollY - pullUpOffset;
                                        pullUpEl.className = 'flip';
                                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '信息更新中...';
                                    }
                                } else if (this.y > (maxScrollY - pullUpOffset - OFFSET) && pullUpEl.className.match('flip')) {
                                    if (hasMoreData) {
                                        this.maxScrollY = maxScrollY;
                                        pullUpEl.className = 'idle';
                                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                                    }
                                }
                            },
                            onScrollEnd: function () {
                                if (pullDownEl.className.match('flip')) {
                                    pullDownEl.className = 'loading';
                                    pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
                                    refresh();
                                }
                                if (hasMoreData && pullUpEl.className.match('flip')) {
                                    pullUpEl.className = 'loading';
                                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
                                    nextPage();
                                }
                            }
                        });

                        $("#thelist").empty();
                        pageresp(response);
                        myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)

                        if (hasMoreData) {
                            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
                        } else {
                            myScroll.maxScrollY = myScroll.maxScrollY;
                        }
                        maxScrollY = myScroll.maxScrollY;
                    };
                },
                "json");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = '无数据...';
        }

        function refresh() {
            page = 1;
            $.post(
                    "<?php echo U('Web/Order/init');?>", {
                        "Page": page,
                        "type": thestatus
                    },
                function (response, status) {
                    if (status == "success") {
                        $("#thelist").empty();
                        myScroll.refresh();
                        if (response.length < PAGESIZE) {
                            hasMoreData = false;
                            $("#pullUp").hide();
                        } else {
                            hasMoreData = true;
                            $("#pullUp").show();
                        }
                        pageresp(response);
                        myScroll.refresh();
                        if (hasMoreData) {
                            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
                        } else {
                            myScroll.maxScrollY = myScroll.maxScrollY;
                        }
                        maxScrollY = myScroll.maxScrollY;
                    };
                },
                "json");
        }

        function nextPage() {
            page++;
            $.post(
                    "<?php echo U('Web/Order/init');?>", {
                        "Page": page,
                        "type": thestatus
                    },
                function (response, status) {
                    if (status == "success") {
                        if (response.length < PAGESIZE) {
                            hasMoreData = false;
                            $("#pullUp").hide();
                        } else {
                            hasMoreData = true;
                            $("#pullUp").show();
                        }
                        pageresp(response);
                        myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)
                        if (hasMoreData) {
                            myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
                        } else {
                            myScroll.maxScrollY = myScroll.maxScrollY;
                        }
                        maxScrollY = myScroll.maxScrollY;
                    };
                },
                "json");
        }

        function toview(obj) {
            var id = obj.id;
            location.href = "<?php echo U('Web/Order/show');?>?id=" + id;
        }

        function goeval() {
            alert('请下载APP进行评价');
        }



        function paychange(obj) {
            var data = '';
            var orderid = $("#pays").data('orderid');
            console.log(orderid)
            var money = $("#pays").data('money');
            var discount = $("#pays").data('discount');

            //alert(money);
            if ($("#pays option:selected").val() == '1') {
                $.post(
                    "<?php echo u('web/cat/orderpayagain');?>", {
                        "orderid": orderid,
                        "paystyle": "3",
                        "money": "0.0",
                        "wallet": money,
                        "discount": '0.0'
                    },
                    function (response, status) {
                        if (status == "success") {
                            if (response.code == '-200')
                            {
                                alert('您钱包的余额不够,请使用其他支付方式!');
                                location.href = '<?php echo U("Web/Order/index");?>';
                            }
                            else {
                                alert('您的钱包余额足够，正使用钱包支付!');
                                location.href = '<?php echo U("Web/Order/index");?>';
                            }
                        } else {
                            alert('您钱包的余额不够,请使用其他支付方式!');
                            location.href = '<?php echo U("Web/Order/index");?>';
                        };
                    },
                    "json");
            } else if ($("#pays option:selected").val() == '2') {
                $.post(
                    "<?php echo u('web/cat/onlinpingpp');?>", {
                        "orderid": orderid
                    },
                    function (response, status) {
                        if (status == "success") {
                            location.href = "<?php echo U('Web/Pay/index');?>";
                        };
                    },
                    "json");
            } else if ($("#pays option:selected").val() == '3') {
                $.post(
                    "<?php echo u('web/cat/orderpayagain');?>", {
                        "orderid": orderid,
                        "paystyle": "2",
                        "money": money,
                        "wallet": "0.00",
                        "discount": '0.00'
                    },
                    function (response, status) {
                        if (status == "success") {
                            location.href = '<?php echo U("Web/Order/index");?>';
                        };
                    },
                    "json");
            } else {
                return false;
            }
        }

        function open(elem) {
           if (document.createEvent) {
               var e = document.createEvent("MouseEvents");
               e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
               elem[0].dispatchEvent(e);
           } else if (element.fireEvent) {
               elem[0].fireEvent("onmousedown");
           }
        }

    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="<?php echo U('Web/Member/index');?>" target="_self"></a></div>
        <h1>我的订单</h1>
        <div class="r" style="display: none">地图模式</div>
    </div>
    <div id="orderHead" class="orderHead" style="margin-top:0px">
        <ul>
            <li class="hover">
                <div class="image">
                    <img alt="" src="/Public/Web/images/icon_whole.png" />
                </div>
                <div class="title">全部订单</div>
            </li>
            <li class="nomal">
                <div class="image">
                    <img alt="" src="/Public/Web/images/icon_payment_default.png" />
                    <?php if(($waitpay != '0') AND ($waitpay != '')): ?><span><?php echo ($waitpay); ?></span><?php endif; ?>
                </div>
                <div class="title">待付款</div>
            </li>
            <li class="nomal">
                <div class="image">
                    <img alt="" src="/Public/Web/images/icon_package_default.png" />
                    <?php if(($waitpackage != '0') AND ($waitpackage != '')): ?><span><?php echo ($waitpackage); ?></span><?php endif; ?>
                </div>
                <div class="title">待包装</div>
            </li>
            <li class="nomal">
                <div class="image">
                    <img alt="" src="/Public/Web/images/icon_receiving_default.png" />
                    <?php if(($waitconfirm != '0') AND ($waitconfirm != '')): ?><span><?php echo ($waitconfirm); ?></span><?php endif; ?>
                </div>
                <div class="title">待收货</div>
            </li>
            <li class="nomal">
                <div class="image">
                    <img alt="" src="/Public/Web/images/icon_evaluation_default.png" />
                    <?php if(($waitevaluate != '0') AND ($waitevaluate != '')): ?><span><?php echo ($waitevaluate); ?></span><?php endif; ?>
                </div>
                <div class="title">待评价</div>
            </li>
        </ul>
    </div>
    <div id="page_info" class="page_info" style="background-color: #f3f3f3;">
        <div class="orderlist" id="orderList">
            <div id="scroller">
                <div id="pullDown" class="idle">
                    <span class="pullDownIcon"></span>
                    <span class="pullDownLabel">下拉加载数据...</span>
                </div>
                <div id="thelist">
                </div>
                <div id="pullUp" class="idle">
                    <span class="pullUpIcon"></span>
                    <span class="pullUpLabel">上拉加载数据...</span>
                </div>
            </div>
        </div>
    </div>
    <select id="pays"  style="float: left; color: #ffffff; opacity: 0.01" onchange="paychange(this)" >                                '
           <option value="0">请选择</option>         '
          <option value="1">钱包支付</option>       '
            <option value="2">微信支付</option>       '
           <option value="3">货到付款</option>       '
    </select>  
</body>
</html>
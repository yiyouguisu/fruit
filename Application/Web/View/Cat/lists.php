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
            overflow: visible;
            position: absolute;
            left: 0px;
            top: 55px;
            padding-bottom:105px;
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
        <!--<div class="l"><a class="return" href="javascript:history.go(-1)" target="_self"></a></div>-->
        <h1>购物车</h1>
        <div class="r"><a class="delete" id="selectdel" href="javascript:" target="_self"></a></div>
    </div>
    <div id="page_info" class="page_info">
        <div class="cartsList">
            <volist name="storelist['subdata']" id="store">
       	  		<div class="cartsShop" >
		             <div class="select"><span class="unSelect"></span><span class="storeid"></span></div>
		             <div class="jbinfo"><img src="__IMG__/icon_shop.png" /><span style="margin-left:5px; margin-right:5px">{$store.title}</span><!--<img src="__IMG__/icon_arrow.png" width="10px" height="17px" />--></div>
		             <div class="delete"><span class="doDelete" id="{$store.storeid}" store-type="{$store.type}"></span></div>
		        </div>
		        <div class="shopitem">
			        <volist name="store['cartinfo']" id="items">
			        	<div class="cartsItem">
				             <div class="select"><span class="unSelect"></span></div>
				             <div class="jbinfo">
				                <div class="goodsImage">
				                	<img alt="" src="__ROOT__{$items.thumb}" />
				                </div>
				                <div class="goodsInfos">
							            <eq name="items[type]" value = "0"> 
				                   			<div class="title"><img alt="" src="__IMG__/orderStyleQy.png" />{$vo.title}</div>
				                   		</eq>
				                		<eq name="items[type]" value = "1"> 
				                   			<div class="title"><img alt="" src="__IMG__/orderStyleTh.png" />{$items.title}</div>
				                   		</eq>
				                   		<eq name="items[type]" value = "2"> 
				                   			<div class="title"><img alt="" src="__IMG__/orderStyleTg.png" />{$items.title}</div>
				                   		</eq>
				                   		<eq name="items[type]" value = "3"> 
				                   			<div class="title"><img alt="" src="__IMG__/orderStyleYg.png" />{$items.title}</div>
				                   		</eq>
				                   		<eq name="items[type]" value = "4"> 
				                   			<div class="title"><img alt="" src="__IMG__/orderStyleCz.png" />{$items.title}</div>
				                   		</eq>
				                   <div class="marks">{$items.description}</div>
				                   <div class="price" id="{$items.id}" goods_type="{$items.type}">
				                     	 ￥<span class="prs">{$items.nowprice}/{$items.standard}{$items.unit}</span><span style="float:right;"><span class="goodsCountCut"></span><label class="goodsCountLab" id="{$items.id}" data-storeid="{$items.storeid}">{$items.cartnum}</label><span class="goodsCountAdd"></span></span>
				                   </div>
				                </div>
				             </div>
				         </div>
			        </volist>
		        </div>
                <div style="height:20px;background-color:#f3f3f3"></div>
       	  </volist>
        </div>
    </div>
    <div class="cartsGsub">
        <!--          <div class="select"><span class="isSelect"></span><span class="mydotips" id="allcheck">全选</span></div>-->
        <div class="totals">合计：<span id="temptotal">￥0.00</span></div>
        <div class="button">
            <input id="toolSubmit" name="toolSubmit" type="button" value="结算" />
        </div>
    </div>
    <div style="height: 50px;">
    </div>
    </div>
    <include file="Common:foot" />
</body>
<script type="text/javascript">
    $("#toolSubmit").click(function () {
        var data = '';
        var totalmoney = $('#temptotal').html();
        var flag = 0;
        var yuflag = 0;
        var qyflag = 0;
        var product_type = -1;
        totalmoney = totalmoney.substring(totalmoney.length, 1);
        if (totalmoney == "0") {
            alert("还没选择任何商品");
            return false;
        }

        $(".price").each(function () {
            if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
                num = $(this).parents('.cartsItem').find('.goodsCountLab').html();
                product_id = $(this).attr('id');
                product_type = $(this).attr('goods_type');
                data += '{"num":' + num + ',"product_id":' + product_id + ',"totalmoney":' + totalmoney + '},';


                if (product_type == '1' || product_type == '2' || product_type == '4') {
                    flag = 1;
                }
                if (product_type == '0') {
                    if (flag == 1 && qyflag==0) {
                        flag = 2;
                    } else {
                        flag = 1;
                        qyflag = 1;
                    }
                }
                //console.log(product_type);

                if (product_type == '3') {
                    if (yuflag == 1) {
                        yuflag = 2;
                    }
                    if (flag == 1) {
                        flag = 2;
                    } else {
                        flag = 1;
                        yuflag = 1;
                    }
                    
                }
            }
        });
        console.log(flag);
        console.log(yuflag);
        if (yuflag == 2) {
            alert('预购商品只能一次结算一个！');
            return false;
        }
        if (flag == 2) {
            alert('每次只能结算一种订单哦！');
            return false;
        }
        if (data != '') {
            //			alert(data);
            data = data.substring(0, data.length - 1)
            data = '{"Data":[' + data + ']}';
            $.post(
            "{:U('Web/Cat/goodscache')}", "cartinfo=" + data, function (response, status) {
                if (status == "success") {
                    //console.log(response);
                    location.href = "{:U('Web/Cat/submits')}";
                };
            },
            "json");
        }
        else {
            alert('请至少结算一件商品哦！');
            //return false;
        }
    });

    /***初始化***/
    $(document).ready(function () {
        setTotal();
    });

    /***商店批量删除***/
    $('.doDelete').click(function () {
        var storeid = $(this).attr('id');
        var storetype = $(this).attr('store-type');
        if (confirm("确认删除吗")) {
            $.post(
			"{:U('Web/Cat/catdel')}", {
			    "storeid": storeid,
			    "type": storetype
			},
			function (response, status) {
			    if (status == "success") {
			        if (response > 0)
			            $('.cartSz').html(response);
			        else
			            location.href = "{:U('Web/Cat/empty')}";
			    };
			},
			"json");
            location.reload();
        }
        else {
            return;
        }
    });

    /***产品批量删除***/
    //$('#selectdel').click(function () {
    //    var product_id = 0;
    //    var storeid = 0;
    //    var data = '';
    //    $(".price").each(function () {
    //        if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
    //            storeid = $(this).parent().parent().parent().parent().prev().find('.doDelete').attr('id');
    //            product_id = $(this).attr('id');
    //            data += '{"storeid":' + storeid + ',"product_id":' + product_id + '},';
    //        }
    //    });
    //    data = data.substring(0, data.length - 1)
    //    data = '{"Data":[' + data + ']}';
    //    console.log(data);
    //    if (confirm("确认删除吗")) {
    //        $.post(
	//		"{:U('Web/Cat/catdel')}",
    //        "cartinfo=" + data,
    //        function (response, status) {
    //            console.log(response);
    //            if (status == "success") {
    //                if (response > 0)
    //                    $('.cartSz').html(response);
    //                else
    //                    location.href = "{:U('Web/Cat/empty')}";
    //            };
    //        },
	//		"json");
    //        location.reload();
    //    }
    //    else {
    //        return;
    //    }
    //});

    //清空购物车
    $('#selectdel').click(function () {
        var product_id = 0;
        var storeid = 0;
        var data = '';
        $(".price").each(function () {
                storeid = $(this).parent().parent().parent().parent().prev().find('.doDelete').attr('id');
                product_id = $(this).attr('id');
                data += '{"storeid":' + storeid + ',"product_id":' + product_id + '},';
        });
        data = data.substring(0, data.length - 1)
        data = '{"Data":[' + data + ']}';
        console.log(data);
        if (confirm("确认删除吗")) {
            $.post(
			"{:U('Web/Cat/catdel')}",
            "cartinfo=" + data,
            function (response, status) {
                console.log(response);
                if (status == "success") {
                    if (response > 0)
                        $('.cartSz').html(response);
                    else
                        location.href = "{:U('Web/Cat/empty')}";
                };
            },
			"json");
            location.reload();
        }
        else {
            return;
        }
    });


    /***全选***/
    $("#allcheck").click(function () {
        $('.cartsList').find('.unSelect').addClass("isSelect");
    });
    /***加购物车***/
    $(".goodsCountAdd").click(function () {
        var t = $(this).parent().find($('.goodsCountLab'));
        t.html(parseInt(t.html()) + 1);

        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        /***计算总额***/
        setTotal();
        $.post(
			"{:U('Web/Cat/catupdate')}", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (response == 'stock') {
			            alert('库存不足！');
			            t.html(parseInt(t.html()) - 1);
			            return false;
			        }
			        $('.cartSz').html(response);
			        if ($('.cartSz').html() == '') {
			            location.reload();
			        }
			    };
			},
			"json");
    });

    /***减购物车***/
    $(".goodsCountCut").click(function () {
        var obj=$(this);
        var t = $(this).parent().find($('.goodsCountLab'));
        t.html(parseInt(t.html()) - 1);
        if (parseInt(t.html()) <= 0) {
            obj.parents(".cartsItem").remove();
        }

        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        /***计算总额***/
        setTotal();

        $.post(
			"{:U('Web/Cat/catupdate')}", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (pnum == "0" && $('.cartSz').html() == "0") {
                        location.reload();
			        }
			        else {
			            $('.cartSz').html(response);
			            if ($('.cartSz').html() == '') {
			                location.reload();
			            }
			        }
			    };
			},
			"json");
    });


    $(".select").on("click", ".isSelect", function () {
        $(this).removeClass('isSelect').addClass("unSelect");
        if ($(this).next().attr('id') == "allcheck")
            $(".cartsList").find('.isSelect').removeClass('isSelect').addClass("unSelect");
        if ($(this).next().attr('class') == "storeid")
            $(this).parent().parent().next().find('.isSelect').removeClass('isSelect').addClass("unSelect");
        setTotal();
    });


    $(".select").on("click", ".unSelect", function () {
        $(this).removeClass('unSelect').addClass("isSelect");
        if ($(this).next().attr('id') == "allcheck")
            $(".cartsList").find('.unSelect').removeClass('unSelect').addClass("isSelect");
        if ($(this).next().attr('class') == "storeid")
            $(this).parent().parent().next().find('.unSelect').removeClass('unSelect').addClass("isSelect");
        setTotal();
    });

    function setTotal() {
        var s = 0;
        var total = 0;
        $(".price").each(function () {
            if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
                s = parseFloat($(this).find('.goodsCountLab').html()) * parseFloat($(this).find('.prs').html());
                total += parseFloat(s);
            }
        });
        $("#temptotal").html('￥' + total.toFixed(2));
    }
</script>
</html>

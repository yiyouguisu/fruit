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
    <script type="text/javascript" src="__JS__/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="__JS__/swiper.min.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }

            //轮播图
            var mySwiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                lazyLoading: true,
                autoplay:5000,
                slidesPerView: 1,
                paginationClickable: true,
                spaceBetween: 30,
                loop: true,
                onLazyImageLoad: function (swiper, slide, image) {
                }
            });
        });
    </script>
</head>
<body>
    <div id="goodsItem" class="goodsItem">
        <div class="return" id="toolReturn"></div>
        <if condition="$isatt eq 0">
       			<div class="dolike" id="toolDolike"></div>
       		<else />
       			<div class="dolikeyes" id="toolDolike"></div>
       		</if>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <volist name="backimglist" id="vo">
	                <div class="swiper-slide"><img class="swiper-lazy" data-src="__ROOT__{$vo}"></div>
	            </volist>
            </div>
            <div class="swiper-pagination" ></div>
        </div>
        <div class="cp_title">
            <div class="title">{$data.title}</div>
            <!--<div class="marks">价格：￥<span id="nowpice">{$data.nowprice}</span> </div>-->
            <eq name="data[type]" value="0">
                <div class="marks"><img src="__IMG__/goodsItemStyleQy.png" /></div>
            </eq>
            <eq name="data[type]" value="1">
                <div class="marks"><img src="__IMG__/goodsItemStyleTh.png" /></div>
            </eq>
            <eq name="data[type]" value="2">
                <div class="marks"><img src="__IMG__/goodsItemStyleTg.png" /></div>
            </eq>
            <eq name="data[type]" value="3">
                <div class="marks"><img src="__IMG__/goodsItemStyleYg.png" /></div>
            </eq>
            <eq name="data[type]" value="4">
                <div class="marks"><img src="__IMG__/goodsItemStyleCz.png" /></div>
            </eq>
        </div>
        <div class="cp_count">
            <div class="marks">
                <p>{$data.description}</p>
            </div>
            <div class="count">
                <div style="float: right; {$data.isover}">
                    <if condition="$pnum eq '0'">
                        <if condition="$data.stock eq '0'">
                            <img src="__IMG__/icon_Replenishment.png"  height="50px"/>
                        <else />
                            <span class="goodsCountCut" style="display:none"></span>
                            <label class="goodsCountLab" style="display:none">{$pnum} </label>
                            <span class="goodsCountAdd" ></span>
                        </if>
                    <else />
                        <if condition="$data.stock eq '0'">
                            <img src="__IMG__/icon_Replenishment.png"  height="50px"/>
                        <else />
                            <span class="goodsCountCut"></span>
                            <label class="goodsCountLab">{$pnum} </label>
                            <span class="goodsCountAdd"></span>
                        </if>
                    </if>
                </div>
            </div>
        </div>
        <div class="cp_price">
            <span class="priceNow">￥{$data.nowprice}</span>
            <span class="priceOld">￥{$data.oldprice}</span>
            <br />
            <eq name="data[type]" value="3">
                <if condition="$data.issellover eq '1'">
                    <span class="saletime">已过期</span>
                <else />
                    <span class="saletime">出售时间: {$data.selltime}</span>
                </if>
            </eq>
            <eq name="data[type]" value="2">
                <if condition="$data.isexpireover eq '1'">
                    <span class="saletime">已过期</span>
                <else />
                    <span class="saletime">结束时间: {$data.expiretime}</span>
                </if>
            </eq>
        </div>
        <!--<eq name="data[type]" value="1">
            <div class="cp_style th">
                <label>一般商品</label>
            </div>
        </eq>
        <eq name="data[type]" value="2">
             <div class="cp_style tg">
                <label>团购商品</label>
             </div>
        </eq>
        <eq name="data[type]" value="3">
             <div class="cp_style yg">
                 <label>预购商品</label>
             </div>
        </eq>
        <eq name="data[type]" value="4">
             <div class="cp_style cz">
                <label>称重商品</label>
             </div>
        </eq>-->
        <div class="cp_marks">
            <span class="title">品　　牌：</span><span class="marks">{$data.brand}</span>
        </div>
        <div class="cp_marks">
            <span class="title">产品规格：</span><span class="marks">{$data.standard}{$data.unit}</span>
        </div>
        <div class="cp_marks" id="todiscuss" product-id="{:I('get.id')}">
            <span class="title">商品评价：</span>
            <!--<eq name="isdiscuss" value="1"><a href="{:U('Web/Product/discuss',array('id'=>I('get.id')))}"><img src="__IMG__/icon_arrow.png" style="float:right;padding-left:10px" /></a></eq>-->
            <a href="{:U('Web/Product/discuss',array('id'=>I('get.id')))}"><img src="__IMG__/icon_arrow.png" style="float:right;padding-left:10px" /></a>
            <div class="mystar">
                <div style="font-size: 12px; color: #808080; text-align: center">{$data.percent}%果友给了</div>
                <img src="__IMG__/icon_star.png" />
                <img src="__IMG__/icon_star.png" />
                <img src="__IMG__/icon_star.png" />
                <img src="__IMG__/icon_star.png" />
                <img src="__IMG__/icon_star.png" />
            </div>
        </div>
        <div class="cp_marks">
            <span class="title">图文详情：</span>
        </div>
        <div class="cp_xqing">
            <volist name="imglist" id="bgvo">
       			<img alt="" src="__ROOT__{$bgvo}" />
       		</volist>
        </div>
    </div>
    <div id="page_foot" class="page_foot" style="border-top: 1px #d6d6d6 solid;">
        <ul>
            <li class="total">
                <label class="mtips">购物车总金额：</label><label class="price">￥{$carttotal}</label></li>
            <li class="nomal">
                <img class="cartBox" alt="" src="__IMG__/icon_dayuan.png" />
                <img class="cartImg" alt="" src="__IMG__/icon_shopping_cart.png" />
                <span class="cartSz">{$cartcount}</span>
            </li>
        </ul>
    </div>
    <script type="text/javascript">
        $("#toolReturn").bind('click', function () {
            history.go(-1);
            //location.href = document.referrer;
        });

        $("#todiscuss").click(function () {
            var pid = $(this).attr('product-id');
            location.href = "{:U('Web/Product/discuss')}?id=" + pid;
        })

        $(".nomal").click(function () {
            location.href = "{:U('Web/Cat/lists')}";
        })

        $(".goodsCountAdd").click(function () {
            var uid='{$user.id}';
            if(uid==''){
                alert("请先登录");
                window.location.href="{:U('Web/Member/login')}";
            }
            var storeid='{$data.storeid}';
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) + 1);
            t.show();
            cat.show();
            $(".cartSz").show();

            var pnum = t.html();
            var pid = "{:I('get.id')}";
            $.post(
                "{:U('Web/Cat/catupdate')}", {
                    "num": pnum,
                    "pid": pid,
                    "storeid":storeid
                },
                function (response, status) {
                    if (status == "success") {
                        console.log(response);
                        if (response == 'stock') {
                            alert('库存不足！');
                            t.html(parseInt(t.html()) - 1);
                            return false;
                        }
                        $('.cartSz').html(response);
                        if ($('.cartSz').html() == '') {
                            location.reload();
                        }
                        //location.reload();
                    };
                },
                "json");
        })

        $(".goodsCountCut").click(function () {
            var uid='{$user.id}';
            if(uid==''){
                alert("请先登录");
                window.location.href="{:U('Web/Member/login')}";
            }
            var storeid='{$data.storeid}';
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) - 1);
            if (parseInt(t.html()) == 0) {
                cat.hide();
                t.hide();
            }
            if (parseInt(t.html()) < 0) {
                t.html(0);
            }
            var pnum = t.html();
            var pid = "{:I('get.id')}";
            $.post(
                "{:U('Web/Cat/catupdate')}", {
                    "num": pnum,
                    "pid": pid,
                    "storeid":storeid
                },
                function (response, status) {
                    if (status == "success") {
                        if (pnum <= "0" && $('.cartSz').html() == "0") {
                            $(".cartSz").hide();
                        }
                        else {
                            $('.cartSz').html(response);
                            if (response == null) {
                                $(".cartSz").hide();

                            } else {
                                $('.cartSz').html(response);
                                if ($('.cartSz').html() == '') {
                                    location.reload();
                                }
                            }
                        }
                        //location.reload();
                    };
                },
                "json");
        })


        //        $("#goodsItem").on("click",".dolike",function(){
        //        	$.post("{:U('Web/Focus/add')}",
        //                    { pid: "{:I('get.id')}"},
        //             	   function(data){
        //                  	   alert(1);
        //             	   });
        //    	});

        $("#goodsItem").on("click", '.dolike', function () {
            var uid='{$uid}';
            if(uid==''){
                alert("请先登录");
                window.location.href="{:U('Web/Member/login')}";
                return false;
            }
            $(this).removeClass("dolike").addClass("dolikeyes");
            $.post("{:U('Web/Focus/add')}",
                    { pid: "{:I('get.id')}" },
                   function (data) {
                   });
        });

        $("#goodsItem").on("click", '.dolikeyes', function () {
            var uid='{$uid}';
            if(uid==''){
                alert("请先登录");
                window.location.href="{:U('Web/Member/login')}";
                return false;
            }
            $(this).removeClass("dolikeyes").addClass("dolike");
            $.post("{:U('Web/Focus/del')}",
                    { pid: "{:I('get.id')}" },
                   function (data) {
                   });
        });
        //$(".dolike").click(function () {
        //    $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //    $.post("{:U('Web/Focus/add')}",
        //       { pid: "{:I('get.id')}" },
        //	   function (data) {
        //	       //            	   $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //	       //location.reload();
        //	       //        	     	$(this).cdolikeyes
        //	   });
        //});

        //$(".dolikeyes").click(function () {
        //    $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //    $.post("{:U('Web/Focus/del')}",
        //       { pid: "{:I('get.id')}" },
        //	   function (data) {
        //	       console.log(data);
        //	       //            	   $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //	       //location.reload();
        //	       //        	     	$(this).cdolikeyes
        //	   });
        //});


    </script>
</body>
</html>

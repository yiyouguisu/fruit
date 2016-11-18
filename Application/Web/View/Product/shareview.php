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
                autoplay: 5000,
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
        </div>
        <div class="cp_price">
            <span class="priceNow">￥{$data.nowprice}/{$data.standard}{$data.unit}</span>
            <span class="priceOld">￥{$data.oldprice}/{$data.standard}{$data.unit}</span>
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
        <div class="cp_marks">
            <span class="title">品　　牌：</span><span class="marks">{$data.brand}</span>
        </div>
        <div class="cp_marks">
            <span class="title">产品规格：</span><span class="marks">{$data.standard}{$data.unit}</span>
        </div>
        <div class="cp_marks">
            <span class="title">商品评价：</span>
            <eq name="isdiscuss" value="1"><a href="{:U('Web/Product/discuss',array('id'=>$data['id']))}"><img src="__IMG__/icon_arrow.png" style="float:right;padding-left:10px" /></a></eq>
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
    <include file="Common:foot5" />
</body>
</html>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/swiper.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="__JS__/swiper.min.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=61dG5IBV8LakyGZPhDNQAAT1DY9oFjRY"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
    <style type="text/css">
        /*内容*/
        .page_info {
            width: 100%;
            height: auto;
            overflow: visible;
            position:absolute;
            top:55px;
            padding-bottom:55px;
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
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
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
            $("#place").click(function () {
                location.href = "{:U('Web/Public/modifyaddr')}";
            })
            $("#otheradd").click(function () {
                location.href = "{:U('Web/Public/modifyaddr')}";
            });

            //      });
            if (supportsGeoLocation()) {
                //              alert("你的浏览器支持 GeoLocation.");
            } else {
                alert("不支持 GeoLocation.")
            }
            function supportsGeoLocation() {
                return !!navigator.geolocation;
            }
            function getLocation() {
                navigator.geolocation.getCurrentPosition(mapIt, locationError);
            }
            function mapIt(position) {
                console.log(position)
                var lon = position.coords.longitude;
                var lat = position.coords.latitude;
                    var storeid='{$storeid}';
                    if(storeid==''){
                        $.post(
                         "{:U('Web/Index/getstore')}", {
                             "lat": lat,
                             "lng": lon,
                         },
                         function (response, status) {
                             if (status == "success") {

                                 $("#place span").html(response.storename);

                                 if (response.storeid == '') {
                                     $("#place").html('选择店铺');
                                 } else {
                                    var id="{$_GET['id']}";
                                     if (id == "") {
                                         location.href = "{:U('Web/Index/index')}?id=" + response.storeid;
                                     } else {
                                         $(".indexNoShop span").html('亲，当前店铺卖空了哟~请选择其他店铺吧！');
                                     }
                                 }

                                 $(".indexNoShop span").html('亲，当前店铺卖空了哟~请选择其他店铺吧！');
                             };
                         },
                         "json");
                    }
                    
                //});
            }
            function locationError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }
            // 页面加载时执行getLocation函数
            window.onload = getLocation;
        });
        function bindcompany() {
            if (confirm('您还没有绑定企业帐号,确定要去绑定吗')) {
                location.href = '{:U("Web/Company/bind")}';
            }
        }

        function changeplace() {
            location.href = "{:U('Web/Public/modifyaddr')}";
        }

        function chosestore() {
            if (confirm('亲，当前没有商家呦～请选择其他店铺吧！')) {
                location.href = "{:U('Web/Public/modifyaddr')}";
            } else {
                return false;
            }
        }
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="mylogo" href="javascript:" target="_self">
                <img alt="" src="__IMG__/icon_logo.png" /></a>
        </div>
        <h1 id="place"><span>{$place}</span><a class="ondown" id="otheradd"></a></h1>
        <if condition="$place eq '选择店铺'">
            <div class="r" style="top: 6px">
                <a class="search" onclick="chosestore()" target="_self">
                    <img alt="" src="__IMG__/icon_search.png" />
                </a>
            </div>
        <else />
            <div class="r" style="top: 6px">
                <a class="search" href="{:U('Web/Query/index')}" target="_self">
                    <img alt="" src="__IMG__/icon_search.png" />
                </a>
            </div>
        </if>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff;">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <volist name="adimglist" id="vo">
                <eq name="vo[type]" value="1">
                    <div class="swiper-slide"><div class="flash-text"><span>{$vo.title}</span></div><a href="{$vo.url}"><img class="swiper-lazy" data-src="__ROOT__{$vo.image}" ></a></div>
                </eq>
                <eq name="vo[type]" value="3">
                    <div class="swiper-slide"><div class="flash-text"><span>{$vo.title}</span></div><a href="{:U('Web/Index/view',array('id'=>$vo['id']))}"><img class="swiper-lazy" data-src="__ROOT__{$vo.image}" ></a></div>
                </eq>
                <eq name="vo[type]" value="2">
                    <div class="swiper-slide"><div class="flash-text"><span>{$vo.title}</span></div><a href="{:U('Web/Product/infoview',array('id'=>$vo['pid']))}"><img class="swiper-lazy" data-src="__ROOT__{$vo.image}" ></a></div>
                </eq>
                 <!--<div class="swiper-slide"><img class="swiper-lazy" data-src="__ROOT__{$vo.image}"></div>-->
            </volist>
            </div>
            <div class="swiper-pagination" style="text-align: right"></div>
        </div>
        <div class="indexMenus">
            <if condition="$place eq '选择店铺'">
                <ul>
                 <li onclick="chosestore()"><img alt="" src="__IMG__/icon_group_buy.png" /><p>团购</p></li>
                 <li onclick="chosestore()"><img alt="" src="__IMG__/icon_pre_order.png" /><p>预购</p></li>
                    <li onclick="chosestore()"><img alt="" src="__IMG__/icon_prefecture.png" /><p>企业专区</p></li>
                 <li onclick="chosestore()"><img alt="" src="__IMG__/icon_inquire.png" /><p>物流查询</p></li>
              </ul>
            <else />
                  <ul>
                 <li onclick="window.location.href='{:U('Web/Product/group')}';  "><img alt="" src="__IMG__/icon_group_buy.png" /><p>团购</p></li>
                 <li onclick="window.location.href='{:U('Web/Product/reserve')}';"><img alt="" src="__IMG__/icon_pre_order.png" /><p>预购</p></li>
                  <if condition="$userinfo['companyid'] eq 0 ">
                    <li onclick="bindcompany()"><img alt="" src="__IMG__/icon_prefecture.png" /><p>企业专区</p></li>
                  <else />
                    <li onclick="window.location.href='{:U('Web/Product/company')}';"><img alt="" src="__IMG__/icon_prefecture.png" /><p>企业专区</p></li>
                  </if>
                 <li onclick="window.location.href='{:U('Web/Order/logistics')}'; "><img alt="" src="__IMG__/icon_inquire.png" /><p>物流查询</p></li>
              </ul>
           </if>
        </div>
        <div style="height: 20px; background-color: #f3f3f3">
            <eq name="indeximglist" value="">
               <div class="indexNoShop">
                    <img src="__IMG__/icon_Stores.png" />
                   <span>亲，当前没有商家呦～请选择其他店铺吧！</span>
                   <button onclick="changeplace()">换店铺</button>
               </div>
           </eq>
        </div>
        <div class="indexTjian">
            <volist name="indeximglist" id="vo1">
              <img style="display: block;width: 100%; margin-bottom:0px;    margin-top: 0px;" alt="" src="__ROOT__{$vo1.extrathumb}" onclick="window.location.href='{:U('Web/Product/infoview',array('id'=>$vo1['id']))}'"/>
          </volist>
        </div>
    </div>
    <include file="Common:foot" />
</body>
</html>

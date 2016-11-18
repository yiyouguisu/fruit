<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/list.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/weixin.jquery.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript" src="__JS__/jquery.min.js"></script>
    <script type="text/javascript" src="__JS__/iscroll.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }
            $("#place").click(function () {
                location.href = "{:U('Web/Public/modifyaddr')}";
            })
            $("#otheradd").click(function () {
                location.href = "{:U('Web/Public/modifyaddr')}";
            });

            $("#classList ul li").eq(0).click();
            $("#classHead h2").html($("#classList ul").find('.hover').find('a').html());

            $("#search").click(function () {
                location.href = "{:U('Web/Query/index')}";
            })
            //if("{:I('get.catid')}" == ''){
            //    location.href = "{:U('Web/Product/lists')}?catid="+{$firstcid};
            //}
        });
        wxglobal.SetSize(function () {
            $("#classList").height($(window).height() - 109 + 'px');
            $("#classHead").width($(window).width() - 91 + 'px');
            $("#goodsList").width($(window).width() - 91 + 'px');
        });
    </script>
    <script type="text/javascript">

        var OFFSET = 5;
        var page = 1;	//页数
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

        var template_all = '<div class="item">';
        template_all += '<div class="image"><a href="{productview_img}" target="_self"><img alt="" src="__IMAGES__{thumb}" /></a></div>';
        template_all += '<div class="infos">';
        template_all += '   ';
        template_all += '   <a href="{productview_title}" target="_self"><div class="cp_title">{iconimg}{title}</div>';
        template_all += '   <div class="cp_marks">';
        template_all += '      {description}';
        template_all += '   </div>';
        template_all += '   <div class="cp_price">';
        template_all += '      <span class="priceNow">￥{nowprice}/{standard}{unit}</span>';
        template_all += '      <span class="priceOld">￥{oldprice}/{standard}{unit}</span>';
        template_all += '   </div></a>';
        template_all += '   <div class="cp_count" {isread}>';
        template_all += '        <span style="float:right; margin-right:10px;"><span class="goodsCountCut" {isCutdis}></span><label class="goodsCountLab" id="{goods_id}"  data-storeid="{storeid}" {isLabdis}>{cartnum}</label><span class="goodsCountAdd" "></span></span>';
        template_all += '   </div>';
        template_all += '   <div class="cp_count" {noread}>';
        template_all += '        <span style="float:right; margin-right:10px;">已过期</span>';
        template_all += '   </div>';
        template_all += '   <div class="cp_count" {noreads}>';
        template_all += '        <span style="float:right; margin-right:10px; color:red">正在补货中...</span>';
        template_all += '   </div>';

        template_all += ' </div>';
        template_all += '</div>';

        function pageresp(response) {
            $.each(response, function (key, value) {
                var template = template_all;
                template = template.replace('{thumb}', value.thumb);
                template = template.replace('{title}', value.title);
                template = template.replace('{description}', value.description);
                template = template.replace('{nowprice}', value.nowprice);
                template = template.replace('{standard}', value.standard);
                template = template.replace('{standard}', value.standard);
                template = template.replace('{unit}', value.unit);
                template = template.replace('{unit}', value.unit);
                template = template.replace('{oldprice}', value.oldprice);
                template = template.replace('{goods_id}', value.id);
                if (value.isover == '1') {
                    template = template.replace('{isread}', "style='display:none'");
                }
                else {
                    template = template.replace('{noread}', "style='display:none'");
                }
                if (value.stock == '0' && value.isover != '1') {
                    template = template.replace('{isread}', "style='display:none'");
                }
                else {
                    template = template.replace('{noreads}', "style='display:none'");
                }

                if (value.catnum <=0) {
                    template = template.replace('{isCutdis}', "style='display:none'");
                    template = template.replace('{isLabdis}', "style='display:none'");
                }
                if (value.type == "4") {
                    template = template.replace('{iconimg}', '<img src="__IMG__/orderStyleCz.png">');
                } else if (value.ishot == "1") {
                    template = template.replace('{iconimg}', '<img src="__IMG__/orderStyleTh.png">');
                } else {
                    template = template.replace('{iconimg}', '');
                }

                template = template.replace('{cartnum}', value.catnum);
                template = template.replace('{productview_img}', "{$goods_info_img}");
                template = template.replace('{productview_title}', "{$goods_info_title}");
                template = template.replace('%7Bgoods_id%7D', value.id);
                template = template.replace('%7Bgoods_id%7D', value.id);
                template = template.replace('{storeid}', value.storeid);
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
				"{:U('Web/Product/infolist')}", {
				    "Page": page,
				    "catid": "{$catid}",
				},
				function (response, status) {
				    if (status == "success") {
				        console.log(response);
				        $("#goodsList").show();
                        if(response==null){
                            $("#pullDown").hide();
                            $("#pullUp").hide();
                        }
				        if (response.length < PAGESIZE ) {
				            hasMoreData = false;
				            $("#pullUp").hide();
				        } else {
				            hasMoreData = true;
				            $("#pullUp").show();
				        }

				        myScroll = new iScroll('goodsList', {

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
				"{:U('Web/Product/infolist')}", {
				    "Page": page,
				    "catid": "{$catid}",
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
				"{:U('Web/Product/infolist')}", {
				    "Page": page,
				    "catid": "{$catid}",
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
    </script>
</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="mylogo" href="javascript:" target="_self">
                <img alt="" src="__IMG__/icon_logo.png" /></a>
        </div>
        <h1 id="place">{$place}<a class="ondown" id="otheradd"></a></h1>
        <div class="r">
            <img alt="" src="__IMG__/icon_search.png" id="search" />
        </div>
    </div>
    <div class="classList" id="classList">
        <ul>
            <volist name="data" id="vo">
                <if condition="$vo.id eq $catid ">
                    <li class="hover"><a href="{:U('Web/Product/lists',array('catid'=>$vo['id']))}" target="_self">{$vo.catname}</a></li>
                <else />
                    <li class="nomal"><a href="{:U('Web/Product/lists',array('catid'=>$vo['id']))}" target="_self">{$vo.catname}</a></li>
                </if>
        		<!--<li class="hover"><a href="{:U('Web/Product/lists',array('catid'=>$vo['id']))}" target="_self">{$vo.catname}</a></li>-->
        	</volist>
        </ul>
    </div>
    <div class="classHead" id="classHead">
        <h2>优选水果</h2>
    </div>
    <div id="page_info" class="page_info" style="background-color: #ffffff">
        <div class="goodsList" id="goodsList">
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
    <include file="Common:foot2" />
</body>
<script type="text/javascript">
    $("#thelist").on("click", ".goodsCountAdd", function () {
        var uid='{$uid}';
        if(uid==''){
            alert("请先登录");
            window.location.href="{:U('Web/Member/login')}";
            return false;
        }
        var t = $(this).parent().find($('.goodsCountLab'));
        var cat = $(this).parent().find($('.goodsCountCut'));
        var catnum = $(".cartSz");
        t.html(parseInt(t.html()) + 1);
        t.show();
        cat.show();
        catnum.show();
        catnum.html(parseInt(catnum.html()) + 1);
        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        console.log(pnum);
        $.post(
			"{:U('Web/Cat/catupdate')}", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        //console.log(response);
			        if (response == 'stock')
			        {
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

    $("#thelist").on("click", ".goodsCountCut", function () {
        var t = $(this).parent().find($('.goodsCountLab'));
        var cat = $(this).parent().find($('.goodsCountCut'));
        t.html(parseInt(t.html()) - 1);
        if (parseInt(t.html()) == 0) {
            cat.hide();
            t.hide();
            //$(".cartSz").hide();
        }
        if (parseInt(t.html()) < 0) {
            t.html(0);
        }
        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        $.post(
			"{:U('Web/Cat/catupdate')}", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (pnum == "0") {
			            cat.hide();
			            t.hide();
			        }
			        if ($('.cartSz').html() == "0") {
			            $(".cartSz").hide();
			        }
			        else {
			            console.log(response);
			            if (response == null) {
			                $('.cartSz').html(0);
			                $(".cartSz").hide();
			            } else {
			                $('.cartSz').html(response);
			                if ($('.cartSz').html() == '') {
			                    location.reload();
			                }
			            }
			        }
			    };
			},
			"json");
    });
</script>
</html>

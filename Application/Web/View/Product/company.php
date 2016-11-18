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
        })

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

		document.addEventListener('touchmove', function(e) {
			e.preventDefault();
		}, false);

		document.addEventListener('DOMContentLoaded', function() {
			$(document).ready(function() {
				loaded();
			});
		}, false);
		var template_all  ='	<div class="companyGoodsItem">                                                                                                                                ';
		template_all  +='        <div class="infos">                                                                                                                                      ';
		template_all += '            <a href="{productview_title}" target="_self"><div class="title">{title}</div>                                                                ';
		template_all += '            <div class="marks">{description}</div>                                                                                                          ';
		template_all  +='            <div class="price">                                                                                                                                  ';
		template_all += '                <span class="priceNow">￥{nowprice}/{standard}{unit}</span>                                                                                                         ';
		template_all += '                <span class="priceOld">￥{oldprice}/{standard}{unit}</span>                                                                                                         ';
		template_all += '            </div>  </a>                                                                                                                                             ';
		template_all += '            <div class="count" {isread}>                                                                                                                                  ';
		template_all += '               <span style="float:left;"><span class="goodsCountCut" {isCutdis}></span><label class="goodsCountLab"  id="{goods_id}" {isLabdis}>{cartnum}</label><span class="goodsCountAdd"></span></span>    ';
		template_all  +='            </div>                                                                                                                                               ';
		template_all += '            <div class="count" {noreads}>                                                                                                                                  ';
		template_all += '               <span style="float:left;  color:red">正在补货中...</span>';
		template_all += '            </div>                                                                                                                                               ';

		template_all += '        </div>                                                                                                                                                   ';
		template_all  +='        <div class="image">                                                                                                                                      ';
		template_all += '            <a href="{productview_img}" target="_self"><img alt="" src="__ROOT__{thumb}" /></a>                                                                  ';
		template_all  +='        </div>                                                                                                                                                   ';
		template_all += '   </div>';


		function replaceData(response) {
		    $.each(response, function (key, value) {
		        var template = template_all;
		        template = template.replace('{thumb}', value.thumb);
		        template = template.replace('{title}', value.title);
		        template = template.replace('{standard}', value.standard);
		        template = template.replace('{standard}', value.standard);
		        template = template.replace('{unit}', value.unit);
		        template = template.replace('{unit}', value.unit);

		        template = template.replace('{description}', value.description);
		        template = template.replace('{nowprice}', value.nowprice);
		        template = template.replace('{oldprice}', value.oldprice);
		        template = template.replace('{goods_id}', value.id);
		        template = template.replace('{pnum}', value.paynum);
		        template = template.replace('{cartnum}', value.catnum);
		        if (value.catnum <= 0) {
		            template = template.replace('{isCutdis}', "style='display:none'");
		            template = template.replace('{isLabdis}', "style='display:none'");
		        }
		        if (value.stock == '0' ) {
		            template = template.replace('{isread}', "style='display:none'");
		        }
		        else {
		            template = template.replace('{noreads}', "style='display:none'");
		        }
		        template = template.replace('{slodtime}', value.slodtime);
		        template = template.replace('{productview_img}', "{$goods_info_img}");
		        template = template.replace('{productview_title}', "{$goods_info_title}");
		        template = template.replace('%7Bgoods_id%7D', value.id);
		        template = template.replace('%7Bgoods_id%7D', value.id);
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
				"{:U('Web/Product/companylist')}", {
					"Page": page
				},
				function(response, status) {
					if (status == "success") {
						console.log(response);
						$("#groupList").show();
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
						
						myScroll = new iScroll('groupList', {
							useTransition: true,
							topOffset: pullDownOffset,
							onRefresh: function() {
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
							onScrollMove: function() {
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
							onScrollEnd: function() {
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
						replaceData(response);
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
				"{:U('Web/Product/companylist')}", {
					"Page": page
				},
				function(response, status) {
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
						replaceData(response);
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
				"{:U('Web/Product/companylist')}", {
					"Page": page
				},
				function(response, status) {
					if (status == "success") {
						if (response.length < PAGESIZE) {
							hasMoreData = false;
							$("#pullUp").hide();
						} else {
							hasMoreData = true;
							$("#pullUp").show();
						}
						replaceData(response);
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
       <div class="l"><a id="toolReturn" class="return" href="{:U('Web/Index/index')}" target="_self"></a></div>
       <h1>企业专区</h1>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff">
       <div class="orderlist" id="groupList">
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
    <include file="Common:foot4" />
    <script type="text/javascript">
        $("#toolReturn").bind('click', function () {
            history.go(-1);
        });
        $("#thelist").on("click",".goodsCountAdd",function(){
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
    		t.html(parseInt(t.html())+1);
    		t.show();
    		cat.show();
    		$('.cartSz').show();

    		var pnum = t.html();
    		var pid  = t.attr("id");
    		$.post(
    			"{:U('Web/Cat/catupdate')}", { 
    				"num": pnum,
    				"pid": pid,
    			},
    			function(response, status) {
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
    				};
    			},
    			"json");
    	});

    	$("#thelist").on("click",".goodsCountCut",function(){
    	    var t = $(this).parent().find($('.goodsCountLab'));
    	    var cat = $(this).parent().find($('.goodsCountCut'));
    	    t.html(parseInt(t.html()) - 1);
    	    if (parseInt(t.html()) == 0) {
    	        cat.hide();
    	        t.hide();
    	    }
    		if(parseInt(t.html())<0){
    			t.html(0);
    		}
    		var pnum = t.html();
    		var pid  = t.attr("id");
    		$.post(
    			"{:U('Web/Cat/catupdate')}", { 
    				"num": pnum,
    				"pid": pid,
    			},
    			function(response, status) {
    			    if (status == "success") {
    			        if (pnum == "0") {
    			            cat.hide();
    			            t.hide();
    			        }
    				    if (pnum == "0" && $('.cartSz').html() == "0") {
    				        $('.cartSz').hide();
    				    }
    				    else {
    				        console.log(response);
    				        if (response == null) {
    				            $('.cartSz').hide();
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
</body>
</html>
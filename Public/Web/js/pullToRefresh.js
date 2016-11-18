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
				},
				function(response, status) {
					if (status == "success") {
						$("#goodsList").show();
						if (response.length < PAGESIZE) {
							hasMoreData = false;
							$("#pullUp").hide();
						} else {
							hasMoreData = true;
							$("#pullUp").show();
						}

						myScroll = new iScroll('goodsList', {
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
						$.each(response, function(key, value) {
							var template = '<div class="item">';
							template +='<div class="image"><a href="productItem.html" target="_self"><img alt="" src="__IMAGES__{thumb}" /></a></div>';
							template +='<div class="infos">';
							template +='   <div class="cp_title"><a href="productItem.html" target="_self">{title}</a></div>';
							template +='   <div class="cp_marks">';
							template +='      {description}';
							template +='   </div>';
							template +='   <div class="cp_price">';
							template +='      <span class="priceNow">￥{nowprice}</span>';
							template +='      <span class="priceOld">￥{oldprice}</span>';
							template +='   </div>';
							template +='   <div class="cp_count">';
							template +='        <span style="float:right; margin-right:10px;"><span class="goodsCountCut"></span><label class="goodsCountLab">1</label><span class="goodsCountAdd"></span></span>';
							template +='   </div>';
							template +=' </div>';
							template +='</div>';
							template = template.replace('{thumb}',value.thumb);
							template = template.replace('{title}',value.title);
							template = template.replace('{description}',value.description);
							template = template.replace('{nowprice}',value.nowprice);
							template = template.replace('{oldprice}',value.oldprice);
							$("#thelist").append(template);
						});
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

		
		function refresh() {
			page = 1;
			$.post(
				"{:U('Web/Product/infolist')}", { "page": page },
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

						$.each(response, function(key, value) {
							var template = '<div class="item">';
							template +='<div class="image"><a href="productItem.html" target="_self"><img alt="" src="__IMAGES__{thumb}" /></a></div>';
							template +='<div class="infos">';
							template +='   <div class="cp_title"><a href="productItem.html" target="_self">{title}</a></div>';
							template +='   <div class="cp_marks">';
							template +='      {description}';
							template +='   </div>';
							template +='   <div class="cp_price">';
							template +='      <span class="priceNow">￥{nowprice}</span>';
							template +='      <span class="priceOld">￥{oldprice}</span>';
							template +='   </div>';
							template +='   <div class="cp_count">';
							template +='        <span style="float:right; margin-right:10px;"><span class="goodsCountCut"></span><label class="goodsCountLab">1</label><span class="goodsCountAdd"></span></span>';
							template +='   </div>';
							template +=' </div>';
							template +='</div>';
							template = template.replace('{thumb}',value.thumb);
							template = template.replace('{title}',value.title);
							template = template.replace('{description}',value.description);
							template = template.replace('{nowprice}',value.nowprice);
							template = template.replace('{oldprice}',value.oldprice);
							$("#thelist").append(template);
						});
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
				"{:U('Web/Product/infolist')}", { "page": page },
				function(response, status) {
					if (status == "success") {
						if (response.length < PAGESIZE) {
							hasMoreData = false;
							$("#pullUp").hide();
						} else {
							hasMoreData = true;
							$("#pullUp").show();
						}
						
						$.each(response, function(key, value) {
							var template = '<div class="item">';
							template +='<div class="image"><a href="productItem.html" target="_self"><img alt="" src="__IMAGES__{thumb}" /></a></div>';
							template +='<div class="infos">';
							template +='   <div class="cp_title"><a href="productItem.html" target="_self">{title}</a></div>';
							template +='   <div class="cp_marks">';
							template +='      {description}';
							template +='   </div>';
							template +='   <div class="cp_price">';
							template +='      <span class="priceNow">￥{nowprice}</span>';
							template +='      <span class="priceOld">￥{oldprice}</span>';
							template +='   </div>';
							template +='   <div class="cp_count">';
							template +='        <span style="float:right; margin-right:10px;"><span class="goodsCountCut"></span><label class="goodsCountLab">1</label><span class="goodsCountAdd"></span></span>';
							template +='   </div>';
							template +=' </div>';
							template +='</div>';
							template = template.replace('{thumb}',value.thumb);
							template = template.replace('{title}',value.title);
							template = template.replace('{description}',value.description);
							template = template.replace('{nowprice}',value.nowprice);
							template = template.replace('{oldprice}',value.oldprice);
							$("#thelist").append(template);
						});
						
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
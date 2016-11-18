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
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript" src="__JS__/jquery.min.js"></script>
    <script type="text/javascript" src="__JS__/iscroll.js"></script>
    <script type="text/javascript">
        var OFFSET = 5;
        var page = 1;	//页数
        var PAGESIZE = 10;//每页显示数据
        var evaluatetype=1;
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
 $(document).ready(function () {
                loaded(1);
            });
        // document.addEventListener('DOMContentLoaded', function () {
           
        // }, false);

        var template_all = ' <div class="discussItem">                                            ';
        template_all += '         <img src="{headimg}" />                            ';
        template_all += '         <div class="title">                                          ';
        template_all += '             <div class="bt">{name}<span>{startdate}</span></div>          ';
        template_all += '             <div class="star">                                       ';
        template_all += '             {starlist}                                                   ';
        template_all += '             </div>                                                   ';
        template_all += '             <div class="sj">{discuss}</div>  ';
        template_all += '         </div>                                                       ';
        template_all += '         <div class="img">                                            ';
        template_all += '         {imglist}                                                       ';
        template_all += '         </div>                                                       ';
        template_all += '     </div>                                                           ';

        var template_smm = '<a href="{images1}"><img src="{images}"/></a>';

        var template_star = '<img src="__IMG__/icon_star.png" />';
        var template_grey_star = '<img src="__IMG__/icon_Grey_stars.png" style="width:20px" />'
        //循环列
        function pageresp(response) {
            $.each(response, function (key, value) {
                var template = template_all;
                var temstar = template_star;
                //console.log(response);
                template = template.replace('{name}', value.username);
                template = template.replace('{startdate}', value.inputtime);
                template = template.replace('{discuss}', value.content);
                template = template.replace('{headimg}', value.head);
                //星星按照数量来循环
                var j = 5;
                for (var i = 1; i < value.total; i++) {
                    temstar += template_star;
                    j--;
                }
                for (var n = 1; n < j; n++) {
                    temstar += template_grey_star;
                }
                
                template = template.replace('{starlist}', temstar);
                if(value.thumb!=''){
                    var template_items = '';
                    var sss = '';
                    $.each(value.thumb, function (key, value) {
                        //console.log(value);
                        template_items = template_smm.replace('{images}', value);
                        template_items = template_items.replace('{images1}', value);
                        sss += template_items;
                    });
                    template = template.replace('{imglist}', sss);
                }else{
                    template = template.replace('{imglist}', '');
                }
                

                $("#thelist").append(template);
            });
        }

        function loaded(type) {
            evaluatetype=type;
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
                "{:U('Web/Product/get_product_evaluatelist')}", {
                    "Page": page,
                    'pid':'{:I("get.id")}',
                    'type':type
                },
                function (response, status) {
                    if (status == "success") {
                        //console.log(status);
                        $("#discussList").show();
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
                        myScroll = new iScroll('discussList', {
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
                                // console.log(this.y);
                                // console.log(this.OFFSET);
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
                                    refresh(evaluatetype);
                                }
                                if (hasMoreData && pullUpEl.className.match('flip')) {
                                    pullUpEl.className = 'loading';
                                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
                                    nextPage(evaluatetype);
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
                        
                    }
                },
                "json");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = '无数据...';
        }

        function refresh(type) {
            page = 1;
            $.post(
                    "{:U('Web/Product/get_product_evaluatelist')}", {
                        "Page": page,
                        'pid': '{:I("get.id")}',
                        'type':type
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

        function nextPage(type) {
            page++;
            $.post(
                    "{:U('Web/Product/get_product_evaluatelist')}", {
                        "Page": page,
                        'pid': '{:I("get.id")}',
                        'type':type
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
    <div id="page_info" class="page_info">
        <div class="discussHead">
            <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
            <h1>评论</h1>
            <!--<img alt="" src="__IMG__/pointHeadBg.png" class="backImg" />-->
            <div class="diswidth" onclick="loaded(1);">
                <h2>不错哟</h2>
                <span style="height: {$hign}%"></span>
                <h1>{$hign}%</h1>
            </div>
            <div class="diswidth" onclick="loaded(2);">
                <h2>一般</h2>
                <span style="height: {$middle}%"></span>
                <h1>{$middle}%</h1>
            </div>
            <div class="diswidth" onclick="loaded(3);">
                <h2>小失落</h2>
                <span style="height: {$low}%"></span>
                <h1>{$low}%</h1>
            </div>
        </div>
        <div class="discussList" id="discussList">
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
</body>
</html>

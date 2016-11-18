<!DOCTYPE>
<html>
<head>
    <meta charset="UTF-8" />
    <title>蔬果先生后台管理系统</title>
    <meta name="author" content="上海裕崇设计有限公司" />
    <link href="__ROOT__/favicon.ico" rel="SHORTCUT ICON">
    <link href="__CSS__/admin_layout.css?v=" rel="stylesheet" />
    <link href="__JS__/artDialog/skins/default.css" rel="stylesheet" />
    <script src="__JS__/jquery.js"></script>
    <script src="__JS__/layer/layer.js"></script>
    <link rel="stylesheet" href="__JS__/layer/skin/layer.css" id="layui_layer_skinlayercss">
    <style>
        /*.fullScreen .content th {*/
            display: none;
            width: 0;
        }

        .fullScreen .head, .fullScreen .tab {
            height: 0;
            display: none;
        }

        .fullScreen #default {
            *left: 0;
            *top: -90px;
        }

        .fullScreen div.options {
            top: 0;
        }
    </style>
    <script type="text/javascript">
        if (window.top !== window.self) {
            document.write = '';
            window.top.location.href = window.self.location.href;
            setTimeout(function () {
                document.body.innerHTML = '';
            }, 0);
        }
        setInterval("document.getElementById('nowTime').innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());", 1000);
    </script>

</head>
<body>
    <div class="wrap">
        <noscript>
            <h1 class="noscript">您已禁用脚本，这样会导致页面不可用，请启用脚本后刷新页面</h1>
        </noscript>
        <table width="100%" height="100%" style="table-layout: fixed;">
            <tr class="head">
                <th>
                    <a href="{:U('Admin/Index/index')}" class="logo">管理中心</a></th>
                <td>
                    <div class="nav">
                        <!-- 菜单异步获取，采用json格式，由js处理菜单展示结构 -->
                        <ul id="J_B_main_block">
                        </ul>
                    </div>
                    <div class="login_info">
                        <a id="nowTime"></a><span class="mr10">{$User.role_name}： {$User.username}</span>
                        <a href="{:U('Admin/Public/loginout')}" class="mr10">[注销]</a>
                        <!-- <a href="{:U('Home/Index/index')}" class="home" target="_blank">前台首页</a> -->
                        <!--                            <a href="{:U('Admin/Member/index')}" class="home" target="_blank">会员中心</a>-->
                        <a href="javascript:;" onclick="omnipotent('selectid','{:U('Admin/Index/public_cache')}','清除缓存',1,700,420)" class="home" style="color: #fff">缓存更新</a>
                    </div>
                </td>
            </tr>
            <tr class="tab">
                <th>
                    <div class="search">
                        <input size="15" placeholder="Hi！" id="J_search_keyword" type="text">
                        <button type="button" name="keyword" id="J_search" value="" data-url="{:U('Admin/index/public_find')}">菜单搜索</button>
                    </div>
                </th>
                <td>
                    <div id="B_tabA" class="tabA">
                        <a href="" tabindex="-1" class="tabA_pre" id="J_prev" title="上一个">上一个</a>
                        <a href="" tabindex="-1" class="tabA_next" id="J_next" title="下一个">下一个</a>
                        <div style="margin: 0 25px; min-height: 1px;">
                            <div style="position: relative; height: 30px; width: 100%; overflow: hidden;">
                                <ul id="B_history" style="white-space: nowrap; position: absolute; left: 0; top: 0;">
                                    <li class="current" data-id="default" tabindex="0">
                                        <span>
                                            <a>后台首页</a>
                                        <a class="del" title="关闭此页">关闭</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="content">
                <th style="overflow: hidden; border-right: 1px solid #CCC;">
                    <div id="B_menunav">
                        <div class="menubar">
                            <dl id="B_menubar">
                                <dt class="disabled"></dt>
                            </dl>
                        </div>
                        <div id="menu_next" class="menuNext" style="display: none;">
                            <a href="" class="pre" title="顶部超出，点击向下滚动">向下滚动</a>
                            <a href="" class="next" title="高度超出，点击向上滚动">向上滚动</a>
                        </div>
                    </div>
                </th>
                <td id="B_frame" style="height: 100%;">
                    <div class="options">
                        <a href="" class="refresh" id="J_refresh" title="刷新">刷新</a>
                        <a href="" id="J_fullScreen" class="full_screen" title="全屏">全屏</a>
                    </div>
                    <div class="loading" id="loading">加载中...</div>
                    <iframe id="iframe_default" src="{:U('Admin/Index/main')}" style="height: 100%; width: 100%; display: none;" data-id="default" frameborder="0" scrolling="auto"></iframe>
                </td>
            </tr>
        </table>
    </div>
    <include file="Common:Js" />
    <script src="__JS__/common.js"></script>
    <script src="__JS__/content_addtop.js"></script>
    <script type="text/javascript">
        var isopen=0;
        var role='{$User.role}';
        if(role==2){
            var url="{:U('Admin/Package/ajax_getneworder')}";
            window.setInterval(function(){
                getneworder(url)
            },5000);
        }else if(role==6){
            var url="{:U('Admin/Package/ajax_getnewerrororder')}";
            window.setInterval(function(){
                getneworder(url)
            },5000);
        }else if(role==3){
            var url="{:U('Admin/Order/ajax_getneworder')}";
            window.setInterval(function(){
                getneworder(url)
            },5000);
        }else if(role==1){
            var url="{:U('Admin/Order/ajax_getnewcompanyorder')}";  
            window.setInterval(function(){
                getneworder(url)
            },5000);        
        }
        
        var lasttime = Date.parse( new Date());

        function getneworder(url) {
            $.ajax({
                type: "POST",
                url: url,
                data: { 'lasttime': lasttime },
                dataType: "json",
                success: function (data) {
                    if (data.status == 1) {
                        if(role==2){
                            var cookieobj='packageOrdernoticestatus';
                            var ps = $.cookie('packageOrdernoticestatus');
                        }else if(role==6){
                            var cookieobj='packageerrorOrdernoticestatus';
                            var ps = $.cookie('packageerrorOrdernoticestatus');
                        }else if(role==3){
                            var cookieobj='Ordernoticestatus';
                            var ps = $.cookie('Ordernoticestatus');
                        }else if(role==1){
                            var cookieobj='Ordernoticestatus';
                            var ps = $.cookie('Ordernoticestatus');
                        }
                        if(isopen==0){
                            if (ps) {
                                if(ps.split(',').indexOf(data.order.orderid+'')==-1){
                                    isopen=1;
                                    layer.open({
                                        type: 2,
                                        title: '提示',
                                        shadeClose: true,
                                        shade: false,
                                        area: ['330px','220px'],
                                        content: "{:U('Admin/Order/newordernotice')}?orderid=" + data.order.orderid,
                                        end:function(){
                                            isopen=0;
                                            if(role==2){
                                                iframeJudge({
                                                    elem: $(this),
                                                    content:"订单包装管理",
                                                    href: "{:U('Admin/Package/index',array('menuid'=>348))}",
                                                    id: '348Admin'
                                                });
                                            }else if(role==6){
                                                iframeJudge({
                                                    elem: $(this),
                                                    content:"订单包装管理",
                                                    href: "{:U('Admin/Package/errororder',array('menuid'=>422))}",
                                                    id: '422Admin'
                                                });
                                            }
                                        }
                                    });
                                    $.cookie(cookieobj, ps + ',' + data.order.orderid);
                                }
                            }else {
                                layer.open({
                                    type: 2,
                                    title: '提示',
                                    shadeClose: true,
                                    shade: false,
                                    area: ['330px','220px'],
                                    content: "{:U('Admin/Order/newordernotice')}?orderid=" + data.order.orderid,
                                    end:function(){
                                        isopen=0;
                                        if(role==2){
                                            iframeJudge({
                                                elem: $(this),
                                                content:"订单包装管理",
                                                href: "{:U('Admin/Package/index',array('menuid'=>348))}",
                                                id: '348Admin'
                                            });
                                        }else if(role==6){
                                                iframeJudge({
                                                    elem: $(this),
                                                    content:"订单包装管理",
                                                    href: "{:U('Admin/Package/errororder',array('menuid'=>422))}",
                                                    id: '422Admin'
                                                });
                                            }
                                    }
                                });
                                $.cookie(cookieobj, data.order.orderid);
                            }
                        }
                    
                    }
                }
            })
        }
        function setopenstatus(){
            isopen=0;
        }
    </script>
    <script>
        $("#deletecache").on('click',function(e){
            e.preventDefault();
            e.stopPropagation();
            iframeJudge({
                elem: $(this),
                href: "<?php echo U('Admin/Index/public_cache'); ?>",
                id: "deletecache"
            });
        });
        //iframe 加载事件
        var iframe_default = document.getElementById('iframe_default');
        var def_iframe_height = 0;
        $(iframe_default.contentWindow.document).ready(function () {
            setTimeout(function(){
                $('#loading').hide();
            },500);
            $(iframe_default).show();
        });

        function iframe_height(){
            def_iframe_height = $("body").height() - $("#B_history").height() - $(".head th").height();
            $("#B_frame").height(def_iframe_height);
        }

        var USUALL = [],
        /*常用的功能模块*/
        TEMP = [],
        SUALL = USUALL.concat('-', [{
            name: '最近操作',
            disabled: true
        }], TEMP),
        SUBMENU_CONFIG = <?php echo $SUBMENU_CONFIG; ?>, /*主菜单区*/
        imgpath = '',
        times = 0,
        getdescurl = '',
        searchurl = '',
        token = ""; 
        //一级菜单展示
        $(function () {
            var html = [];
            //iframe_height();
            //console.log(SUBMENU_CONFIG);
            $.each(SUBMENU_CONFIG, function (i, o) {
                html.push('<li><a href="" title="' + o.name + '" data-id="' + o.id + '">' + o.name + '</a></li>');
            });
            $('#J_B_main_block').html(html.join(''));
            //后台位在第一个导航
            $('#J_B_main_block li:first > a').click();
        });

        //检查是否出现上下页
        function checkMenuNext() {
            var B_menunav = $('#B_menunav');
            var menu_next = $('#menu_next');
            if (B_menunav.offset().top + B_menunav.height() >= $(window).height() || B_menunav.offset().top < B_menunav.parent().offset().top) {
                menu_next.show();
            } else {
                menu_next.hide();
            }
        }

        //当文档窗口改变大小时触发
        $(window).on('resize', function () {
            setTimeout(function () {
                checkMenuNext();
                //iframe_height();
            }, 100);
        });

        //上一页下一页的点击
        (function () {
            var menu_next = $('#menu_next');
            var B_menunav = $('#B_menunav');
            menu_next.on('click', 'a', function (e) {
                e.preventDefault();
                if (e.target.className === 'pre') {
                    if (B_menunav.offset().top < B_menunav.parent().offset().top) {
                        B_menunav.animate({
                            'marginTop': '+=28px'
                        }, 100);
                    }
                } else if (e.target.className === 'next') {
                    if (B_menunav.offset().top + B_menunav.height() >= $(window).height()) {
                        B_menunav.animate({
                            'marginTop': '-=28px'
                        }, 100);
                    }
                }
            });
        })();
        //一级导航点击
        $('#J_B_main_block').on('click', 'a', function (e) {
            //取消事件的默认动作
            e.preventDefault();
            //终止事件 不再派发事件
            e.stopPropagation();
            $(this).parent().addClass('current').siblings().removeClass('current');
            var data_id = $(this).attr('data-id'),
            data_list = SUBMENU_CONFIG[data_id],
            html = [],
            child_html = [],
            child_index = 0,
            B_menubar = $('#B_menubar');

            if (B_menubar.attr('data-id') == data_id) {
                return false;
            };
            //显示左侧菜单
            show_left_menu(data_list['items']);
            B_menubar.html(html.join('')).attr('data-id', data_id);

            //检查是否应该出现上一页、下一页
            checkMenuNext();

            //显示左侧菜单
            function show_left_menu(data) {
                for (var attr in data) {
                    if (data[attr] && typeof (data[attr]) === 'object') {
                        //循环子对象
                        if (!data[attr].url && attr === 'items') {
                            //子菜单添加识别属性
                            $.each(data[attr], function (i, o) {
                                child_index++;
                                o.isChild = true;
                                o.child_index = child_index;
                            });
                        }
                        show_left_menu(data[attr]); //继续执行循环(筛选子菜单)
                    } else {
                        if (attr === 'name') {
                            data.url = data.url ? data.url : '#';
                            if (!(data['isChild'])) {
                                //一级菜单
                                html.push('<dt><a href="' + data.url + '" data-id="' + data.id + '"><b>' + data.name + '</b></a></dt>');
                            } else {
                                //二级菜单
                                child_html.push('<li><a href="' + data.url + '" data-id="' + data.id + '">' + data.name + '</a></li>');

                                //二级菜单全部push完毕
                                if (data.child_index == child_index) {
                                    html.push('<dd style="display: block; "><ul>' + child_html.join('') + '</ul></dd>');
                                    child_html = [];
                                }
                            }
                        }
                    }
                }
            };
        });
        //左边菜单点击
        $('#B_menubar').on('click', 'a', function (e) {
            e.preventDefault();
            e.stopPropagation();
            //iframe_height();
            var $this = $(this),
            _dt = $this.parent(),
            _dd = _dt.next('dd');
            $("#B_menubar li").removeClass('current');
            //当前菜单状态
            _dt.addClass('current').siblings('dt.current').removeClass('current');

            //子菜单显示&隐藏
            if (_dd.length) {
                _dt.toggleClass('current');
                _dd.toggle();
                //检查上下分页
                checkMenuNext();
                return false;
            };

            $('#loading').show().focus(); //显示loading
            $('#B_history li').removeClass('current');
            var data_id = $(this).attr('data-id'),
            li = $('#B_history li[data-id=' + data_id + ']');
            var href = this.href;

            iframeJudge({
                elem: $this,
                href: href,
                id: data_id
            });

        });

        /*
         * 搜索
         */
        var search_keyword = $('#J_search_keyword'),
        search = $('#J_search');
        search.on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
            search_val = $.trim(search_keyword.val());
            if (search_val) {
                iframeJudge({
                    elem: $this,
                    href: $this.data('url') + '&keyword=' + search_val,
                    id: 'search'
                });
            }
        });

        //回车搜索
        search_keyword.on('keydown', function (e) {
            if (e.keyCode == 13) {
                search.click();
            }
        });

        //判断显示或创建iframe
        function iframeJudge(options) {
            var elem = options.elem,
            href = options.href,
            id = options.id,
            content=options.content,
            li = $('#B_history li[data-id=' + id + ']');

            if (li.length > 0) {
                //如果是已经存在的iframe，则显示并让选项卡高亮,并不显示loading
                var iframe = $('#iframe_' + id);
                setTimeout(function(){
                    $('#loading').hide();
                },500);
                li.addClass('current');
                if (iframe[0].contentWindow && iframe[0].contentWindow.location.href !== href) {
                    iframe[0].contentWindow.location.href = href;
                }
                $('#B_frame iframe').hide();
                $('#iframe_' + id).show();
                showTab(li); //计算此tab的位置，如果不在屏幕内，则移动导航位置
            } else {
                //创建一个并加以标识
                var iframeAttr = {
                    src: href,
                    id: 'iframe_' + id,
                    frameborder: '0',
                    scrolling: 'auto',
                    height: '100%',
                    width: '100%'
                };
                var iframe = $('<iframe/>').prop(iframeAttr).appendTo('#B_frame');

                $(iframe[0].contentWindow.document).ready(function () {
                    $('#B_frame iframe').hide();
                    setTimeout(function(){
                        $('#loading').hide();
                    },500);
                    if(content!=undefined){
                        var li = $('<li tabindex="0"><span><a>' + content + '</a><a class="del" title="关闭此页">关闭</a></span></li>').attr('data-id', id).addClass('current');
                    }else{
                        var li = $('<li tabindex="0"><span><a>' + elem.html() + '</a><a class="del" title="关闭此页">关闭</a></span></li>').attr('data-id', id).addClass('current');
                    }
                    
                    li.siblings().removeClass('current');
                    li.appendTo('#B_history');
                    showTab(li); //计算此tab的位置，如果不在屏幕内，则移动导航位置
                    //$(this).show().unbind('load');
                });
            }
        }

        //顶部点击一个tab页
        $('#B_history').on('click focus', 'li', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data_id = $(this).data('id');
            if(data_id){
                //选择顶部菜单
                var curid = data_id;
                if(curid == "default") curid = "changyong";
                var topmenu = getTopMenuByID(curid);
                var objtopmenu = $('#J_B_main_block').find("a[data-id=" + topmenu.id +"]");
                if(objtopmenu.parent().attr("class") != "current"){
                    //选中当前顶部菜单
                    objtopmenu.parent().addClass('current').siblings().removeClass('current');
                    //触发事件
                    objtopmenu.click();
                }
                //选择左边菜单
                $("#B_menubar").find(".current").removeClass('current');
                $("#B_menubar").find("a[data-id=" + data_id +"]").parent().addClass('current');
            }
    
            $(this).addClass('current').siblings('li').removeClass('current');
            try{
                var menuid = parseInt(data_id);
                if(menuid){
                    setCookie("menuid",menuid);
                }
            }catch(err){}
            $('#iframe_' + data_id).show().siblings('iframe').hide(); //隐藏其它iframe
        });

        //顶部关闭一个tab页
        $('#B_history').on('click', 'a.del', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var li = $(this).parent().parent(),
            prev_li = li.prev('li'),
            data_id = li.attr('data-id');
            li.hide(60, function () {
                $(this).remove(); //移除选项卡
                $('#iframe_' + data_id).remove(); //移除iframe页面
                var current_li = $('#B_history li.current');
                //找到关闭后当前应该显示的选项卡
                current_li = current_li.length ? current_li : prev_li;
                current_li.addClass('current');
                cur_data_id = current_li.attr('data-id');
                $('#iframe_' + cur_data_id).show();
            });
        });

        //通过菜单id查找菜单配置对象
        function getMenuByID(mid,menugroup){
            var ret = {};
            mid = parseInt(mid);
            if(!menugroup) menugroup = SUBMENU_CONFIG;
            if(isNaN(mid)){
                ret = menugroup['changyong'];
            }else{
                $.each(menugroup, function (i, o) {
                    if( o.id &&  parseInt(o.id) == mid ){
                        ret = o;
                        return false
                    }else if(o.items){
                        var tmp = getMenuByID(mid,o.items);
                        if( tmp.id && parseInt(tmp.id) == mid ){
                            ret = tmp;
                            return false
                        }
                    }
                });
            }
            return ret;
        }

        function getTopMenuByID(mid){
            var ret = {};
            var menu = getMenuByID(mid);
            if(menu){
                if(menu.parent){
                    var tmp = getTopMenuByID(menu.parent);
                    if(tmp && tmp.id){
                        ret = tmp;
                    }
                }else{
                    ret = menu;
                }
            }
            return ret;
        }

        //刷新
        $('#J_refresh').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $('#B_history .current').attr('data-id'),
            iframe = $('#iframe_' + id);
            if (iframe[0].contentWindow) {
                //common.js
                reloadPage(iframe[0].contentWindow);
            }
        });

        //全屏/非全屏
        $('#J_fullScreen').toggle(function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(document.body).addClass('fullScreen');
            def_iframe_height = $("body").height();
            $("#B_frame").height(def_iframe_height);
        }, function () {
            $(document.body).removeClass('fullScreen');
            //iframe_height();
        });

        //下一个选项卡
        $('#J_next').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var ul = $('#B_history'),
            current = ul.find('.current'),
            li = current.next('li');
            showTab(li);
        });

        //上一个选项卡
        $('#J_prev').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var ul = $('#B_history'),
            current = ul.find('.current'),
            li = current.prev('li');
            showTab(li);
        });

        //显示顶部导航时作位置判断，点击左边菜单、上一tab、下一tab时公用
        function showTab(li) {
            if (li.length) {
                var ul = $('#B_history'),
                li_offset = li.offset(),
                li_width = li.outerWidth(true),
                next_left = $('#J_next').offset().left - 9, //右边按钮的界限位置
                prev_right = $('#J_prev').offset().left + $('#J_prev').outerWidth(true); //左边按钮的界限位置
                if (li_offset.left + li_width > next_left) { //如果将要移动的元素在不可见的右边，则需要移动
                    var distance = li_offset.left + li_width - next_left; //计算当前父元素的右边距离，算出右移多少像素
                    ul.animate({
                        left: '-=' + distance
                    }, 200, 'swing');
                } else if (li_offset.left < prev_right) { //如果将要移动的元素在不可见的左边，则需要移动
                    var distance = prev_right - li_offset.left; //计算当前父元素的左边距离，算出左移多少像素
                    ul.animate({
                        left: '+=' + distance
                    }, 200, 'swing');
                }
                li.trigger('click');
            }
        }

        //增强体验，如果支持全屏，则使用更完美的全屏方案
        
        // Wind.use('requestFullScreen', function () {
        //     if (fullScreenApi.supportsFullScreen) {
        //         $('#J_fullScreen').unbind('click').one('click', function (e) {
        //             e.preventDefault();
        //             $('body').requestFullScreen();
        //         });
        //     }
        // })
         
    </script>


</body>
</html>

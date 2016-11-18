<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>网站后台管理系统</title>
        <script type="text/javascript" src="__JS__/jquery-1.10.2.min.js"></script>
        <link type="text/css" rel="stylesheet" href="__CSS__/global.css" />
        <link type="text/css" rel="stylesheet" href="__CSS__/style1.css" title="a" />
        <script type="text/javascript" src="__JS__/common.js"></script>
         <script type="text/javascript" src="__JS__/index.js"></script>
        <!--公共头部End-->
        <script type="text/javascript" src="__JS__/menu.js"></script>
    </head>
    <body id="body_index">
        <div class="bar fl" id="header" style="position:relative; z-index:2;">
            <div class="fl" id="logo"><h1><a href="#" class="ico"></a></h1></div>
            <ul class="fl menu menu_one">
                <!--动态载入一级菜单-->
            </ul>
            <div class="barright">

                <ul class=" fl skin">
                    <li class="ico" id="style1"></li>
                    <li class="ico" id="style2"></li>
                    <li class="ico" id="style3"></li>
                </ul>

                <!--消息下拉菜单-->
                <div class="header_login_later fl"><div class="ico userico"></div><a href="#" id="J_head_user_a" class="username" title="超级管理员">超级管理员<em class="core_arrow"></em></a>
                    <div class="fl">
                        <div id="J_head_user_menu" role="menu" class="header_menu_wrap my_menu_wrap" style="display:none;" >
                            <div class="header_menu my_menu cc fl">
                                <div class="header_menu_hd" id="J_head_pl_user"><div class="ico userico"></div><span title="超级管理员" class="username">超级管理员</span><em class="core_arrow_up"></em></div>
                                <ul class="ct cc">
                                    <li><a href="#" onclick="alert('未连接好');return false;"><em class="icon_forum"></em>我的消息</a></li>
                                    <li><a href="#" onclick="alert('未连接好');return false;"><em class="icon_article"></em>我的文章</a></li>
                                </ul>
                                <ul class="ft cc">
                                    <li><a href="#" ><em class="icon_profile"></em>个人信息</a></li>
                                    <li><a href="{:U('Admin/Public/loginout')}" ><em class="icon_quit"></em>退出</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
</div>
        </div>
        <div class="fl" id="footer_k" style="position:relative; z-index:0;">
            <div class="fl" id="left_k">
                <div id="search_menu_k">
                    <div id="search_kuang">
                        <div class="fl inputk"><input type="text" placeholder="搜索菜单" title="搜索菜单" /></div><div class="ico search_btn">搜索菜单</div>
                    </div>
                </div>
                <div class="fl" id="left_menu">
                    <ul class="menutwo">
                        <!--动态载入二级，三级菜单-->
                    </ul>
                </div>
                <ul id="roll_k">
                    <li class="ico" onclick="pre_scoll();"><div class="ico scoll_ico pre_scoll" ></div></li>
                    <li class="ico" onclick="nex_scoll();"><div class="ico scoll_ico nex_scoll" ></div></li>
                </ul>
            </div>

            <div class="fl" id="right_k">
                <div class="fl navbar">
                    <div class="ico tabA_sc tabA_pres" id="J_prev" title="上一个">上一个</div>
                    <div id="navbarkt">
                        <ul id="B_history" style="white-space:nowrap;position:absolute;left:0px;top:0; z-index:0;">
                            <li class="current" data-id="default" tabindex="0"><span><a>首页</a></span></li>

                        </ul>
                    </div>
                    <div class="ico tabA_sc tabA_nexts" id="J_next" title="下一个">下一个</div>               
                </div>
                <div class="fl main_k" id="B_frame">
                    <iframe name="main" class="main" id="iframe_default"  src="{:U('Admin/Index/Main')}" frameborder="0"></iframe>
                </div>
            </div>

        </div>
    </body>
</html>

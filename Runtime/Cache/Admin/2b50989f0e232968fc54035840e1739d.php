<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>网站系统管理后台</title>
<link href="/Public/Admin/css/admin_style.css" rel="stylesheet" />
<link href="/Public/Admin/js/artDialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript" src="/Public/Editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "",
    JS_ROOT: "/Public/Admin/js/",
    TOKEN: "d8a7e4212dd72764fc54360bc619692c_0be21a07a2313806c7f61fc129e26832"
};
</script>
<script src="/Public/Admin/js/wind.js"></script>
<script src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/layer/layer.js"></script>
<script src="/Public/Admin/js/jquery.cookie.js"></script>
<script>
    $(document).ready(function(){
        $('a.del').click(function(){
             if(confirm("您确定要删除此信息？")){
                 return true;
            }else{
                return false;
            }
        });
        $('a.cancel').click(function () {
            if (confirm("您确定要取消此订单？")) {
                return true;
            } else {
                return false;
            }
        });
        $('a.close').click(function () {
            if (confirm("您确定要关闭此订单？")) {
                return true;
            } else {
                return false;
            }
        });

        $("button.J_ajax_submit_btn").click(function(){
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement(); 
                return true;
            }
        })
    });
</script>
</head>
<script type="text/javascript" src="/Public/Admin/js/eventSend.js"></script>
<script type="text/javascript" src="/Public/Admin/js/highcharts.js"></script>
<script type="text/javascript" src="/Public/Admin/js/exporting.js"></script>
<body>
    <div class="wrap">
        <div id="home_toptip"></div>
        <div class="welcome_login"><span class="green"><?php echo ($User["username"]); ?></span> 您好，欢迎登陆蔬果先生后台管理系统<span>上次登陆时间：<?php echo ($lastLogin["add_time"]); ?></span><span>上次登陆地区：<?php echo ($lastLogin["area"]); ?></span></div>
        
        <div class="welcome_block" style="width: 100%">
            <h2 class="h_a">今日实时统计</h2>
            <table class="centerTable" cellpadding="0" cellspacing="1">
                <tr>
                    <th>新增会员</th>
                    <th>新增普通订单</th>
                    <th>新增预购订单</th>
                    <th>新增企业订单</th>
                    <th>新增极速达订单</th>
                    <th>新增称重订单</th>
                    <th>新增企业申请</th>
                    <th>新增发票申请</th>
                </tr>
                <tr>
                    <td><span id="users">0</span></td>
                    <td><span id="simpleorder">0.00</span>(<span id="simpleorder_count">0单</span>)</td>
                    <td><span id="bookorder">0.00</span>(<span id="bookorder_count">0单</span>)</td>
                    <td><span id="companyorder">0.00</span>(<span id="companyorder_count">0单</span>)</td>
                    <td><span id="speedorder">0.00</span>(<span id="speedorder_count">0单</span>)</td>
                    <td><span id="weighorder">0.00</span>(<span id="weighorder_count">0单</span>)</td>
                    <td><span id="company">0</span></td>
                    <td><span id="bill">0</span></td>
                </tr>
            </table>
        </div>

        <h2 class="h_a">运营统计</h2>
        <script type="text/javascript">
            $(function () {
                $('#container').highcharts({
                    title: {
                        text: '网站运营统计数据图表',
                        x: -20 //center
                    },
                    exporting :{
                        url:'/Public/Admin/js/export/index.php',
                        width:800,
                        enabled:false
                    },
                    subtitle: {
                        text: '最近十五天的网站运营统计数据图表(点击最右边曲线名称可显示/隐藏对应曲线)',
                        x: -20
                    },
                    xAxis: {
                        categories: <?php echo $date; ?>
                        },
                    yAxis: {
                        title: {
                            text: '新增数量'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        xDateFormat: '%Y-%m-%d',
                    },
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom',
                        borderWidth: 0
                    },
                    series: [{
                        name: '新增会员',
                        data: [<?php echo $user; ?>]
                    }, {
                        name: '新增普通订单',
                        data: [<?php echo $simpleorder; ?>]
                            }, {
                                name: '新增预购订单',
                                data: [<?php echo $bookorder; ?>]
                            }, {
                                name: '新增企业订单',
                                data: [<?php echo $companyorder; ?>]
                            }, {
                                name: '新增极速达订单',
                                data: [<?php echo $speedorder; ?>]
                            }, {
                                name: '新增称重订单',
                                data: [<?php echo $weighorder; ?>]
                            }],
                    credits : {
                        enabled:false//不显示highCharts版权信息
                    }
                });
            }); 
        </script>
        <div id="container" style="min-width: 600px; height: 400px"></div>
        <h2 class="h_a" style="float: left;width: 50%;">系统信息</h2>
        <div class="home_info" style="float: left;width: 50%;">
            <ul>
                <?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li> <em><?php echo ($key); ?></em> <span><?php echo ($vo); ?></span> </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <h2 class="h_a">系统版权</h2>
        <div class="home_info" id="home_devteam" style="float: left;">
            <ul>
                <li><em>版权所有</em> <span>esugo.cn 蔬果先生</span> </li>
                <!-- <li><em>研发团队</em> <span>上海裕崇广告设计有限公司</span> </li> -->
            </ul>
        </div>
    </div>
    </div>
    <div id="rbbox">
        <div class="table_full">
            <table cellpadding="0" cellspacing="0" class="table_form contentWrap" width="100%">
                <tbody>
                    <tr>
                        <th width="100">待派发订单</th>
                        <td>
                            <?php echo ($waitdistribute); ?>单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待包装订单</th>
                        <td>
                            <?php echo ($waitpackage); ?>单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待配送订单</th>
                        <td>
                            <?php echo ($waitdelivery); ?>单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核异常订单</th>
                        <td>
                            <?php echo ($waitcheckerror); ?>单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核售后订单</th>
                        <td>
                            <?php echo ($waitcheckservice); ?>单
                        </td>
                    </tr>
                    <?php if(($User['role']) == "1"): ?><tr>
                        <th width="100">待派发第三方平台订单</th>
                        <td>
                            <?php echo ($waitdistribute_third); ?>单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核企业申请</th>
                        <td>
                            <?php echo ($waitreview_company); ?>个
                        </td>
                    </tr><?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
    <style type="text/css">
        #rbbox {
            position: absolute;
            right: 0px;
            bottom: 0;
            width: 300px;
            height: 0px;
            overflow: hidden;
            background: #f3f3f3;
        }
    </style>
    <script src="/Public/Admin/js/layer/layer.js"></script>
    <script language="javascript" type="text/javascript">
        var role = '<?php echo ($User["role"]); ?>';
        if(role==1){
            window.onload = function () {
                showBox();
            }
        } else if (role == 3) {
            window.onload = function () {
                showBox();
            }
        }
        
        function showBox(o) {
            layer.open({
                type: 1,
                skin: 'layui-layer-demo', //样式类名
                closeBtn: 1, //不显示关闭按钮
                shift: 2,
                offset: 'rb', //右下角弹出
                title: '系统消息提示',
                time: 5000, //2秒后自动关闭
                shade: false,
                shadeClose: true, //开启遮罩关闭
                content: $("#rbbox").html()
            });
        }
    </script>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script>
        $("#btn_submit").click(function () {
            $("#tips_success").fadeTo(500, 1);
        });
    </script>
      
</body>
</html>
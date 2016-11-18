<include file="Common:Head" />
<script type="text/javascript" src="__JS__/highcharts.js"></script>
<script type="text/javascript" src="__JS__/exporting.js"></script>
<body>
    <div class="wrap">
        <div id="home_toptip"></div>
        <div class="welcome_login"><span class="green">{$User.username}</span> 您好，欢迎登陆{$site.sitename}<span>上次登陆时间：{$lastLogin.add_time}</span><span>上次登陆地区：{$lastLogin.area}</span></div>
        <h2 class="h_a">系统信息</h2>
        <div class="home_info">
            <ul>
                <volist name="server_info" id="vo">
                    <li> <em>{$key}</em> <span>{$vo}</span> </li>
                </volist>
            </ul>
        </div>
        <h2 class="h_a">系统版权</h2>
        <div class="home_info" id="home_devteam">
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
                            {$waitdistribute}单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待包装订单</th>
                        <td>
                            {$waitpackage}单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待配送订单</th>
                        <td>
                            {$waitdelivery}单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核异常订单</th>
                        <td>
                            {$waitcheckerror}单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核售后订单</th>
                        <td>
                            {$waitcheckservice}单
                        </td>
                    </tr>
                    <eq name="User['role']" value="1">
                    <tr>
                        <th width="100">待派发第三方平台订单</th>
                        <td>
                            {$waitdistribute_third}单
                        </td>
                    </tr>
                    <tr>
                        <th width="100">待审核企业申请</th>
                        <td>
                            {$waitreview_company}个
                        </td>
                    </tr>
                    </eq>
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
    <script src="__JS__/layer/layer.js"></script>
    <script language="javascript" type="text/javascript">
        var role = '{$User.role}';
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
    <script src="__JS__/common.js?v"></script>
    <script>
        $("#btn_submit").click(function () {
            $("#tips_success").fadeTo(500, 1);
        });
    </script>
</body>
</html>
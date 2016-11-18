<include file="Common:Head" />
<style type="text/css">
            .col-auto {
                overflow: hidden;
                _zoom: 1;
                _float: left;
                border: 1px solid #c2d1d8;
            }
            .col-right {
                float: right;
                width: 210px;
                overflow: hidden;
                margin-left: 6px;
                border: 1px solid #c2d1d8;
            }

            body fieldset {
                border: 1px solid #D8D8D8;
                padding: 10px;
                background-color: #FFF;
            }
            body fieldset legend {
                background-color: #F9F9F9;
                border: 1px solid #D8D8D8;
                font-weight: 700;
                padding: 3px 8px;
            }
            .picList li{ float: left; margin-top: 2px; margin-right: 5px;}
        </style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
                <div class="h_a">申请详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">联系人</th>
                                <td>{$data.name}</td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td>{$data.phone}</td>
                            </tr>
                            <tr>
                                <th>邮箱</th>
                                <td>{$data.email}</td>
                            </tr>
                            <tr>
                                <th>公司名称</th>
                                <td>{$data.company}</td>
                            </tr>
                            <tr>
                                <th>希望我们帮助您做</th>
                                <td>{$data.wish}</td>
                            </tr>
                            <tr>
                                <th>联系状态</th>
                                <td>
                                    <if condition="$data.status eq 0">未联系</if>
                                    <if condition="$data.status eq 1">已联系</if>
                                </td>
                            </tr>
                            <tr>
                                <th>申请时间</th>
                                <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
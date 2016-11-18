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

            <form method="post" action="{:U('Admin/Integral/edit')}">
                <div class="h_a">基本信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">用户名</th>
                                <td>{$username}
                                    <input type="text" name="id" style="display:none" id="id" value="{$data.id}">
                                </td>
                            </tr>
                            <tr>
                                <th>当前可用积分</th>
                                <td>{$data.useintegral}分</td>
                            </tr>
                            <tr>
                                <th>当前累计积分</th>
                                <td>{$data.totalintegral}分</td>
                            </tr>
                            <tr>
                                <th>增加可用积分</th>
                                <td><input type="text" name="addintegral" class="input" id="addintegral" value="">
                                    <span class="gray">请输入增加的积分</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    
</body>
</html>
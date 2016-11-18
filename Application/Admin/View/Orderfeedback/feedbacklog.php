<include file="Common:Head" />
<style type="text/css">
    .cu, .cu-li li, .cu-span span {
        cursor: hand;
        !important;
        cursor: pointer;
    }

    tr.cu:hover td {
        background-color: #FF9966;
    }
</style>

<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap" style="padding-bottom: 0">
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td width="10%" align="center">ID</td>
                        <td width="60%" align="left">详情</td>
                        <td width="15%" align="center">处理人</td>
                        <td width="15%" align="center">处理时间</td>

                    </tr>
                </thead>
                <tbody>
                    <foreach name="data" item="vo">
                      <tr>
                        <td width="10%" align="center">{$vo.id}</td>
                        <td width="6%" align="left" >{$vo.loginfo}</td>
                        <td width="15%" align="center">{$vo.username}</td>
                        <td width="15%" align="center">{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                      </tr>
                     </foreach>
                </tbody>
            </table>
        </div>
        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <form class="J_ajaxForm" action="{:U('Admin/Orderfeedback/feedbacklog')}" method="post">
                    <textarea type="text" name="loginfo" value=""  class="valid" style="width:100%;height:80px;"></textarea>
                    <input type="hidden" name="orderid" value="{$orderid}" />
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">新建记录</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
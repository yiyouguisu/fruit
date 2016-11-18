<include file="Common:Head" />
<style type="text/css">
.table_full td span{position:relative; margin-left:20px;padding-left: 20px;padding-top: 5px; top: 5px;}
.table_full td span.tip{color:#0C0;background:url(__IMG__/admin/tips/tips_follow.png) left bottom no-repeat;} 
</style>
<body>
    <div class="wrap">
            <div class="h_a">缓存更新</div>
            <div class="table_full">
                <table width="100%">
                    <col class="th" />
                    <col width="100%" />
                    <col />
                    <tr>
                        <th width="100">更新站点数据缓存</th>
                        <td>
                            <a class="btn" href="javascript:;" onClick="doCleanCache('site',this);">提交</a><span></span>
                        </td>
                    </tr>
                    <tr>
                        <th>更新后台模板缓存</th>
                        <td>
                            <a class="btn" href="javascript:;" onClick="doCleanCache('Atemplate',this);">提交</a><span></span>
                        </td>
                    </tr>
                    <tr>
                        <th>更新前台模板缓存</th>
                        <td>
                            <a class="btn" href="javascript:;" onClick="doCleanCache('Htemplate',this);">提交</a><span></span>
                        </td>
                    </tr>
                    <tr>
                        <th>更新WEB模板缓存</th>
                        <td>
                            <a class="btn" href="javascript:;" onClick="doCleanCache('Wtemplate',this);">提交</a><span></span>
                        </td>
                    </tr>
                    <tr>
                        <th>清除网站运行日志</th>
                        <td>
                            <a class="btn" href="javascript:;" onClick="doCleanCache('logs',this);">提交</a><span></span>
                        </td>
                    </tr>
                </table>
            </div>
    </div>
    <script src="__JS__/common.js?v"></script> 
<script type="text/javascript">
var loading = "__PUBLIC__/public/images/loading.gif";
function doCleanCache(type,obj){
    var span = $(obj).siblings("span");
    span.html("<img src='"+loading+"'/>");
    $.ajax({
        url: "{:U('Admin/Index/public_cache')}",
        data: {"type":type},
        timeout: 5000,
        cache: false,
        type: "get",
        dataType: "json",
        success: function (data) {
            if(data) span.html(data.info).addClass("tip");
        }
    });
}
</script>
</body>
</html>
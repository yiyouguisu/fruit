<script type="text/javascript" charset="utf-8" src="__Editor__/UEditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__Editor__/UEditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="__Editor__/UEditor/lang/zh-cn/zh-cn.js"></script>

<script>
    $(function(){
        var url='{:U('Admin/Ueditor/index')}';
        var ue = UE.getEditor('content',{
            serverUrl :url,
            UEDITOR_HOME_URL:'__Editor__/UEditor/',
        });

        ue.ready(function(){
            ue.execCommand('serverparam', {
               'userid': '1',
               'username': 'admin',
            });
        });
    })
</script>
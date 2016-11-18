<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        
        <form action="{:U('Admin/Db/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="15%" align="center" >备份名称</td>
                            <td width="10%" align="center" >卷数</td>
                            <td width="10%" align="center" >压缩</td>
                            <td width="10%" align="center" >数据大小</td>
                            <td width="20%" align="center" >备份时间</td>
                            <td width="25%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="baklist" id="vo">
                        <tr>
                          <td align="center">{$vo.time|date='Ymd-His',###}</td>
                          <td align="center">{$vo.part}</td>
                          <td align="center">{$vo.compress}</td>
                          <td align="center">{$vo.size|format_bytes}</td>
                          <td align="center">{$key}</td>
                          <td align="center" > 
                          <a href="{:U('Admin/Db/delzip',array('time'=>$vo['time']))}"  class="del">删除</a>|
                          <a href="{:U('Admin/Db/import',array('time'=>$vo['time']))}" >还原</a>|
                          <a href="{:U('Admin/Db/downzip',array('time'=>$vo['time'],'part'=>$vo['part'],'compress'=>$vo['compress']))}" >下载</a>
                          </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
            </div>
         
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
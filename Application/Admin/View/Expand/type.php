<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
     
            <div class="table_list">
                <table width="100%">
                    <colgroup>
                        <col width="200">
                        <col width="200">
                        <col>
                        <col width="300">
                        <col width="300">
                    </colgroup>
                    <thead>
                        <tr>
                            <td align='center'>ID</td>
                            <td align='center'>名称</td>
                            <td align='center'>描述</td>
                            <td align='center'>状态</td>
                            <td align='center'>管理操作</td>
                        </tr>
                    </thead>
                    <volist name="data" id="vo">
                        <tr>
                            <td align='center'>{$vo.id}</td>
                            <td align='center'>{$vo.catname}</td>
                            <td align='center'>{$vo.description}</td>
                            <td align='center'><if condition="$vo.status eq 1">启用<else/> <span style="color: red">禁用</span></if></td>
                            <td align='center'>
                                <a href="{:U('Admin/Expand/index', array('catid' => $vo['id']))}">管理子菜单</a>  |
                                <a href="{:U('Admin/Expand/add', array('catid' => $vo['id']))}">添加子菜单</a> |  
                                <a href="{:U('Admin/Expand/typeedit', array('id' => $vo['id']))}">修改</a>  | 
                                <a class="del" href="{:U('Admin/Expand/typedel', array('id' => $vo['id']))}">删除</a>  
                            </td>
                        </tr>
                    </volist>
                </table>
                <div class="p10"><div class="pages"> {$Page} </div> </div>
            </div>
       
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        

        <form action="{:U('Admin/Ad/typelistorder')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            
                            <td width="5%" align="center">排序</td>
                            <td width="5%" align="center" >ID</td>
                            <td width="45%" align="left" >类型名称</td>
                            <td width="30%" align="left" >描述</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                 {$categorys}
                    </volist>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> {$Page} </div>
            </div>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                        <if condition="authcheck('Admin/Ad/typelistorder')">
                        <button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button>
                    </if> 
                
                  
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
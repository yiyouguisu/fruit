<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="15%" align="center" >字段</td>
                            <td width="20%" align="center" >类型</td>
                            <td width="15%" align="center" >索引</td>
                            <td width="15%" align="center" >不是null</td>
                            <td width="15%" align="center" >默认值</td>
                            <td width="15%" align="center" >其他</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="tablecontent" id="vo">
                        <tr>
                          <td align="center" >{$key}</td>
                          <td align="center">{$vo.Field}</td>
                          <td align="center">{$vo.Type}</td>
                          <td align="center">{$vo.Key|default="无"}</td>
                          <td align="center">{$vo.Null}</td>
                          <td align="center">{$vo.Default}</td>
                          <td align="center">{$vo.Extra}</td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
            </div>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
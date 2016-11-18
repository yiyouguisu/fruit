<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Expand/add')}">
                <div class="h_a">基本信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">所属类别:</td>
                                <td class="jgbox">
                                    <select name="catid">
                                     <option value="">--请选择--</option>         
                                     <volist name="cat" id="vo">
                                        <option value="{$vo.id}" <if condition="$vo['id'] eq $catid">selected="selected"</if>>{$vo.catname}</option>      
                                     </volist>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td>名称:</td>
                                <td><input type="text" class="input" name="name" id="name" value="" ></td>
                            </tr>
                             <tr>
                                <td>值:</td>
                                <td><input type="text" class="input" name="value" id="value" value="" ></td>
                            </tr>
                           <!--  <tr>
                                <td>附加数据:</td>
                                <td><input type="text" class="input" name="extravalue" id="extravalue" value="" ></td>
                            </tr> -->
                            <tr>
                                <td>状态:</td>
                                <td><select name="status">
                                        <option value="1" >启用</option>
                                        <option value="0" >禁用</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>排序:</td>
                                <td><input type="text" class="input" name="listorder" id="listorder" value="0" ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
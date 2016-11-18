<include file="Common:Head" />
<script>
    $(function(){
        getchildren(0,true);
        initvals();
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
    })
    function getval()
    {
        var vals="";
        $(".jgbox select").each(function(){
            var val=$(this).val();
            if(val!=null&&val!="")
            {
                vals+=',';
                vals+=val;
            }
        });
        if(vals!="")
        {
            vals=vals.substr(1);        
            $("#arrparentid").val(vals);
        }
    }
    function getchildren(a,b) {
        $.ajax({
            url: "{:U('admin/Expand/getareachildren')}",
            async: false,
            data: { id: a },
            success: function (data) {
                data=eval("("+data+")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if(b)
                    {
                        ahtml += "<option value=''>--请选择--</option>";
                    }
                    for (var i = 0; i < data.length; i++) {
                        ahtml += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                    }
                    ahtml += "</select>";
                    $(".jgbox").append(ahtml);
                }
            }
        });
                    getval();
    }
    function initvals()
    {
        var vals=$("#arrparentid").val();
        if(vals!=null&&vals!="")
        {
            var arr=new Array();
            arr=vals.split(",");
            for(var i=0;i<arr.length;i++)
            {
                if($.trim(arr[i]) !="")
                {
                    $(".jgbox select").last().val(arr[i]);
                    getchildren(arr[i],true);
                }
            }
        }
    }
</script>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Expand/areaedit')}">
                <div class="h_a">基本信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">上级:<input type="hidden" name="arrparentid" id="arrparentid" value="{$data.arrparentid}">  </td>
                                <td class="jgbox">
<!--                  <select name="parentid">
 <option value="0">作为一级菜单</option>          
    {$select_categorys}  
</select>-->
                                </td>
                            </tr>
                            <tr>
                                <td>名称:</td>
                                <td><input type="text" class="input" name="name" id="name" value="{$data.name}" ></td>
                            </tr>
                            <tr>
                                <td>状态:</td>
                                <td><select name="status">
                                        <option value="1"  <eq name="data.status" value="1">selected</eq>>启用</option>
                                        <option value="0"  <eq name="data.status" value="0">selected</eq>>禁用</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>排序:</td>
                                <td><input type="text" class="input" name="listorder" id="listorder" value="{$data.listorder}" ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <input type="hidden" class="input" name="id" value="{$data.id}" >
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
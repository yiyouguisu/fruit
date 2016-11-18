<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<script>
    $(function(){
        getchildren(0,true);
        initvals();
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
    })
     
    function getchildren(a,b) {
        $.ajax({
            url: "{:U('admin/Expand/getchildren')}",
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
            $("#area").val(vals);
        }
    }
    function initvals()
    {
        var vals=$("#area").val();
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
    <div class="wrap J_check_wrap">
       <div class="common-form">
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Bank/edit')}">
                <div class="h_a">银行卡详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">用户名</th>
                                <td>{:getuser($data['uid'])}</td>
                            </tr>
                            <tr>
                                <th>真实姓名</th>
                                <td>{:getuser($data['uid'],'realname')}</td>
                            </tr>
                            <tr>
                                <th>银行卡号</th>
                                <td><input type="text" class="input"  name="banknumber" value="{$data.banknumber}" /></td>
                            </tr>
                            <tr>
                                <th>开户行</th>
                                <td><input type="text" class="input"  name="bankname" value="{$data.bankname}" /></td>
                            </tr>
                            <tr>
                                <th>支行<input type="hidden" class="input" name="area" id="area" value="{$data.area}"></th>
                                <td><span class="jgbox"></span><input type="text" class="input length_2" name="banksubname" value="{$data.banksubname}" /></td>
                            </tr>
                            <tr>
                                <th>提交时间</th>
                                <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                              <th>银行卡照片正面</th>
                              <td>
                                <img src="{$data.bankface}" width="100px"/>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                
                    <div class="btn_wrap">
                        <div class="btn_wrap_pd">
                            <input type="hidden" name="id" value="{$data.id}">
                            <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
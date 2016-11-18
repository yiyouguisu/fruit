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
<script type="text/javascript">
    $(function () {
        getchildren(0, true);
        initvals();
        $(".jgbox").delegate("select", "change", function () {
            $(this).nextAll().remove();
            getchildren($(this).val(), true);
        });
    })

    function getchildren(a, b) {
        $.ajax({
            url: "{:U('admin/Expand/getchildren')}",
            async: false,
            data: { id: a },
            success: function (data) {
                data = eval("(" + data + ")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if (b) {
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
    function getval() {
        var vals = "";
        $(".jgbox select").each(function () {
            var val = $(this).val();
            if (val != null && val != "") {
                vals += ',';
                vals += val;
            }
        });
        if (vals != "") {
            vals = vals.substr(1);
            $("#area").val(vals);
        }
    }
    function initvals() {
        var vals = $("#area").val();
        if (vals != null && vals != "") {
            var arr = new Array();
            arr = vals.split(",");
            for (var i = 0; i < arr.length; i++) {
                if ($.trim(arr[i]) != "") {
                    $(".jgbox select").last().val(arr[i]);
                    getchildren(arr[i], true);
                }
            }
        }
    }

</script>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Companyorder/deal')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10">
                    <span class="mr20">
                        <input type="hidden" class="input" name="area" id="area" value="{$Think.get.area}">
                        <div class="jgbox" style="display: inline-block;"></div>
                        <button class="btn">搜索</button>
                    </span>
                </div>
            </div>
        </form>
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td width="5%" align="center">ID</td>
                        <td width="30%" align="left">店铺名称</td>
                        <td width="15%" align="center">管理操作</td>
                    </tr>
                </thead>
                <tbody>
                    <foreach name="data" item="vo">
                       <tr>
                            <td align="center" >{$vo.id}</td>
                            <td align="left" >
                                <if condition="$vo.status eq 1"> <span style="color: gray">[审核中]</span></if>
                                <if condition="$vo.status eq 2"> <span style="color: green">[审核成功]</span></if>
                                <if condition="$vo.status eq 3"> <span style="color: red">[审核失败]</span></if>
                                <span title="{$vo.title}">{$vo.sortitle}</span>
                            </td>
                            <td align="center" > 
                                <a href="{:U('Admin/Store/dealorder',array('orderid'=>$orderid,'storeid'=>$vo['id']))}" >派发</a>
                            </td>
                        </tr>
                    </foreach>
                </tbody>
            </table>
            <div class="p10">
                <div class="pages">{$Page} </div>
            </div>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
       <div class="common-form">
            <form class="J_ajaxForm" method="post" id="form" action="{:U('Admin/Package/weigh')}" >
                <div class="h_a">商品详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">商品名称</th>
                                <td>{$data.title}</td>
                            </tr>
                            <tr>
                                <th>商品规格</th>
                                <td>{$data.standard}{:getunit($data['unit'])}</td>
                            </tr>
                            <tr>
                                <th>购买数量</th>
                                <td>{$data.nums}</td>
                            </tr>
                            <tr>
                                <th>商品单价</th>
                                <td>{$data.price}元/{:getunit($data['unit'])}</td>
                            </tr>
                            <tr>
                                <th>目前重量</th>
                                <td><input type="number" class="input"  name="weigh" value="" min="0"/>{:getunit($data['unit'])}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                
                    <div class="btn_wrap">
                        <div class="btn_wrap_pd">
                            <input type="hidden" name="id" value="{$id}">
                            <button class="btn btn_submit mr10 J_ajax_submit_btn" type="button" id="save">提交</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
<script src="__JS__/common.js?v"></script>
<script>
    $(function(){
        $(".J_ajax_submit_btn").click(function(){
            if(confirm("请确认重量填写无误，提交后不可修改")){
                var weigh = $("input[name='weigh']").val();
                   
                if (weigh == '') {
                    alert("目前重量不能为空");
                    $("input[name='weigh']").focus();
                    return false;
                }else {
                    $("#form").submit();
                }
                
            }else{
                return false;
            }
        })
    })
</script>
</body>
</html>
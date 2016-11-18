<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="table_list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr style="height: 60px; font-size: 18px; font-weight: bold;">
                        <td width="20%" align="center">订单编号</td>
                        <td width="80%" align="center">商品信息</td>
                    </tr>
                </thead>
                <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center">{$vo.orderid}</td>
                            <td align="center">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <td width="100%" align="center" colspan="5">订单留言：{$vo.buyerremark|default="无"}</td>
                                        </tr>
                                        <tr>
                                            <td width="30%" align="left">商品名称</td>
                                            <td width="20%" align="center">商品数量</td>
                                            <td width="20%" align="center">商品规格</td>
                                            <td width="20%" align="center">需要称重</td>
                                            <td width="10%" align="center">操作</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <volist name="vo['productinfo']" id="v">
                                            <tr>
                                                <td align="left">
                                                    <eq name="v['isout']" value="1"><span style="color:red">【缺货】</span></eq>
                                                    {$v.productname}
                                                </td>
                                                <td align="center">{$v.nums|default="0"}</td>
                                                <td align="center">{$v.standard}{:getunit($v['unit'])}</td>
                                                <td align="center">
                                                    <eq name="v['product_type']" value="4">
                                                        是
                                                        <else />
                                                        否
                                                    </eq>
                                                </td>
                                                <td align="center">
                                                    <a class="doout" href="javascript:;" data-href="{:U('Admin/Product/doout',array('id'=>$v['pid'],'orderid'=>$vo['orderid']))}">已处理</a>|
                                                    <a class="out" href="javascript:;" data-href="{:U('Admin/Product/out',array('id'=>$v['pid'],'orderid'=>$vo['orderid']))}">缺货</a>
                                                    <!-- <a href="javascript:;" class="unout" data-id="{$v.pid}">取消缺货</a> -->

                                                </td>
                                            </tr>
                                        </volist>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </volist>
                </tbody>
            </table>
            <div class="p10">
                <div class="pages" style="text-align: center;">{$Page} </div>
            </div>
        </div>

    </div>

    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/layer/extend/layer.ext.js"></script>
    <script>
        window.setInterval(function () {
            reloadPage(window);
        }, 10000);

        $(function () {
            $(".out").click(function(){
                var url=$(this).data("href");
                if(confirm("是否确认缺货？")){
                    window.location.href=url;
                }
            })
            $(".doout").click(function(){
                var url=$(this).data("href");
                if(confirm("是否确认已处理？")){
                    window.location.href=url;
                }
            })
            $(".unout").click(function () {
                var pid = $(this).data("id");
                layer.prompt({
                    formType: 0,
                    title: '请填写库存'
                }, function (stock, index, elem) {
                    if (stock == '' || Number(stock) == 0) {
                        alert("请填写库存");
                        return false;
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "{:U('Admin/Product/ajax_unout')}",
                            data: { 'pid': pid, 'stock': stock },
                            dataType: "json",
                            success: function (data) {
                                if (data.status == 1) {
                                    layer.close(index);
                                    reloadPage(window);
                                } else {
                                    alert(data.msg);
                                    return false;
                                }
                            }
                        });
                    }
                })
            })

        })

    </script>
</body>
</html>
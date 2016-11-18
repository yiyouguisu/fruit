<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>网站系统管理后台</title>
<link href="/Public/Admin/css/admin_style.css" rel="stylesheet" />
<link href="/Public/Admin/js/artDialog/skins/default.css" rel="stylesheet" />
<script type="text/javascript" src="/Public/Editor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "",
    JS_ROOT: "/Public/Admin/js/",
    TOKEN: "d8a7e4212dd72764fc54360bc619692c_0be21a07a2313806c7f61fc129e26832"
};
</script>
<script src="/Public/Admin/js/wind.js"></script>
<script src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/layer/layer.js"></script>
<script src="/Public/Admin/js/jquery.cookie.js"></script>
<script>
    $(document).ready(function(){
        $('a.del').click(function(){
             if(confirm("您确定要删除此信息？")){
                 return true;
            }else{
                return false;
            }
        });
        $('a.cancel').click(function () {
            if (confirm("您确定要取消此订单？")) {
                return true;
            } else {
                return false;
            }
        });
        $('a.close').click(function () {
            if (confirm("您确定要关闭此订单？")) {
                return true;
            } else {
                return false;
            }
        });

        $("button.J_ajax_submit_btn").click(function(){
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement(); 
                return true;
            }
        })
    });
</script>
</head>
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
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center"><?php echo ($vo["orderid"]); ?></td>
                            <td align="center">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <td width="100%" align="center" colspan="5">订单留言：<?php echo ((isset($vo["buyerremark"]) && ($vo["buyerremark"] !== ""))?($vo["buyerremark"]):"无"); ?></td>
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
                                        <?php if(is_array($vo['productinfo'])): $i = 0; $__LIST__ = $vo['productinfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                                <td align="left">
                                                    <?php if(($v['isout']) == "1"): ?><span style="color:red">【缺货】</span><?php endif; ?>
                                                    <?php echo ($v["productname"]); ?>
                                                </td>
                                                <td align="center"><?php echo ((isset($v["nums"]) && ($v["nums"] !== ""))?($v["nums"]):"0"); ?></td>
                                                <td align="center"><?php echo ($v["standard"]); echo getunit($v['unit']);?></td>
                                                <td align="center">
                                                    <?php if(($v['product_type']) == "4"): ?>是
                                                        <?php else: ?>
                                                        否<?php endif; ?>
                                                </td>
                                                <td align="center">
                                                    <a class="doout" href="javascript:;" data-href="<?php echo U('Admin/Product/doout',array('id'=>$v['pid'],'orderid'=>$vo['orderid']));?>">已处理</a>|
                                                    <a class="out" href="javascript:;" data-href="<?php echo U('Admin/Product/out',array('id'=>$v['pid'],'orderid'=>$vo['orderid']));?>">缺货</a>
                                                    <!-- <a href="javascript:;" class="unout" data-id="<?php echo ($v["pid"]); ?>">取消缺货</a> -->

                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <div class="p10">
                <div class="pages" style="text-align: center;"><?php echo ($Page); ?> </div>
            </div>
        </div>

    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/layer/extend/layer.ext.js"></script>
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
                            url: "<?php echo U('Admin/Product/ajax_unout');?>",
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
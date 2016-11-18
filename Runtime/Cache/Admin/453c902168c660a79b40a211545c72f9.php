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
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <div class="h_a">订单详细信息</div>
            <div class="table_full">
                <table width="100%" class="table_form contentWrap">
                    <tr>
                        <td width="50%" align="center">订单号</td>
                        <td width="50%" align="center">订单来源</td>
                        <td width="30%" align="center">支付方式</td>
                        <td width="30%" align="center">订单金额</td>
                    </tr>
                    <tr>
                        <td align="center"><?php if(($vo['isfeedback']) == "1"): ?><span style="color:green;">[已反馈]</span><?php endif; echo ($data["orderid"]); ?></td>
                        <td align="center">
                            <?php if( $data["ordersource"] == '1'): ?>[手机web]<?php endif; ?>
                            <?php if( $data["ordersource"] == '2'): ?>[App]<?php endif; ?>
                            <?php if( $data["ordersource"] == '3'): ?>[饿了么]<?php endif; ?>
                            <?php if( $data["ordersource"] == '4'): ?>[口碑外卖]<?php endif; ?>
                        </td>
                        <td align="center">
                            <?php if($data["paystyle"] == 1): ?><span style="color: green">在线支付</span>
                                    <?php if($data["paytype"] == 1): ?><span style="color: green">(支付宝)</span><?php endif; ?>
						            <?php if($data["paytype"] == 2): ?><span style="color: green">(微信)</span><?php endif; endif; ?>
                            <?php if($data["paystyle"] == 2): ?><span style="color: green">货到付款</span><?php endif; ?>
                        </td>
                        <td align="center"><?php echo ($data["money"]); ?></br>配送费￥<?php echo ((isset($data["delivery"]) && ($data["delivery"] !== ""))?($data["delivery"]):"0.00"); ?></td>
                    </tr>
                    <tr>
                        <td width="30%" align="center">收货人信息</td>
                        <td width="70%" align="center">收货地址</td>
                        <td width="70%" align="center">订单时间</td>
                        <td width="70%" align="center">订单备注</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <?php echo ($data["name"]); ?><br />
                            <?php echo ($data["tel"]); ?>
                        </td>
                        <td align="center">
                            <?php echo getarea($data['area']);?><br />
                            <?php echo ($data["address"]); ?>
                        </td>
                        <td align="center">
                            <?php echo (date("Y-m-d",$data["inputtime"])); ?><br />
                            <?php echo (date("H:i:s",$data["inputtime"])); ?>
                        </td>
                        <td align="center">
                            <?php echo ($order["buyer_remark"]); ?>
                        </td>
                    </tr>

                    <tr id="product_<?php echo ($data["id"]); ?>">
                        <td colspan="4">
                            <table width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <td width="25%" align="center">产品名称</td>
                                        <td width="25%" align="center">产品价格</td>
                                        <td width="25%" align="center">购买数量</td>
                                        <td width="25%" align="center">商品类型</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_array($data['productinfo'])): $i = 0; $__LIST__ = $data['productinfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                                <td width="25%" align="center" ><?php echo ($v["title"]); ?></td>
                                                <td width="25%" align="center" ><?php echo ($v["price"]); ?>元/<?php echo ($v["standard"]); echo getunit($v['unit']);?></td>
                                                <td width="25%" align="center" ><?php echo ($v["nums"]); ?></td>
                                                <td width="25%" align="center" >
                                                    <?php if( $v["product_type"] == '0'): ?>[企业商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '1'): ?>[一般商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '2'): ?>[团购商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '3'): ?>[预购商品]<?php endif; ?>
                                                    <?php if( $v["product_type"] == '4'): ?>[称重商品]
                                                        <?php if( $v["isweigh"] == 1): ?>已称重[<?php echo (date("Y-m-d H:i:s",$v["weightime"])); ?>]
                                                            <?php else: ?> 
                                                            未称重<?php endif; endif; ?>
                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                        <tr>
                            <td colspan="4" align="left">
                                <?php echo ($data["error_content"]); ?></br>
                                <div id="layer-photos-demo" class="layer-photos-demo">
                                    <?php if(!empty($data['error_thumb'])): if(is_array($data['error_thumb'])): $i = 0; $__LIST__ = $data['error_thumb'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img layer-pid="" layer-src="<?php echo ($vo); ?>" src="<?php echo ($vo); ?>"  alt="订单异常处理图片"  width="60px" height="60px" style="margin: 5px;" /><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </div>
                                
                            </td>
                        </tr>
                </table>



            </div>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
    <script src="/Public/Admin/js/layer/extend/layer.ext.js"></script>
    <script>
            //调用示例
            layer.ready(function(){ //为了layer.ext.js加载完毕再执行
              layer.photos({
                  photos: '#layer-photos-demo'
              });
            }); 
        
    </script>
</body>
</html>
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
</head><body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <!-- <?php  $getMenu = \Admin\Controller\PublicController::getMenu(); if($getMenu) { ?>
<div class="nav">
  <ul class="cc">
    <?php
 foreach($getMenu as $r){ $name = $r['name']; $app=explode("/",$r['name']); $action=$app[1].$app[2]; ?>
    <li <?php echo $action==CONTROLLER_NAME.ACTION_NAME?'class="current"':""; ?>><a href="<?php echo U("".$name."");?>"><?php echo $r['title'];?></a></li>
    <?php
 } ?>
  </ul>
</div>
<?php } ?> -->
       <!--  <div class="h_a">搜索</div>
        <form method="get" action="<?php echo U('Admin/Package/index');?>">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> 
                    <span class="mr20">
                        下单时间：
                        <input type="text" name="start_time" class="input length_2 J_datetime" value="<?php echo ($_GET['start_time']); ?>" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_datetime" name="end_time" value="<?php echo ($_GET['end_time']); ?>" style="width:80px;">
                        订单来源：
                        <select class="select_2" name="ordersource" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['ordersource'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['ordersource']== '1'): ?>selected<?php endif; ?>>手机web</option>
                            <option value="2" <?php if( $_GET['ordersource']== '2'): ?>selected<?php endif; ?>>App</option>
                            <option value="3" <?php if( $_GET['ordersource']== '3'): ?>selected<?php endif; ?>>饿了么</option>
                            <option value="4" <?php if( $_GET['ordersource']== '4'): ?>selected<?php endif; ?>>口碑外卖</option>
                            <option value="5" <?php if( $_GET['ordersource']== '5'): ?>selected<?php endif; ?>>售后订单</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['ordertype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['ordertype']== '1'): ?>selected<?php endif; ?>>一般订单</option>
                            <option value="2" <?php if( $_GET['ordertype']== '2'): ?>selected<?php endif; ?>>预购订单</option>
                            <option value="3" <?php if( $_GET['ordertype']== '3'): ?>selected<?php endif; ?>>企业订单</option>
                            <option value="4" <?php if( $_GET['ordertype']== '4'): ?>selected<?php endif; ?>>称重订单 </option>
                        </select>
                        支付方式：
                        <select class="select_2" name="paytype" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['paytype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['paytype']== '1'): ?>selected<?php endif; ?>>支付宝</option>
                            <option value="2" <?php if( $_GET['paytype']== '2'): ?>selected<?php endif; ?>>微信</option>
                            <option value="3" <?php if( $_GET['paytype']== '3'): ?>selected<?php endif; ?>>货到付款</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['ordertype'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="1" <?php if( $_GET['ordertype']== '1'): ?>selected<?php endif; ?>>普通订单</option>
                            <option value="2" <?php if( $_GET['ordertype']== '2'): ?>selected<?php endif; ?>>预购订单</option>
                            <option value="3" <?php if( $_GET['ordertype']== '3'): ?>selected<?php endif; ?>>企业订单</option>
                        </select>
                        包装状态：
                        <select class="select_2" name="package_status" style="width:85px;">
                            <option value=""  <?php if(empty($_GET['package_status'])): ?>selected<?php endif; ?>>全部</option>
                            <option value="0" <?php if( $_GET['package_status']== '0'): ?>selected<?php endif; ?>>待包装</option>
                            <option value="1" <?php if( $_GET['package_status']== '1'): ?>selected<?php endif; ?>>包装中</option>
                            <option value="2" <?php if( $_GET['package_status']== '2'): ?>selected<?php endif; ?>>包装完成</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($_GET['keyword']); ?>" placeholder="请输入订单号...">
                        <button class="btn">搜索</button>
                    </span>
                    </div>
            </div>
        </form>  -->
        <style>
            .tab_nav {
                padding:10px 15px 0;
                margin-bottom:10px;
                    margin-left: 27%;
            }
            .tab_nav ul{
                border-bottom:1px solid #e3e3e3;
                padding:0 5px;
                height:50px;
                clear:both;
            }
            .tab_nav ul li{
                float:left;
                margin-right: 70px;
                border: 1px s;
                /* border: 1px solid #e3e3e3; */
                border-bottom: 0 none;
                /* color: #333; */
                font-weight: 700;
                background: #fff;
                position: relative;
                border-radius: 10px;
                margin-bottom: -1px;
            }
            .tab_nav ul li a{
                float:left;
                display:block;
                padding:0 10px;
                height:25px;
                line-height:23px;
                    height: 40px;
                    line-height: 40px;
            }
            .tab_nav ul li.current a{
                /*border:1px solid #e3e3e3;*/
                border-bottom:0 none;
                color:#333;
                font-weight:700;
                background:#fff;
                position:relative;
                border-radius:2px;
                margin-bottom:-1px;
                height: 40px;
                line-height: 40px;
                border-radius: 10px;
            }
            .pop_cont{
                padding:0 15px;
            }
        </style>
        <div class="tab_nav" style="margin-bottom:0px">
            <ul class="J_tabs_nav1">
              <li class=""><a href="<?php echo U('Admin/Package/index');?>">全部订单</a><i>+<?php echo ((isset($allnum) && ($allnum !== ""))?($allnum):"0"); ?></i></li>
              <li class="current"><a href="<?php echo U('Admin/Package/waitpackage');?>">待包装</a><i>+<?php echo ((isset($waitpackagenum) && ($waitpackagenum !== ""))?($waitpackagenum):"0"); ?></i></li>
              <li class=""><a href="<?php echo U('Admin/Package/packaging');?>">包装中</a><i>+<?php echo ((isset($packagingnum) && ($packagingnum !== ""))?($packagingnum):"0"); ?></i></li>
              <li class=""><a href="<?php echo U('Admin/Package/packagdone');?>">包装完成</a><i>+<?php echo ((isset($packagdonenum) && ($packagdonenum !== ""))?($packagdonenum):"0"); ?></i></li>
            </ul>
        </div>
        <form action="<?php echo U('Admin/Package/action');?>" method="post" >
                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="liv_box">
                <div class="liv_list">
                    <div class="liv_top">
                        <div class="liv_a1 fl">
                          <input type="checkbox" class="J_check liv_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>">
                          <label><span><?php echo (date("Y-m-d H:i:s",$vo["inputtime"])); ?></span>订单号：<?php echo ($vo["orderid"]); ?></label>
                        </div>
                        <div class="liv_a2 fl">[<a href="javascript:;" data-href="<?php echo U('Admin/Order/download',array('orderid'=>$vo['orderid']));?>" onclick="image_priview('<?php echo ($vo["ordercode"]); ?>');">查看二维码</a>]</div>
                        <div class="liv_a2 fl">下单账号：<?php echo getuserinfo($vo['uid']);?></div>
                    </div>
                    <div class="liv_btm">
                        <div class="liv_left fl" style="width: 55%;">
                          <?php if(is_array($vo['productinfo'])): $i = 0; $__LIST__ = $vo['productinfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="liv_b">
                                <div class="liv_c fl">
                                    <img src="<?php echo ($v["thumb"]); ?>"></div>
                                <div class="liv_d fl">
                                    <div class="liv_d1">
                                        <a href=""><?php echo ($v["title"]); ?></a></div>
                                    <div class="liv_d2">
                                        <a href="">品牌：<?php echo ($v["brand"]); ?> 规格：<?php echo ($v["standard"]); ?>   单位：<?php echo getunit($v['unit']);?></a>
                                      </div>
                                      <?php if(($v['product_type']) == "4"): ?><div class="liv_d3">
                                          <?php if( $v["isweigh"] == 1): ?>已称重[<?php echo (date("Y-m-d H:i:s",$v["weightime"])); ?>]
                                                <?php else: ?> 
                                                未称重<?php endif; ?>
                                        </div><?php endif; ?>
                                </div>

                                <div class="liv_e fl">
                                    <div class="liv_e1"><?php echo ($v["oldprice"]); ?></div>
                                    <div class="liv_e2"><?php echo ($v["nowprice"]); ?></div>
                                </div>
                                <div class="liv_f fl">x<?php echo ($v["nums"]); ?></div>
                                <div class="liv_g fl">
                                    <div class="liv_g1">
                                      <?php if( $v["product_type"] == '0'): ?>[企业商品]<?php endif; ?>
                                      <?php if( $v["product_type"] == '1'): ?>[一般商品]<?php endif; ?>
                                      <?php if( $v["product_type"] == '2'): ?>[团购商品]<?php endif; ?>
                                      <?php if( $v["product_type"] == '3'): ?>[预购商品]<?php endif; ?>
                                      <?php if( $v["product_type"] == '4'): ?>[称重商品]<?php endif; ?>
                                    </div>
                                    
                                        <?php if(($v['product_type']) == "4"): if( $v["isweigh"] == 0): ?><input type="button" onClick="omnipotent('selectid','<?php echo U('Admin/Package/weigh',array('id'=>$v['id']));?>','填写称重信息',1,700,400)" value="需要称重"><?php endif; endif; ?>
                                        <?php if(($v['isout']) == "1"): ?><span style="color:red">【缺货】</span><?php endif; ?>
                                        
                                </div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                            <!-- <div class="over_a">
                                <div class="over_b fl">【<?php echo ($vo["name"]); ?>】<?php echo ($vo["tel"]); ?></div>
                                <div class="over_c fl"><?php echo getarea($vo['area']);?></div>
                                <div class="over_d fl"><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></div>
                                <div class="over_d fl" style="    margin-left: 10px;"><?php echo getordersendtime($vo['orderid'],2);?></div>
                            </div> -->

                        </div>
                        <div class="liv_right fl" style="width:45%;">
                            <div class="fish_a fl">
                                
                                <div class="fish_a1"><?php echo ($vo["total"]); ?><a href="javascript:;" onclick="lalert(<?php echo ($vo['money']); ?>,<?php echo ($vo['wallet']); ?>,<?php echo ($vo['discount']); ?>,<?php echo ($vo['total']); ?>,<?php echo ($vo['wait_money']); ?>,<?php echo ($vo['yes_money']); ?>,<?php echo ($vo['yes_money_total']); ?>,<?php echo ($vo['pay_status']); ?>,<?php echo ($vo['ordertype']); ?>);" class="info"><img src="/Public/Admin/images/info.png" style="width: 20px;display: inline-block;margin-bottom: -5px;"></a></div>
                                <div class="fish_a2">含运费（<?php echo ((isset($vo["delivery"]) && ($vo["delivery"] !== ""))?($vo["delivery"]):"0.00"); ?>）</div>
                                <div class="fish_a3">
                                  <?php echo getordersource($vo['ordersource']);?>
                                </div>
                                <div class="fish_a3">
                                <?php if(($vo['ordertype']) == "1"): ?>一般订单<?php endif; ?>
                                <?php if(($vo['ordertype']) == "2"): ?>预购订单<?php endif; ?>
                                <?php if(($vo['ordertype']) == "3"): ?>企业订单<?php endif; ?>
                                <?php if(($vo['ordertype']) == "4"): ?>称重订单<?php endif; ?>
                                </div>
                            </div>

                            <div class="fish_c fl">
                              <div class="fish_c1">
                                <?php if($vo['status'] != 5): if(empty($vo['puid'])): ?>待派发</br>
                                        <?php else: ?>
                                        <?php if(($vo['package_status']) == "0"): ?>已派发给【<?php echo getAuser($vo['puid']);?>】</br><?php endif; ?>
                                        <?php if(($vo['package_status']) == "1"): ?>【<?php echo getAuser($vo['puid']);?>】正在包装中</br><?php endif; ?>
                                        <?php if(($vo['package_status']) == "2"): ?>【<?php echo getAuser($vo['puid']);?>】包装完成</br><?php endif; ?>
                                        <?php if(empty($vo['ruid'])): ?>待配送</br>
                                          <?php else: ?>
                                          <?php if(($vo['delivery_status']) == "0"): ?>【<?php echo getuser($vo['ruid']);?>】待发货</br><?php endif; ?>
                                          <?php if(($vo['delivery_status']) == "1"): ?>【<?php echo getuser($vo['ruid']);?>】正在配送中</br><?php endif; ?>
                                          <?php if(($vo['delivery_status']) == "4"): ?>【<?php echo getuser($vo['ruid']);?>】配送完成</br><?php endif; endif; endif; ?>
                                    
                                    <?php else: ?>
                                    订单已完成<br/><?php echo (date("Y-m-d H:i:s",$vo["donetime"])); ?></br><?php endif; ?>
                              </div>
                                <div class="fish_c2">
                                  <a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Order/show',array('orderid'=>$vo['orderid']));?>','订单详情',1,700,400)">查看详情</a></br>
                                </div>
                                <div class="fish_c2"><?php echo getpaystyle($vo['orderid']);?></div>
                                <div class="fish_c2"><?php echo getpaystatus($vo['pay_status'],$vo['orderid']);?></div>
                            </div>

                                <div class="fish_d fl">
                                    <?php if($vo["package_status"] == 0): ?>待包装[<a href="<?php echo U('Admin/Package/package',array('orderid'=>$vo['orderid']));?>">包装</a>]<?php endif; ?>
                                    <?php if($vo["package_status"] == 1): ?>包装中[<a href="<?php echo U('Admin/Package/packagedone',array('orderid'=>$vo['orderid']));?>">包装完成</a>]</br><?php echo getAuser($vo['puid']);?></br><?php echo (date("Y-m-d H:i:s",$vo["package_time"])); ?></br><?php endif; ?>
                                    <?php if($vo["package_status"] == 2): ?>包装完成</br><?php echo getAuser($vo['puid']);?></br><?php echo (date("Y-m-d H:i:s",$vo["package_donetime"])); ?></br><?php endif; ?>
                                    <?php if(($vo['print_status']) == "0"): ?><a href="javascript:;" onClick="omnipotent('selectid','<?php echo U('Admin/Order/orderprint',array('orderid'=>$vo['orderid']));?>','打印订单',1,390,250)">打印</a>
                                    <a href="<?php echo U('Admin/Order/printorder',array('orderid'=>$vo['orderid']));?>">更新打印状态</a>
                                    <?php else: ?>
                                    已打印<?php endif; ?>
                                <!-- <input type="button" class="doout" data-url="<?php echo U('Admin/Package/doout',array('orderid'=>$vo['orderid']));?>" value="缺货处理"> -->
                                </div>

                            </div>
                        </div>
                        <div class="liv_btm">
                        <div class="over_b fl">【<?php echo ($vo["name"]); ?>】<?php echo ($vo["tel"]); ?></div>
                        <div class="over_c fl"><?php echo getarea($vo['area']);?></div>
                        <div class="over_d fl"><?php echo ($vo["areatext"]); echo ($vo["address"]); ?></div>
                        <div class="over_d fl" style="    margin-left: 10px;"><?php echo getordersendtime($vo['orderid'],2);?></div>
                    </div>
                    <div class="liv_btm">
                            <div class="over_b fl">【订单留言】:<?php echo ((isset($vo["buyerremark"]) && ($vo["buyerremark"] !== ""))?($vo["buyerremark"]):"无订单留言"); ?></div>
                        </div>
                        <div class="liv_btm">
                            <div class="over_b fl">【贺卡留言】:<?php echo ((isset($vo["cardremark"]) && ($vo["cardremark"] !== ""))?($vo["cardremark"]):"无贺卡留言"); ?></div>
                        </div>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="p10">
                    <div class="pages"> <?php echo ($Page); ?> </div>
                    <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>   
                    <?php if(authcheck('Admin/Package/packages')): ?><button class="btn btn_submit mr10 " type="submit" name="submit" value="packages">批量包装</button><?php endif; ?>
                    
                </div>
        </form>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <?php if(authcheck('Admin/Package/excel')): ?><form method="post" action="<?php echo U('Admin/Package/excel');?>">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="<?php echo ($_GET['start_time']); ?>" >
                            <input type="hidden"  name="end_time" value="<?php echo ($_GET['end_time']); ?>" >
                            <input type="hidden"  name="ordersource" value="<?php echo ($_GET['ordersource']); ?>" >
                            <input type="hidden"  name="paytype" value="<?php echo ($_GET['paytype']); ?>" >
                            <input type="hidden"  name="ordertype" value="<?php echo ($_GET['ordertype']); ?>" >
                            <input type="hidden"  name="package_status" value="<?php echo ($_GET['package_status']); ?>" >
                            <input type="hidden"  name="keyword" value="<?php echo ($_GET['keyword']); ?>" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form><?php endif; ?>
                </div>
            </div>


    </div>

    <script src="/Public/Admin/js/common.js?v"></script>
        <script src="/Public/Admin/js/content_addtop.js"></script>
    <script>

        $(function () {
            $(".productshow a").click(function () {
                var href = $(this).attr("href");
                window.location.href = href;
                return false;
            })
             $(".productshow").click(function () {
                 var obj = "#product_" + $(this).data("id");
                 $(obj).toggle();
             })
            $(".doout").click(function(){
                var url=$(this).data("url");
                if(confirm("确定将该订单作缺货处理吗？")){
                    omnipotent('selectid',url,'缺货处理',1,300,180);
                }
            })
        })
    </script>
</body>
</html>
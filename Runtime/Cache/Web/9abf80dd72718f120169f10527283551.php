<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/swiper.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/Public/Web/js/swiper.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript">
        $(function () {
            if ($(".cartSz").html() == 0) {
                $(".cartSz").hide();
            }

            //轮播图
            var mySwiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                lazyLoading: true,
                autoplay:5000,
                slidesPerView: 1,
                paginationClickable: true,
                spaceBetween: 30,
                loop: true,
                onLazyImageLoad: function (swiper, slide, image) {
                }
            });
        });
    </script>
</head>
<body>
    <div id="goodsItem" class="goodsItem">
        <div class="return" id="toolReturn"></div>
        <?php if($isatt == 0): ?><div class="dolike" id="toolDolike"></div>
       		<?php else: ?>
       			<div class="dolikeyes" id="toolDolike"></div><?php endif; ?>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($backimglist)): $i = 0; $__LIST__ = $backimglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img class="swiper-lazy" data-src="<?php echo ($vo); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="swiper-pagination" ></div>
        </div>
        <div class="cp_title">
            <div class="title"><?php echo ($data["title"]); ?></div>
            <!--<div class="marks">价格：￥<span id="nowpice"><?php echo ($data["nowprice"]); ?></span> </div>-->
            <?php if(($data[type]) == "0"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleQy.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "1"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleTh.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "2"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleTg.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "3"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleYg.png" /></div><?php endif; ?>
            <?php if(($data[type]) == "4"): ?><div class="marks"><img src="/Public/Web/images/goodsItemStyleCz.png" /></div><?php endif; ?>
        </div>
        <div class="cp_count">
            <div class="marks">
                <p><?php echo ($data["description"]); ?></p>
            </div>
            <div class="count">
                <div style="float: right; <?php echo ($data["isover"]); ?>">
                    <?php if($pnum == '0'): if($data["stock"] == '0'): ?><img src="/Public/Web/images/icon_Replenishment.png"  height="50px"/>
                        <?php else: ?>
                            <span class="goodsCountCut" style="display:none"></span>
                            <label class="goodsCountLab" style="display:none"><?php echo ($pnum); ?> </label>
                            <span class="goodsCountAdd" ></span><?php endif; ?>
                    <?php else: ?>
                        <?php if($data["stock"] == '0'): ?><img src="/Public/Web/images/icon_Replenishment.png"  height="50px"/>
                        <?php else: ?>
                            <span class="goodsCountCut"></span>
                            <label class="goodsCountLab"><?php echo ($pnum); ?> </label>
                            <span class="goodsCountAdd"></span><?php endif; endif; ?>
                </div>
            </div>
        </div>
        <div class="cp_price">
            <span class="priceNow">￥<?php echo ($data["nowprice"]); ?></span>
            <span class="priceOld">￥<?php echo ($data["oldprice"]); ?></span>
            <br />
            <?php if(($data[type]) == "3"): if($data["issellover"] == '1'): ?><span class="saletime">已过期</span>
                <?php else: ?>
                    <span class="saletime">出售时间: <?php echo ($data["selltime"]); ?></span><?php endif; endif; ?>
            <?php if(($data[type]) == "2"): if($data["isexpireover"] == '1'): ?><span class="saletime">已过期</span>
                <?php else: ?>
                    <span class="saletime">结束时间: <?php echo ($data["expiretime"]); ?></span><?php endif; endif; ?>
        </div>
        <!--<?php if(($data[type]) == "1"): ?><div class="cp_style th">
                <label>一般商品</label>
            </div><?php endif; ?>
        <?php if(($data[type]) == "2"): ?><div class="cp_style tg">
                <label>团购商品</label>
             </div><?php endif; ?>
        <?php if(($data[type]) == "3"): ?><div class="cp_style yg">
                 <label>预购商品</label>
             </div><?php endif; ?>
        <?php if(($data[type]) == "4"): ?><div class="cp_style cz">
                <label>称重商品</label>
             </div><?php endif; ?>-->
        <div class="cp_marks">
            <span class="title">品　　牌：</span><span class="marks"><?php echo ($data["brand"]); ?></span>
        </div>
        <div class="cp_marks">
            <span class="title">产品规格：</span><span class="marks"><?php echo ($data["standard"]); echo ($data["unit"]); ?></span>
        </div>
        <div class="cp_marks" id="todiscuss" product-id="<?php echo I('get.id');?>">
            <span class="title">商品评价：</span>
            <!--<?php if(($isdiscuss) == "1"): ?><a href="<?php echo U('Web/Product/discuss',array('id'=>I('get.id')));?>"><img src="/Public/Web/images/icon_arrow.png" style="float:right;padding-left:10px" /></a><?php endif; ?>-->
            <a href="<?php echo U('Web/Product/discuss',array('id'=>I('get.id')));?>"><img src="/Public/Web/images/icon_arrow.png" style="float:right;padding-left:10px" /></a>
            <div class="mystar">
                <div style="font-size: 12px; color: #808080; text-align: center"><?php echo ($data["percent"]); ?>%果友给了</div>
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
                <img src="/Public/Web/images/icon_star.png" />
            </div>
        </div>
        <div class="cp_marks">
            <span class="title">图文详情：</span>
        </div>
        <div class="cp_xqing">
            <?php if(is_array($imglist)): $i = 0; $__LIST__ = $imglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bgvo): $mod = ($i % 2 );++$i;?><img alt="" src="<?php echo ($bgvo); ?>" /><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div id="page_foot" class="page_foot" style="border-top: 1px #d6d6d6 solid;">
        <ul>
            <li class="total">
                <label class="mtips">购物车总金额：</label><label class="price">￥<?php echo ($carttotal); ?></label></li>
            <li class="nomal">
                <img class="cartBox" alt="" src="/Public/Web/images/icon_dayuan.png" />
                <img class="cartImg" alt="" src="/Public/Web/images/icon_shopping_cart.png" />
                <span class="cartSz"><?php echo ($cartcount); ?></span>
            </li>
        </ul>
    </div>
    <script type="text/javascript">
        $("#toolReturn").bind('click', function () {
            history.go(-1);
            //location.href = document.referrer;
        });

        $("#todiscuss").click(function () {
            var pid = $(this).attr('product-id');
            location.href = "<?php echo U('Web/Product/discuss');?>?id=" + pid;
        })

        $(".nomal").click(function () {
            location.href = "<?php echo U('Web/Cat/lists');?>";
        })

        $(".goodsCountAdd").click(function () {
            var uid='<?php echo ($user["id"]); ?>';
            if(uid==''){
                alert("请先登录");
                window.location.href="<?php echo U('Web/Member/login');?>";
            }
            var storeid='<?php echo ($data["storeid"]); ?>';
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) + 1);
            t.show();
            cat.show();
            $(".cartSz").show();

            var pnum = t.html();
            var pid = "<?php echo I('get.id');?>";
            $.post(
                "<?php echo U('Web/Cat/catupdate');?>", {
                    "num": pnum,
                    "pid": pid,
                    "storeid":storeid
                },
                function (response, status) {
                    if (status == "success") {
                        console.log(response);
                        if (response == 'stock') {
                            alert('库存不足！');
                            t.html(parseInt(t.html()) - 1);
                            return false;
                        }
                        $('.cartSz').html(response);
                        if ($('.cartSz').html() == '') {
                            location.reload();
                        }
                        //location.reload();
                    };
                },
                "json");
        })

        $(".goodsCountCut").click(function () {
            var uid='<?php echo ($user["id"]); ?>';
            if(uid==''){
                alert("请先登录");
                window.location.href="<?php echo U('Web/Member/login');?>";
            }
            var storeid='<?php echo ($data["storeid"]); ?>';
            var t = $(this).parent().find($('.goodsCountLab'));
            var cat = $(this).parent().find($('.goodsCountCut'));
            t.html(parseInt(t.html()) - 1);
            if (parseInt(t.html()) == 0) {
                cat.hide();
                t.hide();
            }
            if (parseInt(t.html()) < 0) {
                t.html(0);
            }
            var pnum = t.html();
            var pid = "<?php echo I('get.id');?>";
            $.post(
                "<?php echo U('Web/Cat/catupdate');?>", {
                    "num": pnum,
                    "pid": pid,
                    "storeid":storeid
                },
                function (response, status) {
                    if (status == "success") {
                        if (pnum <= "0" && $('.cartSz').html() == "0") {
                            $(".cartSz").hide();
                        }
                        else {
                            $('.cartSz').html(response);
                            if (response == null) {
                                $(".cartSz").hide();

                            } else {
                                $('.cartSz').html(response);
                                if ($('.cartSz').html() == '') {
                                    location.reload();
                                }
                            }
                        }
                        //location.reload();
                    };
                },
                "json");
        })


        //        $("#goodsItem").on("click",".dolike",function(){
        //        	$.post("<?php echo U('Web/Focus/add');?>",
        //                    { pid: "<?php echo I('get.id');?>"},
        //             	   function(data){
        //                  	   alert(1);
        //             	   });
        //    	});

        $("#goodsItem").on("click", '.dolike', function () {
            var uid='<?php echo ($uid); ?>';
            if(uid==''){
                alert("请先登录");
                window.location.href="<?php echo U('Web/Member/login');?>";
                return false;
            }
            $(this).removeClass("dolike").addClass("dolikeyes");
            $.post("<?php echo U('Web/Focus/add');?>",
                    { pid: "<?php echo I('get.id');?>" },
                   function (data) {
                   });
        });

        $("#goodsItem").on("click", '.dolikeyes', function () {
            var uid='<?php echo ($uid); ?>';
            if(uid==''){
                alert("请先登录");
                window.location.href="<?php echo U('Web/Member/login');?>";
                return false;
            }
            $(this).removeClass("dolikeyes").addClass("dolike");
            $.post("<?php echo U('Web/Focus/del');?>",
                    { pid: "<?php echo I('get.id');?>" },
                   function (data) {
                   });
        });
        //$(".dolike").click(function () {
        //    $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //    $.post("<?php echo U('Web/Focus/add');?>",
        //       { pid: "<?php echo I('get.id');?>" },
        //	   function (data) {
        //	       //            	   $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //	       //location.reload();
        //	       //        	     	$(this).cdolikeyes
        //	   });
        //});

        //$(".dolikeyes").click(function () {
        //    $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //    $.post("<?php echo U('Web/Focus/del');?>",
        //       { pid: "<?php echo I('get.id');?>" },
        //	   function (data) {
        //	       console.log(data);
        //	       //            	   $(".dolike").removeClass("dolike").addClass("dolikeyes");
        //	       //location.reload();
        //	       //        	     	$(this).cdolikeyes
        //	   });
        //});


    </script>
</body>
</html>
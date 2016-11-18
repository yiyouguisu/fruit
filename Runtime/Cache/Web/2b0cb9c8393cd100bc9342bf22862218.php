<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <style type="text/css">
        /*内容*/
        .page_info {
            width: 100%;
            height: auto;
            overflow: visible;
            position: absolute;
            left: 0px;
            top: 55px;
            padding-bottom:105px;
            z-index: 1;
        }
        /*底部*/
        .page_foot {
            width: 100%;
            height: 54px;
            color: #ffffff;
            background-color: #ffffff;
            text-align: center;
            position: fixed;
            left: 0px;
            bottom: 0px;
            z-index: 3;
        }
    </style>
</head>
<body>
    <div id="page_head" class="page_head">
        <!--<div class="l"><a class="return" href="javascript:history.go(-1)" target="_self"></a></div>-->
        <h1>购物车</h1>
        <div class="r"><a class="delete" id="selectdel" href="javascript:" target="_self"></a></div>
    </div>
    <div id="page_info" class="page_info">
        <div class="cartsList">
            <?php if(is_array($storelist['subdata'])): $i = 0; $__LIST__ = $storelist['subdata'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$store): $mod = ($i % 2 );++$i;?><div class="cartsShop" >
		             <div class="select"><span class="unSelect"></span><span class="storeid"></span></div>
		             <div class="jbinfo"><img src="/Public/Web/images/icon_shop.png" /><span style="margin-left:5px; margin-right:5px"><?php echo ($store["title"]); ?></span><!--<img src="/Public/Web/images/icon_arrow.png" width="10px" height="17px" />--></div>
		             <div class="delete"><span class="doDelete" id="<?php echo ($store["storeid"]); ?>" store-type="<?php echo ($store["type"]); ?>"></span></div>
		        </div>
		        <div class="shopitem">
			        <?php if(is_array($store['cartinfo'])): $i = 0; $__LIST__ = $store['cartinfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$items): $mod = ($i % 2 );++$i;?><div class="cartsItem">
				             <div class="select"><span class="unSelect"></span></div>
				             <div class="jbinfo">
				                <div class="goodsImage">
				                	<img alt="" src="<?php echo ($items["thumb"]); ?>" />
				                </div>
				                <div class="goodsInfos">
							            <?php if(($items[type]) == "0"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleQy.png" /><?php echo ($vo["title"]); ?></div><?php endif; ?>
				                		<?php if(($items[type]) == "1"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTh.png" /><?php echo ($items["title"]); ?></div><?php endif; ?>
				                   		<?php if(($items[type]) == "2"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleTg.png" /><?php echo ($items["title"]); ?></div><?php endif; ?>
				                   		<?php if(($items[type]) == "3"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleYg.png" /><?php echo ($items["title"]); ?></div><?php endif; ?>
				                   		<?php if(($items[type]) == "4"): ?><div class="title"><img alt="" src="/Public/Web/images/orderStyleCz.png" /><?php echo ($items["title"]); ?></div><?php endif; ?>
				                   <div class="marks"><?php echo ($items["description"]); ?></div>
				                   <div class="price" id="<?php echo ($items["id"]); ?>" goods_type="<?php echo ($items["type"]); ?>">
				                     	 ￥<span class="prs"><?php echo ($items["nowprice"]); ?>/<?php echo ($items["standard"]); echo ($items["unit"]); ?></span><span style="float:right;"><span class="goodsCountCut"></span><label class="goodsCountLab" id="<?php echo ($items["id"]); ?>" data-storeid="<?php echo ($items["storeid"]); ?>"><?php echo ($items["cartnum"]); ?></label><span class="goodsCountAdd"></span></span>
				                   </div>
				                </div>
				             </div>
				         </div><?php endforeach; endif; else: echo "" ;endif; ?>
		        </div>
                <div style="height:20px;background-color:#f3f3f3"></div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="cartsGsub">
        <!--          <div class="select"><span class="isSelect"></span><span class="mydotips" id="allcheck">全选</span></div>-->
        <div class="totals">合计：<span id="temptotal">￥0.00</span></div>
        <div class="button">
            <input id="toolSubmit" name="toolSubmit" type="button" value="结算" />
        </div>
    </div>
    <div style="height: 50px;">
    </div>
    </div>
    <div id="page_foot" class="page_foot">
       <ul>
          <li class="hover" onclick="window.location.href='<?php echo U('Web/Index/index');?>';"><img alt="" src="/Public/Web/images/icon_home_click.png" /><p>首页</p></li>
           <?php if($place == '选择店铺'): ?><li class="nomal" onclick="window.location.href='<?php echo U('Web/Product/pempty');?>';"><img alt="" src="/Public/Web/images/icon_productsClass_default.png" /><p>分类</p></li>
           <?php else: ?> 
                <li class="nomal" onclick="window.location.href='<?php echo U('Web/Product/lists');?>';"><img alt="" src="/Public/Web/images/icon_productsClass_default.png" /><p>分类</p></li><?php endif; ?>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Member/index');?>';"><img alt="" src="/Public/Web/images/icon_me_default.png" /><p>我的</p></li>
          <li class="nomal" onclick="window.location.href='<?php echo U('Web/Cat/lists');?>';">
             <img class="cartBox" alt="" src="/Public/Web/images/icon_dayuan.png" />
             <img class="cartImg" alt="" src="/Public/Web/images/icon_shopping_cart.png" />
             <span class="cartSz"><?php echo ($cartcount); ?></span>
          </li>
       </ul>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
      debug: false,
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone',
        'hideMenuItems'
      ]
  });
  wx.ready(function () {


  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareAppMessage({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {

      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareAppMessage','error');
      }
    });


  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareTimeline({
      title: '<?php echo ($share["content"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareTimeline','error');
      }
    });


  // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口

    wx.onMenuShareQQ({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQQ','error');
      }
    });

  
  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
    wx.onMenuShareWeibo({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareWeibo','error');
      }
    });


  // 2.5 监听“分享到QZone”按钮点击、自定义分享内容及分享接口

    wx.onMenuShareQZone({
      title: '<?php echo ($share["title"]); ?>',
      desc: '<?php echo ($share["content"]); ?>',
      link: '<?php echo ($share["link"]); ?>',
      imgUrl: '<?php echo ($share["image"]); ?>',
      trigger: function (res) {
      },
      complete: function (res) {
      },
      success: function (res) {
        alert('已分享');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','success');
      },
      cancel: function (res) {
        alert('已取消');
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','cancel');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
        ajax_share('<?php echo ($share["id"]); ?>','ShareQZone','error');
      }
    });
});

wx.error(function (res) {
  alert(res.errMsg);
});
function ajax_share(mid,sharetype,sharestatus){
    //$.ajax({
    //    type: "POST",
    //    url: "<?php echo U('Home/Index/ajax_share');?>",
    //    data: {'sharetype':sharetype,'sharestatus':sharestatus,'mid':mid},
    //    dataType: "json",
    //    success: function(data){
    //        if(sharestatus=='success'){
    //            window.location.href='/index.php/Index/order/mid/'+mid+'.html';
    //        }
                    
    //    }
    //});
}
</script>
</body>
<script type="text/javascript">
    $("#toolSubmit").click(function () {
        var data = '';
        var totalmoney = $('#temptotal').html();
        var flag = 0;
        var yuflag = 0;
        var qyflag = 0;
        var product_type = -1;
        totalmoney = totalmoney.substring(totalmoney.length, 1);
        if (totalmoney == "0") {
            alert("还没选择任何商品");
            return false;
        }

        $(".price").each(function () {
            if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
                num = $(this).parents('.cartsItem').find('.goodsCountLab').html();
                product_id = $(this).attr('id');
                product_type = $(this).attr('goods_type');
                data += '{"num":' + num + ',"product_id":' + product_id + ',"totalmoney":' + totalmoney + '},';


                if (product_type == '1' || product_type == '2' || product_type == '4') {
                    flag = 1;
                }
                if (product_type == '0') {
                    if (flag == 1 && qyflag==0) {
                        flag = 2;
                    } else {
                        flag = 1;
                        qyflag = 1;
                    }
                }
                //console.log(product_type);

                if (product_type == '3') {
                    if (yuflag == 1) {
                        yuflag = 2;
                    }
                    if (flag == 1) {
                        flag = 2;
                    } else {
                        flag = 1;
                        yuflag = 1;
                    }
                    
                }
            }
        });
        console.log(flag);
        console.log(yuflag);
        if (yuflag == 2) {
            alert('预购商品只能一次结算一个！');
            return false;
        }
        if (flag == 2) {
            alert('每次只能结算一种订单哦！');
            return false;
        }
        if (data != '') {
            //			alert(data);
            data = data.substring(0, data.length - 1)
            data = '{"Data":[' + data + ']}';
            $.post(
            "<?php echo U('Web/Cat/goodscache');?>", "cartinfo=" + data, function (response, status) {
                if (status == "success") {
                    //console.log(response);
                    location.href = "<?php echo U('Web/Cat/submits');?>";
                };
            },
            "json");
        }
        else {
            alert('请至少结算一件商品哦！');
            //return false;
        }
    });

    /***初始化***/
    $(document).ready(function () {
        setTotal();
    });

    /***商店批量删除***/
    $('.doDelete').click(function () {
        var storeid = $(this).attr('id');
        var storetype = $(this).attr('store-type');
        if (confirm("确认删除吗")) {
            $.post(
			"<?php echo U('Web/Cat/catdel');?>", {
			    "storeid": storeid,
			    "type": storetype
			},
			function (response, status) {
			    if (status == "success") {
			        if (response > 0)
			            $('.cartSz').html(response);
			        else
			            location.href = "<?php echo U('Web/Cat/empty');?>";
			    };
			},
			"json");
            location.reload();
        }
        else {
            return;
        }
    });

    /***产品批量删除***/
    //$('#selectdel').click(function () {
    //    var product_id = 0;
    //    var storeid = 0;
    //    var data = '';
    //    $(".price").each(function () {
    //        if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
    //            storeid = $(this).parent().parent().parent().parent().prev().find('.doDelete').attr('id');
    //            product_id = $(this).attr('id');
    //            data += '{"storeid":' + storeid + ',"product_id":' + product_id + '},';
    //        }
    //    });
    //    data = data.substring(0, data.length - 1)
    //    data = '{"Data":[' + data + ']}';
    //    console.log(data);
    //    if (confirm("确认删除吗")) {
    //        $.post(
	//		"<?php echo U('Web/Cat/catdel');?>",
    //        "cartinfo=" + data,
    //        function (response, status) {
    //            console.log(response);
    //            if (status == "success") {
    //                if (response > 0)
    //                    $('.cartSz').html(response);
    //                else
    //                    location.href = "<?php echo U('Web/Cat/empty');?>";
    //            };
    //        },
	//		"json");
    //        location.reload();
    //    }
    //    else {
    //        return;
    //    }
    //});

    //清空购物车
    $('#selectdel').click(function () {
        var product_id = 0;
        var storeid = 0;
        var data = '';
        $(".price").each(function () {
                storeid = $(this).parent().parent().parent().parent().prev().find('.doDelete').attr('id');
                product_id = $(this).attr('id');
                data += '{"storeid":' + storeid + ',"product_id":' + product_id + '},';
        });
        data = data.substring(0, data.length - 1)
        data = '{"Data":[' + data + ']}';
        console.log(data);
        if (confirm("确认删除吗")) {
            $.post(
			"<?php echo U('Web/Cat/catdel');?>",
            "cartinfo=" + data,
            function (response, status) {
                console.log(response);
                if (status == "success") {
                    if (response > 0)
                        $('.cartSz').html(response);
                    else
                        location.href = "<?php echo U('Web/Cat/empty');?>";
                };
            },
			"json");
            location.reload();
        }
        else {
            return;
        }
    });


    /***全选***/
    $("#allcheck").click(function () {
        $('.cartsList').find('.unSelect').addClass("isSelect");
    });
    /***加购物车***/
    $(".goodsCountAdd").click(function () {
        var t = $(this).parent().find($('.goodsCountLab'));
        t.html(parseInt(t.html()) + 1);

        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        /***计算总额***/
        setTotal();
        $.post(
			"<?php echo U('Web/Cat/catupdate');?>", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (response == 'stock') {
			            alert('库存不足！');
			            t.html(parseInt(t.html()) - 1);
			            return false;
			        }
			        $('.cartSz').html(response);
			        if ($('.cartSz').html() == '') {
			            location.reload();
			        }
			    };
			},
			"json");
    });

    /***减购物车***/
    $(".goodsCountCut").click(function () {
        var obj=$(this);
        var t = $(this).parent().find($('.goodsCountLab'));
        t.html(parseInt(t.html()) - 1);
        if (parseInt(t.html()) <= 0) {
            obj.parents(".cartsItem").remove();
        }

        var pnum = t.html();
        var pid = t.attr("id");
        var storeid = t.data("storeid");
        /***计算总额***/
        setTotal();

        $.post(
			"<?php echo U('Web/Cat/catupdate');?>", {
			    "num": pnum,
			    "pid": pid,
                "storeid":storeid
			},
			function (response, status) {
			    if (status == "success") {
			        if (pnum == "0" && $('.cartSz').html() == "0") {
                        location.reload();
			        }
			        else {
			            $('.cartSz').html(response);
			            if ($('.cartSz').html() == '') {
			                location.reload();
			            }
			        }
			    };
			},
			"json");
    });


    $(".select").on("click", ".isSelect", function () {
        $(this).removeClass('isSelect').addClass("unSelect");
        if ($(this).next().attr('id') == "allcheck")
            $(".cartsList").find('.isSelect').removeClass('isSelect').addClass("unSelect");
        if ($(this).next().attr('class') == "storeid")
            $(this).parent().parent().next().find('.isSelect').removeClass('isSelect').addClass("unSelect");
        setTotal();
    });


    $(".select").on("click", ".unSelect", function () {
        $(this).removeClass('unSelect').addClass("isSelect");
        if ($(this).next().attr('id') == "allcheck")
            $(".cartsList").find('.unSelect').removeClass('unSelect').addClass("isSelect");
        if ($(this).next().attr('class') == "storeid")
            $(this).parent().parent().next().find('.unSelect').removeClass('unSelect').addClass("isSelect");
        setTotal();
    });

    function setTotal() {
        var s = 0;
        var total = 0;
        $(".price").each(function () {
            if ($(this).parents(".cartsItem").find(".select span").hasClass("isSelect")) {
                s = parseFloat($(this).find('.goodsCountLab').html()) * parseFloat($(this).find('.prs').html());
                total += parseFloat(s);
            }
        });
        $("#temptotal").html('￥' + total.toFixed(2));
    }
</script>
</html>
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
<script type="text/javascript">
    $(function () {
        var role = '<?php echo ($User["role"]); ?>';
        if (role == 2) {
            playAudio("notification", "/mp3/waitpackage.mp3");
        } else if (role == 6) {
            playAudio("notification", "/mp3/packageerror.mp3");
        } else if (role == 3  || role ==1) {
            var ordertype = '<?php echo ($data["ordertype"]); ?>';
            var isserviceorder = '<?php echo ($data["isserviceorder"]); ?>';
            var isspeed = '<?php echo ($data["isspeed"]); ?>';
            if (isserviceorder == '1') {
                playAudio("notification", "/mp3/serviceorder.mp3");
            } else {
                if (isspeed == '1') {
                    playAudio("notification", "/mp3/speed.mp3");
                } else {
                    if (ordertype == '1') {
                        playAudio("notification", "/mp3/simple.mp3");
                    } else if (ordertype == '2') {
                        playAudio("notification", "/mp3/book.mp3");
                    } else if (ordertype == '3') {
                        playAudio("notification", "/mp3/company.mp3");
                    }
                }
            }
        }
        $("#close").click(function () {
            var orderid = '<?php echo ($data["orderid"]); ?>';
            window.parent.setopenstatus();
            if (role == 2) {
                var obj = 'packageOrdernoticestatus';
                var ps = $.cookie('packageOrdernoticestatus');
            }else if (role == 6) {
                var obj = 'packageerrorOrdernoticestatus';
                var ps = $.cookie('packageerrorOrdernoticestatus');
            } else if (role == 3) {
                var obj = 'Ordernoticestatus';
                var ps = $.cookie('Ordernoticestatus');
            } else if (role == 1) {
                var obj = 'Ordernoticestatus';
                var ps = $.cookie('Ordernoticestatus');
            }
                        
            if (ps) {
                if (ps.split(',').indexOf(orderid + '') != -1) {
                    ps.split(',').splice(ps.split(',').indexOf(orderid + ''), 1);
                    $.cookie(obj, ps);
                }
            }
            //if (role == 2) {
            //    var url = "<?php echo U('Admin/Package/ajax_setnoticestatus');?>";
            //} else if (role == 3) {
            //    var url = "<?php echo U('Admin/Order/ajax_setnoticestatus');?>";
            //} else if (role == 1) {
            //    var url = "<?php echo U('Admin/Order/ajax_setnoticestatus');?>";
            //}
            //var orderid = '<?php echo ($data["orderid"]); ?>';
            //$.ajax({
            //    type: "POST",
            //    url: url,
            //    data: { 'orderid': orderid },
            //    dataType: "json",
            //    success: function (data) {}
            //});
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭     
            //window.parent.art.dialog({ id: 'newordernotice' }).close();
        })
        $("#do").click(function () {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.title('派发订单', index);
            parent.layer.style(index, {
                width: '320px',
                height: '240px'
            });
            parent.layer.iframeSrc(index, "<?php echo U('Admin/Order/neworderdeal',array('orderid'=>$data['orderid']));?>");
            //omnipotent('deal', '/index.php/Admin/Order/deal/orderid/<?php echo ($data["orderid"]); ?>.html', '派发订单', 1, 300, 180)
        })
    })
    function playAudio(id, source) {
        var target = $("#audio-" + id);
        target.length || (target = $('<audio id="audio-' + id + '">\t\t\t\t\t\t<source src="' + source + '" type="audio/mpeg" />\t\t\t\t\t</audio>').appendTo("body")),
          target = target.get(0),
          $.browser.chrome && target.load(),
          target.play()
    }

</script>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <div class="h_a">
                有个订单总额￥<?php echo ($data["total"]); ?>,派送时间：<?php echo getordersendtime($data['orderid'],2);?>,派送地址为：<?php echo getarea($data['area']); echo ($data["address"]); ?>来了！！ 
            </div>
            <?php if(($User['role']) == "2"): ?><div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="close">好的</button>
                    </div>
                </div><?php endif; ?>  
            <?php if(($User['role']) == "6"): ?><div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="close">好的</button>
                    </div>
                </div><?php endif; ?>  
             <?php if(($User['role']) == "1"): ?><div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="close">好的</button>
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="do">派发</button>
                        <!-- <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="do">派发</button> -->
                    </div>
                </div><?php endif; ?>
            <?php if(($User['role']) == "3"): ?><div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="close">好的</button>
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="do">派发</button>
                        <!-- <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="do">派发</button> -->
                    </div>
                </div><?php endif; ?>
        </div>
    </div>
    <script src="/Public/Admin/js/common.js?v"></script>
    <script src="/Public/Admin/js/content_addtop.js"></script>
</body>
</html>
<include file="Common:Head" />
<script type="text/javascript">
    $(function () {
        var role = '{$User.role}';
        if (role == 2) {
            playAudio("notification", "/mp3/waitpackage.mp3");
        } else if (role == 6) {
            playAudio("notification", "/mp3/packageerror.mp3");
        } else if (role == 3  || role ==1) {
            var ordertype = '{$data.ordertype}';
            var isserviceorder = '{$data.isserviceorder}';
            var isspeed = '{$data.isspeed}';
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
            var orderid = '{$data.orderid}';
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
            //    var url = "{:U('Admin/Package/ajax_setnoticestatus')}";
            //} else if (role == 3) {
            //    var url = "{:U('Admin/Order/ajax_setnoticestatus')}";
            //} else if (role == 1) {
            //    var url = "{:U('Admin/Order/ajax_setnoticestatus')}";
            //}
            //var orderid = '{$data.orderid}';
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
            parent.layer.iframeSrc(index, "{:U('Admin/Order/neworderdeal',array('orderid'=>$data['orderid']))}");
            //omnipotent('deal', '/index.php/Admin/Order/deal/orderid/{$data.orderid}.html', '派发订单', 1, 300, 180)
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
                有个订单总额￥{$data.total},派送时间：{:getordersendtime($data['orderid'],2)},派送地址为：{:getarea($data['area'])}{$data.address}来了！！ 
            </div>
            <eq name="User['role']" value="2">
                <div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="close">好的</button>
                    </div>
                </div>
            </eq>  
            <eq name="User['role']" value="6">
                <div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="close">好的</button>
                    </div>
                </div>
            </eq>  
             <eq name="User['role']" value="1">
                <div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="close">好的</button>
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="do">派发</button>
                        <!-- <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="do">派发</button> -->
                    </div>
                </div>
            </eq>
            <eq name="User['role']" value="3">
                <div class="btn_wrap" style="bottom: 15px;">
                    <div class="btn_wrap_pd" style="padding: 0;text-align: center;">
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="close">好的</button>
                        <button class="btn btn_submit" type="button" style="width: 155px; margin-right: 0;" id="do">派发</button>
                        <!-- <button class="btn btn_submit" type="button" style="width: 310px; margin-right: 0;" id="do">派发</button> -->
                    </div>
                </div>
            </eq>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
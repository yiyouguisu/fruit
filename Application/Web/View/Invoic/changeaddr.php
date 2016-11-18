<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/weixin.jquery.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script>
        $(function () {
            //$(".infoDetail").on('click', '.isSelect', function () {
                
            //})

            $(".infoDetail").on('click', '.unSelect', function () {
                //alert(1);
                $(this).parent().parent().parent().find('.isSelect').removeClass('isSelect').addClass('unSelect');
                $(this).removeClass('unSelect').addClass('isSelect');
                //if ($(this).parent().parent().index() == "3") {
                //    $("#billtype").val('2')
                //}
                //else if ($(this).parent().parent().index() == "2") {

                //}
            })
        })
    </script>
</head>
<body>
    <form action="{:U('Web/Invoic/billapply')}" method="post">
        <div id="page_head" class="page_head">
            <div class="l"><a class="return" href="javascript:history.go(-1)" target="_self"></a></div>
            <h1>确认地址</h1>
        </div>
        <div id="page_info" class="page_info">
            <div class="orderInfo">
                <div class="infoDetail" style="padding-top: 10px;">
                    <div class="addrDetail">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <td style="width: 32px;">&nbsp;</td>
                                    <td style="width: auto;">&nbsp;</td>
                                    <td style="width: 50px;">&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 6px;">
                                        <span class="name">{$address.name}</span>
                                        <eq name="address['type']" value="1">
                       				<span class="type">公司</span>
                       			</eq>
                                        <eq name="address['type']" value="2">
                       				<span class="type">家</span>
                       			</eq>
                                        <eq name="address['type']" value="3">
                       				<span class="type">其他</span>
                       			</eq>
                                    </td>
                                    <td rowspan="3" style="padding-bottom: 6px; text-align: right; vertical-align: middle;">
                                        <a href="{:U('Web/Address/index',array('fapiaoid'=>$address['id']))}">
                                            <img alt="" src="__IMG__/icon_arrow.png" id="changeaddress" />
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="__IMG__/icon_AddDetail.png" style="margin-left: 0px;" /></td>
                                    <td>{$address.province}{$address.city}{$address.areas}<br />
                                        {$address.address}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="__IMG__/icon_AddMobile.png" style="margin-left: 3px;" /></td>
                                    <td>{$address.tel}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="addressid" value="{$address.id}">
                    <div class="item" style="margin-top: 20px">
                        <div class="check" style="padding-right: 10px; float: left"><span class="isSelect"></span></div>
                        <div>个人</div>
                    </div>
                    <div class="item" style="margin-top: 20px">
                        <div class="check" style="padding-right: 10px; float: left"><span class="unSelect"></span></div>
                        <div><span>公司</span>
                            <input type="text" name="billtitle" id="companyname" placeholder="请输入公司名称" /></div>
                    </div>
                    <input type="hidden" id="billtype" name="billtype" value="1" />
                </div>
            </div>
        </div>
        <div class="nrongDiv">
            <input id="toolSubmit" name="toolSubmit" type="submit" value="确定" class="toolSubmit" />
        </div>
    </form>
    <script type="text/javascript">
        $("#changeaddress").click(function () {
            location.href = "{:U('Web/Address/index')}";
        });
    </script>
</body>
</html>

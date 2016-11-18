<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery.min.js"></script>
    <script type="text/javascript" src="__JS__/weixin.global.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=61dG5IBV8LakyGZPhDNQAAT1DY9oFjRY"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
    <script src="http://c.cnzz.com/core.php"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            
            var lat = '';
            var lng = '';
            if ('{$lat}'!='' && '{$lng}'!='') {
                lat = '{$lat}';
                lng = '{$lng}';
            }
            var pt = new BMap.Point(lng,lat)
            var geoc = new BMap.Geocoder();
            geoc.getLocation(pt, function (rs) {
                var addComp = rs.addressComponents;
                $("#address").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                //$("#tel").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
            });

            $("#addrdel").click(function () {
                var addressid = 0;
                if ('{:I('get.id')}' != '') {
                    addressid = '{:I('get.id')}';
                }
                $.ajax({
                    type: "GET",
                    url: "{:U('Web/Address/address_del')}",
                    data: { 'addressid':  addressid},
                    dataType: "json",
                    success: function (data) {
                        window.location.href = document.referrer;
                    }
                });
            });

            
            if ($("#areaID option").val() == '')
                $("#areaID").hide();

            $("#toolSubmit").click(function () {
                if ($("#name").val() == '') {
                    alert('请填写收货人');
                    return false;
                }
                else if ($("#provinceID option:selected").val() == '') {
                    alert('请选择区域');
                    return false;
                }
                else if ($("#address").val() == '') {
                    alert('请填写详细地址');
                    return false;
                }
                else if ($("#tel").val() == '') {
                    alert('请填写联系方式');
                    return false;
                }
                if ($("#addtype option:selected").val() == '') {
                    alert('请填写地址类型');
                    return false;
                }
            });

            $(".input").on("click", ".switchClose", function () {
                $(this).removeClass('switchClose').addClass("switchDakai");
                $("#isdefault").val(1);
            });
            $(".input").on("click", ".switchDakai", function () {
                $(this).removeClass('switchDakai').addClass("switchClose");
                $("#isdefault").val(0);
            });

            //省份-城市
            $("#provinceID").change(function () {
                //清空城市下拉框中的内容，除提示信息外
                $("#cityID option:first").remove();
                $("#areaID option:first").remove();
                $("#areaID").html("");
                $("#cityID").html("");
                //获取选中的省份
                var url = "{:U('Web/Address/getchildren')}";
                var thisid = $('#provinceID option:selected').val();
                var sendData = { "id": thisid };
                $.post(url, sendData, function (response, status) {
                    //解析字符串
                    //                console.log(response);
                    var jsonString = eval("(" + response + ")");
                    //                console.log(jsonString.length);
                    var size = jsonString.length;
                    for (var i = 0; i < size; i++) {
                        //获取每一个城市
                        //                    console.log(jsonString[0].name);
                        var city = jsonString[i].name;
                        var cityid = jsonString[i].id;
                        var $option = $("<option value=" + cityid + ">" + city + "</option>")
                        $("#cityID").append($option);
                    }
                });
            });
            //城市-区域
            $("#cityID").change(function () {
                $("#areaID option:first").remove();
                $("#areaID").show();
                var url = "{:U('Web/Address/getchildren')}";
                var thisid = $('#cityID option:selected').val();
                var sendData = { "id": thisid };
                $.post(url, sendData, function (response, status) {
                    try {
                        var jsonString = eval("(" + response + ")");
                        var size = jsonString.length;
                        for (var i = 0; i < size; i++) {

                            var city = jsonString[i].name;
                            var cityid = jsonString[i].id;
                            var $option = $("<option value=" + cityid + ">" + city + "</option>")
                            $("#areaID").append($option);
                        }
                    }
                    catch (e) {
                        $("#areaID").hide();
                    }
                });
            });

            $(".r").click(function () {
                $("#toolSubmit").click();
            })

            $("#addresstype").click(function () {
                //$("#tsex").click();
                open($("#addtype"));
            })
            $("#addresstype").change(function () {
                $("#addresstype").find('span').html($(this).find("option:selected").text());
            })

            

        });

        function open(elem) {
            if (document.createEvent) {
                var e = document.createEvent("MouseEvents");
                e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                elem[0].dispatchEvent(e);
            } else if (element.fireEvent) {
                elem[0].fireEvent("onmousedown");
            }
        }
        function tomap(){
            location.href = "{:U('Web/Address/map')}";
        }

        
    </script>
</head>
<body>
    <eq name="addurl" value="1">
           <form method="post"  action="{:U('Web/Address/edit')}?addressid={$orderid}" >
    </eq>
    <eq name="addurl" value="0">
           <form method="post"  action="{:U('Web/Address/edit')}" >
     </eq>
    <eq name="addurl" value="2">
           <form method="post"  action="{:U('Web/Address/edit')}?fapiaoid={$fapiaoid}" >
     </eq>

    <input type="hidden" value="{$getid}" name="getid">
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
        <if condition="I('get.id') eq 0">
            <h1>创建收货地址</h1>
        <else />
            <h1>修改收货地址</h1>
        </if>
        <div class="r">保存</div>
    </div>
    <div id="page_info" class="page_info" style="background-color:#ffffff;margin-top:55px">
        <div style="height: 20px; background-color: #f3f3f3"></div>
        <div class="inputDiv">
            <div class="title">收货人</div>
            <div class="input">
                <input name="name" id="name" type="text" style="text-align: right;" value="{$data.name}" />
            </div>
        </div>
        <div class="inputDiv">
            <div class="title">区域选择</div>
            <div class="input">
                <img class="turn" alt="" src="__IMG__/icon_arrow.png" style="float: right; margin-top: 10px;" />
                <select id="provinceID" name="province">
                    <if condition="$data.type eq ''">
                    <option value="">选择省份</option>
                <else />
                    <option value="{$data.provinceid}">{$data.province}</option>
                </if>
                    <volist name="provincelist" id="area">
                    <option value="{$area.id}">{$area.name}</option>
                </volist>
                </select>
                <select id="cityID" name="city">
                    <option value="{$data.cityid}">{$data.city}</option>
                </select>
                <select id="areaID" name="areas">
                    <option value="{$data.areasid}">{$data.areas}</option>
                </select>
            </div>
        </div>
        <div class="inputDiv">
            <div class="title">详细地址</div>
            <div class="input">
                <input name="address" id="address" type="text" style="text-align: right; width: 80%" value="{$data.address}" />
                <a href="{:U('Web/Address/map')}"><img src="__IMG__/icon_Locate.png" style="width: 24px"/></a>
            </div>
        </div>
        <div class="inputDiv">
            <div class="title">联系方式</div>
            <div class="input">
                <input name="tel" id="tel" type="text" style="text-align: right;" value="{$data.tel}" />
            </div>
        </div>
        <div class="inputDiv" id="chooseAddressType">
            <div class="title">地址类型</div>
            <div class="input" id="addresstype">
                <select id="addtype" name="type" style="color: #ffffff; opacity: 0.01">
                    <!-- <if condition="$data.type eq 1">
                    <option value="1" selected>公司</option>
                    <option value="2">家</option>
                    <option value="3">其他</option>
                <elseif condition="$data.type eq 2"/>
                    <option value="1">公司</option>
                    <option value="2" selected>家</option>
                    <option value="3">其他</option>
                <elseif condition="$data.type eq 3"/>
                    <option value="1">公司</option>
                    <option value="2">家</option>
                    <option value="3" selected>其他</option>
                <else />
                    <option value="1" selected>公司</option>
                    <option value="2">家</option>
                    <option value="3">其他</option>     
                </if>-->
                    <option value="" selected>请选择</option>
                    <option value="1">公司</option>
                    <option value="2">家</option>
                    <option value="3">其他</option>
                </select>
                <eq name="data[type]" value="">
                    <span>请选择</span>
                </eq>
                <eq name="data[type]" value="1">
                    <span>公司</span>
                </eq>
                <eq name="data[type]" value="2">
                    <span>家</span>
                </eq>
                <eq name="data[type]" value="3">
                    <span>其他</span>
                </eq>
            </div>
        </div>
        <div class="inputDiv" style="border:0">
            <div class="title">默认地址</div>
            <div class="input">
                <if condition="$data.isdefault eq 1">
                 <span class="switchDakai" style="vertical-align:middle;"></span>
                 <input name="isdefault" id="isdefault" type="hidden" value="1">
             <elseif condition="$data.isdefault eq 0"/>
                 <span class="switchClose" style="vertical-align:middle;"></span>
                 <input name="isdefault" id="isdefault" type="hidden" value="0">
             <else /> 
                 <span class="switchClose" style="vertical-align:middle;"></span>
                 <input name="isdefault" id="isdefault" type="hidden" value="0">
             </if>
            </div>
        </div>

    </div>
    <div id="page_foot" class="page_foot">
        <div style="height: 100px; background-color: #f3f3f3"></div>
        <div style="width: auto; padding: 0px 10px;">
            <if condition="I('get.id') neq 0">
                <input id="addrdel" name="toolSubmit" type="buttom" value="删除" class="toolSubmit"  />
            </if>
            <input id="toolSubmit" name="toolSubmit" type="submit" value="保存" class="toolSubmit" style="display: none" />
        </div>
    </div>
    </form>
    <script type="text/javascript">
        $("#address").click(function(){
            var lat = '';
            var lng = '';
            if ('{$lat}'!='' && '{$lng}'!='') {
                lat = '{$lat}';
                lng = '{$lng}';
            }
            var pt = new BMap.Point(lng,lat)
            var geoc = new BMap.Geocoder();
            geoc.getLocation(pt, function (rs) {
                var addComp = rs.addressComponents;
                $("#address").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                //$("#tel").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
            });
        })

        $("#tel").click(function(){
            var lat = '';
            var lng = '';
            if ('{$lat}'!='' && '{$lng}'!='') {
                lat = '{$lat}';
                lng = '{$lng}';
            }
            var pt = new BMap.Point(lng,lat)
            var geoc = new BMap.Geocoder();
            geoc.getLocation(pt, function (rs) {
                var addComp = rs.addressComponents;
                $("#address").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                //$("#tel").val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
            });
        })
    </script>
</body>
</html>

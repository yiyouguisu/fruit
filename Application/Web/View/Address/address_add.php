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
    <script>
    $(function(){
        getchildren(0,true);
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
    })
     
    function getchildren(a,b) {
        $.ajax({
            url: "{:U('Web/Address/getchildren')}",
            async: false,
            data: { id: a },
            success: function (data) {
                data=eval("("+data+")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if(b)
                    {
                        ahtml += "<option value=''>--请选择--</option>";
                    }
                    for (var i = 0; i < data.length; i++) {
                        ahtml += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                    }
                    ahtml += "</select>";
                    $(".jgbox").append(ahtml);
                }
            }
        });
                    getval();
    }
    function getval()
    {
        var vals="";
        $(".jgbox select").each(function(){
            var val=$(this).val();
            if(val!=null&&val!="")
            {
                vals+=',';
                vals+=val;
            }
        });
        if(vals!="")
        {
            vals=vals.substr(1);        
            $("#area").val(vals);
        }
    }
    
  
</script>
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

            
            
            $("#toolSubmit").click(function () {
                if ($("#name").val() == '') {
                    alert('请填写收货人');
                    return false;
                }
                else if ($("#ara").val() == '') {
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
                if ($("#addtype option:selected").text() == '请选择') {
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
            location.href = "{:U('Web/Address/testmap')}";
        }

        
    </script>
</head>
<body>
    <form method="post"  action="{:U('Web/Address/address_add')}" >
    <div id="page_head" class="page_head">
        <div class="l"><a id="toolReturn" class="return" href="javascript:history.go(-1)" target="_self"></a></div>
         <h1>创建收货地址</h1>
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
                <span class="jgbox">

                </span>
                <input name="area" id="area" type="hidden" value="" />
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
                    <option value="0" selected>请选择</option>
                    <option value="1">公司</option>
                    <option value="2">家</option>
                    <option value="3">其他</option>
                </select>
                    <span>请选择</span>
                
            </div>
        </div>
        <div class="inputDiv" style="border:0">
            <div class="title">默认地址</div>
            <div class="input">
                 <span class="switchClose" style="vertical-align:middle;"></span>
                 <input name="isdefault" id="isdefault" type="hidden" value="0">
             </if>
            </div>
        </div>

    </div>
    <div id="page_foot" class="page_foot">
        <div style="height: 100px; background-color: #f3f3f3"></div>
        <div style="width: auto; padding: 0px 10px;">
            <input name="lat" id="lat" type="hidden" value="{$lat}" />
            <input name="lng" id="lng" type="hidden" value="{$lng}" />
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

<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=61dG5IBV8LakyGZPhDNQAAT1DY9oFjRY"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
    <script src="http://c.cnzz.com/core.php"></script>
    <script>
        wxglobal.SetSize(function () {
            $("#suggestId").width($(window).width() - 90 + 'px');
        });
    </script>
    <style type="text/css">
        body, html {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: "微软雅黑";
            font-size: 14px;
        }

        #l-map {
            height: 100%;
            width: 100%;
        }

        #r-result {
            width: 100%;
        }
    </style>

</head>
<body>
    <div id="page_head" class="page_head">
        <div class="l">
            <a class="return" href="javascript:;" target="_self"></a>
        </div>
        <h1>地图</h1>
        <input id="suggestId" name="htmlKeyword" type="text"  style="    margin-top: -69px;"/>
        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
        <div class="r">确定</div>
    </div>
    <img src="/Public/Web/images/icon_Locate_minddle.png" style="position: absolute; top: 25%; left: 45%; width: 25px; z-index: 99" />
    <img src="/Public/Web/images/first_location.png" style="position: absolute; top: 40%; left: 85%; width: 50px; z-index: 99" onclick="toinit()" />
    
    <div id="l-map"></div>
    <div class="maptop"><span id="addtext"></span></div>
    <div class="tangram-suggestion-main" id="result">
      
    </div>
    <style type="text/css">
    .tangram-suggestion-main,.tangram-suggestion{
      display: none;
          z-index: 99;
    width: 100% !important;
    left: 0!important;
    height: 100% !important;
    background-color: #333;
    opacity: 0.9;
    position: absolute;
    top: 50px;
    }
    .map_ul{
      height: 50%;
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #fff;
    overflow-y: scroll;
    }
     .map_list { padding:1rem 0rem ; margin:0 2.5% 0 3rem;border-bottom:1px solid #ccc;color:#636363} 
     .map_a {     font-size: 1rem;
    position: relative;
    color: #fff;} 
    .map_a span{
          color: #666;
    }
     .map_img { position:absolute;top:0;left:-2rem;}
     .map_img img { width:20px;}
     .map_b { font-size:1rem;} 
  </style>

  <div class="map_ul">
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="map_list" data-lat="<?php echo ($vo["point"]["y"]); ?>" data-lng="<?php echo ($vo["point"]["x"]); ?>" data-name="<?php echo ($vo["name"]); ?>">
          <div class="map_a">
          	<?php if(($key) == "0"): ?><div class="map_img"><img src="/Public/Web/images/img.png"></div><?php endif; ?>
	          <span><?php echo ($vo["name"]); ?></span>
	      </div>
          <div class="map_b"><?php echo ($vo["addr"]); ?></div>
     </div><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
</body>
</html>
<script type="text/javascript">
var address="";
    $(function () {
      $("#suggestId").keyup(function(){
        var keyword=$(this).val();
        if(keyword!=''){
          $.ajax({
               type: "POST",
               url: "<?php echo U('Web/Address/getqueryinfo');?>",
               data: { 'keyword': keyword },
               dataType: "json",
               success: function (data) {
                  $("#result").html(data);
                  $("#result").show();
               }
           });
        }
        
        
      })
        var map = new BMap.Map("l-map", { minZoom: 4, maxZoom: 20 });
        //var point = new BMap.Point(116.331398, 39.897445);
        //map.centerAndZoom(point, 12);

        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function (r) {
            if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                var mk = new BMap.Marker(r.point);
                //map.addOverlay(mk);
                map.panTo(r.point);
                map.centerAndZoom(r.point, 18);
                map.enableScrollWheelZoom(true);
            }
            else {
                alert('failed' + this.getStatus());
            }
        }, { enableHighAccuracy: true })

        map.addEventListener("tilesloaded", function () {
           var pt = map.getBounds().getCenter();
           $.ajax({
               type: "POST",
               url: "<?php echo U('Web/Address/getadd');?>",
               data: { 'lng': pt.lng, 'lat': pt.lat },
               dataType: "json",
               success: function (data) {
                   console.log(data);
                   $(".map_ul").html(data);
                   var geoc = new BMap.Geocoder();
                   geoc.getLocation(pt, function (rs) {
                       var addComp = rs.addressComponents;
                       address=addComp.street + addComp.streetNumber;
                       $("#addtext").html(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                   });;
               }
           });
        });

        // $('.r').click(function () {
        //     window.location.href = document.referrer;
        // })
        $(".r").click(function () {
            var addtext = address;

            $(window.parent.document).find("#address").val(addtext);
            window.parent.CloseIframe();
        })
        $(".return").click(function(){
            window.parent.CloseIframe();
        })

        $("div.map_ul,#result").delegate(".map_list","click",function(){
          var lat=$(this).data("lat");
          var lng=$(this).data("lng");
          var name=$(this).data("name");
          $.ajax({
               type: "POST",
               url: "<?php echo U('Web/Address/getadd');?>",
               data: { 'lng': lng, 'lat': lat },
               dataType: "json",
               success: function (data) {

                   
                       $(window.parent.document).find("#address").val(name);
                       window.parent.CloseIframe();
                   
               }
           });
        })

        // 百度地图API功能
        function G(id) {
           return document.getElementById(id);
        }

        // var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
        //    {
        //        "input": "suggestId"
        //    , "location": map
        //    });

        // ac.addEventListener("onhighlight", function (e) {  //鼠标放在下拉列表上的事件
        //    var str = "";
        //    var _value = e.fromitem.value;
        //    var value = "";
        //    if (e.fromitem.index > -1) {
        //        value = _value.province + _value.city + _value.district + _value.street + _value.business;
        //    }
        //    str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

        //    value = "";
        //    if (e.toitem.index > -1) {
        //        _value = e.toitem.value;
        //        value = _value.province + _value.city + _value.district + _value.street + _value.business;
        //    }
        //    str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        //    G("searchResultPanel").innerHTML = str;
        // });

        // var myValue;
        // ac.addEventListener("onconfirm", function (e) {    //鼠标点击下拉列表后的事件
        //    var _value = e.item.value;
        //    myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
        //    G("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

        //    setPlace();
        // });

        function setPlace() {
           map.clearOverlays();    //清除地图上所有覆盖物
           function myFun() {
               var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
               map.centerAndZoom(pp, 18);
               map.addOverlay(new BMap.Marker(pp));    //添加标注
           }
           var local = new BMap.LocalSearch(map, { //智能搜索
               onSearchComplete: myFun
           });
           local.search(myValue);
        }

        map.addEventListener("click", showInfo);

        var geoc = new BMap.Geocoder();
        function showInfo(e) {
           //alert(e.point.lng + ", " + e.point.lat);
           mk = new BMap.Marker(new BMap.Point(e.point.lng, e.point.lat));
           add_overlay();
           var pt = e.point;
           geoc.getLocation(pt, function (rs) {
               var addComp = rs.addressComponents;
               //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
           });
        }

        //添加覆盖物
        function add_overlay() {
           map.clearOverlays();
           map.addOverlay(mk);            //增加点
        }

    })


    function toinit() {
        var map = new BMap.Map("l-map", { minZoom: 4, maxZoom: 20 });

        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function (r) {
            if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                var mk = new BMap.Marker(r.point);
                //map.addOverlay(mk);
                map.panTo(r.point);
                map.centerAndZoom(r.point, 18);
                map.enableScrollWheelZoom(true);
            }
            else {
                alert('failed' + this.getStatus());
            }
        }, { enableHighAccuracy: true })
    }
</script>
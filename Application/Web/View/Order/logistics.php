<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=61dG5IBV8LakyGZPhDNQAAT1DY9oFjRY"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
    <script src="http://c.cnzz.com/core.php"></script>
    <style type="text/css">
        body, html, #allmap {
            width: 100%;
            height: 100%;
            overflow: hidden;
            margin: 0;
            font-family: "微软雅黑";
        }

        #l-map {
            height: 100%;
            width: 78%;
            float: left;
            border-right: 2px solid #bcbcbc;
        }

        #r-result {
            height: 100%;
            width: 20%;
            float: left;
        }
    </style>
    <script type="text/javascript">
        function tolist() {
            location.href = "{:U('Web/Order/index')}?status=waitconfirm";
        }
    </script>
</head>
<body>

    <div id="page_head" class="page_head">
        <div class="l">
            <a class="return" href="javascript:history.go(-1)" target="_self"></a>
        </div>
        <h1>地图模式</h1>
        <div class="r" onclick="tolist()">列表模式</div>
    </div>
    <div id="allmap"></div>

</body>
</html>
<script type="text/javascript">
    $(function () {
        $.ajax({
            url: "{:U('Web/Order/getlngandlat')}",
            success: function (data) {
                console.log(data);
                // 百度地图API功能
                var map = new BMap.Map("allmap");
                //var point = new BMap.Point(116.331398,39.897445);
                //map.centerAndZoom(point,18);
                // 编写自定义函数,创建标注
                function addMarker(point) {
                    var marker = new BMap.Marker(point);
                    map.addOverlay(marker);
                    marker.setAnimation(BMAP_ANIMATION_BOUNCE);
                }

                var geolocation = new BMap.Geolocation();
                geolocation.getCurrentPosition(function (r) {
                    if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                        console.log(r.point);
                        //point = new BMap.Point(data[i].order_lng, data[i].order_lat);
                        //console.log(r.point.lng);
                        point = new BMap.Point(r.point.lng, r.point.lat);
                        //addMarker(r.point);
                    }
                    else {
                        alert('failed' + this.getStatus());
                    }
                }, { enableHighAccuracy: true })

                if (data != null && data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].order_lng != '' && data[i].order_lat != '')
                        {
                            point = new BMap.Point(data[i].order_lng, data[i].order_lat);
                            addMarker(point);
                        }
                        
                    }
                }
                map.centerAndZoom(point, 15);
            }
        });
    })
</script>

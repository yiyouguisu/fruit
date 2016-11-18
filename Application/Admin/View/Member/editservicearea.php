<include file="Common:Head" />
<style>
#allmap {width: 100%; height:800px; overflow: hidden;}
dl,dt,dd,ul,li{
    margin:0;
    padding:0;
    list-style:none;
}
p{font-size:12px;}
dt{
    font-size:14px;
    font-family:"微软雅黑";
    font-weight:bold;
    border-bottom:1px dotted #000;
    padding:5px 0 5px 5px;
    margin:5px 0;
}
dd{
    padding:5px 0 0 5px;
}
li{
    line-height:28px;
}
.red{color: red;}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=5E5EE28a7615536d1ffe2ce2a3667859"></script>
<!--加载鼠标绘制工具-->
<script type="text/javascript" src="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Member/editservicearea')}">
                <div class="h_a">跑腿配送区域划分</div>
                <div style="width:100%;height:800px;">
                    <div id="allmap" style="overflow:hidden;zoom:1;position:relative;">  
                        <div id="map" style="height:100%;-webkit-transition: all 0.5s ease-in-out;transition: all 0.5s ease-in-out;"></div>
                        <div id="showPanelBtn" style="position:absolute;font-size:14px;top:50%;margin-top:-95px;right:0px;width:20px;padding:10px 10px;color:#999;cursor:pointer;text-align:center;height:170px;rgba(255,255,255,0.9);-webkit-transition:  all 0.5s ease-in-out;transition: all 0.5s ease-in-out;font-family:'微软雅黑';font-weight:bold;">编辑多边形<br/>&lt;</div>
                        <div id="panelWrap" style="width:0px;position:absolute;top:0px;right:0px;height:100%;overflow:auto;-webkit-transition: all 0.5s ease-in-out;transition: all 0.5s ease-in-out;">
                            <div style="width:20px;height:200px;margin:-100px 0 0 -10px;color:#999;position:absolute;opacity:0.5;top:50%;left:50%;" id="showOverlayInfo">此处用于展示覆盖物信息</div>
                            <div id="panel" style="position:absolute;"></div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="uid" value="{$data.uid}" />
                        <input type="hidden" name="point" value="{$data.point}" />
                        <input type="hidden" name="pointcount" value="{$data.pointcount|default='0'}" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script type="text/javascript">
    var bmap = {
        status: false,
        map: '',
        point: '',
        overlays: [],
        overlaysCache: [],
        myPolygon: '',
        myOverlay: [],
        drawingManager: '',
        styleOptions: {
            strokeColor:"red",      //边线颜色。
            fillColor:"red",        //填充颜色。当参数为空时，圆形将没有填充效果。
            strokeWeight: 3,        //边线的宽度，以像素为单位。
            strokeOpacity: 0.8,     //边线透明度，取值范围0 - 1。
            fillOpacity: 0.3,       //填充的透明度，取值范围0 - 1。
            strokeStyle: 'solid'    //边线的样式，solid或dashed。
        },
        /**
         * 实例化
         */
        init: function(){
            if(this.status){
                return;
            }
            this.status = true;
            this.map = new BMap.Map('map');
            this.point = new BMap.Point(116.307852,40.057031);
            var map = this.map;
            var styleOptions = this.styleOptions;
            map.centerAndZoom(this.point, 16);
            map.enableScrollWheelZoom();
            //实例化鼠标绘制工具
            this.drawingManager = new BMapLib.DrawingManager(map, {
                isOpen: false, //是否开启绘制模式
                enableDrawingTool: false, //是否显示工具栏
                drawingToolOptions: {
                    anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
                    offset: new BMap.Size(5, 5), //偏离值
                    scale: 0.8 //工具栏缩放比例
                },
                polygonOptions: styleOptions, //多边形的样式
            });
            
            //添加鼠标绘制工具监听事件，用于获取绘制结果
            this.drawingManager.addEventListener('overlaycomplete', bmap.overlaycomplete);
            /*加载一个已有的多边形*/
            if (this.myOverlay!='') {
                this.drawingManager.close();
                this.loadMyOverlay();
            }else{
                this.drawingManager.setDrawingMode(BMAP_DRAWING_POLYGON);
                this.drawingManager.open();
            }
            map.addEventListener("rightclick",function(e){
                alert(e.point.lng + "," + e.point.lat);
            });
        },
        loadMyOverlay: function(){
            var map = this.map;
            this.clearAll();
            map.centerAndZoom(this.point, 11);
            myPolygon = new BMap.Polygon(this.myOverlay, this.styleOptions);
            this.myPolygon = myPolygon;
            try{
                myPolygon.enableEditing();
            }catch(e){};
            myPolygon.addEventListener("lineupdate",function(e){
                bmap.showLatLon(e.currentTarget.oo);
            });
            map.addOverlay(myPolygon);
        },
        showLatLon: function(a){
            var len = a.length;
            var s = '';
            var point = '';
            var arr = [];
            for(var i =0 ; i < len; i++){
                arr.push([a[i].lng, a[i].lat]);
                if(i>0){
                    point += "|";
                }
                point += (a[i].lng + "," + a[i].lat);
                s += '<li>'+ a[i].lng +','+ a[i].lat +'<span class="red" title="删除" onclick="bmap.delPoint('+i+')">X</span></li>';
            }
            this.overlaysCache = arr;
            $("#panel").html('<ul>'+ s +'</ul>');
            $("input[name='point']").val(point);
            $("input[name='pointcount']").val(len);
            console.log(point);
        },
        delPoint: function(i){
            if(this.overlaysCache.length <=3 ){
                alert('不能再删除, 请保留3个以上的点.');
                return;
            }
            this.overlaysCache.splice(i,1);
            var a = this.overlaysCache;
            var newOverlay = [];
            for(var i in a ){
                newOverlay.push( new BMap.Point(a[i][0],a[i][1]) );
            }
            this.myOverlay = newOverlay;
            this.loadMyOverlay();
        },
        /**
         *回调获得覆盖物信息
         */
        overlaycomplete: function(e){
            bmap.overlays.push(e.overlay);
            e.overlay.enableEditing();
            bmap.showLatLon(e.overlay.oo);
            e.overlay.addEventListener("lineupdate",function(ee){
                bmap.showLatLon(ee.currentTarget.oo);
            });
        },
        /**
         * 清除覆盖物
         */
        clearAll: function() {
            var map = this.map;
            var overlays = this.overlays;
            for(var i = 0; i < overlays.length; i++){
                map.removeOverlay(overlays[i]);
            }
            this.overlays.length = 0
            map.removeOverlay(this.myPolygon);
            this.myPolygon = '';
        },
        /**
         *取覆盖物的经纬度
         */
        getOverLay: function(){
            var box = this.myPolygon ? this.myPolygon : this.overlays[this.overlays.length - 1];
            console.log(box.oo);
        },
        getCount: function(){
            var n = 0;
            if (this.myPolygon) {
                n++;
            };
            if (this.overlays) {
                n = n + this.overlays.length;
            };
            console.log(n);
        }
    };
    //显示结果面板动作
    var isPanelShow = false;
    $("#showPanelBtn").click(function(){
        if (isPanelShow == false) {
            isPanelShow = true;
            $("#showPanelBtn").css("right","230px");
            $("#panelWrap").css("width","230px");
            $("#map").css("marginRight","230px");
            $("#showPanelBtn").text("编辑多边形");
        } else {
            isPanelShow = false;
            $("#showPanelBtn").css("right","0px");
            $("#panelWrap").css("width","0px");
            $("#map").css("marginRight","0px");
            $("#showPanelBtn").text("编辑多边形");
        }
    });
    //加载一个已有的多边形
    // bmap.myOverlay = [  
    //     new BMap.Point(116.256515,39.995242),
    //     new BMap.Point(116.502579,39.951893),
    //     new BMap.Point(116.256515,39.866882)
    // ];
    bmap.myOverlay = "{$data.pointobj|default=""}";
    bmap.init();
    </script>
</body>
</html>
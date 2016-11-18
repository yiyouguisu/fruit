<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <include file="Common:Nav" />
  <div class="h_a">检测</div>
    <div class="search_type cc mb10">
      <div class="mb10"> <span class="mr20">
        对系统目录的读写和执行权限进行检测，对于程序无需写入和执行的文件夹不给予相应的权限，可在一定程度上防止程序挂马
        <button class="btn" onClick="testfile();">检测</button>
        </span> </div>
      <div class="mb10"> <span class="mr20" id="testResult">
        </span> </div>
    </div>
   <div class="table_list" style="border:1px solid #CCC">
	<iframe src="" width="100%" height="500px" frameborder="0" id="fieltestShow"></iframe>
   </div>
</div>
<script type="text/javascript">
function testfile(){
	$("#testResult").html("扫描中。。。");
	$("#fieltestShow").attr("src","{:U('Admin/SafeCenter/dofiletest')}");
}
function showDirListF(type){
	window.frames["fieltestShow"].showDirList(type);
}
</script>
<script src="__JS__/common.js?v"></script>
</body>
</html>
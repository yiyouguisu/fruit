<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <include file="Common:Nav" />
  <div class="h_a">检测</div>
    <div class="search_type cc mb10">
      <div class="mb10"> <span class="mr20">
        对系统目录内的文件进行检测，对于不应该出现动态脚本的文件夹如果检测出动态脚本，则程序有可能已经被挂马，应立即清除
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
	$("#testResult").html("检测中。。。");
	$("#fieltestShow").attr("src","{:U('Admin/SafeCenter/dofilescan')}");
}
function showDirListF(type){
	window.frames["fieltestShow"].showDirList(type);
}
function deleteAllF(type){
	window.frames["fieltestShow"].deleteAllFile();
}
</script>
<script src="__JS__/common.js?v"></script>
</body>
</html>

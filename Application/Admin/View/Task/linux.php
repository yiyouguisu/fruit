<include file="Common:header" />
<body class="J_scroll_fixed">
<style type="text/css">
.table_full td span,.table_full td button{float:left}
.table_full td span{position:relative; top:10px; margin-left:20px}
.table_full td span.tip{color:#0C0;background:url({$_static_}/images/admin/tips/tips_follow.png) left bottom no-repeat;} 
</style>
<div class="wrap J_check_wrap" style="height:200px">
    <include file="Common:tab" />
      <div><span class="red"><?php echo (function_exists('exec'))?"":"系统不支持exec函数，请修改系统环境或者在服务器端手动开启执守程序";?></span></div>
      <div class="h_a">{$menuName}</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
              <th width="200">开启服务：</th>
              <td><button class="btn" style="margin-top:5px" onClick="doCleanCache('startserver',this);">开启</button><span></span><span class="commonTip">开启服务后，计划任务才会执行</span></td>
            </tr>
            <tr>
              <th width="200">关闭服务：</th>
              <td><button class="btn" style="margin-top:5px" onClick="doCleanCache('stopserver',this);">关闭</button><span></span><span class="commonTip">开关闭服务后，计划任务不会执行</span></td>
            </tr>
            <tr>
              <th width="200">查看状态：</th>
              <td><button class="btn" style="margin-top:5px" onClick="doCleanCache('showstatus',this);">查看</button><span></span><span class="commonTip">查看当前服务的运行状态</span></td>
            </tr>
            </table>    
        </div><!--table_full-->
</div>
<script type="text/javascript">
var loading = "{$_static_}/images/admin/content/loading.gif";
function doCleanCache(type,obj){
	$(".table_form td").find("span:not(.commonTip)").empty();
	var span = $(obj).siblings("span:not(.commonTip)");
	span.html("<img src='"+loading+"'/>");
	$.ajax({
		url: "{$_thisUrl_}"+type,
		data: {},
		timeout: 50000,
		cache: false,
		type: "get",
		dataType: "html",
		success: function (d, s, r) {
			if(d){
				span.html(d).addClass("tip");
			}
		}
	});
}
</script>
<include file="Common:footer" />

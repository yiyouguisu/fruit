<script type="text/javascript">
function doneTip(){
	var count_error = $(".dofiletest li.error").length;
	var count_ok = $(".dofiletest li.ok").length;
	var html = "此次共检测<b>"+(count_error+count_ok)+"</b>个文件夹,安全文件夹<b class='lightgreen'>"+count_ok+"</b>个，建议修改权限的文件夹<b class='red'>"+count_error+"</b>个";
		html+='<button class="btn" onClick="showDirListF(\'safe\');">只看安全的文件夹</button>';
		html+='<button class="btn" onClick="showDirListF(\'notsafe\');">只看建议修改的文件夹</button>';
	$("#testResult", window.parent.document).html(html);
	alert("此次共检测"+(count_error+count_ok)+"个文件夹,安全文件夹"+count_ok+"个，建议修改权限的文件夹"+count_error+"个");
}

function showDirList(type){
	if(type=="safe"){
		$(".dofiletest li.error").hide();
		$(".dofiletest li.ok").show();
	}else{
		$(".dofiletest li.error").show();
		$(".dofiletest li.ok").hide();
	}
}

doneTip();
</script>
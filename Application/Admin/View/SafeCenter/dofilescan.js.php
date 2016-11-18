<script type="text/javascript">
function doneTip(){
	var count_error = $(".dofiletest li.error").length;
	var count_ok = $(".dofiletest li.ok").length;
	var html = "此次共检测<b>"+(count_error+count_ok)+"</b>个文件,安全文件<b class='lightgreen'>"+count_ok+"</b>个，可疑文件<b class='red'>"+count_error+"</b>个";
		html+='<button class="btn" onClick="showDirListF(\'safe\');">只看安全的文件</button>';
		html+='<button class="btn" onClick="showDirListF(\'notsafe\');">只看可疑文件</button>';
		html+='<button class="btn" onClick="deleteAllF();">删除所有可疑文件</button>';
	$("#testResult", window.parent.document).html(html);
	alert("此次共检测"+(count_error+count_ok)+"个文件,安全文件"+count_ok+"个，可疑文件"+count_error+"个");
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

function showFile(path,obj){
	window.open("/index.php/Admin/SafeCenter/showfile?path="+path);
}

function deleteFile(filePath,obj){
  if(confirm("确定要删除吗?")){
	$.ajax({
		url: "/index.php/Admin/SafeCenter/deletefile",
		data: {"path":filePath},
		timeout: 5000,
		cache: false,
		type: "post",
		dataType: "json",
		success: function (d, s, r) {
			if(d){
				if(d.status==1){
					$(obj).parent().parent("li").remove();
					alert("删除成功");
				}else{
					alert("删除失败，请通过FTP或者服务器查看是否有对此文件有删除权限");	
				}
			}else{
				alert("出错");	
			}
		}
	});
  }
}


function deleteAllFile(){
  if(confirm("确定要删除所有可疑文件吗?")){
	var p={};
	$(".dofiletest li.error").each(function(k, v) {
		p[k] = $(this).find(".sp1").attr('title');
    });
	if(typeof p[0]=="undefined"){
		alert("可删除文件为0");
		return false;
	}
	$.ajax({
		url: "./index.php/Admin/SafeCenter/deletefile",
		data: {"path":p,"type":"all"},
		timeout: 5000,
		cache: false,
		type: "post",
		dataType: "json",
		success: function (d, s, r) {
			if(d){
				if(d.status==1){
					var errorList = $(".dofiletest li.error");
					$.each(d.ids,function(k,v){
						errorList.eq(v).remove();
					});
					alert(d.message);
				}
			}else{
				alert("出错");	
			}
		}
	});
  }
}

doneTip();
</script>
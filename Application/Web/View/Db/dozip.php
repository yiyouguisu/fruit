<include file="Common:header" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
      <div class="h_a">数据库打包下载</div>

        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
              <th id="zipstate">数据库打包中...</th>
            </tr>
          </table>    
        </div><!--table_full-->

</div>
<script type="text/javascript">
var _fixBtn = true;
var dirn = '{$name}';
var datas = { 'bakup':dirn };
function dozipFun(){
	$.post("{$_thisUrl_}dozip", datas, zipResponse,'json');
}
setTimeout(function(){ dozipFun(); },1000);
function zipResponse(res){
	$("#zipstate").html(res.message);
	if(res.status==1){
		window.location.href="{$_thisUrl_}downzip?filename="+res.filename;
	}
}
</script>
<include file="Common:footer" />
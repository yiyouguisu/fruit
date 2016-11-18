<include file="Common:Head" />
<body class="J_scroll_fixed">
<style type="text/css">
.table_list tr td{text-align:center}
.table_list tr td.tt{text-align:left}
</style>
<div class="wrap J_check_wrap">
  <include file="Common:Nav"/>
  <div>
      <span class="red"><?php echo (function_exists('exec'))?"":"系统不支持exec函数，请修改系统环境或者在服务器端手动开启执守程序";?></span>
  </div>
  <div class="table_list">
    <table width="100%">
        <colgroup>
        <col>
        <col>
        <col>
        <col>
        </colgroup>
        <thead>
          <tr align="center" bgcolor="#FFFFE9">
            <td width="3%" rowspan="2" align="center">ID</td>
            <td width="13%" rowspan="2" align="center">任务名</td>
            <td height="15" colspan="4" align="center">运行时间</td>
            <td width="7%" rowspan="2">状态</td>
            <td width="12%" rowspan="2">操作</td>
          </tr>
          <tr align="center" bgcolor="#FFFFE9">
            <td width="14%" align="center">月</td>
            <td width="16%" align="center">日</td>
            <td width="15%" align="center">小时</td>
            <td width="20%" height="18" align="center">分钟</td>
          </tr>
        </thead>
        <tbody>
        <volist name="list" id="vl">
        <tr id="list_{$vl.id}">
            <td>{$vl.id}</td>
            <td class="tt">{$vl.name}</td>
            <td>{$vl.month|showTask=###,'每'}月</td>
            <td><?php echo ($vl['week']=="*")?(($vl['day']=="*")?'每天':'每个月的第'.$vl['day'].'天'):'第一个'.showWeek($vl['week']);?></td>
            <td>{$vl.hour|showTask=###,'每'}小时</td>
            <td>{$vl.min|showTask=###,'每'}分</td>
            <td><?php echo $vl['is_on']==1?'<span class="green">开启</span>':'<span class="red">关闭</span>';?></td>
            <td>
                <a href="{:U('Admin/Task/edit',array('id'=>$vl['id']))}">修改</a>
                <a href="{:U('Admin/Task/delete',array('id'=>$vl['id']))}" class="J_ajax_del">删除</a><br>
                <a href="javascript:;" onClick="showLog({$vl.id});">查看最后一次执行记录</a>
            </td>
        </tr>
		</volist>
        </tbody>
      </table>
  </div>
</form>
</div>
<script type="text/javascript">
function showLog(id){
  omnipotent('selectid','/index.php/Admin/Task/showlog/id/'+id+'))}','查看最后一次执行记录',1,700,400);return false;
}
</script>
<script type="text/javascript" src="__JS__/content_addtop.js"></script>
<script src="__JS__/common.js?v"></script>
</body>
</html>

<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
<!-- <div class="nav">
  <ul class="cc">
        <li class="current"><a href="{:U('Admin/Menu/index')}">后台菜单管理</a></li>
        <li ><a href="{:U('Admin/Menu/add')}">添加菜单</a></li>
      </ul>
</div>-->
<include file="Common:Nav"/>
 <!-- -->
  <div class="h_a">搜索</div>
  <form method="post" action="{:U('Admin/Logs/index')}">
  <div class="search_type cc mb10">
    <div class="mb10"> <span class="mr20">
              搜索类型：
    <select class="select_2" name="status" style="width:70px;">
        <option value='' <if condition="$_POST['status'] eq ''">selected</if>>不限</option>
                <option value="1" <if condition="$_POST['status'] eq '1'">selected</if>>成功</option>
                <option value="2" <if condition="$_POST['status'] eq '2'">selected</if>>失败</option>
              
      </select>
      用户ID：<input type="text" class="input length_2" name="uid" size='10' value="{$Think.post.uid}" placeholder="用户ID">
      IP：<input type="text" class="input length_2" name="ip" size='20' value="{$Think.post.ip}" placeholder="IP">
    时间：
      <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
      -
      <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
      <button class="btn">搜索</button>
      <a class="btn" name="del_log_4" href="{:U('Admin/Logs/del')}" >删除一月前数据</a>
      </span> </div>
      </form> 
  </div>
    <div class="table_list">
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td align="center" width="30">ID</td>
            <td align="center" width="50" >用户ID</td>
            <td align="center" width="100" >用户名</td>
            <td align="center" width="60">状态</td>
            <td align="center">说明</td>
            <td align="center" width="150">时间</td>
            <td align="center" width="150">IP</td>
          </tr>
        </thead>
        <tbody>
          <volist name="logs" id="vo">
            <tr>
              <td align="center">{$vo.id}</td>
              <td align="center">{$vo.uid}</td>
              <td align="center">{$vo.username}</td>
              <td align="center">
                <if condition="$vo['status'] eq 1">成功</if>
                <if condition="$vo['status'] eq 2"><font color="#FF0000">失败</font></if>
          </td>
              <td align="">{$vo.info}</td>
              <td align="center">{$vo.time}</td>
              <td align="center">{$vo.ip}</td>
            </tr>
          </volist>
        </tbody>
      </table>
      <div class="p10">
        <div class="pages"> {$Page} </div>
      </div>
    </div>
  
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
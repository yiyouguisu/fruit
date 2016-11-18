<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap jj">
<!--   <div class="nav">
  <ul class="cc">
        <li ><a href="{:U('Admin/Menu/index')}">后台菜单管理</a></li>
        <li class="current"><a href="{:U('Admin/Menu/add')}">添加菜单</a></li>
      </ul>
</div>-->
<include file="Common:Nav"/>
  <div class="common-form">
  <!---->
    <form method="post" action="{:U('Admin/Role/add')}">
      <div class="h_a">角色信息</div>
   <div class="table_full">
      <table width="100%">
        <tr>
          <th width="100">角色名称</th>
          <td><input type="text" name="title" value="{$data.title}" class="input" id="title"></input></td>
        </tr>
        <tr>
          <th>角色描述</th>
          <td><textarea name="remark" rows="2" cols="20" id="remark" class="inputtext" style="height:100px;width:500px;">{$data.remark}</textarea></td>
        </tr>
        <tr>
          <th>是否启用</th>
          <td><input type="radio" name="status" value="1"  <if condition="$data['status'] eq 1">checked</if>>启用<label>  &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="status" value="0" <if condition="$data['status'] eq 0">checked</if>>禁止</label></td>
        </tr>
      </table>
      <input type="hidden" name="id" value="{$data.id}" />
      </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
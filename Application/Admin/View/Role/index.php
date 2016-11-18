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
   <div class="table_list">
     <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <td width="20"  align="center">ID</td>
          <td width="200"  align="left" >角色名称</td>
          <td align="left" >角色描述</td>
          <td width="50"  align="left" >状态</td>
          <td width="300">管理操作</td>
        </tr>
      </thead>
      <tbody>
        <foreach name="data" item="vo">
        <tr>
          <td width="10%" align="center">{$vo.id}</td>
          <td width="15%"  >{$vo.title}</td>
          <td >{$vo.remark}</td>
          <td width="5%">
          <if condition="$vo['status'] eq 1"> 
          <font color="red">√</font>
          <else />
          <font color="red">╳</font>
          </if>
          </td>
          <td  class="text-c">
          <if condition="$vo['id'] eq 1"> 
          <font color="#cccccc">权限设置</font> | <a href="{:U('Admin/Manager/index',array('group_id'=>$vo['id']))}">成员管理</a> | <font color="#cccccc">修改</font> | <font color="#cccccc">删除</font>
          <else />
            <if condition="authcheck('Admin/Role/auth')">
                 <a href="{:U('Admin/Role/auth',array('id'=>$vo['id']))}">权限设置</a>
                <else/>
                 <font color="#cccccc">权限设置</font>
              </if>
          | 
          <if condition="authcheck('Admin/Manager/index')">
                 <a href="{:U('Admin/Manager/index',array('group_id'=>$vo['id']))}">成员管理</a>
                <else/>
                 <font color="#cccccc">成员管理</font>
              </if>
           | <if condition="authcheck('Admin/Role/edit')">
                 <a href="{:U('Admin/Role/edit',array('id'=>$vo['id']))}">修改</a>
                <else/>
                 <font color="#cccccc">修改</font>
              </if> | <if condition="authcheck('Admin/Role/delete')">
                 <a href="{:U('Admin/Role/delete',array('id'=>$vo['id']))}" class="del">删除</a>
                <else/>
                 <font color="#cccccc">删除</font>
              </if>
          </if>
          </td>
        </tr>
        </foreach>
      </tbody>
    </table>
   </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
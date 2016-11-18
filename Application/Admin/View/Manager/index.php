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
            <td width="5%">序号</td>
            <td width="10%" align="left" >用户名</td>
            <td width="10%" align="left" >所属角色</td>
            <td width="15%"  align="left" >最后登录IP</td>
            <td width="15%"  align="left" >最后登录时间</td>
            <td width="15%"  align="left" >E-mail</td>
            <td width="15%">备注</td>
            <td width="15%" >管理操作</td>
          </tr>
        </thead>
        <tbody>
        <foreach name="Userlist" item="vo">
          <tr>
            <td width="5%" align="left">{$vo.id}</td>
            <td width="10%" align="left">{$vo.username}</td>
            <td width="10%" align="left">{$vo.role_name}</td>
            <td width="15%" align="left">{$vo.lastlogin_ip}</td>
            <td width="10%"  align="left">
            <if condition="$vo['lastlogin_time'] eq 0">
            该用户还没登陆过
            <else />
            {$vo.lastlogin_time|date="Y-m-d H:i:s",###}
            </if>
            </td>
            <td width="15%" align="left">{$vo.email}</td>
            <td width="20%"  align="left">{$vo.content}</td>
            <td width="15%"  align="left">
                   <if condition="authcheck('Admin/Manager/edit')">
                <a href="{:U('Admin/Manager/edit',array('id'=>$vo[id]))}">修改</a>
                <else/>
                 <font color="#cccccc">删除</font>
              </if>
             | 
            <if condition="$User['username'] eq $vo['username']">
            <font color="#cccccc">删除</font>
            <else />
              <if condition="authcheck('Admin/Manager/delete')">
                <a href="{:U('Admin/Manager/delete',array('id'=>$vo['id']))}" class="del">删除</a>
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
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
    <form method="post" action="{:U('Admin/Manager/edit')}">
      <div class="h_a">基本信息</div>
      <div class="table_full">
        <table width="100%" class="table_form contentWrap">
        <tbody>
          <tr>
            <th width="80">用户名</th>
            <td> <input type="hidden" name="id" value="{$data.id}"/>
                <input type="text" name="username" class="input" id="username" value="{$data.username}">
              <span class="gray">请输入用户名</span></td>
          </tr>
          <tr>
            <th>密码</th>
            <td><input type="password" name="password" class="input" id="password" value="">
              <span class="gray">请输入密码</span></td>
          </tr>
          <tr>
            <th>确认密码</th>
            <td><input type="password" name="pwdconfirm" class="input" id="pwdconfirm" value="">
              <span class="gray">请输入确认密码</span></td>
          </tr>
          <tr>
            <th>E-mail</th>
            <td><input type="text" name="email" class="input" id="email" value="{$data.email}" size="30">
              <span class="gray">请输入E-mail</span></td>
          </tr>
          <tr>
            <th>真实姓名</th>
            <td><input type="text" name="nickname" class="input" id="nickname"  value="{$data.nickname}" ></td>
          </tr>
          <tr>
          <th>备注</th>
          <td><textarea name="content" rows="2" cols="20" id="content" class="inputtext" style="height:100px;width:500px;">{$data.content} </textarea></td>
        </tr>
          <tr>
            <th>所属角色</th>
            <td>
            <select name="group_id">
                <foreach name="role" item="vo">
                <option value="{$vo.id}" <if condition="$data['group_id'] eq $vo['id'] ">selected</if>>{$vo.title}</option>
                </foreach>
              </select></td>
          </tr>
          <tr>
          <th>状态</td>
          <td><select name="status">
                <option value="1" <if condition="$data['status'] eq 1 ">selected</if>>开启</option>
                <option value="0" <if condition="$data['status'] eq 0 ">selected</if>>禁止</option>
          </select></td>
        </tr>
        </tbody>
      </table>
      </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
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
    <form method="post" action="{:U('Admin/Manager/chanpass')}" > 
     <div class="h_a">用户信息</div>
    <div class="table_full">
      <table width="100%">
        <col class="th" />
        <col/>
        <thead>
          <tr>
            <th>用户名</th>
            <td> {$User.username}</td>
          </tr>
        </thead>
        <tr>
          <th>旧密码</th>
          <td><input name="password" type="password" class="input length_5" value=""></td>
        </tr>
        <tr>
          <th>新密码</th>
          <td><input name="new_password" type="password" class="input length_5" value="">
           <span id="J_reg_tip_new_password" role="tooltip"></span></td>
        </tr>
        <tr>
          <th>重复新密码</th>
          <td><input name="new_pwdconfirm" type="password" class="input length_5 " value=""></td>
        </tr>
      </table>
    </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="__JS__/common.js?v"></script>

</body>
</html>
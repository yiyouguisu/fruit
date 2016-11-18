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
    <form method="post" action="{:U('Admin/Menu/add')}">
      <div class="h_a">菜单信息</div>
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
          <tbody>
            <tr>
              <td width="140">上级:</td>
              <td><select name="parentid">
                  <option value="0">作为一级菜单</option>          
                     {$select_categorys}  
                </select></td>
            </tr>
            <tr>
              <td>名称:</td>
              <td><input type="text" class="input" name="title" id="title" value="" ></td>
            </tr>
            <tr>
              <td>规则:</td>
              <td><input type="text" class="input" name="name" id="name" value=""> <span class="gray">模块/控制器/方法 例 Admin/index/index</span></td>
            </tr>
            <tr>
              <td>规则表达式:</td>
              <td><input type="text" class="input" name="condition" id="condition" value=""> <span class="gray">例如：score<100 积分小于100</span></td>
            </tr>
           
            <tr>
              <td>备注:</td>
              <td><textarea name="remark" rows="5" cols="57"></textarea></td>
            </tr>
            <tr>
              <td>状态:</td>
              <td><select name="ismenu">
                  <option value="1" >显示</option>
                  <option value="0" >不显示</option>
                </select></td>
            </tr>
            <tr>
              <td>权限状态:</td>
              <td><select name="status">
                  <option value="1" >启用</option>
                  <option value="0" >禁用</option>
                </select></td>
            </tr>
            <tr>
              <td>类型:</td>
              <td><select name="type">
                  <option value="3" selected>权限认证+菜单</option>
                  <option value="2" >只作为权限认证</option>
                  <option value="1" >只作为菜单</option>
                </select>
                  <span class="gray">注意：“权限认证+菜单”表示加入后台权限管理，纯碎是菜单项请不要选择此项。</span></td>
            </tr>
          </tbody>
        </table>
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
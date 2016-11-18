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
  <form action="{:U('Admin/Menu/listorders')}" method="post">
    <div class="table_list">
      <table width="100%">
        <colgroup>
        <col width="80">
        <col width="50">
        <col>
        <col width="200">
        <col width="150">
        <col width="100">
        <col width="80">
        <col width="200">
        </colgroup>
        <thead>
          <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>
            <td>规则</td>
            <td>表达式</td>
             <td>类型</td>
            <td>状态</td>
            <td>管理操作</td>
          </tr>
        </thead>
        {$categorys}
      </table>
      <div class="p10"><div class="pages"> {$Page} </div> </div>
     
    </div>
       <if condition="authcheck('Admin/Menu/listorders')">
                 <div class="btn_wrap">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button>
      </div>
    </div>
              </if>
    
  
  </form>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
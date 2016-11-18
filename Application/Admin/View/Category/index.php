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
 <div class="h_a">温馨提示</div>
  <div class="prompt_text">
    <p>1、栏目<font color="blue">ID</font>为<font color="blue">蓝色</font>才可以添加内容。可以使用“属性转换”进行转换！</p>
    <p>2、终极栏目不能添加子栏目</p>
    <p>3、排序按照排序数值倒序排列</p>
    <p>4、外部链接不能进行“属性转换”</p>
  </div>
 <!-- -->
   <form name="myform" action="{:U('Admin/Category/listorder')}" method="post">
  <div class="table_list">
    <table width="100%">
        <colgroup>
        <col width="80">
        <col width="80">
        <col>
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="100" >
        <col width="300">
        </colgroup>
        <thead>
          <tr>
            <td align='center'>排序</td>
            <td align='center'>栏目ID</td>
            <td>栏目名称</td>
            <td align='center'>栏目类型</td>
            <td align='center'>所属模型</td>
            <td align='center'>是否显示</td>
            <td align='center'>是否终极</td>
            <td align='center'>管理操作</td>
          </tr>
        </thead>
        {$categorys}
      </table>
    <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button>
      </div>
    </div>
  </div>
  </div>
</form>
   </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
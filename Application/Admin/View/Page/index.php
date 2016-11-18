<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
<include file="Common:Nav"/>
<!-- <div class="h_a">温馨提示</div>
  <div class="prompt_text">
    <p>1、栏目<font color="blue">ID</font>为<font color="blue">蓝色</font>才可以添加内容。可以使用“属性转换”进行转换！</p>
    <p>2、终极栏目不能添加子栏目</p>
    <p>3、排序按照排序数值倒序排列</p>
    <p>4、外部链接不能转换属性</p>
  </div>-->

  <div class="table_list">
    <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="5%" align="center" >序号</td>
            <td width="30%" align="left" >标题</td>
            <td width="20%" align="left" >所属栏目</td>
            <td width="15%"  align="center" >修改时间</td>
             <td width="15%"  align="center" >修改操作人</td>
            <td width="15%" align="center" >管理操作</td>
          </tr>
        </thead>
        <tbody>
              {$data}
<!--        <volist name="data" id="data">
            <tr>
	 <td align="center">{$data.id}</td>
	 <td>{$data.title}</td>
                   <td>{$data.catname}</td>
                   <td align="center">{$data.updatetime|date="Y-m-d H:i:s",###}</td>
                   <td align="center">{$data.username}</td>
                    <td align="center">
                 
                        <a href="{:U('Admin/Page/edit',array('catid'=>$data['id']))}">修改</a>
                     
                    </td>
	</tr>
        </volist>-->
        </tbody>
      </table>
        <div class="p10">
        <div class="pages"> {$Page} </div>
      </div>

  </div>
  </div>

   </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
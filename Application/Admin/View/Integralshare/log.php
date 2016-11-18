<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
   <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="4%" align="center" >序号</td>
            <td width="8%" align="center" >用户名</td>
            <td width="4%" align="center" >性别</td>
            <td width="8%" align="center" >可用分享币</td>
            <td width="8%" align="center" >累计分享币</td>
            <td width="4%" align="center" >类型</td>
            <td width="12%" align="center" >使用详情</td>
            <td width="10%"  align="center" >使用时间</td>
            <td width="8%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td align="center">{$vo.id}</td>
            <td align="center">{$vo.username}</td>
            <td align="center" >
                 <if condition=" $vo['sex'] eq '0'">未知</if>
                 <if condition=" $vo['sex'] eq '1'">男</if>
                 <if condition=" $vo['sex'] eq '2'">女</if>
            </td>
            <td align="center">{$vo.useIntegralsharebpay}</td>
            <td align="center">{$vo.totalIntegralsharebpay}</td>
            <td align="center">
                <if condition=" $vo['type'] eq '0'">增加</if>
                <if condition=" $vo['type'] eq '1'">减少</if>
            </td>
            <td align="center">{$vo.content}</td>
            <td align="center">{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
            <td  align="center" >
                <a href="{:U('Admin/Integralsharebpay/dellog',array('id'=>$vo['id']))}"  class="del">删除 </a> 
            </td> 
          </tr>
         </foreach>
        </tbody>
      </table>
            <div class="p10">
                    <div class="pages"> {$Page} </div>
                </div>
   </div>
</div>
<script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
    
</body>
</html>
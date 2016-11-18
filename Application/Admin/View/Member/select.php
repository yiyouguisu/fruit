<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
	background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
     <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Member/select')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                      性别：
                        <select class="select_1" name="sex">
                            <option value="" <empty name="Think.post.sex">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.post.sex eq '1'"> selected</if>>男</option>
                            <option value="2" <if condition=" $Think.post.sex eq '2'"> selected</if>>女</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>用户名</option>
                        <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>真实姓名</option>
                         <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>邮箱</option>
                         <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>手机</option>
<!--                     
                         <option value='4' <if condition=" $searchtype eq '4' "> selected</if>>ID</option>-->
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
   <div class="table_list">
   <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="8%" align="center" >用户名</td>
            <td width="8%" align="center" >性别</td>
            <td width="8%" align="center" >真实姓名</td>
            <td width="20%"  align="center" >邮箱</td>
            <td width="15%"  align="center" >手机</td>
            <td width="10%"  align="center" >汇付账号</td>
            <td width="15%" align="center" >汇付客户号</td>
            
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr onclick="select_list(this,'{$vo.username}','{$vo.id}')" class="cu" title="点击选择">
            <td width="10%" align="center">{$vo.username}</td>
            <td width="8%" align="center" >
                 <if condition=" $vo.sex eq '1'">男</if>
                 <if condition=" $vo.sex eq '2'">女</if>
                 <if condition=" $vo.sex eq '0'">未知</if>
            </td>
            <td width="10%" align="center">{$vo.realname}</td>
            <td width="20%" align="center">{$vo.email}</td>
            <td width="15%"  align="center">
            {$vo.phone}
            </td>
             <td width="10%"  align="center">{$vo.UsrId}</td>
            <td width="15%"  align="center">{$vo.UsrCustId}</td>
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
<script>
function select_list(obj, title, id) {
        $(window.parent.document).find("#uid").val(id);
         $(window.parent.document).find('#username').val(title);
        
}
</script>
</body>
</html>
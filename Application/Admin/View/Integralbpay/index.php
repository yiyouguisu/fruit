<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Integral/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                              可用霸王币：
                        <input type="text" name="start_integral" class="input length_2" value="{$Think.post.start_integral}" style="width:80px;">
                       -
                        <input type="text" class="input length_2" name="end_integral" value="{$Think.post.end_integral}" style="width:80px;">
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>用户名</option>
                         <option value='4' <if condition=" $searchtype eq '4' "> selected</if>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
   <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="4%" align="center" >序号</td>
            <td width="10%" align="center" >用户名</td>
            <td width="4%" align="center" >性别</td>
            <td width="8%" align="center" >可用霸王币</td>
            <td width="8%" align="center" >赠送霸王币</td>
            <td width="8%" align="center" >已用霸王币</td>
            <td width="8%" align="center" >累计霸王币</td>
            <td width="10%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td align="center">{$vo.id}</td>
            <td align="center">{$vo.username}</td>
            <td align="center" >
                 <if condition=" $vo.sex eq '0'">未知</if>
                 <if condition=" $vo.sex eq '1'">男</if>
                 <if condition=" $vo.sex eq '2'">女</if>
            </td>
            <td align="center">{$vo.useintegral}</td>
            <td align="center">{$vo.giveintegral}</td>
            <td align="center">{$vo.payed}</td>
            <td align="center">{$vo.totalintegral}</td>
            <td  align="center" >
                <if condition="authcheck('Admin/Integral/edit')">
                            <a href="{:U('Admin/Integral/edit',array('id'=>$vo['id']))}" >赠送 </a>  |
                            <else/>
                            <font color="#cccccc">赠送</font> |
                        </if>  
                  <if condition="authcheck('Admin/Integral/log')">
                            <a href="{:U('Admin/Integral/log',array('id'=>$vo['id']))}" >使用记录 </a>
                            <else/>
                            <font color="#cccccc">使用记录</font>
                        </if>  
                        <!-- <if condition="authcheck('Admin/Integral/del')">
                            <a href="{:U('Admin/Integral/del',array('id'=>$vo['id']))}"  class="del">删除 </a> 
                            <else/>
                            <font color="#cccccc">删除 </font> 
                        </if>  -->
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
    
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Store/courier')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                              添加时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                      性别：
                        <select class="select_1" name="sex">
                            <option value="" <empty name="Think.post.sex">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.post.sex eq '1'"> selected</if>>男</option>
                            <option value="2" <if condition=" $Think.post.sex eq '2'"> selected</if>>女</option>
                        </select>
                        组长：
                        <select class="select_1" name="isleader">
                            <option value="" <empty name="Think.post.isleader">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.post.isleader eq '1'"> selected</if>>是</option>
                            <option value="0" <if condition=" $Think.post.isleader eq '0'"> selected</if>>否</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>用户名</option>
                         <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>手机</option>
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
            <td width="6%" align="center" >性别</td>
            <td width="10%" align="center" >手机号</td>
            <td width="10%" align="center" >最后登录时间</td>
            <td width="8%"  align="center" >登陆次数</td>
            <td width="12%"  align="center" >添加时间</td>
            <td width="10%"  align="center" >送货订单总金额</td>
            <td width="10%"  align="center" >送货订单总数量</td>
            <td width="10%"  align="center" >推荐码</td>
            <td width="12%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td align="center">{$vo.id}</td>
            <td align="center">{$vo.username}</td>
            <td align="center">
              <if condition="$vo.sex eq 0">未知</if>
              <if condition="$vo.sex eq 1">男</if>
              <if condition="$vo.sex eq 2">女</if>
            </td>
            <td align="center" >{$vo.phone}</td>
            <td align="center">
              <empty name="vo.lastlogin_time">
                用户还未登录
                <else />
                {$vo.lastlogin_time|date="Y-m-d H:i:s",###}
              </empty>
            </td>
            <td align="center" >{$vo.login_num}次</td>
            <td align="center" >{$vo.reg_time|date="Y-m-d H:i:s",###}</td>
            <td align="center" >{:getdeliveryorder_totalmoney($vo['id'])}</td>
            <td align="center" >{:getdeliveryorder_totalnum($vo['id'])}</td>
            <td align="center" >{$vo.tuijiancode}</td>
            <td  align="center" >
                  <if condition="authcheck('Admin/Store/courieredit')">
                            <a href="{:U('Admin/Store/courieredit',array('id'=>$vo['id']))}" >修改基本资料 </a>  |
                            <else/>
                            <font color="#cccccc">修改基本资料</font> |
                        </if>  
                        <if condition="authcheck('Admin/Store/courierdel')">
                            <a href="{:U('Admin/Store/courierdel',array('id'=>$vo['id']))}"  class="del">删除 </a> 
                            <else/>
                            <font color="#cccccc">删除 </font> 
                        </if> 
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
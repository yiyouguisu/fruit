<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Push/store')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                            推送时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        推送状态：
                        <select class="select_2" name="status" style="width:120px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value='1' <if condition=" $Think.get.status eq '1'"> selected</if>>待推送</option>
                            <option value='2' <if condition=" $Think.get.status eq '2'"> selected</if>>推送成功</option>
                            <option value='3' <if condition=" $Think.get.status eq '3'"> selected</if>>推送失败</option>

                        </select>
                        推送标题：
                        <input type="text" class="input length_2"  style="width:200px;" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="{:U('Admin/Push/sdel')}" method="post" >
        <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="4%" align="center"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
            <td width="4%" align="center" >序号</td>
            <td width="10%" align="center" >发布者</td>
            <td width="20%" align="center" >推送标题</td>
            <td width="25%" align="center" >推送摘要</td>
            <td width="12%"  align="center" >推送时间</td>
            <td width="10%"  align="center" >推送状态</td>
            <td width="10%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
            <td align="center">{$vo.id}</td>
            <td align="center">{$vo.username}</td>
            <td align="center">{$vo.title}</td>
            <td align="center">{$vo.description}</td>
            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
            <td align="center" >
              <if condition="$vo['status'] eq 1">待推送</if>
                <if condition="$vo['status'] eq 2">成功</if>
                <if condition="$vo['status'] eq 3">失败</if>
            </td>
            <td  align="center" >
              <if condition="authcheck('Admin/Push/pushsagain')">
                  <a href="{:U('Admin/Push/pushsagain',array('id'=>$vo['id']))}">再次推送 </a> |
                  <else/>
                  <font color="#cccccc">再次推送 </font> |
              </if> 
              <if condition="authcheck('Admin/Push/delete')">
                  <a href="{:U('Admin/Push/delete',array('id'=>$vo['id']))}"  class="del">删除 </a> 
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
        <div class="btn_wrap">
          <div class="btn_wrap_pd">
              <label class="mr20">
              <input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>     
            <if condition="authcheck('Admin/Push/sdel')">
              <button class="btn btn_submit mr10 " type="submit" name="submit" value="sdel">删除</button>
            </if>
          </div>
        </div>
    </form>
 </div>
<script src="__JS__/common.js?v"></script>
    
</body>
</html>
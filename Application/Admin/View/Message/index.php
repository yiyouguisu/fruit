<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/message/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                            发布时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                        状态：
                        <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.post.status">selected</empty>>全部</option>
                            <option value='1' <if condition=" $Think.post.status eq '1'"> selected</if>>已读</option>
                            <option value='0' <if condition=" $Think.post.status eq '0'"> selected</if>>未读</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>内容</option>
                        <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>用户昵称</option>
                         <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="{:U('Admin/Message/del')}" method="post" >
        <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="4%" align="center"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
            <td width="4%" align="center" >序号</td>
            <td width="12%" align="center" >用户名</td>
            <td width="12%" align="center" >标题</td>
            <td width="20%" align="center" >内容</td>
            <td width="10%"  align="center" >发布时间</td>
            <td width="8%"  align="center" >是否已读</td>
            <td width="10%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
            <td align="center">{$vo.id}</td>
            <td align="center">{:getuserinfo($vo['tuid'])}</td>
            <td align="center">{$vo.title}</td>
            <td align="center">{$vo.content}</td>
            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
            <td align="center" >
                <if condition="$vo['status'] eq 0">未读</if>
                <if condition="$vo['status'] eq 1">已读</if>
            </td>
            <td  align="center" >
                        <if condition="authcheck('Admin/message/delete')">
                            <a href="{:U('Admin/message/delete',array('id'=>$vo['id']))}"  class="del">删除 </a> 
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
            <if condition="authcheck('Admin/Message/del')">
              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
            </if>
          </div>
        </div>
    </form>
 </div>
<script src="__JS__/common.js?v"></script>
<script src="__JS__/content_addtop.js"></script>
</body>
</html>
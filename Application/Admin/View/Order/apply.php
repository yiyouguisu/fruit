<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Order/apply')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                         状态：
                       <select class="select_2" name="status" style="width:85px;">
                            <option value=""  <empty name="Think.post.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.post.status eq '1'"> selected</if>>申请中</option>
                            <option value="2" <if condition=" $Think.post.status eq '2'"> selected</if>>已处理</option>
                            <option value="3" <if condition=" $Think.post.status eq '3'"> selected</if>>未处理</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入订单号...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="{:U('Admin/Order/applydel')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >用户</td>
                            <td width="10%" align="center" >手机号</td>
                            <td width="15%" align="center" >邮箱</td>
                            <td width="20%" align="center" >订单号</td>
                            <td width="10%" align="center" >状态</td>
                            <td width="15%"  align="center" >申请时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >{:getuser($vo['uid'])}</td>
                            <td align="center" >{$vo.phone}</td>
                            <td align="center" >{$vo.email}</td>
                            <td align="center" >{$vo.orderid}</td>
                            <td align="center" >
                                <if condition="$vo.status eq 1"><font color="green">申请中</font></if>
                                <if condition="$vo.status eq 2"><font color="green">已处理</font></if>
                                <if condition="$vo.status eq 3"><font color="red">未处理</font></if>
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" > 
              <if condition="authcheck('Admin/Order/review')">
              <a href="{:U('Admin/Order/review',array('id'=>$vo['id']))}" >审核</a> 
                <else/>
                 <font color="#cccccc">审核</font>
              </if> 
                <if condition="authcheck('Admin/Order/applydelete')">
              <a href="{:U('Admin/Order/applydelete',array('id'=>$vo['id']))}"  class="del">删除</a> 
                <else/>
                 <font color="#cccccc">删除</font>
              </if> 
                                
                               
                            </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> {$Page} </div>
            </div>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>   
                        <if condition="authcheck('Admin/Order/applydel')">
                              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                        </if>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
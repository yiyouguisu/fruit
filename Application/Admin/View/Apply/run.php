<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/apply/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                        审核：
                        <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>审核</option>
                            <option value="0" <if condition=" $Think.get.status eq '0'"> selected</if>>未审核</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" style="width:70px;">
                            <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>手机号</option>
                            <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>邮箱</option>
                            <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>联系人</option>
                            <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="{:U('Admin/apply/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >联系人</td>
                            <td width="10%" align="center" >手机号</td>
                            <td width="15%" align="center" >邮箱</td>
                            <td width="20%" align="center" >公司名称</td>
                            <td width="10%" align="center" >是否联系</td>
                            <td width="15%"  align="center" >申请时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >{$vo.name}</td>
                            <td align="center" >{$vo.phone}</td>
                            <td align="center" >{$vo.email}</td>
                            <td align="center" >{$vo.company}</td>
                            <td align="center" >
                                <if condition="$vo.status eq 0">未联系</if>
                                <if condition="$vo.status eq 1">已联系</if>
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" > 
                                <if condition="authcheck('Admin/apply/check')">
              <a href="{:U('Admin/apply/check',array('id'=>$vo['id']))}" >联系</a> 
                <else/>
                 <font color="#cccccc">联系</font>
              </if> 
              <if condition="authcheck('Admin/apply/show')">
              <a href="{:U('Admin/apply/show',array('id'=>$vo['id']))}" >查看</a> 
                <else/>
                 <font color="#cccccc">查看</font>
              </if> 
                            <if condition="authcheck('Admin/apply/delete')">
              <a href="{:U('Admin/apply/delete',array('id'=>$vo['id']))}"  class="del">删除</a> 
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
                     <!-- <if condition="authcheck('Admin/apply/listorder')">
                                  <button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button>
                        </if> 
                   <if condition="authcheck('Admin/apply/review')">
                                  <button class="btn btn_submit mr10 " type="submit" name="submit" value="review">审核</button>
                        </if>
                 <if condition="authcheck('Admin/apply/unreview')">
                              <button class="btn btn_submit mr10 " type="submit" name="submit" value="unreview">取消审核</button>
                        </if>
                         <if condition="authcheck('Admin/apply/pushs')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="pushs">推荐</button>
                        </if>
                      <if condition="authcheck('Admin/apply/unpushs')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="unpushs">取消推荐</button>
                        </if> -->
                        <if condition="authcheck('Admin/apply/del')">
                              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                        </if>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
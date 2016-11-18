<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/apply/joincompany')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        申请时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>申请中</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>审核成功</option>
                            <option value="3" <if condition=" $Think.get.status eq '3'"> selected</if>>审核失败</option>
                        </select>
                        会员名：<input type="text" class="input length_2" name="username" value="{$Think.get.username}" style="width:80px;">
                        真实姓名：<input type="text" class="input length_2" name="realname" value="{$Think.get.realname}" style="width:80px;">
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
                            <td width="10%" align="center" >用户名</td>
                            <td width="10%" align="center" >真实姓名</td>
                            <td width="15%" align="left" >商户名称</td>
                            <td width="8%" align="center" >申请人姓名</td>
                            <td width="10%" align="center" >申请人手机号</td>
                            <td width="8%" align="center" >审核状态</td>
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
                            <td align="center" >{:getuser($vo['uid'],'realname')}</td>
                            <td align="left" >{$vo.company}</td>
                            <td align="center" >{$vo.name}</td>
                            <td align="center" >{$vo.phone}</td>
                            <td align="center" >
                              {:getreviewstatus($vo['status'])}
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" > 
                                <eq name="vo['status']" value="1">
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Apply/joincompanyreview',array('id'=>$vo['id']))}','审核',1,700,420)">审核</a>
                                </eq>
                                <a href="{:U('Admin/apply/joincompanydelete',array('id'=>$vo['id']))}"  class="del">删除</a> 
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
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="joincompanydel">删除</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="joincompanyreviewsuccess">批量审核成功</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="joincompanyreviewerror">批量审核失败</button>
                    
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
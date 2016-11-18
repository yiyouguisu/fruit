<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/User_upload/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上传时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        资料类型：
                        <select class="select_2" name="catid">
                            <option value="" >全部</option>
                            {$category}
                        </select>
                        会员名：<input type="text" class="input length_2" name="username" value="{$Think.get.username}" style="width:80px;">
                        真实姓名：<input type="text" class="input length_2" name="realname" value="{$Think.get.realname}" style="width:80px;">
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>待审核</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>审核成功</option>
                            <option value="3" <if condition=" $Think.get.status eq '3'"> selected</if>>审核失败</option>
                        </select>
                        
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >用户名</td>
                            <td width="10%" align="center" >真实姓名</td>
                            <td width="20%" align="left" >所属分类</td>
                            <td width="20%" align="left" >资料名称</td>
                            <td width="10%"  align="center" >状态</td>
                            <td width="15%"  align="center" >上传时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >{:getuserinfo($vo['uid'])}</td>
                            <td align="center" >{:getuser($vo['uid'],'realname')}</td>
                            <td align="left" >{$vo.catname}</td>
                            <td align="left" >
                                <span title="{$vo.filename}">{$vo.sortitle}(<a href="javascript:;" onClick="image_priview('{$vo.url}')">查看</a>)</span>
                            </td>
                            <td align="center" >
                                <if condition="$vo.status eq 1"> <span style="color: gray">待审核</span></if>
                                <if condition="$vo.status eq 2"> <span style="color: green">审核成功</span></if>
                                <if condition="$vo.status eq 3"> <span style="color: red">审核失败</span></if>
                            </td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" > 
                                <if condition="authcheck('Admin/Userupload/review')">
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Userupload/review',array('id'=>$vo['id']))}','资料审核',1,700,420)">审核</a>
                                <else/>
                                    <font color="#cccccc">审核</font>
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
         
            
    </div>

    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
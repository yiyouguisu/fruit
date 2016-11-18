<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Company/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                              申请时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>企业名称</option>
                         <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>企业负责人</option>
                         <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>负责人联系方式</option>
                         <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>绑定邮箱</option>
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
            <td width="12%" align="center" >企业名称</td>
            <td width="12%" align="center" >企业编号</td>
            <td width="15%" align="center" >企业LOGO</td>
            <td width="12%" align="center" >企业负责人</td>
            <td width="12%" align="center" >负责人联系方式</td>
            <td width="12%" align="center" >绑定邮箱</td>
            <td width="12%"  align="center" >申请时间</td>
            <td width="12%" align="center" >操作</td> 
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td align="center">{$vo.id}</td>
            <td align="center">{$vo.title}</td>
            <td align="center">{$vo.companynumber|default="未填写"}</td>
            <td align="center" >
              <if condition="$vo['head'] eq null">暂无缩略图<else /><img width="80" height="80" src="{$vo.head}" /></if>
            </td>
            <td align="center">{$vo.username}</td>
            <td align="center">{$vo.tel}</td>
            <td align="center" >{$vo.email}</td>
            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
            <td  align="center" >
                  <if condition="authcheck('Admin/Company/edit')">
                            <a href="{:U('Admin/Company/edit',array('id'=>$vo['id']))}" >修改基本资料 </a>  |
                            <else/>
                            <font color="#cccccc">修改基本资料</font> |
                        </if>  
                        <if condition="authcheck('Admin/Company/del')">
                            <a href="{:U('Admin/Company/del',array('id'=>$vo['id']))}"  class="del">删除 </a> 
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
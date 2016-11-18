<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
         <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Member/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                        会员名：<input type="text" class="input length_2" name="username" value="{$Think.get.username}" style="width:100px;">
                        真实姓名：<input type="text" class="input length_2" name="realname" value="{$Think.get.realname}" style="width:100px;">
                        开户行：<input type="text" class="input length_2" name="bankname" value="{$Think.get.bankname}" style="width:100px;">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
   <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="4%" align="center" >序号</td>
            <td width="8%" align="center" >用户名</td>
            <td width="8%" align="center" >真实姓名</td>
            <td width="10%" align="center" >身份证号</td>
            <td width="12%" align="center" >银行卡号</td>
            <td width="8%"  align="center" >开户行</td>
            <td width="12%"  align="center" >支行</td>
            <td width="12%" align="center" >添加时间</td> 
            <td width="12%" align="center" >修改时间</td>
            <td width="10%" align="center" >管理操作</td>
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
            <td align="center">{$vo.id}</td>
            <td align="center" >{:getuserinfo($vo['uid'])}</td>
            <td align="center" >{:getuser($vo['uid'],'realname')}</td>
            <td align="center">{:getuser($vo['uid'],'idcard')}</td>
            <td align="center" >{$vo.banknumber}</td>
            <td align="center" >{$vo.bankname}</td>
            <td align="center" >{$vo.banksubname}</td>
            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
            <td align="center">
              <empty name="vo.updatetime">
                <span style="color:gray">未修改</span>
                <else />
                {$vo.updatetime|date="Y-m-d H:i:s",###}
              </empty>
            </td>
            <td  align="center" >
                  <if condition="authcheck('Admin/Bank/edit')">
                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Bank/edit',array('id'=>$vo['id']))}','信息修改',1,700,400)">修改</a>
                            <else/>
                            <font color="#cccccc">修改</font>
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
<script src="__JS__/content_addtop.js"></script>
</body>
</html>
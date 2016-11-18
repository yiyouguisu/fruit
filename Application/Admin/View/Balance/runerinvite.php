<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Balance/runerinvite')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：<input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width: 80px;">
                        配送员：
                        <select class="select_2" name="runerid" style="width:120px;">
                            <option value=""  <empty name="Think.get.runerid">selected</empty>>全部</option>
                            <volist name="runer" id="vo">
                                <option value="{$vo.ruid}" <if condition="$Think.get.runerid eq $vo['ruid']"> selected</if>>{$vo.username}</option>
                            </volist>
                        </select>
                       <!-- 结算：
                       <select class="select_2" name="status" style="width:100px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="0" <if condition=" $Think.get.status eq '0'"> selected</if>>未结算</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>部分结算</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>完成结算</option>
                        </select>-->
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
       <thead>
          <tr>
            <td width="12%" align="center" >用户名</td>
            <td width="8%" align="center" >性别</td>
            <td width="12%" align="center" >手机号</td>
            <td width="12%" align="center" >最后登录时间</td>
            <td width="12%"  align="center" >登陆次数</td>
            <td width="10%"  align="center" >注册时间</td>
            <td width="10%"  align="center" >邀请人</td>
            <td width="10%"  align="center" >邀请码</td>
          </tr>
        </thead>
        <tbody>
     
          
        <foreach name="data" item="vo">
          <tr>
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
              <td align="center" >{$vo.tuijianusername}</td>
              <td align="center" >{$vo.tuijiancode}</td>
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
    <script type="text/javascript" src="__JS__/birthday.js"></script>
    <script>
        $(function () {
            $("#birthday_container").birthday();
        });
    </script>
</body>
</html>
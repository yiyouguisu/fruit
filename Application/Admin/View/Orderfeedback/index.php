<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">

<include file="Common:Nav"/>

   <div class="table_list">
        <div class="h_a">搜索</div>
        <form method="post" action="{:U('Admin/Orderfeedback/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                            反馈时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                       -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                        状态：
                        <select class="select_2" name="status" style="width:80px;">
                            <option value=""  <empty name="Think.post.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.post.status eq '1'"> selected</if>>处理中</option>
                            <option value="2" <if condition=" $Think.post.status eq '2'"> selected</if>>处理成功</option>
                            <option value="3" <if condition=" $Think.post.status eq '3'"> selected</if>>处理失败</option>
                        </select>
                        关键字：
                        <select class="select_2" name="searchtype" >
                        <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>内容</option>
                        <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>反馈人</option>
                        <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>订单号</option>
                         <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="{:U('Admin/Orderfeedback/del')}" method="post" >
        <table width="100%" cellspacing="0">
         <thead>
            <tr>
              <td width="4%" align="center"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
              <td width="4%" align="center" >序号</td>
              <td width="10%" align="center" >用户名</td>
              <td width="15%" align="center" >订单号</td>
              <td width="25%" align="left" >内容</td>
              <td width="15%"  align="center" >发布时间</td>
              <td width="10%"  align="center" >是否处理</td>
              <td width="20%" align="center" >操作</td> 
            </tr>
          </thead>
          <tbody>
          <foreach name="data" item="vo">
            <tr>
              <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
              <td align="center">{$vo.id}</td>
              <td align="center">{$vo.username}</td>
              <td align="center">{$vo.orderid}</td>
              <td align="left">{:str_cut($vo['content'],30)}</td>
              <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
              <td align="center" >
                  <if condition="$vo['status'] eq 1"><font color="gray">处理中</font></if>
                  <if condition="$vo['status'] eq 3"><font color="red">处理失败</font></if>
                  <if condition="$vo['status'] eq 2"><font color="green">处理成功</font></if>
              </td>
              <td  align="center" >
                  <if condition="$vo['status'] eq 1">
                      <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Orderfeedback/feedbackcheck',array('id'=>$vo['id']))}','处理订单反馈',1,800,600)">处理</a>|
                  </if>
                  <if condition="$vo['status'] eq 2">
                      <empty name="vo['relationorderid']">
                          <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/addorder',array('orderid'=>$vo['orderid']))}','新建订单',1,700,400)">新建订单</a>|
                          <else />
                          <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/show',array('orderid'=>$vo['relationorderid']))}','查看售后订单详情',1,700,400)">查看售后订单</a>|
                      </empty>
                  </if>
                  <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Orderfeedback/feedbacklog',array('orderid'=>$vo['orderid']))}','处理记录',1,700,400)">处理记录</a> |
                  <a href="javascript:;"  class="showpic" onclick="showpic('#layer-photos-demo_{$vo.id}');">查看图片</a> 
                               <div id="layer-photos-demo_{$vo.id}" class="layer-photos-demo" style="display:none;">
                                   <volist name="vo['imglist']" id="v">
                                        <img layer-pid="" layer-src="{$v}" src="{$v}" alt="订单反馈图片">
                                   </volist>
                                </div>
                  <a href="{:U('Admin/Orderfeedback/delete',array('id'=>$vo['id']))}"  class="del">删除 </a> 
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
            <if condition="authcheck('Admin/Orderfeedback/del')">
              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
            </if>
          </div>
        </div>
    </form>
 </div>
<script src="__JS__/common.js?v"></script>
     <script src="__JS__/content_addtop.js"></script>
    <script src="__JS__/layer/layer.js"></script>
    <script src="__JS__/layer/extend/layer.ext.js"></script>
    <script>
        function showpic(obj) {
            //调用示例
            layer.ready(function(){ //为了layer.ext.js加载完毕再执行
              layer.photos({
                  photos: obj
              });
              $(obj).find("img:first").click();
            }); 

        }  
    </script>
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Evaluate/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        评价时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                        关键字：
                        <select class="select_2" name="searchtype" style="width:70px;">
                            <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>评论内容</option>
                            <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>用户名</option>
                            <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>手机号</option>
                            <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>ID</option>
                        </select>
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="{:U('Admin/Evaluate/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >评分</td>
                            <td width="45%" align="left" >评价内容</td>
                            <td width="15%"  align="center" >评价时间</td>
                            <td width="10%"  align="center" >评价用户</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >{$vo.total}</td>
                            <td align="left" >
                               <span title="{$vo.content}">{$vo.sortitle}</span></td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{$vo.username}</td>
                            <td align="center" > 
                                <a href="{:U('Admin/Evaluate/delete',array('id'=>$vo['id']))}"  class="del">删除</a> 
                                <a href="javascript:;"  class="showpic" onclick="showpic('#layer-photos-demo_{$vo.id}');">查看图片</a> 
                               <div id="layer-photos-demo_{$vo.id}" class="layer-photos-demo" style="display:none;">
                                   <volist name="vo['imglist']" id="v">
                                        <img layer-pid="" layer-src="{$v}" src="{$v}" alt="商品评论图片">
                                   </volist>
                                </div>
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
                        <if condition="authcheck('Admin/Evaluate/del')">
                              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                        </if>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
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
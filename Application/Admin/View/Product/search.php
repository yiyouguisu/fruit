<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Product/search')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        搜索次数：
                        <select class="select_2" name="querytype" style="width:60px;">
                            <option value=""  <empty name="Think.get.querytype">selected</empty>>全部</option>
                            <option value="eq" <if condition=" $Think.get.querytype eq 'eq'"> selected</if>>等于</option>
                            <option value="gt" <if condition=" $Think.get.querytype eq 'gt'"> selected</if>>大于</option>
                            <option value="lt" <if condition=" $Think.get.querytype eq 'lt'"> selected</if>>小于</option>
                        </select><input type="number" min="0" class="input length_2" name="hit" style="width:60px;" value="{$Think.get.hit}">
                        关键字：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$Think.get.keyword}" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

        <form action="{:U('Admin/Product/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center">排序</td>
                            <td width="5%" align="center" >ID</td>
                            <td width="30%" align="left" >关键字</td>
                            <td width="10%" align="center" >搜索次数</td>
                            <td width="15%"  align="center" >最后一次搜索时间</td>
                            <td width="15%"  align="center" >第一次搜索时间</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" ><input name='listorders[{$vo.id}]' class="input length_1 mr5"  type='number' size='3' value='{$vo.listorder}' align="center"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="left" >{$vo.keyword|default="未填写"}</td>
                            <td align="center" >{$vo.hit|default="0"}</td>
                            <td align="center" >{$vo.lastupdatetime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" > 
                                <a href="{:U('Admin/Product/searchdelete',array('id'=>$vo['id']))}"  class="del">删除</a>  
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
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="searchlistorder" >排序</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="searchdel">删除</button>
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
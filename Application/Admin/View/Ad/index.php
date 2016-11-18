<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Ad/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        <!-- 类型：
                        <select class="select_2" name="catid">
                            <option value="" >全部</option>
                            {$category}
                        </select> -->
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>审核</option>
                            <option value="0" <if condition=" $Think.get.status eq '0'"> selected</if>>未审核</option>
                        </select>
                     
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="{:U('Admin/Ad/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center">排序</td>
                            <td width="5%" align="center" >ID</td>
                            <td width="15%" align="left" >标题</td>
                             <td width="35%" align="left" >图片</td>
                            <td width="10%" align="left" >所属分类</td>
                            
                            <td width="10%"  align="center" >状态</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" ><input name='listorders[{$vo.id}]' class="input mr5 length_1"  type='number' size='3' value='{$vo.listorder}' align="center"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="left" >
                                <span title="{$vo.title}">{$vo.sortitle}</span></td>
                            <td align="left" >
                        
                            <img src="{$vo.image}" height="30" >
                       
                            </td>
                            <td align="left" >
                                <eq name="vo['type']" value="1">链接广告</eq>
                                <eq name="vo['type']" value="2">商品广告</eq>
                                <eq name="vo['type']" value="3">图文广告</eq>
                            </td>
                           
                            <td align="center" ><if condition="$vo.status eq 0"> <span style="color: red">未审核</span></if>
                                <if condition="$vo.status eq 1"> 已审核</if>
                            </td>
                            <td align="center" > 
                                <if condition="authcheck('Admin/Ad/edit')">
             <a href="{:U('Admin/Ad/edit',array('id'=>$vo['id']))}" >修改</a>
                <else/>
                 <font color="#cccccc">修改</font>
              </if>
                                <if condition="authcheck('Admin/Ad/delete')">
           <a href="{:U('Admin/Ad/delete',array('id'=>$vo['id']))}" class="del" >删除</a>
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
                    <label class="mr20">
                    <input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>     
                                  <button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="review">审核</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="unreview">取消审核</button>
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Product/storehouse')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上架时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        所属类别：
                        <select class="select_2" name="subcatid">
                            <option value="" <empty name="Think.get.subcatid">selected</empty>>全部</option>
                            {$category}
                        </select>
                        商品所属：
                        <select class="select_2" name="type" style="width:85px;">
                            <option value=""  <empty name="Think.get.type">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.type eq '1'"> selected</if>>一般商品</option>
                            <option value="2" <if condition=" $Think.get.type eq '2'"> selected</if>>团购商品</option>
                            <option value="3" <if condition=" $Think.get.type eq '3'"> selected</if>>预购商品</option>
                            <option value="4" <if condition=" $Think.get.type eq '4'"> selected</if>>称重商品</option>
                        </select>
                        审核：
                        <select class="select_2" name="status" style="width:85px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>审核中</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>审核成功</option>
                            <option value="3" <if condition=" $Think.get.status eq '3'"> selected</if>>审核失败</option>
                        </select>   
                        库存：
                        <select class="select_2" name="stocktype" style="width:60px;">
                            <option value=""  <empty name="Think.get.stocktype">selected</empty>>全部</option>
                            <option value="eq" <if condition=" $Think.get.stocktype eq 'eq'"> selected</if>>等于</option>
                            <option value="gt" <if condition=" $Think.get.stocktype eq 'gt'"> selected</if>>大于</option>
                            <option value="lt" <if condition=" $Think.get.stocktype eq 'lt'"> selected</if>>小于</option>
                        </select><input type="number" min="0" class="input length_2" name="stock" style="width:60px;" value="{$Think.get.stock}">
                        关键字：
                        <select class="select_2" name="searchtype" style="width:120px;">
                            <option value='0' <if condition=" $searchtype eq '0' "> selected</if>>商品名称</option>
                            <option value='1' <if condition=" $searchtype eq '1' "> selected</if>>商品简介</option>
                            <!-- <option value='2' <if condition=" $searchtype eq '2' "> selected</if>>用户名</option> -->
                            <option value='3' <if condition=" $searchtype eq '3' "> selected</if>>ID</option>
                        </select>
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
                            <td width="8%" align="center" >产品编号</td>
                            <td width="15%" align="left" >产品名称</td>
                            <td width="12%" align="center" >所属类别</td>
                            <td width="6%" align="center" >产品规格</td>
                            <td width="6%" align="center" >产品库存</td>
                            <td width="6%" align="center" >产品售价</td>
                            <td width="12%"  align="center" >上架时间</td>
                            <td width="12%"  align="center" >所属店铺</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" ><input name='listorders[{$vo.id}]' class="input length_1 mr5"  type='number' size='3' value='{$vo.listorder}' align="center"></td>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >{$vo.productnumber|default="未填写"}</td>
                            <td align="left" >
                                <if condition="$vo.status eq 1"> <span style="color: gray">[审核中]</span></if>
                                <if condition="$vo.status eq 2"> <span style="color: green">[审核成功]</span></if>
                                <if condition="$vo.status eq 3"> <span style="color: red">[审核失败]</span></if>
                                <if condition="$vo.isoff eq 1"> <span style="color: red">[下架]</span></if>
                                <if condition="$vo.ishot eq 1"> <span style="color: red">[促销]</span></if>
                               <if condition="$vo.isindex eq 1"> <span style="color: red">[置顶]</span></if>
                               <if condition="$vo.isout eq 1"> <span style="color: red">[缺货]</span></if>
                                <span title="{$vo.title}">{$vo.sortitle}</span></td>
                            <td align="center" >{$vo.catname}</td>
                            <td align="center" >{$vo.standard}</td>
                            <td align="center" >{$vo.stock}</td>
                            <td align="center" >{$vo.nowprice}</td>
                            <td align="center" >{$vo.shelvestime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{:getstoreinfo($vo['storeid'])}</td>
                            <td align="center" > 
                                <!-- <if condition="authcheck('Admin/Product/review')">
              <a href="{:U('Admin/Product/review',array('id'=>$vo['id']))}" >审核</a>  |
                <else/>
                 <font color="#cccccc">审核</font> |
              </if>  -->
                               <a href="{:U('Admin/Product/edit',array('id'=>$vo['id']))}" >修改</a>  |
              <a href="{:U('Admin/Product/delete',array('id'=>$vo['id']))}"  class="del">删除</a> 
                                
                               
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
                     <if condition="authcheck('Admin/Product/listorder')">
                                  <button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button>
                        </if> 
                         <if condition="authcheck('Admin/Product/pushs')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="pushs">置顶</button>
                        </if>
                      <if condition="authcheck('Admin/Product/unpushs')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="unpushs">取消置顶</button>
                        </if>
                        <if condition="authcheck('Admin/Product/off')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="off">下架</button>
                        </if>
                      <if condition="authcheck('Admin/Product/unoff')">
                             <button class="btn btn_submit mr10 " type="submit" name="submit" value="unoff">取消下架</button>
                        </if>
                        <if condition="authcheck('Admin/Product/del')">
                              <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                        </if>
                    
                   
                    
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
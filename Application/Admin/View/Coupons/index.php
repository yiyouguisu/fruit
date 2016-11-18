<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Coupons/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>审核</option>
                            <option value="0" <if condition=" $Think.get.status eq '0'"> selected</if>>未审核</option>
                        </select>
                        优惠券名称：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="" placeholder="请输入关键字...">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <form action="{:U('Admin/Coupons/action')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center">排序</td>
                            <td width="5%" align="center" >ID</td>
                            <td width="15%" align="left" >标题</td>
                            <td width="10%" align="left" >图片</td>
                            <td width="8%" align="center" >价格</td>
                            <td width="12%"  align="center" >有效时间</td>
                            <td width="12%"  align="center" >适用范围</td>
                            <td width="12%"  align="center" >发布时间</td>
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
                            <td align="left" >{$vo.title}</td>
                            <td align="left" ><img src="{$vo.thumb}" height="30" ></td>
                            <td align="center" >{$vo.price|default="0.00"}</td>
                            <td align="center" >{$vo.validity_endtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >消费满{$vo.range|default="0.00"}元可使用</td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >
                                <if condition="$vo.status eq 0"> <span style="color: red">未审核</span></if>
                                <if condition="$vo.status eq 1"> 已审核</if>
                            </td>
                            <td align="center" > 
                                <a href="{:U('Admin/Coupons/edit',array('id'=>$vo['id']))}" >修改</a> |
                                <a href="{:U('Admin/Coupons/delete',array('id'=>$vo['id']))}" class="del" >删除</a> |
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Coupons/send',array('catid'=>$vo['id']))}','发放优惠券',1,700,400)">发放</a>
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
                          <if condition="authcheck('Admin/Coupons/listorder')">
                                  <button class="btn btn_submit mr10 " type="submit" name="submit" value="listorder" >排序</button>
                        </if> 
                 <if condition="authcheck('Admin/Coupons/review')">
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="review">审核</button>
                 </if>
                       <if condition="authcheck('Admin/Coupons/unreview')">
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="unreview">取消审核</button>
                       </if>
                     <if condition="authcheck('Admin/Coupons/del')">
                    <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">删除</button>
                     </if>
                </div>
            </div>
        </form>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
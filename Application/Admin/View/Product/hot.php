<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Product/hot')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                        购买次数：
                        <select class="select_2" name="querytype" style="width:60px;">
                            <option value=""  <empty name="Think.get.querytype">selected</empty>>全部</option>
                            <option value="eq" <if condition=" $Think.get.querytype eq 'eq'"> selected</if>>等于</option>
                            <option value="gt" <if condition=" $Think.get.querytype eq 'gt'"> selected</if>>大于</option>
                            <option value="lt" <if condition=" $Think.get.querytype eq 'lt'"> selected</if>>小于</option>
                        </select><input type="number" min="0" class="input length_2" name="buynum" style="width:60px;" value="{$Think.get.buynum}">
                        
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 

            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="8%" align="center" >产品编号</td>
                            <td width="15%" align="left" >产品名称</td>
                            <td width="10%" align="left" >产品LOGO</td>
                            <td width="15%" align="center" >所属类别</td>
                            <td width="10%" align="center" >产品规格</td>
                            <td width="10%" align="center" >购买总数</td>
                            <td width="10%" align="center" >销售总额</td>
                            <td width="15%"  align="center" >上架时间</td>
                            <td width="12%"  align="center" >所属店铺</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
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
                            <td align="center" ><img src="{$vo.thumb}" width="80" height="80"></td>
                            <td align="center" >{$vo.catname}</td>
                            <td align="center" >{$vo.standard}</td>
                            <td align="center" >{$vo.buynum|default="0"}</td>
                            <td align="center" >{$vo.totalmoney|default="0.00"}</td>
                            <td align="center" >{$vo.shelvestime|date="Y-m-d H:i:s",###}</td>
                            <td align="center" >{:getstoreinfo($vo['storeid'])}</td>
                            
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
</body>
</html>
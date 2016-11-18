<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Balance/runer')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        结算账期：<input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width: 80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width: 80px;">
                        配送员：
                        <select class="select_2" name="runerid" style="width:120px;">
                            <option value=""  <empty name="Think.get.runerid">selected</empty>>全部</option>
                            <volist name="runer" id="vo">
                                <option value="{$vo.ruid}" <if condition="$Think.get.runerid eq $vo['ruid']"> selected</if>>{$vo.realname}</option>
                            </volist>
                        </select>
                        结算：
                       <select class="select_2" name="status" style="width:100px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="0" <if condition=" $Think.get.status eq '0'"> selected</if>>未结算</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>部分结算</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>完成结算</option>
                        </select>
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
        <div class="h_a">已结算金额￥{$totalyes_money|default="0.00"} 未结算金额￥{$totalno_money|default="0.00"}</div>
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="10%" align="center">结算账期</td>
                            <td width="10%" align="center" >配送员</td>
                            <td width="10%" align="center" >订单汇总数</td>
                            <td width="10%" align="center" >订单汇总金额</td>
                            <td width="10%" align="center" >已结算金额</td>
                            <td width="10%" align="center" >未结算金额</td>
                            <td width="10%" align="center" >是否已结算</td>
                            <td width="10%" align="center" >最后打款时间</td>
                            <td width="10%" align="center" >管理操作</td>

                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center" >{$vo.date|date="Y-m-d",###}</td>
                            <td align="center" >{$vo.realname}<br/>{$vo.phone}</td>
                            <td align="center" >{$vo.ordernum|default="0"}</td>
                            <td align="center" >{$vo.ordermoney|default="0.00"}</td>
                            <td align="center" >{$vo.yes_money|default="0.00"}</td>
                            <td align="center" >{$vo.no_money|default="0.00"}</td>
                            <td align="center" >
                                <if condition="$vo.status eq 0"> 未结算</if>
                                <if condition="$vo.status eq 1"> 部分结算</if>
                                <if condition="$vo.status eq 2"> 完成结算</if>
                            </td>
                            <td align="center" >
                                <notempty name="vo['last_pay_time']">
                                {$vo.last_pay_time|date="Y-m-d H:i:s",###}
                                    <else />
                                    尚未打款
                                </notempty>
                            </td>
                            <td align="center" > 
                                <a href="{:U('Admin/Balance/runerdeal',array('id'=>$vo['id']))}" >结算</a> |
                                <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Balance/runerinfo',array('id'=>$vo['id']))}','查看详情',1,800,400)">查看详情</a>
                            </td>
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
    <script src="__JS__/content_addtop.js"></script>
    <script type="text/javascript" src="__JS__/birthday.js"></script>
</body>
</html>
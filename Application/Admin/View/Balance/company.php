<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Balance/company')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        结算账期：<div id="birthday_container" data-year="{$Think.get.year}" data-month="{$Think.get.month}" style="display: inline-block;">
                            <select name="year" style="width:80px;" class="chosen-select-no-single" ></select>
                            <select name="month" style="width:70px;" class="chosen-select-no-single" ></select>
                        </div>
                        企业：
                        <select class="select_2" name="companyid" style="width:180px;">
                            <option value=""  <empty name="Think.get.companyid">selected</empty>>全部</option>
                            <volist name="company" id="vo">
                                <option value="{$vo.id}" <if condition="$Think.get.companyid eq $vo['id']"> selected</if>>{$vo.title}</option>
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
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="10%" align="center">结算账期</td>
                            <td width="10%" align="center" >企业名称</td>
                            <td width="10%" align="center" >联系人</td>
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
                            <td align="center" >{$vo.year}/{$vo.month}</td>
                            <td align="center" >{$vo.title}</td>
                            <td align="center" >{$vo.realname}<br/>{$vo.tel}</td>
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
                                <empty name="vo['last_paytime']">
                                    尚未结算过
                                    <else />
                                    {$vo.last_paytime|date="Y-m-d H:i:s",###}
                                </empty>
                            </td>
                            <td align="center" > 
                                <if condition="authcheck('Admin/Balance/companyorderdeal')">
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Balance/companyorderdeal',array('id'=>$vo['id']))}','结算',1,700,400)">结算</a>
                <else/>
                 <font color="#cccccc">结算</font>
              </if>
                                <if condition="authcheck('Admin/Balance/companyorderinfo')">
           <a href="{:U('Admin/Balance/companyorderinfo',array('id'=>$vo['id']))}">查看明细</a>
                <else/>
                 <font color="#cccccc">查看明细</font>
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
    </div>

    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
    <script type="text/javascript" src="__JS__/birthday.js"></script>
    <script>  
        $(function () {
            $("#birthday_container").birthday();
        }); 
    </script>
</body>
</html>
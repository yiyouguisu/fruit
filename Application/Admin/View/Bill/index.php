<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Bill/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        申请时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        
                        审核：
                       <select class="select_2" name="status" style="width:70px;">
                            <option value=""  <empty name="Think.get.status">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.status eq '1'"> selected</if>>申请中</option>
                            <option value="2" <if condition=" $Think.get.status eq '2'"> selected</if>>审核成功</option>
                            <option value="3" <if condition=" $Think.get.status eq '3'"> selected</if>>审核失败</option>
                        </select>
                        店铺：
                        <select class="select_2" name="storeid" style="width:120px;">
                            <option value=""  <empty name="Think.get.storeid">selected</empty>>全部</option>
                            <volist name="store" id="vo">
                                <option value="{$vo.id}" <if condition="$Think.get.storeid eq $vo.id"> selected</if>>{$vo.title}</option>
                            </volist>
                        </select>
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="10%" align="center" >订单号</td>
                            <td width="10%" align="center" >订单金额</td>
                            <td width="10%" align="center" >发票类型</td>
                            <td width="10%" align="center" >发票抬头</td>
                            <td width="10%" align="center" >发票地址</td>
                            <td width="10%" align="center" >申请人</td>
                            <td width="10%" align="center" >申请时间</td>
                            <td width="10%" align="center" >审核状态</td>
                            <td width="10%" align="center" >审核时间</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}"></td>
                            <td align="center" >{$vo.orderid}</td>
                            <td align="center" >{$vo.money|default="0.00"}</td>
                            <td align="center" >
                                <eq name="vo['billtype']" value="0">未选择</eq >
                                <eq name="vo['billtype']" value="1">普通发票</eq >
                                <eq name="vo['billtype']" value="2">增值发票</eq >
                            </td>
                            <td align="center" >{$vo.billtitle}</td>
                            <td align="center" >{:getaddress($vo['billaddressid'])}</td>
                            <td align="center" >{:getuser($vo['uid'])}</td>
                            <td align="center" >
                                <empty name="vo['bill_apply_time']">
                                    未申请
                                    <else />
                                    {$vo.bill_apply_time|date="Y-m-d H:i:s",###}
                                </empty>
                            </td>
                            <td align="center" >
                              {:getreviewstatus($vo['bill_apply_status'])}
                            </td>
                            <td align="center" >
                                <eq name="vo['billtype']" value="0">
                                    未申请
                                    <else />
                                    <empty name="vo['bill_review_time']">
                                        审核中
                                        <else />
                                        {$vo.bill_review_time|date="Y-m-d H:i:s",###}
                                    </empty>
                                </eq>
                            </td>
                            <td align="center" > 
                                <eq name="vo.bill_apply_status" value="2">
                                    已成功开票</br>
                                    <a href="javascript:;" onClick="laert({$vo['bill_review_remark']});">查看详情</a>
                                    <else />
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Bill/review',array('orderid'=>$vo['orderid']))}','确认开票',1,700,420)">确认开票</a>
                                </eq>
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
    <script>
        function laert($content) {
            if ($content == '') {
                $content = "暂无审核备注";
            }
            layer.alert($content, {
                closeBtn: 0
            })
        }
    </script>
    
</body>
</html>
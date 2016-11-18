<include file="Common:Head" />
<script>
    $(function(){
        getchildren(0,true);
        initvals();
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
    })
     
    function getchildren(a,b) {
        $.ajax({
            url: "{:U('admin/Expand/getchildren')}",
            async: false,
            data: { id: a },
            success: function (data) {
                data=eval("("+data+")");
                if (data != null && data.length > 0) {
                    var ahtml = "<select class=''>";
                    if(b)
                    {
                        ahtml += "<option value=''>--请选择--</option>";
                    }
                    for (var i = 0; i < data.length; i++) {
                        ahtml += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                    }
                    ahtml += "</select>";
                    $(".jgbox").append(ahtml);
                }
            }
        });
                    getval();
    }
    function getval()
    {
        var vals="";
        $(".jgbox select").each(function(){
            var val=$(this).val();
            if(val!=null&&val!="")
            {
                vals+=',';
                vals+=val;
            }
        });
        if(vals!="")
        {
            vals=vals.substr(1);        
            $("#area").val(vals);
        }
    }
    function initvals()
    {
        var vals=$("#area").val();
        if(vals!=null&&vals!="")
        {
            var arr=new Array();
            arr=vals.split(",");
            for(var i=0;i<arr.length;i++)
            {
                if($.trim(arr[i]) !="")
                {
                    $(".jgbox select").last().val(arr[i]);
                    getchildren(arr[i],true);
                }
            }
        }
    }
  
</script>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Order/edit')}">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">标题:</td>
                                <td>{$data.title}</td>
                            </tr>
                            <tr>
                                <td>产品名称:</td>
                                <td>{$pro.catname}-{$pro.title}</td>
                            </tr>
                            <tr>
                                <td>产品价格/折扣价:</td>
                                <td>
                                    <if condition="$data.version eq 1">
                                        {$pro.price}/{$pro.discountprice}
                                    </if>
                                    <if condition="$data.version eq 2">
                                        {$pro.price1}/{$pro.discountprice1}
                                    </if>
                                </td>
                            </tr>
                            <tr>
                                <td>使用积分:</td>
                                <td><if condition="$data.integral eq 0">
                                    未使用积分
                                    <else />
                                    {$data.integral}积分
                                    </if>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>配送方式:</td>
                                <td>快递-{$data.delivery}</td>
                            </tr>
                             <tr>
                                <td>数量:</td>
                                <td>{$data.nums}件</td>
                            </tr>
                            <tr>
                                <td>价格总计:</td>
                                <td>
                                    <if condition="$data.version eq 1">
                                        ￥
                                    </if>
                                    <if condition="$data.version eq 2">
                                        $
                                    </if>
                                    {$data.money} 
                                </td>
                            </tr>
                            <if condition="$data.paystatus eq 1">
                            <tr>
                                <td>交易号:</td>
                                <td>{$data.trade_no}</td>
                            </tr>
                            <tr>
                                <td>支付时间:</td>
                                <td>
                                    <empty name="data.paytime">
                                        未支付
                                        <else />
                                        {$data.paytime|date="Y-m-d H:i:s",###} 
                                    </empty>
                                    
                                </td>
                            </tr>
                            </if>
                            <tr>
                                <td>支付方式:</td>
                                <td>
                                    <select name="paytype" id="paytype" >
                                        <option value="" <eq name="data.paytype" value="">selected</eq>>请选择支付方式</option>
                                        <option value="1" <eq name="data.paytype" value="1">selected</eq>>银联</option>
                                        <option value="2" <eq name="data.paytype" value="2">selected</eq>>支付宝</option>
                                        <option value="3" <eq name="data.paytype" value="3">selected</eq>>paypal</option>
                                        <option value="4" <eq name="data.paytype" value="4">selected</eq>>微信</option>
                                        <option value="5" <eq name="data.paytype" value="5">selected</eq>>银行汇款</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="h_a">收货信息</div>
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td  width="140">收货人:</td>
                                <td><input type="text" class="input" name="name" id="name" value="{$data.name}"></td>
                            </tr>
                            <tr>
                                <td>联系电话:</td>
                                <td><input type="text" class="input" name="tel" id="tel" value="{$data.tel}"></td>
                            </tr>
                            <tr>
                                <td>邮编:</td>
                                <td><input type="text" class="input" name="code" id="code" value="{$data.code}"></td>
                            </tr>
                            <if condition="$data.isotherarea eq 0">
                            <tr>
                                <td>收货地址:<input type="hidden" class="input" name="area" id="area" value="{$data.area}"></td>
                                <td class="jgbox"></td>
                            </tr>
                            </if>
                            <if condition="$data.isotherarea eq 1">新加坡</if>
                            <tr>
                                <td>街道地址</td>
                                <td height="50"><input type="text" class="input length_6 input_hd" name="address" id="address" value="{$data.address}"></td>
                            </tr>
                            <tr>
                                <td>货物号</td>
                                <td><input type="text" class="input length_6 input_hd" name="delivernumber" id="delivernumber" value="{$data.delivernumber}"></td>
                            </tr>
                        </tbody>
                    </table>
                    <if condition="$data.billtype neq 0">
                    <div class="h_a">发票信息</div>
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td  width="140">发票抬头:</td>
                                <td><input type="text" class="input" name="billtitle" id="billtitle" value="{$data.billtitle}"></td>
                            </tr>
                            <if condition="$data.billtype eq 2">
                            <tr>
                                <td>开户银行:</td>
                                <td><input type="text" class="input" name="billbankname" id="billbankname" value="{$data.billbankname}"></td>
                            </tr>
                            <tr>
                                <td>增值税号:</td>
                                <td><input type="text" class="input" name="billtariff" id="billtariff" value="{$data.billtariff}"></td>
                            </tr>
                            <tr>
                                <td>银行账号:</td>
                                <td><input type="text" class="input" name="billbanknumber" id="billbanknumber" value="{$data.billbanknumber}"></td>
                            </tr>
                            <tr>
                                <td>发票电话:</td>
                                <td><input type="text" class="input" name="billtel" id="billtel" value="{$data.billtel}"></td>
                            </tr>
                            <tr>
                                <td>发票地址</td>
                                <td height="50"><input type="text" class="input length_6 input_hd" name="billaddress" id="billaddress" value="{$data.billaddress}"></td>
                            </tr>
                            </if>
                        </tbody>
                    </table>
                    </if>
                    <notempty name="data.dailiid">
                    <div class="h_a">服务商</div>
                       <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">服务商</td>
                                <td>
                                  {$data['dailiid']}
                                </td>
                            </tr>
                       
                        </tbody>
                    </table>
                    </notempty>
                    <div class="h_a">状态</div>
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">支付状态</td>
                                <td>
                                    <ul class="switch_list cc ">
                                          <li>
                                            <label>
                                                <input type='radio' name='paystatus' value='0' <eq name="data.paystatus" value="0">checked</eq>>
                                                <span>未支付</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='paystatus' value='1' <eq name="data.paystatus" value="1">checked</eq>>
                                                <span>已支付</span></label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td width="140">配送状态</td>
                                <td>
                                    <ul class="switch_list cc ">
                                        <li>
                                            <label>
                                                <input type='radio' name='deliverystatus' value='0' <eq name="data.deliverystatus" value="0">checked</eq>>
                                                <span>未发货</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='deliverystatus' value='1' <eq name="data.deliverystatus" value="1">checked</eq>>
                                                <span>配送中</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='deliverystatus' value='2' <eq name="data.deliverystatus" value="2">checked</eq>>
                                                <span>已完成</span></label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="id" value="{$data.id}">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
  <script src="__JS__/jquery.js"></script>
<script>
    $(function(){
        aa();
        getchildren(0,true);
        initvals();
        $(".jgbox").delegate("select","change",function(){
            $(this).nextAll().remove();
            getchildren($(this).val(),true);
        });
        var i=1;
        $(".ve_align:checkbox").click(function () {
        if ($(this).is(":checked")) {
            i=i+1;
            changeprice(i);
        } else {
           i=i-1;
           changeprice(i);
        }
      });
	    $("#nums").blur(function () {
		   var nums=parseInt($("#nums").val());
		   if(nums<1){
			   nums=1;
			   $("#nums").val(1); 
		   }
		  
		var total='';
        total=(parseFloat($("#price").val())+parseFloat($("#delivery").val()))*parseInt($("#nums").val());
        $("#total").val(total);
	   });  
	  
   	  
    })
     function changeprice(i) {
         if(i==0){
             alert("请至少选择一个阶段");
             $("#price").val(parseFloat($("#price1").val()));
         }else if(i==2){
              $("#price").val(parseFloat($("#price2").val()));
         }else if(i==3){
              $("#price").val(parseFloat($("#price3").val()));
         }else{
             $("#price").val(parseFloat($("#price1").val()));
         }
         aa(); 
     }
     
      function aa() {
        var total='';
        total=(parseFloat($("#price").val())+parseFloat($("#delivery").val()))*parseInt($("#nums").val());
        $("#total").val(total);
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

<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Order/add')}">
                <div class="h_a">订单信息</div>
                <div class="table_list">
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td width="140">选择阶段:</td>
                                <td >
                                    
                                <input type="checkbox" name="jieduan[]" value="8" id="jieduan1"   class="ve_align" checked/> 阅读1阶段  
                                <input type="checkbox" name="jieduan[]" value="9" id="jieduan2"   class="ve_align"/> 阅读2阶段  
                                <input type="checkbox" name="jieduan[]" value="10" id="jieduan3" class="ve_align"/> 阅读3阶段
                                <input type="hidden" value="{$pro.price1}" name="price1" id="price1"  />
                                <input type="hidden" value="{$pro.price2}" name="price2" id="price2"  />
                                <input type="hidden" value="{$pro.price3}" name="price3" id="price3"  />
                                
                                 <input type="hidden" value="{$pro.price1}" name="price" id="price"  />
                                 
                                </td>
                            </tr>
                          
                            <tr>
                                <td>配送方式:</td>
                                <td>
                                     <select name="delivery" id="delivery" onChange="aa();">
                                      <volist name="delivery" id="delivery">    
                                      <option value="{$delivery.money}" <eq name="delivery.money" value="$data['delivery']">selected</eq>>{$delivery.name}-{$delivery.money}</option>
                                    </volist>
                                    
                                </td>
                            </tr>

                            <tr>
                                <td>订购数量:</td>
                                <td><input type="text" class="input" name="nums" id="nums" value="<if condition="($data.nums eq 0)">1<else /> {$data.nums} </if> " onkeyup="this.value=this.value.replace(/^ +| +$/g,'')"></td>
                            </tr>
                            
                            <tr>
                                <td>价格总计:</td>
                                <td><input type="text" class="input" name="money" id="total" value="{$data.money}"> </td>
                            </tr>
                            
                           
                            <tr>
                                <td>支付账号:</td>
                                <td><input type="text" class="input" name="buyer_email" id="total" value="{$data.buyer_email}"> </td>
                            </tr>
                              <tr>
                                <td>交易号:</td>
                                <td><input type="text" class="input" name="trade_no" id="total" value="{$data.trade_no}"> </td>
                            </tr>
                            <tr>
                                <td>支付时间:</td>
                                <td><input type="text" class="input" name="notify_time" id="total" value="{$data.notify_time}"> </td>
                            </tr>
                          
                            <tr>
                                <td>支付方式:</td>
                                <td>
                                      <select name="paytype" id="paytype" >
                                        <option value="1" <eq name="data.paytype" value="1">selected</eq>>支付宝</option>
                                        <option value="2" <eq name="data.paytype" value="2">selected</eq>>财付通</option>
                                       <option value="4" <eq name="data.paytype" value="4">selected</eq>>网银在线</option>
                                        <option value="3" <eq name="data.paytype" value="3">selected</eq>>银行汇款</option>
                                      </select>
                                </td>
                            </tr>
                            <tr>
                                <td>补充说明:</td>
                                <td><textarea name="remark" rows="5" cols="57">{$data.remark}</textarea></td>
                            </tr>
                            </tr>
                        </tbody>
                    </table>
                    <div class="h_a">收货信息</div>
                    <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                        <tbody>
                            <tr>
                                <td  width="140">收货人:</td>
                                <td><input type="text" class="input" name="name" id="name" value="{$data.name}"> <font color="#FF0000">*</font> </td>
                            </tr>
                            <tr>
                                <td>联系电话:</td>
                                <td><input type="text" class="input" name="tel" id="tel" value="{$data.tel}">  <font color="#FF0000">*</font></td>
                            </tr>
                            <tr>
                                <td>收货地址:<input type="hidden" class="input" name="area" id="area" value="{$data.area}"> <font color="#FF0000">*</font></td>
                                <td class="jgbox"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><textarea name="address" rows="5" cols="57">{$data.address}</textarea></td>
                            </tr>
                        </tbody>
                    </table>
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
                                                <input type='radio' name='status' value='0' <eq name="data.status" value="0">checked</eq>>
                                                <span>未发货</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='status' value='1' <eq name="data.status" value="1">checked</eq>>
                                                <span>配送中</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='status' value='2' <eq name="data.status" value="2">checked</eq>>
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
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">增加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
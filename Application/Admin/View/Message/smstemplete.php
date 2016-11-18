<include file="Common:Head" />
<style>
.pop_nav{
  padding: 0px;
}
.pop_nav ul{
  border-bottom:1px solid green;
  padding:0 5px;
  height:25px;
  clear:both;
}
.pop_nav ul li.current a{
  border:1px solid green;
  border-bottom:0 none;
  color:#333;
  font-weight:700;
  background:#F3F3F3;
  position:relative;
  border-radius:2px;
  margin-bottom:-1px;
}

</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">

        <include file="Common:Nav"/>
         <div class="pop_nav">
    <ul class="J_tabs_nav">
      <li class="current"><a href="javascript:;;">邮件模版</a></li>
      <li class=""><a href="javascript:;;">短信模版</a></li>
      <li class=""><a href="javascript:;;">站内信模版</a></li>
    </ul>
  </div>
  <form name="myform" id="myform" action="{:U('Admin/Config/template')}" method="post" enctype="multipart/form-data">
  <div class="h_a">温馨提示</div>
  <div class="prompt_text">
    <p>1、<literal><font color="red">{#username#}</font> 代表用户名，<font color="red">{#link#}</font> 代表激活链接，<font color="red">{#code#}</font> 代表验证码，<font color="red">{#money#}</font> 代表操作金额，<font color="red">{#title#}</font> 代表项目名称</literal></p>
    <p>2、短信模版不要太长，不要包含违法关键字，内容结尾请加网站签名，网站签名格式为：【网站名称】</p>
  </div>
    <div class="J_tabs_contents">
      <div>
        <div class="h_a">邮件模版</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
           
            <tr>
              <th width="150">用户注册成功：</th>
              <td>
              <textarea name="email_reg" style="width:500px;height:60px;">{$Site.email_reg}</textarea>
                
              </td>
            </tr>
            <tr>
              <th width="150">密码找回提示：</th>
              <td>
                <textarea name="email_password" style="width:500px;height:60px;">{$Site.email_password}</textarea>
              
              </td>
            </tr>
                <tr>
              <th width="150">充值提示：</th>
              <td>
                <textarea name="email_recharge" style="width:500px;height:60px;">{$Site.email_recharge}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">提现提示：</th>
              <td>
                <textarea name="email_cash" style="width:500px;height:60px;">{$Site.email_cash}</textarea>
              
              </td>
            </tr>
             <tr>
              <th width="150">投资提示：</th>
              <td>
                <textarea name="email_invest" style="width:500px;height:60px;">{$Site.email_invest}</textarea>
              
              </td>
            </tr>
              <tr>
              <th width="150">借款提示：</th>
              <td>
                <textarea name="email_borrow" style="width:500px;height:60px;">{$Site.email_borrow}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">还款提示：</th>
              <td>
                <textarea name="email_refund" style="width:500px;height:60px;">{$Site.email_refund}</textarea>
              
              </td>
            </tr>
            
          </table>
        </div>
      </div>
      <div style="display:none">
        <div class="h_a">短信模版</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
              <th width="150">短信验证码提示：</th>
              <td>
                <textarea name="phone_code" style="width:500px;height:60px;">{$Site.phone_code}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">充值提示：</th>
              <td>
                <textarea name="phone_recharge" style="width:500px;height:60px;">{$Site.phone_recharge}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">提现提示：</th>
              <td>
                <textarea name="phone_cash" style="width:500px;height:60px;">{$Site.phone_cash}</textarea>
              
              </td>
            </tr>
             <tr>
              <th width="150">投资提示：</th>
              <td>
                <textarea name="phone_invest" style="width:500px;height:60px;">{$Site.phone_invest}</textarea>
              
              </td>
            </tr>
              <tr>
              <th width="150">借款提示：</th>
              <td>
                <textarea name="phone_borrow" style="width:500px;height:60px;">{$Site.phone_borrow}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">还款提示：</th>
              <td>
                <textarea name="phone_refund" style="width:500px;height:60px;">{$Site.phone_refund}</textarea>
              
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div style="display:none">
        <div class="h_a">站内信模版</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
             <tr>
              <th width="150">注册成功：</th>
              <td>
                <textarea name="mes_reg" style="width:500px;height:60px;">{$Site.mes_reg}</textarea>
              </td>
            </tr>
              <tr>
              <th width="150">认证提示：</th>
              <td>
                <textarea name="mes_attestation" style="width:500px;height:60px;">{$Site.mes_attestation}</textarea>
              <span class="gray"><literal>{$attestation}代表认证类型，比如手机认证、实名认证...</literal></span>
              </td>
                   
            </tr>
          
            <tr>
              <th width="150">充值提示：</th>
              <td>
                <textarea name="mes_recharge" style="width:500px;height:60px;">{$Site.mes_recharge}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">提现成功：</th>
              <td>
                <textarea name="mes_cesh" style="width:500px;height:60px;">{$Site.mes_cesh}</textarea>
              </td>
            </tr>
             <tr>
              <th width="150">投标成功：</th>
              <td>
                <textarea name="mes_invest" style="width:500px;height:60px;">{$Site.mes_invest}</textarea>
              
              </td>
            </tr>
             <tr>
              <th width="150">发标初审提示：</th>
              <td>
                <textarea name="mes_firstreview" style="width:500px;height:60px;">{$Site.mes_firstreview}</textarea>
              
              </td>
            </tr>
            <tr>
              <th width="150">发标复审提示：</th>
              <td>
                <textarea name="mes_retrial" style="width:500px;height:60px;">{$Site.mes_retrial}</textarea>
              
              </td>
            </tr>
            
          </table>
        </div>
      </div>
    </div>
    <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 " type="submit">提交</button>
      </div>
    </div>
  </form>
    </div>
    <script src="__JS__/common.js?v"></script>
</body>
</html>
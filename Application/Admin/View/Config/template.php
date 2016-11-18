<include file="Common:Head" />
<style>
    .pop_nav {
        padding: 0px;
    }

        .pop_nav ul {
            border-bottom: 1px solid green;
            padding: 0 5px;
            height: 25px;
            clear: both;
        }

            .pop_nav ul li.current a {
                border: 1px solid green;
                border-bottom: 0 none;
                color: #333;
                font-weight: 700;
                background: #F3F3F3;
                position: relative;
                border-radius: 2px;
                margin-bottom: -1px;
            }
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">

        <include file="Common:Nav" />
        <div class="pop_nav">
            <ul class="J_tabs_nav">
                <li class="current">
                    <a href="javascript:;;">邮件模版</a>
                </li>
                <!--<li class="">
                    <a href="javascript:;;">短信模版</a>
                </li>
                <li class="">
                    <a href="javascript:;;">站内信模版</a>
                </li>-->
            </ul>
        </div>
        <form name="myform" class="J_ajaxForm" id="myform" action="{:U('Admin/Config/template')}" method="post" enctype="multipart/form-data">
            <div class="h_a">温馨提示</div>
            <div class="prompt_text">
                <p>1、<literal><font color="red">{#username#}</font> 代表用户名，<font color="red">{#link#}</font> 代表激活链接，<font color="red">{#code#}</font> 代表验证码，<font color="red">{#money#}</font> 代表操作金额，<font color="red">{#date#}</font> 代表操作日期，<font color="red">{#product#}</font> 代表产品名称，<font color="red">{#store#}</font> 代表商户名称，<font color="red">{#bpaymoney#}</font> 代表霸王币操作金额，<font color="red">{#memberrole#}</font> 代表会员申请角色</literal></p>
            </div>
            <div class="J_tabs_contents">
                <div>
                    <div class="h_a">邮件模版</div>
                    <div class="table_full">
                        <table width="100%" class="table_form ">

                            <tr>
                                <th width="150">用户申请加入企业：</th>
                                <td>
                                    <input type="text" name="email_joincompany_subject" class="input length_6 input_hd" placeholder="请输入邮件主题" id="email_joincompany_subject" value="{$Site.email_joincompany_subject}"><span class="gray"><literal>请填写邮件主题</literal></span>
                                    </br>
             	                    <textarea name="email_joincompany" id="email_joincompany" style="width: 500px; height: 120px;">{$Site.email_joincompany}</textarea>
                                </td>
                            </tr>
                            <!--<tr>
                                <th width="150">密码找回提示：</th>
                                <td>
                                    <input type="text" name="email_password_subject" class="input length_6 input_hd" placeholder="请输入邮件主题" id="email_password_subject" value="{$Site.email_password_subject}"><span class="gray"><literal>请填写邮件主题</literal></span>
                                    </br>
              	                    <textarea name="email_password" id="email_password" style="width: 500px; height: 120px;">{$Site.email_password}</textarea>
                                </td>
                            </tr>-->
                        </table>
                    </div>
                </div>
                <div style="display: none">
                    <div class="h_a">短信模版</div>
                    <div class="table_full">
                        <table width="100%" class="table_form ">
                            <tr>
                                <th width="150">短信验证码提示：</th>
                                <td>
                                    <textarea name="phone_code" style="width: 500px; height: 120px;">{$Site.phone_code}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">成功下单提示：</th>
                                <td>
                                    <textarea name="phone_order_done_success" style="width: 500px; height: 120px;">{$Site.phone_order_done_success}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">成功支付订单提示：</th>
                                <td>
                                    <textarea name="phone_order_pay_success" style="width: 500px; height: 120px;">{$Site.phone_order_pay_success}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">注册审核成功提示：</th>
                                <td>
                                    <textarea name="phone_reg_apply_success" style="width: 500px; height: 120px;">{$Site.phone_reg_apply_success}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">注册审核失败提示：</th>
                                <td>
                                    <textarea name="phone_reg_apply_error" style="width: 500px; height: 120px;">{$Site.phone_reg_apply_error}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">产品发布审核成功提示：</th>
                                <td>
                                    <textarea name="phone_product_add_success" style="width: 500px; height: 120px;">{$Site.phone_product_add_success}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">产品发布审核失败提示：</th>
                                <td>
                                    <textarea name="phone_product_add_error" style="width: 500px; height: 120px;">{$Site.phone_product_add_error}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">店铺发布审核成功提示：</th>
                                <td>
                                    <textarea name="phone_store_add_success" style="width: 500px; height: 120px;">{$Site.phone_store_add_success}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">店铺发布审核失败提示：</th>
                                <td>
                                    <textarea name="phone_store_add_error" style="width: 500px; height: 120px;">{$Site.phone_store_add_error}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">会员升级提示：</th>
                                <td>
                                    <textarea name="phone_upgrade_success" style="width: 500px; height: 120px;">{$Site.phone_upgrade_success}</textarea>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div style="display: none">
                    <div class="h_a">站内信模版</div>
                    <div class="table_full">
                        <table width="100%" class="table_form ">
                            <tr>
                                <th width="150">注册成功：</th>
                                <td>
                                    <textarea name="mes_reg" style="width: 500px; height: 120px;">{$Site.mes_reg}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th width="150">认证提示：</th>
                                <td>
                                    <textarea name="mes_attestation" style="width: 500px; height: 120px;">{$Site.mes_attestation}</textarea>
                                    <!-- <span class="gray"><literal>{$attestation}代表认证类型，比如手机认证、实名认证...</literal></span> -->
                                </td>

                            </tr>


                        </table>
                    </div>
                </div>
            </div>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                </div>
            </div>
        </form>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
    <script type="text/javascript">
        //CKEDITOR.replace('email_reg', { toolbar: 'Full' });
        //CKEDITOR.replace('email_password', { toolbar: 'Full' });
        CKEDITOR.replace('email_joincompany', { toolbar: 'Full' });
    </script>
</body>
</html>
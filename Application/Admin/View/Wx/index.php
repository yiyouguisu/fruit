<include file="Common:Head" />
<style>
.pop_nav{
    padding: 0px;
}
.pop_nav ul{
    border-bottom:1px solid #266AAE;
    padding:0 5px;
    height:25px;
    clear:both;
}
.pop_nav ul li.current a{
    border:1px solid #266AAE;
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
        <div class="h_a">温馨提示</div>
            <div class="prompt_text">
            <p>1、需要正常使用功能，请到 <a href="https://mp.weixin.qq.com/">微信公众平台</a> 申请接口</p>
            <p>2、有的功能无法正常使用，受限属于接口权限</p>
        </div>
        <div class="pop_nav">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">基本设置</a></li>
              <li class=""><a href="javascript:;;">默认消息设置</a></li>
            </ul>
        </div>
        <form name="myform" class="J_ajaxForm" id="myform" action="{:U('Admin/Wx/index')}" method="post" enctype="multipart/form-data">
            
            <div class="J_tabs_contents">
              <div class="tba">
                <div class="h_a">基本设置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">接口URL:</th>
                                <td><input type="text" class="input"  name="apiurl" value="{$data.apiurl}" size="80">请将此地址复制到<a href="https://mp.weixin.qq.com/">微信公众平台</a>接口URL处</td>
                            </tr>
                            <tr>
                                <th width="140">微信Token:</th>
                                <td><input type="text" class="input"  name="apitoken" value="{$data.apitoken}" size="40">请与<a href="https://mp.weixin.qq.com/">微信公众平台</a>Token保持一致</td>
                            </tr>
                            <tr>
                                <th width="140">AppId:</th>
                                <td><input type="text" class="input"  name="appid" value="{$data.appid}" size="40">请与<a href="https://mp.weixin.qq.com/">微信公众平台</a>开发者凭据AppId保持一致</td>
                            </tr>
                            <tr>
                                <th width="140">AppSecret:</th>
                                <td><input type="text" class="input"  name="appsecret" value="{$data.appsecret}" size="40">请与<a href="https://mp.weixin.qq.com/">微信公众平台</a>开发者凭据AppSecret保持一致</td>
                            </tr>
                        </table>
                </div>
              </div>
              <div class="tba">
                <div class="h_a">默认消息设置</div>
                <div class="table_full">
                        <table cellpadding=0 cellspacing=0 width="100%" class="table_form" >
                            <tr>
                                <th width="140">被添加自动回复:</th>
                                <td><input type="text" class="input"  name="jpush_member_appkey" value="{$Site.jpush_member_appkey}" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">消息自动回复:</th>
                                <td><input type="text" class="input"  name="jpush_member_masterSecret" value="{$Site.jpush_member_masterSecret}" size="40"></td>
                            </tr>
                            <tr>
                                <th width="140">关键词回复:</th>
                                <td><input type="text" class="input"  name="jpush_run_appkey" value="{$Site.jpush_run_appkey}" size="40"></td>
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
</body>
</html>
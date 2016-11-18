<include file="Common:Head" />
<body class="J_scroll_fixed">
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
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="pop_nav" style="margin-bottom:0px">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">注册协议</a></li>
              <li class=""><a href="javascript:;;">帮助中心</a></li>
              <li class=""><a href="javascript:;;">关于蔬果先生</a></li>
            </ul>
        </div>
        <form  id="myform" method="post" enctype="multipart/form-data"  action="{:U('Admin/Config/service')}">
            <div class="h_a"></div>
            <div class="J_tabs_contents" >
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="reg_service" id="reg_service">{$Site.reg_service}</textarea>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="help" id="help">{$Site.help}</textarea>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <textarea  name="about_us" id="about_us">{$Site.about_us}</textarea>
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
    CKEDITOR.replace('reg_service',{toolbar : 'Full'});
    CKEDITOR.replace('help',{toolbar : 'Full'});
    CKEDITOR.replace('about_us',{toolbar : 'Full'});
</script>
</body>
</html>
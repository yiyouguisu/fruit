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
         <div class="pop_nav">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">滴答跑腿分享</a></li>
              <li class=""><a href="javascript:;;">滴答跑腿邀请好友加入</a></li>
              <li class=""><a href="javascript:;;">普通用户邀请好友加入</a></li>
            </ul>
          </div>
        <form name="myform" class="J_ajaxForm" id="myform" action="{:U('Admin/Config/share')}" method="post" enctype="multipart/form-data">
           <div class="h_a">温馨提示</div>
            <div class="prompt_text">
            <p>1、文案信息不要太长，不要包含违法关键字</p>
            <p>2、<span class="gray"><literal><font color="red">{#invitecode#}</font> 代表邀请码， <font color="red">{#money#}</font> 代表红包金额（数字） </literal></span></p>
           </div>
    <div class="J_tabs_contents">
      <div class="tba">
        <div class="h_a">滴答跑腿分享</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
                <th width="100">分享标题：</th>
                <td><input type="text" name="run_share_title" id="run_share_title" class="input length_5" value="{$Site.run_share_title}" ></td>
            </tr>
            <tr>
                <th>分享图片：</th>
                <td><input type="text" name="run_share_image" id="run_share_image" class="input length_5" value="{$Site.run_share_image}" style="float: left"  ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button upload" value="选择上传" id="uploadbtn_run_share_image"  data-id="run_share_image"> <span class="gray"> 双击文本框查看图片</span></td>
            </tr>
            <tr>
                <th>分享内容</th>
                <td>
                    <textarea  name="run_share_content" id="run_share_content" class="valid" style="width:500px;height:80px;">{$Site.run_share_content}</textarea>
                </td>
            </tr>
            <tr>
                <th width="100">分享链接：</th>
                <td><input type="text" name="run_share_link" id="run_share_link" class="input length_5" value="{$Site.run_share_link}" ></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tba">
        <div class="h_a">滴答跑腿邀请好友加入</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
                <th width="100">分享标题：</th>
                <td><input type="text" name="run_invite_share_title" id="run_invite_share_title" class="input length_5" value="{$Site.run_invite_share_title}" ></td>
            </tr>
            <tr>
                <th>分享图片：</th>
                <td><input type="text" name="run_invite_share_image" id="run_invite_share_image" class="input length_5" value="{$Site.run_invite_share_image}" style="float: left"  ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button upload" value="选择上传" id="uploadbtn_run_invite_share_image" data-id="run_invite_share_image"> <span class="gray"> 双击文本框查看图片</span></td>
            </tr>
            <tr>
                <th>分享内容</th>
                <td>
                    <textarea  name="run_invite_share_content" id="run_invite_share_content" class="valid" style="width:500px;height:80px;">{$Site.run_invite_share_content}</textarea>
                </td>
            </tr>
            <tr>
                <th width="100">分享链接：</th>
                <td><input type="text" name="run_invite_share_link" id="run_invite_share_link" class="input length_5" value="{$Site.run_invite_share_link}" ></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tba">
        <div class="h_a">普通用户邀请好友加入</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
                <th width="100">分享标题：</th>
                <td><input type="text" name="member_invite_share_title" id="member_invite_share_title" class="input length_5" value="{$Site.member_invite_share_title}" ></td>
            </tr>
            <tr>
                <th>分享图片：</th>
                <td><input type="text" name="member_invite_share_image" id="member_invite_share_image" class="input length_5" value="{$Site.member_invite_share_image}" style="float: left"  ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button upload" value="选择上传" id="uploadbtn_member_invite_share_image" data-id="member_invite_share_image"> <span class="gray"> 双击文本框查看图片</span></td>
            </tr>
            <tr>
                <th>分享内容</th>
                <td>
                    <textarea  name="member_invite_share_content" id="member_invite_share_content" class="valid" style="width:500px;height:80px;">{$Site.member_invite_share_content}</textarea>
                </td>
            </tr>
            <tr>
                <th width="100">分享链接：</th>
                <td><input type="text" name="member_invite_share_link" id="member_invite_share_link" class="input length_5" value="{$Site.member_invite_share_link}" ></td>
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
     <include file="Common:upload"/>
</body>
</html>
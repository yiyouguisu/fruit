<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <div class="wrap jj">
    	<div class="h_a">温馨提示</div>
	 	<div class="prompt_text">
	    	<p>1、<literal><font color="red">{#nickname#}</font>代表用户名</literal></p>
	  	</div>
    	<div class="h_a">选择条件</div>
    	<form method="post"  action="{:U('Admin/Message/send')}">
        <input type="hidden" value="1" name="search">
        <div class="search_type cc mb10">
            <div class="mb10"> 
                <span class="mr20">
                          注册时间：
                    <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.post.start_time}" style="width:80px;">
                   -
                    <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.post.end_time}" style="width:80px;">
                  兴趣行业分类：
                    <select class="select_1" name="catid">
                        <option value="" >全部</option>
                        <volist name="category" id="vo">
                          <option value="{$vo.id}">{$vo.catname}</option>
                        </volist>
                    </select>
                    兴趣内容分类：
                    <select class="select_1" name="catid1">
                        <option value="" >全部</option>
                        <volist name="category1" id="vo">
                          <option value="{$vo.id}">{$vo.catname}</option>
                        </volist>
                    </select> 
                    性别：
                    <select class="select_1" name="sex">
                        <option value="">全部</option>
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </span>
                </div>
                <div class="mb10"> 
                <span class="mr20">
                    关键字：
                    <select class="select_2" name="searchtype" >
                        <option value='0'>昵称</option>
                        <option value='1'>真实姓名</option>
                        <option value='2'>助手号</option>
                        <option value='3'>手机</option>
                        <option value='4'>ID</option>
                    </select>
                    <input type="text" class="input length_2" name="keyword" value="" placeholder="请输入关键字...">
                </span> 
            </div>
        </div> 
        <div class="h_a">发送内容</div>
        <textarea  name="content" id="content" class="valid" style="width:695px;height:200px;">{$data.description}</textarea>
        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">发送</button>
            </div>
        </div>
        </form>
    </div>
</div>
     <script src="__JS__/common.js?v"></script>
      <script src="__JS__/content_addtop.js"></script>
</body>
</html>
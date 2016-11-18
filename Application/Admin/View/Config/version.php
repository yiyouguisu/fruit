<include file="Common:Head" />
<script type="text/javascript" src="__JS__/jquery-1.7.1.js"></script>
<script type="text/javascript" src="__JS__/ajaxfileupload.js"></script>
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
<script>
    function ajaxFileUpload(obj) {
            $.ajaxFileUpload({
                url: "{:U('Admin/Public/uploadone')}",
                secureuri: false, //一般设置为false
                fileElementId: 'fileupload_'+obj,
                dataType: 'text',
                success: function (data, status) {
                    $("#"+obj).val("http://www.esugo.cn"+data);
                },
                error: function (data, status, e) { }
            })
            return false;
        }
</script>
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="pop_nav" style="margin-bottom:0px">
            <ul class="J_tabs_nav">
              <li class="current"><a href="javascript:;;">用户端安卓版本信息</a></li>
              <li class=""><a href="javascript:;;">用户端IOS版本信息</a></li>
              <li class=""><a href="javascript:;;">配送端安卓版本信息</a></li>
              <li class=""><a href="javascript:;;">配送端IOS版本信息</a></li>
            </ul>
        </div>
        <form class="J_ajaxForm" id="myform" method="post" enctype="multipart/form-data"  action="{:U('Admin/Config/version')}">
            <div class="h_a"></div>
            <div class="J_tabs_contents" >
                <div class="tba">
                    <div class="table_full">
                        <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="120">当前版本号</th>
                                <td>{$member_anzhuo.version}</td>
                            </tr>
                            <tr>
                                <th>当前版本下载路径</th>
                                <td><a href="{$member_anzhuo['url']}" target="_blank">{$member_anzhuo.url}</a></td>
                            </tr>
                            <tr>
                                <th>当前版本描述</th>
                                <td><textarea  class="valid" style="width:500px;height:80px;">{$member_anzhuo.info}</textarea></td>
                            </tr>
                            
                            <tr>
                                <th>上次更新时间</th>
                                <td>{$member_anzhuo.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                                <th>新版本号</th>
                                <td>
                                    <input type="text" name="member_anzhuo_version" class="input length_6 input_hd" placeholder="请输入版本号" id="member_anzhuo_version" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>新版本描述</th>
                                <td><textarea  name="member_anzhuo_info" id="member_anzhuo_info"  class="valid" style="width:500px;height:80px;"></textarea></td>
                            </tr>
                            <tr>
                                <th>新版本下载路径：</th>
                                <td><input type="text" name="member_anzhuo_url" id="member_anzhuo_url" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                            <tr>
                                <th>新版本安装包：</th>
                                <td><input type="file" name="Filedata" id="fileupload_member_anzhuo_url"  onchange="ajaxFileUpload('member_anzhuo_url')" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="120">当前版本号</th>
                                <td>{$member_ios.version}</td>
                            </tr>
                            <tr>
                                <th>当前版本下载路径</th>
                                <td><a href="{$member_ios['url']}" target="_blank">{$member_ios.url}</a></td>
                            </tr>
                            <tr>
                                <th>当前版本描述</th>
                                <td><textarea  class="valid" style="width:500px;height:80px;">{$member_ios.info}</textarea></td>
                            </tr>
                            <tr>
                                <th>上次更新时间</th>
                                <td>{$member_ios.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                                <th>新版本号</th>
                                <td>
                                    <input type="text" name="member_ios_version" class="input length_6 input_hd" placeholder="请输入版本号" id="member_ios_version" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>新版本描述</th>
                                <td><textarea  name="member_ios_info" id="member_ios_info"  class="valid" style="width:500px;height:80px;"></textarea></td>
                            </tr>
                            <tr>
                                <th>新版本下载路径：</th>
                                <td><input type="text" name="member_ios_url" id="member_ios_url" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                            <tr>
                                <th>新版本安装包：</th>
                                <td><input type="file" name="Filedata" id="fileupload_member_ios_url"  onchange="ajaxFileUpload('member_ios_url')" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="120">当前版本号</th>
                                <td>{$run_anzhuo.version}</td>
                            </tr>
                            <tr>
                                <th>当前版本下载路径</th>
                                <td><a href="{$run_anzhuo['url']}" target="_blank">{$run_anzhuo.url}</a></td>
                            </tr>
                            <tr>
                                <th>当前版本描述</th>
                                <td><textarea  class="valid" style="width:500px;height:80px;">{$run_anzhuo.info}</textarea></td>
                            </tr>
                            
                            <tr>
                                <th>上次更新时间</th>
                                <td>{$run_anzhuo.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                                <th>新版本号</th>
                                <td>
                                    <input type="text" name="run_anzhuo_version" class="input length_6 input_hd" placeholder="请输入版本号" id="run_anzhuo_version" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>新版本描述</th>
                                <td><textarea  name="run_anzhuo_info" id="run_anzhuo_info"  class="valid" style="width:500px;height:80px;"></textarea></td>
                            </tr>
                            <tr>
                                <th>新版本下载路径：</th>
                                <td><input type="text" name="run_anzhuo_url" id="run_anzhuo_url" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                            <tr>
                                <th>新版本安装包：</th>
                                <td><input type="file" name="Filedata" id="fileupload_run_anzhuo_url"  onchange="ajaxFileUpload('run_anzhuo_url')" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="tba">
                    <div class="table_full">
                        <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="120">当前版本号</th>
                                <td>{$run_ios.version}</td>
                            </tr>
                            <tr>
                                <th>当前版本下载路径</th>
                                <td><a href="{$run_ios['url']}" target="_blank">{$run_ios.url}</a></td>
                            </tr>
                            <tr>
                                <th>当前版本描述</th>
                                <td><textarea  class="valid" style="width:500px;height:80px;">{$run_ios.info}</textarea></td>
                            </tr>
                            <tr>
                                <th>上次更新时间</th>
                                <td>{$run_ios.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                                <th>新版本号</th>
                                <td>
                                    <input type="text" name="run_ios_version" class="input length_6 input_hd" placeholder="请输入版本号" id="run_ios_version" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>新版本描述</th>
                                <td><textarea  name="run_ios_info" id="run_ios_info"  class="valid" style="width:500px;height:80px;"></textarea></td>
                            </tr>
                            <tr>
                                <th>新版本下载路径：</th>
                                <td><input type="text" name="run_ios_url" id="run_ios_url" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                            <tr>
                                <th>新版本安装包：</th>
                                <td><input type="file" name="Filedata" id="fileupload_run_ios_url"  onchange="ajaxFileUpload('run_ios_url')" class="input length_6 input_hd" placeholder="请输入新版本下载路径" value=""></td>
                            </tr>
                        </tbody>
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
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">

            <form method="post" action="{:U('Admin/Company/add')}">
                <div class="h_a">企业基本信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">企业名称</th>
                                <td><input type="test" name="title" class="input" id="title" value="{$data.company}" ></td>
                            </tr>
                            <tr>
                                <th>企业LOGO</th>
                                <td>
                                    <img id="head" src="{$data.head}" width="80" height="80" />
                                    <input type="hidden" name="head" id="image" class="input" value="{$data.head}" style="float: left"  runat="server">&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" ></td>
                                </td>
                            </tr>
                            <tr>
                                <th>企业编号</th>
                                <td><input type="text" class="input"  name="companynumber" value="{$data.companynumber}" /></td>
                            </tr>
                            <tr>
                                <th width="80">登录名</th>
                                <td>
                                    <input type="text" name="loginname" class="input" id="loginname" value="{$companyuser.username}" >
                                </td>
                            </tr>
                            <tr>
                                <th>密码</th>
                                <td><input type="password" name="password" class="input" id="password" value="">
                                    <span class="gray">请输入密码</span></td>
                            </tr>
                            <tr>
                                <th>确认密码</th>
                                <td><input type="password" name="pwdconfirm" class="input" id="pwdconfirm" value="">
                                    <span class="gray">请输入确认密码</span></td>
                            </tr>
                            <tr>
                                <th width="80">企业负责人</th>
                                <td>
                                    <input type="text" name="username" class="input" id="username" value="{$data.username}" >
                                </td>
                            </tr>
                            <tr>
                                <th>负责人联系方式</th>
                                <td><input type="text" name="tel" value="{$data.tel}" class="input" id="tel" size="30"></td>
                            </tr>
                            <tr>
                                <th>E-mail</th>
                                <td><input type="text" name="email" value="{$data.email}" class="input" id="email" size="30"></td>
                            </tr>
                            <tr>
                                <th>审核</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='status' value='1' checked>
                      <span>审核</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='status' value='0' >
                      <span>未审核</span></label>
                  </li>
                </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="applyid" value="{$data.id}" />
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#uploadify").uploadify({
                'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                //'script'    : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                'script'  : "{:U('Admin/Public/uploadone')}",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/Company',//服务端的上传目录
                'auto'      : true,//自动上传
                'multi'     : true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': 2100000,//限制上传文件的大小2m(比特b)
                'queueSizeLimit' :10, //可上传的文件个数
                'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
                'width'     : 80,//buttonImg的大小
                'height'    : 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    //alert(response);
                    getResult(response);//获得上传的文件路径
                }
            });
          
            var imgg = $("#image");
            function getResult(content){        
                imgg.val(content);
                $("#head").attr("src",content);
            }
        });
    </script>
</body>
</html>
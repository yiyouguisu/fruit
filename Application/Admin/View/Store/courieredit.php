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
<script type="text/javascript">
    $(function(){
        var type='{$data.isleader}';
        if(type==0){
            $(".leader").show();
        }else if(type==1) {
            $(".leader").hide();
        }
    })
</script>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
            <form method="post" action="{:U('Admin/Store/courieredit')}">
                <div class="h_a">基本信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="80">用户名</th>
                                <td> <input type="hidden" name="id" value="{$data.id}"/>
                                    <input type="text" name="username" class="input" id="username" value="{$data.username}" >
                                </td>
                            </tr>
                            <tr>
                                <th width="80">昵称</th>
                                <td>
                                    <input type="text" name="nickname" class="input" id="nickname" value="{$data.nickname}" >
                                </td>
                            </tr>
                             <tr>
                                <th>图像</th>
                                <td>
                                    <img id="head" src="{$data.head}" width="80" height="80" />
                                    <input type="hidden" name="head" id="image" class="input" value="{$data.head}" style="float: left"  runat="server">&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" ></td>
                                </td>
                            </tr>
                            <tr>
                                <th>密码</th>
                                <td><input type="password" name="password" class="input" id="password" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>确认密码</th>
                                <td><input type="password" name="pwdconfirm" class="input" id="pwdconfirm" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td><input type="text" name="phone" class="input" id="phone" value="{$data.phone}" size="30">
                                </td>
                            </tr>
                            
                            <tr>
                                <th>真实姓名</th>
                                <td><input type="text" name="realname" class="input" id="realname"  value="{$data.realname}" ></td>
                            </tr>
                            <tr>
                                <th>性别</th>
                                <td>
                                    <select name="sex">
                                        <option value="1" <if condition="$data['sex'] eq 1 ">selected</if>>男</option>
                                        <option value="2" <if condition="$data['sex'] eq 2 ">selected</if>>女</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <th>组长</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='isleader' value='1' <if condition="$data['isleader'] eq '1' ">checked</if>>
                      <span>是</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='isleader' value='0' <if condition="$data['isleader'] eq '0' ">checked</if>>
                      <span>否</span></label>
                  </li>
                </ul>
                                </td>
                            </tr>
                            <tr class="leader" style="display:none;">
                                <th>组长</td>
                                <td><select name="leader">
                                        <option value="" >请选择组长</option>
                                        <volist name="leader" id="vo">
                                            <option value="{$vo.id}" <if condition="$data['leader'] eq $vo['id'] ">selected</if>>{$vo.username}</option>
                                        </volist>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>状态</td>
                                <td><select name="status">
                                        <option value="1" <if condition="$data['status'] eq 1 ">selected</if>>开启</option>
                                        <option value="0" <if condition="$data['status'] eq 0 ">selected</if>>禁止</option>
                                    </select></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="btn_wrap_pd">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
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
                'script': "{:U('Admin/Public/uploadone')}",//实现上传的程序
                'method'    : 'get',
                'folder'    : '/Uploads/images/pc',//服务端的上传目录
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
            $("input[name='isleader']:radio").click(function() {
                if($(this).val()==0){
                    $(".leader").show();
                }else if($(this).val()==1) {
                    $(".leader").hide();
                }
            });
        });
    </script>
</body>
</html>
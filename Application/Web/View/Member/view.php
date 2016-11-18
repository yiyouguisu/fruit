<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="__CSS__/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/weixin.master.css" rel="stylesheet" type="text/css" />
    <link href="__CSS__/mobiscroll.custom-2.6.2.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__JS__/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="__JS__/ajaxfileupload.js"></script>
    <script type="text/javascript" src="__JS__/mobiscroll.custom-2.6.2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var opt = {
                preset: 'date', //日期
                theme: 'android-ics light', //皮肤样式
                display: 'modal', //显示方式
                mode: 'Scroller', //日期选择模式
                dateFormat: 'yy-mm-dd', // 日期格式
                setText: '确定', //确认按钮名称
                cancelText: '取消',//取消按钮名籍我
                dateOrder: 'yymmdd', //面板中日期排列格式
                dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
                endYear: 2020 //结束年份
            }
            $('#txtBirthday').mobiscroll(opt);

            $('#bindcompany').click(function () {
                if ($(this).find('span').html() == "未绑定") {
                    if (confirm('还没有绑定企业，是否去绑定')) {
                        location.href = "{:U('Web/Company/bind')}";
                    } else {
                        return false;
                    }
                }
                else {
                    return true;
                }
                
            })

            $("#mylove").click(function () {
                location.href = "{:U('Web/Member/mylove')}";
            })

            $("#sex").click(function () {
                //$("#tsex").click();
                open($("#tsex"));
            })

            $("#tsex").change(function () {
                $("#sex").find('span').html($(this).find("option:selected").text());
            })

            $(".toolTuichu").click(function () {
                if (confirm("确定要推出当前帐号?")) {
                    $.post(
			            "{:U('Web/Member/clearcookie')}", {

			            },
			            function (response, status) {
			                if (status == "success") {
			                    location.href = "{:U('Web/Member/login')}";
			                };
			            },
			            "json");
                } else {
                    return false;
                }
            })

            $("#save").click(function () {
                $("#toolSubmit").click();
            })
        });

        function open(elem) {
            if (document.createEvent) {
                var e = document.createEvent("MouseEvents");
                e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                elem[0].dispatchEvent(e);
            } else if (element.fireEvent) {
                elem[0].fireEvent("onmousedown");
            }
        }

        function changeload() {
            ajaxFileUpload();
        }

        function ajaxFileUpload() {
            $.ajaxFileUpload({
                url: "{:U('Web/Member/fileupload')}",
                secureuri: false, //一般设置为false
                fileElementId: 'fileupload',
                secureuri: false,
                dataType: 'text',
                success: function (data, status) {
                    $("#headimg").attr("src", "__ROOT__" + data);
                    $("#uploadtext").val(data);
                },
                error: function (data, status, e) { }
            })
            return false;
        }

       
    </script>
</head>
<body>
    <form action="{:U('Web/Member/edit')}" method="post">
        <div id="page_head" class="page_head">
            <div class="l"><a id="toolReturn" class="return"  href="{:U('Web/Member/index')}" target="_self"></a></div>
            <h1>个人信息</h1>
            <div class="r" id="save">保存</div>
        </div>
        <div id="page_info" class="page_info" style="background-color: #ffffff;">
            <div class="inputDiv" style="border-bottom: 0px;margin-top:0px">
                <div class="title" style="padding-top: 18px">头像</div>
                <div class="input">
                    <input type="hidden" name="head" id="uploadtext">
                    <input type="file" name="Filedata" id="fileupload" style="display: none" onchange="changeload()">
                    <img id="headimg" alt="" src="__ROOT__{$data.head}" style="width: 60px; height: 60px; border-radius: 60px; margin: 10px 10px 10px 0px;" onclick="openfile()" />
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </div>
            </div>
            <div style="width: 100%; height: 20px; background-color: #f3f3f3"></div>
            <div class="inputDiv">
                <div class="title">手机号码</div>
                <div class="input">
                    <span style="color: #999999; font-size: 15px; margin-right: 15px">{$data.phone}</span>
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">昵称</div>
                <div class="input">
                    <input name="nickname" id="username" type="text" style="text-align: right;" value="{$data.nickname}" />
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">会员等级</div>
                <div class="input">
                    <span style="width: 50px; height: 50px; padding: 5px; margin-right: 15px; color: #ffffff; font-size: 15px; text-align: center; background-color: #f59c0c; border-radius: 60px/30px;">{$data.level}</span>

                </div>
            </div>
            <div class="inputDiv">
                <div class="title">性别</div>
                <div class="input" id="sex">
                    <select name="sex" id="tsex" style="color: #ffffff; opacity: 0.01">
                        <!--<eq name="data[sex]" value="0">
	                		<option value='0' selected>未知</option>
	                		<option value='1'>男</option>
	                		<option value='2'>女</option>
                		</eq>
                        <eq name="data[sex]" value="1">
	                		<option value='0'>未知</option>
	                		<option value='1' selected>男</option>
	                		<option value='2'>女</option>
                		</eq>
                        <eq name="data[sex]" value="2">
	                		<option value='0'>未知</option>
	                		<option value='1'>男</option>
	                		<option value='2' selected>女</option>
                		</eq>-->
                        <option value='0'>保密</option>
                        <option value='1'>帅哥</option>
                        <option value='2'>美女</option>
                    </select>
                    <eq name="data[sex]" value="0">
	                	<span style="">保密</span>
                	</eq>
                    <eq name="data[sex]" value="1">
	                	<span >帅哥</span>
                	</eq>
                    <eq name="data[sex]" value="2">
	                	<span>美女</span>
                	</eq>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </div>
            </div>
            <div class="inputDiv">
                <div class="title">生日</div>
                <div class="input">
                    <input type="text" id="txtBirthday" name="birthday" value="{$data.birthday}" />
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png" />
                </div>
            </div>
            <div class="inputDiv" id="mylove">
                <div class="title">个人偏好</div>
                <div class="input">
                    <span style="margin-right: 0px; color: #999999; font-size: 15px;"></span>
                    <img class="turn" alt="" src="__IMG__/icon_arrow.png"  />
                </div>
            </div>
            <div class="inputDiv" style="border-bottom: 0px" id="bindcompany">
                <div class="title">企业账号</div>
                <div class="input">
                    <span style="margin-right: 0px; color: #999999; font-size: 15px;">{$data.companyid}</span>
                    <eq name="data[companyid]" value="未绑定">
                        <img class="turn" alt="" src="__IMG__/icon_arrow.png"   />
                    </eq>
                </div>
            </div>
            <div class="nrongDiv" style="background-color: #f3f3f3; margin-top: 0px">
                <input id="toolSubmit" name="toolSubmit" type="submit" value="保存" class="toolSubmit" style="display: none" />
                <input name="toolSubmit" type="button" value="退出当前账号" class="toolTuichu" />
            </div>
        </div>
    </form>
</body>
<script>
    function openfile() {
        $('#fileupload').click();
    }
</script>
</html>

<include file="Common:Head" />
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <!--   <div class="nav">
          <ul class="cc">
                <li ><a href="{:U('Admin/Menu/index')}">后台菜单管理</a></li>
                <li class="current"><a href="{:U('Admin/Menu/add')}">添加菜单</a></li>
              </ul>
        </div>-->
        <include file="Common:Nav"/>
        <div class="common-form">
            <!---->
            <form method="post"  action="{:U('Admin/Coupons/edit')}">
                <div class="h_a">优惠券信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <script type="text/javascript">
                                $(function () {
                                    var type = '{$data.type}';
                                    if (type != 0) {
                                        if (type == 1) {
                                            $("#shop").show();
                                            $("#product").hide();
                                            $("#cate").hide();
                                            load('{$data.storeid}', '{$data.pid}');
                                        } else if (type == 2) {
                                            $("#shop").show();
                                            $("#product").show();
                                            $("#cate").hide();
                                            load('{$data.storeid}', '{$data.pid}');
                                        } else if (type == 3) {
                                            $("#shop").hide();
                                            $("#product").hide();
                                            $("#cate").show();
                                        }
                                        
                                    }
                                })
                                function load(parentid, subcatid) {
                                    $.ajax({
                                        type: "POST",
                                        url: "{:U('Admin/Ad/ajax_getproduct')}",
                                        data: { 'storeid': parentid },
                                        dataType: "json",
                                        success: function (data) {
                                            $('#pid').html('<option>请选择商品</option>');
                                            $.each(data, function (no, items) {
                                                if (items.id == subcatid) {
                                                    $('#pid').append('<option value="' + items.id + '" selected>' + items.title + '</option>');
                                                } else {
                                                    $('#pid').append('<option value="' + items.id + '">' + items.title + '</option>');
                                                }
                                            });
                                        }
                                    });


                                }
                            </script>
                            <tr>
                                <th width="80">优惠券名称</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="title" class="input length_6 input_hd" placeholder="请输入标题" id="title" value="{$data.title}">
                                </td>
                            </tr>
                            <tr>
                                <th>优惠券类型</th>
                                <td>
                                  <ul class="switch_list cc ">
                                    <li style="width: 130px;">
                                        <label><input type='radio' name='type' value='0' <if condition="$data['type'] eq '0' ">checked</if>><span>默认</span></label>
                                    </li>
                                    <li style="width: 130px;">
                                        <label><input type='radio' name='type' value='1' <if condition="$data['type'] eq '1' ">checked</if>><span>适用于某个店铺</span></label>
                                    </li>
                                    <li style="width: 130px;">
                                        <label><input type='radio' name='type' value='2' <if condition="$data['type'] eq '2' ">checked</if> ><span>适用于某件商品</span></label>
                                    </li>
                                    <li style="width: 130px;">
                                        <label><input type='radio' name='type' value='3' <if condition="$data['type'] eq '3' ">checked</if>><span>适用于某种分类商品</span></label>
                                    </li>
                                  </ul>
                                </td>
                            </tr>
                            <tr id="shop" style="display:none;">
                                <th width="80">优惠券适用店铺</th>
                                <td>
                                     <select name="storeid"  onchange="load(this.value)">
                                        <option value="">请选择店铺</option>
                                        <volist name="shop" id="vo">
                                            <option value="{$vo.id}" <if condition="$data['storeid'] eq $vo['id']">selected</if>>{$vo.title}</option>
                                        </volist>
                                    </select>
                                </td>
                            </tr>
                            <tr id="product" style="display:none;">
                                <th width="80">优惠券适用商品</th>
                                <td>
                                     <select name="pid" id="pid">
                                        <option value="">请选择商品</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="cate" style="display: none;">
                                <th width="80">适用商品类别</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <select class="select_2" name="catid">
                                        <option value="" >请选择类别</option>
                                        {$category}
                                    </select>
                                </td>
                            </tr>
                            <tr id="logo">
                                <th>展示图：</th>
                                <td><input type="text" name="thumb" id="image" class="input length_5" value="{$data.thumb}" style="float: left"  runat="server" ondblclick='image_priview(this.value);'>&nbsp; <input type="button" class="button" value="选择上传" id="uploadify" >    <span class="gray"> 双击文本框查看图片</span></td>
                            </tr>
                            <tr>
                                <th>优惠券价格</th>
                                <td>
                                    <span class="must_red">*</span>
                                    <input type="text" name="price" class="input length_2"  placeholder="请输入优惠券价格" id="price" value="{$data.price}">
                                </td>
                            </tr>
                            <tr>
                                <th>优惠券适用范围</th>
                                <td>
                                    消费满<input type="text" name="range" class="input length_2"  placeholder="请输入优惠券金额" id="range" value="{$data.range}">元可使用
                                </td>
                            </tr>
                            <tr>
                                <th>优惠券有效时间</th>
                                <td>
                                    <input type="text" name="validity_starttime" class="input length_2 J_datetime" value="{$data.validity_starttime}" style="width:120px;">
                                    -
                                    <input type="text" class="input length_2 J_datetime" name="validity_endtime" value="{$data.validity_endtime}" style="width:120px;">
                                </td>
                            </tr>
                            <tr>
                                <th>优惠券使用规则</th>
                                <td>
                                    <textarea  name="content" id="description" class="valid" style="width:500px;height:150px;">{$data.content}</textarea>
                                </td>
                            </tr>
                              <tr>
                                <th>审核</th>
                                <td>
                                  <ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='status' value='1' <if condition="$data['status'] eq '1' ">checked</if>>
                      <span>审核</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='status' value='0' <if condition="$data['status'] eq '0' ">checked</if>>
                      <span>未审核</span></label>
                  </li>
                </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="id" value="{$data.id}">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">修改</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
       <script src="__JS__/content_addtop.js"></script>

    <link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#uploadify").uploadify({
                'uploader': '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
                'cancelImg': '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
                // 'script' : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
                'script': "{:U('Admin/Public/uploadone')}",//实现上传的程序
                'method': 'post',
                'folder': '/Uploads/images/',//服务端的上传目录
                'auto': true,//自动上传
                'multi': true,//是否多文件上传
                'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
                'sizeLimit': '',//限制上传文件的大小2m(比特b)
                'queueSizeLimit': 10, //可上传的文件个数
                'buttonImg': '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
                'width': 80,//buttonImg的大小
                'height': 26,
                onComplete: function (evt, queueID, fileObj, response, data) {
                    //alert(response);
                    getResult(response);//获得上传的文件路径
                }
            });
            var imgg = $("#image");
            function getResult(content) {
                imgg.val(content);
            }

            $("input[name='type']:radio").click(function () {
                if ($(this).val() == 0) {
                    $("#shop").hide();
                    $("#product").hide();
                    $("#cate").hide();
                } else if ($(this).val() == 1) {
                    $("#shop").show();
                    $("#product").hide();
                    $("#cate").hide();
                } else if ($(this).val() == 2) {
                    $("#shop").show();
                    $("#product").show();
                    $("#cate").hide();
                } else if ($(this).val() == 3) {
                    $("#shop").hide();
                    $("#product").hide();
                    $("#cate").show();
                }
            });
        });
    </script>
</body>
</html>
<include file="Common:Head" />
<style type="text/css">
    .cu, .cu-li li, .cu-span span {
        cursor: hand;
        !important;
        cursor: pointer;
    }

    tr.cu:hover td {
        background-color: #FF9966;
    }
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <div class="common-form">
            <form class="J_ajaxForm" method="post" action="{:U('Admin/Orderfeedback/feedbackcheck')}">
                <div class="h_a">反馈详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">用户名</th>
                                <td>{$data.username}</td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td>{$data.phone}</td>
                            </tr>
                            <tr>
                                <th>订单号</th>
                                <td>{$data.orderid}</td>
                            </tr>
                            <tr>
                                <th>反馈内容</th>
                                <td>{$data.content}</td>
                            </tr>
                            <tr>
                                <th>反馈图片</th>
                                <td>
                                    <div id="layer-photos-demo" class="layer-photos-demo">
                                       <volist name="imglist" id="v">
                                            <img layer-pid="" layer-src="{$v}" src="{$v}" alt="订单反馈图片" width="80px" height="80px">
                                       </volist>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>反馈时间</th>
                                <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                        </tbody>
                    </table>
                
                    <div class="h_a">审核信息</div>       
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                        <tr>
                            <th  width="80">审核</th>
                            <td>
                                <ul class="switch_list cc ">
                                    <li>
                                        <label>
                                            
                                            <input type='radio' name='status' value='2' <if condition="$data['status'] eq '2' ">checked</if>>
                                            <span>审核通过</span></label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type='radio' name='status' value='3' <if condition="$data['status'] eq '3' ">checked</if>>
                                            <span>审核不通过</span></label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                         <tr>
                            <th>审核备注</th>
                            <td>
                                <textarea  name="remark" class="valid" style="width:500px;height:80px;">{$data.remark}</textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn_wrap">
                    <div class="btn_wrap_pd">
                        <input type="hidden" name="id" value="{$data.id}">
                        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">审核</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js"></script>
    <script src="__JS__/content_addtop.js"></script>
    <script src="__JS__/layer/layer.js"></script>
    <script src="__JS__/layer/extend/layer.ext.js"></script>
    <script>
            //调用示例
            layer.ready(function(){ //为了layer.ext.js加载完毕再执行
              layer.photos({
                  photos: '#layer-photos-demo'
              });
            }); 
        
    </script>
</body>
</html>
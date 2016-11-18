<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
    background-color:#FF9966;
}
 
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
       <div class="common-form">
                <div class="h_a">上传资料详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">用户名</th>
                                <td>{:getuser($data['uid'])}</td>
                            </tr>
                            <tr>
                                <th>资料类型</th>
                                <td>{$data.catname}</td>
                            </tr>
                            <tr>
                                <th>上传时间</th>
                                <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                            <tr>
                              <th>资料详情</th>
                              <td>
                                <a href="__ROOT__{$data.url}" target="_blank">
                                <if condition="in_array($data['ext'],array('png','jpg','gif','bmp'))">
                                <img src="{$data.url}" width="100px"/>
                                <else/>点击下载</if>
                                </a>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                    <form class="J_ajaxForm" method="post" action="{:U('Admin/Userupload/review')}">
                         <div class="bk10"></div>
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
                                                <span>审核成功</span></label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type='radio' name='status' value='3' <if condition="$data['status'] eq '3' ">checked</if>>
                                                <span>审核失败</span></label>
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
                        <div class="btn_wrap">
                            <div class="btn_wrap_pd">
                                <input type="hidden" name="id" value="{$data.id}">
                                <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">审核</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
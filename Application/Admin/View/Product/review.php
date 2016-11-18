<include file="Common:Head" />
<style type="text/css">
            .col-auto {
                overflow: hidden;
                _zoom: 1;
                _float: left;
                border: 1px solid #c2d1d8;
            }
            .col-right {
                float: right;
                width: 210px;
                overflow: hidden;
                margin-left: 6px;
                border: 1px solid #c2d1d8;
            }

            body fieldset {
                border: 1px solid #D8D8D8;
                padding: 10px;
                background-color: #FFF;
            }
            body fieldset legend {
                background-color: #F9F9F9;
                border: 1px solid #D8D8D8;
                font-weight: 700;
                padding: 3px 8px;
            }
            .picList li{ float: left; margin-top: 2px; margin-right: 5px;}
        </style>
<body class="J_scroll_fixed">
    <div class="wrap jj">
        <include file="Common:Nav"/>
        <div class="common-form">
                <div class="h_a">商品详细信息</div>
                <div class="table_full">
                    <table width="100%" class="table_form contentWrap">
                        <tbody>
                            <tr>
                                <th width="100">商品名称</th>
                                <td>{$data.title}</td>
                            </tr>
                            <tr>
                                <th width="100">商品类别</th>
                                <td>{$data.catname}</td>
                            </tr>
                            <tr>
                                <th>产品图片</th>
                                <td><img src="{$data.thumb}" width="100px" height="100px"></td>
                            </tr>
                            <tr>
                                <th>产品价格</th>
                                <td>{$data.discountprice}{$data.unit} / {$data.price}{$data.unit}</td>
                            </tr>
                            <tr>
                                <th>产品详情</th>
                                <td>{$data.info}</td>
                            </tr>
                            <tr>
                                <th>规格参数</th>
                                <td>{$data.standard}</td>
                            </tr>
                            <tr>
                                <th>所属店铺</th>
                                <td>{:getstoreinfo($data['storeid'])}</td>
                            </tr>
                            <tr>
                                <th>提交时间</th>
                                <td>{$data.inputtime|date="Y-m-d H:i:s",###}</td>
                            </tr>
                        </tbody>
                    </table>
                <form method="post" action="{:U('Admin/Product/review')}">
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
    <script src="__JS__/common.js?v"></script>
</body>
</html>
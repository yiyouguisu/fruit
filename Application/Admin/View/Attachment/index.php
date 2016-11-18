<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Attachment/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> <span class="mr20">
                        上传时间：
                        <input type="text" name="start_time" class="input length_2 J_date" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_date" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        附件类型：
                        <select class="select_2" name="ext">
                            <option value=""  <empty name="Think.get.ext">selected</empty>>全部</option>
                            <option value="jpg" <if condition=" $Think.get.ext eq 'jpg'"> selected</if>>jpg</option>
                            <option value="png" <if condition=" $Think.get.ext eq 'png'"> selected</if>>png</option>
                            <option value="jpeg" <if condition=" $Think.get.ext eq 'jpeg'"> selected</if>>jpeg</option>
                            <option value="gif" <if condition=" $Think.get.ext eq 'gif'"> selected</if>>gif</option>
                            <option value="doc" <if condition=" $Think.get.ext eq 'doc'"> selected</if>>doc</option>
                            <option value="xls" <if condition=" $Think.get.ext eq 'xls'"> selected</if>>xls</option>
                            <option value="zip" <if condition=" $Think.get.ext eq 'zip'"> selected</if>>zip</option>
                            <option value="rar" <if condition=" $Think.get.ext eq 'rar'"> selected</if>>rar</option>
                        </select>
                        会员名：<select class="select_2" name="is_admin" style="width:60px;">
                            <option value=""  <empty name="Think.get.is_admin">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.is_admin eq '1'"> selected</if>>后台</option>
                            <option value="0" <if condition=" $Think.get.is_admin eq '0'"> selected</if>>前台</option>
                        </select>
                        <input type="text" class="input length_2" name="username" value="{$Think.get.username}" style="width:120px;">
                        <button class="btn">搜索</button>
                    </span> </div>
            </div>
        </form> 
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >用户名</td>
                            <td width="10%" align="center" >上传类型</td>
                            <td width="20%" align="left" >附件名称</td>
                            <td width="10%" align="center" >附件大小</td>
                            <td width="15%"  align="center" >上传时间</td>
                            <td width="20%"  align="left" >说明</td>
                            <td width="15%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    <volist name="data" id="vo">
                        <tr>
                            <td align="center" >{$vo.id}</td>
                            <td align="center" >
                                <if condition="$vo.isadmin eq 1">{$vo['username']}</if>
                                <if condition="$vo.isadmin eq 0">{:getuserinfo($vo['uid'])}</if>
                            </td>
                            <td align="center" >{$vo['catname']}</td>
                            <td align="left" ><img src="__IMG__/ext/{$vo.ext}.gif" />||{$vo.name}</td>
                            <td align="center" >{:format_bytes($vo['size'])}</td>
                            <td align="center" >{$vo.inputtime|date="Y-m-d H:i:s",###}</td>
                            <td align="left" >{$vo.info}</td>
                            <td align="center" > 
                                <a href="javascript:;" onClick="image_priview('{$vo.url}')">查看</a>
                            </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
                   <div class="p10">
                <div class="pages"> {$Page} </div>
            </div>
            </div>
         
            
    </div>

    <script src="__JS__/common.js?v"></script>
    <script src="__JS__/content_addtop.js"></script>
</body>
</html>
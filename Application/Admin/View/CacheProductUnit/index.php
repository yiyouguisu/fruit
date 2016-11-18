<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <form class="J_ajaxForm" action="{:U('Admin/CacheProductUnit/save')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0" >
                    <thead>
                        <tr>
                            <td width="5%" align="center" >ID</td>
                            <td width="5%" align="center" >排序</td>
                            <td width="10%" align="center" >单位名称</td>
                            <td width="5%" align="center" >值</td>
                            <td width="25%" align="center" >单位LOGO名称</td>
                            <td width="15%" align="center" >单位LOGO</td>
                            <td width="10%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody id="ProductUnit_list" >
                    <volist name="ProductUnitConfig" id="vo">
                        <tr id="list_{$key}">
                            <td align="center" >{$key}</td>
                            <td align="center" ><input name='ProductUnit[{$key}][listorder]' class="input length_1 mr5"  type='number' size='3' value='{$vo.listorder}' align="center"></td>
                            <td align="center" ><input type="text" class="input" style="width:100px" name="ProductUnit[{$key}][title]" value="{$vo.title}" /></td>
                            <td align="center" ><input type="text" class="input" style="width:40px" name="ProductUnit[{$key}][value]" value="{$vo.value}" /></td>
                            <td align="center" ><input type="text" class="input" style="width:305px;float: left" name="ProductUnit[{$key}][image]" value="{$vo.image}" id="icnoname_{$key}"/> <input type="button" id="uploadbtn{$key}" class="button upload" value="选择上传" data-id="{$key}"></td>
                            <td align="center" ><img src="{$vo.image}" id="image_{$key}"   height="20px"/></td>
                            <td align="center" > 
                                <a href="javascript:;" onClick="delx({$key});">删除</a>
                            </td>
                        </tr>
                    </volist>
                    </tbody>
                </table>
                <button class="btn" type="button" style="margin-top:5px" id="add">添加一个商品单位</button>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit" name="submit">保存</button>
                </div>
            </div>
        </form>
    </div>

     <script src="__JS__/common.js?v"></script>
<script type="text/javascript">
$(function(){
    var xss=parseInt({$key})||0;
    $("#add").click(function(){
        xss++;
        var htmladd="<tr id='list_"+xss+"''>";
            htmladd +="<td align=\"center\" >"+xss+"</td>";
            htmladd +="<td align=\"center\" ><input type=\"number\" class=\"input length_1 mr5\" size='3' name=\"ProductUnit["+xss+"][listorder]\" value='0'  align=\"center\"/></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:100px\" name=\"ProductUnit["+xss+"][title]\" value='' /></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:40px\" name=\"ProductUnit["+xss+"][value]\" value='' /></td>";
            htmladd +="<td align=\"center\" ><input type=\"text\" class=\"input\" style=\"width:305px;float: left\" name=\"ProductUnit["+xss+"][image]\"  id='icnoname_"+xss+"' value=''/><input type=\"button\" id='uploadbtn"+xss+"' class=\"button upload\" value=\"选择上传\" data-id='"+xss+"'></td>";
            htmladd +="<td align=\"center\" ><img id=\"image_"+xss+"\"   height=\"20px\"/></td>";
            htmladd +="<td align=\"center\" >";
            htmladd +="<a href=\javascript:void(0);\" onclick=\"delx('"+xss+"');\">删除</a>";
            htmladd +="</td>";
            htmladd +="</tr>";
        var htmltr=  $(htmladd);
            htmltr.appendTo($("#ProductUnit_list"));
            inituploadify(htmltr.find(".upload"));
    })
})

function delx(id){
    if(confirm("删除后不可恢复，并且删除完要确定保存后才会生效，确定要删除吗?")) $("#list_"+id).remove();
}
</script>
<link rel="stylesheet" type="text/css"  href="__PUBLIC__/Public/uploadify/uploadify.css" />
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Public/uploadify/uploadify.js"></script>
    <script type="text/javascript">
    $(function(){
        $(".upload").each(function(){
            inituploadify($(this));
        })
    })
   function inituploadify(a){
    a.uploadify({
        'uploader'  : '__PUBLIC__/Public/uploadify/uploadify.swf',//所需要的flash文件
        'cancelImg' : '__PUBLIC__/Public/uploadify/cancel.gif',//单个取消上传的图片
        //'script' : '__PUBLIC__/Public/uploadify/uploadify.php',//实现上传的程序
        'script'    : "{:U('Admin/Public/uploadone')}",//实现上传的程序
        'method'    : 'post',
        'auto'      : true,//自动上传
        'multi'     : false,//是否多文件上传
        'fileDesc': 'Image(*.jpg;*.gif;*.png)',//对话框的文件类型描述
        'fileExt': '*.jpg;*.jpeg;*.gif;*.png',//可上传的文件类型
        'sizeLimit': '',//限制上传文件的大小2m(比特b)
        'queueSizeLimit' :10, //可上传的文件个数
        'buttonImg' : '__PUBLIC__/Public/uploadify/add.gif',//替换上传钮扣
        'width'     : 80,//buttonImg的大小
        'height'    : 26,
        onComplete: function (evt, queueID, fileObj, response, data) {
            $("#image_"+$(evt.currentTarget).attr("data-id")).attr("src",response);
            $("#icnoname_"+$(evt.currentTarget).attr("data-id")).val(response);
        }
    });
   }
   </script>
</body>
</html>
<include file="Common:Head" />
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        
        <form id="export-form" action="{:U('Admin/Db/export')}" method="post" >
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
                            <td width="5%" align="center" >ID</td>
                            <td width="10%" align="center" >表名</td>
                            <td width="8%" align="center" >引擎</td>
                            <td width="10%" align="center" >编码</td>
                            <td width="8%" align="center" >记录数</td>
                            <td width="8%" align="center" >数据大小</td>
                            <td width="15%" align="center" >最后更新时间</td>
                            <td width="10%" align="center" >备注</td>
                            <td width="10%" align="center" >备份状态</td>
                            <td width="25%" align="center" >管理操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        <php>$totalsize=0;</php>
                    <volist name="tablelist" id="vo">
                        <tr>
                          <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="tables[]" value="{$vo.Name}"></td>
                          <td align="center" >{$key}</td>
                          <td align="center">{$vo.Name}</td>
                          <td align="center">{$vo.Engine}</td>
                          <td align="center">{$vo.Collation|default='--'}</td>
                          <td align="center">{$vo.Rows}</td>
                          <td align="center">{$vo.Data_length|format_bytes}</td>
                          <td align="center">{$vo.Update_time}</td>
                          <td align="center">{$vo.Comment}</td>
                          <td align="center" class="info">未备份</td>
                          <td align="center" > 
                          <a href="{:U('Admin/Db/showtable',array('table'=>$vo['Name']))}" >查看表结构</a>|
                          <a href="{:U('Admin/Db/optimize',array('tables'=>$vo['Name']))}">优化表</a>|
                          <a href="{:U('Admin/Db/repair',array('tables'=>$vo['Name']))}">修复表</a>
                          </td>
                          <?php 
                          $totalsize=$totalsize+$vo['Data_length'];
                          ?>
                        </tr>
                    </volist>
                    </tbody>
                </table>
            </div>
         
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <label class="mr20">
                    <input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>     
                    <if condition="authcheck('Admin/Db/setbackup')">
                        <button style="width: 155px;" class="btn btn_submit mr10 " type="submit" name="submit" id="export">立即备份</button>
                        <b style="margin-left: 20px;color: #999;font-weight: normal;">数据库总大小：{$totalsize|format_bytes}(尽量不要在访问量较大时备份)</b>
                    </if>
                </div>
            </div>
        </form>
    </div>

    <script src="__JS__/common.js?v"></script>
</body>
</html>
<script type="text/javascript">
    (function($){
        var $form = $("#export-form"), $export = $("#export"), tables;
        $export.click(function(){
            $export.parent().children().addClass("disabled");
            $export.text("正在发送备份请求...");
            $.post(
                $form.attr("action"),
                $form.serialize(),
                function(data){
                    if(data.status==1){
                        tables = data.tables;
                        $export.text(data.info + "开始备份，请不要关闭本页面！");
                        backup(data.tab);
                        window.onbeforeunload = function(){ return "正在备份数据库，请不要关闭！" }
                    } else if(data.status==0) {
                        alert(data.info);
                        $export.parent().children().removeClass("disabled");
                        $export.text("立即备份");
                        setTimeout(function(){
                          $('#top-alert').find('button').click();
                          $(this).removeClass('disabled').prop('disabled',false);
                      },1500);
                    }
                },
                "json"
            );
            return false;
        });

        function backup(tab, status){
            status && showmsg(tab.id, "开始备份...(0%)");
            $.get($form.attr("action"), tab, function(data){
                if(data.status==1){
                    showmsg(tab.id, data.info);
                    if(!$.isPlainObject(data.tab)){
                        $export.parent().children().removeClass("disabled");
                        $export.text("备份完成，点击重新备份");
                        window.onbeforeunload = function(){ return null }
                        return;
                    }
                    backup(data.tab, tab.id != data.tab.id);
                } else if(data.status==0){
                    alert(data.info);
                    $export.parent().children().removeClass("disabled");
                    $export.text("立即备份");
                    setTimeout(function(){
                      $('#top-alert').find('button').click();
                      $(this).removeClass('disabled').prop('disabled',false);
                  },1500);
                }
            }, "json");

        }

        function showmsg(id, msg){
            $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg).css('color','green');
        }
    })(jQuery);
</script>
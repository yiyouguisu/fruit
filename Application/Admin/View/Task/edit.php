<include file="Common:Head" />
<include file="Common:ueditor" />
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
  <form class="J_ajaxForm" name="myform" id="myform" action="{:U('Admin/Task/doedit')}" method="post">
  	<input type="hidden" value="{$vo.id}" name="id"/>
    <div class="J_tabs_contents">
      <div>
        <div class="h_a">添加新任务</div>
        <div class="table_full">
          <table width="100%" class="table_form ">
            <tr>
              <th width="200">任务名称：</th>
              <td><?php echo FormHelper(array("id"=>"name","input_type"=>1),$vo['name']) ;?></td>
            </tr>
            <tr>
              <th width="200">任务标识名称：</th>
              <td><?php echo FormHelper(array("id"=>"task_name","input_type"=>1),$vo['task_name']) ;?></td>
            </tr>
            <tr>
              <th width="200">是否启用：</th>
              <td><?php echo FormHelper(array("id"=>"is_on","input_type"=>4,"items"=>"1|||是,0|||否"),$vo['is_on']) ;?></td>
            </tr>
            <tr>
              <th colspan="2">执行时间：</th>
            </tr>
            <tr>
              <th width="200">月：</th>
              <td><?php echo FormHelper(array("id"=>"month","input_type"=>4,"items"=>"*|||每月,set|||指定月份"),($vo['month']=="*")?'*':'set','onclick="ckjump(this)"') ;?></td>
            </tr>
            <tr id="month_set_tr" <?php echo ($vo['month']!="*")?'':'style="display:none"';?>>
              <th width="200">指月份：</th>
              <td><?php echo FormHelper(array("id"=>"month_set","input_type"=>1),($vo['month']=="*")?'':$vo['month'],'style="width:300px"','输入数字月份，多个月份用英文逗号隔开，有效数字为(1-12)，如(1,2,10,11)') ;?></td>
            </tr>
            <tr>
              <th width="200">日期：</th>
              <td><?php echo FormHelper(array("id"=>"day","input_type"=>4,"items"=>"*|||每天,set_day|||指定日期,set_week|||指定星期几")
			  ,($vo['week']=="*")?(($vo['day']=="*")?'*':'set_day'):'set_week'
			  ,'onclick="ckjump(this)"') ;?></td>
            </tr>
            <tr id="day_set_day_tr" <?php echo ($vo['day']!="*")?'':'style="display:none"';?>>
              <th width="200">指定日期：</th>
              <td><?php echo FormHelper(array("id"=>"day_set","input_type"=>1),($vo['day']=="*")?'':$vo['day'],'style="width:300px"','输入数字日期，多个日期用英文逗号隔开，有效数字为(1-31)，如(1,2,18,25,28)') ;?></td>
            </tr>
            <tr id="day_set_week_tr" <?php echo ($vo['week']!="*")?'':'style="display:none"';?>>
              <th width="200">指定星期几：</th>
              <td><?php echo FormHelper(array("id"=>"week_set","input_type"=>1),($vo['week']=="*")?'':$vo['week'],'style="width:300px"','输入数字，有效数字为(0-6)，0表示星期天，依此类推，一个星期内多天用英文逗号隔开，如(0,3,5)') ;?></td>
            </tr>
            <tr>
              <th width="200">小时：</th>
              <td><?php echo FormHelper(array("id"=>"hour","input_type"=>4,"items"=>"*|||每小时,set|||指定小时"),($vo['hour']=="*")?'*':'set','onclick="ckjump(this)"') ;?></td>
            </tr>
            <tr id="hour_set_tr" <?php echo ($vo['hour']!="*")?'':'style="display:none"';?>>
              <th width="200">指定小时：</th>
              <td><?php echo FormHelper(array("id"=>"hour_set","input_type"=>1),($vo['hour']=="*")?'':$vo['hour'],'style="width:300px"','输入数字，多个小时用英文逗号隔开，有效数字为(0-23)，如(0,1,2,18,20,23)') ;?></td>
            </tr>
            <tr>
              <th width="200">分钟：</th>
              <td><?php echo FormHelper(array("id"=>"min","input_type"=>4,"items"=>"*|||每分,set|||指定分钟"),($vo['min']=="*")?'*':'set','onclick="ckjump(this)"') ;?></td>
            </tr>
            <tr id="min_set_tr" <?php echo ($vo['min']!="*")?'':'style="display:none"';?>>
              <th width="200">指定分钟：</th>
              <td><?php echo FormHelper(array("id"=>"min_set","input_type"=>1),($vo['min']=="*")?'':$vo['min'],'style="width:300px"','输入数字，多个分钟用英文逗号隔开，有效数字为(0-59)，如(0,1,2,43,54,57)') ;?></td>
            </tr>
            <tr>
              <th width="200">任务脚本地址：</th>
              <td><?php echo FormHelper(array("id"=>"task_url","input_type"=>1),$vo['task_url'],'style="width:300px"','输入执行此任务的脚本地址') ;?></td>
            </tr>
          </table>
        </div>
      </div>


    </div>
      <?php $submit="修改";?>
      <div class="btn_wrap">
        <div class="btn_wrap_pd">             
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit"><?php echo ($submit)?$submit:"提交";?></button><?php echo $_tip?'<b class="subtip">'.$_tip.'</b>':'';?>
        </div>
      </div>
  </form>
</div>

<script type="text/javascript">
function ckjump(obj){
	var type = $(obj).val();
	var id = $(obj).attr("name")+"_"+type+"_tr";
	$("#"+id).show();
	$(obj).siblings("input").each(function(k, v) {
		var stype = $(this).val();
		if(stype!="*"){
			var sid = $(this).attr("name")+"_"+stype+"_tr";
			$("#"+sid).hide();
		}
    });
}
</script>
<script type="text/javascript" src="__JS__/content_addtop.js"></script>
<script src="__JS__/common.js?v"></script>
</body>
</html>

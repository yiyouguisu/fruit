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
 <div class="h_a">分类应用</div>
  <form action="{:U('Admin/Productcate/apply')}" method="post" class="J_Form">
    <div class="table_full">
      <table width="100%" cellspacing="0" id="dnd-example">
        <tbody>
          <?php echo $categorys;?>
        </tbody>
      </table>
    </div>
    <div class="">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">应用</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function () {
    Wind.css('treeTable');
    Wind.use('treeTable', function () {
        $("#dnd-example").treeTable({
            indent: 20,
            expandable:false
        });
    });
});

function checknode(obj) {
    var chk = $("input[type='checkbox']");
    var count = chk.length;
    var num = chk.index(obj);
    var level_top = level_bottom = chk.eq(num).attr('level')
    for (var i = num; i >= 0; i--) {
        var le = chk.eq(i).attr('level');
        if (eval(le) < eval(level_top)) {
            chk.eq(i).attr("checked", true);
            var level_top = level_top - 1;
        }
    }
    for (var j = num + 1; j < count; j++) {
        var le = chk.eq(j).attr('level');
        if (chk.eq(num).attr("checked")) {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", true);
            else if (eval(le) == eval(level_bottom)) break;
        } else {
            if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", false);
            else if (eval(le) == eval(level_bottom)) break;
        }
    }
}
</script>
<script src="__JS__/common.js?v"></script>
</body>
</html>
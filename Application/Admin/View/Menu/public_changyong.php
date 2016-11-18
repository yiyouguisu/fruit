<include file="Common:Head" />
<body class="J_scroll_fixed">
<style type="text/css">
.table_full th.v2Menu{padding-left:15px}
.table_full td.v1Menu{padding:0px}
.table_full td.v1Menu td{padding:7px 10px 9px 15px}
</style>
<div class="wrap J_check_wrap">
  <div class="h_a">常用菜单</div>
  <form action="{:U('Admin/Menu/public_changyong')}" method="post">
    <div class="table_full J_check_wrap">
      <table width="100%">
        <col class="th" />
        <col width="400" />
        <col />
        <tr>
          <th>
            <label>
              <input disabled="true" checked id="J_role_custom" class="J_check_all" data-direction="y" data-checklist="J_check_custom" type="checkbox">
              <span>常用</span>
            </label>
            </th>
          <td>
            <ul data-name="custom" class="three_list cc J_ul_check">
              <li>
                <label>
                  <input disabled checked data-yid="J_check_custom" class="J_check" type="checkbox" >
                  <span>常用菜单</span>
                </label>
              </li>
            </ul>
          </td>
          <td><div class="fun_tips"></div></td>
        </tr>
        <?php foreach($data as $key1=>$leve1):;?>
          <tr>
            <th><label><span>{$leve1.title}</span></label></th>
            <td colspan="2" class="leve1Menu">
              <table width="100%">
                <?php foreach($leve1['child'] as $key2=>$leve2):;?>
          <tr>
                    <th width="20%" class="leve2Menu"><label><input id="J_role_{$leve2.id}" class="J_check_all" data-direction="y" data-checklist="J_check_{$leve2.id}" type="checkbox"><span>{$leve2.title}</span></label></th>
                    <td>
                  <ul data-name="{$key2}" class="three_list cc J_ul_check">
                <?php foreach($leve2['child'] as $key3=>$leve3):;?>
                    <li><label><input  name="menu[]" data-yid="J_check_{$leve2.id}" class="J_check" type="checkbox" value="{$leve3.id}" <if condition="  in_array($leve3['id'],$panel) ">checked</if>
><span>{$leve3.title}</span></label></li>
                <?php foreach($leve3['child'] as $key4=>$leve4):;?>
                    <li><label><input  name="menu[]" data-yid="J_check_{$leve2.id}" class="J_check" type="checkbox" value="{$leve4.id}" <if condition="  in_array($leve4['id'],$panel) ">checked</if>
><span>{$leve4.title}</span></label></li>
          <?php endforeach;?>
          <?php endforeach;?>
                  </ul>
                    </td>
                    </tr>
        <?php endforeach; ?>
                  </table>
              </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div class="btn_wrap">
    <div class="btn_wrap_pd">             
      <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
    </div>
    </div>

  </form>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>

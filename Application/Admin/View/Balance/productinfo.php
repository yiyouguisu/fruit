<include file="Common:Head" />
<style type="text/css">
.cu,.cu-li li,.cu-span span {cursor: hand;!important;cursor: pointer}
 tr.cu:hover td{
	background-color:#FF9966;
}
</style>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
   <div class="table_list">
   <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="8%" align="center" >商品名称</td>
            <td width="8%" align="center" >单价</td>
            <td width="8%" align="center" >数量</td>
            <td width="20%"  align="center" >小计</td>
          </tr>
        </thead>
        <tbody>
        <foreach name="data" item="vo">
          <tr>
            <td width="10%" align="center">{$vo.productname}</td>
            <td width="10%" align="center">{$vo.price}</td>
            <td width="20%" align="center">{$vo.nums}</td>
            <td width="10%"  align="center">
                <?php
                    echo $vo['nums']*$vo['price'];
                ?>
            </td>
          </tr>
         </foreach>
        </tbody>
      </table>
      <div class="p10">
            <div class="pages"> {$Page} </div>
      </div>
   </div>
</div>
<script src="__JS__/common.js?v"></script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no" />
    <title>蔬果先生</title>
    <link href="/Public/Web/css/weixin.global.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Web/css/weixin.master.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/Web/js/weixin.jquery.js"></script>
    <script type="text/javascript" src="/Public/Web/js/weixin.global.js"></script>
</head>
<body>
    <div id="page_info" class="page_info" style="background-color:#f3f3f3;">
       <div class="orderStatus">
          <ul>
          <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key == (count($list)-1)): ?><li><span class="new"></span><?php echo ($vo["title"]); echo ($vo["sendtime"]); ?></li>
          		<?php else: ?>
          			<li><span class="old"></span><?php echo ($vo["title"]); echo ($vo["sendtime"]); ?></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </ul>
       </div>
    </div>
</body>
</html>
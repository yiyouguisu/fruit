<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>提示信息</title>
<include file="Common:Cssjs"/>
</head>
<body>
<div class="wrap">
  <div id="error_tips">
    <h2>{$msgTitle}</h2>
    <div class="error_cont">
      <ul>
        <li>{$error}</li>
      </ul>
       <div class="error_return"><a href="<?php echo($jumpUrl); ?>" id="href" class="btn">返回</a>  <b id="wait"><?php echo($waitSecond); ?></b></div>
    </div>
  </div>
</div>
<script src="__JS__/common.js?v"></script>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
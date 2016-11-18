<?php 
$getMenu = \Admin\Controller\PublicController::getMenu(); 
if($getMenu) {
 ?>
<div class="nav">
  <ul class="cc">
    <?php
	foreach($getMenu as $r){
		$name = $r['name'];
		$app=explode("/",$r['name']);
                                         $action=$app[1].$app[2];
	?>
    <li <?php echo $action==CONTROLLER_NAME.ACTION_NAME?'class="current"':""; ?>><a href="<?php echo U("".$name."");?>"><?php echo $r['title'];?></a></li>
    <?php
	}
	?>
  </ul>
</div>
<?php } ?>
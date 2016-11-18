<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class CacheLevelController extends CommonController {

	public function index(){
		$levelConfig = F("levelConfig",'',CACHEDATA_PATH);
		$this->assign('levelConfig',$levelConfig);
        $this->display();
    }
    public function save(){
    	$data=$_POST['level'];
		F("levelConfig",$data,CACHEDATA_PATH);
		$this->success("操作成功");
    }
}

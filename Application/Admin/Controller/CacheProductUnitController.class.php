<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class CacheProductUnitController extends CommonController {

	public function index(){
		$ProductUnitConfig = F("ProductUnitConfig",'',CACHEDATA_PATH);
		$listorders = array();
		foreach ($ProductUnitConfig as $vo) {
		    $listorders[] = $vo['listorder'];
		}
		 
		array_multisort($listorders, SORT_DESC, $ProductUnitConfig);
		$this->assign('ProductUnitConfig',$ProductUnitConfig);
        $this->display();
    }
    public function save(){
    	$data=$_POST['ProductUnit'];
		F("ProductUnitConfig",$data,CACHEDATA_PATH);
		$this->success("操作成功");
    }
}

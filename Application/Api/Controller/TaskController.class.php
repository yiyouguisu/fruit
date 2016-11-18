<?php

namespace Api\Controller;

use Api\Common\CommonController;

class TaskController extends CommonController {
	private $exedir;
	private $serverType;
    function _initialize() {
		set_time_limit(20);
        parent::_initialize();
		$this->serverType='Linux';  
		$this->exedir = "/var/spool/cron/";
    }

	Public function startServer(){
		exec("service crond start",$out,$status);
		print_r(array_pop($out));
		exit;
	}
	
	
	Public function stopServer(){
		exec("service crond stop",$out,$status);
		print_r(array_pop($out));
		exit;
	}
	
	Public function showstatus(){
		$s = exec("service crond status",$out,$status);
		$res = array_pop($out);
		if(strpos($res," running")!==false) $str.='<font class="red">执守服务未开启</font>';
		else $str.='服务已开启';
		echo $str;
		exit;
	}

	Public function createConfigFile(){
		$exeDir = "/var/spool/cron";
		if(!is_dir($exeDir)||!is_writeable($exeDir)){
			return "配置文件生成失败,exe文件目录({$exeDir})不可写或者不存在";	
		}
		$list = M("Task")->field(true)->where("is_on=1 and id=15")->find();
		$config="";//#url|month|week|day|hour|min|id
		foreach($list as $v){
			if($v['hour']=="*"&&$v['min']!="*") $v['hour']="*/1";
			$config[]=$v['min']." ".$v['hour']." ".$v['day']." ".$v['month']." ".$v['week']." curl ". $v['task_url'];
		}
		$configStr = implode("\n",$config);
		file_put_contents($exeDir."/www",$configStr);
		return '';
	}
	Public function start(){
		$cmdStr = $_SERVER['DOCUMENT_ROOT']."/AutoTask/HttpTask.exe";
        exec($cmdStr,$out,$status);
        dump($out);
	}
}
?>
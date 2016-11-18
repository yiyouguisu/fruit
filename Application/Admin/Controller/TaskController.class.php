<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class TaskController extends CommonController {
	private $exedir;
	private $serverType;
    public function _initialize() {
		set_time_limit(20);
        parent::_initialize();
		if(PATH_SEPARATOR==':'){
			$this->serverType='Linux';  
			$this->exedir = "/var/spool/cron/";
		}else{
			$this->exedir = dirname(__ROOT__)."/autoExecute/";
		}
    }
    /**
     *  显示菜单
     */
    public function index() {
		$list = M("Task")->field(true)->select();
		$this->assign("list",$list);
        $this->display();
    }
	
	public function showlog(){
		$id = intval($_GET['id']);
		if(!$id) exit('数据有误');
		$vo = M("task_log")->where("task_id={$id}")->order("id DESC")->find();
		if(!is_array($vo)) exit('还没有执行记录');
		$content = $vo['task_info'];
		echo nl2br($content);
	}
	
	public function doadd(){
		$taskModel = D('Task');
		foreach($_POST as $key=>$v){
			${$key} = I("post.{$key}");
		}
		($month!="*")&&$month=$month_set;
		if($day!="*"){
			if($day=="set_week"){
				$day="*";	
				$week=$week_set;	
			}else{
				$day=$day_set;	
				$week="*";	
			}
		}else{
			$week="*";
		}
		($hour!="*")&&$hour=$hour_set;
		($min!="*")&&$min=$min_set;

		$save=array();
		$save['month'] = ($month!='*')?removeMu($month):'*';
		$save['day'] = ($day!='*')?removeMu($day):'*';
		$save['week'] = ($week!='*')?removeMu($week):'*';
		$save['hour'] = ($hour!='*')?removeMu($hour):'*';
		$save['min'] = ($min!='*')?removeMu($min):'*';
		$save['name'] = $name;
        $save['task_name'] = $task_name;
		$save['task_url'] = $task_url;
		$save['add_time'] = time();
		if(empty($save['name'])) $this->error("任务名称不能为空");
        if(empty($save['task_name'])) $this->error("任务标识名称不能为空");
		if(empty($save['task_url'])) $this->error("执行脚本地址不能为空");
		
		$newid = $taskModel->add($save);
		if($newid){
			$res = self::createConfigFile_windows($newid);
			$this->success("添加成功");
		}
		else $this->error("添加失败");
	}
	
	public function edit(){
		$id = intval($_GET['id']);
		$vo = M("Task")->field(true)->find($id);
		$this->assign("vo",$vo);
		$this->display();
	}
	
	public function doedit(){
		$taskModel = D('Task');
		foreach($_POST as $key=>$v){
			${$key} = I("post.{$key}");
		}
		($month!="*")&&$month=$month_set;
		if($day!="*"){
			if($day=="set_week"){
				$day="*";	
				$week=$week_set;	
			}else{
				$day=$day_set;	
				$week="*";	
			}
		}else{
			$week="*";
		}
		($hour!="*")&&$hour=$hour_set;
		($min!="*")&&$min=$min_set;

		$save=array();
		$save['month'] = ($month!='*')?removeMu($month):'*';
		$save['day'] = ($day!='*')?removeMu($day):'*';
		$save['week'] = ($week!='*')?removeMu($week):'*';
		$save['hour'] = ($hour!='*')?removeMu($hour):'*';
		$save['min'] = ($min!='*')?removeMu($min):'*';
		$save['name'] = $name;
        $save['task_name'] = $task_name;
		$save['task_url'] = $task_url;
		$save['is_on'] = $is_on;
		$save['add_time'] = time();
		$save['id'] = $id;
		if(empty($save['name'])) $this->error("任务名称不能为空");
        if(empty($save['task_name'])) $this->error("任务标识名称不能为空");
		if(empty($save['task_url'])) $this->error("执行脚本地址不能为空");
		
		$newid = $taskModel->save($save);
		if($newid){
            //$res = self::deletetask($id);
			$res = self::createConfigFile_windows($id);
			$this->success("修改成功".$res);
		}
		else $this->error("修改失败");
	}


	protected function _doDelFilter($id){
		$map['id']=array("in",$id);
		$map['is_sys'] = 1;
		$count = M('task')->where($map)->count('id');
		if($count>0) $this->error("系统内置任务不能删除，不使用'关闭'即可");
	}

	public function exeaction(){
		if($this->serverType=="Linux") $this->display("linux");
		else $this->display();
	}
	
	public function start(){
		exec($this->exedir."start_exe.exe",$out,$status);
		print_r(array_pop($out));
	}
	public function close(){
		$s = exec($this->exedir."stop_exe.bat",$out,$status);
		print_r(array_pop($out));
	}
	public function startServer(){
		if($this->serverType=="Linux") $this->linux_startServer();
		
		exec($this->exedir."install.bat",$out,$status);
		print_r(array_pop($out));
	}
	public function stopServer(){
		if($this->serverType=="Linux") $this->linux_stopServer();

		exec($this->exedir."uninstall.bat",$out,$status);
		print_r(array_pop($out));
	}
	public function showstatus(){

		if($this->serverType=="Linux") $this->linux_showstatus();

		exec($this->exedir."showstatus.bat",$out,$status);
		foreach($out as $key=>$v){
			if($v=="haveSer") $server=1;
			if($v=="haveExe") $exe=1;
		}
		$str="1:";
		if($server==1) $str.='服务已开启';
		else $str.='<font class="red">服务未开启</font>';
		$str.="2:";
		if($exe==1) $str.='执守程序已开启';
		else $str.='<font class="red">执守程序未开启</font>';
		MHeader("utf-8");
		echo $str;
	}	
	
	private function linux_startServer(){
		exec("service crond start",$out,$status);
		print_r(array_pop($out));
		exit;
	}
	
	
	private function linux_stopServer(){
		exec("service crond stop",$out,$status);
		print_r(array_pop($out));
		exit;
	}
	
	private function linux_showstatus(){
		$s = exec("service crond status",$out,$status);
		$res = array_pop($out);
		if(strpos($res," running")!==false) $str.='<font class="red">执守服务未开启</font>';
		else $str.='服务已开启';
		echo $str;
		exit;
	}
	
	
	protected function _afertDoDel($id){
		createConfigFile();
	}
    public function createConfigFile_windows($id){
        $task = M("Task")->field(true)->where("is_on=1 and id=".$id)->find();
        $cmd="schtasks.exe /create ";
        if($task['month']=='*'){
            if($task['week']=='*'){
                if($task['hour']=='*'){
                    if($task['min']=='*'){
                        $cmd = $cmd . "/sc minute /mo 1";
                    }else{
                        if($task['min']<10){
                            $task['min']="0".$task['min'];
                        }
                        $cmd = $cmd . "/sc hourly /st 00:" . $task['min']  . ":00";
                    }
                }else{
                    if($task['min']=='*'){
                        if($task['hour']<10){
                            $task['hour']="0".$task['hour'];
                        }
                        $cmd = $cmd . "/sc daily /st " . $task['hour'] . ":00:00";
                    }else{
                        if($task['min']<10){
                            $task['min']="0".$task['min'];
                        }
                        if($task['hour']<10){
                            $task['hour']="0".$task['hour'];
                        }
                        $cmd = $cmd . "/sc daily /st " . $task['hour'] . ":" . $task['min']  . ":00";
                    }
                }
            }else{
                if($task['day']=='*'){
                    if($task['hour']=='*'){
                        if($task['min']=='*'){
                            $cmd = $cmd . "/sc monthly /d " . week_change($task['week']);
                        }else{
                            if($task['min']<10){
                                $task['min']="0".$task['min'];
                            }
                            $cmd = $cmd . "/sc monthly /d " . week_change($task['week']) . " /st 00:" . $task['min']  . ":00";
                        }
                    }else{
                        if($task['min']=='*'){
                            if($task['hour']<10){
                                $task['hour']="0".$task['hour'];
                            }
                            $cmd = $cmd . "/sc daily /d " . week_change($task['week']) . " /st " . $task['hour']  . ":00:00";
                        }else{
                            if($task['min']<10){
                                $task['min']="0".$task['min'];
                            }
                            if($task['hour']<10){
                                $task['hour']="0".$task['hour'];
                            }
                            $cmd = $cmd . "/sc daily /d " . week_change($task['week']) . " /st " . $task['hour']  . ":" . $task['min']  . ":00";
                        }
                    }
                }
            }
            
        }else{
            if($task['week']=='*'){

                if($task['hour']=='*'){
                    if($task['min']=='*'){
                        $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /d " . $task['day'];
                    }else{
                        if($task['min']<10){
                            $task['min']="0".$task['min'];
                        }
                        $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /d " . $task['day'] . " /st 00:" . $task['min']  . ":00";
                    }
                }else{
                    if($task['min']=='*'){
                        if($task['hour']<10){
                            $task['hour']="0".$task['hour'];
                        }
                        $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /d " . $task['day'] . " /st " . $task['hour'] . ":00:00";
                    }else{
                        if($task['min']<10){
                            $task['min']="0".$task['min'];
                        }
                        if($task['hour']<10){
                            $task['hour']="0".$task['hour'];
                        }
                        $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /d " . $task['day'] . " /st " . $task['hour'] . ":" . $task['min']  . ":00";
                    }
                }
                
            }else{
                if($task['day']=='*'){
                    if($task['hour']=='*'){
                        if($task['min']=='*'){
                            $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /mo first /d " . week_change($task['week']);
                        }else{
                            if($task['min']<10){
                                $task['min']="0".$task['min'];
                            }
                            $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /mo first /d " . week_change($task['week']) . " /st 00:" . $task['min']  . ":00";
                        }
                    }else{
                        if($task['min']=='*'){
                            if($task['hour']<10){
                                $task['hour']="0".$task['hour'];
                            }
                            $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /mo first /d " . week_change($task['week']) . " /st " . $task['hour']  . ":00:00";
                        }else{
                            if($task['min']<10){
                                $task['min']="0".$task['min'];
                            }
                            if($task['hour']<10){
                                $task['hour']="0".$task['hour'];
                            }
                            $cmd = $cmd . "/sc monthly /m " . month_change($task['month']) . " /mo first /d " . week_change($task['week']) . " /st " . $task['hour']  . ":" . $task['min']  . ":00";
                        }
                    }
                }
            }
        }
        
        $cmd = $cmd . " /tn \"{$task['task_name']}\"";
        $cmd = $cmd . " /tr " . str_replace("/","\\",$_SERVER['DOCUMENT_ROOT'] .$this->exedir . $task['task_url']) ." /RU System";
        exec($cmd,$out,$status);
        return $status;
    }
    public function deletetask($id){
        $task = M("Task")->field(true)->where("is_on=1 and id=".$id)->find();
        $cmd="schtasks.exe /delete /tn \"{$task['task_name']}\" -f /RU System";
        exec($cmd,$out,$status);
        return $status;
    }
    public function querytask(){
        $id=1;
        $task = M("Task")->field(true)->where("is_on=1 and id=".$id)->find();
        $cmd="schtasks.exe /query /tn \"{$task['task_name']}\"";
        exec($cmd,$out,$status);
        dump( $out);
    }
}



function createConfigFile(){
	$isLinux = (PATH_SEPARATOR==':')?true:false;
	$exeDir = ($isLinux===true)?"/var/spool/cron":dirname(APP_WEB_ROOT)."/autoExecute";
	if(!is_dir($exeDir)||!is_writeable($exeDir)){
		return "配置文件生成失败,exe文件目录({$exeDir})不可写或者不存在";	
	}
	$list = M("Task")->field(true)->where("is_on=1")->select();
	$config="";//#url|month|week|day|hour|min|id
	foreach($list as $v){
		$v['task_url'] = (strpos($v['task_url'],"http:")===false)?WEB_HOST.$v['task_url']:$v['task_url'];
		$v['task_url'] = (strpos($v['task_url'],"?")===false)?$v['task_url']."?autokey=".C('AUTO_KEY'):$v['task_url']."\&autokey=".C('AUTO_KEY');
		if($isLinux===true){
			if($v['hour']=="*"&&$v['min']!="*") $v['hour']="*/1";
			$config[]=$v['min']." ".$v['hour']." ".$v['day']." ".$v['month']." ".$v['week']." curl ". $v['task_url']."\&id=".$v['id'];
		}else $config[]=$v['task_url']."|".$v['month']."|".$v['week']."|".$v['day']."|".$v['hour']."|".$v['min']."|".$v['id'];
	}
	if($isLinux===true)  $configStr = implode("\n",$config);
	else   $configStr = implode("\r\n",$config);
	if($isLinux===true) file_put_contents($exeDir."/www",$configStr);
	else file_put_contents($exeDir."/config.txt",$configStr);
	return '';
}


?>
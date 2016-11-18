<?php

/**
 * 后台首页
 * Some rights reserved：342556105@qq.com
 * Contact email:342556105@qq.com
 */
namespace Admin\Controller;

use Admin\Common\CommonController;

class SafeCenterController extends CommonController {
	static $i=0;
    //后台框架首页
    public function filetest() {
        $this->display();
    }
	public function dofiletest(){
		MHeader("utf-8");
		set_time_limit(0);
		echo $this->fetch("Common:Head");
		echo '<style>html, body{background:#FFFFFF}</style>';
		echo '<div class="wrap J_check_wrap">
			  <div class="ckresult">检测结果</div>
			  <div class="table_list dofiletest" style="border:0px none">
			  <li class="title"><span class="sp1">路径</span><span class="sp2">是否可读</span><span class="sp3">是否可写</span><span class="sp4">是否可执行</span></li>';
			 testSafe('file_deal','dir_deal');
		echo "</div>";
		echo "</div>";
		echo $this->fetch("dofiletest.js");
	}
	
    //后台框架首页
    public function filescan() {
    	//$path=$_SERVER['DOCUMENT_ROOT']."/Uploads/files";
    	//dump(fileAccessTest($path));die;

    	//test($path);die;
        $this->display();
    }
	public function dofilescan(){
		MHeader("utf-8");
		set_time_limit(0);
		echo $this->fetch("Common:Head");
		echo '<style>html, body{background:#FFFFFF}</style>';
		echo '<div class="wrap J_check_wrap">
			  <div class="ckresult">检测结果</div>
			  <div class="table_list dofiletest dofilescan" style="border:0px none">
			  <li class="title"><span class="sp1">路径</span><span class="sp2">文件类型</span><span class="sp3">删除</span><span class="sp4">查看</span><span class="sp5">最后修改时间</span><span class="sp6">最后访问时间</span></li>';
			 testSafeFile('file_deal_scan','dir_deal_scan');
		echo "</div>";
		echo "</div>";
		echo $this->fetch("dofilescan.js");
	}
	
	//删除文件
	public function deletefile(){
		if(I('post.type')=="all"){
			$i=0;
			$j=0;
			$ids=array();
			$paths = I('post.path');	
			foreach($paths as $key=>$path){
				if(!fileAccessTest($path)){
					$j++;
					continue;
				}
				if(is_file($path)) $res = @unlink($path);
				if($res){
					$ids[] = $key;
					$i++;
				}
			}
			if($i>0){
				$msg = ($j>0)?"禁止访问的文件{$j}个":'';
				$data['ids'] = $ids;
				$data['message'] = "成功删除{$i}个文件,失败".(count($paths)-$i)."个文件".$msg;
				$this->ajaxReturn($data,"json");
			}
			else ajaxmsg("删除失败",0);
		}else{
			$path = urldecode(I('post.path'));	
			if(!fileAccessTest($path)) ajaxmsg("禁止访问",0);
			if(is_file($path)) $res = @unlink($path);
			if($res) ajaxmsg("删除成功");
			else ajaxmsg("删除失败",0);
		}
	}
	
	public function showfile(){
		MHeader('utf-8');
		$path = urldecode(I('get.path'));
		if(!fileAccessTest($path)) exit("禁止访问");
		if(is_file($path)) $content = @file_get_contents($path);
		echo $content;
	}
}



?>

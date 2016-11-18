<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class AttachmentController extends CommonController {
    var $adminType;
    public function _initialize() {
		$this->adminType=array(1,2,3,4,5);
		$this->upload_file_type=C("upload_file_type");
    }
    public function index() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            
            $ext = I('get.ext');
            if (!empty($ext)) {
                $where["ext"] = array("EQ", $ext);
            }
            //状态
            $is_admin = I('get.is_admin', null, 'intval');
            $username=I('username');
            if(!empty($username)){
	            if ($is_admin != "" && $is_admin != null) {
	            	if($is_admin==0){
		                $uid=M('Member')->where(array('username'=>$username))->getField('id');
	            	}else{
		                $uid=M('user')->where(array('username'=>$username))->getField('id');
	            	}
	            	if(!empty($uid)){
		                $where['uid']=$uid;
		            }
	            }
            }
            
            
        }
      
        $count = D("Attachment")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Attachment")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $isadmin=(in_array($r['catid'], $this->adminType))?1:0;
            $data[$k]['isadmin']=$isadmin;
            if($isadmin==1){
                $data[$k]['username']=M('user')->where(array('id'=>$r['uid']))->getField("username");
            }
            $data[$k]["catname"] = $this->upload_file_type[$r['catid']];
            
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }


    
}
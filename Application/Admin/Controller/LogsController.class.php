<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class LogsController extends CommonController {
      /**
     * 登录日志查看
     */
    public function  login(){
        $username =$_POST["username"];
        $start_time =$_POST['start_time'];
        $end_time = $_POST['end_time'];
        $ip = $_POST["ip"];
        $status = $_POST["status"];
        if(!empty($username)){
             $data['username'] = array('like', '%'.$username.'%');
        }
        if(!empty($start_time) && !empty($end_time)){
            $data['_string'] = " `logintime` >'$start_time' AND  `logintime`<'$end_time' ";
        }
        if(!empty($ip )){
            $data['loginip'] = array('like', '%'.$ip.'%');
        }
        if(!empty($status)){
            $data['status'] = array('eq',$status);
        }
        if(is_array($data)){
            $data['_logic'] = 'or';
            $map['_complex'] = $data;
        }else{
            $map = array();
        }
        $count = M("loginlog")->where($map)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $Logs = M("loginlog")->where($map)->limit($page->firstRow.','.$page->listRows)->order(array("logintime"=>"desc"))->select();
        $show = $page->show();
        $this->assign("logs",$Logs);
        
        $this->assign("Page",$show);
        $this->display();
    }
    
     /**
     * 删除一个月前的登陆日志
     */
    public function logindel(){
        $t = date("Y-m-d H:i:s",  time()-2592000);
     
        if(D("Loginlog")->where(array("logintime"=>array("lt",$t)))->delete() !== false){
            $this->success("删除登陆日志成功！");
        }else{
            $this->error("删除登陆日志失败！");
        }
    }
    
    /**
     * 操作日志查看
     */
    public function  index(){
        $uid =$_POST["uid"];
        $start_time =$_POST['start_time'];
        $end_time = $_POST['end_time'];
        $ip = $_POST["ip"];
        $status = $_POST["status"];
        if(!empty($uid)){
            $data['uid'] = array('eq', $uid);
        }
        if(!empty($start_time) && !empty($end_time)){
            $data['_string'] = " `time` >'$start_time' AND  `time`<'$end_time' ";
        }
        if(!empty($ip )){
            $data['ip '] = array('like', '%'.$ip.'%');
        }
        if(!empty($status)){
            $data['status'] = array('eq',$status);
        }
        if(is_array($data)){
            $data['_logic'] = 'or';
            $map['_complex'] = $data;
        }else{
            $map = array();
        }
        $count = M("Log")->where($map)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $Logs = M("Log")->where($map)->limit($page->firstRow.','.$page->listRows)->order(array("id"=>"desc"))->select();
        foreach ($Logs as $key => $value) {
            $Logs[$key]["username"]=M("user")->where("id=".$value["uid"])->getField("username");
        }
        $show = $page->show();
        $this->assign("logs",$Logs);
        $this->assign("Page",$show);
        $this->display();
    }
    
    /**
     * 删除一个月前的操作日志
     */
    public function del(){
        $t = date("Y-m-d H:i:s",  time()-2592000);
           print_r($t);
        exit();
        if(D("Log")->where(array("time"=>array("lt",$t)))->delete() !== false){
            $this->success("删除操作日志成功！");
        }else{
            $this->error("删除操作日志失败！");
        }
    }

}
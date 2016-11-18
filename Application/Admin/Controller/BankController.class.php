<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class BankController extends CommonController {

    public function index() {
        $search = I('post.search');
        $where = array();
        if (!empty($search)) {
             //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            $username=I('username');
            $realname=I('realname');
            if(!empty($username)){
                $select['username']=$username;
            }
            if(!empty($realname)){
                $select['realname']=$realname;
            }
            $uid=M('Member')->where($select)->getField('id');
            if(!empty($uid)){
                $where['uid']=$uid;
            }
            $bankname=I('bankname');
            if(!empty($bankname)){
                $select['bankname']=$bankname;
            }
        }
        $count = M("bank")->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M("bank")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }

    public function edit() {
        if (IS_POST) {
            if (false !== D("bank")->save($_POST)) {
                $this->success("更新成功！", U("Admin/Bank/index"));
            } else {
                $this->error(D("bank")->getError());
            }
        } else {
            $data = M("bank")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $this->display();
        }
    }
    
}
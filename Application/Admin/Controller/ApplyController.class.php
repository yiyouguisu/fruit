<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ApplyController extends CommonController {
    public function _initialize(){
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->companyid=$_SESSION['companyid'];
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
            
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('phone', 'email', 'name');
                if ($searchtype < 3) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = D("apply")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("apply")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function company() {
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
            
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('username','tel', 'email');
                if ($searchtype < 3) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = D("cooperation")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("cooperation")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $key => $value) {
            # code...
            $status=M('Company')->where(array('applyid'=>$value['id']))->find();
            if($status){
                $data[$key]['isallot']=1;
            }else{
                $data[$key]['isallot']=0;
            }
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    /**
     *  删除
     */
    public function companydelete() {
        $id = $_GET['id'];
        if (D("cooperation")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function companydel(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("cooperation")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function companyreview(){
        if (IS_POST) {
            $status=I('status');
            $data=M('cooperation')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $checkstatus=M('cooperation')->where(array('companynumber'=>$_POST['companynumber']))->find();
            if(!empty($checkstatus)){
                $this->error("企业编号已经存在！");
            }
            $id=M('cooperation')->where('id=' . I('id'))->save(array(
                'companynumber'=>$_POST['companynumber'],
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功！");
            }elseif($id>0&&$status==3){
                $this->success("审核成功！");
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('cooperation')->where(array('id'=>$id))->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
    public function shop() {
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
            
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('username','tel', 'email');
                if ($searchtype < 3) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = D("Store_apply")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Store_apply")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $key => $value) {
            # code...
            $status=M('Store')->where(array('applyid'=>$value['id']))->find();
            if($status){
                $data[$key]['isallot']=1;
            }else{
                $data[$key]['isallot']=0;
            }
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    /**
     *  删除
     */
    public function shopdelete() {
        $id = $_GET['id'];
        if (D("Store_apply")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function shopdel(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Store_apply")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function shopreview(){
        if (IS_POST) {
            $status=I('status');
            $data=M('Store_apply')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('Store_apply')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功！");
            }elseif($id>0&&$status==3){
                $this->success("审核成功！");
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('Store_apply')->where(array('id'=>$id))->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
    public function joincompany() {
        $search = I('get.search');
        $where = array();
        $where['companyid']=$this->companyid;
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
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = M("company_member")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("company_member")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $key => $value) {
            # code...
            $data[$key]['company']=M('company')->where(array('id'=>$value['companyid']))->getField("title");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->display();
    }
    /**
     *  删除
     */
    public function joincompanydelete() {
        $id = $_GET['id'];
        if (D("company_member")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function joincompanydel(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("company_member")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function joincompanyreview(){
        if (IS_POST) {
            $status=I('status');
            $data=M('company_member')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('company_member')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                M('Member')->where(array('id'=>$data['uid']))->setField("companyid",$data['companyid']);
                $this->success("审核成功！");
            }elseif($id>0&&$status==3){
                $this->success("审核成功！");
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('company_member')->where(array('id'=>$id))->find();
            $data['company']=M('company')->where(array('id'=>$data['companyid']))->getField("title");
            $this->assign("data",$data);
            $this->display();
        }
    }
    
    /*
     * 操作判断
     */

    public function action() {
        $submit = trim($_POST["submit"]);
        if ($submit == "del") {
            $this->del();
        } elseif ($submit == "companydel") {
            $this->companydel();
        } elseif ($submit == "shopdel") {
            $this->shopdel();
        } elseif ($submit == "joincompanydel") {
            $this->joincompanydel();
        } elseif ($submit == "joincompanyreviewsuccess") {
            $this->joincompanyreviewsuccess();
        } elseif ($submit == "joincompanyreviewerror") {
            $this->joincompanyreviewerror();
        }
    }
    public function joincompanyreviewsuccess(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                $data=M('company_member')->where('id=' . $id)->find();
                if($data['status']==1){
                        
                    $mid=M('company_member')->where('id=' . $id)->save(array(
                        'status'=>2,
                        'verify_time' => time(),
                        'verify_user' => $_SESSION['user']
                    ));
                    if($mid){
                        M('Member')->where(array('id'=>$data['uid']))->setField("companyid",$data['companyid']);
                    }
                }
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
        
    }
    public function joincompanyreviewerror(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                $data=M('company_member')->where('id=' . $id)->find();
                if($data['status']==1){
                        
                    $mid=M('company_member')->where('id=' . $id)->save(array(
                        'status'=>3,
                        'verify_time' => time(),
                        'verify_user' => $_SESSION['user']
                    ));
                    
                }
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
        
    }
}
<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class FeedbackController extends CommonController {

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
            //状态
            $status = $_POST["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }elseif($searchtype == 0){
                    $where["content"] = array("like", "%{$keyword}%");
                }else if($searchtype == 1){
                    $select["username"] = array("LIKE", "%" . $keyword . "%");
                    $uids=M('member')->where($select)->getField("id",true);
                    $where['uid']=array('in',$uids);
                }
            }
        }
        $count = M("feedback")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("feedback")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["username"] = D("Member")->where("id=" . $r["uid"])->getField("username");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

    public function feedbackcheck(){
        if (IS_POST) {
            $status=I('status');
            $data=M('feedback')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('feedback')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功！", U("Admin/feedback/index"));
            }elseif($id>0&&$status==3){
                $this->success("审核成功！", U("Admin/feedback/index"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('feedback')->where('id=' . $id)->find();
            $user=M('member')->where('id=' . $data['uid'])->field('username,phone')->find();
            $data=array_merge($data,$user);
            $this->assign("data", $data);
            $this->display();
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("feedback")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 删除内容
     */

    public function del() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("feedback")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    
}
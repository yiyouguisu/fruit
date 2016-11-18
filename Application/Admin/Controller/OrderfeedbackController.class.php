<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class OrderfeedbackController extends CommonController {
    public function _initialize(){
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $search = I('post.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['b.storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            //状态
            $status = $_POST["status"];
            if ($status != "" && $status != null) {
                $where["a.status"] = array("EQ", $status);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 3) {
                    $where["a.id"] = array("EQ", (int) $keyword);
                }elseif($searchtype == 0){
                    $where["a.content"] = array("like", "%{$keyword}%");
                }elseif($searchtype == 2){
                    $where["a.orderid"] = array("like", "%{$keyword}%");
                }else if($searchtype == 1){
                    $select["username"] = array("LIKE", "%" . $keyword . "%");
                    $uids=M('member')->where($select)->getField("id",true);
                    $where['a.uid']=array('in',$uids);
                }
            }
        }
        $count = M("order_feedback a")->join("left join zz_order b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order_feedback a")->join("left join zz_order b on a.orderid=b.orderid")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order(array("a.id" => "desc"))
            ->field("a.*,b.orderid")
            ->select();
        foreach ($data as $k => $r) {
            $data[$k]["username"] = D("Member")->where("id=" . $r["uid"])->getField("username");
            $data[$k]['imglist']=explode("|",$r['thumb']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }

    public function feedbackcheck(){
        if (IS_POST) {
            $status=I('status');
            $data=M('Order_feedback')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('Order_feedback')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$data['orderid'],
                'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."进行了订单反馈审核，审核通过！审核备注：".$_POST['remark'],
                'username'=>$_SESSION['user'],
                'status'=>1,
                'inputtime'=>time()
                ));
                $this->success("审核成功！", U("Admin/Orderfeedback/index"));
            }elseif($id>0&&$status==3){
                M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$data['orderid'],
                'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."进行了订单反馈审核，审核不通过！审核备注：".$_POST['remark'],
                'username'=>$_SESSION['user'],
                'status'=>0,
                'inputtime'=>time()
                ));
                $this->success("审核成功！", U("Admin/Orderfeedback/index"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('Order_feedback')->where('id=' . $id)->find();
            $user=M('member')->where('id=' . $data['uid'])->field('username,realname,head,idcard,sex,email,phone')->find();
            $data=array_merge($data,$user);
            $this->assign("data", $data);
            $imglist=explode("|", $data['thumb']);
            $this->assign("imglist",$imglist);
            $this->display();
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (M("Order_feedback")->delete($id)) {
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
                M("Order_feedback")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function feedbacklog(){
        if(IS_POST){
            if(empty($_POST['loginfo'])){
                $this->error("记录内容不能为空！");
            }
            $id=M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$_POST['orderid'],
                'loginfo'=>$_POST['loginfo'],
                'username'=>$_SESSION['user'],
                'status'=>1,
                'inputtime'=>time()
                ));

            if($id){
                $this->success("操作成功");
            }else{
                $this->error("操作失败");
            }
        }else{
            $orderid=I('orderid');
            $this->assign("orderid",$orderid);
            $data=M('feedback_dolog')->where(array('varname'=>'order','value'=>$orderid))->order(array('inputtime'=>'desc'))->select();
            $this->assign("data",$data);
            $this->display();
        }
    }
    
}
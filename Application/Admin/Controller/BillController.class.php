<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class BillController extends CommonController {

    public function index() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["a.bill_apply_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.bill_apply_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.bill_apply_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["b.storeid"] = array("EQ", $storeid);
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["a.bill_apply_status"] = array("EQ", $status);
            }
        }
        $where['a.status']=5;
        $where['b.ordertype']=array("neq",3);
        $where['b.money']=array('gt',0);
        $where['a.bill_apply_status']=array("gt",0);
        $count = M("order_time a")->join("left join zz_order b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order_time a")
            ->join("left join zz_order b on a.orderid=b.orderid")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->field("a.orderid,a.bill_apply_status,a.bill_apply_time,a.bill_review_remark,a.bill_review_time,b.billtype,b.billtitle,b.billaddressid,b.uid,b.storeid,b.total")
            ->order(array("a.bill_apply_status" => "asc","a.inputtime" => "desc"))
            ->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);

        $this->display();
    }
    public function review(){
        if (IS_POST) {
            $status=I('status');
            $orderid=I('orderid');
            $data=M('order_time')->where(array('orderid'=>$orderid))->find();
            if($data['bill_apply_status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                'bill_apply_status'=>$status,
                'bill_review_time' => time(),
                'bill_review_remark'=>I('remark')
            ));
            if($id){
                $this->success("审核成功！");
            }else{
                $this->error("审核失败！");
            }
        } else {
            $orderid=I('orderid');
            $data=M('order_time')->where(array('orderid'=>$orderid))->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
}
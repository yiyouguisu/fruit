<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class CompanyController extends CommonController {
    public function _initialize(){
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->companyid=$_SESSION['companyid'];
    }
    /**
     * 企业列表
     * @author oydm<389602549@qq.com>  time|20140507
     */
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
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $type_array = array('title','username','tel','email');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("Company")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("Company")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }
    
    /**
     *  添加
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function add() {
        if (IS_POST) {
            if (D("Company")->create()) {
                D("Company")->inputtime = time();
                D("Company")->ip = get_client_ip();
                $id = D("Company")->add();
                if ($id) {
                    $member['username']=$_POST['loginname'];
                    $member['password']=$_POST['password'];
                    $member['role']=5;
                    $member['group_id']=6;
                    $uid=D("user")->addUser($member);
                    if($uid){
                        M('Company')->where(array('id'=>$id))->setField("uid",$uid);
                    }
                    $this->success("新增企业成功！", U("Admin/Company/index"));
                } else {
                    $this->error("新增企业失败！");
                }
            } else {
                $this->error(D("Company")->getError());
            }
        } else {
            $data=M('cooperation')->where('id=' . I('applyid'))->find();
            $this->assign("data",$data);
            $this->display();
        }
    }

    /**
     *  删除
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function del() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        //执行删除
        if (D("Company")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(D("Company")->getError());
        }
    }

    /**
     *  编辑
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function edit() {
        if (IS_POST) {
            if (D("Company")->create()) {
                D("Company")->updatetime = time();
                $id = D("Company")->save();
                if (!empty($id)) {
                    $member['username']=$_POST['loginname'];
                    $member['password']=$_POST['password'];
                    $member['id']=$_POST['uid'];
                    D("user")->editUser($member);
                    $this->success("修改企业成功！", U("Admin/Company/index"));
                } else {
                    $this->error("修改企业失败！");
                }
            } else {
                $this->error(D("Company")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("企业ID参数错误");
            }
            $data=D("Company")->where("id=".$id)->find();
            $this->assign("data", $data);
            $companyuser=D("user")->where("id=".$data['uid'])->find();
            $this->assign("companyuser", $companyuser);

            $this->display();
        }
        
    }
    public function info() {
        if (IS_POST) {
            if (D("Company")->create()) {
                D("Company")->updatetime = time();
                $id = D("Company")->save();
                if (!empty($id)) {
                    $this->success("修改企业成功！", U("Admin/Company/index"));
                } else {
                    $this->error("修改企业失败！");
                }
            } else {
                $this->error(D("Company")->getError());
            }
        } else {
            $id= $this->companyid;
            if (empty($id)) {
                $this->error("企业ID参数错误");
            }
            $data=D("Company")->where("id=".$id)->find();
            $this->assign("data", $data);
            $this->display();
        }
        
    }
    public function member() {
        $search = I('post.search');
        $where = array();
        $where['companyid']=$this->companyid;
        $where['group_id']=1;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["reg_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["reg_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            //性别
            $sex= I('post.sex', null, 'intval');
            if (!empty($sex)) {
                $where["sex"] = array("EQ", $sex);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $type_array = array('username','realname','email','phone');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("member")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("member")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function changestatus(){
        $id = $_GET['id'];
        $status=M('member')->where(array('id'=>$id))->getField("status");
        if($status==1){
            $mid=M('member')->where(array('id'=>$id))->setField("status",0);
        }else{
            $mid=M('member')->where(array('id'=>$id))->setField("status",1);
        }
        if ($mid) {
            $this->success("更新成功！");
        } else {
            $this->error("更新失败！");
        }
    }
    public function order() {
        $search = I('get.search');
        $where = array();
        $uids=M('Member')->where(array('companyid'=>$this->companyid))->getField("id",true);
        $where['a.uid']=array('in',$uids);
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            $paytype = I('get.paytype');
            if ($paytype != "" && $paytype != null) {
                $where["a.paytype"] = array("EQ", $paytype);
            }
            $paystatus = I('get.paystatus');
            if ($paystatus != "" && $paystatus != null) {
                $where["b.pay_status"] = array("EQ", $paystatus);
            }

            $deliverystatus = I('get.deliverystatus');
            if ($deliverystatus != "" && $deliverystatus != null) {
                $where["b.delivery_status"] = array("EQ", $deliverystatus);
            }
            $uid = I('get.uid');
            if ($uid != "" && $uid != null) {
                $where["a.uid"] = array("EQ", $uid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.inputtime" => "desc"))->field("a.*,b.pay_status,b.delivery_status,b.donetime")->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $select['group_id']=1;
        $search['companyid']=$this->companyid;
        $daili = M("member")->where($select)->select();
        $this->assign("daili", $daili);
        $this->display();
    }
    public function ordershow(){
        $id = I('get.id', null, 'intval');
        if (empty($id)) {
            $this->error("ID参数错误");
        }
        $data = D("order")->where("id=" . $id)->find();
        $this->assign("data", $data);

        $pro = M("order_productinfo a")->join("left join zz_product b on  a.pid=b.id")->where("a.orderid=" . $data['orderid'])->field("a.*,b.title")->select();
        foreach ($pro as $key => $value)
        {
        	$pro[$key]['total']=sprintf("%.2f",$value['price'] * $value['nums']); 
        }
        
        $this->assign("pro", $pro);

        $this->display();
    }
}
<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class BalanceController extends CommonController {
    public function _initialize() {
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $search = I('get.search');
        if (!empty($search)) {
            $start_time = I('get.start_time',0,strtotime);
            if (empty($start_time)) {
                $start_time = time();
            }
            $end_time = I('get.end_time',0,strtotime);
            if (empty($end_time)) {
                $end_time = time();
            }
        }else{
            $end_time=time();
            $start_time=strtotime("-1 months");
        }
        $date=getdays($start_time,$end_time);
        $page = new \Think\Page(count($date), 3);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $datedata=array_slice($date,$page->firstRow,$page->listRows);
        $data=array();
        foreach($datedata as $key => $value){
            $starttime=mktime(0,0,0,intval(date("m",$value)),intval(date("d",$value)),intval(date("Y",$value)));
            $endtime=mktime(23,59,59,intval(date("m",$value)),intval(date("d",$value)),intval(date("Y",$value)));
            $where=array();
            $where['inputtime'] = array(array('EGT', $starttime), array('ELT', $endtime));

            $data[$key]['date']=$value;
            $data[$key]['total']=M('order')->where($where)->sum("total");
            $data[$key]['alipay_money']=M('order')->where($where)->where(array('paystyle'=>1,'paytype'=>1))->sum("total");
            $data[$key]['wx_money']=M('order')->where($where)->where(array('paystyle'=>1,'paytype'=>2))->sum("total");
            $data[$key]['wallet_money']=M('order')->where($where)->where(array('wallet'=>array('gt',0)))->sum("wallet");
            $data[$key]['delivery_money']=M('order')->where($where)->where(array('paystyle'=>2))->sum("total");
            $data[$key]['discount_money']=M('order')->where($where)->where(array('discount'=>array('gt',0)))->sum("discount");
            $data[$key]['total_order']=M('order')->where($where)->count();

            $ids=M('member')->where(array('reg_time'=>array('gt',strtotime("-3 hours"))))->getField("id",true);
            $data[$key]['new_order']=M('order')->where($where)->where(array('uid'=>array('in',$ids)))->count();
            $data[$key]['discount_order']=M('order')->where($where)->where(array('discount'=>array('gt',0)))->count();
            $data[$key]['delivery_order']=M('order')->where($where)->where(array('paystyle'=>2))->count();
            $subdata=array(
                array("id"=>3,"name"=>"饿了么"),
                array("id"=>4,"name"=>"口碑外卖"),
                array("id"=>1,"name"=>"手机web"),
                array("id"=>2,"name"=>"App"),
                );
            foreach($subdata as $k =>$val){
                $select=array();
                $select['ordersource']=$val['id'];
                $select['inputtime'] = array(array('EGT', $starttime), array('ELT', $endtime));
                $subdata[$k]['total']=M('order')->where($select)->sum("total");
                $subdata[$k]['total_order']=M('order')->where($select)->count();
                $subdata[$k]['wallet_money']=M('order')->where($select)->where(array('wallet'=>array('gt',0)))->sum("wallet");
                $subdata[$k]['discount_money']=M('order')->where($select)->where(array('discount'=>array('gt',0)))->sum("discount");
                $subdata[$k]['discount_order']=M('order')->where($select)->where(array('discount'=>array('gt',0)))->count();
                $subdata[$k]['delivery_money']=M('order')->where($select)->where(array('paystyle'=>2))->sum("total");
                $subdata[$k]['delivery_order']=M('order')->where($select)->where(array('paystyle'=>2))->count();
                $subdata[$k]['alipay_money']=M('order')->where($select)->where(array('paystyle'=>1,'paytype'=>1))->sum("total");
                $subdata[$k]['wx_money']=M('order')->where($select)->where(array('paystyle'=>1,'paytype'=>2))->sum("total");
                
            }
            $data[$key]['subdata']=$subdata;
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $totalmoney=M('order')->sum("total");
        $this->assign("totalmoney", $totalmoney);
        $this->display();
    }
    public function store() {
        if (!empty($search)) {
            $start_time = I('get.start_time',0,strtotime);
            if (empty($start_time)) {
                $start_time = time();
            }
            $end_time = I('get.end_time',0,strtotime);
            if (empty($end_time)) {
                $end_time = time();
            }
        }else{
            $end_time=time();
            $start_time=strtotime("-1 months");
        }
        $date=getdays($start_time,$end_time);
        $page = new \Think\Page(count($date), 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $datedata=array_slice($date,$page->firstRow,$page->listRows);
        $data=array();
        foreach($datedata as $key => $value){
            $starttime=mktime(0,0,0,intval(date("m",$value)),intval(date("d",$value)),intval(date("Y",$value)));
            $endtime=mktime(23,59,59,intval(date("m",$value)),intval(date("d",$value)),intval(date("Y",$value)));
            $where=array();
            $where['storeid']=$this->storeid;
            $where['inputtime'] = array(array('EGT', $starttime), array('ELT', $endtime));

            $data[$key]['date']=$value;
            $data[$key]['total']=M('order')->where($where)->sum("total");
            $data[$key]['alipay_money']=M('order')->where($where)->where(array('paystyle'=>1,'paytype'=>1))->sum("total");
            $data[$key]['wx_money']=M('order')->where($where)->where(array('paystyle'=>1,'paytype'=>2))->sum("total");
            $data[$key]['wallet_money']=M('order')->where($where)->where(array('wallet'=>array('gt',0)))->sum("wallet");
            $data[$key]['delivery_money']=M('order')->where($where)->where(array('paystyle'=>2))->sum("total");
            $data[$key]['discount_money']=M('order')->where($where)->where(array('discount'=>array('gt',0)))->sum("discount");
            $data[$key]['total_order']=M('order')->where($where)->count();

            $ids=M('member')->where(array('reg_time'=>array('gt',strtotime("-3 hours"))))->getField("id",true);
            $data[$key]['new_order']=M('order')->where($where)->where(array('uid'=>array('in',$ids)))->count();
            $data[$key]['discount_order']=M('order')->where($where)->where(array('discount'=>array('gt',0)))->count();
            $data[$key]['delivery_order']=M('order')->where($where)->where(array('paystyle'=>2))->count();
            
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $totalmoney=M('order')->sum("total");
        $this->assign("totalmoney", $totalmoney);
        $this->display();
    }
    public function company() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            $year = I('get.year');
            if (!empty($year)) {
                $where["a.year"] = array("eq", $year);
            }
            $month = I('get.month');
            if (!empty($month)) {
                $where["a.month"] = array("eq", $month);
            }
            
            $companyid = I('get.companyid');
            if ($companyid != "" && $companyid != null) {
                $where["a.companyid"] = array("EQ", $companyid);
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["a.status"] = array("EQ", $status);
            }
        }
        $count = M("companyorder_info a")->join("left join zz_company b on a.companyid=b.id")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("companyorder_info a")->join("left join zz_company b on a.companyid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field("a.*,b.title,b.username,b.tel")->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $company = M("company")->where(array('status'=>1))->select();
        $this->assign("company", $company);

        $this->display();
    }
    
    public function companyorderdeal(){
        if (IS_POST) {
            $money=$_POST['money'];
            $id=$_POST['id'];
            $data=M('companyorder_info')->where(array('id'=>$id))->find();
            if($data['status']==2){
                $this->error("已完成结算！");
            }
            if($money==$data['no_money']){
                $status=2;
            }else{
                $status=1;
            }
            $id=M('companyorder_info')->where(array('id'=>$id))->save(array(
                'yes_money'=>$data['yes_money']+$money,
                'no_money'=>$data['no_money']-$money,
                'status'=>$status,
                'last_paytime' => time()
            ));
            if($id){
                $this->success("结算成功！");
            }else{
                $this->error("结算失败！");
            }
        } else {
            $data=M("companyorder_info a")->join("left join zz_company b on a.companyid=b.id")->where(array('a.id'=>$_GET['id']))->field("a.*,b.title,b.username,b.tel")->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
    public function companyorderinfo() {
        $id=I('id');
        $data=M('companyorder_info')->where(array('id'=>$id))->find();
        $where=array();
        $where['c.status']=5;
        $ids=M('member')->where(array('companyid'=>$data['companyid']))->getField("id",true);
        $where['a.uid']=array('in',$ids);

        $starttime=mktime(0,0,0,$data['month'],1,$data['year']);
        $endtime=strtotime("+1 months -1 days",$starttime);
        
        $where['a.inputtime'] = array(array('EGT', $starttime), array('ELT', $endtime));

        $count = M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field("a.*,b.title as storename,c.inputtime,c.donetime")->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function productinfo() {
        $where['a.orderid']=$_GET['orderid'];
        $count = M("order_productinfo a")->join("left join zz_product b on a.pid=b.id")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order_productinfo a")->join("left join zz_product b on a.pid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field("a.*,b.title as productname")->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function runercommission() {
        $search = I('get.search');
        $where = array();
        $ruids=M('store_member')->where(array('storeid'=>$this->storeid))->getField("ruid",true);
        $where["a.ruid"]=array("in",$ruids);
        if (!empty($search)) {
            $start_year = I('get.start_year');
            $end_year = I('get.end_year');
            $start_month = I('get.start_month');
            $end_month = I('get.end_month');
            if (!empty($start_year)&&!empty($end_year)) {
                if($start_year==$end_year){
                    $where["a.year"] = array("eq", $start_year);
                }else{
                    $where["a.year"] = array(array("egt", $start_year),array("elt", $end_month));
                }
                
            }
            if (!empty($start_month)&&!empty($end_month)) {
                if($start_month==$end_month){
                    $where["a.month"] = array("eq", $start_month);
                }else{
                    $where["a.month"] = array(array("egt", $start_month),array("elt", $end_month));
                }
            }
            $runerid = I('get.runerid');
            if ($runerid != "" && $runerid != null) {
                $where["a.ruid"] = array("EQ", $runerid);
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["a.status"] = array("EQ", $status);
            }
        }
        $count = M("runercommission_info a")->join("left join zz_member b on a.ruid=b.id")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("runercommission_info a")->join("left join zz_member b on a.ruid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field("a.*,b.realname,b.username,b.phone")->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $runer = M('store_member a')->join("left join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid))->select();
        $this->assign("runer", $runer);
        $totalyes_money=M('runercommission_info')->where(array('ruid'=>array("in",$ruids)))->sum("yes_money");
        $this->assign("totalyes_money", $totalyes_money);
        $totalno_money=M('runercommission_info')->where(array('ruid'=>array("in",$ruids)))->sum("no_money");
        $this->assign("totalno_money", $totalno_money);
        $this->display();
    }
    public function runercommissioninfo() {
        $id=I('id');
        $data=M('runercommission_info')->where(array('id'=>$id))->find();

        $where=array();
        $where['c.status']=5;
        $where['a.ruid']=$data['ruid'];

        $starttime=mktime(0,0,0,$data['month'],1,$data['year']);
        $endtime=strtotime("+1 months -1 days",$starttime);
        
        $where['c.donetime'] = array(array('EGT', $starttime), array('ELT', $endtime));

        $count = M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->count();
        $page = new \Think\Page($count, 5);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order a")
            ->join("left join zz_store b on a.storeid=b.id")
            ->join("left join zz_order_time c on a.orderid=c.orderid")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order(array("a.id" => "desc"))
            ->field("a.*,b.title as storename,c.inputtime,c.donetime")
            ->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->assign("totalnum",$count);
        $this->assign("totalmoney",$count*3);

        $where['a.isspeed']=1;
        $speednum=M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->count();
        $this->assign("speednum",$speednum);
        $this->assign("speedmoney",$speednum*3);

        $where['a.isspeed']=0;
        $simplenum=M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->count();
        $this->assign("simplenum",$simplenum);
        $this->assign("simplemoney",$simplenum*3);

        $this->display();
    }
    public function runercommissiondeal(){
        if (IS_POST) {
            $money=$_POST['money'];
            $id=$_POST['id'];
            $data=M('runercommission_info')->where(array('id'=>$id))->find();
            if($data['status']==2){
                $this->error("已完成结算！");
            }
            if($money==$data['no_money']){
                $status=2;
            }else{
                $status=1;
            }
            $id=M('runercommission_info')->where(array('id'=>$id))->save(array(
                'yes_money'=>$data['yes_money']+$money,
                'no_money'=>$data['no_money']-$money,
                'status'=>$status,
                'last_paytime' => time()
            ));
            if($id){
                $this->success("结算成功！");
            }else{
                $this->error("结算失败！");
            }
        } else {
            $data=M("runercommission_info a")->join("left join zz_member b on a.ruid=b.id")->where(array('a.id'=>$_GET['id']))->field("a.*,b.nickname,b.username,b.phone")->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
    public function runer() {
        $search = I('get.search');
        $where = array();
        $ruids=M('store_member')->where(array('storeid'=>$this->storeid))->getField("ruid",true);
        $where["a.ruid"]=array("in",$ruids);
        $search = I('get.search');
        if (!empty($search)) {
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["a.date"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.date"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.date'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            
            $runerid = I('get.runerid');
            if ($runerid != "" && $runerid != null) {
                $where["a.ruid"] = array("EQ", $runerid);
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["a.status"] = array("EQ", $status);
            }

        }else{
            $where['date'] = array('eq', strtotime(date("Y")."-".date("m")."-".date("d")));
        }
        $count = M("runermoney_info a")->join("left join zz_member b on a.ruid=b.id")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("runermoney_info a")->join("left join zz_member b on a.ruid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field("a.*,b.realname,b.username,b.phone")->select();
        
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $runer = M('store_member a')->join("left join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid))->select();
        $this->assign("runer", $runer);
        $totalyes_money=M('runermoney_info')->where(array('ruid'=>array("in",$ruids)))->sum("yes_money");
        $this->assign("totalyes_money", $totalyes_money);
        $totalno_money=M('runermoney_info')->where(array('ruid'=>array("in",$ruids)))->sum("no_money");
        $this->assign("totalno_money", $totalno_money);
        $this->display();
    }
    public function runerinfo() {
        $id=I('id');
        $data=M('runermoney_info')->where(array('id'=>$id))->find();

        $where=array();
        $where['c.status']=5;
        $where['a.ruid']=$data['ruid'];
        $where['a.paystyle']=2;

        $starttime=$data['date'];
        $endtime=strtotime("+1 days -1 hours",$starttime);
        
        $where['c.donetime'] = array(array('EGT', $starttime), array('ELT', $endtime));

        $count = M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->count();
        $page = new \Think\Page($count, 5);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order a")
            ->join("left join zz_store b on a.storeid=b.id")
            ->join("left join zz_order_time c on a.orderid=c.orderid")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order(array("a.id" => "desc"))
            ->field("a.*,b.title as storename,c.inputtime,c.donetime")
            ->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->assign("totalnum",$count);
        $totalmoney=M("order a")->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->sum("money");
        $this->assign("totalmoney",$totalmoney);

        $this->display();
    }
    public function runerdeal(){
        if (IS_POST) {
            $money=$_POST['money'];
            $id=$_POST['id'];
            $data=M('runermoney_info')->where(array('id'=>$id))->find();
            if($data['status']==2){
                $this->error("已完成结算！");
            }
            if($money==$data['no_money']){
                $status=2;
            }else{
                $status=1;
            }
            $id=M('runermoney_info')->where(array('id'=>$id))->save(array(
                'yes_money'=>$data['yes_money']+$money,
                'no_money'=>$data['no_money']-$money,
                'status'=>$status,
                'last_paytime' => time()
            ));
            if($id){
                $this->success("结算成功！");
            }else{
                $this->error("结算失败！");
            }
        } else {
            $data=M("runermoney_info a")->join("left join zz_member b on a.ruid=b.id")->where(array('a.id'=>$_GET['id']))->field("a.*,b.nickname,b.username,b.phone")->find();
            $this->assign("data",$data);
            $this->display();
        }
    }
    public function runerinvite() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
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
            
        }
        $where['_string']="b.id <> ''";
        $count = M("invite a")->join("left join zz_member b on a.tuid=b.id")->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M("invite a")->join("left join zz_member b on a.tuid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("b.id" => "desc"))->select();
        foreach ($data as $key => $value)
        {
        	$data[$key]['tuijianusername']=M('member')->where(array('id'=>$value['uid']))->getField("username");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $runer = M('store_member a')->join("left join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid))->select();
        $this->assign("runer", $runer);
        $this->display();
    }
}
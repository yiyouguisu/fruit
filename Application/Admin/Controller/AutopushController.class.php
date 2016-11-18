<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class AutopushController extends CommonController {
    public function _initialize() {
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
        
        $time=time();
        $this->starttime=mktime(0,0,0,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
        $this->endtime=mktime(23,59,59,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
    }

    public function today_weblog() {
        $endSig = "\n\n";
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $time = date('r');
        $response = array(
            'users'=>$this->users(),
            'simpleorder' => $this->simpleorder(),
            'bookorder' => $this->bookorder(),
            'companyorder' => $this->companyorder(),
            'speedorder' => $this->speedorder(),
            'weighorder' => $this->weighorder(),
            'company' => $this->company(),
            'bill' => $this->bill()
        );
        $res = json_encode($response);
        echo "data:{$res}{$endSig}";
        flush();
    }
    protected function users() {
        $where['reg_time']=array(array('EGT', $starttime), array('ELT', $endtime));
        $where['group_id']=1;
        $data=M('member')->where($where)->count();
        return intval($data);
    }
    protected function simpleorder() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['ordertype']=1;
        $count=M('order')->where($where)->count();
        $money=M('order')->where($where)->sum("total");
        $data = array(
          'count' => intval($count),
          'money' => sprintf("%.2f",floatval($money))
        );
        return $data;
    }
    protected function bookorder() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['ordertype']=2;
        $count=M('order')->where($where)->count();
        $money=M('order')->where($where)->sum("total");
        $data = array(
          'count' => intval($count),
          'money' => sprintf("%.2f",floatval($money))
        );
        return $data;
    }
    protected function companyorder() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['ordertype']=3;
        $count=M('order')->where($where)->count();
        $money=M('order')->where($where)->sum("total");
        $data = array(
          'count' => intval($count),
          'money' => sprintf("%.2f",floatval($money))
        );
        return $data;
    }
    protected function speedorder() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['isspeed']=1;
        $count=M('order')->where($where)->count();
        $money=M('order')->where($where)->sum("total");
        $data = array(
          'count' => intval($count),
          'money' => sprintf("%.2f",floatval($money))
        );
        return $data;
    }
    protected function weighorder() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['ordertype']=1;
        $where['iscontainsweigh']=1;
        $count=M('order')->where($where)->count();
        $money=M('order')->where($where)->sum("total");
        $data = array(
          'count' => intval($count),
          'money' => sprintf("%.2f",floatval($money))
        );
        return $data;
    }
    protected function company() {
        $where['inputtime']=array(array('EGT', $this->starttime), array('ELT', $this->endtime));
        $where['status']=1;
        $data=M("cooperation")->where($where)->count();
        return intval($data);
    }
    protected function bill() {
        $where['a.status']=5;
        $where['b.ordertype']=array("neq",3);
        $where['b.money']=array('gt',0);
        $where['a.bill_apply_status']=array("gt",0);
        $where['c.donetime']=array(array('EGT', $starttime), array('ELT', $endtime));
        $data = M("order_time a")
                ->join("left join zz_order b on a.orderid=b.orderid")
                ->where($where)
                ->count();
        return intval($data);
    }
}
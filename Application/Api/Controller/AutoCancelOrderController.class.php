<?php

namespace Api\Controller;

use Api\Common\CommonController;

class AutoCancelOrderController extends CommonController {
	var $runTime_1;
	protected $Config, $ConfigData;

    public function _initialize(){
    	$this->runTime_1 = microtime(true);
        parent::_initialize();
        set_time_limit(0);
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
    }
	
	public function cancelorder(){
        $strOut="";
        $where=array('c.pay_status'=>0);
        $field=array('a.orderid,a.uid');
        $data=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")
                          ->where($where)
                          ->field($field)
                          ->select();
        foreach ($data as $value)
        {
        	$id=M('order_time')->where(array('orderid'=>$value['orderid']))->save(array(
                'status'=>3,
                'cancel_status'=>1,
                'cancel_time'=>time()
            ));
            if($id){
                $c="您好！系统在".date("Y年m月d日 H时i分s秒") ."成功取消了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$value['uid'],
                    'title'=>"系统取消订单",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$value['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"系统取消订单成功",
                    'value'=>$value['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $strOut.="系统自动取消订单处理成功\r\n";
            }else{
                $strOut.="系统自动取消订单处理失败\r\n";
            }
        }
        $log = array(
	        "task_info" => $strOut,
	        "add_time" => date("Y-m-d H:i:s"),
	        "run_time" => (microtime(true)-$this->runTime_1)
	    );
	    M("task_log")->add($log);
    }
}
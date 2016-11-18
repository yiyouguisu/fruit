<?php

namespace Api\Controller;

use Api\Common\CommonController;

class AutoBookorderNoticeController extends CommonController {
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
	//预购订单只有一个预购商品
	public function bookordernotice(){
        $strOut="";
        $sql="SELECT orderid,uid FROM(SELECT a.orderid,a.uid, (a.yes_money_total - a.yes_money) AS wait_yes_money FROM zz_order a LEFT JOIN zz_order_productinfo b ON a.orderid = b.orderid LEFT JOIN zz_order_time c ON a.orderid = c.orderid LEFT JOIN zz_product d ON b.pid = d.id WHERE (b.isnotice=0) AND (c.pay_status = 0) AND (a.ordertype = 2)AND (d.selltime > " . strtotime("-5 minute") . ")AND(d.selltime <= " . time() . ")) AS A WHERE(wait_yes_money <= 0) LIMIT 0,30";
        $data=M()->query($sql);
        foreach ($data as $value)
        {
        	$user=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$value['orderid']))->field("b.phone,b.username,b.deviceToken")->find();
            $smsdata=json_encode(array('phone'=>$user['phone'],'datas'=>array($value['orderid'],$user['username']),'templateid'=>"64804"));
            $CCPRest = A("Api/CCPRest");
            $CCPRest->sendsmsapi($smsdata);
            $strOut.="预购订单".$value['orderid']."到货提醒成功\r\n";

            $message_type='bookordernotice';
            $push['title']="预购订单到货提醒";
            $push['description']="[蔬果先生]尊敬的客户，你的预购订单(".$value['orderid'].")已经到货，请您尽快完成订单余款支付，我们会在您确认完成订单后尽快安排配送，感谢您的配合。";
            $push['content']="[蔬果先生]尊敬的客户，你的预购订单(".$value['orderid'].")已经到货，请您尽快完成订单余款支付，我们会在您确认完成订单后尽快安排配送，感谢您的配合。";
            $push['isadmin']=1;
            $push['inputtime']=time();
            $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
            $mid = M("Push")->add($push);
            if ($mid) {
                $registration_id=$user["deviceToken"];
                $extras = array("orderid"=>$value['orderid'],'message_type'=>$message_type);
                if(!empty($registration_id)){
                    PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                }
                M('order_productinfo')->where(array('orderid'=>$value['orderid']))->setField("isnotice",1);
            }
            M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"商品已到了",
                    'value'=>$value['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
        }
        $log = array(
	        "task_info" => $strOut,
	        "add_time" => date("Y-m-d H:i:s"),
	        "run_time" => (microtime(true)-$this->runTime_1)
	    );
	    M("task_log")->add($log);
    }
}
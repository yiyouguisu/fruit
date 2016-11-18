<?php

namespace Web\Controller;

use Web\Common\CommonController;

class PayController extends CommonController {
    
	public function index(){
        $charge = $_COOKIE['web_ping_charge'];
        $orderid = $_COOKIE['web_temporderid'];
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        if($order['ordertype']==2){
            $yes_money_total=$order['yes_money_total'];
            if($yes_money_total==0.00){
                $productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
                foreach ($productinfo as $value) {
                    # code...
                    $product=M('product')->where(array('id'=>$value['pid']))->find();
                    $yes_money_total+=$value['nums']*$product['advanceprice'];
                }
            }
            $isallstatus=0;
            if($order['yes_money']>=$yes_money_total){
                $isallstatus=1;
            }else{
                if($order['total']<=$yes_money_total){
                    $isallstatus=1;
                }else{
                    $isallstatus=0;
                }
            }
            $this->assign("isallstatus",$isallstatus);
        }
        $this->assign("ordertype",$order['ordertype']);
        $this->assign("couponsid",$order['couponsid']);


        $this->assign("orderid",$orderid);
        $this->assign("charge",$charge);
        //dump($charge);die;
        $this->display();
    }

    public function recharge(){
        $charge = $_COOKIE['web_ping_charge'];
        $this->assign("charge",$charge);
        $this->display();
    }
}
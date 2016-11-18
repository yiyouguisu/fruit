<?php

namespace Web\Controller;

use Web\Common\CommonController;

class InvoicController extends CommonController {
	
    public function index(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
           $isapply= 0;
           $where['a.uid']=$uid;
           $where['b.status']=5;
           $where['b.delivery_status']=4;
           if($isapply==0){
                $where['b.bill_apply_status']=0;
           }else{
                $where['b.bill_apply_status']=1;
           }
            $where['a.inputtime']=array(array('elt',time()),array('egt',strtotime("-7 days")));
            $data=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->order(array('a.inputtime'=>'desc'))->field('a.orderid,a.money,a.total,a.inputtime')->page($p,$num)->select();
            $this->assign('list',$data);
            $this->display();
        }
    }
    
    public function isalready(){
    	$uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $isapply= 1;
            $where['a.uid']=$uid;
            $where['b.status']=5;
            $where['b.delivery_status']=4;
            if($isapply==0){
                $where['b.bill_apply_status']=0;
            }else{
                $where['b.bill_apply_status']=1;
            }
            $where['a.inputtime']=array(array('elt',time()),array('egt',strtotime("-7 days")));
            $data=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->order(array('a.inputtime'=>'desc'))->field('a.orderid,a.money,a.total,a.inputtime')->page($p,$num)->select();
            $this->assign('list',$data);
            $this->display();
        }
    }


    public function changeaddr(){
        //先获得默认的收货地址，且这里的收货地址一直让他显示默认地址，如果他选择了其他的地址的话，那么也将他设置为默认地址
    	$uid = session('uid');
    	$cachefile = "goodslist".$uid;
    	$cachefiletotal = "goodstotal".$uid;
    	$cachefileordertype = "ordertype".$uid;
    	/***收货地址***/
    	$addressid = I('get.id'); 
    	if (!empty($addressid)){
    		$address = M('address')->where(array('id'=>$addressid))->find();
    	}else{
    		$address = M('address')->where(array('uid'=>$uid,'isdefault'=>'1'))->find();
    	}
    	$area = explode(',',$address['area']);
		$address['province'] = D("area")->where("id=".$area[0])->getField('name');
	    $address['city'] = D("area")->where("id=".$area[1])->getField('name');
	    if (count($area)>2){
	        $address['areas'] = D("area")->where("id=".$area[2])->getField('name');
	    }
        //dump(S('orderids'));
    	$this->assign('address',$address);
        $this->display();
    }

    public function cache(){
        $orderid = $_REQUEST['orderid'];
        S('orderids',$orderid);
        $this->ajaxReturn("success");
    }

    public function billapply(){
        $orderid=trim(S('orderids'));
        $billtype = intval(trim($_POST['billtype']));
        $billaddressid = intval(trim($_POST['addressid']));
        $billtitle=trim($_POST['billtitle']);
        $uid = trim(session('uid'));
        
        $user=M('Member')->where(array('id'=>$uid))->find();
        $address=M('address')->where(array('id'=>$billaddressid))->find();
        if ($uid == ''||$orderid == '') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } elseif(empty($address)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Address is not exist!")));
        } else {
            if(is_array($orderid)){
                $where['orderid']=array('in',$orderid);
            }else{
                $where['orderid']=array('eq',$orderid);
            }
            $id=M('order')->where($where)->save(array(
                'billtype'=>$billtype,
                'billaddressid'=>$billaddressid,
                'billtitle'=>$billtitle
                ));
            if($id){
                M('order_time')->where($where)->save(array(
                    'bill_apply_status'=>1,
                    'bill_apply_time'=>time()
                    ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功申请了订单发票。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单发票申请",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                //exit(json_encode(array('code'=>200,'msg'=>"申请成功")));
                $this->show('<script>alert("申请成功！")</script>');
                $this->redirect('Web/Invoic/isalready');
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"申请失败")));
            }
        }
    }
}
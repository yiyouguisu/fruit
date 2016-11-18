<?php

namespace Web\Controller;

use Web\Common\CommonController;

class CouponsController extends CommonController {
    
	public function index(){
		$uid = session('uid');
        $change = I('get.id');
        $user = M('Member')->where(array('id'=>$uid))->find();
        $catids=M('coupons')->where(array('status'=>1))->getField("id",true);
        
        if(empty($change)){
            $data = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$uid,'a.status'=>0,'b.status'=>1,'b.validity_endtime'=>array('GT',time())))->field("b.id,b.thumb,b.storeid,b.catid,b.pid,b.title,b.price,b.validity_endtime,a.num,a.`status`,b.`range`")->order(array('a.status'=>'asc','a.inputtime'=>'desc'))->page($p,$num)->select();
            foreach ($data as $key=>$value){
                if(($data[$key]['validity_endtime'] - time()) < 0)
                {
                    $data[$key]['status'] = '1';
                }
                $data[$key]['enddate'] = date('Y-m-d',$data[$key]['validity_endtime']);
                $data[$key]['price'] = $data[$key]['price'];
                $data[$key]['storename'] = M('store')->where('id='.$data[$key]['storeid'])->getfield('title');
                $data[$key]['productname'] = M('product')->where('id='.$data[$key]['pid'])->getfield('title');
                $data[$key]['catname'] = M('category')->where('id='.$data[$key]['catid'])->getfield('catname');

            }
        }else{
            $money =I('get.money');
            $pid = I('get.pid');
            $storeid = I('get.storeid');
            
            $where = array();
            if (!empty($pid)){
                $where = array('a.uid'=>$uid,'a.status'=>0,'b.status'=>1,'b.validity_endtime'=>array('GT',time()),'b.range'=>array('ELT',$money),'b.pid'=>$pid);
            }else if(!empty($storeid)){
                $where = array('a.uid'=>$uid,'a.status'=>0,'b.status'=>1,'b.validity_endtime'=>array('GT',time()),'b.range'=>array('ELT',$money),'b.storeid'=>$storeid);
            }else{
                $where = array('a.uid'=>$uid,'a.status'=>0,'b.status'=>1,'b.validity_endtime'=>array('GT',time()),'b.range'=>array('ELT',$money));
            }
            $data = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where($where)->field("b.id,b.thumb,b.storeid,b.catid,b.pid,b.title,b.price,b.validity_endtime,a.num,a.`status`,b.`range`")->order(array('a.status'=>'asc','a.inputtime'=>'desc'))->page($p,$num)->select();
            foreach ($data as $key=>$value){
                if(($data[$key]['validity_endtime'] - time()) < 0)
                {
                    $data[$key]['status'] = '1';
                }
                $data[$key]['enddate'] = date('Y-m-d',$data[$key]['validity_endtime']);
                $data[$key]['price'] = $data[$key]['price'];
                $data[$key]['storename'] = M('store')->where('id='.$data[$key]['storeid'])->getfield('title');
                $data[$key]['productname'] = M('product')->where('id='.$data[$key]['pid'])->getfield('title');
                $data[$key]['catname'] = M('category')->where('id='.$data[$key]['catid'])->getfield('catname');
            }
        }
        //dump($data);
        //dump(M('coupons_order a')->_sql());
		$this->assign('list',$data);
		$this->display();
	}
}
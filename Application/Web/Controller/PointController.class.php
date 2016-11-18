<?php

namespace Web\Controller;

use Web\Common\CommonController;

class PointController extends CommonController {
    /*
    **我的积分
    */
    public function index(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $data = M("integrallog")->where(array('uid'=>$uid))->order(array('inputtime'=>"desc"))->limit(50)->select();
            foreach ($data as $key=>$value){
            	$data[$key]['thedate'] = Date('Y-m-d H:i',$data[$key]['inputtime']);
            }
            
            $this->assign('isusenum',$data[0]['useintegral']);
            $this->assign('list',$data);
            
            $today = mktime(0,0,0,$m,$d,$y);
            $data = M("integrallog")->where("uid = '".$uid."' and inputtime > '".$today."'")->find();
            if (empty($data['uid'])){
            	$data['isqiandao'] = "0";
            }else{
            	$data['isqiandao'] = "1";
            }
            $this->assign('qiandao',$data['isqiandao']);
            $this->display();  
        }
    }
    
}
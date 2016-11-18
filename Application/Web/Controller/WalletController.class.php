<?php

namespace Web\Controller;

use Web\Common\CommonController;

class WalletController extends CommonController {

    public function _initialize(){
    	parent::_initialize();
        Vendor("pingpp.init");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=D("Config")->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $this->ConfigData=$ConfigData;
    }
	
    public function index(){
    	$uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
	    	$data = M('account')->where('uid='.$uid)->find();
	    	$this->assign('total',$data['usemoney']);
	    	$data = M('account_log')->where('uid='.$uid)->order(array('id'=>'desc'))->select();
	    	foreach ($data as $key=>$value){
	    		$data[$key]['addtime'] = date('Y-m-d H:i:s',$data[$key]['addtime']);
	    	}
	    	$this->assign('list',$data);
	    	$this->display();
	    }
    }
    
    public function point(){
        $uid = session('uid');
    	$data = M('integral')->where('uid='.$uid)->find();
    	$this->assign('total',$data['useintegral']);
        $this->display();
    }

    public function recharge(){
        $uid = session('uid');
    	$data = M('account')->where('uid='.$uid)->find();
    	$this->assign('total',$data['usemoney']);
        $this->display();
    }
 
	public function rechargeon(){
		$uid = intval(trim(session('uid')));
		$money=floatval(trim($_REQUEST['money']));
        $paytype = '2';
		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		if($uid==''||$money==''||$paytype==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif($money<200){
			exit(json_encode(array('code'=>-200,'msg'=>"充值最小金额为200元")));
		}elseif(fmod($money,100)!=0){
			exit(json_encode(array('code'=>-200,'msg'=>"充值的递增金额必须为100的倍数")));
		}else{
			$paytypetext="";
            $paytypevalue="";
			$orderid="rc".date("YmdHis", time()) . rand(100, 999);
			switch ($paytype) {
				case '1':
					# code...
					$paytypetext="支付宝";
					$paytypevalue="alipay";
					$chanel='alipay';
					break;
				case '2':
					# code...
					$paytypetext="微信";
					$paytypevalue="weixin";
					$chanel='wx_pub';
					break;
			}
			$mid=M('recharge')->add(array(
					'uid'=>$uid,
					'title'=>"钱包充值--在线充值",
                    'orderid' => $orderid,
					'type'=>$paytypevalue,
					'money'=>$money,
					'channel'=>$chanel,
					'status'=>1,
					'addip'=>get_client_ip(),
					'addtime'=>time()
					));
            if($mid){
            	$pingpp=self::pingpp($orderid);
                exit($pingpp);
            }else{
            	exit(json_encode(array('code'=>-202,'msg'=>"充值失败")));
            }
		}
	}

    public function recharge_integral(){
		$uid = session('uid');
		$money=floatval(trim($_REQUEST['money']));
        
		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		$integral=M('integral')->where(array('uid'=>$uid))->getField("useintegral");
		if($uid==''||$money==''){
            
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
            
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif($integral<=$money*100){
			exit(json_encode(array('code'=>-200,'msg'=>"您的积分不足，至少需要100积分才能充值！")));
		}else{
			$account=M('account')->where(array('uid'=>$uid))->find();
			$mid=M('account')->where(array('uid'=>$uid))->save(array(
				'recharemoney'=>$account['recharemoney']+floatval($money),
				'total'=>$account['total']+floatval($money),
				'usemoney'=>$account['usemoney']+floatval($money),
				));
			if($mid){
				M('recharge')->add(array(
					'uid'=>$uid,
					'type'=>'integral',
					'money'=>$money,
					'status'=>1,
					'addip'=>get_client_ip(),
					'addtime'=>time()
					));
				M('account_log')->add(array(
					'uid'=>$uid,
					'type'=>'integral_recharge',
					'money'=>$money,
					'total'=>$account['total']+floatval($money),
					'usemoney'=>$account['usemoney']+floatval($money),
					'nousemoney'=>$account['nousemoney'],
					'status'=>1,
					'dcflag'=>1,
                    'remark'=>"使用积分充值".$money."元",
					'addip'=>get_client_ip(),
					'addtime'=>time()
					));
				self::update_integral($uid,$money*100,2,'使用积分充值'.$money.'元','integral_recharge');
				exit(json_encode(array('code'=>200,'msg'=>"充值成功")));     
			}else{
				M('recharge')->add(array(
					'uid'=>$uid,
					'type'=>'integral',
					'money'=>$money,
					'status'=>0,
					'addip'=>get_client_ip(),
					'addtime'=>time()
					));
				exit(json_encode(array('code'=>-202,'msg'=>"充值失败")));
			}
		}
    }

    /*
     **更新用户积分
     * uid 用户id
     * integral  操作积分
     * type 1 增 2减
     * content 积分变更说明
     */ 
	public static function update_integral($uid,$integral,$type,$content,$update_type){
		if($type==1){
			M('integral')->where(array('uid'=>$uid))->setInc("useintegral",intval($integral));
            M('integral')->where(array('uid'=>$uid))->setInc("totalintegral",intval($integral));
		}elseif($type==2){
			M('integral')->where(array('uid'=>$uid))->setDec("useintegral",intval($integral));
            M('integral')->where(array('uid'=>$uid))->setInc("payed",intval($integral));
		}
        
        M('integrallog')->add(array(
        'uid'=>$uid,
        'paytype'=>$type,
        'content'=>$content,
        'integral'=>$integral,
        'varname'=>$update_type,
        'useintegral'=>M('integral')->where(array('uid'=>$uid))->getField('useintegral'),
        'totalintegral'=>M('integral')->where(array('uid'=>$uid))->getField('totalintegral'),
        'inputtime'=>time()
        )); 
        self::addmessage($uid,$content,$content,$content,'system');
	}
    
	public static function addmessage($uid,$title,$description,$content,$message_type = 'system',$value=''){
		$mid=M('message')->add(array(
	  		'uid'=>0,
	  		'tuid'=>$uid,
	  		'varname'=>$message_type,
	  		'value'=>$value,
	  		'title'=>$title,
	  		'description'=>$description,
	  		'content'=>$content,
	  		'inputtime'=>time()
	  		));

        $registration_id=M('member')->where(array('id'=>array('eq',$uid)))->getField("deviceToken");
        $receiver = $registration_id;
        $extras = array("mid"=>$mid,'message_type'=>$message_type);
        if(!empty($receiver)){
            PushQueue($mid, $message_type,$receiver, $title, serialize($extras));
        }
	}

    

    public function pingpp($orderid){
        $order=M('recharge')->where(array('orderid'=>$orderid))->find();
        $orderid=$order['orderid'];
        $extra = array();
        $channel = "wx_pub";
		switch ($channel) {
		    case 'alipay_wap':
		        $extra = array(
		            'success_url' => 'http://www.yourdomain.com/success',
		            'cancel_url' => 'http://www.yourdomain.com/cancel'
		        );
		        break;
		    case 'upmp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'bfb_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'upacp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'wx_pub':
		        $extra = array(
		            'open_id' => $_COOKIE['web_user_openid']
		        );
		        break;
		    case 'wx_pub_qr':
		        $extra = array(
		            'product_id' => 'Productid'
		        );
		        break;
		}
        $money=$order['money'];
        //$money=0.01;
        $ping_config=array();
        foreach ($this->ConfigData as $r) {
            if($r['groupid'] == 5){
                $ping_config[$r['varname']] = $r['value'];
            }
        }
        
        \Pingpp\Pingpp::setApiKey($ping_config['pingKey']);
        try {
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => $order['title'],
                    "body"      => $order['title'],
                    "amount"    => $money*100,
                    "order_no"  => $orderid,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $order['channel'],
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => $ping_config['pingAppid'])
                )
            );
            //$this->ajaxReturn($ch);
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,\Think\Log::INFO);
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            //return $ch;
            cookie("temporderid",$orderid);
            cookie("ping_charge",$ch);
            $this->ajaxReturn('success');
        }
        catch (\Pingpp\Error\Base $e) {
            
            $Status = $e->getHttpStatus();
            $body = $e->getHttpBody();
            $this->ajaxReturn('Status: ' . $Status ." body:".$body);
            return 'Status: ' . $Status ." body:".$body;
        }

    }
}
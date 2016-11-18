<?php

namespace Api\Controller;

use Api\Common\CommonController;

class MemberController extends CommonController {
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
	/*
     * *会员注册
     */
    public function test() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
        $ret = CommonController::decrypt_des($ret['data']);
		$password = trim($ret['password']);
		$phone = trim($ret['phone']);
		if ($phone == '' || $password == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null")));
		} else {
		    $data['username'] = $phone;
		    $data['thirdname'] = $phone;
		    $data['password'] = $password;
		    $data['phone'] = $phone;
		    $data['phone_status'] = 1;
		    if ($data) {
				exit(json_encode(array('code' => 200, 'msg' => "success", 'data' => CommonController::encrypt_des($data))));
		    } else {
				exit(json_encode(array('code' => -200, 'msg' => "error")));
		    }
		}
    }
	/*
     * *会员注册
     */
    public function reg() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$telverify = trim($ret['telverify']);
		$password = trim($ret['password']);
		$phone = trim($ret['phone']);
        $deviceToken=trim($ret['deviceToken']);
        $invite_code = trim($ret['invite_code']);
		$verifyset = M('verify')->where('phone=' . $phone)->find();
		$time = time() - $verifyset['expiretime'];
		if ($time > 0) {
		    $verify = "";
		    M('verify')->where('phone=' . $phone)->save(array(
			'status' => 1
		    ));
		} else {
		    $verify = $verifyset['verify'];
		    M('verify')->where('phone=' . $phone)->save(array(
			'status' => 1
		    ));
		}
        $tuijianuser=M('member')->where(array('tuijiancode'=>$invite_code))->find();
		if ($phone == '' || $password == '' || $telverify == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (strtolower($telverify) != strtolower($verify)) {
		    exit(json_encode(array('code' => -200, 'msg' => "验证码错误")));
		} elseif (!isMobile($phone)) {
		    exit(json_encode(array('code' => -200, 'msg' => "手机号码格式错误")));
		} elseif (!check_phone($phone)) {
		    exit(json_encode(array('code' => -200, 'msg' => "手机号已被注册")));
		} elseif (!$tuijianuser&&!empty($invite_code)) {
		    exit(json_encode(array('code' => -200, 'msg' => "推荐用户不存在")));
		} else {
		    $data['username'] = $phone;
		    $data['password'] = $password;
		    $data['phone'] = $phone;
		    $data['phone_status'] = 1;
		    $data['group_id'] = 1;
		    $data['head']="/default_head.png";
            if($tuijianuser&&!empty($invite_code)){
                $data['groupid_id'] = $tuijianuser['id'];
            }
		    $id = D("Member")->addUser($data);
		    if ($id) {
				$dataset['uid'] = $id;
				$dataset['username'] = $phone;
                if(!empty($deviceToken)){
                    M('member')->where(array('deviceToken'=>$deviceToken))->setField('deviceToken','');
                    M('member')->where(array('id'=>$id))->setField('deviceToken',$deviceToken);
                }
                if($tuijianuser&&!empty($invite_code)){
                    for ($i=0; $i < 3; $i++) { 
                        # code...
                        M("coupons_order")->add(array(
                            'catid'=>6,
                            'uid'=>$id,
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                    }
                    $url=C("WEB_URL")."/index.php/Web/Member/invitecode/uid/".$id.".html";
                    $message_type='onceregnotice';
                    $push['title']="会员首次注册提醒";
                    $push['description']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['content']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['isadmin']=1;
                    $push['inputtime']=time();
                    $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                    $mid = M("Push")->add($push);
                    if ($mid) {
                        $extras = array("shareurl"=>$url,'message_type'=>$message_type);
                        if(!empty($deviceToken)){
                            PushQueue($mid,$message_type,$deviceToken, $push['title'],$push['description'], serialize($extras),1);
                        }
                    }
                    M('invite')->add(array(
                        'uid'=>$tuijianuser['id'],
                        'tuid'=>$id,
                        'tuijiancode'=>$invite_code,
                        'status'=>2,
                        'inputtime'=>time()
                        ));
                    if($tuijianuser['group_id']==1){
                        $cid=M("coupons_order")->add(array(
                            'catid'=>7,
                            'uid'=>$tuijianuser['id'],
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                        $coupons=M("Coupons")->where(array("id" => 7))->find();
                        $message_type='sendcouponnotice';
                        $push['title']="赠送优惠券提醒";
                        $push['description']="亲!蔬果先生为您奉上的总额为".$coupons['price']."元的优惠券到账了,截止有效期:".date("Y年m月d日",$coupons['validity_endtime']).",请尽快使用,千万别错过哦!";
                        $push['content']="亲!蔬果先生为您奉上的总额为".$coupons['price']."元的优惠券到账了,截止有效期:".date("Y年m月d日",$coupons['validity_endtime']).",请尽快使用,千万别错过哦!";
                        $push['isadmin']=1;
                        $push['inputtime']=time();
                        $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                        $mid = M("Push")->add($push);
                        if ($mid) {
                            $extras = array("couponsid"=>$cid,'message_type'=>$message_type);
                            if(!empty($tuijianuser['deviceToken'])){
                                PushQueue($mid,$message_type,$tuijianuser['deviceToken'], $push['title'],$push['description'], serialize($extras),1);
                            }
                        }
                    }
                }else{
                    for ($i=0; $i < 3; $i++) { 
                        # code...
                        $cid=M("coupons_order")->add(array(
                            'catid'=>6,
                            'uid'=>$id,
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                    }
                    $url=C("WEB_URL")."/index.php/Web/Member/invitecode/uid/".$id.".html";
                    $message_type='onceregnotice';
                    $push['title']="会员首次注册提醒";
                    $push['description']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['content']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['isadmin']=1;
                    $push['inputtime']=time();
                    $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                    $mid = M("Push")->add($push);
                    if ($mid) {
                        $extras = array("shareurl"=>$url,'message_type'=>$message_type);
                        if(!empty($deviceToken)){
                            PushQueue($mid,$message_type,$deviceToken, $push['title'],$push['description'], serialize($extras),1);
                        }
                    }

                }
                
				exit(json_encode(array('code' => 200, 'msg' => "注册成功", 'data' => $dataset)));
		    } else {
				exit(json_encode(array('code' => -200, 'msg' => "注册失败")));
		    }
		}
    }

    /*
     * *会员登录
     */

    public function login() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$username = trim($ret['username']);
		$password = trim($ret['password']);
        $deviceToken=trim($ret['deviceToken']);
		$group_id = intval(trim($ret['group_id']));

		$user = $this->loginAdmin($username, $password);
		if ($username == '' || $password == '' || $group_id == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (!$user) {
		    M("userlog")->add(array(
				"username" => $username,
				"logintime" => time(),
				"loginip" => get_client_ip(),
				"status" => 0,
				"password" => "***" . substr($password, 3, 4) . "***",
				"info" => "登录失败"
		    ));
		    exit(json_encode(array('code' => -200, 'msg' => "登录失败")));
		} elseif ($user['status']==0) {
		    exit(json_encode(array('code' => -200, 'msg' => "账户已被禁用")));
		} elseif ($user['group_id']!=$group_id) {
		    exit(json_encode(array('code' => -200, 'msg' => "账户角色错误")));
		} else {
		    M("userlog")->add(array(
				"username" => $username,
				"logintime" => time(),
				"loginip" => get_client_ip(),
				"status" => 1,
				"password" => "***" . substr($password, 3, 4) . "***",
				"info" => "登录成功"
		    ));
            if(!empty($deviceToken)&&$deviceToken!=$user['deviceToken']){
	        	M('member')->where(array('deviceToken'=>$deviceToken))->setField('deviceToken','');
	        	M('member')->where(array('id'=>$user['id']))->setField('deviceToken',$deviceToken);
	        }
		    //根据用户的type显示不同的结果集
		    $dataset = array();
		    $dataset['uid'] = $user['id'];
		    switch ($group_id) {
				case 1:
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
				    $dataset['phone'] = $user['phone'];
                    $dataset['sex'] = $user['sex'];
				    $dataset['level'] = getlevel($user['id']);
				    $dataset['sex'] = $user['sex'];
				    $dataset['birthday'] = $user['birthday'];
				    $dataset['preference'] = $user['preference'];
				    $dataset['companyid'] = $user['companyid'];
				    $company=M('company')->where(array('id'=>$user['companyid']))->getField("title");
				    $dataset['company'] = !empty($company)?$company:"";
				    $integral=M('integral')->where(array('uid'=>$user['id']))->getField("useintegral");
				    $usemoney=M('account')->where(array('uid'=>$user['id']))->getField("usemoney");
                    $catids=M('coupons')->where(array('status'=>1))->getField("id",true);
				    $coupons = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$user['id'],'status'=>0,'b.validity_endtime'=>array('gt',time()),'b.id'=>array('in',$catids)))->count();
				    $dataset['integral'] = !empty($integral)?$integral:0;
				    $dataset['usemoney'] = !empty($usemoney)?$usemoney:0.00;
				    $dataset['coupons'] = !empty($coupons)?$coupons:0;
				    $messagenum=M("message")->where(array('tuid'=>$user['id'],'varname'=>array("in",'system,hot'),'isdel'=>'0','status'=>0))->count();
				    $dataset['messagenum'] = !empty($messagenum)?$messagenum:0;
				    break;
				case 2:
				    $dataset['workstatus'] = $user['workstatus'];
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
                    $dataset['isleader'] = $user['isleader'];
                    $dataset['tuijiancode'] = $user['tuijiancode'];
				    $totaldistance=M('runerposition')->where(array('uid'=>$user['id']))->getField("totaldistance");
				    $dataset['totaldistance'] = !empty($totaldistance)?sprintf("%.2f",$totaldistance):0.00;
				    $totalorder=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$user['id'],'b.delivery_status'=>4))->count('a.id');
				    $dataset['totalorder'] = !empty($totalorder)?$totalorder:0;
				    $totalinvite=M('member')->where(array('groupid_id'=>$user['id']))->count();
				    $dataset['totalinvite'] = !empty($totalinvite)?$totalinvite:0;
				    //$waitmoney=M('runermoney_info')->where(array('ruid'=>$user['id'],'status'=>array("neq",2),'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->getField("no_money");
				    $waitmoney=M('runermoney_info')->where(array('ruid'=>$user['id'],'status'=>array("neq",2)))->sum("no_money");
				    $dataset['waitmoney'] = !empty($waitmoney)?$waitmoney:0.00;
                    $wait_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$user['id']))->getField("no_money");
				    $dataset['wait_commissionmoney'] = !empty($wait_commissionmoney)?$wait_commissionmoney:0.00;
                    $yes_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$user['id']))->getField("yes_money");
				    $dataset['yes_commissionmoney'] = !empty($yes_commissionmoney)?$yes_commissionmoney:0.00;
				    $dataset['attitudestar']=getattitudestar($user['id']);
				    $dataset['speedstar']=getspeedstar($user['id']);
				    break;
			}
		    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' =>$dataset)));
		}
    }
    /*
     * *会员登录-第三方登陆
     */

    public function applogin() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$openid = trim($ret['openid']);
		$site = trim($ret['site']);
		$nickname = trim($ret['nickname']);
		$head = trim($ret['head']);
        $deviceToken=trim($ret['deviceToken']);

    	$bind = M('oauth')->field('id,bind_uid,logintimes')->where(array('openid' => $openid, 'site' => $site))->find();
		$user = M('member')->where(array('id' => $bind['bind_uid']))->find();
		if ($openid == '' || $site == '' || $nickname == '' || $head == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (!$bind) {
		    $data['username'] = $openid;
		    $data['password'] = "123456";
		    $data['nickname'] = $nickname;
		    $data['head'] = $head;
            if(!empty($deviceToken)){
	        	M('member')->where(array('deviceToken'=>$deviceToken))->setField('deviceToken','');
                $data['deviceToken'] = $deviceToken;
	        }
		    $id = D("Member")->addUser($data);
		    $map['bind_uid'] = $id;
		    $map['is_bind'] = 1;
		    $map['nickname'] = $nickname;
		    $map['openid'] = $openid;
		    $map['site'] = $site;
		    $map['logintimes'] = 1;
		    $map['inputtime'] = time();
		    $map['logintime'] = time();
		    M('oauth')->add($map);

		    $dataset = array();
		    $dataset['uid'] = $id;
		    $dataset['username'] = $openid;
		    $dataset['head'] = $head;
		    $dataset['nickname'] = $nickname;
		    $dataset['phone'] = "";
		    $dataset['level'] = 0;
		    $dataset['sex'] = 0;
		    $dataset['birthday'] = "";
		    $dataset['preference'] = "";
		    $dataset['companyid'] = "";
		    $dataset['company'] = "";
		    $dataset['integral'] = 0;
		    $dataset['usemoney'] = "0.00";
		    $dataset['coupons'] = 0;
		    
		    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' =>$dataset)));
		} else {
			if(!$user){
				$data['username'] = $openid;
			    $data['password'] = "123456";
			    $data['nickname'] = $nickname;
			    $data['head'] = $head;
	            if(!empty($deviceToken)){
		        	M('member')->where(array('deviceToken'=>$deviceToken))->setField('deviceToken','');
	                $data['deviceToken'] = $deviceToken;
		        }
			    $id = D("Member")->addUser($data);
			    M('oauth')->where(array('openid'=>$openid,'site'=>$site))->delete();
			    $map['bind_uid'] = $id;
			    $map['is_bind'] = 1;
			    $map['nickname'] = $nickname;
			    $map['openid'] = $openid;
			    $map['site'] = $site;
			    $map['logintimes'] = 1;
			    $map['logintime'] = time();
			    M('oauth')->where(array('id'=>$bind['id']))->save($map);
				
			    $dataset = array();
			    $dataset['uid'] = $id;
			    $dataset['username'] = $openid;
			    $dataset['head'] = $head;
			    $dataset['nickname'] = $nickname;
			    $dataset['phone'] = "";
			    $dataset['level'] = 0;
			    $dataset['sex'] = 0;
			    $dataset['birthday'] = "";
			    $dataset['preference'] = "";
			    $dataset['companyid'] = "";
			    $dataset['company'] = "";
			    $dataset['integral'] = 0;
			    $dataset['usemoney'] = "0.00";
			    $dataset['coupons'] = 0;
			    
			    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' =>$dataset)));
			} else {
			    $save['id'] = $bind['id'];
			    $save['logintimes'] = ($bind['logintimes'] + 1);
			    $save['logintime'] = time();
			    M('oauth')->save($save);

	            if(!empty($deviceToken)){
		        	M('member')->where(array('deviceToken'=>$deviceToken))->setField('deviceToken','');
	                M('member')->where(array('id' => $bind['bind_uid']))->save(array(
			    	    'nickname' => $nickname,
			    	    'head' => $head,
	                    'deviceToken'=>$deviceToken
			    	    ));
		        }else{
	                M('member')->where(array('id' => $bind['bind_uid']))->save(array(
			    	    'nickname' => $nickname,
			    	    'head' => $head
			    	    ));
	            }
			    
			    $dataset = array();
			    $dataset['uid'] = $user['id'];
			    $dataset['username'] = $user['username'];
			    $dataset['head'] = $head;
			    $dataset['nickname'] = $nickname;
			    $dataset['phone'] = $user['phone'];
			    $dataset['level'] = getlevel($user['id']);
			    $dataset['sex'] = $user['sex'];
			    $dataset['birthday'] = $user['birthday'];
			    $dataset['preference'] = $user['preference'];
			    $dataset['companyid'] = $user['companyid'];
			    $company=M('company')->where(array('id'=>$user['companyid']))->getField("title");
				$dataset['company'] = !empty($company)?$company:"";
			    $integral=M('integral')->where(array('uid'=>$user['id']))->getField("useintegral");
			    $usemoney=M('account')->where(array('uid'=>$user['id']))->getField("usemoney");
			    $catids=M('coupons')->where(array('status'=>1))->getField("id",true);
				$coupons = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$user['id'],'status'=>0,'b.validity_endtime'=>array('gt',time()),'b.id'=>array('in',$catids)))->count();
				$dataset['integral'] = !empty($integral)?$integral:0;
			    $dataset['usemoney'] = !empty($usemoney)?$usemoney:0.00;
			    $dataset['coupons'] = !empty($coupons)?$coupons:0;
			    $messagenum=M("message")->where(array('tuid'=>$user['id'],'varname'=>array("in",'system,hot,hotproduct'),'isdel'=>'0','status'=>0))->count();
	            $dataset['messagenum'] = !empty($messagenum)?$messagenum:0;
	            
			    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' => $dataset)));
			}
		}

    }

    /**
     * 登陆
     * @param string|int $identifier 用户ID,或者用户名
     * @param string $password 用户密码，不能为空
     * @param int $autotype 是否记住用户自动登录
     * @return array|bool 成功返回true，否则返回false
     */
    public function loginAdmin($identifier, $password, $autotype = 0) {
		if (empty($identifier) || empty($password)) {
		    return false;
		}
		$user = D("member")->getLocalAdminUser($identifier, $password);
		if (!$user) {
		    //$this->recordLoginAdmin($identifier, $password, 0, "帐号密码错误");
		    return false;
		}
		//判断帐号状态
		if ($user['status'] == 0) {
		    //记录登陆日志
		    // $this->recordLoginAdmin($identifier, $password, 0, "帐号被禁止");
		    return false;
		}
		//设置用户名
		M("member")->where(array("id" => $user['id']))->save(array(
		    "lastlogin_time" => time(),
		    "login_num" => $user["login_num"] + 1,
		    "lastlogin_ip" => get_client_ip()
		));
		return $user;
    }
    /*
     **获取会员的基本信息
     */
	public function ucenter(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();

		if($uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$dataset['uid'] = $user['id'];
		    switch ($user['group_id']) {
				case 1:
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
				    $dataset['phone'] = $user['phone'];
				    $dataset['level'] = getlevel($user['id']);
				    $dataset['sex'] = $user['sex'];
				    $dataset['birthday'] = $user['birthday'];
				    $dataset['preference'] = $user['preference'];
				    $dataset['companyid'] = $user['companyid'];
				    $company=M('company')->where(array('id'=>$user['companyid']))->getField("title");
				    $dataset['company'] = !empty($company)?$company:"";
				    $integral=M('integral')->where(array('uid'=>$uid))->getField("useintegral");
				    $usemoney=M('account')->where(array('uid'=>$uid))->getField("usemoney");
				    $catids=M('coupons')->where(array('status'=>1))->getField("id",true);
				    $coupons = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$user['id'],'status'=>0,'b.validity_endtime'=>array('gt',time()),'b.id'=>array('in',$catids)))->count();
				    $dataset['integral'] = !empty($integral)?$integral:0;
				    $dataset['usemoney'] = !empty($usemoney)?$usemoney:0.00;
				    $dataset['coupons'] = !empty($coupons)?$coupons:0;
				    $messagenum=M("message")->where(array('tuid'=>$user['id'],'varname'=>array("in",'system,hot,hotproduct'),'isdel'=>'0','status'=>0))->count();
				    $dataset['messagenum'] = !empty($messagenum)?$messagenum:0;
				    break;
				case 2:
				    $dataset['workstatus'] = $user['workstatus'];
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
                    $dataset['phone'] = $user['phone'];
                    $dataset['sex'] = $user['sex'];
				    $dataset['nickname'] = $user['nickname'];
                    $dataset['isleader'] = $user['isleader'];
                    $dataset['tuijiancode'] = $user['tuijiancode'];
				    $totaldistance=M('runerposition')->where(array('uid'=>$uid))->getField("totaldistance");
				    $dataset['totaldistance'] = !empty($totaldistance)?sprintf("%.2f",$totaldistance):0.00;
				    $totalorder=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$uid,'b.delivery_status'=>4))->count('a.id');
				    $dataset['totalorder'] = !empty($totalorder)?$totalorder:0;
				    $totalinvite=M('member')->where(array('groupid_id'=>$uid))->count();
				    $dataset['totalinvite'] = !empty($totalinvite)?$totalinvite:0;
				    $waitmoney=M('runermoney_info')->where(array('ruid'=>$user['id'],'status'=>array("neq",2),'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->getField("no_money");
				    //$waitmoney=M('runermoney_info')->where(array('ruid'=>$user['id'],'status'=>array("neq",2),'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->getField("no_money");
				    $waitmoney=M('runermoney_info')->where(array('ruid'=>$user['id'],'status'=>array("neq",2)))->sum("no_money");
				    $dataset['waitmoney'] = !empty($waitmoney)?$waitmoney:0.00;
                    $wait_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$uid))->getField("no_money");
				    $dataset['wait_commissionmoney'] = !empty($wait_commissionmoney)?$wait_commissionmoney:0.00;
                    $yes_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$uid))->getField("yes_money");
				    $dataset['yes_commissionmoney'] = !empty($yes_commissionmoney)?$yes_commissionmoney:0.00;
                    $dataset['attitudestar']=getattitudestar($uid);
				    $dataset['speedstar']=getspeedstar($uid);
				    break;
			}
			exit(json_encode(array('code' => 200, 'msg' => "获取数据成功", 'data' =>$dataset)));
		}
	}
	/*
     **会员完善资料
     */
	public function change_info(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$filed=trim($ret['filed']);
		$value=trim($ret['value']);

		$where['id']=$uid;
		$user=M('Member')->where($where)->field('id')->find();
		if($uid==''||$filed==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$id=M('member')->where(array('id'=>$uid))->setField($filed,$value);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"修改成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"修改失败")));
			}
		}
	}
	/*
     **会员完善资料(一次性提交)
     */
	public function change_info_once(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$updatedata=$ret['updatedata'];

		$where['id']=$uid;
		$user=M('Member')->where($where)->field('id')->find();
		if($uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($updatedata)){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$id=M('member')->where(array('id'=>$uid))->save($updatedata);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"修改成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"修改失败")));
			}
		}
	}
	
	/*
     **设置密码
     */
	public function setpassword(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$telverify=trim($ret['telverify']);
		$new_password=trim($ret['new_password']);
		$phone=trim($ret['phone']);

		$where['phone']=$phone;
		$user=M('Member')->where($where)->find();

		$verifyset=M('verify')->where('phone=' . $phone)->find();
		$time=time()-$verifyset['expiretime'];
		if($time>0){
			$verify="";
			M('verify')->where('phone=' . $phone)->save(array(
				'status'=>1
			));
		}else{
			$verify=$verifyset['verify'];
			M('verify')->where('phone=' . $phone)->save(array(
				'status'=>1
			));
		}
		if($phone==''||$new_password==''||$telverify==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(strtolower($telverify)!=strtolower($verify)){
            exit(json_encode(array('code'=>-200,'msg'=>"验证码错误")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$verify = CommonController::genRandomString(6);
            $data['verify'] = $verify;
            $data['password'] = D("Member")->encryption($user['username'], $new_password, $verify);
			$id=M('Member')->where("id=" . $user['id'])->save($data);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"重置密码成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"重置密码失败")));
			}
		}
	}
    
	/*
     **修改密码
     */
	public function changepassword(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$old_password=trim($ret['old_password']);
		$new_password=trim($ret['new_password']);
		$uid = intval(trim($ret['uid']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		
		$verify = CommonController::genRandomString(6);
        $old_password1 = D("Member")->encryption($user['username'], $old_password, $user['verify']);

		if($uid==''||$old_password==''||$new_password==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif($user['password']!=$old_password1){
			exit(json_encode(array('code'=>-200,'msg'=>"旧密码错误")));
		}else{
            $data['verify'] = $verify;
            $data['password'] = D("Member")->encryption($user['username'], $new_password, $verify);
			$id=M('Member')->where($where)->save($data);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"修改成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"修改失败")));
			}
		}
	}
    /*
     **忘记密码
     */
	public function findpassword(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$telverify=trim($ret['telverify']);
		$new_password=trim($ret['new_password']);
		$phone=trim($ret['phone']);

		$where['phone']=$phone;
		$user=M('Member')->where($where)->find();

		$verifyset=M('verify')->where('phone=' . $phone)->find();
		$time=time()-$verifyset['expiretime'];
		if($time>0){
			$verify="";
			M('verify')->where('phone=' . $phone)->save(array(
				'status'=>1
			));
		}else{
			$verify=$verifyset['verify'];
			M('verify')->where('phone=' . $phone)->save(array(
				'status'=>1
			));
		}
		if($phone==''||$new_password==''||$telverify==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(strtolower($telverify)!=strtolower($verify)){
            exit(json_encode(array('code'=>-200,'msg'=>"验证码错误")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$verify = CommonController::genRandomString(6);
            $data['verify'] = $verify;
            $data['password'] = D("Member")->encryption($user['username'], $new_password, $verify);
			$id=M('Member')->where("id=" . $user['id'])->save($data);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"重置密码成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"重置密码失败")));
			}
		}
	}
	/*
     **绑定手机号
     */
	public function bindphone(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid=intval(trim($ret['uid']));
		$password=trim($ret['password']);
		$phone=trim($ret['phone']);
		$invite_code = trim($ret['invite_code']);
		
        $tuijianuser=M('member')->where(array('tuijiancode'=>$invite_code))->find();

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();

		if($phone==''||$password==''||$uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif(!check_phone($phone)){
			exit(json_encode(array('code'=>-200,'msg'=>"手机号已经被使用!")));
		}elseif (!$tuijianuser&&!empty($invite_code)) {
		    exit(json_encode(array('code' => -200, 'msg' => "推荐用户不存在")));
		} else{
			$verify = CommonController::genRandomString(6);
            $data['verify'] = $verify;
            $data['password'] = D("Member")->encryption($user['username'], $password, $verify);
            $data['phone']=$phone;
            if($tuijianuser&&!empty($invite_code)){
                $data['groupid_id'] = $tuijianuser['id'];
            }
			$id=M('Member')->where("id=" . $user['id'])->save($data);
			if($id){
				if($tuijianuser&&!empty($invite_code)){
                    for ($i=0; $i < 3; $i++) { 
                        # code...
                        M("coupons_order")->add(array(
                            'catid'=>6,
                            'uid'=>$uid,
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                    }
                    $url=C("WEB_URL")."/index.php/Web/Member/invitecode/uid/".$uid.".html";
                    $message_type='onceregnotice';
                    $push['title']="会员首次注册提醒";
                    $push['description']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['content']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['isadmin']=1;
                    $push['inputtime']=time();
                    $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                    $mid = M("Push")->add($push);
                    if ($mid) {
                        $extras = array("shareurl"=>$url,'message_type'=>$message_type);
                        if(!empty($user['deviceToken'])){
                            PushQueue($mid,$message_type,$user['deviceToken'], $push['title'],$push['description'], serialize($extras),1);
                        }
                    }
                    M('invite')->add(array(
                        'uid'=>$tuijianuser['id'],
                        'tuid'=>$id,
                        'tuijiancode'=>$invite_code,
                        'status'=>2,
                        'inputtime'=>time()
                        ));
                    if($tuijianuser['group_id']==1){
                        $cid=M("coupons_order")->add(array(
                            'catid'=>7,
                            'uid'=>$tuijianuser['id'],
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                        $coupons=M("Coupons")->where(array("id" => 7))->find();
                        $message_type='sendcouponnotice';
                        $push['title']="赠送优惠券提醒";
                        $push['description']="亲!蔬果先生为您奉上的总额为".$coupons['price']."元的优惠券到账了,截止有效期:".date("Y年m月d日",$coupons['validity_endtime']).",请尽快使用,千万别错过哦!";
                        $push['content']="亲!蔬果先生为您奉上的总额为".$coupons['price']."元的优惠券到账了,截止有效期:".date("Y年m月d日",$coupons['validity_endtime']).",请尽快使用,千万别错过哦!";
                        $push['isadmin']=1;
                        $push['inputtime']=time();
                        $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                        $mid = M("Push")->add($push);
                        if ($mid) {
                            $extras = array("couponsid"=>$cid,'message_type'=>$message_type);
                            if(!empty($tuijianuser['deviceToken'])){
                                PushQueue($mid,$message_type,$tuijianuser['deviceToken'], $push['title'],$push['description'], serialize($extras),1);
                            }
                        }
                    }
                }else{
                    for ($i=0; $i < 3; $i++) { 
                        # code...
                        $cid=M("coupons_order")->add(array(
                            'catid'=>6,
                            'uid'=>$uid,
                            'num'=>1,
                            'status'=>0,
                            'inputtime'=>time(),
                            'updatetime'=>time()
                            ));
                    }
                    $url=C("WEB_URL")."/index.php/Web/Member/invitecode/uid/".$uid.".html";
                    $message_type='onceregnotice';
                    $push['title']="会员首次注册提醒";
                    $push['description']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['content']="亲，终于等到你了，欢迎来到蔬果先生APP，总额15元的优惠券已经放入口袋了；成功推荐好友，还可以再获得一张5元的现金券，不要犹豫....";
                    $push['isadmin']=1;
                    $push['inputtime']=time();
                    $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                    $mid = M("Push")->add($push);
                    if ($mid) {
                        $extras = array("shareurl"=>$url,'message_type'=>$message_type);
                        if(!empty($user['deviceToken'])){
                            PushQueue($mid,$message_type,$user['deviceToken'], $push['title'],$push['description'], serialize($extras),1);
                        }
                    }

                }
				exit(json_encode(array('code'=>200,'msg'=>"绑定成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"绑定失败")));
			}
		}
	}
	/*
     **我的地址
     */
	public function myaddress(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		if($uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			
			$other=M('address')->where(array('uid'=>$user['id'],'type'=>3))->field('id,area,areatext as areatextinfo,address,name,remark,tel,lat,lng')->select();
			foreach ($other as $key => $value) {
				# code...
				$other[$key]['areatext']=getarea($value['area']);
			}
			$company=M('address')->where(array('uid'=>$user['id'],'type'=>1))->field('id,area,areatext as areatextinfo,address,name,remark,tel,lat,lng')->select();
			foreach ($company as $key => $value) {
				# code...
				$company[$key]['areatext']=getarea($value['area']);
			}
			$home=M('address')->where(array('uid'=>$user['id'],'type'=>2))->field('id,area,areatext as areatextinfo,address,name,remark,tel,lat,lng')->select();
			foreach ($home as $key => $value) {
				# code...
				$home[$key]['areatext']=getarea($value['area']);
			}
			$default=M('address')->where(array('uid'=>$user['id'],'isdefault'=>1))->field('id,area,areatext as areatextinfo,address,name,remark,tel,lat,lng,type')->order(array('inputtime'=>'desc'))->find();
		    if(!empty($default)){
                $default['areatext']=getarea($default['area']);
            }
			$data=array(
				'other'=>$other,
				'company'=>$company,
				'home'=>$home,
				'default'=>$default
			);
            
			
			if($data){
				exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));     
			}else{
				exit(json_encode(array('code'=>-200,'msg'=>"获取数据失败")));
			}
		}
	}

	/*
     **新增地址
     */
	public function address_add(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$area=trim($ret['area']);
		$areatext=trim($ret['areatext']);
		$address=trim($ret['address']);
		$name=trim($ret['name']);
		$remark=trim($ret['remark']);
		$tel=trim($ret['tel']);
		$lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));
		$isdefault=intval(trim($ret['isdefault']));
		$type=intval(trim($ret['type']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		if($uid==''||$area==''||$address==''||$name==''||$tel==''||$lat==''||$lng==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$id=M('address')->add(array(
				'uid'=>$user['id'],
				'area'=>$area,
				'areatext'=>$areatext,
				'address'=>$address,
				'remark'=>$remark,
				'name'=>$name,
				'tel'=>$tel,
				'type'=>$type,
				'isdefault'=>$isdefault,
				'lat'=>$lat,
				'lng'=>$lng,
				'inputtime'=>time(),
				'updatetime'=>time()
			));

			if($id){
				if($isdefault==1){
					M('address')->where(array('id'=>array('neq'=>$id),'uid'=>$user['id']))->save(array('isdefault'=>0));
				}
				exit(json_encode(array('code'=>200,'msg'=>"添加成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"添加失败")));
			}
		}
	}
	/*
     **修改地址
     */
	public function address_modify(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$addressid=intval(trim($ret['addressid']));
		$uid = intval(trim($ret['uid']));
		$area=trim($ret['area']);
		$areatext=trim($ret['areatext']);
		$address=trim($ret['address']);
		$name=trim($ret['name']);
		$remark=trim($ret['remark']);
		$tel=trim($ret['tel']);
		$lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));
		$isdefault=intval(trim($ret['isdefault']));
		$type=intval(trim($ret['type']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		$addressarray=M('address')->where(array('id'=>$addressid))->find();
		if($addressid==''||$uid==''||$area==''||$address==''||$name==''||$tel==''||$lat==''||$lng==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif(!$addressarray){
			exit(json_encode(array('code'=>-200,'msg'=>"The Address is not exist!")));
		}else{
			$id=M('address')->where(array('id'=>$addressid,'uid'=>$user['id']))->save(array(
				'area'=>$area,
				'areatext'=>$areatext,
				'address'=>$address,
				'remark'=>$remark,
				'name'=>$name,
				'tel'=>$tel,
				'type'=>$type,
				'isdefault'=>$isdefault,
				'lat'=>$lat,
				'lng'=>$lng,
				'updatetime'=>time()
			));

			if($id){
				if($isdefault==1){
					M('address')->where(array('id'=>array('neq'=>$addressid),'uid'=>$user['id']))->save(array('isdefault'=>0));
				}
				exit(json_encode(array('code'=>200,'msg'=>"更新成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"更新失败")));
			}
		}
	}
	/*
     **删除地址
     */
	public function address_del(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$addressid=intval(trim($ret['addressid']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		$address=M('address')->where(array('id'=>$addressid))->find();
		if($uid==''||$addressid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif(!$address){
			exit(json_encode(array('code'=>-200,'msg'=>"The Address is not exist!")));
		}else{
			$select['uid']=$user['id'];
			$select['id']=$addressid;
			$id=M('address')->where($select)->delete();
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"删除成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
			}
		}
	}
	/*
     **我的优惠券列表页
     */
    public function myvouchers() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
		$num=intval(trim($ret['num']));
        $p=intval(trim($ret['p']));

		$user=M('Member')->where(array('id'=>$uid))->find();
		if ($uid == ''||$p == '' ||$num == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		} else{
            $catids=M('coupons')->where(array('status'=>1))->getField("id",true);
            if(!empty($storeid)){
                $data = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$uid,'b.storeid'=>array('eq',$storeid),'b.id'=>array('in',$catids)))->field("a.id,b.thumb,b.title,b.price,b.type,b.storeid,b.pid,b.catid,b.validity_endtime,a.num,a.`status`,b.`range`")->order(array('b.validity_endtime'=>'asc','a.`status`'=>'asc','a.inputtime'=>'desc'))->page($p,$num)->select();
            }else{
                $data = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$uid,'b.id'=>array('in',$catids)))->field("a.id,b.thumb,b.title,b.price,b.type,b.storeid,b.pid,b.catid,b.validity_endtime,a.num,a.`status`,b.`range`")->order(array('b.validity_endtime'=>'asc','a.`status`'=>'asc','a.inputtime'=>'desc'))->page($p,$num)->select();
            }
		    if ($data) {
				exit(json_encode(array('code' => 200, 'msg' => "success", 'data' => $data)));
		    } else {
				exit(json_encode(array('code' => -201, 'msg' => "暂无优惠券")));
		    }
		}
    }
	/*
     **签到
     */
    public function sign(){
    	$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$user=M('Member')->where(array('id'=>$uid))->find();
		if ($uid == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		} else{
            $hasSign=M('signlog')->where(array('uid'=>$uid))->find();
            $count=!empty($hasSign['continuesign'])?$hasSign['continuesign']:1;
            $lastintegral=!empty($hasSign['integral'])?$hasSign['integral']:0;
            $totalsign=!empty($hasSign['totalsign'])?$hasSign['totalsign']:0;
            if($hasSign){
                $lastSignDay=$hasSign['lastsigntime'];
                $lastSign=date('Y-m-d',$lastSignDay);
                $today=date('Y-m-d',time());
                if($lastSign==$today){
                    exit(json_encode(array('code'=>-200,'msg'=>'今天已签到,您已连续签到'.$count.'天!')));
                }   
                $residueHour=24+24-date('H',$lastSignDay); //有效的签到时间  (签到当天剩余的小时+1天的时间)
                $formatHour=strtotime(date('Y-m-d H',$lastSignDay).':00:00');//签到当天 2014-12-07 18:00:00
                $lastSignDate=strtotime("+{$residueHour}hour",$formatHour);//在2014-12-07 18:00:00 基础上+ 有效的签到时间
                if(time()>$lastSignDate){ //当前时间 >  上一次签到时间
                    $count=1;  
                }else{
                    $count=$count+1;
                }
                //$signintegral=getsignintegral($lastintegral,$count);
                $signintegral=5;
                $sign=M('signlog')->where(array('uid'=>$uid))->save(array(
                    'continuesign'=>$count,
                    'integral'=>$signintegral,    
                    'content'=>'签到+'.$signintegral.'分',    
                    'status'=>1,
                    'totalsign'=>$totalsign+1,
                    'lastsigntime'=>time()
                )); 
            }else{
            	$signintegral=getsignintegral($lastintegral,$count);
                $sign=M('signlog')->add(array(
                    'uid'=>$uid,
                    'continuesign'=>$count,
                    'integral'=>$signintegral,    
                    'content'=>'签到+'.$signintegral.'分',    
                    'status'=>1,
                    'totalsign'=>1,
                    'lastsigntime'=>time(),
                    'inputtime'=>time()
                )); 
            }
            
            if($sign){
            	self::update_integral($uid,$signintegral,1,'签到+'.$signintegral.'分','sign');
                if($count>0){
                    exit(json_encode(array('code'=>200,'msg'=>'签到成功,您已连续签到'.$count.'天!')));
                }else{
                    exit(json_encode(array('code'=>200,'msg'=>'签到成功')));
                }
            }else{
                exit(json_encode(array('code'=>-200,'msg'=>'签到失败,请稍后重试！')));
            }
        }
    }
    /*
     **改进建议
     */
	public function feedback(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$content=trim($ret['content']);

		$where['id']=$uid;
		$user=M('Member')->where($where)->field('id')->find();
		if($uid==''||$content==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$id=M('feedback')->add(array(
				'uid'=>$uid,
				'content'=>$content,
				'inputtime'=>time()
				));
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"提交成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
			}
		}
	}
	/*
     * *合作申请
     */
    public function cooperation() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$company = trim($ret['company']);
		$username = trim($ret['username']);
		$email = trim($ret['email']);
		$contact = trim($ret['contact']);
		$content = trim($ret['content']);

		$user=M('Member')->where(array('id'=>$uid))->find();
		if ($uid == '' || $company == '' || $username == '' || $email == '' || $contact == '' || $content == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		} else {
			$data['uid']=$user['id'];
		    $data['company'] = $company;
		    $data['username'] = $username;
		    $data['email'] = $email;
		    $data['tel'] = $contact;
		    $data['content'] = $content;
		    $data['inputtime'] = time();
		    $id = M("cooperation")->add($data);
		    if ($id) {
				exit(json_encode(array('code' => 200, 'msg' => "申请成功")));
		    } else {
				exit(json_encode(array('code' => -202, 'msg' => "申请失败")));
		    }
		}
    }
    /*
     * *绑定企业账号
     */
    public function bind_company() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$companyid = trim($ret['companyid']);
		$name = trim($ret['name']);
		$phone = trim($ret['phone']);

		$user=M('Member')->where(array('id'=>$uid))->find();
		$company=M('company')->where(array('companynumber'=>$companyid))->find();
		if ($uid == '' || $companyid == '' || $name == '' || $phone == '' ) {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		} elseif(!$company){
			exit(json_encode(array('code'=>-200,'msg'=>"企业不存在!")));
		} else {
			$data['uid']=$user['id'];
		    $data['companyid'] = $company['id'];
		    $data['name'] = $name;
		    $data['phone'] = $phone;
		    $data['inputtime'] = time();
		    $id = M("company_member")->add($data);
		    if ($id) {
                $email_joincompanyconfig=array();
                foreach ($this->ConfigData as $r) {
                    if($r['groupid']==3){
                        $email_joincompanyconfig[$r['varname']] = $r['value'];
                    }
                }
                $body=CommonController::mail_template("email_joincompany",$user['username'],$company['title'],$company['companynumber'],'');
                CommonController::send_mail($company['email'], $email_joincompanyconfig['email_joincompany_subject'], $email_joincompanyconfig['email_joincompany_subject'], $body);
                M("emaillog")->add(array(
                    "email" => $company['email'],
                    "content" => $body,
                    "title"=>$email_joincompanyconfig['email_joincompany_subject'],
                    "s_id" => 0,
                    "inputtime" => time(),
                ));
				exit(json_encode(array('code' => 200, 'msg' => "申请成功")));
		    } else {
				exit(json_encode(array('code' => -202, 'msg' => "申请失败")));
		    }
		}
    }
	/*
     **获取优惠活动
     */
    public function get_activity(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            //$data=M("message a")->join("left join zz_activity b on a.value=b.id")->where(array('a.tuid'=>$uid,'a.varname'=>'hot','b.status'=>1,'a.isdel'=>'0'))->order(array('a.inputtime'=>"desc"))->field('a.id,b.title,b.pid,b.thumb,b.description,b.content,a.inputtime,a.status')->page($p,$num)->select();
			$data=M('message')->where(array('tuid'=>$uid,'varname'=>array('in','hot,hotproduct'),'isdel'=>0))->order(array('id'=>"desc"))->field('id,title,value,varname,description,content,inputtime,status')->page($p,$num)->select();           
            foreach ($data as $key => $value)
            {
                if($value['varname']=='hot'){
                    $data[$key]['thumb']=M('activity')->where(array('id'=>$value['value']))->getField("thumb");
                }elseif($value['varname']=='hotproduct'){
                    $data[$key]['thumb']=M('product')->where(array('id'=>$value['value']))->getField("thumb");
                }
            	
            }
            
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }   
        }
    }
	/*
     **钱包充值--积分充值
     */
	public function recharge_integral(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$money=floatval(trim($ret['money']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		$integral=M('integral')->where(array('uid'=>$uid))->getField("useintegral");
		if($uid==''||$money==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif($integral<$money*100){
			exit(json_encode(array('code'=>-200,'msg'=>"可用积分不足！")));
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
					'remark'=>'使用积分充值'.$money.'元',
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
     **钱包充值--在线充值
     */
	public function recharge_online(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$money=floatval(trim($ret['money']));
		$paytype=intval(trim($ret['paytype']));

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
			$channel="";
            $paytypevalue="";
			$orderid="rc".date("YmdHis", time()) . rand(100, 999);
			switch ($paytype) {
				case '1':
					# code...
					$paytypevalue="alipay";
					$channel='alipay';
					break;
				case '2':
					# code...
					$paytypevalue="weixin";
					$channel='wx';
					break;
			}
			$mid=M('recharge')->add(array(
					'uid'=>$uid,
					'title'=>"钱包充值--在线充值",
                    'orderid' => $orderid,
					'type'=>$paytypevalue,
					'money'=>$money,
					'status'=>1,
					'channel'=>$channel,
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
    public function pingpp($orderid){
        $order=M('recharge')->where(array('orderid'=>$orderid))->find();
        $orderid=$order['orderid'];
        $extra = array();

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
            //echo $ch;
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,\Think\Log::INFO);
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            return $ch;
        }
        catch (\Pingpp\Error\Base $e) {
            $Status = $e->getHttpStatus();
            $body = $e->getHttpBody();
            return 'Status: ' . $Status ." body:".$body;
        }

    }

	/*
     **我的关注
     */
	public function myattention(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
			$data=M('attention a')->join("left join zz_product b on a.pid=b.id")->where(array('a.uid'=>$uid,'b.isoff'=>0,'b.isdel'=>0))->field('a.pid,b.title,b.thumb,b.description,b.nowprice,b.oldprice,b.unit,b.ishot,b.type')->page($p,$num)->select();
			if($data){
                exit(json_encode(array('code'=>-200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
		}
	}
	/*
     **添加关注
     */
	public function attention(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$pid=intval(trim($ret['pid']));
		$uid=intval(trim($ret['uid']));

		$user=M('Member')->where(array('id'=>$uid))->find();
		$Product=M('Product')->where(array('id'=>$pid))->find();

		$status=M('attention')->where(array('uid'=>$uid,'pid'=>$pid))->find();
		if($pid==''||$uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
		}elseif(empty($Product)){
			exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
		}elseif(!empty($status)){
			exit(json_encode(array('code'=>-200,'msg'=>"已关注此商品")));
		}else{
			$id=M('attention')->add(array(
				'uid'=>$uid,
				'pid'=>$pid,
				'inputtime'=>time()
			));
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"添加关注成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"添加关注失败")));
			}
		}
	}
	/*
     **取消关注
     */
	public function unattention(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
		$pid=intval(trim($ret['pid']));
		$uid=intval(trim($ret['uid']));

		$user=M('Member')->where(array('id'=>$uid))->find();
		$Product=M('Product')->where(array('id'=>$pid))->find();

		$status=M('attention')->where(array('uid'=>$uid,'pid'=>$pid))->find();
		if($pid==''||$uid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
		}elseif(empty($Product)){
			exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
		}elseif(empty($user)){
			exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
		}elseif(empty($status)){
			exit(json_encode(array('code'=>-200,'msg'=>"尚未关注此商品")));
		}else{
			$id=M('attention')->delete($status['id']);
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"取消关注成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"取消关注失败")));
			}
		}
	}
	/*
     **我的商品收藏
     */
    public function mycollect(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M('collect a')->join("left join zz_product b on a.value=b.id")->where(array('a.uid'=>$uid,'b.isdel'=>0))->field('a.value as pid,b.title,b.thumb,b.description,b.nowprice,b.oldprice,b.unit,b.ishot,b.type,b.expiretime,b.selltime,b.standard,b.unit')->page($p,$num)->select();
            foreach ($data as $key => $value)
            {
            	$data[$key]['unit']=getunit($value['unit']);
            }
            
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /*
     **收藏商品
     */
    public function collect(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $pid=intval(trim($ret['pid']));
        $uid=intval(trim($ret['uid']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        $product=M('product')->where(array('id'=>$pid))->find();
        $status=M('collect')->where(array('uid'=>$uid,'value'=>$pid,'varname'=>'product'))->find();
        if($pid==''||$uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(empty($product)){
            exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
        }elseif($status){
            exit(json_encode(array('code'=>-200,'msg'=>"已收藏此商品")));
        }else{
            $id=M('collect')->add(array(
                'uid'=>$uid,
                'varname'=>'product',
                'value'=>$pid,
                'inputtime'=>time()
            ));
            if($id){
                M("message")->add(array(
                    'uid'=>0,
	  				'tuid'=>$uid,
                    'title'=>'收藏商品',
                    'description'=>"我收藏了商品" . $product['title'],
                    'content'=>"我收藏了商品" . $product['title'],
                    'varname'=>"system",
                    'value'=>$pid,
                    'inputtime'=>time()
                ));
                exit(json_encode(array('code'=>200,'msg'=>"收藏成功")));     
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"收藏失败")));
            }
        }
    }
    /*
     **取消收藏商品
     */
    public function uncollect(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $pid=intval(trim($ret['pid']));
        $uid=intval(trim($ret['uid']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        $product=M('product')->where(array('id'=>$pid))->find();
        $status=M('collect')->where(array('uid'=>$uid,'value'=>$pid,'varname'=>'product'))->find();
        if($pid==''||$uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(empty($product)){
            exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
        }elseif(!$status){
            exit(json_encode(array('code'=>-200,'msg'=>"尚未收藏此商品")));
        }else{
            $id=M('collect')->delete($status['id']);
            if($id){
                M("message")->add(array(
                    'uid'=>0,
	  				'tuid'=>$uid,
                    'title'=>'取消收藏商品',
                    'description'=>"我取消收藏了商品" . $product['title'],
                    'content'=>"我取消收藏商品" . $product['title'],
                    'varname'=>"system",
                    'value'=>$pid,
                    'inputtime'=>time()
                ));
                exit(json_encode(array('code'=>200,'msg'=>"取消收藏成功")));     
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"取消收藏失败")));
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
		M('message')->add(array(
            'uid'=>0,
            'tuid'=>$uid,
            'varname'=>$message_type,
            'value'=>$value,
            'title'=>$title,
            'description'=>$description,
            'content'=>$content,
            'inputtime'=>time()
            ));
        $push['title']=$title;
        $push['description']=$description;
        $push['content']=$content;
        $push['isadmin']=1;
        $push['inputtime']=time();
        $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
        $mid = M("Push")->add($push);
        $registration_id=M('member')->where(array('id'=>array('eq',$uid)))->getField("deviceToken");
        $receiver = $registration_id;
        $extras = array("mid"=>$mid,'message_type'=>$message_type);
        if(!empty($receiver)){
            PushQueue($mid, $message_type,$receiver, $title,$description, serialize($extras),1);
        }
	}

	/*
     **我的积分
     */
    public function get_integrallog(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data=M("integrallog")->where(array('uid'=>$uid))->order(array('id'=>"desc"))->field('id,uid,paytype,content,integral,inputtime')->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
            }   
        }
    }
    public function walletlog(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $where['group_id']=1;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M("account_log")->where(array('uid'=>$uid))->order(array('id'=>"desc"))->field("id,money,dcflag,remark,status,addtime")->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"无使用记录")));
            }  
        }
    }
}
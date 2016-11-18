<?php

namespace Web\Controller;

use Web\Common\CommonController;

class LogisticsController extends CommonController {
	/*
     * *会员注册
     */
    public function test() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
        $ret = CommonController::decrypt($ret['data']);
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
				exit(json_encode(array('code' => 200, 'msg' => "success", 'data' => CommonController::encrypt($data))));
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
        $ret = CommonController::decrypt($ret['data']);
		$telverify = trim($ret['telverify']);
		$password = trim($ret['password']);
		$phone = trim($ret['phone']);
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
		if ($phone == '' || $password == '' || $telverify == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (strtolower($telverify) != strtolower($verify)) {
		    exit(json_encode(array('code' => -200, 'msg' => "验证码错误")));
		} elseif (!isMobile($phone)) {
		    exit(json_encode(array('code' => -200, 'msg' => "手机号码格式错误")));
		} elseif (!check_phone($phone)) {
		    exit(json_encode(array('code' => -200, 'msg' => "手机号已被注册")));
		} else {
		    $data['username'] = $phone;
		    $data['password'] = $password;
		    $data['phone'] = $phone;
		    $data['phone_status'] = 1;
		    $data['group_id'] = 1;
		    $id = D("Member")->addUser($data);
		    if ($id) {
				$dataset['uid'] = $id;
				$dataset['username'] = $phone;
				exit(json_encode(array('code' => 200, 'msg' => "注册成功", 'data' => CommonController::encrypt($dataset))));
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
        $ret = CommonController::decrypt($ret['data']);
		$username = trim($ret['username']);
		$password = trim($ret['password']);
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
		} else {
		    M("userlog")->add(array(
				"username" => $username,
				"logintime" => time(),
				"loginip" => get_client_ip(),
				"status" => 1,
				"password" => "***" . substr($password, 3, 4) . "***",
				"info" => "登录成功"
		    ));
		    //根据用户的type显示不同的结果集
		    $dataset = array();
		    $dataset['uid'] = $user['id'];
		    switch ($group_id) {
				case 1:
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
				    $dataset['phone'] = $user['phone'];
				    $dataset['level'] = $user['level'];
				    $dataset['sex'] = $user['sex'];
				    $dataset['birthday'] = $user['birthday'];
				    $dataset['preference'] = $user['preference'];
				    $dataset['companyid'] = $user['companyid'];
				    // $dataset['integral'] = $user['integral'];
				    // $dataset['usemoney'] = $user['usemoney'];
				    // $dataset['coupons'] = $user['coupons'];
				    break;
				case 2:
				    $dataset['workstatus'] = $user['workstatus'];
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
				    break;
			}
		    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' => CommonController::encrypt($dataset))));
		}
    }
    /*
     * *会员登录-第三方登陆
     */

    public function applogin() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$ret = CommonController::decrypt($ret['data']);
		$openid = trim($ret['openid']);
		$site = trim($ret['site']);
		$nickname = trim($ret['nickname']);
		$head = trim($ret['head']);


		$bind = M('oauth')->field('id,bind_uid,logintimes')->where(array('openid' => $openid, 'site' => $site))->find();
		$user = M('member')->where(array('id' => $bind['bind_uid']))->find();
		if ($openid == '' || $site == '' || $nickname == '' || $head == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (!$user) {
		    $data['username'] = $openid;
		    $data['password'] = "123456";
		    $data['nickname'] = $nickname;
		    $data['head'] = $head;
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
		    $dataset['integral'] = 0;
		    $dataset['usemoney'] = "0.00";
		    $dataset['coupons'] = 0;

		    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' => CommonController::encrypt($dataset))));
		} else {
		    $save['id'] = $bind['id'];
		    $save['logintimes'] = ($bind['logintimes'] + 1);
		    $save['logintime'] = time();
		    M('oauth')->save($save);
		    M('member')->where(array('id' => $bind['bind_uid']))->save(array(
		    	'nickname' => $nickname,
		    	'head' => $head
		    	));
		    $dataset = array();
		    $dataset['uid'] = $user['id'];
		    $dataset['username'] = $user['username'];
		    $dataset['head'] = $head;
		    $dataset['nickname'] = $nickname;
		    $dataset['phone'] = $user['phone'];
		    $dataset['level'] = $user['level'];
		    $dataset['sex'] = $user['sex'];
		    $dataset['birthday'] = $user['birthday'];
		    $dataset['preference'] = $user['preference'];
		    $dataset['companyid'] = $user['companyid'];
		    $dataset['integral'] = $user['integral'];
		    $dataset['usemoney'] = $user['usemoney'];
		    $dataset['coupons'] = $user['coupons'];
				    
		    exit(json_encode(array('code' => 200, 'msg' => "登录成功", 'data' => CommonController::encrypt($dataset))));
		}
    }

    /**
     * 登陆
     * @param type $identifier 用户ID,或者用户名
     * @param type $password 用户密码，不能为空
     * @param type $autotype 是否记住用户自动登录
     * @return type 成功返回true，否则返回false
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
        $ret = CommonController::decrypt($ret['data']);
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
				    $dataset['level'] = $user['level'];
				    $dataset['sex'] = $user['sex'];
				    $dataset['birthday'] = $user['birthday'];
				    $dataset['preference'] = $user['preference'];
				    $dataset['companyid'] = $user['companyid'];
				    $dataset['integral'] = $user['integral'];
				    $dataset['usemoney'] = $user['usemoney'];
				    $dataset['coupons'] = $user['coupons'];
				    break;
				case 2:
				    $dataset['workstatus'] = $user['workstatus'];
				    $dataset['username'] = $user['username'];
				    $dataset['head'] = $user['head'];
				    $dataset['nickname'] = $user['nickname'];
				    break;
			}
			exit(json_encode(array('code' => 200, 'msg' => "获取数据成功", 'data' => CommonController::encrypt($dataset))));
		}
	}
	/*
	**会员完善资料
	*/
	public function change_info(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
        $ret = CommonController::decrypt($ret['data']);
		$uid = intval(trim($ret['uid']));
		$filed=trim($ret['filed']);
		$value=trim($ret['value']);

		$where['id']=$uid;
		$user=M('Member')->where($where)->field('id')->find();
		$fieldarray=array('head');
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
	**设置密码
	*/
	public function setpassword(){
		$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
		$ret=json_decode($ret,true);
        $ret = CommonController::decrypt($ret['data']);
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
        $ret = CommonController::decrypt($ret['data']);
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
        $ret = CommonController::decrypt($ret['data']);
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
			
			$other=M('address')->where(array('uid'=>$user['id'],'type'=>3))->field('id,area,address,name,remark,tel,lat,lng')->select();
			foreach ($other as $key => $value) {
				# code...
				$other[$key]['areatext']=getarea($value['area']);
			}
			$company=M('address')->where(array('uid'=>$user['id'],'type'=>1))->field('id,area,address,name,remark,tel,lat,lng')->select();
			foreach ($company as $key => $value) {
				# code...
				$company[$key]['areatext']=getarea($value['area']);
			}
			$home=M('address')->where(array('uid'=>$user['id'],'type'=>2))->field('id,area,address,name,remark,tel,lat,lng')->select();
			foreach ($home as $key => $value) {
				# code...
				$home[$key]['areatext']=getarea($value['area']);
			}
			$default=M('address')->where(array('uid'=>$user['id'],'isdefault'=>1))->field('id,area,address,name,remark,tel,lat,lng')->order(array('inputtime'=>'desc'))->find();
			$default['areatext']=getarea($default['area']);
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
        $ret = CommonController::decrypt($ret['data']);
		$uid = intval(trim($ret['uid']));
		$area=trim($ret['area']);
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
        $ret = CommonController::decrypt($ret['data']);
		$addressid=intval(trim($ret['addressid']));
		$uid = intval(trim($ret['uid']));
		$area=trim($ret['area']);
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
		if($addressid==''||$uid==''||$area==''||$address==''||$name==''||$tel==''||$lat==''||$lng==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}else{
			$id=M('address')->where(array('id'=>$addressid,'uid'=>$user['id']))->save(array(
				'area'=>$area,
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
        $ret = CommonController::decrypt($ret['data']);
		$uid = intval(trim($ret['uid']));
		$addressid=intval(trim($ret['addressid']));

		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		if($uid==''||$addressid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
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
		$user=M('Member')->where(array('id'=>$uid))->find();
		if ($uid == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		} else{
			$data = M('vouchersorder a')->where(array('uid'=>$uid))->join("left join zz_vouchers as b on b.id=a.vid")->field("a.id,b.id as vid,b.thumb,b.name,a.price,b.validity_endtime,a.num,a.status")->order(array('a.inputtime'=>'desc'))->select();
		    if ($data) {
				exit(json_encode(array('code' => 200, 'msg' => "success", 'data' => $data)));
		    } else {
				exit(json_encode(array('code' => -201, 'msg' => "暂无代金券")));
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
}
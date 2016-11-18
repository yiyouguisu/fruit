<?php

namespace Web\Controller;

use Web\Common\CommonController;

class MemberController extends CommonController {
	public function _initialize() {
        parent::_initialize();
        $this->cart_total_num();
    }
	//首页
	public function index(){
		if (!session('uid')) {
            $returnurl=urlencode($_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
            $this->error('请先登录！',U('Web/Member/login')."?returnurl=".$returnurl);
        } else {
			$uid = session('uid');
			$data=D("member")->where("id=".$uid)->find();
            $data['level'] = getlevel($data['id']);
			$this->assign("data",$data);
			
            $integral = M('integral')->where(array("uid"=>$uid))->find();
            if(empty($integral['useintegral'])){
                $this->assign('integral_total',"0");
            }else{
                $this->assign('integral_total',$integral['useintegral']);
            }

            $account = M('account')->where(array("uid"=>$uid))->find();
            if(empty($account['usemoney'])){
                $this->assign('account_total',"0.00");
            }else{
                $this->assign('account_total',$account['usemoney']);
            }

            $coupons = M('coupons_order a')->join("left join zz_coupons b on a.catid=b.id")->where(array('a.uid'=>$uid,'a.status'=>0,'b.status'=>1,'b.validity_endtime'=>array('GT',time())))->count();
            $this->assign('coupons_total',$coupons);

            //判定是否有新消息
            $messagecount = M('message')->where(array('tuid'=>$uid,'varname'=>array('in','system,hot,hotproduct'),'isdel'=>'0','status'=>'0'))->order(array('inputtime'=>"desc"))->select();
            $this->assign('wordcount',count($messagecount));
            $this->display();
		}
		
	}
    /**
     * 会员注册
     * @author yiyouguisu<741459065@qq.com> time|20151219
     * @retrun void
     */
    public function reg() {
        if(IS_POST){
            $telverify = trim($_POST['telverify']);
            $password = trim($_POST['password']);
            $phone = trim($_POST['phone']);

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
            if(empty($password)||empty($telverify)||empty($phone)){
                $this->error('请将信息填写完整');
            }elseif (strtolower($telverify) != strtolower($verify)) {
                $this->error('验证码错误');
            }elseif(!check_phone($phone)){
                $this->error('手机号已被注册');
            }else{
                $data=$_POST;
                $data['group_id']=1;
                $data['username']=$_POST['phone'];
                $data['head']="/default_head.png";
                if($_COOKIE['web_user_openid']){
                    $data['user_openid']=$_COOKIE['web_user_openid'];
                }
                $id = D("member")->addUser($data);
                if ($id) {
                    cookie("username",$data['username']);
                    cookie("userid",$id);
                    cookie("groupid",$data['group_id']);
                    $this->success('注册成功',U('Web/Member/index'));
                } else {
                    $this->error(D("member")->getError());
                }
            }
            
        }else{
            $this->display();
        }
    }


    /**
     * 会员登录
     * @author yiyouguisu<741459065@qq.com> time|20151219
     * @retrun void
     */
    public function login() {
        if (session('uid')) {
            $this->redirect('Web/Member/index');
        } else {
            if (IS_POST) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $autotype = 1;
                //$user = D("member")->getLocalAdminUser($username, $password);
                //dump($user);die;
                $status=$this->loginHome($username, $password, $autotype);
                if ($status==2) {
                    $this->redirect('Web/Member/index');
                }elseif($status==0) {
                    $this->error('登录失败');
                }elseif($status==1) {
                    $this->error('帐号被禁用,请联系管理员');
                }
            } else {
                $returnurl=$_GET['returnurl'];
                $this->assign("returnurl",$returnurl);
                $this->display();
            }
        }
    }
    public function wxlogin(){
        if (session('uid')) {
            $this->redirect('Web/Member/index');
        } else {
            $Wxhelp=A('Web/Wxhelp');
            $userinfo = $Wxhelp->GetUserInfo();
            if(empty($userinfo['unionid'])){
                $this->error('授权失败',U('Member/login'));
            }
            cookie("user_unionid",$userinfo['unionid'],C('AUTO_TIME_LOGIN'));
            $user=M('Member')->where(array('username'=>$userinfo['unionid']))->find();
            if (!$user) {
                $this->error('登录失败',U('Member/login'));
            }elseif ($user['status'] == 0) {
                $this->error('帐号被禁用,请联系管理员',U('Member/login'));
            }else{
                session('username', $user['username']);
                session('uid', $user['id']);
                session('groupid',$user['group_id']);
                if($_COOKIE['web_user_openid']){
                    M('member')->where(array('id'=>$user['id']))->setField('user_openid',$_COOKIE['web_user_openid']);
                }
                M("member")->where(array("id" => $user['id']))->save(array(
                    "lastlogin_time" => time(),
                    "login_num" => $user["login_num"] + 1,
                    "lastlogin_ip" => get_client_ip()
                ));
                $this->redirect('Web/Member/index');
            }
        }
    }
    /**
     * 会员退出登录
     * @author yiyouguisu<741459065@qq.com> time|20151219
     * @retrun void
     */
    public function loginout() {
        if (session('uid')) {
            cookie('auto', null);
            cookie('user_openid', null);
            session('uid', null);
            session('username', null);
            session('groupid', null);
            $this->redirect('Web/Member/login');
        } else {
            $this->redirect('Web/Member/login');
        }
    }
    /**
     * 会员忘记密码
     * @return void
     * @author yiyouguisu<741459065@qq.com> time|20151219
     */
    public function forgot() {
        if(IS_POST){
            $telverify = trim($_POST['telverify']);
            $password = trim($_POST['password']);
            $phone = trim($_POST['phone']);

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
            $user = M("Member")->where(array('phone'=>$phone))->find();
            if(empty($password)||empty($telverify)||empty($phone)){
                $this->error('请将信息填写完整');
            }elseif (strtolower($telverify) != strtolower($verify)) {
                $this->error('验证码错误');
            }elseif(!$user){
                $this->error('用户不存在');
            }else{
                if ($rs = D("Member")->ChangePassword($user['username'], $password)) {
                    $this->success("设置成功，请登录",U('Member/login'));
                } else {
                    $this->error("设置失败！");
                }
            }
        }else{
            $this->display();
        }
    }
	//个人信息展示
	public function view(){
		$userid = session('uid');
		$data=D("member")->where("id=".$userid)->find();
        if($data['companyid'] == '0') {
            $data['companyid'] = '未绑定';
        }else{
            $data['companyid']=M('company')->where('id='.$data['companyid'])->getField('title');
        }
        $data['level'] = getlevel($data['id']);
		$this->assign("data",$data);
		$this->display();
	}
	
	//个人信息修改
	public function edit(){
		$uid = session('uid');
        $preference = $_REQUEST['preference'];
		$userinfo = M('member')->where(array("id"=>$uid))->find();
		if ($_POST){
			if (D("member")->create()) {
	    		D("member")->id = $uid;
                if(!empty($preference)){
                    D("member")->preference = $preference;
                }
	    		if (empty($_POST['head'])){
	    			D("member")->head = $userinfo['head'];
	    		}
		        $id = D("member")->save();
		        
	            if ($id===false) {
                    $this->show('<script>alert("用户信息修改失败")</script>');
                    echo "<script>history.go(-1)</script>";
	            }else {
	            	$this->redirect('Web/Member/index');
	            }
            }
            else {
                $this->show('<script>alert("用户信息修改失败1")</script>');
                echo "<script>history.go(-1)</script>";
            }
		}
	}
	
	//图片上传
    public function fileupload() {
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts= array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath = 'Uploads/images/pc/'; // 设置附件上传目录
        $upload->subName = array('date','Ymd');
        $info   =   $upload->upload();    
        if(!$info) {// 上传错误提示错误信息        
            $this->error($upload->getError());    
        }else{
            foreach($info as $file){        
                echo "/".$file['savepath'].$file['savename'];    
            }
        }
    }
    
    //签到
	public function sign(){
		$uid = session('uid');
		if (!$uid) {
            exit(json_encode(array('code'=>-200,'msg'=>'请登录')));
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
                    $count = $count+1;
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
            PushQueue($mid, $message_type,$receiver, $title,$description, serialize($extras),1);
        }
	}

    public function feedback(){
		$uid = intval(trim(session('uid')));
        if ($_POST) {
            if (D("feedback")->create()) {
                D("feedback")->inputtime = time();
                D("feedback")->uid =$uid;
                $id = D("feedback")->add();
                if (!empty($id)) {
                    echo "<SCRIPT >alert('您的留言已提交，我们非常感谢！');location.href='javascript:history.go(-2);';</SCRIPT>";
                } else {
                    $this->error("新增链接失败！");
                }
            } else {
                $this->error(D("feedback")->getError());
            }
        }
        $this->display();
    }

    public function mylove(){
        $uid = session('uid');
        $data = M('member')->where("id=".$uid)->find();
        $arrf = array_filter(explode(",", $data["preference"]));
        
        $data = M("linkage")->where("catid=1")->field('value,name')->select();
        foreach ($data as $key=>$value)
        {
        	foreach ($arrf as $k=>$v)
            {
                if($data[$key]['value'] == $arrf[$k])
                {
                    $data[$key]['islove'] = 1;
                }
            }
            
        }
        //dump($data);
        $this->assign('data',$data);
    	$this->display();
    }
	public function setup(){
        $this->display();
    }
    /*
     **获取服务协议配置
     */
    public function about(){
        $type='about_us';
        if($type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data = M("config")->where(array('groupid'=>6,'varname'=>$type))->getField("value");
            if($data){
                //dump($data);
                $this->assign('data',$data);
            }else{
                
            }
        }
        $this->display();
    }

    public function invitecode(){
        $uid=I('get.uid');
        if (!empty($uid))
        {
        	$data = M('member')->where('id='.$uid)->find();
        }else{
            $uid=session("uid");
            $data = M('member')->where('id='.$uid)->find();
        }
        $this->assign('data',$data);
        $url = '';
        $userclient = get_browsers();
        if ($userclient == 'Chrome'||$userclient == 'Firefox'||$userclient == 'Opera'||$userclient == 'Android')
        {   
            $member_anzhuo=M('version')->where('type=1 and group_id=1')->order(array('inputtime'=>'desc'))->find();
            //$url=$member_anzhuo['url'];
        	$url = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.manran.fruitveg';
        }else if($userclient == 'iPhone'||$userclient == 'Safari'){
            $member_ios=M('version')->where('type=2 and group_id=1')->order(array('inputtime'=>'desc'))->find();
            //$url=$member_ios['url'];
            $url = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.manran.fruitveg';
        }else if($userclient == 'iPad'||$userclient == 'Safari'){
            $member_ios=M('version')->where('type=2 and group_id=1')->order(array('inputtime'=>'desc'))->find();
            //$url=$member_ios['url'];
            $url = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.manran.fruitveg';
        }else{
            $url = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.manran.fruitveg';
        }
        if(!empty($url)){
            $this->assign('downurl',$url);
        }
        $this->display();
    }
    /**
     * 登陆
     * @param int|string $identifier 用户ID,或者用户名
     * @param string $password 用户密码，不能为空
     * @param int $autotype 是否记住用户自动登录
     * @return int 
     */
    public function loginHome($identifier, $password, $autotype = 0) {
        if (empty($identifier) || empty($password)) {
            return 0;
        }else{
            $user = D("member")->getLocalAdminUser($identifier, $password);
            if (!$user) {
                $this->recordLoginHome($identifier, $password, 0, "帐号密码错误");
                return 0;
            }elseif ($user['status'] == 0) {
                $this->recordLoginHome($identifier, $password, 0, "帐号被禁止");
                return 1;
            }elseif ($user['group_id'] != 1) {
                $this->recordLoginHome($identifier, $password, 0, "用户角色错误");
                return 0;
            }else{
                session('username', $user['username']);
                session('uid', $user['id']);
                session('groupid',$user['group_id']);
                if ($autotype == 1) {
                    $autoinfo = $user['id'] . "|" . $user['username'] . "|" . get_client_ip();
                    $auto = \Web\Common\CommonController::authcode($autoinfo, "ENCODE");
                    cookie('auto', $auto, C('AUTO_TIME_LOGIN'));
                }
                if($_COOKIE['web_user_openid']){
                    M('member')->where(array('id'=>$user['id']))->setField('user_openid',$_COOKIE['web_user_openid']);
                }
                M("member")->where(array("id" => $user['id']))->save(array(
                    "lastlogin_time" => time(),
                    "login_num" => $user["login_num"] + 1,
                    "lastlogin_ip" => get_client_ip()
                ));
                return 2;
            }
        }
        
        
    }

    /**
     * 记录前台登陆信息
     * @param string $identifier 用户名
     * @param string $password 用户密码
     * @param int $status 状态 1登录成功 0登录失败
     * @param string $info 备注
     * @author yiyouguisu<741459065@qq.com> time|20151219
     */
    public function recordLoginHome($identifier, $password, $status, $info = "") {
        M("userlog")->add(array(
            "username" => $identifier,
            "logintime" => date("Y-m-d H:i:s"),
            "loginip" => get_client_ip(),
            "status" => $status,
            "password" => "***" . substr($password, 3, 4) . "***",
            "info" => $info
        ));
    }
}
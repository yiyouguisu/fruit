<?php

namespace Api\Controller;

use Api\Common\CommonController;

class OauthController extends CommonController {

    function _initialize(){
        import("ORG.OAuth.ThinkOAuth2");
        $this->oauth = new \ORG\OAuth\ThinkOAuth2();  
    }
    public function authorize() {
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
          exit(json_encode(array('code' => -200, 'msg' => "The User is not exist!")));
      } else {
          //根据用户的type显示不同的结果集
          $dataset = array();
          $data['client_id']="ycs201509151105";
          $data['client_secret']="S5VsfCQSDZDt4ndXixrF6feLhzBgUYDb";
          $data['response_type']="code";
          $data['redirect_uri']="http://o2o2otest.ccjjj.net/";
          $code=$this->oauth->finishClientAuthorization(TRUE, $user['id'], $data);
          exit(json_encode(array('code' => 200, 'msg' => "success", 'authorizecode' => $code)));
      }
    }
    
    //获取到应用网站token
    public function getAccessToken(){
      $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
      $ret=json_decode($ret,true);
		  $ret = CommonController::decrypt($ret['data']);
      $code = trim($ret['code']);
      $data['grant_type']="authorization_code";
      $data['code']=$code;
      $data['redirect_uri']="http://o2o2otest.ccjjj.net/";
      $data['client_id']="ycs201509151105";
      $data['client_secret']="S5VsfCQSDZDt4ndXixrF6feLhzBgUYDb";

      $result = httppost('http://o2o2otest.ccjjj.net/index.php/Api/Oauth/token',$data);
      exit(json_encode(array('code' => 200, 'msg' => "success", 'result' => $result)));
    }
    public function token(){
      $data = $this->oauth->grantAccessToken();
      echo $data;
    }
    

    public function getUserInfo(){
      $access_token = $_POST['access_token'];
      $status = $this->oauth->verifyAccessToken();

      if ($status === TRUE) {
        $uid=M('auth_token')->where(array('access_token'=>$access_token))->getField("uid");
        $user = M('member')->field('uid,username')->find($uid);
        $user['uname'] = $user['username'];
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

    
}
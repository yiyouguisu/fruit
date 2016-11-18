<?php

namespace Web\Controller;

use Web\Common\CommonController;

class WxhelpController extends CommonController {
    public function _initialize() {
        $this->appid = C('WEI_XIN_INFO.APP_ID');
        $this->appsecret = C("WEI_XIN_INFO.APP_SECRET");
    }
	
	public function GetOpenid()
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
			$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_base');
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$get_user_info_url = $this->__CreateOauthUrlForOpenid($code);
            $res = file_get_contents($get_user_info_url);
            $user_obj = json_decode($res, true);
            $openId=$user_obj["openid"];
			return $openId;
		}
	}
	public function GetUserInfo()
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
			$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_userinfo');
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$get_user_url = $this->__CreateOauthUrlForOpenid($code);
            $user_obj_res = file_get_contents($get_user_url);
            $user_obj = json_decode($user_obj_res, true);
            $get_user_info_url = $this->__CreateGetUserInfoUrl($user_obj["access_token"],$user_obj["openid"]);
            $res = file_get_contents($get_user_info_url);
            $user_info_obj = json_decode($res, true);
			return $user_info_obj;
		}
	}
	public function GetUserInfoForOpenId($user_obj){
		$get_user_info_url = $this->__CreateGetUserInfoUrl($user_obj["access_token"],$user_obj["openid"]);
        $res = file_get_contents($get_user_info_url);
        $user_info_obj = json_decode($res, true);
		return $user_info_obj;
	}
	public function GetOpenidFromMp($code)
	{
		$url = $this->__CreateOauthUrlForOpenid($code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$openid = $data['openid'];
		return $openid;
	}
	
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	private function __CreateOauthUrlForCode($redirectUrl,$scope='snsapi_base')
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = $scope;
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	private function __CreateOauthUrlForOpenid($code)
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["secret"] = $this->appsecret;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
    private function __CreateGetUserInfoUrl($access_token,$openId){
        $urlObj["access_token"] = $access_token;
		$urlObj["openid"] = $openId;
        $urlObj["lang"] = "zh_CN";
        $bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
    }
}
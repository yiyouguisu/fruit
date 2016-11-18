<?php

namespace Api\Controller;

use Api\Common\CommonController;

use Org\Util\CCPRest;

class CCPRestController extends CommonController {

	protected $client,$Config, $ConfigData,$sms_config ;

	public function _initialize(){
        parent::_initialize();
		set_time_limit(0);
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        $sms_config=array();
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        foreach ($ConfigData as $r) {
            if($r['groupid'] == 5){
                $sms_config[$r['varname']] = $r['value'];
            }
        }
        $this->ConfigData=$sms_config;
		$this->softVersion='2013-12-26';
    }
    public function test(){
    	$datas=array('4425','2');
		self::sendTemplateSMS("15225071509",$datas,"62495");
    }
	public function sendsmsapi($ret){
		$ret=json_decode($ret,true);
        $phone=trim($ret['phone']);
        $datas=$ret['datas'];
        $templateid=trim($ret['templateid']);
        self::sendTemplateSMS($phone,$datas,$templateid);
	}
	public function sendsmsapi_weixin($ret){
		$ret=json_decode($ret,true);
		$phone=trim($ret['phone']);
		$code=trim($ret['code']);
		$timelimit=trim($ret['timelimit']);
		$datas=array($code,$timelimit);
		self::sendTemplateSMS($phone,$datas,"62495");
	}
	/**
     * 发送模板短信
     * @param string $to 手机号码集合,用英文逗号分开
     * @param array $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param string $tempid 手机号码集合,用英文逗号分开
     */       
	public function sendTemplateSMS($to,$datas,$tempid){
        $client = new CCPRest($this->ConfigData['ccpest_serverIP'],$this->ConfigData['ccpest_serverPort'],$this->softVersion);
        $client->setAccount($this->ConfigData['ccpest_accountSid'],$this->ConfigData['ccpest_accountToken']);
        $client->setAppId($this->ConfigData['ccpest_appId']);
	    
        $result = $client->sendTemplateSMS($to,$datas,$tempid);
        return $result->statusCode;
	}
}
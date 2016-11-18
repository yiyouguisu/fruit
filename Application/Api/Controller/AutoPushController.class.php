<?php

namespace Api\Controller;

use Api\Common\CommonController;

class AutoPushController extends CommonController {
	var $runTime_1;
	protected $Config, $ConfigData;

    public function _initialize(){
    	$this->runTime_1 = microtime(true);
        parent::_initialize();
        set_time_limit(0);
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $thirdApiconfig=array();
        foreach ($ConfigData as $r) {
            if($r['groupid'] == 5){
                $thirdApiconfig[$r['varname']] = $r['value'];
            }
        }
        $this->ConfigData=$thirdApiconfig;
    }
	
	public function push(){
		$strOut="";
		$push_queue = M('sendpush_queue')->field('id,mid,receiver,title,description,extras,type')->limit(50)->where(array('status'=>0))->select();
		foreach($push_queue as $v){
			$receiver=explode(",", $v['receiver']);
			$receiver = array("registration_id"=>$receiver);//接收者
			M('sendpush_queue')->where(array('id'=>$v['id']))->setField('send_time_start',time());
			$res_arr=self::pushMessage($v['description'],$v['title'],$receiver,unserialize($v['extras']),$v['type']);
            M('pushlog')->add(array(
                'receiver'=>$v['receiver'],
                'title'=>$v['title'],
                'extras'=>$v['extras'],
                'result'=>serialize($res_arr),
                'inputtime'=>time()
                ));
            if(!empty($v['mid'])){
                if(!empty($res_arr['msg_id'])){
                    M('push')->where(array('id'=>$v['mid']))->setField("status",2);
                }else{
                    M('push')->where(array('id'=>$v['mid']))->setField("status",3);
                }
            }
			$save = array();
			if($res_arr){
				$save['status'] = 1;
				$save['send_time_end'] = time();
				$strOut.="列队第:{$v['id']}号待推送信息处理成功\r\n";
			}else{
				$save['send_time_end'] = time();
				$strOut.="列队第:{$v['id']}号待推送信息处理失败\r\n";
			}
			M('sendpush_queue')->where(array('id'=>$v['id']))->save($save);
		}
		$data = array(
	        "task_info" => $strOut,
	        "add_time" => date("Y-m-d H:i:s"),
	        "run_time" => (microtime(true)-$this->runTime_1)
	    );
	    M("task_log")->add($data);
		exit();
	}

	public function request_post($url="",$param="",$header="") {
	    if (empty($url) || empty($param)) {
	        return false;
	    }
	    $postUrl = $url;
	    $curlPost = $param;
	    $ch = curl_init();//初始化curl
	    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
	    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	    // 增加 HTTP Header（头）里的字段 
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    // 终止从服务端进行验证
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    $data = curl_exec($ch);//运行curl
	    curl_close($ch);
	    return $data;
	}
	public function pushMessage($message='',$title='',$receiver='all',$extras=array(),$type,$message_type=1,$m_time='86400',$platform='all'){
	    $url = 'https://api.jpush.cn/v3/push';
        $appkeys="";
        $masterSecret="";
        if($type==1){
            $appkeys=$this->ConfigData['jpush_member_appkey'];
            $masterSecret = $this->ConfigData['jpush_member_masterSecret'];
        }elseif($type==2){
            $appkeys=$this->ConfigData['jpush_run_appkey'];
            $masterSecret = $this->ConfigData['jpush_run_masterSecret'];
        }
	    $base64=base64_encode("$appkeys:$masterSecret");
	    $header=array(
	        "Authorization:Basic $base64",
	        "Charset:UTF-8",
	        "Content-Type:application/json");
	    $data = array();
	    $data['platform'] = $platform;
	    $data['audience'] = $receiver;
	    if($message_type == 1){
	        $data['notification'] = array(
	            "alert"=>$message,   
	            "android"=>array(
	                //"alert"=>$message,
	                //"title"=>$title,
	                "builder_id"=>1,
	                "extras"=> $extras
	                ),
	            "ios"=>array(
	                "badge"=>"+1",
	                "sound"=>"default",
	                "extras"=>$extras
	                ),
	            );
	    }else{
	        $data['message'] = array(
	            "title"=> $title,
	            "msg_content" =>$message,
	            "extras"=>$extras
	            );
	    }
	    $data['options'] = array(
	        "sendno"=>111111,
	        "time_to_live"=>$m_time,
	        "apns_production"=>true,
	    );
	    $param = json_encode($data);
	    $res = self::request_post($url,$param,$header);
	    if ($res === false) {
	        return false;
	    }
	    $res_arr = json_decode($res, true);
	    return $res_arr;
	}
	public function test(){
	    $url = 'https://api.jpush.cn/v3/push';
        $appkeys="";
        $masterSecret="";
        $m_time='86400';
        $message_type=1;
        $appkeys=$this->ConfigData['jpush_member_appkey'];
        $masterSecret = $this->ConfigData['jpush_member_masterSecret'];

	    $base64=base64_encode("$appkeys:$masterSecret");
	    $header=array(
	        "Authorization:Basic $base64",
	        "Charset:UTF-8",
	        "Content-Type:application/json");
	    $data = array();
	    $data['platform'] = "all";
	    $data['audience'] = "all";
	    if($message_type == 1){
	        $data['notification'] = array(
	            "alert"=>"1121",   
	            "android"=>array(
	                //"alert"=>$message,
	                //"title"=>$title,
	                "builder_id"=>1,
	                "extras"=> ""
	                ),
	            "ios"=>array(
	                "badge"=>"+1",
	                "sound"=>"default",
	                "extras"=>""
	                ),
	            );
	    }else{
	        $data['message'] = array(
	            "title"=> "1121",
	            "msg_content" =>"1313136",
	            "extras"=>""
	            );
	    }
	    $data['options'] = array(
	        "sendno"=>111111,
	        "time_to_live"=>$m_time,
	        "apns_production"=>true,
	    );
	    $param = json_encode($data);
	    $res = self::request_post($url,$param,$header);
	    if ($res === false) {
	        return false;
	    }
	    $res_arr = json_decode($res, true);
	    dump($res_arr) ;
	}
}
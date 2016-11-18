<?php

namespace Web\Controller;

use  Think\Controller;

class PublicController extends Controller {

    public function check_verify() {
        $verify = new \Think\Verify();
        $code = $_POST['verify'];
        $verifyok = $verify->check($code, $id = '');
        if (!$verifyok) {
            echo "验证码错误";
        } else {
            echo "";
        }
    }

    public function verify() {
        $verify = new \Think\Verify();
        ob_end_clean();
        $verify->expire = 300;
        $verify->fontSize = 16;
        $verify->length = 4;
        $verify->imageW = 110;
        $verify->imageH = 40;
        $verify->useNoise = false;
        $verify->useCurve = false;
        $verify->bg = array(255, 255, 255);
        $verify->entry();
    }

    public function sendchecknum(){
    	$phone = $_GET['phone'];
    	if (!check_phone($phone)) {
		    $msg = array('code' => "-200", 'msg' => "手机已经被注册");
		    $this->ajaxReturn($msg,'json');
		}else {
			$code=\Api\Common\CommonController::checknum(4);
            $m=$phone;
            $verify=M('verify')->where(array('phone'=>$m))->find();
            if($verify){
                M('verify')->where(array('phone'=>$m))->save(array(
                    'verify'=>$code,
                    'inputtime'=>time(),
                    'expiretime'=>strtotime("+5 minute"),
                    'status'=>0
                ));
            }else{
                M('verify')->add(array(
                    'phone'=>$phone,
                    'verify'=>$code,
                    'inputtime'=>time(),
                    'expiretime'=>strtotime("+5 minute"),
                    'status'=>0
                ));
            }
            $data=json_encode(array('phone'=>$m,'datas'=>array($code,2),'templateid'=>"62495"));
            $CCPRest = A("Api/CCPRest");
            $CCPRest->sendsmsapi($data);
	        $msg = array('code'=>$code,'msg'=>"已发送");
	        $this->ajaxReturn($msg,'json');
		}
    }
    public function sendchecknum_forgot(){
        $phone = $_GET['phone'];
        if (empty($phone)) {
            $msg = array('code' => "-200", 'msg' => "手机号不能为空");
            $this->ajaxReturn($msg,'json');
        }else {
            $code=\Api\Common\CommonController::checknum(4);
            $m=$phone;
            $verify=M('verify')->where(array('phone'=>$m))->find();
            if($verify){
                M('verify')->where(array('phone'=>$m))->save(array(
                    'verify'=>$code,
                    'inputtime'=>time(),
                    'expiretime'=>strtotime("+5 minute"),
                    'status'=>0
                ));
            }else{
                M('verify')->add(array(
                    'phone'=>$phone,
                    'verify'=>$code,
                    'inputtime'=>time(),
                    'expiretime'=>strtotime("+5 minute"),
                    'status'=>0
                ));
            }
            $data=json_encode(array('phone'=>$m,'datas'=>array($code,2),'templateid'=>"62495"));
            $CCPRest = A("Api/CCPRest");
            $CCPRest->sendsmsapi($data);
            $msg = array('code'=>$code,'msg'=>"已发送");
            $this->ajaxReturn($msg,'json');
        }
    }
   
    public function modifyaddr(){
        $lat = $_COOKIE['web_lat'];
        $lng = $_COOKIE['web_lng'];

        $keyword = I('get.keyword');
        $Map=A("Api/Map");
        if(empty($keyword)){
            // $areadata=$Map->get_areainfo_baidu_simple($lat.",".$lng);
            // $data=M('store')->where(array('servicearea'=>array('like','%,'.$areadata['district'].',%')))->field('id as storeid,title as storename,thumb,lat,lng')->select();
            $data=M('store')->field('id as storeid,title as storename,thumb,lat,lng')->select();
        }else{
            $data=M('store')->where(array('title'=>array('like','%'.$keyword.'%')))->field('id as storeid,title as storename,thumb,lat,lng')->select();
        }
        foreach ($data as $key => $value) {
            # code...
            $areadata=$Map->get_distance_baidu("driving",$lat.",".$lng,$value['lat'].",".$value['lng']);
            $data[$key]['distance']=sprintf("%.2f", ($areadata['distance']['value']/1000));
            $data[$key]['distancetext']=$areadata['distance']['text'];
        }
        foreach ($data as $value) {
            $distance[] = $value['distance'];
        }
        array_multisort($distance, SORT_ASC, $data);
        $this->assign("list",$data);
        $this->display();
    }

    /**
     * 首页获取门店列表
     */
    public function getstorelist(){
        $Map=A('Api/Map');
        $point = $Map->getlocation();
        $keyword=trim(I('get.keyword'));

        if(empty($point)){
            exit(json_encode(array('code'=>-200,'msg'=>"定位失败")));
        }else{
            $lat=$point['x'];
            $lng=$point['y'];
            $Map=A("Api/Map");
            if(empty($keyword)){
                $areadata=$Map->get_areainfo_baidu_simple($lat.",".$lng);
                $data=M('store')->where(array('servicearea'=>array('like','%,'.$areadata['district'].',%')))->field('id as storeid,title as storename,thumb,lat,lng')->find();
            }else{
                $data=M('store')->where(array('title'=>array('like','%'.$keyword.'%')))->field('id as storeid,title as storename,thumb,lat,lng')->select();
            }
            foreach ($data as $key => $value) {
                # code...
                $areadata=$Map->get_distance_baidu("driving",$lat.",".$lng,$value['lat'].",".$value['lng']);
                $data[$key]['distance']=$areadata['distance']['value'];
                $data[$key]['distancetext']=$areadata['distance']['text'];
            }
            
            foreach ($data as $value) {
                $distance[] = $value['distance'];
            }
            array_multisort($distance, SORT_ASC, $data);
            
            $result=array_slice($data,($p-1)*$num,$num);
            if($result){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$result)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }


    public function outlogin() {
        unset($_SESSION['userid']);
        unset($_SESSION['user']);
    }
    
    public function upload() {
        if (!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->Fupload();
        }
    }
    public function uploadone() {
        if (!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->Fuploadone();
        }
    }
    
    /**
     * 多图片上传
     */
    protected function Fupload() {
        $upload = new \Think\Upload();
        $upload->maxSize = $this->maxSize;
        $upload->exts= $this->allowExtstype;
        $upload->savePath = $this->uploadDir;
        $upload->autoSub= $this->autoSub;
        $upload->saveName = $this->saveRule;
        $upload->subName  = $this->subNameRule;
        $info=$upload->upload();
        if (!$info) {
            echo ($upload->getError());
        } else {
            foreach($info as $file){
                \Admin\Common\CommonController::save_uploadinfo($this->adminid,$this->upload_file_type,$file,$info['name']);
                echo $file['savepath'].$file['savename'];    
            }
        }
    }
    /**
     * 单图片上传
     */
    public function FuploadOne() {
        $upload = new \Think\Upload();
        $upload->maxSize = $this->config['uploadASize'];
        $upload->exts= explode("|",$this->config['uploadAType']);// 设置附件上传类型
        $upload->savePath = $this->imagessavePath;
        $upload->autoSub= $this->autoSub;
        $upload->saveName = $this->saveRule;
        $upload->subName  = $this->subNameRule;
        $info=$upload->uploadOne($_FILES['Filedata']);
        if (!$info) {
            echo ($upload->getError());
        } else {
            $fname=$info['savepath'].$info['savename'];
            $imagearr = explode(',', 'jpg,gif,png,jpeg,bmp,ttf,tif'); 
            $info['ext']= strtolower($info['ext']);
            \Admin\Common\CommonController::save_uploadinfo($this->userid,$this->upload_file_type,$info,$info['name'], $isthumb = 0, $isadmin = 1,  $time = time());
            $isimage = in_array($info['ext'],$imagearr) ? 1 : 0;
            if ($isimage) {
                $image=new \Think\Image();
                $image->Open(".".$fname);

                $thumbsrc=$info['savepath'] . $upload->saveName . "_thumb." . $info['ext'];
                if($this->thumb==1){
                    $fname=$thumbsrc;
                }
                
                if($this->thumb==1){
                    $image->thumb($this->config['thumbW'],$this->config['thumbH'],$this->config['thumbType'])->save(".".$thumbsrc);
                }
                if ($this->water==1) {
                    if($this->thumb==1){
                        $image->text($this->waterText,'./Public/Public/font/STXINGKA.TTF',$this->config['waterFontsize'],$this->config['waterColor'],$this->waterPosition,array(-2,0))->save(".".$thumbsrc); 
                    }else{
                        $image->text($this->waterText,'./Public/Public/font/STXINGKA.TTF',$this->config['waterFontsize'],$this->config['waterColor'],$this->waterPosition,array(-2,0))->save(".".$$fname); 
                    }
                }
                if ($this->water==2) {
                    if($this->thumb==1){
                        $image->water(".".$this->config['waterImg'],$this->waterPosition,$this->config['waterTran'])->save(".".$thumbsrc);
                    }else{
                        $image->water(".".$this->config['waterImg'],$this->waterPosition,$this->config['waterTran'])->save(".".$fname);
                    }
                }   
            }
            echo $fname;
        }
    }
}

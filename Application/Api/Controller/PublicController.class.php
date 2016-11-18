<?php

namespace Api\Controller;

use Api\Common\CommonController;

class PublicController extends CommonController {
    protected $config, $ConfigData;

    private $thumb;//是否开启缩略图
    private $water; //是否加水印(0:无水印,1:水印文字,2水印图片)
    private $waterText;//水印文字
    private $waterTextColor;//水印文字颜色
    private $waterTextFontsize;//水印文字大小
    private $thumbType;//缩略图类型
    private $waterPosition;//水印位置
    private $savePath; //保存位置
    private $userid; //操作用户名
    private $upload_file_type=1;

    public function _initialize(){
        $ConfigData=F("web_config");
        foreach ($ConfigData as $key => $r) {
            $this->config[$r['varname']] = $r['value'];
        }

        $this->userid=empty($_SESSION['userid'])? $_GET['userid'] : $_SESSION['userid'];
        if(empty($this->userid)){
            $this->userid= '1';
        }

        $this->imagessavePath= '/Uploads/images/api/';
        $this->filesavePath= '/Uploads/files/api/';
        $this->videosavePath= '/Uploads/video/api/';
        $this->remotesavePath= '/Uploads/remote/api/';
        $this->scrawlsavePath= '/Uploads/scrawl/api/';
        $this->thumb=$this->config['thumbShow'];
        $this->water=$this->config['waterShow'];
        $this->thumbType=$this->config['thumbType'];
        $this->waterText=$this->config['waterText'];
        $this->waterTextColor=$this->config['waterColor'];
        $this->waterTextFontsize=$this->config['waterFontsize'];
        $this->waterPosition= $this->config['waterPos'];
        $this->filelistpath='/Uploads/files/api/';
        $this->imageslistpath='/Uploads/images/api/';
        $this->saveRule = date('His')."_".rand(1000,9999);
        $this->uploadDir = "/Uploads/images/api/";
        $this->autoSub= true;
        $this->subNameRule = array('date','Ymd');
    }

    

    public function _empty(){ 
        exit(json_encode(array('code'=>-200,'msg'=>"访问出错")));     
    }
    
    /*
    **上传图片
    */
    public function uploadimg(){
        $src='';
        $base_path = "/Uploads/images/api/" . date("Ymd",time()) . "/"; //接收文件目录 
        if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $base_path)){
            mkdir($_SERVER['DOCUMENT_ROOT'] . $base_path,0777);   
        } 
        $base_path=$base_path . date('His')."_".rand(1000,9999);
        $size=$_FILES ['fileField']['size'];
        $ext=getExt($_FILES['fileField']['name']);
        if($ext=="png"||$ext=="jpg"||$ext=="jpeg"||$ext=='gif'){
            if($size>600000){
                $target_path = $base_path .'_src.'.$ext;  
            }else{
                $target_path = $base_path .'.'.$ext;  
            }
        }else{
            $target_path = $base_path .'.'.$ext;  
        }
        if (move_uploaded_file ( $_FILES ['fileField'] ['tmp_name'],".". $target_path )) {  
            $src=$target_path;
            //$src=substr($src, 1);
            if($ext=="png"||$ext=="jpg"||$ext=="jpeg"||$ext=='gif'){
                $thumbsrc=$base_path .'_thumb.'.$ext;  
                $src_img=$_SERVER['DOCUMENT_ROOT'] . $src;
                $dst_img=$_SERVER['DOCUMENT_ROOT'] . $thumbsrc;
                img2thumb($src_img, $dst_img, $width = 640, $height = 320, $cut = 0, $proportion = 0);
                if($size>600000){
                    $thumbsrc1=$base_path .'.'.$ext; 
                    $dst_img1=$_SERVER['DOCUMENT_ROOT'] . $thumbsrc1;
                    img2thumb($src_img, $dst_img1, $width = 640, $height = 320, $cut = 0, $proportion = 0.6);
                    exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$thumbsrc1,'thumb'=>$thumbsrc)));
                }else{
                    exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$src,'thumb'=>$thumbsrc)));
                }
            }else{
                exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$src)));
            }
        }else{
            exit(json_encode(array('code'=>-200,'msg'=>"上传失败")));
        } 
    }
    public function upload() {
        $upload = new \Think\Upload();
        $upload->maxSize = $this->config['uploadASize'];
        $upload->exts= explode("|",$this->config['uploadAType']);// 设置附件上传类型
        $upload->savePath = $this->imagessavePath;
        $upload->autoSub= $this->autoSub;
        $upload->saveName = $this->saveRule;
        $upload->subName  = $this->subNameRule;
        $info=$upload->uploadOne($_FILES['fileField']);
        if (!$info) {
            exit(json_encode(array('code'=>-200,'msg'=>"上传失败")));
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
                if($this->thumb==1){
                    exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$fname,'thumb'=>$thumbsrc)));
                }else{
                    exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$fname)));
                } 
            }else{
                exit(json_encode(array('code'=>200,'msg'=>"上传成功",'imgpath'=>$fname)));
            }
            
            
        }
        
    }

     /*
    **获取地区省份数据
    */
    public function getprovince(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $data = M("area")->where("parentid=0 and status=1")->field('id,name')->select();
        if($data){
            exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
        }else{
            exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
        }
    }
    /*
    **获取子地区数据
    */
    public function getarea(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $parentid=intval(trim($ret['parentid']));
        if($parentid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data = M("area")->where("status=1 and parentid=" . $parentid)->field('id,name')->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
            }
        }
    }
    /*
    **搜索地区
    */
    public function searcharea(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $keyword=trim($ret['keyword']);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        if($p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $where['name']=array('like',"%".$keyword."%");
            $where['staus']=1;
            $data = M("area")->where($where)->field('id,name')->page($p,$num)->select();
            if($data){
                $data=json_encode($data);
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-200,'msg'=>"获取数据失败")));
            }
        }
    }

    /*
    **发送验证码
    */
    public function sendchecknum_phone(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $phone=trim($ret['phone']);
        //$phone="15225071509";
        if($phone==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(!isMobile($phone)){
            exit(json_encode(array('code'=>-200,'msg'=>"手机号码格式错误")));
        }else{
            $m=$phone;
            $code=\Api\Common\CommonController::checknum(4);
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
            $data=array('code'=>$code);
            exit(json_encode(array('code'=>200,'msg'=>"发送成功",'data' => $data)));
        }
    }
    
    /*
    **
    */
    public function checktelverify(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $telverify=trim($ret['telverify']);
        
        $email=trim($ret['email']);

        $verifyset=M('verify')->where(array('type'=>1, 'receiver'=>$email))->order(array('inputtime'=>"desc"))->find();
        $time=time()-$verifyset['expiretime'];
        if($time>0){
            $verify="";
            M('verify')->where(array('type'=>1, 'receiver'=>$email))->save(array(
                'status'=>1
            ));
        }else{
            $verify=$verifyset['verify'];
            M('verify')->where(array('type'=>1, 'receiver'=>$email))->save(array(
                'status'=>1
            ));
        }
        if($email==''||$telverify==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            if(strtolower($telverify)!=strtolower($verify)){
                exit(json_encode(array('code'=>-200,'msg'=>"验证码错误".json_encode($verifyset))));
            }else{
                exit(json_encode(array('code'=>200,'msg'=>"验证码正确".json_encode($verifyset))));
            }
        }
    }
    /*
     * 检查手机号是否注册
     */

    public function checkphone() {
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $phone = trim($ret['phone']);
        $where['phone|username']=$phone;
        $status = M("Member")->where($where)->select();
        if ($status) {
            exit(json_encode(array('code' => -200, 'msg' => "此手机号已被使用")));
        } else {
            exit(json_encode(array('code' => 200, 'msg' => "此手机号未被使用")));
        }
    }
    public function checkarea(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));


        if($lat==''||$lng==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
            $url.="&ak=".$this->config['baidumap_key'];
            $url.="&location=".$lat.",".$lng;
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            if($data['status']==0){
                $result=array();
                $result=$data['result'];
                $province=$result['addressComponent']['province'];
                $city=$result['addressComponent']['city'];
                $district=$result['addressComponent']['district'];

                $province_id=M('area')->where(array('name'=>$province))->getField("id");
                $city_id=M('area')->where(array('name'=>$city))->getField("id");
                $district_id=M('area')->where(array('name'=>$district))->getField("id");
                if($province==$city){
                    $area=$province_id.",".$district_id;
                    $address=$province.$district;
                }else{
                    $area=$province_id.",".$city_id.",".$district_id;
                    $address=$province.$city.$district;
                }
                $areadata=array(
                    'area'=>$area,
                    'address'=>$address,
                    'addressinfo'=>str_replace($address, "", $result['formatted_address'])
                    );
                if($areadata){
                    exit(json_encode(array('code'=>200,'msg'=>"success",'data'=>$areadata))); 
                }else{
                    exit(json_encode(array('code'=>-200,'msg'=>"无法获取位置信息"))); 
                }
            }else{
                exit(json_encode(array('code'=>-200,'msg'=>"无法获取位置信息"))); 
            }
        }
        
    }

    /*
    **用户端版本更新
    */
    public function get_Memberversion(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $type=intval(trim($ret['type']));
        $version=trim($ret['version']);
        if($type==''||$version==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data=M('version')->where(array('type'=>$type,'group_id'=>1))->order(array('inputtime'=>"desc"))->find();
            if($data){
                if($data['version']!=$version){
                    $data1=array('version'=>$data['version'],'url'=>$data['url'],'info'=>$data['info']);
                    exit(json_encode(array('code'=>200,'msg'=>"success",'data' => $data1)));
                }else{
                    exit(json_encode(array('code'=>-200,'msg'=>"没有版本更新")));
                }
                
            }else{
                exit(json_encode(array('code'=>-200,'msg'=>"没有版本更新")));
            }  
        }  
    }
    /*
     **配送端版本更新
     */
    public function get_Runversion(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $type=intval(trim($ret['type']));
        $version=trim($ret['version']);
        if($type==''||$version==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data=M('version')->where(array('type'=>$type,'group_id'=>2))->order(array('inputtime'=>"desc"))->find();
            if($data){
                if($data['version']!=$version){
                    $data1=array('version'=>$data['version'],'url'=>$data['url'],'info'=>$data['info']);
                    exit(json_encode(array('code'=>200,'msg'=>"success",'data' => $data1)));
                }else{
                    exit(json_encode(array('code'=>-200,'msg'=>"没有版本更新")));
                }
                
            }else{
                exit(json_encode(array('code'=>-200,'msg'=>"没有版本更新")));
            }  
        }  
    }

    /*
    **获取服务协议配置
    */
    public function get_service(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $type=trim($ret['type']);

        if($type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data = M("config")->where(array('groupid'=>6,'varname'=>$type))->getField("value");
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
            }
        }
    }
    /*
    **获取分享文案配置
    */
    public function get_share(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $type=trim($ret['type']);

        if($type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $data=array(
                'title'=>M("config")->where(array('groupid'=>7,'varname'=>$type."_title"))->getField("value"),
                'content'=>M("config")->where(array('groupid'=>7,'varname'=>$type."_content"))->getField("value"),
                'image'=>'http://' . $_SERVER['HTTP_HOST'].M("config")->where(array('groupid'=>7,'varname'=>$type."_image"))->getField("value"),
                'link'=>M("config")->where(array('groupid'=>7,'varname'=>$type."_link"))->getField("value")
                );
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
            }
        }
    }
    /*
    **个人偏好配置
    */
    public function get_preference(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $data = M("linkage")->where("catid=1")->field('value,name')->select();
        if($data){
            exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
        }else{
            exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
        }
    }
}
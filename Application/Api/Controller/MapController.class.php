<?php

namespace Api\Controller;

use Api\Common\CommonController;

class MapController extends CommonController {
	protected $Config, $ConfigData, $map_config;

    public function _initialize(){
        parent::_initialize();
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        foreach ($ConfigData as $key => $r) {
            if($r['groupid'] == 5){
                $map_config[$r['varname']] = $r['value'];
            }
        }
        $this->ConfigData=$map_config;
    }
    //导航模式，包括：driving（驾车）、walking（步行）

    /*样例数据
    array(
	    'distance'=>array(
	    	'text'=>'22.5公里',
	    	'value'=>22495
	    ),
	    'duration'=>array(
	    	'text'=>'1.1小时',
	    	'value'=>3853
	    )
    )
    */
    public function get_distance_baidu($mode="driving",$origins,$destinations){
    	$url="http://api.map.baidu.com/direction/v1/routematrix?output=json&origins=".$origins."&destinations=".$destinations;
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&mode=".$mode;
        $result=file_get_contents($url);
        $result=json_decode($result,true);
        if($result['status']==0){
            $data=$result['result']['elements'][0];
        }
        return $data;

    }
    public function get_addressinfo_baidu($latlng){
        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&location=".$latlng;
        $result=file_get_contents($url);
        $result=json_decode($result,true);
        if($result['status']==0){
            $data=$result['result']['formatted_address'];
        }
        return $data;
    }
    public function get_position_complex($area,$addresss){
        $location=array();
        
        $arealist=self::getarealist($area);
        $address=urlencode($arealist.$addresss);

        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&address=".$address;
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        if($data['status']==0){
            $location=$data['result']['location'];
        }
            
        return $location;
    }
    public function get_position_simple($addresss){
        $location=array();
        
        $address=urlencode($addresss);

        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&address=".$address;
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        if($data['status']==0){
            $location=$data['result']['location'];
        }
            
        return $location;
    }
    public function get_areainfo_baidu($latlng){
        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&location=".$latlng;
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        $areadata=array();
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
        }
        return $areadata;
        
        
    }
    public function get_areainfo_baidu_simple($latlng){
        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&location=".$latlng;
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        $areadata=array();
        if($data['status']==0){
            $result=array();
            $result=$data['result'];
            $province=$result['addressComponent']['province'];
            $city=$result['addressComponent']['city'];
            $district=$result['addressComponent']['district'];

            $province_id=M('area')->where(array('name'=>$province))->getField("id");
            $city_id=M('area')->where(array('name'=>$city))->getField("id");
            $district_id=M('area')->where(array('name'=>$district))->getField("id");
            $areadata=array(
                'province'=>$province_id,
                'city'=>$city_id,
                'district'=>$district_id,
                );

        }
        return $areadata;
        
        
    }
    public function getarealist($area) {
        $area1=explode(',', $area);
        $arealist="";
        foreach ($area1 as $key => $value) {
            # code...
            if($key==0){
                $arealist=M('area')->where('id=' . $value)->getField("name");
            }else{
                $list=M('area')->where('id=' . $value)->getField("name");
                $arealist=$arealist . $list;
            }
        }
        return $arealist;
    }
    public function getlocation(){
        $ip=get_client_ip();
        $url="http://api.map.baidu.com/location/ip";
        $url.="?ak=".$this->ConfigData['baidumap_key'];
        $url.="&ip=".$ip;
        $url.="&coor=bd09ll";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        $point=array();
        if($data['status']==0){
            $point=$data['content']['point'];
        }
        return $point;
    }
    public function get_addressinfo_pois($latlng){
        $url="http://api.map.baidu.com/geocoder/v2/?output=json&pois=1";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&location=".$latlng;
        $result=file_get_contents($url);
        $result=json_decode($result,true);
        if($result['status']==0){
            $data=$result['result']['pois'];
        }
        return $data;
    }
    public function get_queryinfo($keyword,$location){
        $url="http://api.map.baidu.com/place/v2/suggestion?output=json&region=全国";
        $url.="&ak=".$this->ConfigData['baidumap_key'];
        $url.="&query=".$keyword;
        $url.="&location=".$location;
        $result=file_get_contents($url);
        $result=json_decode($result,true);
        if($result['status']==0){
            $data=$result['result'];
        }
        return $data;
    }
}
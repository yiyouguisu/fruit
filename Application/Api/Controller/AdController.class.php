<?php

namespace Api\Controller;

use Api\Common\CommonController;

class AdController extends CommonController {

	/* 首页广告 */

    public function get_index_banner() {
    	$ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $storeid=intval(trim($ret['storeid']));

        if(!empty($storeid)){
            $data=M("ad")->where(array('status'=>1,'storeid'=>array('in',array('0',$storeid))))->order(array('isadmin'=>"desc",'listorder'=>"desc",'inputtime'=>"desc"))->field('id,title,image,pid,url,content,description,inputtime,type')->select();
        }else{
            $data=M("ad")->where(array('status'=>1,'storeid'=>0))->order(array('isadmin'=>"desc",'listorder'=>"desc",'inputtime'=>"desc"))->field('id,title,image,pid,url,content,description,inputtime,type')->select();
        }
        
		
        foreach ($data as $key => $value) {
            # code...
            if(!empty($value['pid'])){
                $data[$key]['producturl']='http://' . $_SERVER['HTTP_HOST'].U('Web/Product/infoview',array('id'=>$value['pid']));
            }
            
        }
        if($data){
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }else{
            exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
        }   
    }

    
}

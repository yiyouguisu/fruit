<?php

namespace Api\Controller;

use Api\Common\CommonController;

class IndexController extends CommonController {

    public function index() {
        $waitmoney=M('runermoney_info')->where(array('ruid'=>2,'status'=>array("neq",2)))->sum("no_money");
        echo M('runermoney_info')->_sql();die;
        $data['phone'] = '15225071509';
        $data['password']='123456';

        $keyStr = '2b8L3j5b0H';
        echo "md5密钥：";
        dump(md5($keyStr));

        $postUrl = 'http://' . $_SERVER['HTTP_HOST'].U('Api/Member/test');

        echo "传输json数据：";
        dump(json_encode($data));

        echo "传输加密json数据：";
        dump(json_encode(array('data'=>CommonController::encrypt_des($data))));

        $res = https_request($postUrl,json_encode(array('data'=>CommonController::encrypt_des($data)))); 
        echo "返回解密前json数据：";
        dump($res);

        $res = json_decode($res,true);
        echo "格式化数据：";
        dump($res);

        echo "解密后数据：";
        dump(CommonController::decrypt_des($res['data']));
        
    }

    

   
}
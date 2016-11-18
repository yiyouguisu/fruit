<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class WxController extends CommonController {

    public function _initialize(){
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $this->ConfigData=$ConfigData;
    }

    public function index(){

        $this->display();
    }
    public function menu(){
        if(IS_POST){
            set_time_limit(0);
            dump(json_encode($_POST));
            // $menu = array();
            // if(!empty($data['button'])) {
            //     foreach($data['button'] as &$button) {
            //         $temp = array();
            //         $temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $button['name']);
            //         $temp['name'] = urlencode($temp['name']);
            //         if (empty($button['sub_button'])) {
            //             $temp['type'] = $button['type'];
            //             if($button['type'] == 'view') {
            //                 $temp['url'] = urlencode($button['url']);
            //             } elseif ($button['type'] == 'media_id' || $button['type'] == 'view_limited') {
            //                 $temp['media_id'] = urlencode($button['media_id']);
            //             } else {
            //                 $temp['key'] = urlencode($button['key']);
            //             }
            //         } else {
            //             foreach($button['sub_button'] as &$subbutton) {
            //                 $sub_temp = array();
            //                 $sub_temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $subbutton['name']);
            //                 $sub_temp['name'] = urlencode($sub_temp['name']);
            //                 $sub_temp['type'] = $subbutton['type'];
            //                 if($subbutton['type'] == 'view') {
            //                     $sub_temp['url'] = urlencode($subbutton['url']);
            //                 } elseif ($subbutton['type'] == 'media_id' || $subbutton['type'] == 'view_limited') {
            //                     $sub_temp['media_id'] = urlencode($subbutton['media_id']);
            //                 } else {
            //                     $sub_temp['key'] = urlencode($subbutton['key']);
            //                 }
            //                 $temp['sub_button'][] = $sub_temp;
            //             }
            //         }
            //         $menu['button'][] = $temp;
            //     }
            // }

        }else{

            $this->display();
        }
    }
}
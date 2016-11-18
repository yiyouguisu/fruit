<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ConfigController extends CommonController {

    protected $site_config, $email_config, $template_config, $attach_config, $Config, $ConfigData;

    public function _initialize(){
        $this->Config = D("Config");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=$this->Config->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $this->ConfigData=$ConfigData;
    }
    /**
     * 站点设置
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function index() {
        if (IS_POST) {
             $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 1){
                    $this->site_config[$r['varname']] = $r['value'];
                }
            }
            $this->assign('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
            $this->assign('Site', $this->site_config);
            $this->display();
        }
    }

    /**
     * 邮箱设置
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function email() {
        if (IS_POST) {
             $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 2){
                    $this->email_config[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $this->email_config);
            $this->display();
        }
        
    }

    /**
     * 更新配置
     * @author oydm<389602549@qq.com>  time|20140421
     */
    protected function dosite() {
        if ($this->Config->saveConfig($_POST)) {
            $this->success("更新成功！");
        } else {
            $error = $this->Config->getError();
            $this->error($error ? $error : "配置更新失败！");
        }
    }
      /**
     * 信息模版
     * @author oydm<389602549@qq.com>  time|20140421
     */
     public function template() {
         if (IS_POST) {
            $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 3){
                    $this->template_config[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $this->template_config);
            $this->display();
        }
        
    }
      /**
     * 附件配置
     * @author oydm<389602549@qq.com>  time|20140421
     */
     public function attach() {
         if (IS_POST) {
            $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 4){
                    $this->attach_config[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $this->attach_config);
            $this->display();
        }
        
    }
      /**
     * 短信接口配置
     * @author oydm<389602549@qq.com>  time|20140421
     */
     public function third() {
         if (IS_POST) {
            $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 5){
                    $this->attach_config[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $this->attach_config);
            $this->display();
        }
        
    }
      /**
     * 服务信息模版
     * @author oydm<389602549@qq.com>  time|20140421
     */
     public function service() {
         if (IS_POST) {
            $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 6){
                    $data[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $data);
            $this->display();
        }
        
    }
      /**
     * 分享文案模版
     * @author oydm<389602549@qq.com>  time|20140421
     */
     public function share() {
         if (IS_POST) {
            $this->dosite();
        } else {
            foreach ($this->ConfigData as $key => $r) {
                if($r['groupid'] == 7){
                    $data[$r['varname']] = $r['value'];
                }
            }
            $this->assign('Site', $data);
            $this->display();
        }
        
    }
    public function version(){
        if (IS_POST) {
            if(empty($_POST['member_anzhuo_version'])&&empty($_POST['member_ios_version'])&&empty($_POST['run_anzhuo_version'])&&empty($_POST['run_ios_version'])){
                $this->error("版本号不能为空!");
            }
            if(!empty($_POST['member_anzhuo_version'])){
                $ids[]=M('version')->add(array(
                    'type'=>1,
                    'group_id'=>1,
                    'version'=>$_POST['member_anzhuo_version'],
                    'info'=>$_POST['member_anzhuo_info'],
                    'url'=>$_POST['member_anzhuo_url'],
                    'inputtime'=>time()
                    ));
            }
            if(!empty($_POST['member_ios_version'])){
                $ids[]=M('version')->add(array(
                    'type'=>2,
                    'group_id'=>1,
                    'inputtime'=>time(),
                    'version'=>$_POST['member_ios_version'],
                    'info'=>$_POST['member_ios_info'],
                    'url'=>$_POST['member_ios_url'],
                    ));
            }
            if(!empty($_POST['run_anzhuo_version'])){
                $ids[]=M('version')->add(array(
                    'type'=>1,
                    'group_id'=>2,
                    'version'=>$_POST['run_anzhuo_version'],
                    'info'=>$_POST['run_anzhuo_info'],
                    'url'=>$_POST['run_anzhuo_url'],
                    'inputtime'=>time()
                    ));
            }
            if(!empty($_POST['run_ios_version'])){
                $ids[]=M('version')->add(array(
                    'type'=>2,
                    'group_id'=>2,
                    'inputtime'=>time(),
                    'version'=>$_POST['run_ios_version'],
                    'info'=>$_POST['run_ios_info'],
                    'url'=>$_POST['run_ios_url'],
                    ));
            }
            if($ids){
                $this->success("更新成功！");
            }else{
                $this->error("更新失败！");
            }
        } else {
            $member_anzhuo=M('version')->where('type=1 and group_id=1')->order(array('inputtime'=>'desc'))->find();
            $this->assign('member_anzhuo', $member_anzhuo);
            $member_ios=M('version')->where('type=2 and group_id=1')->order(array('inputtime'=>'desc'))->find();
            $this->assign('member_ios', $member_ios);

            $run_anzhuo=M('version')->where('type=1 and group_id=2')->order(array('inputtime'=>'desc'))->find();
            $this->assign('run_anzhuo', $run_anzhuo);
            $run_ios=M('version')->where('type=2 and group_id=2')->order(array('inputtime'=>'desc'))->find();
            $this->assign('run_ios', $run_ios);
            $this->display();
        }
    }
}
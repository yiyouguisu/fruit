<?php

namespace Admin\Controller;

use Think\Controller;
@ini_set('upload_max_filesize', '50M');
class PublicController extends Controller {

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
        set_time_limit(0);
        $ConfigData=F("web_config");
        foreach ($ConfigData as $key => $r) {
            if($r['groupid'] == 4){
                $this->config[$r['varname']] = $r['value'];
            }
        }
        $this->userid=empty($_SESSION['userid'])? $_GET['userid'] : $_SESSION['userid'];
        if(empty($this->userid)){
            $this->userid= '1';
        }

        $this->imagessavePath= '/Uploads/images/pc/';
        $this->filesavePath= '/Uploads/files/pc/';
        $this->videosavePath= '/Uploads/video/pc/';
        $this->remotesavePath= '/Uploads/remote/pc/';
        $this->scrawlsavePath= '/Uploads/scrawl/pc/';
        $this->thumb=$this->config['thumbShow'];
        $this->water=$this->config['waterShow'];
        $this->thumbType=$this->config['thumbType'];
        $this->waterText=$this->config['waterText'];
        $this->waterTextColor=$this->config['waterColor'];
        $this->waterTextFontsize=$this->config['waterFontsize'];
        $this->waterPosition= $this->config['waterPos'];
        $this->filelistpath='/Uploads/files/pc/';
        $this->imageslistpath='/Uploads/images/pc/';
        $this->saveRule = date('His')."_".rand(1000,9999);
        $this->uploadDir = "/Uploads/images/pc/";
        $this->autoSub= true;
        $this->subNameRule = array('date','Ymd');
        //$this->autologin();
    }

    public function autologin(){
        if (isset($_COOKIE['admin_auto'])&&empty($_SESSION['userid'])) {
            $auto = explode('|', \Admin\Common\CommonController::authcode($_COOKIE['admin_auto']));
            $ip = get_client_ip();
            if ($auto[2] == $ip) {
                $where = array(
                    'id' => $auto[0],
                    'username' => $auto[1],
                );

                $user = M('user')->where($where)->find();
                //设置用户名
                $_SESSION["user"] = $user['username'];
                $_SESSION["userid"] = $user['id'];
                $_SESSION["role"] = $user['role'];
                //标记为后台登陆
                $_SESSION["isadmin"] = true;
                //角色
                $_SESSION["group_id"] = $user['group_id'];

                if($user["role"]==3){
                    $storeid=M('Store')->where(array('uid'=>$user['id']))->getField("id");
                    $_SESSION["storeid"] = $storeid;
                }
                if($user["role"]==5){
                    $companyid=M('Company')->where(array('uid'=>$user['id']))->getField("id");
                    $_SESSION["companyid"] = $companyid;
                }
                $User = D("user")->where(array("id" => $uid))->find();
                $User["role_name"] = M("auth_group")->where("id=" . $User["group_id"])->getField('title');
                $this->assign("User", $User);

                if($User["role"]==3){
                    $store=M('Store')->where(array('uid'=>$User['id']))->find();
                    $this->assign("store", $store);
                }
                $this->redirect('Admin/Index/Index');
                
            }
        }
    }

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

    public function login() {
        if ($_SESSION['userid']&&$_SESSION['group_id']!=9) {
            $this->redirect('Admin/Index/Index');
        } else {
            if (IS_POST) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($this->loginAdmin($username, $password)) {
                    $this->redirect('Admin/Index/Index');
                } else {
                    $this->recordLoginAdmin($_POST['username'], $_POST['password'], 0, "账号密码错误"); //记录登录日志
                    $this->error('登录失败');
                }
            } else {
                unset($_SESSION['userid']);
                unset($_SESSION['user']);
                unset($_SESSION['storeid']);
                unset($_SESSION['group_id']);
                $this->display();
            }
        }
    }
    public function logincangku() {
        if ($_SESSION['userid']&&$_SESSION['group_id']==9) {
            $this->redirect('Admin/Index/Index');
        } else {
            if (IS_POST) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($this->loginAdmin($username, $password)) {
                    if($_SESSION["group_id"]==9){
                        if($_SESSION["storeid"]){
                            $this->redirect('Admin/Public/storehousecompany',array('storeid'=>$_SESSION["storeid"]));
                        }else if($_SESSION["sid"]){
                            $this->redirect('Admin/Public/storehouse',array('sid'=>$_SESSION["sid"]));
                        }
                        
                    }
                } else {
                    $this->recordLoginAdmin($_POST['username'], $_POST['password'], 0, "账号密码错误"); //记录登录日志
                    $this->error('登录失败');
                }
            } else {
                unset($_SESSION['userid']);
                unset($_SESSION['user']);
                unset($_SESSION['storeid']);
                unset($_SESSION['group_id']);
                $this->display();
            }
        }
    }

    public function loginout() {
        if (isset($_SESSION['userid'])) {
            unset($_SESSION['userid']);
            unset($_SESSION['user']);
            unset($_SESSION['storeid']);
            unset($_SESSION['group_id']);
            unset($_SESSION['companyid']);
            unset($_SESSION['role']);
            $this->success('退出登录！', U('Admin/Public/login'));
        } else {
            $this->redirect('Admin/Public/login');
        }
    }

    public function outlogin() {
        unset($_SESSION['userid']);
        unset($_SESSION['user']);
        unset($_SESSION['storeid']);
        unset($_SESSION['group_id']);
        unset($_SESSION['companyid']);
        unset($_SESSION['role']);
    }

    /**
     * 获取菜单导航
     */
    public static function getMenu() {
        $menuid = (int) $_GET['menuid'];
        $menuid = $menuid ? $menuid : cookie("menuid", "", array("prefix" => ""));
        //cookie("menuid",$menuid);
        $db = D("Menu");
        $info = $db->cache(true, 60)->where(array("id" => $menuid))->order(array('listorder'=>'desc'))->getField("id,title,parentid,type,name");
        $find = $db->cache(true, 60)->where(array("parentid" => $menuid, "ismenu" => 1))->order(array('listorder'=>'desc'))->getField("id,title,parentid,type,name");
        if ($find) {
            array_unshift($find, $info[$menuid]);
        } else {
            $find = $info;
        }
        foreach ($find as $k => $v) {
            $find[$k]['data'] = "menuid=$menuid";
        }

        return $find;
    }

    /**
     * 记录后台登陆信息
     * @param string $identifier 用户名
     * @param string $password 用户密码
     * @param int $status 状态 1登录成功 0登录失败
     * @param string $info 备注
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function recordLoginAdmin($identifier, $password, $status, $info = "") {
        M("Loginlog")->add(array(
            "username" => $identifier,
            "logintime" => date("Y-m-d H:i:s"),
            "loginip" => get_client_ip(),
            "status" => $status,
            "password" => "***" . substr($password, 3, 4) . "***",
            "info" => $info
        ));
    }

    /**
     * 登陆后台
     * @param type $identifier 用户ID,或者用户名
     * @param type $password 用户密码，不能为空
     * @return type 成功返回true，否则返回false
     */
    public function loginAdmin($identifier, $password) {
        if (empty($identifier) || empty($password)) {
            return false;
        }
        $user = D("User")->getLocalAdminUser($identifier, $password);
        if (!$user) {
            $this->recordLoginAdmin($identifier, $password, 0, "帐号密码错误");
            return false;
        }
        //判断帐号状态
        if ($user['status'] == 0) {
            //记录登陆日志
            $this->recordLoginAdmin($identifier, $password, 0, "帐号被禁止");
            return false;
        }
        //设置用户名
        $_SESSION["user"] = $user['username'];
        $_SESSION["userid"] = $user['id'];
        $_SESSION["role"] = $user['role'];
        //标记为后台登陆
        $_SESSION["isadmin"] = true;
        //角色
        $_SESSION["group_id"] = $user['group_id'];

        if($user["role"]==3){
            $storeid=M('Store')->where(array('uid'=>$user['id']))->getField("id");
            $_SESSION["storeid"] = $storeid;
        }
        if($user["role"]==5){
            $companyid=M('Company')->where(array('uid'=>$user['id']))->getField("id");
            $_SESSION["companyid"] = $companyid;
        }
        if($user["role"]==4||$user['role']==2||$user['role']==6){
            $_SESSION["storeid"] = $user['storeid'];
        }
        $this->recordLoginAdmin($identifier, $password, 1);


        $autoinfo = $user['id'] . "|" . $user['username'] . "|" . get_client_ip();
        $auto = \Admin\Common\CommonController::authcode($autoinfo, "ENCODE");
        cookie('auto', $auto, C('AUTO_TIME_LOGIN'));
        M("User")->where(array("id" => $user['id']))->save(array(
            "lastlogin_time" => time(),
            "login_num" => $user["login_num"] + 1,
            "lastlogin_ip" => get_client_ip()
        ));
        return true;
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
    public function storehouse() {
        $sid=I('sid',0,intval);
        $_SESSION["sid"]=$sid;
        $uid = $_SESSION["userid"];
        if (!$uid) {
            if (isset($_COOKIE['admin_auto'])) {
                $auto = explode('|', $this->authcode($_COOKIE['admin_auto']));
                $ip = get_client_ip();
                if ($auto[2] == $ip) {
                    $uid=$auto[0];
                }else{
                    $this->error('请先登录！', U('Admin/Public/logincangku')); 
                }
            }else{
               $this->error('请先登录！', U('Admin/Public/logincangku')); 
            }

        } 
        $user = M("user")->alias("a")->where(array("a.id" => $uid))->join("left join zz_auth_group b on a.group_id=b.id")->cache(true)->field("a.*,b.title as role_name")->find();
        $_SESSION["userid"] = $user['id'];
        if($user["group_id"]!=9){
            $this->error('请先登录！', U('Admin/Public/logincangku')); 
        }

        
        $where=array();
        $where['d.storehouse']=$sid;
        $where['d.type']=array('neq',0);
        $where['b.puid']=array('neq',0);
        $where['a.isdo']=0;
        $where['c.close_status']=0;
        $where['c.cancel_status']=0;
        $where['c.package_status']=array('neq',2);
        $where['c.packageerror_status']=array('neq',1);
        $count = M("order_productinfo a")
            ->join("left join zz_order b on a.orderid=b.orderid")
            ->join("left join zz_order_time c on b.orderid=c.orderid")
            ->join("left join zz_product d on a.pid=d.id")
            ->where($where)
            ->distinct("a.orderid")
            ->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order_productinfo a")
            ->join("left join zz_order b on a.orderid=b.orderid")
            ->join("left join zz_order_time c on b.orderid=c.orderid")
            ->join("left join zz_product d on a.pid=d.id")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order(array('b.isspeed'=>'desc','c.distribute_time'=>'desc','a.id'=>'desc'))
            ->group("a.orderid")
            ->field("a.orderid,b.buyerremark")
            ->select();
        foreach ($data as $key => $value)
        {
        	$data[$key]['productinfo']=M("order_productinfo a")
                ->join("left join zz_product d on a.pid=d.id")
                ->where(array('a.orderid'=>$value['orderid'],'d.storehouse'=>$sid,'a.isdo'=>0))
                ->field('a.pid,d.title as productname,a.nums,d.unit,a.product_type,d.standard,d.isout')
                ->select();
        }
        //dump(M("order_productinfo a")->_sql());die;
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function storehousecompany(){
        $storeid=I('storeid',0,intval);
        $_SESSION["storeid"]=$storeid;
        $uid = $_SESSION["userid"];
        if (!$uid) {
            if (isset($_COOKIE['admin_auto'])) {
                $auto = explode('|', $this->authcode($_COOKIE['admin_auto']));
                $ip = get_client_ip();
                if ($auto[2] == $ip) {
                    $uid=$auto[0];
                }else{
                    $this->error('请先登录！', U('Admin/Public/logincangku')); 
                }
            }else{
               $this->error('请先登录！', U('Admin/Public/logincangku')); 
            }

        } 
        $user = M("user")->alias("a")->where(array("a.id" => $uid))->join("left join zz_auth_group b on a.group_id=b.id")->cache(true)->field("a.*,b.title as role_name")->find();
        $_SESSION["userid"] = $user['id'];
        if($user["group_id"]!=9){
            $this->error('请先登录！', U('Admin/Public/logincangku')); 
        }

        
        $where=array();
        $where['b.storeid']=$storeid;
        $where['d.type']=array('eq',0);
        $where['b.puid']=array('neq',0);
        $where['a.isdo']=0;
        $where['c.close_status']=0;
        $where['c.cancel_status']=0;
        $where['c.package_status']=array('neq',2);
        $where['c.packageerror_status']=array('neq',1);
        $count = M("order_productinfo a")
            ->join("left join zz_order b on a.orderid=b.orderid")
            ->join("left join zz_order_time c on b.orderid=c.orderid")
            ->join("left join zz_product d on a.pid=d.id")
            ->where($where)
            ->distinct("a.orderid")
            ->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order_productinfo a")
            ->join("left join zz_order b on a.orderid=b.orderid")
            ->join("left join zz_order_time c on b.orderid=c.orderid")
            ->join("left join zz_product d on a.pid=d.id")
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order(array('b.isspeed'=>'desc','c.distribute_time'=>'desc','a.id'=>'desc'))
            ->group("a.orderid")
            ->field("a.orderid,b.buyerremark")
            ->select();
        
        foreach ($data as $key => $value)
        {
        	$data[$key]['productinfo']=M("order_productinfo a")
                ->join("left join zz_product d on a.pid=d.id")
                ->where(array('a.orderid'=>$value['orderid']))
                ->field('a.pid,d.title as productname,a.nums,d.unit,a.product_type,d.standard,d.isout')
                ->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display("storehouse");
    }
}

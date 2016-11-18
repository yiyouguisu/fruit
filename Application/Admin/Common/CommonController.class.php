<?php

//

namespace Admin\Common;

use Think\Controller;

class CommonController extends Controller {

    public function _initialize() {
        //set_time_limit(0);
        $uid = $_SESSION["userid"];
        
        if (!$uid) {
            if (isset($_COOKIE['admin_auto'])) {
                $auto = explode('|', $this->authcode($_COOKIE['admin_auto']));
                $ip = get_client_ip();
                if ($auto[2] == $ip) {
                    $where = array(
                        'id' => $auto[0],
                        'username' => $auto[1],
                    );

                    $user = M('user')->where($where)->cache(true)->find();

                    $uid=$user['id'];
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
                    
                }
            }else{
               $this->error('请先登录！', U('Admin/Public/Login')); 
            }

        } 
        if($_SESSION['group_id']==9){
            if($_SESSION["storeid"]){
                $this->redirect('Admin/Public/storehousecompany',array('storeid'=>$_SESSION["storeid"]));
            }else if($_SESSION["sid"]){
                $this->redirect('Admin/Public/storehouse',array('sid'=>$_SESSION["sid"]));
            }
        }
        $User = M("user")->alias("a")->where(array("a.id" => $uid))->join("left join zz_auth_group b on a.group_id=b.id")->cache(true)->field("a.*,b.title as role_name")->find();
        $this->assign("User", $User);

        
        if($User["role"]==3){
            $store=M('Store')->where(array('uid'=>$User['id']))->find();
            $this->assign("store", $store);
        }
        if ($uid != 1) {
            $name = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);
            if (!in_array($name, C('AUTH_Filter'))) {
                $AUTH = new \Think\Auth();
                $rules = $AUTH->check($name, $uid);
                if (!$rules) {
                    $this->error('您在当前模块没有权限');
                }
            }
        }
    }


    //判断权限，模版中使用
    function authcheck($name) {
        $name = strtolower($name);
        $uid = $_SESSION["userid"];
        $AUTH = new \Think\Auth();
        $rules = $AUTH->check($name, $uid);
        if ($rules) {
            return TRUE;
        } else {
            return false;
        }
    }

    /**
     * 操作成功跳转的快捷方法
     * @access public
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    public function success($message='',$jumpUrl='',$ajax=false) {
        parent::success($message, $jumpUrl, $ajax);
        $text = "模块/控制器/方法：" . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME . "<br>提示语：" . $message;
        $status = 1;
        $application = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $this->addLogs($text, $status, $application);
    }

     /**
     * 操作错误跳转的快捷方法
     * @access public
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    public function error($message='',$jumpUrl='',$ajax=false) {
        parent::error($message, $jumpUrl, $ajax);
        $text = "模块/控制器/方法：" . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME . "<br>提示语：" . $message;
        $status = 2;
        $application = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $this->addLogs($text, $status, $application);
    }

    /**
     * 写入操作日志
     * @param type $info 操作说明
     * @param type $status 状态,1成功，2失败
     * @param type $application 模块
     */
    final public function addLogs($info, $status, $application) {
        $uid = $_SESSION["userid"];
        if (!$uid) {
            return false;
        }
        M("log")->add(array(
            "uid" => $uid,
            "time" => date("Y-m-d H:i:s"),
            "ip" => get_client_ip(),
            "status" => $status,
            "info" => $info,
            "application" => $application
        ));
    }

    /**
     * 产生随机字符串 
     * 产生一个指定长度的随机字符串,并返回给用户 
     * @access public 
     * @param int $len 产生字符串的位数 
     * @return string 
     */
    function genRandomString($len = 6) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱 
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
    function genNumberString($len = 7) {
        $chars = array(
            "0", "1", "2","3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱 
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
    /**
     * 字符截取
     * @param $string 需要截取的字符串
     * @param $length 长度
     * @param $dot
     */
    function str_cut($sourcestr, $length, $dot = '...') {
        $returnstr = '';
        $i = 0;
        $n = 0;
        $str_length = strlen($sourcestr); //字符串的字节数 
        while (($n < $length) && ($i <= $str_length)) {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码 
            if ($ascnum >= 224) {//如果ASCII位高与224，
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
                $i = $i + 3; //实际Byte计为3
                $n++; //字串长度计1
            } elseif ($ascnum >= 192) { //如果ASCII位高与192，
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
                $i = $i + 2; //实际Byte计为2
                $n++; //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            } else {//其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数计1个
                $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > strlen($returnstr)) {
            $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }
    /**
     * 系统邮件模版
     * @param string $type    邮件模版代号 email_reg注册激活email_password密码找回
     * @param string $username 用户名
     * @param string $link 链接
     * @return string $body 邮件主题
     */
    public function mail_template($type, $username, $link) {
        $body = D("Config")->where('varname="' . $type . '"')->getField("value");
        $body = str_replace("{#username#}", $username, $body);
        $body = str_replace("{#link#}", $link, $body);
        return $body;
    }

    // public function save_uploadinfo($uid,$catid,$uploadinfo,$info,$is_user_upload=0,$user_upload_type=0){
    //     $id=M('attachment')->add(array(
    //         'savename'=>$uploadinfo['savename'],
    //         'ext'=>$uploadinfo['ext'],
    //         'size'=>$uploadinfo['size'],
    //         'url'=>$uploadinfo['savepath'] . $uploadinfo['savename'],
    //         'uid'=>$uid,
    //         'catid'=>$catid,
    //         'info'=>$info,
    //         'inputtime'=>time(),
    //         'ip'=>get_client_ip(),
    //         'name'=>$uploadinfo['name'],
    //         ));
    //     // return $info;

    //     if($id){
    //         if($is_user_upload==1){
    //           M('user_upload')->add(array(
    //             'ext'=>$uploadinfo['ext'],
    //             'size'=>$uploadinfo['size'],
    //             'url'=>$uploadinfo['savepath'] . $uploadinfo['savename'],
    //             'uid'=>$uid,
    //             'catid'=>$user_upload_type,
    //             'inputtime'=>time(),
    //             'ip'=>get_client_ip(),
    //             'filename'=>$uploadinfo['name'],
    //             ));  
    //         }
    //         return 1;
    //     }else{
    //         return 0;
    //     }
    // }
    /**
     * 记录上传的附件信息入库
     * @param array $info 文件信息，数组
     * array(
     *      'name' => '6.jpg',//上传文件名
     *      'type' => 'application/octet-stream',//文件类型
     *      'size' => 112102,//文件大小
     *      'key' => 0,
     *      'extension' => 'jpg',//上传文件后缀
     *      'savepath' => '/home/wwwroot/lvyou.abc3210.com/d/file/content/2012/07/',//文件保存完整路径
     *      'savename' => '5002ba343fc9d.jpg',//保存文件名
     *      'hash' => '77b5118c1722da672b0ddce3c4388e64',
     * )
     * @param type $catid 栏目id
     * @param type $isthumb 是否缩略图
     * @param type $isadmin 是否后台
     * @param type $userid 用户id
     * @param type $time 时间戳
     * @return boolean|int
     */
    public function save_uploadinfo($userid = 0, $catid = 0, array $info, $remark, $isthumb = 0, $isadmin = 0,  $time = 0, $is_user_upload=0, $user_upload_type=0) {
        if (empty($info) || !is_array($info)) {
            return false;
        }
        //后缀强制小写
        $info['ext'] = strtolower($info['ext']);
        //文件保存物理地址
        $filePath = $info['savepath'] . $info['savename'];
        if (empty($filePath)) {
            return false;
        }

        //保存数据
        $data = array(
            //栏目ID
            "catid" => (int) $catid,
            //附件名称
            "name" => $info['name'],
            //附件保存名称
            'savename'=>$info['savename'],
            //附件路径
            "url" => $filePath,
            //附件大小
            "size" => $info['size'],
            //附件扩展名
            "ext" => $info['ext'],
            //是否为图片附件
            "isimage" => in_array($info['ext'], array("jpg", "png", "jpeg", "gif")) ? 1 : 0,
            //是否为缩略图
            "isthumb" => $isthumb,
            //上传用户ID
            "uid" => (int) $userid,
            //是否后台上传
            'isadmin' => $isadmin ? 1 : 0,
            //上传时间
            "inputtime" => $time ? $time : time(),
            //上传IP
            "ip" => get_client_ip(),
            //附件状态
            "status" => 0,
            //附件hash
            "authcode" => $info['md5'],
            //附件备注
            'info'=>$remark,
        );
        if($is_user_upload==1){
          M('user_upload')->add(array(
            'ext'=>$info['ext'],
            'size'=>$info['size'],
            'url'=>$info['savepath'] . $info['savename'],
            'uid'=>(int) $userid,
            'catid'=>$user_upload_type,
            'inputtime'=> $time ? $time : time(),
            'ip'=>get_client_ip(),
            'filename'=>$info['name'],
            ));  
        }
        return M('attachment')->add($data);
    }
    /**
     * 简单对称加密算法之解密
     * @param String $string  明文 或 密文  
     * @param String $operation DECODE表示解密,其它表示加密  
     * @param String $key   密匙
     * @param String $expiry 密文有效期
     * @return String
     */
    public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙  
        $ckey_length = 4;
        // 密匙  
        $key = md5($key ? $key : C('AUTH_KEY'));
        // 密匙a会参与加解密  
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证  
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文  
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙  
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿  
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分  
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符  
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性  
            // substr($result, 0, 10) - time() > 0 验证数据有效性  
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
            // 验证数据有效性，请看未加密明文的格式  
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }
}

<?php

namespace Api\Common;

use Think\Controller;

use Org\Util\Aes;

class CommonController extends Controller {

    public function _initialize() {
        header('Content-type:text/html;charset=UTF-8');
        $data['controller']=CONTROLLER_NAME;
        $data['action']=ACTION_NAME;
        $data['get']=$_SERVER['HTTP_REFERER'];
        $data['post']=$GLOBALS['HTTP_RAW_POST_DATA'];
        $data['inputtime']=time();
        M('debug')->add($data);
        
    }

    public function _empty() {
        $this->error("功能模块正在开发中，敬请关注!");
    }

    public function encrypt($signArr=array()){
        // $Aes = new AES();
        // $Aes->set_key(C("key"));
        // $encode = $Aes->encrypt(json_encode($signArr));
        // \Think\Log::write('接口返回数据：'.json_encode($signArr),\Think\Log::INFO);
        // return $encode;
        return $signArr;
    }
    public function decrypt($signList=''){
        // $Aes = new AES();
        // $Aes->set_key(C("key"));
        // $decode = $Aes->decrypt($signList);
        // \Think\Log::write('接口请求数据：'.$decode,\Think\Log::INFO);
        // return json_decode($decode,true);
        return $signList;
    }
    
    /**
     * 对明文密码，进行加密，返回加密后的密码
     * @param $identifier 为数字时，表示uid，其他为用户名
     * @param type $pass 明文密码，不能为空
     * @return type 返回加密后的密码
     */
    public function encryption($pass, $verify = "") {
        $pass = md5($pass . md5($verify));
        return $pass;
    }
    
  

    /**
     * 产生随机字符串 
     * 产生一个指定长度的随机字符串,并返回给用户 
     * @access public 
     * @param int $len 产生字符串的位数 
     * @return string 
     */
    public function genRandomString($len = 6) {
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
    /**
     * 产生随机字符串 
     * 产生一个指定长度的随机字符串,并返回给用户 
     * @access public 
     * @param int $len 产生字符串的位数 
     * @return string 
     */
    public function genNumberString($len = 7) {
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
    public function str_cut($sourcestr, $length, $dot = '...') {
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

    /**
     * 根据用户输入的Email跳转到相应的电子邮箱首页 
     * @param String $mail  邮箱地址
     * @return String
     */
    public function gotomail($mail) {
        $t = explode('@', $mail);
        $t = strtolower($t[1]);
        if ($t == '163.com') {
            return 'http://mail.163.com';
        } else if ($t == 'vip.163.com') {
            return 'http://vip.163.com';
        } else if ($t == '126.com') {
            return 'http://mail.126.com';
        } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
            return 'http://mail.qq.com';
        } else if ($t == 'gmail.com') {
            return 'http://mail.google.com';
        } else if ($t == 'sohu.com') {
            return 'http://mail.sohu.com';
        } else if ($t == 'tom.com') {
            return 'http://mail.tom.com';
        } else if ($t == 'vip.sina.com') {
            return 'http://vip.sina.com';
        } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
            return 'http://mail.sina.com.cn';
        } else if ($t == 'tom.com') {
            return 'http://mail.tom.com';
        } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
            return 'http://mail.cn.yahoo.com';
        } else if ($t == 'tom.com') {
            return 'http://mail.tom.com';
        } else if ($t == 'yeah.net') {
            return 'http://www.yeah.net';
        } else if ($t == '21cn.com') {
            return 'http://mail.21cn.com';
        } else if ($t == 'hotmail.com') {
            return 'http://www.hotmail.com';
        } else if ($t == 'sogou.com') {
            return 'http://mail.sogou.com';
        } else if ($t == '188.com') {
            return 'http://www.188.com';
        } else if ($t == '139.com') {
            return 'http://mail.10086.cn';
        } else if ($t == '189.cn') {
            return 'http://webmail15.189.cn/webmail';
        } else if ($t == 'wo.com.cn') {
            return 'http://mail.wo.com.cn/smsmail';
        } else {
            return "http://mail." . $t;
        }
    }

    /**
     * 系统邮件模版
     * @param string $type    邮件模版代号 email_reg注册激活email_password密码找回
     * @param string $username 用户名
     * @param string $link 链接
     * @return string $body 邮件主题
     */
    public function mail_template($type, $username, $company, $companynumber, $link) {
        $body = D("Config")->where('varname="' . $type . '"')->getField("value");
        if(!empty($username)){
            $body = str_replace("{#username#}", $username, $body);
        }
        if(!empty($link)){
            $body = str_replace("{#link#}", $link, $body);
        }
        if(!empty($company)){
            $body = str_replace("{#company#}", $company, $body);
        }
        if(!empty($companynumber)){
            $body = str_replace("{#companynumber#}", $companynumber, $body);
        }
        return $body;
    }

    /**
     * 系统邮件发送函数
     * @param string $to    接收邮件者邮箱
     * @param string $name  接收邮件者名称
     * @param string $subject 邮件主题 
     * @param string $body    邮件内容
     * @param string $attachment 附件列表
     * @return boolean 
     */
    public function send_mail($to, $name, $subject = '', $body = '', $attachment = null) {
        $mailconfig = D("Config")->where('groupid=2')->select();
        $config=array();
        foreach ($mailconfig as $r) {
            $config[$r['varname']] = $r['value'];
        }
        import("Vendor.PHPMailer.phpmailer");
        $mail = new \PHPMailer(); //PHPMailer对象
        $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();  // 设定使用SMTP服务
        $mail->SMTPDebug = 1;                     // 关闭SMTP调试功能
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // 启用 SMTP 验证功能
        $mail->SMTPSecure = 'ssl';                 // 使用安全协议
        $mail->Host = $config['mail_server'];  // SMTP 服务器
        $mail->Port = $config['mail_port'];  // SMTP服务器的端口号
        $mail->Username = $config['mail_user'];  // SMTP服务器用户名
        $mail->Password = $config['mail_password'];  // SMTP服务器密码
        $mail->SetFrom($config['mail_from'], $config['mail_fname']);
        $replyEmail = $config['mail_from'];
        $replyName = $config['mail_fname'];
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($to, $name);
        //$mail->AddAttachment($attachment);//附件的路径和附件名称
         //if (is_array($attachment)) { // 添加附件
         //    foreach ($attachment as $file) {
         //        $mail->AddAttachment($file);
         //    }
         //}
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    /**
     * edauth高效可逆随机加密函数
     * @param string $string    明文 或 密文
     * @param $operation：true表示加密,false表示解密
     * @param $key： 密匙
     * @param $outtime：密文有效期, 单位为秒
     * @param $entype：加密方式 有md5和sha1两种 加密解密需要统一使用同一种方式才能正确还原明文
     * @return string 
     */
    function edauth($string, $operation = true, $outtime = 0, $entype = 'md5') {
        $key = md5($key ? $key : C('AUTH_KEY'));
        $key_length = 4;
        if ($entype == 'md5') { //使用md5方式
            $long_len = 32;
            $half_len = 16;
            $entype == 'md5';
        } else { //使用sha1方式
            $long_len = 40;
            $half_len = 20;
            $entype == 'sha1';
        }
        $key = $key != '' ? $key : substr(md5($_SERVER['DOCUMENT_ROOT'] . C('AUTH_KEY') . $_SERVER['REMOTE_ADDR']), 0, 30);
        $fixedKey = hash($entype, $key);
        $egiskeys = md5(substr($fixedKey, $half_len, $half_len));
        $runtoKey = $key_length ? ($operation ? substr(hash($entype, microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
        $keys = hash($entype, substr($runtoKey, 0, $half_len) . substr($fixedKey, 0, $half_len) . substr($runtoKey, $half_len) . substr($fixedKey, $half_len));
        $string = $operation ? sprintf('%010d', $outtime ? $outtime + time() : 0) . substr(md5($string . $egiskeys), 0, $half_len) . $string : base64_decode(substr($string, $key_length));
        $i = 0;
        $result = '';
        $string_length = strlen($string);
        for ($i = 0; $i < $string_length; $i++) {
            $result .= chr(ord($string{$i}) ^ ord($keys{$i % $long_len}));
        }
        if ($operation) {
            return $runtoKey . str_replace('=', '', base64_encode($result));
        } else {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, $half_len) == substr(md5(substr($result, $half_len + 10) . $egiskeys), 0, $half_len)) {
                return substr($result, $half_len + 10);
            } else {
                return '';
            }
        }
    }

    /**
     * 根据身份证号码获取性别
     * @param string $string    身份证号码
     * @return int $sex 性别 1男 2女 0未知
     */
    function getsex($idcard) {
        $sexint = (int) substr($idcard, 16, 1);
        return $sexint % 2 === 0 ? 2 : 1;
    }

    /**
     * 根据身份证号码获取生日
     * @param string $string    身份证号码
     * @return $birthday
     */
    function getbirthday($idcard) {
        $bir = substr($idcard, 6, 8);
        $year = (int) substr($bir, 0, 4);
        $month = (int) substr($bir, 4, 2);
        $day = (int) substr($bir, 6, 2);
        return $year . "-" . $month . "-" . $day;
    }
    
    public function log($data,$type){
        $DataforWrite = $data; 
        $fp=fopen("datalog.txt","a");
        flock($fp,LOCK_EX);//写入锁定
        fwrite($fp,"操作：".$type."\n");//写入类型
        fwrite($fp,date("Y-m-d H:i:s")."\n");//写入时间
        foreach($DataforWrite as $key=>$value)
        {
         fwrite($fp,$key.":".$value."\n");//循环写入数组
        }
        fwrite($fp,"\n");
        fclose($fp);
    }
    public function _log($data,$type){
        $DataforWrite = $data; 
        $fp=fopen("datalog.txt","a");
        flock($fp,LOCK_EX);//写入锁定
        fwrite($fp,"操作：".$type."\n");//写入类型
        fwrite($fp,date("Y-m-d H:i:s")."\n");//写入时间
        fwrite($fp,$DataforWrite."\n");
        fwrite($fp,"\n");
        fclose($fp);
    }
    function https_request($url, $data_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $return_content;
    }
    public function checknum($len = 6) {
        $chars = array(
            "0", "1", "2","3", "4", "5", "6",
            "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱 
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
    function postSMS($url,$data=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    

}

<?php

// +----------------------------------------------------------------------
// * 后台公共文件
// * 主要定义后台公共函数库
// +----------------------------------------------------------------------
/**
 * 下拉菜单选择
 * 根据参数产生下拉选择效果
 * @access public
 * @param int $catid 菜单类型ID
 * @param string $value 菜单值
 */
function linkage($catid, $value = "") {
    $catid = intval($catid);
    $data = M("linkage")->where("catid=" . $catid)->order(array("listorder" => "desc", "id" => "asc"))->select();
    $str = "";
    foreach ($data as $k => $val) {
        if ($val["value"] == $value) {
            $selected = " selected";
        } else {
            $selected = " ";
        }
        $str = $str . "<option value='" . $val["value"] . "' " . $selected . ">" . $val["name"] . "</option>";
    }
    echo $str;
}

/**
 * 获取菜单选择
 * 根据参数产生下拉选择效果
 * @access public
 * @param int $catid 菜单类型ID
 * @param string $value 菜单值
 */
function linkageget($catid, $value) {
    $catid = intval($catid);
    $name = M("linkage")->where("catid=" . $catid . " and value='" . $value . "'")->getField("name");
    echo $name;
}

/**
 * 获取用户名
 * 根据用户ID获取用户名
 * @access public
 * @param int $userid 用户ID
 * @return string $username
 */
function getuser($userid,$type="username") {
    $userid = intval($userid);
    $name = M("member")->where("id=" . $userid)->getField($type);
    return $name;
}


/**
 * 字符截取
 * @param string $string 需要截取的字符串
 * @param int $length 长度
 * @param string $dot
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
 * 隐藏隐私信息
 * @access public
 * @param string $str 用户ID
 * @return string
 */
function substrreplace($str) {
    $len = strlen($str) / 2;
    return substr_replace($str, str_repeat('*', $len), ceil(($len) / 2), $len);
}

/**
 * 数值千位分隔符
 * @access public
 * @param float $num
 * @return string
 */
function kilobit($num) {
    $num = preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $num);
    return $num;
}
function isMobile($num) {
    return preg_match('#^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^18[0-9]\d{8}$#', $num) ? true : false;
}
function getaddress($uid) {
    $arealist=array();
    $uid=  intval($uid);
    $area=M("member_info")->where("uid=".$uid)->getField('area');
    $area1=explode(',', $area);
    foreach ($area1 as $key => $value) {
        # code...
        
        if($key==0){
            $arealist=M('area')->where('id=' . $value)->getField("name");
        }elseif($key==1){
            $list=M('area')->where('id=' . $value)->getField("name");
            $arealist=$arealist . $list;
        }
    }
    return $arealist;
}
function p($array){
    dump($array,true,'<pre>',false);
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
 * @return string $birthday
 */
function getbirthday($idcard) {
    $bir = substr($idcard, 6, 8);
    $year = (int) substr($bir, 0, 4);
    $month = (int) substr($bir, 4, 2);
    $day = (int) substr($bir, 6, 2);
    return $year . "-" . $month . "-" . $day;
}
function age($uid){
    $uid=  intval($uid);
    $birthday=M('member')->where('id='.$uid)->getField("birthday");
    if(!empty($birthday)){
        $YTD=str_replace('/','-',$birthday);
        $YTD = strtotime($YTD);
        $year = date('Y', $YTD);
        if(($month = (date('m') - date('m', $YTD))) < 0){
            $year++;
        }else if ($month == 0 && date('d') - date('d', $YTD) < 0){
            $year++;
        }
        return date('Y') - $year;
    }else{
        return "未知";
    }

}
/**
 * 计算给定时间戳与当前时间相差的时间，并以一种比较友好的方式输出
 * @param int $timestamp [给定的时间戳]
 * @param int $current_time [要与之相减的时间戳，默认为当前时间]
 * @return string            [相差天数]
 */
function tmspan($timestamp,$current_time=0){
    if(!$current_time) $current_time=time();
    $span=$current_time-$timestamp;
    if($span<60){
        return "刚刚";
    }else if($span<3600){
        return intval($span/60)."分钟前";
    }else if($span<24*3600){
        return intval($span/3600)."小时前";
    }else if($span<(7*24*3600)){
        return intval($span/(24*3600))."天前";
    }else{
        return date('Y-m-d H:i:s',$timestamp);
    }
}

//身份证正则
function funccard($str){
    return (preg_match('/\d{17}[\d|x]|\d{15}/',$str))?true:false;
}
/**
 * 检测用户名
 * @author oydm<389602549@qq.com>  time|20140421
 */
function check_username($username) {
    $result = M("Member")->where("username='".$username."'")->find();
    if($result){
        return true;
    }else{
        return false;
    }
}
function check_tuijiancode($tuijian) {
    $result = M("Member")->where("tuijiancode='".$tuijian."'")->find();
    if($result){
        return true;
    }else{
        return false;
    }
}
/**
 * 检测邮箱
 * @author oydm<389602549@qq.com>  time|20140421
 */
function check_email($email) {
    $result = M("Member")->where("email='" . $email."'")->find();
    if($result){
        return false;
    }else{
        return true;
    }
}
/**
 * 检测身份证号码
 * @author
 */
function check_idcard($idcard) {
    $result = M("Member")->where("idcard='" . $idcard."'")->find();
    if($result){
        return false;
    }else{
        return true;
    }
}
/**
 * 检测手机号码
 * @author
 */
function check_phone($phone) {
    $where['phone|username']=$phone;
    $result = M("Member")->where($where)->select();
    if($result){
        return false;
    }else{
        return true;
    }
}

//调取等级信息
function getlevel($uid) {
    $levelConfig = F("levelConfig",'',CACHEDATA_PATH);
    $totalorder_num=M('order a')
        ->join("left join zz_order_time b on a.orderid=b.orderid")
        ->where(array('a.uid'=>$uid,'b.status'=>5))
        ->count();
    $totalorder_money=M('order a')
        ->join("left join zz_order_time b on a.orderid=b.orderid")
        ->where(array('a.uid'=>$uid,'b.status'=>5))
        ->sum("total");
    $totalorder_money=!empty($totalorder_money)?$totalorder_money:0;
    $totalorder_num=!empty($totalorder_num)?$totalorder_num:0;
    $level_total=0;
    $level_money=0;
    foreach ($levelConfig as $key => $value)
    {
    	if($value['total_startlimit']<=$totalorder_money&&$totalorder_money<$value['total_endlimit']){
            $level_money=$key;
        }
        if($value['frequency_startlimit']<=$totalorder_num&&$totalorder_num<$value['frequency_endlimit']){
            $level_total=$key;
        }
    }
    $level=min(array($level_total,$level_money));
    
    return $levelConfig[$level]['title'];
}
function getman($frendnum) {
    if ($frendnum < 6) {
        $num = 6 - $frendnum;
    } elseif ($frendnum < 26) {
        $num = 26 - $frendnum;
    } elseif ($frendnum < 51) {
        $num = 51 - $frendnum;
    } elseif ($frendnum < 101) {
        $num = 101 - $frendnum;
    } elseif ($frendnum < 201) {
        $num = 201 - $frendnum;
    } elseif ($frendnum < 401) {
        $num = 401 - $frendnum;
    } elseif ($frendnum < 1001) {
        $num = 1001 - $frendnum;
    } elseif ($frendnum < 2001) {
        $num = 2001 - $frendnum;
    } elseif ($frendnum < 5001) {
        $num = 5001 - $frendnum;
    } elseif ($frendnum < 10001) {
        $num = 10001 - $frendnum;
    } elseif ($frendnum < 30001) {
        $num = 30001 - $frendnum;
    } elseif ($frendnum < 100001) {
        $num = 100001 - $frendnum;
    } elseif ($frendnum > 100000) {
        $num = 100000 - $frendnum;
    }
    return $num;
}
function phpcode($data,$orderid){
    import("Vendor.phpqrcode.phpqrcode","",".php");
    $filename = "Uploads/ordercode/".$orderid .'.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4;
    $QRcode=new \QRcode();
    $QRcode->png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    $filename="/".$filename;
    return $filename;
}
function fileext($file){
    return pathinfo($file, PATHINFO_EXTENSION);
}
/**
 * 生成缩略图
 * @author yangzhiguo0903@163.com<script cf-hash="f9e31" type="text/javascript">
 * @param string     源图绝对完整地址{带文件名及后缀名}
 * @param string     目标图绝对完整地址{带文件名及后缀名}
 * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
 * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
 * @param int        是否裁切{宽,高必须非0}
 * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
 * @return boolean
 */
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0)
{
    if(!is_file($src_img))
    {
        return false;
    }
    $ot = fileext($dst_img);
    $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
    $srcinfo = getimagesize($src_img);
    $src_w = $srcinfo[0];
    $src_h = $srcinfo[1];
    $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
    $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

    $dst_h = $height;
    $dst_w = $width;
    $x = $y = 0;

    /**
     * 缩略图不超过源图尺寸（前提是宽或高只有一个）
     */
    if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
    {
        $proportion = 1;
    }
    if($width> $src_w)
    {
        $dst_w = $width = $src_w;
    }
    if($height> $src_h)
    {
        $dst_h = $height = $src_h;
    }

    if(!$width && !$height && !$proportion)
    {
        return false;
    }
    if(!$proportion)
    {
        if($cut == 0)
        {
            if($dst_w && $dst_h)
            {
                if($dst_w/$src_w> $dst_h/$src_h)
                {
                    $dst_w = $src_w * ($dst_h / $src_h);
                    $x = 0 - ($dst_w - $width) / 2;
                }
                else
                {
                    $dst_h = $src_h * ($dst_w / $src_w);
                    $y = 0 - ($dst_h - $height) / 2;
                }
            }
            else if($dst_w xor $dst_h)
            {
                if($dst_w && !$dst_h)  //有宽无高
                {
                    $propor = $dst_w / $src_w;
                    $height = $dst_h  = $src_h * $propor;
                }
                else if(!$dst_w && $dst_h)  //有高无宽
                {
                    $propor = $dst_h / $src_h;
                    $width  = $dst_w = $src_w * $propor;
                }
            }
        }
        else
        {
            if(!$dst_h)  //裁剪时无高
            {
                $height = $dst_h = $dst_w;
            }
            if(!$dst_w)  //裁剪时无宽
            {
                $width = $dst_w = $dst_h;
            }
            $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
            $dst_w = (int)round($src_w * $propor);
            $dst_h = (int)round($src_h * $propor);
            $x = ($width - $dst_w) / 2;
            $y = ($height - $dst_h) / 2;
        }
    }
    else
    {
        $proportion = min($proportion, 1);
        $height = $dst_h = $src_h * $proportion;
        $width  = $dst_w = $src_w * $proportion;
    }

    $src = $createfun($src_img);
    $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    if(function_exists('imagecopyresampled'))
    {
        imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    else
    {
        imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    $otfunc($dst, $dst_img);
    imagedestroy($dst);
    imagedestroy($src);
    return true;
}
function getrole($uid) {
    $uid=  intval($uid);
    $group_id=M("member")->where("id=".$uid)->getField('group_id');
    return $group_id;
}
//获取已售量
function get_product_sale($pid) {
    $totalnum=M('takeaway')->where(array('pid'=>$pid))->sum("num");
    $unit=M('product')->where(array('id'=>$pid))->getField("unit");
    return $totalnum.$unit;
}
//获取已售量
function get_store_sale($storeid) {
    $pids=M('product')->where(array('storeid'=>$storeid))->getField("id",ture);
    $totalnum=M('takeaway')->where(array('pid'=>array('in',$pids)))->sum("num");
    return $totalnum;
}

function get_store_stock($storeid){
    $totalstock=M('product')->where(array('storeid'=>$storeid))->sum("stock");
    return $totalstock;
}

function getToken() {
    $url = "https://a1.easemob.com/968968/wjyaochisha/token";
    $body = array(
        "grant_type" => "client_credentials",
        "client_id" => "YXA6lcnnUD_2EeW6Ea_4KlE75A",
        "client_secret" => "YXA6UklicFbessg4gLmhSJHAzxnz2Ws"
    );
    $patoken = json_encode($body);
    $res = postCurl($url, $patoken);
    $tokenResult = array();
    $tokenResult = json_decode($res, true);
    return "Authorization: Bearer " . $tokenResult["access_token"];
}
function postCurl($url, $body, $header = array(), $method = "POST") {
    array_push($header, 'Accept:application/json');
    array_push($header, 'Content-Type:application/json');
    //array_push($header, 'http:multipart/form-data');

    $ch = curl_init();//启动一个curl会话
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, $method, 1);

    switch ($method){
        case "GET" :
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
        case "POST":
            curl_setopt($ch, CURLOPT_POST,true);
            break;
        case "PUT" :
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
        case "DELETE":
            curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
    }

    curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    if (isset($body{3}) > 0) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    $ret = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);
    //clear_object($ch);
    //clear_object($body);
    //clear_object($header);

    if ($err) {
        return $err;
    }

    return $ret;
}
function httppost($reurl='',$rearray=array('')){
    if (empty($reurl) || empty($rearray)) {
        return (false);
    }

    $post_data = $rearray;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $reurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'LightPass/X1 (With CURL)');
    $output = curl_exec($ch);
    if (!$output){
        return (false);
    }else {
        curl_close($ch);
        return $output;

    }
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
function getarea($area) {
    $area1=explode(',', $area);
    foreach ($area1 as $key => $value) {
        # code...
        if($key==0){
            $arealist=M('area')->where('id=' . $value)->getField("name");
        }else{
            $list=M('area')->where('id=' . $value)->getField("name");
            $arealist=$arealist ." ". $list;
        }
    }
    return $arealist;
}
function getposition($area,$addresss){
    $location=array();

    $arealist=getarea($area);
    $address=urlencode($arealist.$addresss);

    $url="http://api.map.baidu.com/geocoder/v2/?ak=3ac1342b68fec1069cd54e9af77f7b05&address=";
    $url=$url.$address;
    $url=$url."&output=json";
    $data=file_get_contents($url);
    $data=json_decode($data,true);
    if($data['status']==0){
        $location=$data['result']['location'];
    }

    return $location;
}
function getlinkage($catid){
    $data=M("linkage")->where("catid=".$catid)->order(array("listorder" => "desc","id" => "asc"))->select();
    return $data;
}
function getsnav($pid){
    $cate=M('productcate')->select();
    $nav=getParentsnav($cate,$pid);
    foreach ($nav as $key => $value) {
        # code...
        if($key==0){
            $navlist=$value['catname'];
        }else{
            $navlist=$navlist . '-' . $value['catname'];
        }
    }
    return $navlist;
}
function getParentsnav($cate,$pid){
    $arr=array();
    foreach ($cate as $v) {
        # code...
        if($v['id']==$pid){
            $arr[]=$v;
            $arr=array_merge(getParentsnav($cate,$v['parentid']),$arr);
        }
    }
    return $arr;
}
function getstoreinfo($storeid,$type='title'){
    $data=M("store")->where("id=".$storeid)->getField($type);
    return $data;
}
/**
 * 导出数据为excel表格
 *@param $data    一个二维数组,结构如同从数据库查出来的数组
 *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
 *@param $filename 下载的文件名
 *@examlpe
 */
function exportexcel($data=array(),$title=array(),$filename='report'){
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出xls 开始
    if (!empty($title)){
        foreach ($title as $k => $v) {
            $title[$k]=iconv("UTF-8", "GB2312",$v);
        }
        $title= implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach ($val as $ck => $cv) {
                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
            }
            $data[$key]=implode("\t", $data[$key]);

        }
        echo implode("\n",$data);
    }
}
function getChild($cate,$pid){
    $arr=array();
    foreach ($cate as $v) {
        # code...
        if($v['parentid']==$pid){
            $arr[]=$v['id'];
            $arr=array_merge(getChild($cate,$v['id']),$arr);
        }
    }
    return $arr;
}

function getsendtime($orderid){
    $order=M('order')->where(array('orderid'=>$orderid))->find();
    $timestamp=$order['sendtime']-$order['inputtime'];
    return intval($timestamp/60)."分钟";
}
function getExt($fileName){
    $ext = explode(".", $fileName);
    $ext = $ext[count($ext) - 1];
    return strtolower($ext);
}
/*
 **获取签到积分
 */
function getsignintegral($lastintegral,$continuesign){
    $signintegral=0;
    if($continuesign>1){
        if($lastintegral<50){
            $signintegral=$lastintegral+5;
        }elseif($lastintegral==50){
            $signintegral=50;
        }
    }else{
        $signintegral=5;
    }
    return $signintegral;
}

function getproduct_type($pid) {
    $pid=  intval($pid);
    $type=M("product")->where("id=".$pid)->getField('type');
    return $type;
}

function getproduct_evaluation($pid,$level=5){
    $pid=  intval($pid);
    $level=  intval($level);
    $total=M('evaluation')->where(array('varname'=>'product','value'=>$pid))->count();
    $five_total=M('evaluation')->where(array('varname'=>'product','value'=>$pid,'total'=>$level))->count();
    $percent=sprintf("%.2f",(($five_total/$total)*100));
    return $percent;
}


function getproduct_evaluationpercent($pid,$level){
    $pid=  intval($pid);
    $total=M('evaluation')->where(array('varname'=>'product','value'=>$pid))->count();
    $five_total=M('evaluation')->where(array('varname'=>'product','value'=>$pid,'total'=>array('in',$level)))->count();

    $percent=sprintf("%.2f",(($five_total/$total)*100));
    return $percent;
}

function getspeedstar($uid){
    $total=M('evaluation a')
        ->join("left join zz_order b on a.value=b.orderid")
        ->where(array('a.varname'=>'order','b.ruid'=>$uid))
        ->count('a.speed');
    $totalnum=M('evaluation a')
        ->join("left join zz_order b on a.value=b.orderid")
        ->where(array('a.varname'=>'order','b.ruid'=>$uid))
        ->sum('a.speed');
    $percent=sprintf("%.2f",(($totalnum/($total*5))*100));
    //if($percent<=20){
    //    $star=1;
    //}elseif($percent<=40&&$percent>20){
    //    $star=2;
    //}elseif($percent<=60&&$percent>40){
    //    $star=3;
    //}elseif($percent<=80&&$percent>60){
    //    $star=4;
    //}elseif($percent<=100&&$percent>80){
    //    $star=5;
    //}
    return $percent;
}

function getattitudestar($uid){
    $total=M('evaluation a')
        ->join("left join zz_order b on a.value=b.orderid")
        ->where(array('a.varname'=>'order','b.ruid'=>$uid))
        ->count();
    $five_total=M('evaluation a')
        ->join("left join zz_order b on a.value=b.orderid")
        ->where(array('a.varname'=>'order','a.attitude'=>5,'b.ruid'=>$uid))
        ->count();
    $percent=sprintf("%.2f",(($five_total/$total)*100));
    //if($percent<=20){
    //    $star=1;
    //}elseif($percent<=40&&$percent>20){
    //    $star=2;
    //}elseif($percent<=60&&$percent>40){
    //    $star=3;
    //}elseif($percent<=80&&$percent>60){
    //    $star=4;
    //}elseif($percent<=100&&$percent>80){
    //    $star=5;
    //}
    return $percent;
}
function PushQueue($mid, $message_type, $receiver, $title,$description, $extras, $type){
    $data = array();
    $data['mid'] = $mid;
    $data['status'] = 0;
    $data['varname'] = $message_type;
    $data['receiver'] = $receiver;
    $data['title'] = $title;
    $data['description']=$description;
    $data['extras'] = $extras;
    $data['inputtime'] = time();
    $data['send_time_start'] = 0;
    $data['send_time_end'] = 0;
    $data['type']=$type;
    M( "sendpush_queue" )->add($data);
}
function getunit($unit){
    $unittext="";
    $ProductUnitConfig=F("ProductUnitConfig",'',CACHEDATA_PATH);
    foreach ($ProductUnitConfig as $value) {
        # code...s
        if($value['value']==$unit){
            $unittext=$value['title'];
        }
    }
    return $unittext;
}
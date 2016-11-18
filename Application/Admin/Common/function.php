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
 * @return string $data
 */
function linkage($catid,$value="") {
    $catid=  intval($catid);
    $data=M("linkage")->where("catid=".$catid)->order(array("listorder" => "desc","id" => "asc"))->select();
    $str="";
    foreach ($data as $k => $val) {
        if($val["value"]==$value){
            $selected=" selected";
        }else{
            $selected=" ";
        }
        $str=$str."<option value='".$val["value"]."' ".$selected.">".$val["name"]."</option>";
    }
    echo $str;
}
/**
 *获取菜单选择
 * 根据参数产生下拉选择效果
 * @access public 
 * @param int $catid 菜单类型ID
 * @param string $value 菜单值
 * @return string $name
 */
function linkageget($catid,$value) {
    $catid=  intval($catid);
    $name=M("linkage")->where("catid=".$catid." and value='".$value."'")->getField("name");
    echo $name;
}
/**
 *获取用户名
 * 根据用户ID获取用户名
 * @access public 
 * @param int $userid 用户ID
 * @return void
 */
function getuser($userid,$type='username') {
    $userid=  intval($userid);
    $name=M("member")->where("id=".$userid)->getField($type);
    if(empty($name)){
        echo "<span style='color: gray'>未填写</span>";
    }else{
        echo $name; 
    }
}

function getAuser($userid,$type='realname') {
    $userid=  intval($userid);
    $name=M("User")->where("id=".$userid)->getField($type);
    if(empty($name)){
        echo "<span style='color: gray'>未填写</span>";
    }else{
        echo $name; 
    }
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
 * 数值千位分隔符
 * @access public 
 * @param float $num
 * @return string
 */
function kilobit($num) {
    $num = preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $num);
    return $num;
}
/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
function getarea($area) {
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
function getaddress($addressid) {
    $addess=M('address')->where(array('id'=>$addressid))->find();
    $area1=explode(',', $addess['area']);
    $arealist="";
    foreach ($area1 as $key => $value) {
        # code...
        if($key==0){
            $arealist=M('area')->where('id=' . $value)->getField("name");
        }else{
            $list=M('area')->where('id=' . $value)->getField("name");
            $arealist=$arealist  . "-" . $list;
        }
    }
    return $arealist.$addess['address'];
}
//function getordersource($ordersource) {
//    $ordersource=intval($ordersource);
//    $ordersourcetext="";
//    switch($ordersource){
//        case 1:
//            $ordersourcetext="手机web";
//            break;
//        case 2:
//            $ordersourcetext="App";
//            break;
//        case 3:
//            $ordersourcetext="饿了么";
//            break;
//        case 4:
//            $ordersourcetext="口碑外卖";
//            break;
//    }
//    return $ordersourcetext;
//}
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
    if ($storeid==0) {
        # code...
        return "企业专区";
    }else{
        $data=M("store")->where("id=".$storeid)->getField($type);
        return $data;
    }
    
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
function getuserinfo($uid){
    $user=M('member')->where('id=' . $uid)->find();
    $user['username']=!empty($user['phone'])?$user['phone']:$user['username'];
    return "<a href=\"javascript:;\" onClick=\"omnipotent('selectid','" . U('Admin/Member/details',array('id'=>$user['id'])) . "','". $user['username'] ."的详细情况',1,800,450)\">" . $user['username'] . "</a>";
}
function getCacheUploadType( )
{
    return array( 1 => "后台编辑器上传", 2 => "后台涂鸦上传", 3 => "后台涂鸦作品", 4 => "后台上传符件", 5 => "后台流转标swf上传", 6 => "会员上传身份证", 7 => "会员资料上传", 8 => "后台上传水印图片" );
}
function getlevelinfo($uid){
    $user=M('member')->where('id=' . $uid)->find();
    if($user['phone_status']==1){
        $phonestr="<img title=\"手机认证已通过\" src=\"/Public/Admin/images/icon/phone.png\"/>";
    }else{
        $phonestr="<img title=\"手机认证未通过\" src=\"/Public/Admin/images/icon/phone_0.png\"/>";
    }
    if($user['realname_status']==1){
        $realnamestr="<img title=\"实名认证已通过\" src=\"/Public/Admin/images/icon/id.png\"/>";
    }else{
        $realnamestr="<img title=\"实名认证未通过\" src=\"/Public/Admin/images/icon/id_0.png\"/>";
    }
    if($user['email_status']==1){
        $emailstr="<img title=\"邮件认证已通过\" src=\"/Public/Admin/images/icon/email.png\"/>";
    }else{
        $emailstr="<img title=\"邮件认证未通过\" src=\"/Public/Admin/images/icon/email_0.png\"/>";
    }
    // if($user['workplace_status']==1){
    //     $workplacestr="<img title=\"职场认证已通过\" src=\"/Public/Admin/images/icon/place.png\"/>";
    // }else{
    //     $workplacestr="<img title=\"职场认证未通过\" src=\"/Public/Admin/images/icon/place_0.png\"/>";
    // }
    return $realnamestr.$phonestr.$emailstr;
}
function getreviewstatus($status){
    $status=intval($status);
    $data="";
    switch ($status) {
        case '0':
            # code...
            $data="<span style=\"color: gray\">待申请</span>";
            break;
        case '1':
            # code...
            $data="<span style=\"color: #266aae\">申请中</span>";
            break;
        case '2':
            # code...
            $data="<span style=\"color: green\">审核成功</span>";
            break;
        case '3':
            # code...
            $data="<span style=\"color: red\">审核失败</span>";
            break;
    }
    return $data;
}
function getDeliveryuser($orderid){
    $ruid=M('order')->where(array('orderid'=>$orderid))->getField("ruid");
    $runame=M('Member')->where(array('id'=>$puid))->getField("nickname");
    if(!empty($runame)){
        return $runame;
    }
}
function getdeliverystatus($status,$orderid=''){
    $status=intval($status);
    $data="";
    switch ($status) {
        case '0':
            # code...
            if($orderid==''){
                $data="<span style=\"color: gray\">未发货</span>";
            }else{
                $data="<span style=\"color: gray\">未发货</br>".getDeliveryuser($orderid)."</span>";
            }
            break;
        case '1':
            # code...
            if($orderid==''){
                $data="<span style=\"color: green\">配送员配送中</span>";
            }else{
                $data="<span style=\"color: green\">配送员配送中</br>".getDeliveryuser($orderid)."</span>";
            }
            break;
        case '2':
            # code...
            if($orderid==''){
                $data="<span style=\"color: green\">配送员确认送达</span>";
            }else{
                $data="<span style=\"color: green\">配送员确认送达</br>".getDeliveryuser($orderid)."</span>";
            }
            break;
        case '3':
            # code...
            if($orderid==''){
                $data="<span style=\"color: green\">收货人确认送达</span>";
            }else{
                $data="<span style=\"color: green\">收货人确认送达</br>".getDeliveryuser($orderid)."</span>";
            }
            break;
        case '4':
            # code...
            if($orderid==''){
                $data="<span style=\"color: green\">已完成</span>";
            }else{
                $data="<span style=\"color: green\">已完成</br>".getDeliveryuser($orderid)."</span>";
            }
            break;
    }
    return $data;
}
function deliverystatus($value="") {
    if($value=='0'){
        $str="<option value='0' selected>未发货</option>";
    }else{
        $str="<option value='0'>未发货</option>";
    }
    if($value=='1'){
        $str.="<option value='1' selected>配送员配送中</option>";
    }else{
        $str.="<option value='1'>配送员配送中</option>";
    }
    if($value=='2'){
        $str.="<option value='2' selected>配送员确认送达</option>";
    }else{
        $str.="<option value='2'>配送员确认送达</option>";
    }
    if($value=='3'){
        $str.="<option value='3' selected>收货人确认送达</option>";
    }else{
        $str.="<option value='3'>收货人确认送达</option>";
    }
    if($value=='4'){
        $str.="<option value='4' selected>已完成</option>";
    }else{
        $str.="<option value='5'>已完成</option>";
    }
    echo $str;
}
function getpaytype($type){
    $type=intval($type);
    $data="";
    switch ($type) {
        case '1':
            # code...
            $data="<span style=\"color: green\">支付宝 </span>";
            break;
        case '2':
            # code...
            $data="<span style=\"color: green\">微信</span>";
            break;
    }
    return $data;
}
function paytype($value="") {
    if($value=='1'){
        $str="<option value='1' selected>支付宝</option>";
    }else{
        $str="<option value='1'>支付宝</option>";
    }
    if($value=='2'){
        $str.="<option value='2' selected>微信</option>";
    }else{
        $str.="<option value='2'>微信</option>";
    }
    echo $str;
}
function getpaystatus($status,$orderid=''){
    $status=intval($status);
    
    $data="";
    switch ($status) {
        case '0':
            # code...
            $data="<span style=\"color: red\">未付款 </span>";
            break;
        case '1':
            # code...
            $data="<span style=\"color: green\">已付款</span>";
            break;
    }
    if(!empty($orderid)){
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        if($order['ordertype']==3){
            $data="<span style=\"color: green\">企业订单</span>";
        }
        if($order['isserviceorder']==1){
            $data="<span style=\"color: green\">售后订单</span>";
        }
    }
    return $data;
}
function paystatus($value="") {
    if($value=='0'){
        $str="<option value='0' selected>未付款</option>";
    }else{
        $str="<option value='0'>未付款</option>";
    }
    if($value=='1'){
        $str.="<option value='1' selected>已付款</option>";
    }else{
        $str.="<option value='1'>已付款</option>";
    }
    echo $str;
}
function getPackageuser($orderid){
    $puid=M('order')->where(array('orderid'=>$orderid))->getField("puid");
    $puname=M('user')->where(array('id'=>$puid))->getField("nickname");
    if(!empty($puname)){
        return $puname;
    }
}
function getpackagestatus($status,$orderid=''){
    $status=intval($status);
    $data="";
    switch ($status) {
        case '0':
            # code...
            if($orderid==''){
                $data="<span style=\"color: gray\">未包装</span>";
            }else{
                $data="<span style=\"color: gray\">未包装</br>".getPackageuser($orderid)."</span>";
            }
            break;
        case '1':
            # code...
            if($orderid==''){
                $data="<span style=\"color: green\">包装中</span>";
            }else{
                $data="<span style=\"color: green\">包装中</br>".getPackageuser($orderid)."</span>";
            }
            break;
        case '2':
            # code...s
            if($orderid==''){
                $data="<span style=\"color: green\">包装完成</span>";
            }else{
                $data="<span style=\"color: green\">包装完成</br>".getPackageuser($orderid)."</span>";
            }
            break;
    }
    return $data;
}
function packagestatus($value="") {
    if($value=='0'){
        $str="<option value='0' selected>未包装</option>";
    }else{
        $str="<option value='0'>未包装</option>";
    }
    if($value=='1'){
        $str.="<option value='1' selected>包装中</option>";
    }else{
        $str.="<option value='1'>包装中</option>";
    }
    if($value=='2'){
        $str.="<option value='1' selected>包装完成</option>";
    }else{
        $str.="<option value='1'>包装完成</option>";
    }
    echo $str;
}

function getorderstatus($status){
    $status=intval($status);
    $data="";
    switch ($status) {
        case '0':
            # code...
            $data="<span style=\"color: gray\">默认 </span>";
            break;
        case '1':
            # code...
            $data="<span style=\"color: green\">用户确认订单成功</span>";
            break;
        case '2':
            # code...
            $data="<span style=\"color: green\">订单审核成功</span>";
            break;
        case '3':
            # code...
            $data="<span style=\"color: gray\">取消订单 </span>";
            break;
        case '4':
            # code...
            $data="<span style=\"color: green\">异常订单 </span>";
            break;
        case '5':
            # code...
            $data="<span style=\"color: green\">已完成 </span>";
            break;
    }
    return $data;
}
function getordersendtime($orderid,$isprint=0){
    $order=M('order')->where(array('orderid'=>$orderid))->find();
    if($order['isserviceorder']==1){
        $result="售后订单";
    }else{
        if($order['ordertype']==2){
            $result="预购订单";
        }elseif($order['ordertype']==3){
            $result="企业订单";
        }else{
            if($order['isspeed']==1){
                $result="极速达订单";
            }else{
                $result=date("Y-m-d H:i:s",$order['start_sendtime']);
                
                if($isprint==0){
                    $result.="</br>";
                }elseif($isprint==1){
                    $result.="</br>";
                    $result.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }elseif($isprint==2){
                    $result.="--";
                }
                $result.=date("Y-m-d H:i:s",$order['end_sendtime']);
            }
        }
    }
    return $result;
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

function getdays($start_time,$end_time){
    $timediff=$end_time-$start_time;
    $count=intval($timediff/(24*3600));
    $date=array();
    for($i=$count;$i>0;$i--){
        $date[]=strtotime("+ {$i}days",$start_time);
    }
    return $date;
}
function getCacheMenu( $update = FALSE )
{
	$CacheMenu = "_menu";
	$Cachelist = f( $CacheMenu );
	if ( !$Cachelist || $update === TRUE )
	{
		$Cachelist = array( );
		$list = m( "Menu" )->order( array( "sort_order" => "ASC" ) )->select( );
		foreach ( $list as $key => $vo )
		{
			$Cachelist[$vo['id']] = $vo;
		}
		f( $CacheMenu, $Cachelist );
	}
	return $Cachelist;
}
function showTask($v,$d){
    if($v=="*") echo $d;
    else echo $v;
}

function showWeek($v){
    $wk = explode(",",$v);
    $week=array("周日","周一","周二","周三","周四","周五","周六");
    $newWeek=array();
    foreach($wk as $va){
        $newWeek[] = $week[$va];
    }
    return implode(",",$newWeek);
}

function week_change($v){
    $wk = explode(",",$v);
    $week=array("SUN","MON","TUE","WED","THU","FRI","SAT");
    foreach($wk as $va){
        $newweekarray[] = $week[$va];
    }
    return implode(",",$newweekarray);
}
function month_change($v){
    $wk = explode(",",$v);
    $month=array(1=>"JAN",2=>"FEB",3=>"MAR",4=>"APR",5=>"MAY",6=>"JUN",7=>"JUL",8=>"AUG",9=>"SEP",10=>"OCT",11=>"NOV",12=>"DEC");
    foreach($wk as $va){
        $newmontharray[] = $month[$va];
    }
    return implode(",",$newmontharray);
}

function removeMu($str){
    if(empty($str)&&$str==='') return "";
    $v = explode(",",$str);
    $v = array_map("intval",$v);
    return implode(",",array_unique($v));
}
function Mheader( $charset )
{
    header( "Content-Type:text/html;charset=".$charset );
}
//function FormHelper( $_obfuscate_6RYLWQÿÿ, $_obfuscate_pnrw_ipvpwÿÿ = "", $_obfuscate_WvCqeRJm = "", $_obfuscate_o5fQ1gÿÿ = "" )
//{
//    $_obfuscate_JQlR2DIÿ = $_obfuscate_6RYLWQÿÿ['lable'] ? "<label for=".$_obfuscate_6RYLWQÿÿ['id'].">".$_obfuscate_6RYLWQÿÿ['label'].":</label>" : "";
//    $_obfuscate_wZ6MPP0ÿ = $_obfuscate_6RYLWQÿÿ['_class'] ? " ".$_obfuscate_6RYLWQÿÿ['_class'] : "";
//    switch ( $_obfuscate_6RYLWQÿÿ['input_type'] )
//    {
//    case 1 :
//        $_obfuscate_swÿÿ = "<input ".$_obfuscate_WvCqeRJm." type=\"text\" class=\"input".$_obfuscate_wZ6MPP0ÿ."\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_pnrw_ipvpwÿÿ."\"/>";
//        break;
//    case 6 :
//        $_obfuscate_bhYV4aHoYOISqaZB9rC3PPH9_gÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzczMQæ— ";
//        $_obfuscate_swÿÿ = "<input ".$_obfuscate_WvCqeRJm." type=\"password\" class=\"input".$_obfuscate_wZ6MPP0ÿ."\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_pnrw_ipvpwÿÿ."\"/>";
//        break;
//    case 7 :
//        $_obfuscate_swÿÿ = "<input ".$_obfuscate_WvCqeRJm." type=\"hidden\" class=\"input".$_obfuscate_wZ6MPP0ÿ."\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_pnrw_ipvpwÿÿ."\"/>";
//        $_obfuscate_cmQgUvTZfUfg_xAxLMmKO2Mzwÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzczNQæ— ";
//        break;
//    case 10 :
//        $_obfuscate_e7PLR79F = "WdatePicker({";
//        $_obfuscate_W2jU = "";
//        if ( $_obfuscate_6RYLWQÿÿ['dateFmt'] )
//        {
//            $_obfuscate_e7PLR79F .= $_obfuscate_W2jU."dateFmt:'".$_obfuscate_6RYLWQÿÿ['dateFmt']."'";
//            $_obfuscate_W2jU = ",";
//        }
//        if ( $_obfuscate_6RYLWQÿÿ['maxDate'] )
//        {
//            $_obfuscate_e7PLR79F .= $_obfuscate_W2jU."maxDate:'".$_obfuscate_6RYLWQÿÿ['maxDate']."'";
//            $_obfuscate_W2jU = ",";
//        }
//        if ( $_obfuscate_6RYLWQÿÿ['minDate'] )
//        {
//            $_obfuscate_e7PLR79F .= $_obfuscate_W2jU."minDate:'".$_obfuscate_6RYLWQÿÿ['minDate']."'";
//            $_obfuscate_W2jU = ",";
//        }
//        $_obfuscate_e7PLR79F .= "})";
//        $_obfuscate_swÿÿ = "<input type=\"text\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_pnrw_ipvpwÿÿ."\" readonly=\"readonly\" class=\"Wdate timeInput_Day".$_obfuscate_wZ6MPP0ÿ."\" onFocus=\"".$_obfuscate_e7PLR79F."\"/>";
//        break;
//    case 2 :
//        $_obfuscate_49arwMR7MJYT7WypV7CzvTY_VAÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzc0Ngæ— ";
//        $_obfuscate_swÿÿ = "<textarea rows=\"5\" cols=\"40\" ".$_obfuscate_WvCqeRJm." class=\"myarea".$_obfuscate_wZ6MPP0ÿ."\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" >".$_obfuscate_pnrw_ipvpwÿÿ."</textarea>";
//        break;
//    case 3 :
//        $_obfuscate_tqhKKMNciC7aKZC84uTptmJDgwÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzc0OQæ— ";
//        $_obfuscate_swÿÿ = "<select name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" class=\"myselect".$_obfuscate_wZ6MPP0ÿ."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\" ".$_obfuscate_WvCqeRJm.">";
//        $_obfuscate_Qd4jduh9 = isset( $_obfuscate_6RYLWQÿÿ['_value'] ) ? $_obfuscate_6RYLWQÿÿ['_value'] : "";
//        $_obfuscate_ZqnChiQÿ = isset( $_obfuscate_6RYLWQÿÿ['text'] ) ? $_obfuscate_6RYLWQÿÿ['text'] : "";
//        $_obfuscate_ = !is_array( $_obfuscate_6RYLWQÿÿ['items'] ) ? explode( ",", $_obfuscate_6RYLWQÿÿ['items'] ) : $_obfuscate_6RYLWQÿÿ['items'];
//        if ( $_obfuscate_6RYLWQÿÿ['default'] )
//        {
//            $_obfuscate_vSmxWcDTu34ÿ = explode( "|||", $_obfuscate_6RYLWQÿÿ['default'] );
//            $_obfuscate_swÿÿ .= "<option selected=\"selected\" value=\"".$_obfuscate_vSmxWcDTu34ÿ[0]."\">".$_obfuscate_vSmxWcDTu34ÿ[1]."</option>";
//        }
//        foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_6Aÿÿ )
//        {
//            $_obfuscate_2XnRpZelzAÿÿ = explode( "|||", $_obfuscate_6Aÿÿ );
//            if ( 1 < count( $_obfuscate_2XnRpZelzAÿÿ ) )
//            {
//                $_obfuscate_Vwty = $_obfuscate_2XnRpZelzAÿÿ[0];
//                $_obfuscate_6Aÿÿ = $_obfuscate_2XnRpZelzAÿÿ[1];
//            }
//            if ( $_obfuscate_Qd4jduh9 == "" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_Vwty;
//            }
//            else if ( $_obfuscate_Qd4jduh9 == "_v" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ;
//            }
//            else
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ[$_obfuscate_Qd4jduh9];
//            }
//            if ( $_obfuscate_ZqnChiQÿ == "" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ;
//            }
//            else if ( $_obfuscate_ZqnChiQÿ == "_key" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_Vwty;
//            }
//            else
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ[$_obfuscate_ZqnChiQÿ];
//            }
//            $_obfuscate_pnrw_ipvpwÿÿ !== "" && $_obfuscate_pnrw_ipvpwÿÿ == $_obfuscate_Vwty ? ( $_obfuscate_swÿÿ .= "<option selected=\"selected\" value=\"".$_obfuscate_Vwty."\">".$_obfuscate_6Aÿÿ."</option>" ) : ( $_obfuscate_swÿÿ .= "<option value=\"".$_obfuscate_Vwty."\">".$_obfuscate_6Aÿÿ."</option>" );
//        }
//        $_obfuscate_swÿÿ .= "</select>";
//        break;
//    case 4 :
//        $_obfuscate_Qd4jduh9 = isset( $_obfuscate_6RYLWQÿÿ['_value'] ) ? $_obfuscate_6RYLWQÿÿ['_value'] : "";
//        $_obfuscate_ZqnChiQÿ = isset( $_obfuscate_6RYLWQÿÿ['text'] ) ? $_obfuscate_6RYLWQÿÿ['text'] : "";
//        $_obfuscate_swÿÿ = "";
//        $_obfuscate_7wÿÿ = 1;
//        $_obfuscate_ = !is_array( $_obfuscate_6RYLWQÿÿ['items'] ) ? explode( ",", $_obfuscate_6RYLWQÿÿ['items'] ) : $_obfuscate_6RYLWQÿÿ['items'];
//        foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_6Aÿÿ )
//        {
//            $_obfuscate_2XnRpZelzAÿÿ = explode( "|||", $_obfuscate_6Aÿÿ );
//            if ( 1 < count( $_obfuscate_2XnRpZelzAÿÿ ) )
//            {
//                $_obfuscate_Vwty = $_obfuscate_2XnRpZelzAÿÿ[0];
//                $_obfuscate_6Aÿÿ = $_obfuscate_2XnRpZelzAÿÿ[1];
//            }
//            if ( $_obfuscate_Qd4jduh9 == "" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_Vwty;
//            }
//            else if ( $_obfuscate_Qd4jduh9 == "_v" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ;
//            }
//            else
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ[$_obfuscate_Qd4jduh9];
//            }
//            if ( $_obfuscate_ZqnChiQÿ == "" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ;
//            }
//            else if ( $_obfuscate_ZqnChiQÿ == "_key" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_Vwty;
//            }
//            else
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ[$_obfuscate_ZqnChiQÿ];
//            }
//            if ( $_obfuscate_pnrw_ipvpwÿÿ === "" )
//            {
//                $_obfuscate_7wÿÿ == 1 ? ( $_obfuscate_swÿÿ .= "<input type=\"radio\" class=\"".$_obfuscate_wZ6MPP0ÿ."\" checked=\"checked\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" ) : ( $_obfuscate_swÿÿ .= "<input type=\"radio\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" );
//            }
//            else
//            {
//                $_obfuscate_pnrw_ipvpwÿÿ == $_obfuscate_Vwty ? ( $_obfuscate_swÿÿ .= "<input type=\"radio\" checked=\"checked\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" ) : ( $_obfuscate_swÿÿ .= "<input type=\"radio\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" );
//            }
//            $_obfuscate_JQlR2DIÿ = "<label for=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\">".$_obfuscate_6Aÿÿ."</label>";
//            $_obfuscate_nB2QbeIoVP8nfFZDNEbWg71WQÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzgwMwæ— ";
//            $_obfuscate_swÿÿ .= $_obfuscate_JQlR2DIÿ;
//            ++$_obfuscate_7wÿÿ;
//        }
//        $_obfuscate_swÿÿ .= "</select>";
//        break;
//    case 5 :
//        $_obfuscate_Qd4jduh9 = isset( $_obfuscate_6RYLWQÿÿ['_value'] ) ? $_obfuscate_6RYLWQÿÿ['_value'] : "";
//        $_obfuscate_ZqnChiQÿ = isset( $_obfuscate_6RYLWQÿÿ['text'] ) ? $_obfuscate_6RYLWQÿÿ['text'] : "";
//        $_obfuscate_swÿÿ = "";
//        $_obfuscate_7wÿÿ = 1;
//        $_obfuscate_ = !is_array( $_obfuscate_6RYLWQÿÿ['items'] ) ? explode( ",", $_obfuscate_6RYLWQÿÿ['items'] ) : $_obfuscate_6RYLWQÿÿ['items'];
//        if ( !is_array( $_obfuscate_pnrw_ipvpwÿÿ ) )
//        {
//            $_obfuscate_pnrw_ipvpwÿÿ = explode( ",", $_obfuscate_pnrw_ipvpwÿÿ );
//        }
//        foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_6Aÿÿ )
//        {
//            $_obfuscate_2XnRpZelzAÿÿ = explode( "|||", $_obfuscate_6Aÿÿ );
//            if ( 1 < count( $_obfuscate_2XnRpZelzAÿÿ ) )
//            {
//                $_obfuscate_Vwty = $_obfuscate_2XnRpZelzAÿÿ[0];
//                $_obfuscate_6Aÿÿ = $_obfuscate_2XnRpZelzAÿÿ[1];
//            }
//            if ( $_obfuscate_Qd4jduh9 == "" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_Vwty;
//            }
//            else if ( $_obfuscate_Qd4jduh9 == "_v" )
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ;
//            }
//            else
//            {
//                $_obfuscate_Vwty = $_obfuscate_6Aÿÿ[$_obfuscate_Qd4jduh9];
//            }
//            if ( $_obfuscate_ZqnChiQÿ == "" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ;
//            }
//            else if ( $_obfuscate_ZqnChiQÿ == "_key" )
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_Vwty;
//            }
//            else
//            {
//                $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ[$_obfuscate_ZqnChiQÿ];
//            }
//            if ( $_obfuscate_pnrw_ipvpwÿÿ === "" )
//            {
//                $_obfuscate_7wÿÿ == 1 ? ( $_obfuscate_swÿÿ .= "<input class=\"".$_obfuscate_wZ6MPP0ÿ."\" type=\"checkbox\" checked=\"checked\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."[]\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" ) : ( $_obfuscate_swÿÿ .= "<input type=\"checkbox\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."[]\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" );
//            }
//            else
//            {
//                in_array( $_obfuscate_Vwty, $_obfuscate_pnrw_ipvpwÿÿ ) ? ( $_obfuscate_swÿÿ .= "<input type=\"checkbox\" checked=\"checked\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."[]\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" ) : ( $_obfuscate_swÿÿ .= "<input type=\"checkbox\" name=\"".$_obfuscate_6RYLWQÿÿ['id']."[]\" value=\"".$_obfuscate_Vwty."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\" ".$_obfuscate_WvCqeRJm.">" );
//            }
//            $_obfuscate_JQlR2DIÿ = "<label for=\"".$_obfuscate_6RYLWQÿÿ['id']."_".$_obfuscate_7wÿÿ."\">".$_obfuscate_6Aÿÿ."</label>";
//            $_obfuscate_swÿÿ .= $_obfuscate_JQlR2DIÿ;
//            ++$_obfuscate_7wÿÿ;
//            $_obfuscate_jqSEJ4H3mgJnKih8rSC2sVtPTQÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzgzOQæ— ";
//        }
//        break;
//    case 11 :
//        if ( !defined( "HAVE_EDITOR" ) )
//        {
//            define( "HAVE_EDITOR", TRUE );
//            $_obfuscate_vPSBFNuO7Qÿÿ = "http://".$_SERVER['HTTP_HOST'].__ROOT__."/statics/editer/uedit";
//            $_obfuscate_swÿÿ = "<script type=\"text/javascript\">window.UEDITOR_HOME_URL = \"".$_obfuscate_vPSBFNuO7Qÿÿ."/\";window.UEDITOR_FILE_URL=\"".__ROOT__."/index.php?appg="._ADMIN_GROUP."&appm=Ueditor&appa=\";var _editorAll=[];</script>\r\n\r\n\t\t\t\t\t<script type=\"text/javascript\" charset=\"utf-8\" src=\"".$_obfuscate_vPSBFNuO7Qÿÿ."/editor_config.js\"></script>\r\n\r\n\t\t\t\t\t<script type=\"text/javascript\" charset=\"utf-8\" src=\"".$_obfuscate_vPSBFNuO7Qÿÿ."/editor_all_min.js\"></script>";
//        }
//        $_obfuscate_QSpsVAzJ = $_obfuscate_6RYLWQÿÿ['config'] ? "<script type=\"text/javascript\">".$_obfuscate_6RYLWQÿÿ['config']."</script>" : "";
//        $_obfuscate_swÿÿ .= "<textarea name=\"".$_obfuscate_6RYLWQÿÿ['id']."\" id=\"".$_obfuscate_6RYLWQÿÿ['id']."\">".$_obfuscate_pnrw_ipvpwÿÿ."</textarea>\r\n\r\n\t\t\t\t\t".$_obfuscate_QSpsVAzJ."\r\n\r\n\t\t\t\t\t<script type=\"text/javascript\">var _editor = UE.getEditor(\"".$_obfuscate_6RYLWQÿÿ['id']."\");_editorAll.push(_editor);</script>";
//    }
//    $_obfuscate_ufJw2tYSLUÿ = $_obfuscate_swÿÿ;
//    if ( !empty( $_obfuscate_o5fQ1gÿÿ ) )
//    {
//        $_obfuscate_ufJw2tYSLUÿ .= "<span class=\"commonToolTip\">".$_obfuscate_o5fQ1gÿÿ."</span>";
//    }
//    $_obfuscate_hy8oAZiZyoguUKZlKRzrt1PG4Qÿÿ = "ä½ å¦ˆBçš„åè§£å•Šå•Šå•ŠMzg1Nwæ— ";
//    return $_obfuscate_ufJw2tYSLUÿ;
//}

function getpackageorder_totalmoney($puid){
    $totalmoney=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.puid'=>$puid,'b.package_status'=>2))->sum("a.total");
    return !empty($totalmoney)?$totalmoney:"0.00";
}
function getpackageorder_totalnum($puid){
    $totalnum=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.puid'=>$puid,'b.package_status'=>2))->count("a.id");
    return !empty($totalnum)?$totalnum:0;
}
function getdeliveryorder_totalmoney($ruid){
    $totalmoney=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$ruid,'b.delivery_status'=>array('in','1,4')))->sum("a.total");
    return !empty($totalmoney)?$totalmoney:"0.00";
}
function getdeliveryorder_totalnum($ruid){
    $totalnum=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$ruid,'b.delivery_status'=>array('in','1,4')))->count("a.id");
    return !empty($totalnum)?$totalnum:0;
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

function getname($id,$type){
    $name="";
    switch ( $type)
    {
        case 1:
            $name=M('store')->where(array('id'=>$id))->getField("title");
            break;
        case 2:
            $name=M('product')->where(array('id'=>$id))->getField("title");
            break;
        case 3:
            $name=M('category')->where(array('id'=>$id))->getField("catname");
            break;
    }
    return $name;
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
function getordersource($ordersource){
    switch ($ordersource)
    {
        case 1:
            $result="[手机web]";
            break;
        case 2:
            $result="[App]";
            break;
        case 3:
            $result="[饿了么]";
            break;
        case 4:
            $result="[口碑外卖]";
            break;
        case 5:
            $result="[售后订单]";
            break;
    }
    return $result;
}
function getpaystyle($orderid){
    $order=M('order')->where(array('orderid'=>$orderid))->find();
    if($order['paystyle']==1){
        $str="<span style=\"color: green\">在线支付</span>";
        if($order['paytype']==1){
            $str.="<span style=\"color: green\">(支付宝)</span>";
        }elseif($order['paytype']==2){
            $str.="<span style=\"color: green\">(微信)</span>";
        }
    }elseif($order['paystyle']==2){
        $str="<span style=\"color: green\">货到付款</span>";
    }elseif($order['paystyle']==3){
        $str="<span style=\"color: green\">钱包支付</span>";
    }elseif($order['paystyle']==4){
        $str="<span style=\"color: green\">优惠券抵扣</span>";
    }
    return $str;
}
function getordertype($orderid){
    $order=M('order')->where(array('orderid'=>$orderid))->find();
    if($order['ordertype']==1){
        if($order['iscontainsweigh']==1){
            $str="称重订单";
        }else{
            $str="一般订单";
        }
        
    }elseif($order['ordertype']==2){
        $str="预购订单";
    }elseif($order['ordertype']==3){
        $str="企业订单";
    }
   return $str;
}
//调取等级信息
function getlevel($uid) {
    $levelConfig = F("levelConfig",'',CACHEDATA_PATH);
    // $totalorder_num_sql="SELECT COUNT(*) AS tp_count FROM zz_order a left join zz_order_time b on a.orderid=b.orderid WHERE ( a.uid = '".$uid."' ) AND ( b.status = 5 ) LIMIT 1";
    // $totalorder_num=M()->query($totalorder_num_sql);

    $totalorder_num=M('order a')
        ->join("left join zz_order_time b on a.orderid=b.orderid")
        ->where(array('a.uid'=>$uid,'b.status'=>5))
        ->count();

    // $totalorder_money_sql="SELECT SUM(total) AS tp_sum FROM zz_order a left join zz_order_time b on a.orderid=b.orderid WHERE ( a.uid = '".$uid."' ) AND ( b.status = 5 ) LIMIT 1";
    // $totalorder_money=M()->query($totalorder_money_sql);
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
function getuid_level($level){
    $levelConfig = F("levelConfig",'',CACHEDATA_PATH);
    $sql="SELECT * FROM ( SELECT a.id, ( SELECT IFNULL(SUM(b1.total), 0) FROM zz_order AS b1 JOIN zz_order_time AS c1 ON b1.orderid = c1.orderid WHERE c1.`status` = 5 AND b1.uid = a.id ) AS totalmoney, ( SELECT count(*) FROM zz_order AS b2 JOIN zz_order_time AS c2 ON b2.orderid = c2.orderid WHERE c2.`status` = 5 AND b2.uid = a.id ) AS totalnum FROM zz_member AS a ) AS u WHERE ( totalmoney < ".$levelConfig[$level]['total_endlimit']." AND totalmoney >= ".$levelConfig[$level]['total_startlimit']." AND totalnum >= ".$levelConfig[$level]['frequency_startlimit']." ) OR ( totalnum < ".$levelConfig[$level]['frequency_endlimit']." AND totalnum >= ".$levelConfig[$level]['frequency_startlimit']." AND totalmoney >= ".$levelConfig[$level]['total_startlimit'].")";
    $data=M()->query($sql);
    $uids=array();
    foreach ($data as $value)
    {
        $uids[]=$value['id'];
    }
                
    return $uids;
}
function ip2area($clientIP){
    $taobaoIP = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$clientIP;
    $IPinfo = json_decode(file_get_contents($taobaoIP));
    $province = $IPinfo->data->region;
    $city = $IPinfo->data->city;
    $data = $province.$city;
    return $data;
}

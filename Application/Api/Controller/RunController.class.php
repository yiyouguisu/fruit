<?php

namespace Api\Controller;

use Api\Common\CommonController;

class RunController extends CommonController {

    /**
     * 配送员上下班
     */
    public function set_workstatus(){
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$uid = intval(trim($ret['uid']));
		$workstatus = intval(trim($ret['workstatus']));

        $user=M('Member')->where(array('id'=>$uid))->find();
		if ($uid == '') {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
			$id=M("member")->where(array('id'=>$uid))->setField("workstatus",$workstatus);
		    if($id){
				exit(json_encode(array('code' => 200, 'msg' => "操作成功")));
		    } else {
				exit(json_encode(array('code' => -202, 'msg' => "操作失败")));
		    }
		}
    }
    /**
     * 邀请的果友记录
     */
    public function get_myinvitelog(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $p = intval(trim($ret['p']));
        $num = intval(trim($ret['num']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$p==''||$num=='') {
            exit(json_encode(array('code' =>-200,'msg'=>"Request parameter is null!")));
        }elseif (empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else {
            $data=M('invite a')->join("left join zz_member b on a.tuid=b.id")->where(array('a.status'=>2,'a.uid'=>$uid,'_string'=>"b.id <> ''"))->page($p,$num)->field('a.tuid as uid,b.username,b.phone,a.inputtime')->select();
            //$data['sql']=M('invite a')->_sql();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"The data is empty!")));
            }
        }

    }
    /**
     * 上传配送员地理位置
     */
    public function send_position(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if($uid==''||$lat==''||$lng==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif (empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $status=M('runerposition')->where(array('uid'=>$uid))->find();
            $position=$lat.",".$lng;
            $Map=A("Api/Map");
            if($status){
                $distanceinfo=$Map->get_distance_baidu("driving",$position,$status['lastposition']);
                $distance=$distanceinfo['distance']['value']/1000;

                $id=M('runerposition')->where(array('uid'=>$uid))->save(array(
                    'lastposition'=>$position,
                    'distance'=>$distance,
                    'updatetime'=>time()
                    ));
                if($distanceinfo['distance']['value']>=500){
                    M('runerposition')->where(array('uid'=>$uid))->setInc("totaldistance",$distance);
                }
                
            }else{
                $id=M('runerposition')->add(array(
                    'uid'=>$uid,
                    'lastposition'=>$position,
                    'distance'=>0,
                    'totaldistance'=>0,
                    'updatetime'=>time()
                    ));
            }

            if($id){
                $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$uid,'b.delivery_status'=>1,'b.status'=>2,'b.error_status'=>0))->select();
                foreach($order as $value){
                    M('order_distance')->where(array('ruid'=>$uid,'orderid'=>$value['orderid']))->delete();
                    M('order_distance')->add(array(
                       'ruid'=>$uid,
                       'orderid'=>$value['orderid'],
                       'lat'=>$lat,
                       'lng'=>$lng,
                       'ip'=>get_client_ip(),
                       'inputtime'=>time()
                       ));
                }
                exit(json_encode(array('code' => 200, 'msg' => "操作成功")));
            } else {
                exit(json_encode(array('code' => -202, 'msg' => "操作失败")));
            }
        }
    }
    /**
     * 配送员上传订单地理位置
     */
    public function send_orderposition(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid = trim($ret['$orderid']);
        $lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('Member')->where(array('orderid'=>$orderid))->find();
        if($uid==''||$orderid==''||$lat==''||$lng==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif (empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif (empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }else{
            $id=M('order_distance')->add(array(
                'ruid'=>$uid,
                'orderid'=>$orderid,
                'lat'=>$lat,
                'lng'=>$lng,
                'ip'=>get_client_ip(),
                'inputtime'=>time()
                ));
            if($id){
                exit(json_encode(array('code' => 200, 'msg' => "操作成功")));
            } else {
                exit(json_encode(array('code' => -202, 'msg' => "操作失败")));
            }
        }
    }
    /**
     * 订单异常处理
     */
    public function order_exceptionhandle(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid = trim($ret['orderid']);
        $thumb = trim($ret['thumb']);
        $content = trim($ret['content']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if ($uid == ''||$orderid==''||$content==''||$thumb=='') {
            exit(json_encode(array('code' =>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['status']==5){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        }elseif($order['error_status']==1){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经申请异常处理")));
        }else {
            $id=M('order')->where(array('orderid'=>$orderid))->save(array(
                'error_content'=>$content,
                'error_thumb'=>$thumb,
                ));
            if($id){
                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'error_status'=>1,
                    'error_applytime'=>time(),
                    ));
                $phone=M('order a')->join("left join zz_store b on a.storeid=b.id")->where(array('a.orderid'=>$orderid))->getField("b.contact");
                $data=json_encode(array('phone'=>$phone,'datas'=>array($orderid),'templateid'=>"74017"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($data);

                $c="尊敬的".  $order['name'] ."，您好！您的订单在".date("Y年m月d日 H时i分s秒") ."提交了异常订单申请,请等待管理员审核。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$order['uid'],
                    'title'=>"订单异常处理",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功提交了异常订单申请,请等待管理员审核。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单异常处理",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单异常处理申请中",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }

    /**
     * 确认配送
     */
    public function order_confirm(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['package_status']!=2){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单尚未包装完成")));
        }elseif($order['delivery_status']==4){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        }elseif($order['ruid']==$uid&&$order['isspeed']!=1){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单您正在处理中")));
        }else{
            $runner=M('Member')->where(array('id'=>$order['ruid']))->find();
            if($order['delivery_status']==1){
                exit(json_encode(array('code'=>-203,'msg'=>"该订单已经被配送员".$runner['realname']."配送中，是否接单？")));
            }else{
                $id=M('order')->where(array('orderid'=>$orderid))->setField("ruid",$uid);
                if($id){
                    M('order_time')->where(array('orderid'=>$orderid))->save(array(
                        'delivery_status'=>1,
                        'delivery_time'=>time(),
                        ));
                    $c="尊敬的".  $order['name'] ."，您好！您的订单在".date("Y年m月d日 H时i分s秒") ."已经开始配送了,请注意查收。";
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$order['uid'],
                        'title'=>"订单开始配送",
                        'description'=>$c,
                        'content'=>$c,
                        'value'=>$order['orderid'],
                        'varname'=>"system",
                        'inputtime'=>time()
                    ));
                    $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功接收了一笔订单，并开始配送。";
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$uid,
                        'title'=>"订单开始配送",
                        'description'=>$c,
                        'content'=>$c,
                        'value'=>$order['orderid'],
                        'varname'=>"runner",
                        'inputtime'=>time()
                    ));
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>0,
                        'title'=>"订单开始配送",
                        'value'=>$order['orderid'],
                        'varname'=>"order",
                        'inputtime'=>time()
                    ));
                    $phone=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.phone");
                    $smsdata=json_encode(array('phone'=>$phone,'datas'=>array($order['orderid'],$user['realname'],$user['phone']),'templateid'=>"74020"));
                    $CCPRest = A("Api/CCPRest");
                    $CCPRest->sendsmsapi($smsdata);

                    $message_type='orderdeliverynotice';
                    $push['title']="订单开始配送提醒";
                    $push['description']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经开始配送，请您耐心等待，蔬果小哥（".$user['realname']."|".$user['phone']."）会尽可能在您规定的时间内送达，在此期间万一需要变更时间或地点，请您及时联系我！";
                    $push['content']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经开始配送，请您耐心等待，蔬果小哥（".$user['realname']."|".$user['phone']."）会尽可能在您规定的时间内送达，在此期间万一需要变更时间或地点，请您及时联系我！";
                    $push['isadmin']=1;
                    $push['inputtime']=time();
                    $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                    $mid = M("Push")->add($push);
                    if ($mid) {
                        $registration_id=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.deviceToken");
                        $extras = array("orderid"=>$order['orderid'],'message_type'=>$message_type);
                        if(!empty($registration_id)){
                            PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                        }
                    }
                    exit(json_encode(array('code'=>200,'msg'=>"发货成功")));
                }else{
                    exit(json_encode(array('code'=>-202,'msg'=>"发货失败")));
                }
            }
            
        }
    }
    /**
     * 确认配送
     */
    public function order_confirmdone(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['package_status']!=2){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单尚未包装完成")));
        }elseif($order['delivery_status']==4){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        }elseif($order['ruid']==$uid&&$order['isspeed']!=1){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单您正在处理中")));
        }else{
            $id=M('order')->where(array('orderid'=>$orderid))->setField("ruid",$uid);
            if($id){
                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'delivery_status'=>1,
                    'delivery_time'=>time(),
                    ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功接收了一笔订单，并开始配送。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单开始配送",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单开始配送",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $phone=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.phone");
                $smsdata=json_encode(array('phone'=>$phone,'datas'=>array($order['orderid'],$user['realname'],$user['phone']),'templateid'=>"74020"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($smsdata);
                $message_type='orderdeliverynotice';
                $push['title']="订单开始配送提醒";
                $push['description']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经开始配送，请您耐心等待，蔬果小哥（".$user['realname']."|".$user['phone']."）会尽可能在您规定的时间内送达，在此期间万一需要变更时间或地点，请您及时联系我！";
                $push['content']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经开始配送，请您耐心等待，蔬果小哥（".$user['realname']."|".$user['phone']."）会尽可能在您规定的时间内送达，在此期间万一需要变更时间或地点，请您及时联系我！";
                $push['isadmin']=1;
                $push['inputtime']=time();
                $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                $mid = M("Push")->add($push);
                if ($mid) {
                    $registration_id=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.deviceToken");
                    $extras = array("orderid"=>$order['orderid'],'message_type'=>$message_type);
                    if(!empty($registration_id)){
                        PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                    }
                }
                exit(json_encode(array('code'=>200,'msg'=>"发货成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"发货失败")));
            }
        }
    }
    /**
     * 确认确认送达
     */
    public function ordersenddone_confirm(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['delivery_status']==4){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        }elseif($order['error_status']==1){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经申请异常处理")));
        }else{
            $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'status'=>5,
                    'delivery_status'=>4,
                    'runer_sendstatus'=>1,
                    'runer_sendtime'=>time(),
                    'donetime'=>time(),
                    ));
            if($id){
                $money=$order['total']-$order['wallet']-$order['discount'];
                $integral=intval($money);
                if($order['paystyle']==2&&$order['pay_status']==0){
                    
                    M('order_time')->where(array('orderid'=>$orderid))->save(array(
                         'pay_status'=>1,
                         'pay_time'=>time(),
                        ));
                    $usr=M('member')->where('id=' . $order['uid'])->field("id,phone")->find();
                    $c="尊敬的". $usr['username'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."一笔订单成功支付".$money."元";
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$usr['id'],
                        'title'=>"订单支付成功 ",
                        'description'=>$c,
                        'content'=>$c,
                        'value'=>$order['orderid'],
                        'varname'=>"system",
                        'inputtime'=>time()
                    ));
                    //$sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
                    //self::sendsms($sms);
                }
                self::update_integral($order['uid'],$integral,1,"获得订单消费积分".$integral,'orderpay');
                $c="尊敬的".  $order['name'] ."，您好！您的订单在".date("Y年m月d日 H时i分s秒") ."已经配送成功,请注意查收。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$order['uid'],
                    'title'=>"订单配送成功",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功配送了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单配送成功",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单配送成功",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                if($order['isserviceorder']==1){
                    M('order_time')->where(array('orderid'=>$order['relationorderid']))->save(array(
                        'status'=>5,
                        'delivery_status'=>4,
                        'runer_sendstatus'=>1,
                        'runer_sendtime'=>time(),
                        'donetime'=>time(),
                    ));
                    $oldorder=M('order')->where(array('orderid'=>$order['relationorderid']))->find();
                    $runercommission_info=M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->find();
                    if($runercommission_info){
                        $status=0;
                        if($runercommission_info['status']==2){
                            $status=1;
                        }elseif($runercommission_info['status']==1){
                            $status=1;
                        }elseif($runercommission_info['status']==0){
                            $status=0;
                        }
                        M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+3,'no_money'=>$runercommission_info['no_money']+3,'status'=>$status));
                    }else{
                        M('runercommission_info')->add(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m"),'ordernum'=>1,'ordermoney'=>3,'no_money'=>3,'status'=>0));
                    }
                    if($oldorder['paystyle']==2){
                        $money=$oldorder['money'];
                        $runermoney_info=M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->find();
                        if($runermoney_info){
                            $status=0;
                            if($runermoney_info['status']==2){
                                $status=1;
                            }elseif($runermoney_info['status']==1){
                                $status=1;
                            }elseif($runermoney_info['status']==0){
                                $status=0;
                            }
                            M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+$money,'no_money'=>$runercommission_info['no_money']+$money,'status'=>$status));
                        }else{
                            M('runermoney_info')->add(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d")),'ordernum'=>1,'ordermoney'=>$money,'no_money'=>$money,'status'=>0));
                        }
                    }
                }else{
                    $runercommission_info=M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->find();
                    if($runercommission_info){
                        $status=0;
                        if($runercommission_info['status']==2){
                            $status=1;
                        }elseif($runercommission_info['status']==1){
                            $status=1;
                        }elseif($runercommission_info['status']==0){
                            $status=0;
                        }
                        M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+3,'no_money'=>$runercommission_info['no_money']+3,'status'=>$status));
                    }else{
                        M('runercommission_info')->add(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m"),'ordernum'=>1,'ordermoney'=>3,'no_money'=>3,'status'=>0));
                    }
                    if($order['paystyle']==2){
                        $money=$order['money'];
                        $runermoney_info=M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->find();
                        if($runermoney_info){
                            $status=0;
                            if($runermoney_info['status']==2){
                                $status=1;
                            }elseif($runermoney_info['status']==1){
                                $status=1;
                            }elseif($runermoney_info['status']==0){
                                $status=0;
                            }
                            M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+$money,'no_money'=>$runercommission_info['no_money']+$money,'status'=>$status));
                        }else{
                            M('runermoney_info')->add(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d")),'ordernum'=>1,'ordermoney'=>$money,'no_money'=>$money,'status'=>0));
                        }
                    }
                }
                $phone=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.phone");
                $smsdata=json_encode(array('phone'=>$phone,'datas'=>array($order['orderid'],$user['realname']),'templateid'=>"64803"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($smsdata);

                $message_type='orderdeliverydonenotice';
                $push['title']="订单配送完成提醒";
                $push['description']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经完成交易，享用完了蔬果，不想来晒一晒，说点什么吗？晒图+评论我们还送您积分哦！";
                $push['content']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经完成交易，享用完了蔬果，不想来晒一晒，说点什么吗？晒图+评论我们还送您积分哦！";
                $push['isadmin']=1;
                $push['inputtime']=time();
                $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                $mid = M("Push")->add($push);
                if ($mid) {
                    $registration_id=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.deviceToken");
                    $extras = array("orderid"=>$order['orderid'],'message_type'=>$message_type);
                    if(!empty($registration_id)){
                        PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                    }
                }
                exit(json_encode(array('code'=>200,'msg'=>"确认送达成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"确认送达失败")));
            }
        }
    }
    /**
     * 接收现场
     */
    public function receivesite(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);
        $thumb=trim($ret['thumb']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if($uid==''||$orderid==''||$thumb==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }
        // elseif($order['delivery_status']==4){
        //     exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        // }
        elseif($order['error_status']==1){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经申请异常处理")));
        }else{
            $id=M('order')->where(array('orderid'=>$orderid))->save(array("receivesite_thumb"=>$thumb));
            if($id){
                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                   'status'=>5,
                   'delivery_status'=>4,
                   'runer_sendstatus'=>1,
                   'runer_sendtime'=>time(),
                   'donetime'=>time(),
                   ));
                $c="尊敬的".  $order['name'] ."，您好！您的订单在".date("Y年m月d日 H时i分s秒") ."已经配送成功,请注意查收。";
                M("message")->add(array(
                   'uid'=>0,
                   'tuid'=>$order['uid'],
                   'title'=>"订单配送成功",
                   'description'=>$c,
                   'content'=>$c,
                   'value'=>$order['orderid'],
                   'varname'=>"system",
                   'inputtime'=>time()
               ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功配送了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单配送成功",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单配送成功",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                if($order['isserviceorder']==1){
                    M('order_time')->where(array('orderid'=>$order['relationorderid']))->save(array(
                         'status'=>5,
                        'delivery_status'=>4,
                        'runer_sendstatus'=>1,
                        'runer_sendtime'=>time(),
                        'donetime'=>time(),
                    ));
                    $oldorder=M('order')->where(array('orderid'=>$order['relationorderid']))->find();
                    $runercommission_info=M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->find();
                    if($runercommission_info){
                        $status=0;
                        if($runercommission_info['status']==2){
                            $status=1;
                        }elseif($runercommission_info['status']==1){
                            $status=1;
                        }elseif($runercommission_info['status']==0){
                            $status=0;
                        }
                        M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+3,'no_money'=>$runercommission_info['no_money']+3,'status'=>$status));
                    }else{
                        M('runercommission_info')->add(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m"),'ordernum'=>1,'ordermoney'=>3,'no_money'=>3,'status'=>0));
                    }
                    if($oldorder['paystyle']==2){
                        $money=$oldorder['money'];
                        $runermoney_info=M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->find();
                        if($runermoney_info){
                            $status=0;
                            if($runermoney_info['status']==2){
                                $status=1;
                            }elseif($runermoney_info['status']==1){
                                $status=1;
                            }elseif($runermoney_info['status']==0){
                                $status=0;
                            }
                            M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+$money,'no_money'=>$runercommission_info['no_money']+$money,'status'=>$status));
                        }else{
                            M('runermoney_info')->add(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d")),'ordernum'=>1,'ordermoney'=>$money,'no_money'=>$money,'status'=>0));
                        }
                    }
                }else{
                    $runercommission_info=M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->find();
                    if($runercommission_info){
                        $status=0;
                        if($runercommission_info['status']==2){
                            $status=1;
                        }elseif($runercommission_info['status']==1){
                            $status=1;
                        }elseif($runercommission_info['status']==0){
                            $status=0;
                        }
                        M('runercommission_info')->where(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m")))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+3,'no_money'=>$runercommission_info['no_money']+3,'status'=>$status));
                    }else{
                        M('runercommission_info')->add(array('ruid'=>$user['id'],'year'=>date("Y"),'month'=>date("m"),'ordernum'=>1,'ordermoney'=>3,'no_money'=>3,'status'=>0));
                    }
                    if($order['paystyle']==2){
                        $money=$order['money'];
                        $runermoney_info=M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->find();
                        if($runermoney_info){
                            $status=0;
                            if($runermoney_info['status']==2){
                                $status=1;
                            }elseif($runermoney_info['status']==1){
                                $status=1;
                            }elseif($runermoney_info['status']==0){
                                $status=0;
                            }
                            M('runermoney_info')->where(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d"))))->save(array('ordernum'=>$runercommission_info['ordernum']+1,'ordermoney'=>$runercommission_info['ordermoney']+$money,'no_money'=>$runercommission_info['no_money']+$money,'status'=>$status));
                        }else{
                            M('runermoney_info')->add(array('ruid'=>$user['id'],'date'=>strtotime(date("Y")."-".date("m")."-".date("d")),'ordernum'=>1,'ordermoney'=>$money,'no_money'=>$money,'status'=>0));
                        }
                    }
                }
                $phone=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.phone");
                $smsdata=json_encode(array('phone'=>$phone,'datas'=>array($order['orderid'],$user['realname']),'templateid'=>"64803"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($smsdata);

                $message_type='orderdeliverydonenotice';
                $push['title']="订单配送完成提醒";
                $push['description']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经完成交易，享用完了蔬果，不想来晒一晒，说点什么吗？晒图+评论我们还送您积分哦！";
                $push['content']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已经完成交易，享用完了蔬果，不想来晒一晒，说点什么吗？晒图+评论我们还送您积分哦！";
                $push['isadmin']=1;
                $push['inputtime']=time();
                $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                $mid = M("Push")->add($push);
                if ($mid) {
                    $registration_id=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.deviceToken");
                    $extras = array("orderid"=>$order['orderid'],'message_type'=>$message_type);
                    if(!empty($registration_id)){
                        PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                    }
                }
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }
    /**
     * 无人签收
     */
    public function order_noconfirm(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['delivery_status']==4){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单已经配送完成")));
        }else{
            $id=M('order')->where(array('orderid'=>$orderid))->setInc("noconfirm_num");
            if($id){
                $c="[蔬果先生]尊敬的客户，你的订单(".  $order['orderid'] .")已由蔬果小哥（".  $user['nickname'] ."|".  $user['phone'] ."）配送到订单指定位置，但是我们无法与您取得联系，小哥束手无策，请您尽快与我联系！";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$order['uid'],
                    'title'=>"订单无人签收,",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功提交了一笔无人签收订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单无人签收",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单无人签收",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $phone=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.phone");
                $smsdata=json_encode(array('phone'=>$phone,'datas'=>array($order['orderid'],$user['realname'],$user['phone']),'templateid'=>"74021"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($smsdata);

                $message_type='ordernoconfirmnotice';
                $push['title']="订单无人接收提醒";
                $push['description']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已由蔬果小哥（".$user['realname']."|".$user['phone']."）配送到订单指定位置，但是我们无法与您取得联系，小哥束手无策，请您尽快与我联系！";
                $push['content']="[蔬果先生]尊敬的客户，你的订单(".$order['orderid'].")已由蔬果小哥（".$user['realname']."|".$user['phone']."）配送到订单指定位置，但是我们无法与您取得联系，小哥束手无策，请您尽快与我联系！";
                $push['isadmin']=1;
                $push['inputtime']=time();
                $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
                $mid = M("Push")->add($push);
                if ($mid) {
                    $registration_id=M('order a')->join("left join zz_member b on a.uid=b.id")->where(array('a.orderid'=>$order['orderid']))->getField("b.deviceToken");
                    $extras = array("orderid"=>$order['orderid'],'message_type'=>$message_type);
                    if(!empty($registration_id)){
                        PushQueue($mid,$message_type,$registration_id, $push['title'],$push['description'], serialize($extras),1);
                    }
                }
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }
    /**
     * 我的订单
     */
    public function orderlist(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $type=trim($ret['type']);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $starttime=intval(trim($ret['starttime']));
        $endtime=intval(trim($ret['endtime']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$type == ''||$p==''||$num=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where=array();
            $order=array();
            $field=array();

            $starttime_default=mktime(0,0,0,intval(date("m",time())),intval(date("d",time())),intval(date("Y",time())));
            $endtime_default=mktime(23,59,59,intval(date("m",time())),intval(date("d",time())),intval(date("Y",time())));
            
            switch ($type) {
                case 'error':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.status'=>4);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.error_donetime']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.error_donetime']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.error_donetime'=>'desc');
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,a.discount,a.wallet,a.money,a.total,a.inputtime,a.ordertype,a.paystyle,a.paytype,a.buyerremark,a.start_sendtime,a.end_sendtime,c.status,a.ordertype,a.yes_money,a.wait_money,a.error_content,a.error_thumb,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng');
                    break;
                case 'delivery':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.package_status'=>2,'c.delivery_status'=>1,'c.status'=>2);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.delivery_time']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.delivery_time']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.delivery_time'=>'asc');
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,a.discount,a.wallet,a.money,a.total,a.inputtime,a.ordertype,a.paystyle,a.paytype,a.buyerremark,a.start_sendtime,a.end_sendtime,c.status,a.ordertype,a.yes_money,a.wait_money,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.error_content,a.error_thumb,a.receivesite_thumb');
                    break;
                case 'done':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.package_status'=>2,'c.delivery_status'=>4,'c.status'=>5);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.runer_sendtime']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.delivery_time']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.runer_sendtime'=>'desc');
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,a.discount,a.wallet,a.money,a.total,a.inputtime,a.ordertype,a.paystyle,a.paytype,a.buyerremark,a.start_sendtime,a.end_sendtime,c.status,a.ordertype,a.yes_money,a.wait_money,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.error_content,a.error_thumb,a.receivesite_thumb');
                    break;
            }

            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")
                              ->join("left join zz_order_time c on a.orderid=c.orderid")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->page($p,$num)
                              ->select();
            //$data['sql']=M('order a')->_sql();
            foreach ($data as $key => $value) {
                # code...
                $data[$key]['area']=getarea($value['area']);
                $data[$key]['productinfo']=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$value['orderid']))->field("a.pid,a.nums,b.thumb,b.title,b.description,b.nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh")->select();
            }
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    /**
     * 订单详情
     */
    public function ordershow(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $orderid = trim($ret['orderid']);
        $type=trim($ret['type']);

        if ($orderid==''||$type == '') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array();
            switch ($type) {
                case 'error':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.ordertype,c.status,c.package_status,c.delivery_status,a.yes_money,a.wait_money,a.error_content,a.error_thumb,a.noconfirm_num');
                    break;
                case 'delivery':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.ordertype,c.status,c.package_status,c.delivery_status,a.yes_money,a.wait_money,a.noconfirm_num,a.error_content,a.error_thumb,a.receivesite_thumb');
                    break;
                case 'done':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.ordertype,c.status,c.package_status,c.delivery_status,a.yes_money,a.wait_money,a.noconfirm_num,a.error_content,a.error_thumb,a.receivesite_thumb');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.orderid'=>$orderid))->field($field)->find();
            //$data['sql']=M('order a')->_sql();
            $data['area']=getarea($data['area']);
            $data['productinfo']=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$data['orderid']))->field("a.pid,a.nums,b.thumb,b.title,b.description,b.nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh")->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    /**
     * 订单详情地图模式
     */
    public function ordershowmap(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $orderid = trim($ret['orderid']);

        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array('a.orderid,a.isspeed,a.ruid,a.start_sendtime,a.end_sendtime,a.name,a.tel,a.lat,a.lng');
            $data=M('order a')->where(array('a.orderid'=>$orderid))->field($field)->find();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    /**
     * 我的订单地图模式
     */
    public function ordermap(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $type=trim($ret['type']);
        $starttime=intval(trim($ret['starttime']));
        $endtime=intval(trim($ret['endtime']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$type == '') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where=array();

            $starttime_default=mktime(0,0,0,intval(date("m",time())),intval(date("d",time())),intval(date("Y",time())));
            $endtime_default=mktime(23,59,59,intval(date("m",time())),intval(date("d",time())),intval(date("Y",time())));
            

            $field=array('a.orderid,a.isspeed,a.ruid,a.start_sendtime,a.end_sendtime,a.name,a.tel,a.lat,a.lng');
            switch ($type) {
                case 'error':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.status'=>4);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.error_donetime']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.error_donetime']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.error_donetime'=>'desc');
                    break;
                case 'delivery':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.package_status'=>2,'c.delivery_status'=>1,'c.status'=>2);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.delivery_time']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.delivery_time']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.delivery_time'=>'asc');
                    break;
                case 'done':
                    # code...
                    $where=array('a.ruid'=>$uid,'c.package_status'=>2,'c.delivery_status'=>4,'c.status'=>5);
                    if(empty($starttime)||empty($endtime)){
                        $where['c.runer_sendtime']=array(array('egt',$starttime_default),array('elt',$endtime_default));
                    }else{
                        $where['c.delivery_time']=array(array('egt',$starttime),array('elt',$endtime));
                    }
                    $order=array('c.runer_sendtime'=>'desc');
                    break;
            }

            $data=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where($where)->order($order)->field($field)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    public function ordernum(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == '') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $storeid=M('store_member')->where(array('ruid'=>$uid))->getField("storeid");

            $where['a.storeid']=$storeid;
            $where['b.status']=2;
            $where['b.package_status']=2;
            $where['b.delivery_status']=0;
            $where['b.close_status']=0;
            $where['b.cancel_status']=0;

            $totalnum=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
            $where['a.isspeed']=1;
            $speednum=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
            
            $data=array(
                'totalnum'=>!empty($totalnum)?$totalnum:0,
                'speednum'=>!empty($speednum)?$speednum:0
                );
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }
        
    }
    public function commission(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $year=intval(trim($ret['year']));
        $month=intval(trim($ret['month']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$year == ''||$month=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $data = M("runercommission_info a")->join("left join zz_member b on a.ruid=b.id")->where(array('a.ruid'=>$uid,'a.year'=>$year,'a.month'=>$month))->field("a.ordernum,a.ordermoney,a.yes_money,a.no_money,a.status,a.last_paytime")->find();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such commission information!")));
            }
        }
    }

    public function get_orderstatus(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $orderid = trim($ret['orderid']);

        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array('a.orderid,c.status,c.package_status,c.delivery_status');
            
            $data=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.orderid'=>$orderid))->field($field)->find();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    /*
     **更新用户积分
     * uid 用户id
     * integral  操作积分
     * type 1 增 2减
     * content 积分变更说明
     */ 
    public static function update_integral($uid,$integral,$type,$content,$update_type){
        if($type==1){
            M('integral')->where(array('uid'=>$uid))->setInc("useintegral",intval($integral));
            M('integral')->where(array('uid'=>$uid))->setInc("totalintegral",intval($integral));
        }elseif($type==2){
            M('integral')->where(array('uid'=>$uid))->setDec("useintegral",intval($integral));
            M('integral')->where(array('uid'=>$uid))->setInc("payed",intval($integral));
        }
        
        M('integrallog')->add(array(
          'uid'=>$uid,
          'paytype'=>$type,
          'content'=>$content,
          'integral'=>$integral,
          'varname'=>$update_type,
          'useintegral'=>M('integral')->where(array('uid'=>$uid))->getField('useintegral'),
          'totalintegral'=>M('integral')->where(array('uid'=>$uid))->getField('totalintegral'),
          'inputtime'=>time()
        )); 
        self::addmessage($uid,$content,$content,$content,'system');
    }
    public static function addmessage($uid,$title,$description,$content,$message_type = 'system',$value=''){
        M('message')->add(array(
            'uid'=>0,
            'tuid'=>$uid,
            'varname'=>$message_type,
            'value'=>$value,
            'title'=>$title,
            'description'=>$description,
            'content'=>$content,
            'inputtime'=>time()
            ));
        $push['title']=$title;
        $push['description']=$description;
        $push['content']=$content;
        $push['isadmin']=1;
        $push['inputtime']=time();
        $push['username']=M('user')->where(array('role'=>1,'group_id'=>2))->getField("username");
        $mid = M("Push")->add($push);

        $registration_id=M('member')->where(array('id'=>array('eq',$uid)))->getField("deviceToken");
        $receiver = $registration_id;
        $extras = array("mid"=>$mid,'message_type'=>$message_type);
        if(!empty($receiver)){
            PushQueue($mid, $message_type,$receiver, $title,$description, serialize($extras),1);
        }
    }
}
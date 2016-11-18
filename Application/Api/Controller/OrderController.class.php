<?php

namespace Api\Controller;

use Api\Common\CommonController;

class OrderController extends CommonController {

    public function _initialize(){
        parent::_initialize();
        Vendor("pingpp.init");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=D("Config")->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $this->ConfigData=$ConfigData;
    }

    public function orderadd(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);

        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));

        $productinfo=json_decode($ret['productinfo']);

        //$integral=intval(trim($ret['integral']));
        $wallet=$ret['wallet'];
        $couponsid=intval(trim($ret['couponsid']));
        $discount=$ret['discount'];
        $money=floatval(trim($ret['money']));

        $start_sendtime=intval(trim($ret['start_sendtime']));
        $end_sendtime=intval(trim($ret['end_sendtime']));
        $delivery=floatval(trim($ret['delivery']));

        $addressid=intval(trim($ret['addressid']));
        $addressinfo=M('address')->where(array('id'=>$addressid))->find();

        $isorderremark=intval(trim($ret['isorderremark']));
        $orderremark=trim($ret['orderremark']);
        $iscardremark=intval(trim($ret['iscardremark']));
        $cardremark=trim($ret['cardremark']);

        $ordertype=intval(trim($ret['ordertype']));
        $paytype=intval(trim($ret['paytype']));
        $paystyle=intval(trim($ret['paystyle']));
        $channel=trim($ret['channel']);

        $iscontainsweigh=intval(trim($ret['iscontainsweigh']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        $store=M('store')->where(array('id'=>$storeid))->find();

        $areaset=explode(",",$addressinfo['area']);
        $servicearea=explode(",",$store['servicearea']);
        

        if($uid==''||$storeid==''||$addressid==''||$ordertype==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$store){
            exit(json_encode(array('code'=>-200,'msg'=>"门店不存在")));
        }elseif($user['group_id']!=1){
            exit(json_encode(array('code'=>-200,'msg'=>"非普通用户不能下单")));
        }elseif(!in_array($areaset[count($areaset)-1],$servicearea)){
            exit(json_encode(array('code'=>-200,'msg'=>"亲，当前收货地址不属于此店铺配送范围，请修改收货地址或者更换店铺下单。")));
        }else{
            switch ($ordertype) {
                case '1':
                    # code...
                    if($paystyle==1){
                        if($paystyle==''||$paytype==''||$channel==''){
                            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
                        }
                    }
                    break;
                case '2':
                    # code...
                    if($paystyle==1){
                        if($paystyle==''||$paytype==''||$channel==''){
                            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
                        }
                    }
                    break;
                case '3':
                    # code...
                    if(empty($user['companyid'])){
                        exit(json_encode(array('code'=>-200,'msg'=>"用户尚未进行企业认证，不能提交企业订单")));
                    }
                    break;
            }
            $data['uid']=$uid;
            $data['storeid']=$storeid;
            $data["orderid"] = "wm".date("YmdHis", time()) . rand(100, 999);
            $data['ordercode'] = phpcode('http://' . $_SERVER['HTTP_HOST'] . U('Web/Order/sendshow',array('orderid'=>$data["orderid"])),$data["orderid"]);
            $data['title'] = "蔬果先生-订单编号".$data["orderid"];
            $data['nums']=count($productinfo);
            // if(!empty($integral)){
            //     $integralset=M('integral')->where('uid=' . $uid)->find();
            //     if($integralset['useintegral']<$integral){
            //         exit(json_encode(array('code'=>-200,'msg'=>"可用积分不足")));
            //     }else{
            //         $data['integral']=$integral;
            //     }
            // }
            if(!empty($wallet)&&$wallet!='0.00'){
                $account=M('account')->where('uid=' . $uid)->find();
                if($account['usemoney']<$wallet){
                    exit(json_encode(array('code'=>-200,'msg'=>"钱包可用金额不足")));
                }else{
                    $data['wallet']=$wallet;
                }
            }
            if(!empty($couponsid)){
                $coupons=M('coupons_order')->where(array('id'=>$couponsid))->find();
                if($coupons){
                    if($coupons['status']==1){
                        exit(json_encode(array('code'=>-200,'msg'=>"优惠券已经被使用")));
                    }
                }else{
                    exit(json_encode(array('code'=>-200,'msg'=>"尚未购买此种优惠券")));
                }
                
                $data['couponsid']=$couponsid;
                $data['discount']=$discount;
            }
            if(!empty($isorderremark)){
                $data['buyerremark']=$orderremark;
            }
            if(!empty($iscardremark)){
               $data['cardremark']=$cardremark;
            }

            $total=0.00;
            foreach ($productinfo as $key => $value) {
                # code...
                $product=M('product')->where(array('id'=>$key))->find();
                if($product['isoff']==1){
                    exit(json_encode(array('code'=>-200,'msg'=>"订单中有商品已被下架了"))); 
                }
                if($product['type']==3&&$product['selltime']<time()){
                    exit(json_encode(array('code'=>-200,'msg'=>"订单中有商品已过期啦！！")));
                }
                if($product['type']==2&&$product['expiretime']<time()){
                    exit(json_encode(array('code'=>-200,'msg'=>"订单中有商品已过期啦！！")));
                }
                if($product['stock']==0){
                    exit(json_encode(array('code'=>-200,'msg'=>"订单中有商品正在补货中！")));
                }
                if($value>$product['stock']&&$product['stock']>0){
                    exit(json_encode(array('code'=>-200,'msg'=>"订单中有商品库存不足！")));
                }
                if($product['type']==4){
                    $total+=$value*$product['nowprice'];
                }elseif($product['type']==3){
                    $total+=$value*$product['nowprice'];
                }else{
                    $total+=$value*$product['nowprice']; 
                }
            }
            if(!empty($start_sendtime)||!empty($end_sendtime)){
                $data['isspeed']=0;
            }else{
                if($ordertype!=2){
                    $useintegral=M('integral')->where(array('uid'=>$uid))->getField("useintegral");
                    if($total<199&&$useintegral<500){
                        exit(json_encode(array('code'=>-200,'msg'=>"订单金额低于199元，且用户可用积分不足500分，不能提交极速达订单"))); 
                    }
                    $data['isspeed']=1;
                }else{
                    $data['isspeed']=0;
                }
            }
            $data['start_sendtime']=$start_sendtime;
            $data['end_sendtime']=$end_sendtime;

            $data['money']=$money;

            $data['delivery']=$delivery;
            $data['deliverytype'] = 1;

            $data['addresstype']=$addressinfo['type'];
            $data['lat']=$addressinfo['lat'];
            $data['lng']=$addressinfo['lng'];
            $data['name']=$addressinfo['name'];
            $data['tel']=$addressinfo['tel'];
            $data['area']=$addressinfo['area'];
            $data['code']=$addressinfo['code'];
            $data['areatext']=$addressinfo['areatext'];
            $data['address']=$addressinfo['address'];

            $data['ordertype']=$ordertype;
            $data['paytype']=$paytype;
            $data['paystyle']=$paystyle;
            $data['channel']=$channel;
            $data['ordersource']=2;
            $data['iscontainsweigh']=$iscontainsweigh;
            $data['inputtime']=time();

            $id=M('order')->add($data);
            if($id){
                $c="尊敬的".  $addressinfo['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功提交了一笔订单,订单金额共计" . $data['money'] . "元。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单提交成功",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$data['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单提交成功",
                    'value'=>$data['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $total=0.00;
                $yes_money_total=0.00;
                $isnotice=1;
                foreach ($productinfo as $key => $value) {
                    # code...
                    $product=M('product')->where(array('id'=>$key))->find();
                    if($product['type']==4){
                        $total+=$value*$product['nowprice'];
                    }elseif($product['type']==3){
                        $yes_money_total+=$value*$product['advanceprice'];
                        $total+=$value*$product['nowprice'];
                        $isnotice=0;
                    }else{
                        $total+=$value*$product['nowprice']; 
                    }
                    M('order_productinfo')->add(array(
                        'orderid'=>$data['orderid'],
                        'pid'=>$key,
                        'nums'=>$value,
                        'price'=>$product['nowprice'],
                        'product_type'=>$product['type'],
                        'isweigh'=>0,
                        'isnotice'=>$isnotice
                        ));
                    //$total+=$value*$product['nowprice'];
                }

                M('order')->where(array('id'=>$id))->setField("total",$total);
                if($ordertype==2){
                    M('order')->where(array('id'=>$id))->setField("total",$total);
                    M('order')->where(array('id'=>$id))->setField("wait_money",$total);
                    M('order')->where(array('id'=>$id))->setField("yes_money_total",$yes_money_total);
                }
                if(!empty($wallet)&&$wallet!='0.00'){
                    $account=M('account')->where('uid=' . $uid)->find();
                    $inte['usemoney']=$account['usemoney']-$wallet;
                    $inte['nousemoney']=$account['nousemoney']+$wallet;
                    M('account')->where('uid=' . $uid)->save($inte);
                    // M('account_log')->add(array(
                    //     'uid'=>$uid,
                    //     'type'=>'order',
                    //     'money'=>$wallet,
                    //     'total'=>$account['total'],
                    //     'usemoney'=>$account['usemoney']-$wallet,
                    //     'nousemoney'=>$account['nousemoney']+$wallet,
                    //     'status'=>1,
                    //     'dcflag'=>2,
                    //     'remark'=>'订单使用钱包支付,冻结'.$wallet.'元',
                    //     'addip'=>get_client_ip(),
                    //     'addtime'=>time()
                    //     ));
                }
                switch ($ordertype) {
                    case '1':
                        # code...
                        if($iscontainsweigh==1){
                            M('order_time')->add(array(
                                'orderid'=>$data['orderid'],
                                'status'=>2,
                                'inputtime'=>time(),
                                ));
                        }else{
                            if ($data['paystyle'] == 3 ||$data['paystyle']==4) {
                                M('order_time')->add(array(
                                    'orderid'=>$data['orderid'],
                                    'status'=>2,
                                    'pay_status'=>1,
                                    'pay_time'=>time(),
                                    'inputtime'=>time(),
                                    ));
                            }else{
                                M('order_time')->add(array(
                                    'orderid'=>$data['orderid'],
                                    'status'=>2,
                                    'inputtime'=>time(),
                                    ));
                            }
                        }
                        break;
                    case '2':
                        # code...
                        $orderdata= M('order')->where(array('orderid'=>$data['orderid']))->find();
                        if($orderdata['yes_money']>=$yes_money_total){
                            if($orderdata['total']<=$yes_money_total){
                                M('order')->where(array('orderid'=>$data['orderid']))->save(array(
                                    'yes_money'=>floatval($wallet)+floatval($discount),
                                    'wait_money'=>$orderdata['wait_money']-floatval($wallet)-floatval($discount)
                                ));
                            }else{
                                M('order')->where(array('orderid'=>$data['orderid']))->save(array(
                                    'yes_money'=>floatval($wallet),
                                    'wait_money'=>$orderdata['wait_money']-floatval($wallet)
                                ));
                            }
                        }else{
                            M('order')->where(array('orderid'=>$data['orderid']))->save(array(
                                'yes_money'=>floatval($wallet),
                                'wait_money'=>$orderdata['wait_money']-floatval($wallet)
                            ));
                        }
                       
                        
                        if ($data['paystyle'] == 3) {
                            $c="尊敬的". $orderdata['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔预购订单，已付定金为".$orderdata['yes_money'] . "。";
                            M("message")->add(array(
                                'uid'=>0,
                                'tuid'=>$uid,
                                'title'=>"预购订单支付定金成功",
                                'value'=>$orderdata['orderid'],
                                'varname'=>"order",
                                'inputtime'=>time()
                            ));
                            if($yes_money_total>=$orderdata['total']){
                                M('order')->where(array('orderid'=>$data['orderid']))->save(array(
                                    'yes_money'=>floatval($wallet)+floatval($discount),
                                    'wait_money'=>$orderdata['wait_money']-floatval($wallet)-floatval($discount)
                                ));
                                M('order_time')->add(array(
                                   'orderid'=>$data['orderid'],
                                   'status'=>2,
                                   'pay_status'=>1,
                                   'pay_time'=>time(),
                                   'inputtime'=>time(),
                                   ));
                            }else{
                                M('order_time')->add(array(
                                   'orderid'=>$data['orderid'],
                                   'status'=>2,
                                   'pay_status'=>0,
                                   'pay_time'=>time(),
                                   'inputtime'=>time(),
                                   ));
                            }
                        }else{;
                            M('order_time')->add(array(
                                'orderid'=>$data['orderid'],
                                'status'=>2,
                                'inputtime'=>time(),
                                ));
                        }
                        break;
                    case '3':
                        # code...
                        M('order_time')->add(array(
                            'orderid'=>$data['orderid'],
                            'status'=>2,
                            'pay_status'=>1,
                            'pay_time'=>time(),
                            'inputtime'=>time(),
                            ));
                        
                        $companyorderinfo=M('companyorder_info')->where(array('companyid'=>$user['companyid'],'year'=>date("Y"),'month'=>date("m")))->find();
                        if($companyorderinfo){
                            $status=0;
                            if($companyorderinfo['status']==2){
                                $status=1;
                            }elseif($companyorderinfo['status']==1){
                                $status=1;
                            }elseif($companyorderinfo['status']==0){
                                $status=0;
                            }
                            M('companyorder_info')->where(array('companyid'=>$user['companyid'],'year'=>date("Y"),'month'=>date("m")))->save(array('ordernum'=>$companyorderinfo['ordernum']+1,'ordermoney'=>$companyorderinfo['ordermoney']+$total,'no_money'=>$companyorderinfo['no_money']+$total,'status'=>$status));
                        }else{
                            M('companyorder_info')->add(array('companyid'=>$user['companyid'],'year'=>date("Y"),'month'=>date("m"),'ordernum'=>1,'ordermoney'=>$total,'no_money'=>$total,'status'=>0));
                        }
                        break;
                }
                
                M('coupons_order')->where(array('id'=>$couponsid))->setField('status',1);

                if($data['isspeed']==1&&$total<199){
                    self::update_integral($uid,500,2,"提交极速达订单，消费500积分",'order');
                }

                
                switch ($ordertype) {
                    case '1':
                        # code...
                        self::updatestock($data['orderid']);
                        if($iscontainsweigh==1){
                            if ($data['paystyle'] == 1) {
                                self::wallet($id);
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }elseif($data['paystyle'] == 2){
                                self::wallet($id);
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }elseif($data['paystyle'] == 3){
                                self::wallet($id);
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }elseif($data['paystyle'] == 4){
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }
                        }else{
                            if ($data['paystyle'] == 1) {
                                self::wallet($id);
                                $pingpp=self::pingpp($data['orderid']);
                                exit($pingpp);
                            }elseif($data['paystyle'] == 2){
                                self::wallet($id);
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }elseif($data['paystyle'] == 3){
                                self::wallet($id);
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }elseif($data['paystyle'] == 4){
                                exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                            }
                        }
                        
                        break;
                    case '2':
                        # code...
                        if ($data['paystyle'] == 1) {
                            self::wallet($id);
                            $pingpp=self::pingpp($data['orderid']);
                            exit($pingpp);
                        }elseif($data['paystyle'] == 2){
                            self::wallet($id);
                            exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                        }elseif($data['paystyle'] == 3){
                            self::wallet($id);
                            exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                        }elseif($data['paystyle'] == 4){
                            exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                        }
                        break;
                    case '3':
                        # code...s
                        self::updatestock($data['orderid']);
                        exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                        break;
                }
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"订单提交失败")));
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
        $mid=M('message')->add(array(
            'uid'=>0,
            'tuid'=>$uid,
            'varname'=>$message_type,
            'value'=>$value,
            'title'=>$title,
            'description'=>$description,
            'content'=>$content,
            'inputtime'=>time()
            ));

        $registration_id=M('member')->where(array('id'=>array('eq',$uid)))->getField("deviceToken");
        $receiver = $registration_id;
        $extras = array("mid"=>$mid,'message_type'=>$message_type);
        if(!empty($receiver)){
            PushQueue($mid, $message_type,$receiver, $title,$description, serialize($extras),1);
        }
    }
    public function orderpayagain(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);

        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);
        $paystyle=intval(trim($ret['paystyle']));
        $paytype=intval(trim($ret['paytype']));
        $money=floatval(trim($ret['money']));
        $channel=trim($ret['channel']);
        $wallet=$ret['wallet'];
        $discount=$ret['discount'];

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $offstatus=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$orderid,'b.isoff'=>1))->count();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$order){
            exit(json_encode(array('code'=>-200,'msg'=>"订单不存在")));
        }elseif($offstatus>0){
            exit(json_encode(array('code'=>-200,'msg'=>"订单的商品已被下架，不能支付")));
        }else{
            if(!empty($wallet)&&$wallet!='0.00'){
                $account=M('account')->where('uid=' . $uid)->find();
                if($account['usemoney']<floatval($wallet)){
                    exit(json_encode(array('code'=>-200,'msg'=>"钱包可用金额不足")));
                }
                $inte['usemoney']=$account['usemoney']-floatval($wallet);
                $inte['nousemoney']=$account['nousemoney']+floatval($wallet);
                M('account')->where('uid=' . $uid)->save($inte);
                // M('account_log')->add(array(
                //     'uid'=>$uid,
                //     'type'=>'order',
                //     'money'=>floatval($wallet),
                //     'total'=>$account['total'],
                //     'usemoney'=>$account['usemoney']-floatval($wallet),
                //     'nousemoney'=>$account['nousemoney']+floatval($wallet),
                //     'status'=>1,
                //     'dcflag'=>2,
                //     'remark'=>'订单使用钱包支付,冻结'.floatval($wallet).'元',
                //     'addip'=>get_client_ip(),
                //     'addtime'=>time()
                //     ));
            }
            if(!empty($discount)&&$discount!=0.00&&$order['couponsid']!=0){
                M('coupons_order')->where(array('id'=>$order['couponsid']))->setField('status',1);
            }
            $pingpp="";
            switch ($order['ordertype']) {
                case '1':
                    # code...
                    M('order')->where(array('orderid'=>$orderid))->save(array(
                            'wallet'=>floatval($wallet)+$order['wallet'],
                            'discount'=>floatval($discount),
                            'money'=>$money,
                            'paystyle'=>$paystyle,
                            'paytype'=>$paytype,
                            'channel'=>$channel
                            ));
                    if ($paystyle == 1) {
                        self::wallet($order['id'],floatval($wallet));
                        $pingpp=self::pingpp($orderid);
                        exit($pingpp);
                    }elseif($paystyle == 2){
                        self::wallet($order['id'],floatval($wallet));
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }elseif($paystyle == 3){
                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                'pay_status'=>1,
                                'pay_time'=>time()
                                ));
                        self::wallet($order['id'],floatval($wallet));
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }elseif($paystyle == 4){
                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                'pay_status'=>1,
                                'pay_time'=>time()
                                ));
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }
                    break;
                case '2':
                    # code...
                    $yes_money_total=$order['yes_money_total'];
                    if($yes_money_total==0.00){
                        $productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
                        foreach ($productinfo as $value) {
                            # code...
                            $product=M('product')->where(array('id'=>$value['pid']))->find();
                            $yes_money_total+=$value['nums']*$product['advanceprice'];
                        }
                    }
                    if($order['yes_money']>=$yes_money_total){
                        M('order')->where(array('orderid'=>$orderid))->save(array(
                            'wallet'=>floatval($wallet)+$order['wallet'],
                            'money'=>$money,
                            'discount'=>$discount,
                            'yes_money'=>$order['yes_money']+floatval($wallet)+floatval($discount),
                            'wait_money'=>$order['wait_money']-floatval($wallet)-floatval($discount),
                            'paystyle'=>$paystyle,
                            'paytype'=>$paytype,
                            'channel'=>$channel
                            ));
                    }else{
                        if($order['total']<=$yes_money_total){
                            M('order')->where(array('orderid'=>$orderid))->save(array(
                                'wallet'=>floatval($wallet)+$order['wallet'],
                                'money'=>$money,
                                'discount'=>$order['discount'],
                                'yes_money'=>$order['yes_money']+floatval($wallet),
                                'wait_money'=>$order['wait_money']-floatval($wallet),
                                'paystyle'=>$paystyle,
                                'paytype'=>$paytype,
                                'channel'=>$channel
                                ));
                        }else{
                            M('order')->where(array('orderid'=>$orderid))->save(array(
                                'wallet'=>floatval($wallet)+$order['wallet'],
                                'money'=>$money,
                                'discount'=>$order['discount'],
                                'yes_money'=>$order['yes_money']+floatval($wallet),
                                'wait_money'=>$order['wait_money']-floatval($wallet),
                                'paystyle'=>$paystyle,
                                'paytype'=>$paytype,
                                'channel'=>$channel
                                ));
                        }
                        
                    }
                    
                    if ($paystyle == 1) {
                        self::wallet($order['id'],$wallet);
                        $pingpp=self::pingpp($orderid);
                        exit($pingpp);
                    }elseif($paystyle == 2){
                        self::wallet($order['id'],$wallet);
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }elseif($paystyle == 3){
                        if($order['yes_money']>=$yes_money_total){

                            if($order['total']==$order['yes_money']+floatval($wallet)+floatval($discount)){
                                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                    'pay_status'=>1,
                                    'pay_time'=>time()
                                    ));
                            }
                        }else{
                            if($order['total']==$yes_money_total){
                                M('order')->where(array('orderid'=>$orderid))->save(array(
                                    'yes_money'=>$order['yes_money']+floatval($wallet)+floatval($discount),
                                    'wait_money'=>$order['wait_money']-floatval($wallet)-floatval($discount),
                                    ));
                                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                    'pay_status'=>1,
                                    'pay_time'=>time()
                                    ));
                            }
                            // if($order['wait_money']==floatval($wallet)){
                            //     M('order_time')->where(array('orderid'=>$orderid))->save(array(
                            //         'pay_status'=>1,
                            //         'pay_time'=>time()
                            //         ));
                            // }
                        }
                        
                        self::wallet($order['id'],$wallet);
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }elseif($paystyle == 4){
                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                'pay_status'=>1,
                                'pay_time'=>time()
                                ));
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }
                    break;
            }
            
        }
    }
    public function paycancel(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);

        $uid=intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);
        
        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();

        $coupons=M('coupons_order')->where(array('id'=>$order['couponsid']))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$order){
            exit(json_encode(array('code'=>-200,'msg'=>"订单不存在")));
        }elseif(!$coupons){
            exit(json_encode(array('code'=>-200,'msg'=>"优惠券不存在")));
        }elseif($coupons['status']==0){
            exit(json_encode(array('code'=>-200,'msg'=>"该优惠券尚未使用，不能取消使用")));
        }else{
            $id=M('coupons_order')->where(array('id'=>$order['couponsid']))->setField('status',0);
            if($id){ 
                if($order['ordertype']==1){
                    M('coupons_order')->where(array('id'=>$order['couponsid']))->setField('status',0);
                    M('order')->where(array('orderid'=>$orderid))->save(array(
                        'money'=>$order['money']+floatval($order['discount']),
                    ));
                }elseif($order['ordertype']==2){

                    M('coupons_order')->where(array('id'=>$order['couponsid']))->setField('status',0);
                    M('order')->where(array('orderid'=>$orderid))->save(array(
                        'yes_money'=>$order['yes_money']-floatval($order['discount']),
                        'wait_money'=>$order['wait_money']+floatval($order['discount'])
                    ));
                    
                    
                }
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }
    public function ordernum(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);

        $uid=intval(trim($ret['uid']));
        
        $user=M('Member')->where(array('id'=>$uid))->find();
        if($uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }else{
            $waitpay=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.pay_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.paystyle'=>array("in","1,3"),'a.isserviceorder'=>0,'_string'=>'(a.paystyle!=2 and a.iscontainsweigh=1 and c.package_status=1) or (a.paystyle!=2 and a.iscontainsweigh=0)'))->count();
            $waitpackage=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.package_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0,'a.isserviceorder'=>0,'_string'=>'(c.pay_status=0 and (a.iscontainsweigh=1 or a.paystyle=2)) or (c.pay_status=1 and a.iscontainsweigh=0 and ((a.ordertype!=3)or (a.ordertype=3 and a.puid=0)))'))->count();
            $waitconfirm=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0))->count();
            $waitevaluate=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.pay_status'=>1,'c.delivery_status'=>4,'c.status'=>5,'c.evaluate_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0))->count();           
            $data=array(
                'waitpay'=>!empty($waitpay)?$waitpay:0,
                'waitpackage'=>!empty($waitpackage)?$waitpackage:0,
                'waitconfirm'=>!empty($waitconfirm)?$waitconfirm:0,
                'waitevaluate'=>!empty($waitevaluate)?$waitevaluate:0,
                );
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }
    }
    /*
     *我的订单
     */

    public function orderlist(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $type=trim($ret['type']);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$type == ''||$p==''||$num=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where=array();
            $order=array();
            $field=array();
            switch ($type) {
                case 'all':
                    # code...
                    $where=array('a.uid'=>$uid,'c.close_status'=>0,'a.isserviceorder'=>0);
                    $order=array('a.inputtime'=>'desc');
                    $field=array('a.orderid,a.ruid,a.puid,a.ruid,a.storeid,a.wallet,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.inputtime,a.paystyle,a.paytype,c.status,c.pay_status,c.package_status,c.delivery_status,c.buyer_sendstatus,c.evaluate_status,a.ordertype,a.yes_money,a.wait_money,c.donetime');
                    break;
                case 'waitpay':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.paystyle'=>array("in","1,3"),'a.isserviceorder'=>0,'_string'=>'(a.paystyle!=2 and a.iscontainsweigh=1 and c.package_status=1) or (a.paystyle!=2 and a.iscontainsweigh=0)');
                    $order=array('a.inputtime'=>'desc');
                    $field=array('a.orderid,a.ruid,a.storeid,a.wallet,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.inputtime,a.paystyle,a.paytype,c.status,a.ordertype,a.yes_money,a.wait_money');
                    break;
                case 'waitpackage':
                    # code...
                    $where=array('a.uid'=>$uid,'c.package_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0,'_string'=>'(c.pay_status=0 and (a.iscontainsweigh=1 or a.paystyle=2)) or (c.pay_status=1 and a.iscontainsweigh=0 and ((a.ordertype!=3)or (a.ordertype=3 and a.puid=0)))');
                    $order=array('a.inputtime'=>'desc');
                    $field=array('a.orderid,a.ruid,a.storeid,a.wallet,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.inputtime,a.paystyle,a.paytype,c.status,a.ordertype,a.yes_money,a.wait_money');
                    break;
                case 'waitconfirm':
                    # code...
                    $where=array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0);
                    $order=array('c.delivery_time'=>'asc','c.id'=>'desc');
                    $field=array('a.orderid,a.ruid,a.storeid,a.wallet,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.inputtime,a.paystyle,a.paytype,c.status,a.ordertype,a.yes_money,a.wait_money');
                    break;
                case 'waitevaluate':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>1,'c.delivery_status'=>4,'c.status'=>5,'c.evaluate_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                    $order=array('a.inputtime'=>'desc');
                    $field=array('a.orderid,a.ruid,a.storeid,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.wallet,a.inputtime,a.paystyle,a.paytype,c.status,a.ordertype,a.yes_money,a.wait_money,c.donetime');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")
                              ->join("left join zz_order_time c on a.orderid=c.orderid")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->page($p,$num)
                              ->select();
            foreach ($data as $key => $value) {
                # code...
                $productinfo=M('order_productinfo a')
                    ->join("left join zz_product b on a.pid=b.id")
                    ->where(array('a.orderid'=>$value['orderid']))
                    ->field("a.pid,a.nums,b.thumb,b.title,b.description,a.price as nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh,a.weigh,b.selltime,b.advanceprice,b.price")
                    ->select();
                foreach ($productinfo as $k => $val)
                {
                	$productinfo[$k]['unit']=getunit($val['unit']);
                }
                $data[$key]['productinfo']=$productinfo;
                $feedbackstatus=M('order_feedback')->where(array('orderid'=>$value['orderid']))->find();
                if($feedbackstatus){
                    $data[$key]['isfeedback']=1;
                }else{
                    $data[$key]['isfeedback']=0;
                }
                
            }
            // $data['sql']=M('order a')->_sql();
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

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$type == '') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where=array();
            $order=array('c.inputtime'=>'desc');
            $field=array('a.orderid,d.realname as runerrealname,d.username as runerusername,d.phone as runerphone,d.head as runerhead,c.delivery_time,c.donetime,c.inputtime,a.lat,a.lng');
            switch ($type) {
                case 'all':
                    # code...
                    $where=array('a.uid'=>$uid,'c.close_status'=>0,'a.isserviceorder'=>0);
                    break;
                case 'waitpay':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.paystyle'=>array("in","1,3"),'a.isserviceorder'=>0,'_string'=>'(a.paystyle!=2 and a.iscontainsweigh=1 and c.package_status=1) or (a.paystyle!=2 and a.iscontainsweigh=0)');
                    break;
                case 'waitpackage':
                    # code...
                    $where=array('a.uid'=>$uid,'c.package_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0,'_string'=>'(c.pay_status=0 and (a.iscontainsweigh=1 or a.paystyle=2)) or (c.pay_status=1 and a.iscontainsweigh=0 and ((a.ordertype!=3)or (a.ordertype=3 and a.puid=0)))');
                    break;
                case 'waitconfirm':
                    # code...
                    $where=array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                    break;
                case 'waitevaluate':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>1,'c.delivery_status'=>4,'c.status'=>5,'c.evaluate_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                    break;
            }
            $data=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")
                              ->join("left join zz_member d on a.ruid=d.id")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->select();
            foreach ($data as $key => $value) {
                # code...
                $lat=M('order_distance')->where(array('orderid'=>$value['orderid']))->order(array('inputtime'=>'desc'))->getField("lat");
                if(!empty($lat)){
                    $data[$key]['order_lat']=$lat;
                }else{
                    $data[$key]['order_lat']=$value['lat'];
                }
                $lng=M('order_distance')->where(array('orderid'=>$value['orderid']))->order(array('inputtime'=>'desc'))->getField("lng");
                if(!empty($lat)){
                    $data[$key]['order_lng']=$lng;
                }else{
                    $data[$key]['order_lng']=$value['lng'];
                }
            }
            //$data['sql']=M('order a')->_sql();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"订单数据为空")));
            }
        }
    }
    /*
     **订单祥情
     */
    public function ordershow(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $orderid = trim($ret['orderid']);

        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array();
            $type=M('order')->where(array('orderid'=>$orderid))->getField("ordertype");
            switch ($type) {
                case '1':
                    # code...
                    $field=array('a.orderid,a.puid,a.ruid,a.storeid,a.discount,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.yes_money_total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.*,a.ordertype');
                    break;
                case '2':
                    # code...
                    $field=array('a.orderid,a.puid,a.ruid,a.storeid,a.discount,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.yes_money_total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.yes_money,a.wait_money,a.ordertype,c.*');
                    break;
                case '3':
                    # code...
                    $field=array('a.orderid,a.puid,a.ruid,a.storeid,a.discount,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.yes_money_total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.*,a.ordertype');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.orderid'=>$orderid))->field($field)->find();
            $data['linkurl']='http://' . $_SERVER['HTTP_HOST'] . U('Web/Order/sendshow',array('orderid'=>$data["orderid"]));
            $data['area']=getarea($data['area']);
            $data['productinfo']=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$data['orderid']))->field("a.pid,a.nums,b.thumb,b.title,b.description,b.nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh,b.selltime,b.advanceprice")->select();
            $feedbackstatus=M('order_feedback')->where(array('orderid'=>$data['orderid']))->find();
            if($feedbackstatus){
                $data['isfeedback']=1;
            }else{
                $data['isfeedback']=0;
            }
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    /*
     **订单状态
     */
    public function orderlog(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid = trim($ret['orderid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else {
            $data=M('message')->where(array('varname'=>'order','value'=>$orderid,'tuid'=>0))->field(array('id,title,content,inputtime'))->order(array('id'=>'asc'))->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }

    /*
     **取消订单
     */
    public function closeorder(){
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
        }
        //elseif($order['pay_status']==1){
        //    exit(json_encode(array('code'=>-200,'msg'=>"该订单不能关闭")));
        //}
        elseif($order['package_status']!=0){
            exit(json_encode(array('code'=>-200,'msg'=>"该订单不能关闭")));
        }else{
            $select['orderid']=$orderid;
            $id=M('order_time')->where($select)->save(array(
                'status'=>3,
                'cancel_status'=>1,
                'cancel_time'=>time()
                ));
            if($id){
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功取消了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"取消订单",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"取消订单成功",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $money=0.00;
                switch ($order['ordertype'])
                {
                    case 1:
                        if($order['pay_status']==1){
                            $money=$order['money']+$order['wallet'];
                        }else{
                            $money=$order['wallet'];
                        }
                        break;
                    case 2:
                        $money=$order['yes_money'];
                        break;
                }
                if(!empty($order['couponsid'])){
                    M('coupons_order')->where(array('id'=>$order['couponsid']))->setField("status",0);
                }
                if($order['isspeed']==1&&$order['total']<199){
                    self::update_integral($order['uid'],500,1,"取消极速达订单成功，退回500积分",'order');
                }

                if(!empty($money)&&$money!='0.00'){
                    $account=M('account')->where('uid=' . $order['uid'])->find();
                    $newaccount['usemoney']=$account['usemoney']+$money;
                    M('account')->where('uid=' . $order['uid'])->save($newaccount);

                    M('account_log')->add(array(
                        'uid'=>$order['uid'],
                        'type'=>'order',
                        'money'=>$money,
                        'total'=>$account['total'],
                        'usemoney'=>$account['usemoney']+$money,
                        'nousemoney'=>$account['nousemoney'],
                        'status'=>1,
                        'dcflag'=>1,
                        'remark'=>'取消订单成功，订单款项'.$money.'元退回钱包',
                        'addip'=>get_client_ip(),
                        'addtime'=>time()
                        ));
                }
                exit(json_encode(array('code'=>200,'msg'=>"取消订单成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"取消订单失败")));
            }
        }
    }
    /*
     **评价订单
     */
    public function evaluate(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid = trim($ret['orderid']);
        $speed = intval(trim($ret['speed']));
        $attitude = intval(trim($ret['attitude']));
        $total = intval(trim($ret['total']));
        $thumb = trim($ret['thumb']);
        $content = trim($ret['content']);
        $product_evaluateinfo=$ret['product_evaluateinfo'];

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $status=M('evaluation')->where(array('uid'=>$uid,'value'=>$orderid,'varname'=>'order'))->find();
        if ($uid == ''||$orderid==''||$speed==''||$attitude==''||$total=='') {
            exit(json_encode(array('code' =>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }else {
            $id=M('evaluation')->add(array(
                'varname'=>'order',
                'uid'=>$uid,
                'value'=>$orderid,
                'speed'=>$speed,
                'attitude'=>$attitude,
                'total'=>$total,
                'content'=>$content,
                'thumb'=>$thumb,
                'status'=>0,
                'inputtime'=>time()
                ));
            if($id){
                if(empty($status)){
                    self::update_integral($uid,10,1,"提交订单评论，获取10积分",'orderevaluate');
                }
                
                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'evaluate_status'=>1,
                    'evaluate_time'=>time()
                    ));
                foreach ($product_evaluateinfo as $value) {
                    # code...
                    $status=M('evaluation')->where(array('uid'=>$uid,'value'=>$value['pid'],'varname'=>'product'))->find();
                    M('evaluation')->add(array(
                        'varname'=>'product',
                        'uid'=>$uid,
                        'value'=>$value['pid'],
                        'total'=>$value['total'],
                        'content'=>$value['content'],
                        'thumb'=>$value['thumb'],
                        'status'=>0,
                        'inputtime'=>time()
                        ));
                    
                    if(empty($status)){
                        self::update_integral($uid,5,1,"提交商品评论，获取5积分",'productevaluate');
                    }
                }
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功评价了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"评价订单",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"评价订单成功",
                    'value'=>$order['orderid'],
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                exit(json_encode(array('code'=>200,'msg'=>"评价成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"评价失败")));
            }
        }
    }
    /*
     **订单反馈
     */
    public function feedback(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid = trim($ret['orderid']);
        $thumb = trim($ret['thumb']);
        $content = trim($ret['content']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $orderfeedback=M('order_feedback')->where(array('uid'=>$uid,'orderid'=>$orderid))->find();
        if ($uid == ''||$orderid==''||$content==''||$thumb=='') {
            exit(json_encode(array('code' =>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif(!empty($orderfeedback)){
            exit(json_encode(array('code'=>-200,'msg'=>"您已经提交售后反馈，不能重复提交。")));
        }else {
            $id=M('order_feedback')->add(array(
                'uid'=>$uid,
                'orderid'=>$orderid,
                'content'=>$content,
                'thumb'=>$thumb,
                'status'=>1,
                'inputtime'=>time()
                ));
            if($id){
                $phone=M('order a')->join("left join zz_store b on a.storeid=b.id")->where(array('a.orderid'=>$orderid))->getField("b.contact");
                $data=json_encode(array('phone'=>$phone,'datas'=>array($orderid),'templateid'=>"74017"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($data);
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功提交了订单反馈。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$uid,
                    'title'=>"订单反馈",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }
    /*
     *发票订单
     */

    public function bill_order(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $isapply=intval(trim($ret['isapply']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$p==''||$num=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where['a.uid']=$uid;
            $where['b.status']=5;
            $where['b.delivery_status']=4;
            $where['a.money']=array('gt',0);
            $where['a.ordertype']=array("neq",3);
            if($isapply==0){
                $where['b.bill_apply_status']=0;
            }else{
                $where['b.bill_apply_status']=1;
            }
            $where['a.inputtime']=array(array('elt',time()),array('egt',strtotime("-7 days")));
            $data=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->order(array('a.inputtime'=>'desc'))->field('a.orderid,a.money,a.total,a.inputtime')->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空!")));
            }
        }
    }

    /*
     *发票申请
     */

    public function billapply(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid = intval(trim($ret['uid']));
        $orderid=trim($ret['orderid']);
        $billtype = intval(trim($ret['billtype']));
        $billaddressid = intval(trim($ret['billaddressid']));
        $billtitle=trim($ret['billtitle']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $address=M('address')->where(array('id'=>$billaddressid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        if ($uid == ''||$orderid == ''||$billtype=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } elseif(empty($address)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Address is not exist!")));
        } else {
            $orderidbox=explode(",", $orderid);
            if(is_array($orderidbox)){
                $where['orderid']=array('in',$orderid);
            }else{
                $where['orderid']=array('eq',$orderid);
            }
            $id=M('order')->where($where)->save(array(
                'billtype'=>$billtype,
                'billaddressid'=>$billaddressid,
                'billtitle'=>$billtitle
                ));
            if($id){
                M('order_time')->where($where)->save(array(
                    'bill_apply_status'=>1,
                    'bill_apply_time'=>time()
                    ));
                $c="您好！您在".date("Y年m月d日 H时i分s秒") ."成功申请了订单发票。";

                if(is_array($orderidbox)){
                    foreach ($orderidbox as $value) {
                        # code...
                        M("message")->add(array(
                            'uid'=>0,
                            'tuid'=>$uid,
                            'title'=>"订单发票申请",
                            'description'=>$c,
                            'content'=>$c,
                            'value'=>$value,
                            'varname'=>"system",
                            'inputtime'=>time()
                        ));
                    }
                }else{
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$uid,
                        'title'=>"订单发票申请",
                        'description'=>$c,
                        'content'=>$c,
                        'value'=>$order['orderid'],
                        'varname'=>"system",
                        'inputtime'=>time()
                    ));
                }
                
                exit(json_encode(array('code'=>200,'msg'=>"申请成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"申请失败")));
            }
        }
    }
    public function testping(){
        $orderid="20160104152828438";
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $orderid=$order['orderid'];
        $extra = array();

        $ping_config=array();
        foreach ($this->ConfigData as $r) {
            if($r['groupid'] == 5){
                $ping_config[$r['varname']] = $r['value'];
            }
        }
        \Pingpp\Pingpp::setApiKey($ping_config['pingKey']);
        try {
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => $order['title'],
                    "body"      => $order['title'],
                    "amount"    => $order['money']*100,
                    "order_no"  => $orderid,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $order['channel'],
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => $ping_config['pingAppid'])
                )
            );
            //echo $ch;
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,"INFO");
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            //return $ch;
            echo $ch;
        }
        catch (\Pingpp\Error\Base $e) {
            $Status = $e->getHttpStatus();
            $body = $e->getHttpBody();
            return 'Status: ' . $Status ." body:".$body;
        }
    }

    public function pingpp($orderid){
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $orderid=$order['orderid'];
        $extra = array();
        if($order['ordertype']==2){
            $yes_money=0.00;
            $productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
            foreach ($productinfo as $value) {
                # code...
                $product=M('product')->where(array('id'=>$value['pid']))->find();
                if($product['type']==3){
                    $yes_money+=$value['nums']*$product['advanceprice'];
                }
            }
            if($order['yes_money']<$yes_money){
                if($yes_money>=$order['total']){
                    $money=$order['wait_money']-$order['discount'];
                }else{
                    $money=$yes_money-$order['wallet'];
                }
                
            }else{
                $money=$order['wait_money'];
            }
            
        }else{
            $money=$order['money'];
        }
        //$randstring=\Api\Common\CommonController::genNumberString(6);
        $orderid=$orderid.rand(100000, 999999);
        //$money=0.01;
        $ping_config=array();
        foreach ($this->ConfigData as $r) {
            if($r['groupid'] == 5){
                $ping_config[$r['varname']] = $r['value'];
            }
        }
        \Pingpp\Pingpp::setApiKey($ping_config['pingKey']);
        try {
            $ch = \Pingpp\Charge::create(
                array(
                    "subject"   => $order['title'],
                    "body"      => $order['title'],
                    "amount"    => $money*100,
                    "order_no"  => $orderid,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $order['channel'],
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => $ping_config['pingAppid'])
                )
            );
            //echo $ch;
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,\Think\Log::INFO);
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            return $ch;
        }
        catch (\Pingpp\Error\Base $e) {
            $Status = $e->getHttpStatus();
            $body = $e->getHttpBody();
            return 'Status: ' . $Status ." body:".$body;
        }

    }
    public function pingppcalback(){
        $notifydata = file_get_contents("php://input");
        \Think\Log::write('ping++回调数据：'.$notifydata,\Think\Log::INFO);
        $notify = json_decode($notifydata,true);
        M('thirdparty_data')->add(array(
          'post'=>serialize($notify),
          'type'=>"ping++",
          'ispc'=>0,
          'inputtime'=>time()
          ));
        $notifyarray=$notify['data']['object'];
        
        if( !isset($notifyarray['object'])){
            exit("fail");
        }
        switch($notifyarray['object']){
            case "charge":
                $orderid=$notifyarray['order_no'];
                $type=substr($orderid,0,2);
                //$orderid=substr($orderid, -6,6);
                //$orderid=substr($orderid,0,strlen($orderid)-6);
                switch ($type)
                {
                    case "wm":
                    $orderid=substr($orderid,0,strlen($orderid)-6);
                        $order=M('order_time a')->join("left join zz_order b on a.orderid=b.orderid")->where(array('a.orderid' => $orderid))->find();
                        if($order['pay_status']!=1){
                            $id=M('order')->where(array('orderid'=>$orderid))->save(array(
                                'trade_no'=>$notifyarray['transaction_no'],
                                'paynotifydata'=>serialize($notifyarray)
                                ));
                            if($id){
                                $money=floatval($notifyarray['amount']/100);
                                if($order['ordertype']==2){
                                    // $yes_money_total=0.00;
                                    // $productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
                                    // foreach ($productinfo as $value) {
                                    //    # code...
                                    //    $product=M('product')->where(array('id'=>$value['pid']))->find();
                                    //    if($product['type']==3){
                                    //        $yes_money_total+=$value['nums']*$product['advanceprice'];
                                    //    }
                                    // }
                                    $orderdata= M('order')->where(array('orderid'=>$orderid))->find();
                                    M('order')->where(array('orderid'=>$orderid))->save(array(
                                        'yes_money'=>$orderdata['yes_money']+$money,
                                        'wait_money'=>$orderdata['wait_money']-$money
                                        ));
                                    $yes_money=$orderdata['yes_money']+$money;
                                    
                                    if($yes_money>=$orderdata['total']){
                                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                           'pay_status'=>1,
                                           'pay_time'=>time(),
                                           ));
                                        $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，订单金额为".$order['money'] . "。";
                                        M("message")->add(array(
                                            'uid'=>0,
                                            'tuid'=>0,
                                            'title'=>"订单全款支付成功",
                                            'value'=>$order['orderid'],
                                            'varname'=>"order",
                                            'inputtime'=>time()
                                        ));
                                    }else{
                                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                           'pay_status'=>0,
                                           'pay_time'=>time(),
                                           ));
                                        $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔预购订单，已付定金为".$order['yes_money'] . "。";
                                        M("message")->add(array(
                                            'uid'=>0,
                                            'tuid'=>0,
                                            'title'=>"预购订单支付定金成功",
                                            'value'=>$order['orderid'],
                                            'varname'=>"order",
                                            'inputtime'=>time()
                                        ));
                                    }
                                    self::updatestock($orderid);
                                }else{
                                    M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                        'pay_status'=>1,
                                        'pay_time'=>time(),
                                        ));
                                    
                                    $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，订单金额为".$order['money'] . "。";
                                    M("message")->add(array(
                                        'uid'=>0,
                                        'tuid'=>0,
                                        'title'=>"订单支付成功",
                                        'value'=>$order['orderid'],
                                        'varname'=>"order",
                                        'inputtime'=>time()
                                    ));

                                }
                                
                                $usr=M('member')->where('id=' . $order['uid'])->field("id,phone")->find();
                                
                                //$sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
                                //self::sendsms($sms);
                                //self::integral($order['id']);
                                self::wallet($order['id']);
                                //self::update_integral($order['uid'],$money,1,"在线支付订单成功，获得".$money."积分",'order');
                            }
                        }
                        
                        exit("success");
                        break;
                    // case "pw":
                    //     $money=floatval($notifyarray['amount']/100);
                    //     $orderid=substr($orderid,1,strlen($orderid)-1);
                    //     $order=M('order_time a')->join("left join zz_order b on a.orderid=b.orderid")->where(array('a.orderid' => $orderid))->find();
                    //     if($order['pay_status']!=1){
                    //         $id=M('order')->where(array('orderid'=>$orderid))->save(array(
                    //             'trade_no'=>$notifyarray['transaction_no'],
                    //             'paynotifydata'=>serialize($notifyarray)
                    //             ));
                    //         if($id){
                    //             $yes_money=0.00;
                    //             $productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
                    //             foreach ($productinfo as $value) {
                    //                 # code...
                    //                 $product=M('product')->where(array('id'=>$value['pid']))->find();
                    //                 if($product['type']==3){
                    //                     $yes_money+=$value['nums']*$product['advanceprice'];
                    //                 }
                    //             }
                    //             M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    //                 'pay_status'=>1,
                    //                 'pay_time'=>time(),
                    //                 ));
                    //             $orderdata= M('order')->where(array('orderid'=>$orderid))->find();
                    //             M('order')->where(array('orderid'=>$orderid))->save(array(
                    //                 'yes_money'=>$orderdata['yes_money']+$orderdata['wait_money'],
                    //                 'wait_money'=>$orderdata['wait_money']-$orderdata['wait_money']
                    //                 ));
                    //             $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，订单金额为".$order['money'] . "。";
                    //             M("message")->add(array(
                    //                 'uid'=>0,
                    //                 'tuid'=>0,
                    //                 'title'=>"订单全款支付成功",
                    //                 'value'=>$order['orderid'],
                    //                 'varname'=>"order",
                    //                 'inputtime'=>time()
                    //             ));
                    //             //$usr=M('member')->where('id=' . $order['uid'])->field("id,phone")->find();
                                
                    //             //$sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
                    //             //self::sendsms($sms);
                    //             self::update_integral($order['uid'],$money,1,"预购订单支付剩余金额成功，获得".$money."积分",'order');
                    //         }
                    //     }
                    //     exit("success");
                    //     break;
                    case "rc":
                        M('recharge')->where(array('orderid'=> $orderid))->save(array(
                            'paystatus'=>1,
                            'paytime'=>time()
                        ));
                        $data=M('recharge')->where(array('orderid'=> $orderid))->find();
                        $account=M("account")->where(array("uid"=>$data['uid']))->find();
                        M("account")->where(array("uid"=>$data['uid']))->save(array(
                            "usemoney"=>$data['money']+$account['usemoney'],
                            "recharemoney"=>$data['money']+$account['recharemoney'],
                            "total"=>$data['money']+$account['total'],
                            ));
                        M('account_log')->add(array(
                            'uid'=>$data['uid'],
                            'type'=>$data['type'].'_recharge',
                            'money'=>$data['money'],
                            'total'=>$account['total']+floatval($data['money']),
                            'usemoney'=>$account['usemoney']+floatval($data['money']),
                            'nousemoney'=>$account['nousemoney'],
                            'status'=>1,
                            'dcflag'=>1,
                            'remark'=>'充值'.$data['money'].'元',
                            'addip'=>get_client_ip(),
                            'addtime'=>time()
                            ));
                        $site_config=array();
                        foreach ($this->ConfigData as $r) {
                            if($r['groupid'] == 1){
                                $site_config[$r['varname']] = $r['value'];
                            }
                        }
                        self::update_integral($data['uid'],intval($data['money']*$site_config['integralset']),1,"在线充值钱包，赠送".intval($data['money']*$site_config['integralset'])."积分",$data['type'].'_recharge');
                        exit("success");
                        break;
                }
            
            case "refund":
                exit("success");
            default:
                exit("fail");
        }
    }
    public function integral($id){
        $order=M('order')->where('id=' . $id)->find();
        $orderid=$order['orderid'];
        $uid=$order['uid'];
        $pid=$order['pid'];
        $num=$order['integral'];
        if(!empty($num)&&$num!=0){
            $integral=M('integral')->where('uid=' . $uid)->find();
            $inte['useintegral']=$integral['useintegral']-$num;
            $inte['payed']=$integral['payed']+$num;
            M('integral')->where('uid=' . $uid)->save($inte);

            $log['type']=1;
            $log['uid']=$uid;
            $log['pid']=$pid;
            $log['orderid']=$orderid;
            $log['content']="订单花费" . $num . "积分";
            $log['useintegral']=$integral['useintegral'] - $num ;
            $log['inputtime']=time();
            M('integrallog')->add($log);

            $usr=M('member')->where('id=' . $uid)->field("id,phone")->find();
            $c="尊敬的". $order['name'] ."，您好！，您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，使用" . $num."积分，目前你的可用积分额度为". $log['useintegral']."。";
            M("message")->add(array(
                'uid'=>0,
                'tuid'=>$usr['id'],
                'title'=>"积分使用",
                'description'=>$c,
                'content'=>$c,
                'value'=>$orderid,
                'varname'=>"system",
                'inputtime'=>time()
            ));

            $sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
            self::sendsms($sms);
        }
    }
    public function wallet($id,$wallet=0.00){
        $order=M('order')->where('id=' . $id)->find();
        $orderid=$order['orderid'];
        $uid=$order['uid'];
        if(!empty($wallet)&&$wallet!=0.00){
            $num=$wallet;
        }else{
            $num=$order['wallet'];
        }
        
        if(!empty($num)&&$num!=0){
            $account=M('account')->where('uid=' . $uid)->find();
            $inte['nousemoney']=$account['nousemoney']-$num;
            $inte['paymoney']=$account['paymoney']+$num;
            M('account')->where('uid=' . $uid)->save($inte);

            M('account_log')->add(array(
                'uid'=>$uid,
                'type'=>'order',
                'money'=>$order['wallet'],
                'total'=>$account['total']-floatval($order['wallet']),
                'usemoney'=>$account['usemoney'],
                'nousemoney'=>$account['nousemoney']-floatval($order['wallet']),
                'status'=>1,
                'dcflag'=>2,
                'remark'=>'订单使用钱包支付，扣除金额'.$order['wallet'].'元',
                'addip'=>get_client_ip(),
                'addtime'=>time()
                ));

            $usr=M('member')->where('id=' . $uid)->field("id,phone")->find();
            $c="尊敬的". $order['name'] ."，您好！，您在".date("Y年m月d日 H时i分s秒") ."一笔订单成功使用钱包支付".$order['wallet']."元";
            M("message")->add(array(
                'uid'=>0,
                'tuid'=>$usr['id'],
                'title'=>"订单使用钱包支付",
                'description'=>$c,
                'content'=>$c,
                'value'=>$orderid,
                'varname'=>"system",
                'inputtime'=>time()
            ));

            //$sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
            //self::sendsms($sms);
        }
    }
    public function sendsms($data){
        $CCPRest = A("Api/CCPRest");
        $datas=array($data['content']);
        $CCPRest->sendTemplateSMS($data['phone'],$datas,'59988');
        M("sms")->add(array(
            "phone" => $data['phone'],
            "content" => $data['content'],
            "s_id" => $data['uid'],
            "inputtime" => time(),
        ));

    }
    public function updatestock($orderid){
        $order_productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
        foreach ($order_productinfo as $value)
        {
        	M('product')->where(array('id'=>$value['pid']))->setDec("stock",$value['nums']);

        }
    }
    public function get_orderrunnerinfo() {
		$ret = $GLOBALS['HTTP_RAW_POST_DATA'];
		$ret=json_decode($ret,true);
		$ruid = intval(trim($ret['ruid']));

		$user = M("Member")->where(array("id"=>$ruid))->find();
		if ($ruid == '' ) {
		    exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
		} elseif (!$user) {
		    exit(json_encode(array('code' => -200, 'msg' => "配送员不存在")));
		}  else {
		    
		    $dataset = array();
		    $dataset['realname'] = $user['realname'];
            $dataset['username'] = $user['username'];
            $dataset['phone'] = $user['phone'];
            $dataset['head'] = $user['head'];
            $dataset['nickname'] = $user['nickname'];
            $totaldistance=M('runerposition')->where(array('uid'=>$user['id']))->getField("totaldistance");
            $dataset['totaldistance'] = !empty($totaldistance)?$totaldistance:0.00;
            $totalorder=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$user['id'],'b.delivery_status'=>4))->count('a.id');
            $dataset['totalorder'] = !empty($totalorder)?$totalorder:0;
            $totalinvite=M('member')->where(array('groupid_id'=>$user['id']))->count();
            $dataset['totalinvite'] = !empty($totalinvite)?$totalinvite:0;
            $waitmoney=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$user['id'],'a.paystyle'=>2,'b.delivery_status'=>3))->sum("a.money");
            $dataset['waitmoney'] = !empty($waitmoney)?$waitmoney:0.00;
            $wait_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$user['id']))->getField("no_money");
            $dataset['wait_commissionmoney'] = !empty($wait_commissionmoney)?$wait_commissionmoney:0.00;
            $yes_commissionmoney=M('runercommission_info')->where(array('year'=>date("Y"),'month'=>date("m"),'ruid'=>$user['id']))->getField("yes_money");
            $dataset['yes_commissionmoney'] = !empty($yes_commissionmoney)?$yes_commissionmoney:0.00;
            $dataset['attitudestar']=getattitudestar($user['id']);
            $dataset['speedstar']=getspeedstar($user['id']);
            
		    exit(json_encode(array('code' => 200, 'msg' => "获取数据成功", 'data' =>$dataset)));
		}
    }
}

<?php

namespace Web\Controller;

use Web\Common\CommonController;

use Org\Util\Page;

class OrderController extends CommonController {
    
    public function index(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $waitpay=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.pay_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.paystyle'=>array("in","1,3"),'a.isserviceorder'=>0,'_string'=>'(a.paystyle!=2 and a.iscontainsweigh=1 and c.package_status=1) or (a.paystyle!=2 and a.iscontainsweigh=0)'))->count();
            $waitpackage=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.package_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0,'a.isserviceorder'=>0,'_string'=>'(c.pay_status=0 and (a.iscontainsweigh=1 or a.paystyle=2)) or (c.pay_status=1 and a.iscontainsweigh=0 and ((a.ordertype!=3)or (a.ordertype=3 and a.puid=0)))'))->count();
            $waitconfirm=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0))->count();
            $waitevaluate=M('order a')->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.uid'=>$uid,'c.pay_status'=>1,'c.delivery_status'=>4,'c.status'=>5,'c.evaluate_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0))->count();           
            
            $this->assign('waitpay',$waitpay);
            $this->assign('waitpackage',$waitpackage);
            $this->assign('waitconfirm',$waitconfirm);
            $this->assign('waitevaluate',$waitevaluate);
            
            $this->display();
        }
    }
    
    //订单全部列表json返回
    public function init(){
        $uid = session('uid');
        $pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
        $type = $_REQUEST['type'];
        if (empty($type)){
            $type = "all";
        }
        
        $num=10;
        $p = new \Think\Page($count, 10);
        
        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$p==''||$num=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        } elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        } else {
            $where=array();
            $order=array();
            $field=array();
            $field=array('a.orderid,a.ruid,a.puid,a.ruid,a.storeid,a.wallet,a.discount,b.title as storename,b.thumb as storethumb,a.money,a.total,a.inputtime,a.paystyle,a.paytype,c.status,c.pay_status,c.package_status,c.delivery_status,c.buyer_sendstatus,c.evaluate_status,a.ordertype,a.yes_money,a.wait_money,c.donetime,a.iscontainsweigh');
            switch ($type) {
                case 'all':
                    # code...
                    $where=array('a.uid'=>$uid,'c.close_status'=>0,'a.isserviceorder'=>0);
                    $order=array('a.inputtime'=>'desc');
                    break;
                case 'waitpay':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.paystyle'=>array("in","1,3"),'a.isserviceorder'=>0,'_string'=>'(a.paystyle!=2 and a.iscontainsweigh=1 and c.package_status=1) or (a.paystyle!=2 and a.iscontainsweigh=0)');
                    $order=array('a.inputtime'=>'desc');
                    break;
                case 'waitpackage':
                    # code...
                    $where=array('a.uid'=>$uid,'c.package_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0,'_string'=>'(c.pay_status=0 and (a.iscontainsweigh=1 or a.paystyle=2)) or (c.pay_status=1 and a.iscontainsweigh=0 and ((a.ordertype!=3)or (a.ordertype=3 and a.puid=0)))');
                    $order=array('a.inputtime'=>'desc');
                    break;
                case 'waitconfirm':
                    # code...
                    $where=array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                    $order=array('c.delivery_time'=>'asc','c.id'=>'desc');
                    break;
                case 'waitevaluate':
                    # code...
                    $where=array('a.uid'=>$uid,'c.pay_status'=>1,'c.delivery_status'=>4,'c.status'=>5,'c.evaluate_status'=>0,'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                    $order=array('a.inputtime'=>'desc');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")
                              ->join("left join zz_order_time c on a.orderid=c.orderid")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->page($pagenum,$num)
                              ->select();
            
            foreach ($data as $key => $value) {
                # code...
                $productinfo=M('order_productinfo a')
                    ->join("left join zz_product b on a.pid=b.id")
                    ->where(array('a.orderid'=>$value['orderid']))
                    ->field("a.pid,a.nums,b.thumb,b.title,b.description,a.price as nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh,a.weigh,b.selltime,b.advanceprice,b.price")->select();
                foreach ($productinfo as $k => $val)
                {
                    $productinfo[$k]['unit']=getunit($val['unit']);
                    $productinfo[$k]['title']=str_cut($val['title'],10);
                    //预购订单判断时间来显示是否显示去支付下笔订单
                    if ($val['product_type']=='3')
                    {
                        $temptime = $val['selltime'] - time();
                        if ($temptime < 0){
                            $productinfo[$k]['yugoustatus']  = '1';
                        }else{
                            $productinfo[$k]['yugoustatus']  = '0';
                        }
                    }
                    if($val['product_type'] == '4' && $val['isweigh'] == '0'){
                        if($productinfo[$k]['isweights'] != '2')
                            $productinfo[$k]['isweights'] = '1';
                    }else if($val['product_type'] == '4' && $val['isweigh'] == '1'){
                        $productinfo[$k]['isweights'] = '2';
                    }else{
                        if($productinfo[$k]['isweights'] != '2' && $productinfo[$k]['isweights'] != '1')
                            $productinfo[$k]['isweights'] = '0';
                    }
                }
                
                $data[$key]['productinfo']=$productinfo;

            }
            //            echo json_encode(M('order_productinfo a')->_sql());
            //            exit;
            if($data){
                exit(json_encode($data));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"There is no such order information!")));
            }
        }
    }
    
    //送货段订单详情
    public function  sendshow(){
        $orderid = trim(I('get.orderid'));
        $this->assign('orderid',$orderid);

        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array();
            $type=M('order')->where(array('orderid'=>$orderid))->getField("ordertype");
            switch ($type) {
                case '1':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.status,c.pay_status,c.package_status,c.delivery_status,c.evaluate_status,a.ordertype,a.isserviceorder');
                    break;
                case '2':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.yes_money,a.wait_money,a.ordertype,c.pay_status,a.isserviceorder');
                    break;
                case '3':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.package_status,c.delivery_status,c.evaluate_status,a.ordertype,c.pay_status,a.isserviceorder');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.orderid'=>$orderid))->field($field)->find();
            $data['linkurl']='http://' . $_SERVER['HTTP_HOST'] . U('Web/Order/sendshow',array('orderid'=>$data["orderid"]));
            $data['area']=getarea($data['area']);
            $data['productinfo']=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$data['orderid']))->field("a.pid,a.nums,b.thumb,b.title,b.description,a.price as nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh,a.weigh,b.selltime,b.price")->select();
            $data['pnums'] = count($data['productinfo']);
            
            //$yugoustatus = '';
            $wprices=0;
            $tempprice = 0.00;
            
            foreach ($data['productinfo'] as $key=>$value)
            {
                
                if($data['productinfo'][$key]['product_type'] == '4' && $data['productinfo'][$key]['isweigh'] == '0'){
                    if($data['isweights'] != '2')
                        $data['isweights'] = '1';
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['nowprice'] * $data['productinfo'][$key]['nums'];
                }else if($data['productinfo'][$key]['product_type'] == '4' && $data['productinfo'][$key]['isweigh'] == '1'){
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                    $wprices +=$data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                    $data['isweights'] = '2';
                }else{
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['nowprice'] * $data['productinfo'][$key]['nums'];
                    if($data['isweights'] != '2' && $data['isweights'] != '1')
                        $data['isweights'] = '0';
                }
                //预购订单判断时间来显示是否显示去支付下笔订单
                if ($data['productinfo'][$key]['product_type']=='3')
                {
                    $temptime = $data['productinfo'][$key]['selltime'] - time();
                    if ($temptime < 0){
                        $yugoustatus  = '1';
                    }else{
                        $yugoustatus  = '0';
                    }
                }
                $stotals= $data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                $data['productinfo'][$key]['stotals']=sprintf('%.2f', $stotals);
                $data['productinfo'][$key]['unit'] =  getunit($data['productinfo'][$key]['unit']);
                $tempprice +=$data['productinfo'][$key]['ttotal'];
            }
            $data['wprices'] = $wprices;
            $data['tempprice'] = $tempprice;
            if($data['isweights'] == '2')
            {
                if($data['isserviceorder']==1){
                    $data['shijipay'] = $data['total'];
                    $data['tempprice'] = $data['total'];
                }else{
                    $data['shijipay'] = $data['tempprice']-$data['discount']-$data['wallet'];
                }
                
            }
            else{
                $data['tempprice'] = $data['total'];
                $data['shijipay'] = $data['total']-$data['discount']-$data['wallet'];
            }
            $data['tempprice']=sprintf('%.2f', $data['tempprice']);
            $data['shijipay']=sprintf('%.2f', $data['shijipay']);
            //dump($yugoustatus);
            //dump($data['paystyle']);
            //dump($data['pay_status']);
            //dump($data['ordertype']);
            //dump($data['isweights']);
            if($data['paystyle'] == '2' || $data['pay_status'] == '1' || $data['ordertype'] == '3' || $data['isweights'] == '1' || $yugoustatus == '1'){
                $this->assign('ispayview','style="display:none"');
            }
            //dump($data);
            if($data['pay_status'] == '1' || $data['ordertype'] == '3' || $data['wait_money'] !='0'){
                $this->assign('iscencalview','style="display:none"');
            }
            if($data){
                //dump($data);
                $this->assign('data',$data);
                $this->assign('list',$data['productinfo']);
            }else{
                $this->error('err');
            }
        }
        $this->display();
    }
    
    //用户端订单详情
    public function  show(){
        $orderid = trim(I('get.id'));
        $this->assign('orderid',$orderid);

        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $field=array();
            $type=M('order')->where(array('orderid'=>$orderid))->getField("ordertype");
            switch ($type) {
                case '1':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.status,c.pay_status,c.package_status,c.delivery_status,c.evaluate_status,a.ordertype,a.iscontainsweigh');
                    break;
                case '2':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,a.yes_money,a.wait_money,a.ordertype,c.pay_status');
                    break;
                case '3':
                    # code...
                    $field=array('a.orderid,a.storeid,b.title as storename,b.thumb as storethumb,b.contact as worktel,a.discount,a.wallet,a.money,a.total,a.addresstype,a.name,a.tel,a.area,a.address,a.code,a.lat,a.lng,a.inputtime,a.buyerremark,a.cardremark,a.paystyle,a.paytype,c.package_status,c.delivery_status,c.evaluate_status,a.ordertype,c.pay_status');
                    break;
            }
            $data=M('order a')->join("left join zz_store b on a.storeid=b.id")->join("left join zz_order_time c on a.orderid=c.orderid")->where(array('a.orderid'=>$orderid))->field($field)->find();
            $data['linkurl']='http://' . $_SERVER['HTTP_HOST'] . U('Web/Order/sendshow',array('orderid'=>$data["orderid"]));
            $data['area']=getarea($data['area']);
            $data['productinfo']=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$data['orderid']))->field("a.pid,a.nums,b.thumb,b.title,b.description,a.price as nowprice,b.oldprice,b.standard,b.unit,b.ishot,a.product_type,a.isweigh,a.weigh,b.selltime,b.price")->select();
            $data['pnums'] = count($data['productinfo']);
            
            $wprices=0;
            $tempprice = 0.00;
            $data['isweights'] = 0;
            foreach ($data['productinfo'] as $key=>$value)
            {
                
                if($data['productinfo'][$key]['product_type'] == '4' && $data['productinfo'][$key]['isweigh'] == '0'){
                    if($data['isweights'] != '2')
                        $data['isweights'] = '1';
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['nowprice'] * $data['productinfo'][$key]['nums'];
                }else if($data['productinfo'][$key]['product_type'] == '4' && $data['productinfo'][$key]['isweigh'] == '1'){
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                    $wprices +=$data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                    $data['isweights'] = '2';
                }else{
                    $data['productinfo'][$key]['ttotal'] = $data['productinfo'][$key]['nowprice'] * $data['productinfo'][$key]['nums'];
                    if($data['isweights'] != '2' && $data['isweights'] != '1')
                        $data['isweights'] = '0';
                }
                //预购订单判断时间来显示是否显示去支付下笔订单
                if ($data['productinfo'][$key]['product_type']=='3')
                {
                    $temptime = $data['productinfo'][$key]['selltime'] - time();
                    if ($temptime < 0){
                        $yugoustatus  = '1';
                    }else{
                        $yugoustatus  = '0';
                    }
                }
                $stotals= $data['productinfo'][$key]['price'] * $data['productinfo'][$key]['weigh'];
                $data['productinfo'][$key]['stotals']=sprintf('%.2f', $stotals);
                $data['productinfo'][$key]['unit'] =  getunit($data['productinfo'][$key]['unit']);
                $tempprice +=$data['productinfo'][$key]['ttotal'];
            }
            $data['wprices'] = $wprices;
            $data['tempprice'] = $tempprice;
            if($data['isweights'] == '2')
            {
                if($data['isserviceorder']==1){
                    $data['shijipay'] = $data['total'];
                    $data['tempprice'] = $data['total'];
                }else{
                    $data['shijipay'] = $data['tempprice']-$data['discount']-$data['wallet'];
                }
                
            }
            else{
                $data['tempprice'] = $data['total'];
                $data['shijipay'] = $data['total']-$data['discount']-$data['wallet'];
            }
            $data['tempprice']=sprintf('%.2f', $data['tempprice']);
            $data['shijipay']=sprintf('%.2f', $data['shijipay']);
            //dump($yugoustatus);
            //dump($data['paystyle']);
            //dump($data['pay_status']);
            //dump($data['ordertype']);
            //dump($data['isweights']);
            //dump($data);
            if($data['paystyle'] == '2' || $data['pay_status'] == '1' || $data['ordertype'] == '3' || $data['isweights']=='1' || $yugoustatus == '1'){
                $this->assign('ispayview','style="display:none"');
            }
            //dump($data);
            if($data['pay_status'] == '1' || $data['ordertype'] == '3' || $data['wait_money'] !='0'){
                $this->assign('iscencalview','style="display:none"');
            }
            
            if($data){
                                //dump($data);
                $this->assign('data',$data);
                $this->assign('list',$data['productinfo']);
            }else{
                $this->error('err');
            }
        }
        $this->display();
        
    }
    //用户端订单状态
    public function status(){
        $uid = intval(trim(session('uid')));
        $orderid = trim(I('get.id'));
        $this->assign('orderid',$orderid);
        $user=M('Member')->where(array('id'=>$uid))->find();
        if ($uid == ''||$orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else {
            $data=M('message')->where(array('varname'=>'order','value'=>$orderid))->field(array('id,title,content,inputtime'))->order(array('id'=>'asc'))->select();
            foreach ($data as $key=>$value){
                $data[$key]['sendtime'] = date('Y-m-d H:i:s',$data[$key]['inputtime']);
            }
            if($data){
                $this->assign('list',$data);
                //$this->assign('data',)
            }else{
                $this->error('err');
            }
        }
        $this->display();
    }
    
    //送货端订单状态
    public function sendstatus(){
        $orderid = trim(I('get.orderid'));
        if ($orderid=='') {
            exit(json_encode(array('code' => -200, 'msg' => "Request parameter is null!")));
        }else {
            $data=M('message')->where(array('varname'=>'order','value'=>$orderid))->field(array('id,title,content,inputtime'))->order(array('id'=>'asc'))->select();
            foreach ($data as $key=>$value){
                $data[$key]['sendtime'] = date('Y-m-d H:i:s',$data[$key]['inputtime']);
            }
            if($data){
                $this->assign('list',$data);
            }else{
                //                $this->error('err');
            }
        }
        
        $this->display();
    }

    public function closeorder(){
        $uid = session('uid');
        $orderid = $_REQUEST['orderid'];
        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        if($uid==''||$orderid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }elseif(empty($order)){
            exit(json_encode(array('code'=>-200,'msg'=>"The Order is not exist!")));
        }elseif($order['paystatus']==1){
            exit(json_encode(array('code'=>-200,'msg'=>"订单已付款不能关闭交易")));
        }else{
            //$select['uid']=$user['id'];
            $select['orderid']=$orderid;
            //$this->ajaxReturn($select);
            //exit;
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
                $this->ajaxReturn("success");
            }else{
                $this->ajaxReturn("faild");
            }
        }
    }

    

    public function getlngandlat(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $user=M('Member')->where(array('id'=>$uid))->find();
            if(empty($user)){
                exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
            } else {
                $where=array();
                $order=array('a.inputtime'=>'desc');
                $field=array('a.orderid,d.realname as runerrealname,d.username as runerusername,d.phone as runerphone,d.head as runerhead,c.delivery_time,c.donetime,c.inputtime,a.lat,a.lng');                
                $where=array('a.uid'=>$uid,'c.delivery_status'=>1,'a.ruid'=>array('neq',0),'c.cancel_status'=>0,'c.close_status'=>0,'a.isserviceorder'=>0);
                        
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
                if($data){
                    $this->ajaxReturn($data);
                }else{
                    exit(json_encode(array('code'=>-201,'msg'=>"订单数据为空")));
                }
            }
        }
    }
    public function logistics(){
        $this->display();
    }
}
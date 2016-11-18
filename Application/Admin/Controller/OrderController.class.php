<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class OrderController extends CommonController {
    public function _initialize() {
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    /*
     * 订单列表
     */
    public function index() {
        $search = I('get.search');
        $where = array();
        $where['close_status']=0;
        $where['cancel_status']=0;
        if(!empty($_SESSION['storeid'])){
            $where['storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }

            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }

    /**
     * 编辑内容
     */
    public function edit() {
        if ($_POST) {
            if (M("order")->create()) {
                $id = M("order")->save();
                if (!empty($id)) {
                    M('order')->where("id="  . $_POST['id'])->save(array(
                        'paytime'=>time()
                    ));
                    $this->success("修改成功！", U("Admin/order/index"));
                } else {
                    $this->error("修改失败！");
                }
            } else {
                $this->error(M("order")->getError());
            }
            if($_POST['paytype']==5){
                if($_POST['paystatus']==1){
                    if($_POST['deliverystatus']==0){
                        self::integral($_POST['id']);
                        self::sendsms($_POST['id']); 
                    }
                    
                }
            }
        } else {
            $id = I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("ID参数错误");
            }
            $data = D("order")->where("id=" . $id)->find();

            $pro = M("product")->where("id=" . $data['pid'])->find();
            $pro['catname']=M('category')->where('id=' . $pro['catid'])->getField("catname");
            $this->assign("data", $data);
            $this->assign("pro", $pro);
            $this->display();
        }
    }
    public function sendsms($id){
        $order=M('order')->where('id=' . $id)->find();
        $orderid=$order['orderid'];
        $dailiid=$order['dailiid'];
        $money=$order['money'];
        $price=$order['price'];
        $nums=$order['nums'];
        $area=$order['area'];
        $commission=M('product')->where(array('id'=>$order['pid']))->getField("commission");
        $commissionset=explode("|", $commission);

        $num=ceil($order['money'] * 0.03);
        $integral=M('integral')->where('uid=' . $order['uid'])->find();
        $inte['useintegral']=$integral['useintegral']+$num;
        $inte['totalintegral']=$integral['totalintegral']+$num;
        M('integral')->where('uid=' . $order['uid'])->save($inte);

        $log['type']=0;
        $log['uid']=$order['uid'];
        $log['pid']=$order['pid'];
        $log['orderid']=$orderid;
        $log['content']="成功支付订单，奖励" . $num . "积分";
        $log['useintegral']=$integral['useintegral'] + $num ;
        $log['totalintegral']=$integral['totalintegral'] + $num ;
        $log['inputtime']=time();
        M('integrallog')->add($log);
        $usr=M('member')->where('id=' . $order['uid'])->field("id,phone")->find();
        $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，订单金额为".$order['money'] . "元，系统奖励" . $num."积分，目前你的可用积分额度为". $log['useintegral']."。";
        M("message")->add(array(
            'uid'=>$usr['id'],
            'title'=>"订单支付成功",
            'content'=>$c,
            'value'=>$orderid,
            'valname'=>"integraladd",
            'inputtime'=>time()
        ));
        
        $sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id']));
        $re= \Api\Common\CommonController::sendsms($sms);
        if(!empty($dailiid)){
            $select['username']=$dailiid;
            $user=M('member')->where($select)->find();
            if($user){
                $c="尊敬的". $user['realname'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功代理一笔订单，您可获取到服务佣金" . $commissionset[0] * $nums ."元。请尽快登陆系统申请结算。";
                M("message")->add(array(
                    'uid'=>$user['id'],
                    'title'=>"订单处理成功",
                    'content'=>$c,
                    'value'=>$orderid,
                    'valname'=>"commissionadd",
                    'inputtime'=>time()
                ));
                $sms=json_encode(array('phone'=>$user['phone'],'content'=>$c,'uid'=>$user['id']));
                $re= \Api\Common\CommonController::sendsms($sms);
                if(!empty($user['groupid_id'])){
                    $user1=M('member')->where("id=" . $user['groupid_id'])->find();
                    $c="尊敬的". $user1['realname'] ."，您好！您的下级二维码服务商于".date("Y年m月d日 H时i分s秒") ."代理一笔订单生成成功，您可获取到服务佣金" . $commissionset[1] * $nums ."元。请尽快登陆系统申请结算。";
                    M("message")->add(array(
                        'uid'=>$user['groupid_id'],
                        'title'=>"订单支付成功",
                        'content'=>$c,
                        'value'=>$orderid,
                        'valname'=>"commissionadd",
                        'inputtime'=>time()
                    ));
                    
                    $sms=json_encode(array('phone'=>$user1['phone'],'content'=>$c,'uid'=>$user1['id']));
                    $re= \Api\Common\CommonController::sendsms($sms);

                    if(!empty($user1['groupid_id'])){
                        $rruser=M('member')->where(array('id'=>$user1['groupid_id']))->find();
                        $c="尊敬的". $rruser['realname'] ."，您好！您的下下级二维码服务商于".date("Y年m月d日 H时i分s秒") ."代理一笔订单生成成功，您可获取到服务佣金" . $commissionset[2] * $nums ."元。请尽快登陆系统申请结算。";
                        M("message")->add(array(
                            'uid'=>$user1['groupid_id'],
                            'title'=>"订单支付成功",
                            'content'=>$c,
                            'value'=>$orderid,
                            'valname'=>"commissionadd",
                            'inputtime'=>time()
                        ));
                        
                        $sms=json_encode(array('phone'=>$rruser['phone'],'content'=>$c,'uid'=>$rruser['id']));
                        $re= \Api\Common\CommonController::sendsms($sms);

                        if(!empty($rruser['groupid_id'])){
                            $rrruser=M('member')->where(array('id'=>$rruser['groupid_id']))->find();
                            $c="尊敬的". $rrruser['realname'] ."，您好！您的下下下级二维码服务商于".date("Y年m月d日 H时i分s秒") ."代理一笔订单生成成功，您可获取到服务佣金" . $commissionset[3] * $nums ."元。请尽快登陆系统申请结算。";
                            M("message")->add(array(
                                'uid'=>$rruser['groupid_id'],
                                'title'=>"订单支付成功",
                                'content'=>$c,
                                'value'=>$orderid,
                                'valname'=>"commissionadd",
                                'inputtime'=>time()
                            ));
                            
                            $sms=json_encode(array('phone'=>$rrruser['phone'],'content'=>$c,'uid'=>$rrruser['id']));
                            $re= \Api\Common\CommonController::sendsms($sms);
                        }
                    }
                }
            }
            
        }
        // if(!empty($area)){
        //     $where['servicearea']=$area;
        //     $where['group_id']=2;
        //     $data=M('Member')->where($where)->find();
        //     $where=array();
        //     $where['status']=1;
        //     $where['area']=array('like',"%".$area."%");
        //     $data1=M('extraarea')->where($where)->find();
        //     if($data){
        //         $c="尊敬的". $data['realname'] ."，您好！".date("Y年m月d日 H时i分s秒") ."有一笔订单发往您所服务的地区，可获取到服务佣金150元。请尽快登陆系统申请结算。";
        //         M("message")->add(array(
        //             'uid'=>$data['id'],
        //             'title'=>"订单生成成功",
        //             'content'=>$c,
        //             'value'=>$orderid,
        //             'valname'=>"commissionadd",
        //             'inputtime'=>time()
        //         ));
        
        //         $sms=json_encode(array('phone'=>$data['phone'],'content'=>$c,'uid'=>$data['id']));
        //         $re= \Api\Common\CommonController::sendsms($sms);
        //     }elseif($data1){
        //         $member=M('member')->where("id=" . $data1['uid'])->find();
        //         $c="尊敬的". $member['realname'] ."，您好！".date("Y年m月d日 H时i分s秒") ."有一笔订单发往您所服务的地区，可获取到服务佣金150元。请尽快登陆系统申请结算。";
        //         M("message")->add(array(
        //             'uid'=>$member['id'],
        //             'title'=>"订单生成成功",
        //             'content'=>$c,
        //             'value'=>$orderid,
        //             'valname'=>"commissionadd",
        //             'inputtime'=>time()
        //         ));
        
        //         $sms=json_encode(array('phone'=>$member['phone'],'content'=>$c,'uid'=>$member['id']));
        //         $re= \Api\Common\CommonController::sendsms($sms);
        //     }
        // }
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
            $log['content']="购买产品花费" . $num . "积分";
            $log['useintegral']=$integral['useintegral'] - $num ;
            $log['inputtime']=time();
            M('integrallog')->add($log);
            
            $usr=M('member')->where('id=' . $uid)->field("id,phone")->find();
            $c="尊敬的". $order['name'] ."，您好！您在".date("Y年m月d日 H时i分s秒") ."成功支付一笔订单，使用" . $num."积分，目前你的可用积分额度为". $log['useintegral']."。";
            M("message")->add(array(
                'uid'=>$usr['id'],
                'title'=>"积分使用",
                'content'=>$c,
                'value'=>$orderid,
                'valname'=>"integralpay",
                'inputtime'=>time()
            ));
            
            $sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id']));
            $re= \Api\Common\CommonController::sendsms($sms);
        }
    }
    // 
    /**
     * 查看内容
     */
    public function show() {
        $orderid = I('orderid');
        if (empty($orderid)) {
            $this->error("订单号参数错误");
        }
        $data = M("order a")
            ->join("left join zz_order_time b on a.orderid=b.orderid")
            ->where(array('a.orderid'=>$orderid))
            ->find();
        if(!empty($data['error_thumb'])){
            $data['error_thumb']=explode("|",$data['error_thumb']);
        }
        
        $data['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$orderid))->select();
        $feedback=M('order_feedback')->where(array('orderid'=>$data['orderid']))->find();
        if($feedback){
            $data['isfeedback']=1;
        }else{
            $data['isfeedback']=0;
        }
        $this->assign("data", $data);
        $this->display();
    }
    /**
     *  删除
     */
    public function delete() {
        $orderid = $_GET['orderid'];
        if (D("order")->where(array('orderid'=>$orderid))->delete()) {
            M('order_time')->where(array('orderid'=>$orderid))->delete();
            M('order_productinfo')->where(array('orderid'=>$orderid))->delete();
            M('order_distance')->where(array('orderid'=>$orderid))->delete();
            $this->success("删除订单成功！");
        } else {
            $this->error("删除订单失败！");
        }
    }

    /*
     * 删除内容
     */

    public function del() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                $orderid=M('order')->where(array('id'=>$id))->getField("orderid");
                M("order")->delete($id);
                M('order_time')->where(array('orderid'=>$orderid))->delete();
                M('order_productinfo')->where(array('orderid'=>$orderid))->delete();
                M('order_distance')->where(array('orderid'=>$orderid))->delete();
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 配送处理
     */

    public function deal() {
        if(IS_POST){
            $puid = I('puid');
            $orderid=I('orderid');
            $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
            switch ($order['ordertype']) {
                case '1':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['pay_status']==0&&$order['paystyle']!=2){
                        if($order['iscontainsweigh']==0){
                            $this->error("该订单尚未支付完成，不能派送",U('Admin/Order/index'));
                        }
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;

                case '2':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['wait_money']!=0.00){
                        $this->error("该订单全款尚未支付完成，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;

                case '3':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;
            }
            $id = M("order")->where(array("orderid" => $orderid))->save(array(
                'puid'=>$puid,
                ));
            if($id){
                M('order_time')->where(array("orderid" => $orderid))->save(array(
                'distribute_time'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单等待包装中",
                    'value'=>$orderid,
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $this->success("派发成功");
            }else{
                $this->error("派发失败");
            }
        }else{
            $store = M("store")->where(array('status'=>2))->select();
            $this->assign("store", $store);

            $storeid=$this->storeid;
            $this->assign("storeid", $storeid);
            $orderid=I('orderid');
            $this->assign("orderid", $orderid);
            $this->display();
        }
    }
    /*
     * 配送处理
     */

    public function neworderdeal() {
        if(IS_POST){
            $puid = I('puid');
            $orderid=I('orderid');
            $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
            switch ($order['ordertype']) {
                case '1':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['pay_status']==0&&$order['paystyle']!=2){
                        if($order['iscontainsweigh']==0){
                            $this->error("该订单尚未支付完成，不能派送",U('Admin/Order/index'));
                        }
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;

                case '2':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['wait_money']!=0.00){
                        $this->error("该订单全款尚未支付完成，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;

                case '3':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0){
                        $this->error("该订单已被派送，不能重复派送",U('Admin/Order/index'));
                    }
                    break;
            }
            $id = M("order")->where(array("orderid" => $orderid))->save(array(
                'puid'=>$puid,
                ));
            if($id){
                M('order_time')->where(array("orderid" => $orderid))->save(array(
                'distribute_time'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单等待包装中",
                    'value'=>$orderid,
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $this->success("派发成功");
            }else{
                $this->error("派发失败");
            }
        }else{
            $store = M("store")->where(array('status'=>2))->select();
            $this->assign("store", $store);

            $storeid=$this->storeid;
            $this->assign("storeid", $storeid);
            $orderid=I('orderid');
            $this->assign("orderid", $orderid);
            $this->display();
        }
    }
    /*
     * 重新配送处理
     */

    public function dealagain() {
        if(IS_POST){
            $puid = I('puid');
            $orderid=I('orderid');
            $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
            switch ($order['ordertype']) {
                case '1':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['pay_status']==0&&$order['paystyle']!=2){
                        if($order['iscontainsweigh']==0){
                            $this->error("该订单尚未支付完成，不能派送",U('Admin/Order/index'));
                        }
                    }else if($order['status']==2&&$order['puid']!=0&&$order['package_status']==2){
                        $this->error("该订单已经包装完成，不能重新派送",U('Admin/Order/index'));
                    }
                    break;

                case '2':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['wait_money']!=0.00){
                        $this->error("该订单全款尚未支付完成，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0&&$order['package_status']==2){
                        $this->error("该订单已经包装完成，不能重新派送",U('Admin/Order/index'));
                    }
                    break;

                case '3':
                    # code...
                    if($order['status']==1){
                        $this->error("该订单尚未审核，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==3){
                        $this->error("该订单已被取消，不能派送",U('Admin/Order/index'));
                    }else if($order['status']==2&&$order['puid']!=0&&$order['package_status']==2){
                        $this->error("该订单已经包装完成，不能重新派送",U('Admin/Order/index'));
                    }
                    break;
            }
            $id = M("order")->where(array("orderid" => $orderid))->save(array(
                'puid'=>$puid
                ));
            if($id){
                M('order_time')->where(array("orderid" => $orderid))->save(array(
                   'distribute_time'=>time()
                   ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单等待包装中",
                    'value'=>$orderid,
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $this->success("派发成功",U('Admin/Order/index'));
            }else{
                $this->error("派发失败",U('Admin/Order/index'));
            }
        }else{
            $store = M("store")->where(array('status'=>2))->select();
            $this->assign("store", $store);

            $storeid=$this->storeid;
            $this->assign("storeid", $storeid);
            $orderid=I('orderid');
            $this->assign("orderid", $orderid);
            $this->display();
        }
    }
    public function ajax_getpuser(){
        if($_POST['storeid']==0){
            $data=M('user')->where(array('group_id'=>4,'role'=>2,'status'=>1))->order(array('id'=>'desc'))->select();
        }else{
            $data=M('user')->where(array('group_id'=>4,'role'=>2,'storeid'=>$_POST['storeid'],'status'=>1))->order(array('id'=>'desc'))->select();
        }
        echo json_encode($data);
    }
    //导入excel内容转换成数组 
    public function excelimport($filePath){
        import("Org.Util.PHPExcel");
        $PHPExcel = new \PHPExcel(); 
        /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
        $PHPReader = new \PHPExcel_Reader_Excel2007(); 
        if(!$PHPReader->canRead($filePath)){ 
            $PHPReader = new \PHPExcel_Reader_Excel5(); 
            if(!$PHPReader->canRead($filePath)){ 
                $PHPReader = new \PHPExcel_Reader_CSV();
                if(!$PHPReader->canRead($filePath)){
                    echo 'no Excel'; 
                    return false; 
                }
            } 
        } 
        
        $PHPExcel = $PHPReader->load($filePath); 
        $currentSheet = $PHPExcel->getSheet(0)->toArray();  //读取excel文件中的第一个工作表
        return $currentSheet;
    }
    public function count() {
        $finish = D("order")->where("deliverystatus=2")->count();
        $ing = D("order")->where("deliverystatus=1")->count();
        $uning = D("order")->where("deliverystatus=0")->count();
        $unpay = D("order")->where("paystatus=0")->count();
        $pay = D("order")->where("paystatus=1")->count();
        $total = D("order")->sum('money');
        $unmoney = D("order")->where("paystatus=0")->sum('money');
        $money = D("order")->where("paystatus=1")->sum('money');
        $this->assign("all", $all);
        $this->assign("finish", $finish);
        $this->assign("ing", $ing);
        $this->assign("uning", $uning);
        $this->assign("unpay", $unpay);
        $this->assign("pay", $pay);
        $this->assign("total", $total);
        $this->assign("unmoney", $unmoney);
        $this->assign("money", $money);

        $this->display();
    }

    public function apply() {
        $search = I('post.search');
        $where = array();
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $status = I('post.status', null, 'intval');
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }

            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $where["orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("orderapply")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("orderapply")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $key => $value) {
            # code...
            $data[$key]['phone']=M('member')->where("id=" . $value['uid'])->getField("phone");
            $data[$key]['email']=M('member')->where("id=" . $value['uid'])->getField("email");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->display();
    }
    /**
     *  删除
     */
    public function applydelete() {
        $id = $_GET['id'];
        if (D("orderapply")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 删除内容
     */

    public function applydel() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("orderapply")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function review(){
        if (IS_POST) {
            $status=I('status');
            $data=M('orderapply')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('orderapply')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功！", U("Admin/Order/apply"));
            }elseif($id>0&&$status==3){
                $this->success("审核成功！", U("Admin/Order/apply"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id = I('get.id', null, 'intval');
            $data = D("orderapply")->where("id=" . $id)->find();
            $data['phone']=M('Member')->where("id=" . $data['uid'])->getField("phone");
            $this->assign("data", $data);
            $this->display();
        }
    }
    public function orderprint(){
        $orderid=I('orderid');
        $order=M('order a')
            ->join("left join zz_store b on a.storeid=b.id")
            ->field("a.*,b.title as storename")
            ->where(array('orderid'=>$orderid))
            ->find();
        $order_productinfo=M('order_productinfo a')
            ->join("left join zz_product b on a.pid=b.id")
            ->field("a.*,b.unit,b.title as productname,b.productnumber,b.price,b.type,b.standard,b.advanceprice,b.nowprice")
            ->where(array('orderid'=>$orderid))
            ->select();
        foreach ($order_productinfo as $key => $value)
        {
        	
            $total=0.00;
            $price=0.00;
            if($value['type']==4){
                if($value['isweigh']==1){
                    $total=$value['weigh']*$value['price'];
                    $price=$value['price'];
                }else{
                    $total=$value['nums']*$value['nowprice'];
                    $price=$value['nowprice'];
                }
                
            }elseif($value['type']==3){
                $total=$value['nums']*$value['advanceprice'];
                $price=$value['advanceprice'];
            }else{
                $total=$value['nums']*$value['nowprice']; 
                $price=$value['nowprice'];
            }
            $order_productinfo[$key]['price']=$price;
            $order_productinfo[$key]['total']=$total;
        }
        
        $this->assign("order_productinfo", $order_productinfo);
        
        $this->assign("order", $order);
        $this->display();
    }
    public function order_import(){

        $this->display();
    }
    public function storeimport(){
        if ($_POST) {
            $file=I('file');
            $filetmpname = $_SERVER['DOCUMENT_ROOT'] . $file;
            import("Org.Util.PHPExcel");
            $PHPExcel = new \PHPExcel(); 
            $PHPReader = new \PHPExcel_Reader_Excel2007(); 
            if(!$PHPReader->canRead($filetmpname)){ 
                $PHPReader = new \PHPExcel_Reader_Excel5(); 
                if(!$PHPReader->canRead($filetmpname)){ 
                    $PHPReader = new \PHPExcel_Reader_CSV();
                    if(!$PHPReader->canRead($filetmpname)){
                        $this->error("no Excel");
                    }
                } 
            } 
            $PHPExcel = $PHPReader->load($filetmpname); 
            for ($i = 0; $i < 4; $i++) {
            	$sheet = $PHPExcel->getSheet($i);
                $highestRow = $sheet->getHighestRow();
                $highestColumm = $sheet->getHighestColumn();
                for ($column = 'B'; $column <= $highestColumm; $column++) {
                    for ($row = 2; $row <= $highestRow; $row++){
                        $wuid=M('artificer')->where(array('storeid'=>$_POST['storeid'],'worknumber'=>$sheet->getCell('A'.$row)->getValue()))->getField("id");
                        if($wuid){
                            $worknumber=$sheet->getCell('A'.$row)->getValue();
                            $time=strtotime($sheet->getCell('A1')->getValue().$sheet->getCell($column."1")->getValue());
                            $status=$sheet->getCell($column.$row)->getValue();
                            $schedulenum=M('schedule')->where(array('storeid'=>$_POST['storeid'],'wuid'=>$wuid,'date'=>date("Y-m-d H:i:s",$time)))->count();
                            if($schedulenum==0){
                                $ids=M('schedule')->add(array(
                                    'wuid'=>$wuid,
                                    'worknumber'=>$worknumber,
                                    'storeid'=>$_POST['storeid'],
                                    'time'=>$time,
                                    'date'=>date("Y-m-d H:i:s",$time),
                                    'status'=>$status,
                                    'inputtime'=>time()
                                    ));
                            }else{
                                M('schedule')->where(array('storeid'=>$_POST['storeid'],'wuid'=>$wuid,'date'=>date("Y-m-d H:i:s",$time)))->delete();
                                $ids=M('schedule')->add(array(
                                    'wuid'=>$wuid,
                                    'worknumber'=>$worknumber,
                                    'storeid'=>$_POST['storeid'],
                                    'time'=>$time,
                                    'date'=>date("Y-m-d H:i:s",$time),
                                    'status'=>$status,
                                    'inputtime'=>time()
                                    ));
                            }
                            
                        }
                        
                    }
                }
            }
            if(empty($ids)){
                $this->error("导入排班表失败");
            }else{
                $this->success('导入排班表成功');
            }
        } else {
            $storeid=I('storeid','',intval);
            $this->assign("storeid",$storeid);
            $this->display();
        }
    }
    public function neworder(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['storeid']=$this->storeid;
        }
        $where['close_status']=0;
        $where['cancel_status']=0;
        $end_time=time();
        $start_time=strtotime("-10 hours");
        $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
        if (!empty($search)) {
            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function waitpay(){
        $search = I('get.search');
        $where = array();
        $where['b.status']=2;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['_string']="(a.paystyle!=2 and b.pay_status=0 and a.ordertype!=2) or (b.pay_status=0 and a.ordertype=2)";
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function paynotice(){
        $orderid=I('orderid');
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        if(empty($order)){
            $this->error("订单号错误",U('Admin/Order/waitpay'));
        }
        $mid=M('push')->add(array(
                'title'=>"待支付通知",
                'description'=>"您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！",
                'content'=>"您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！",
                'isadmin' => 1,
                'type'=>1,
                'inputtime' => time(),
                'username' => $_SESSION['user'],
            ));
        if($mid){
            $registration_id=M('member')->where(array('id'=>array('eq',$order['uid'])))->getField("deviceToken");
            $receiver=$registration_id;
            if(!empty($receiver)){
                $extras = array("mid"=>$orderid,'message_type'=>'order');
                PushQueue($mid,'order',$receiver, "待支付通知","您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！", serialize($extras),1);
            }
            $this->success("操作成功",U('Admin/Order/waitpay'));
        }else{
            $this->error("操作失败",U('Admin/Order/waitpay'));
        }
    }
    public function waitreview(){
        $search = I('get.search');
        $where = array();
        $where['b.status']=1;
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function waitdistribute(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['a.puid']=0;
        $where['_string']="(a.paystyle=2 and b.pay_status=0 and ((a.ordertype=2 and a.yes_money_total>=a.total)or a.ordertype!=2)) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function dodistribute(){
        if(IS_POST){
            $orderid=I('orderid');
            $order=M('order')->where(array('orderid'=>$orderid))->find();
            if(empty($order)){
                $this->error("订单号错误");
            }
            $id=M('order')->where(array('orderid'=>$orderid))->setField("ruid",$_POST['ruid']);
            if($id){
                $c="【蔬果先生】嗨咯！你有一个极速达订单".$orderid."急需你配送，赶紧回来取货吧！";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$_POST['ruid'],
                    'title'=>"提示送货员",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"runner",
                    'inputtime'=>time()
                ));
                $ruser=M('member')->where(array('id'=>$_POST['ruid']))->find();
                $data=json_encode(array('phone'=>$ruser['phone'],'datas'=>array($ruser['realname'],$order['orderid']),'templateid'=>"64805"));
                $CCPRest = A("Api/CCPRest");
                $CCPRest->sendsmsapi($data);

                $message_type='noticerunernotice';
                $push['title']="通知配货员取订单提醒";
                $push['description']=$c;
                $push['content']=$c;
                //$push['message_type']=$message_type;
                $push['isadmin']=1;
                $push['inputtime']=time();
                $push['username']=$_SESSION['user'];
                $mid = M("Push")->add($push);
                if ($mid) {
                    $receiver = $ruser["deviceToken"];//接收者
                    $extras = array("orderid"=>$orderid,'message_type'=>$message_type);
                    if(!empty($receiver)){
                        PushQueue($mid,$message_type,$receiver, $push['title'],$push['description'], serialize($extras),2);
                    }
                }
                $this->success("提示送货员成功",U('Admin/Order/speed'));
            }else{
                $this->error("提示送货员失败");
            }
            
        }else{
            $orderid=I('orderid');
            $this->assign("orderid", $orderid);
            $order=M('order')->where(array('orderid'=>$orderid))->find();
            if(empty($order)){
                $this->error("订单号错误");
            }
            $store=M('store')->where(array('id'=>$order['storeid']))->find();
            $this->assign("store", $store);

            $runer=M('store_member a')->join("join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$order['storeid']))->select();
            $a = $store['lng'] . "|" . $store['lat'];
            $markerArrStr="[{ title: '".$store['title']."', content: \"门店所在位置\", point: '".$a."'}";
            foreach ($runer as $key => $value) {
                # code...$
                $runer[$key]['ordernum']=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$value['ruid'],'b.delivery_status'=>1))->count();
                $lastposition=M('runerposition')->where(array('uid'=>$value['ruid']))->getField("lastposition");
                $position=$store['lat'].",".$store['lng'];
                $Map=A("Api/Map");
                $distanceinfo=$Map->get_distance_baidu("driving",$position,$lastposition);
                $runer[$key]['distance']=$distanceinfo['distance']['value']/1000;
                if(!empty($lastposition)){
                    $lastpositions=explode(",", $lastposition);
                    $a = $lastpositions[1] . "|" . $lastpositions[0];
                    $markerArrStr.=",{ title: '".$value['realname']."', content: \"距离订单   {$runer[$key]['distance']}千米<br/>有{$runer[$key]['ordernum']}笔订单在派送\", point: '".$a."'}";
                }
                
            }
            $markerArrStr.="]";
            $this->assign("markerArrStr", $markerArrStr);
            $this->assign("runer", $runer);

            $this->display();
        }
    }
    public function distributedone(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['a.puid']=array('neq',0);
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['_string']="(a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function delivery(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=2;
        $where['b.delivery_status']=1;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function done(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=5;
        $where['b.delivery_status']=4;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
            $feedback=M('order_feedback')->where(array('orderid'=>$value['orderid']))->find();
            if($feedback){
                $data[$key]['isfeedback']=1;
            }else{
                $data[$key]['isfeedback']=0;
            }
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function speed(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['a.isspeed']=1;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function close(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=6;
        $where['b.close_status']=1;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function packageing(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=1;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function packagedone(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=2;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function errororder(){
        $search = I('get.search');
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=array('in','2,4');
        $where['b.error_status']=array('in','1,2');
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }
            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function cancelorder() {
        $search = I('get.search');
        $where = array();
        $where['b.close_status']=0;
        $where['b.status']=3;
        $where['b.cancel_status']=1;
        if(!empty($_SESSION['storeid'])){
            $where['storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["c.cancel_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["c.cancel_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['c.cancel_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }

            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function doerror(){
        $orderid=I('orderid');
        $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'status'=>4,
                    'error_status'=>2,
                    'error_donetime'=>time(),
                    ));
        if($id){
            M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$orderid,
                'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."进行了异常订单审核，审核成功！",
                'username'=>$_SESSION['user'],
                'status'=>1,
                'inputtime'=>time()
                ));
            $this->success("操作成功");
        }else{
            M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$orderid,
                'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."进行了异常订单审核，审核失败！",
                'username'=>$_SESSION['user'],
                'status'=>0,
                'inputtime'=>time()
                ));
            $this->error("操作失败");
        }
    }
    public function cancel(){
        $orderid=I('orderid');
        $order=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.orderid'=>$orderid))->find();
        // if($order['package_status']!=0){
        //     $this->error("该订单不能取消");
        // }else{
            $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                        'status'=>3,
                        'cancel_status'=>1,
                        'cancel_time'=>time(),
                        ));
            if($id){
                $c="您好！系统管理员在".date("Y年m月d日 H时i分s秒") ."成功取消了一笔订单。";
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>$order['uid'],
                    'title'=>"系统管理员取消订单",
                    'description'=>$c,
                    'content'=>$c,
                    'value'=>$order['orderid'],
                    'varname'=>"system",
                    'inputtime'=>time()
                ));
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"系统管理员取消订单成功",
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
                M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单已取消",
                    'value'=>$orderid,
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
                $this->success("操作成功");
            }else{
                $this->error("操作失败");
            }
        //}
        
    }
    public function doclose(){
        $orderid=I('orderid');
        $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'status'=>6,
                    'close_status'=>1,
                    'close_time'=>time(),
                    ));
        if($id){
            M("message")->add(array(
                    'uid'=>0,
                    'tuid'=>0,
                    'title'=>"订单已关闭",
                    'value'=>$orderid,
                    'varname'=>"order",
                    'inputtime'=>time()
                ));
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }
    public function doreview(){
        $orderid=I('orderid');
        $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'status'=>2
                    ));
        if($id){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }
    
    public function doreviews(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $orderid) {
                M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'status'=>2
                    ));
            }
            $this->success("操作成功");
        } else {
            $this->error("操作失败！");
        }
    }
    public function deals() {
        if(IS_POST){
            $puid = I('puid');
            $orderids=session("orderid");
            foreach ($orderids as $orderid) {
                M("order")->where(array("orderid" => $orderid))->save(array(
                'puid'=>$puid,
                ));
            }
            session("orderid",null);
            $this->success("操作成功",U('Admin/Order/distributedone'));
        }else{
            $store = M("store")->where(array('status'=>2))->select();
            $this->assign("store", $store);

            $storeid=$this->storeid;
            $this->assign("storeid", $storeid);
            $orderid=I('ids');
            if (empty($orderid)) {
                $this->error("没有信息被选中！");
            }
            session("orderid",$orderid);
            //$this->assign("orderid", serialize($orderid));
            $this->display();
        }
    }
    public function paynotices(){
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $orderid) {
                $order=M('order')->where(array('orderid'=>$orderid))->find();
                $mid=M('push')->add(array(
                    'title'=>"待支付通知",
                    'description'=>"您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！",
                    'content'=>"您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！",
                    'isadmin' => 1,
                    'type'=>1,
                    'inputtime' => time(),
                    'username' => $_SESSION['user'],
                ));
                if($mid){
                    $registration_id=M('member')->where(array('id'=>array('eq',$order['uid'])))->getField("deviceToken");
                    $receiver=$registration_id;
                    if(!empty($receiver)){
                        $extras = array("mid"=>$orderid,'message_type'=>'order');
                        PushQueue($mid,'order',$receiver, "待支付通知","您好，您有一笔订单号为".$orderid."的订单尚未支付，现在可以前去支付哦！", serialize($extras),1);
                    }
                }
            }
            $this->success("操作成功");
        } else {
            $this->error("操作失败！");
        }
    }
    public function addorder(){
        $orderid=I('orderid');
        $data=M('order')->where(array('orderid'=>$orderid))->find();
        if(IS_POST){
            if(empty($_POST['pid'])){
                $this->error("请先选择商品");
            }
            $iscontainsweigh=0;
            $oldproductinfo=$_POST['productinfo'];
            $productinfo=array();
            foreach ($_POST['pid'] as $value)
            {   
                $productinfo[$value]=$oldproductinfo[$value];
            }
            $containsweighnum=M('product')->where(array('id'=>array('in',$_POST['pid'])))->count();
            if($containsweighnum>0){
                $iscontainsweigh=1;
            }
            $uid=intval(trim($data['uid']));
            $storeid=intval(trim($data['storeid']));
            $orderremark=trim($_POST['orderremark']);
            $ordertype=intval(trim($data['ordertype']));

            $user=M('Member')->where(array('id'=>$uid))->find();
            $store=M('store')->where(array('id'=>$storeid))->find();

            $areaset=explode(",",$_POST['area']);
            $servicearea=explode(",",$store['servicearea']);
            
            if(!in_array($areaset[count($areaset)-1],$servicearea)){
                $this->error("亲，当前收货地址不属于此店铺配送范围，请修改收货地址或者更换店铺下单。");
            }else{
                $orderdata['uid']=$uid;
                $orderdata['relationorderid']=$orderid;
                $orderdata['storeid']=$storeid;
                $orderdata["orderid"] = "wm".date("YmdHis", time()) . rand(100, 999);
                $orderdata['ordercode'] = phpcode('http://' . "m.esugo.cn" . U('Web/Order/sendshow',array('orderid'=>$orderdata["orderid"])),$orderdata["orderid"]);
                $orderdata['title'] = "售后订单——购买".count($productinfo)."件商品";
                $orderdata['nums']=count($productinfo);
                
                foreach ($productinfo as $key => $value) {
                    # code...
                    $product=M('product')->where(array('id'=>$key))->find();
                    if($product['isoff']==1){
                        $this->error("订单中有商品已被下架了");    
                    }
                    if($product['type']==3&&$product['selltime']<time()){
                        $this->error("订单中有商品已过期啦"); 
                    }
                    if($product['type']==2&&$product['expiretime']<time()){
                        $this->error("订单中有商品已过期啦"); 
                    }
                    if($product['stock']==0){
                        $this->error("订单中有商品正在补货中"); 
                    }
                    if($value>$product['stock']&&$product['stock']>0){
                        $this->error("订单中有商品库存不足"); 
                    }
                }
                $orderdata['isspeed']=0;
                $orderdata['start_sendtime']=0;
                $orderdata['end_sendtime']=0;

                $orderdata['money']=0.00;
                $orderdata['total']=0.00;

                $orderdata['delivery']=0.00;
                $orderdata['deliverytype'] = 1;

                $orderdata['addresstype']=$data['addresstype'];

                $Map=A("Api/Map");
                $location=$Map->get_position_complex($_POST['area'],$_POST['address']);
                $orderdata['lat']=$location['lat'];
                $orderdata['lng']=$location['lng'];
                $orderdata['name']=$_POST['name'];
                $orderdata['tel']=$_POST['tel'];
                $orderdata['area']=$_POST['area'];
                $orderdata['address']=$_POST['address'];

                $orderdata['ordertype']=$ordertype;
                $orderdata['ordersource']=5;
                $orderdata['buyerremark']=$orderremark;
                $orderdata['iscontainsweigh']=$iscontainsweigh;
                
                $orderdata['isserviceorder']=1;
                $orderdata['inputtime']=time();
                
                $id=M('order')->add($orderdata);
                if($id){
                    M('order')->where(array('orderid'=>$orderid))->save(array("relationorderid"=>$orderdata['orderid']));
                    foreach ($productinfo as $key => $value) {
                        # code...
                        $product=M('product')->where(array('id'=>$key))->find();
                        M('order_productinfo')->add(array(
                            'orderid'=>$orderdata['orderid'],
                            'pid'=>$key,
                            'nums'=>$value,
                            'price'=>$product['nowprice'],
                            'product_type'=>$product['type'],
                            'isweigh'=>0
                            ));
                    }
                    M('order_time')->add(array(
                                    'orderid'=>$orderdata['orderid'],
                                    'status'=>2,
                                    'pay_status'=>1,
                                    'pay_time'=>time(),
                                    'inputtime'=>time(),
                                    ));
                    
                    $c="尊敬的".  $_POST['name'] ."，您好！系统在".date("Y年m月d日 H时i分s秒") ."成功生成了一笔售后订单";
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$uid,
                        'title'=>"售后订单生成成功",
                        'description'=>$c,
                        'content'=>$c,
                        'value'=>$orderdata['orderid'],
                        'varname'=>"system",
                        'inputtime'=>time()
                    ));
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>0,
                        'title'=>"售后订单生成成功",
                        'value'=>$orderdata['orderid'],
                        'varname'=>"order",
                        'inputtime'=>time()
                    ));
                    M('feedback_dolog')->add(array(
                       'varname'=>'order',
                       'value'=>$orderid,
                       'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."新建一笔售后订单成功，订单号为：".$orderdata['orderid']."!",
                       'username'=>$_SESSION['user'],
                       'status'=>1,
                       'inputtime'=>time()
                       ));
                    \Api\Controller\OrderController::updatestock($orderdata['orderid']);
                    
                    $this->success("售后订单生成成功");    
                }else{
                    M('feedback_dolog')->add(array(
                       'varname'=>'order',
                       'value'=>$orderid,
                       'loginfo'=>"管理员".$_SESSION['user'].",在".date("Y-m-d H:i:s")."新建一笔售后订单失败!",
                       'username'=>$_SESSION['user'],
                       'status'=>0,
                       'inputtime'=>time()
                       ));
                    $this->error("售后订单生成失败");    
                }
            }

        }else{
            //$addresslist=M('address')->where(array('uid'=>$data['uid']))->select();
            //$this->assign("addresslist",$addresslist);
            
            $order_productinfo=M('order_productinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.orderid'=>$orderid))->field("a.*,b.title as productname,b.unit")->select();
            $this->assign("order_productinfo",$order_productinfo);

            $this->assign("data",$data);
            $this->assign("orderid",$orderid);
            $this->display();
        }
    }
    public function download() {
        $data=M('order')->where(array('orderid'=>$_GET['orderid']))->find();
        $file=$_SERVER['DOCUMENT_ROOT']. $data['ordercode'];
        
        if(is_file($file)) {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=".basename($file));
            readfile($file);
            exit;
        }else{
            $this->error('文件不存在！');
        }
    }
    public function ajax_getneworder(){
        $lasttime=$_POST['lasttime'];
        $where=array();
        ///$where['a.inputtime']=array(array('ELT', $lasttime),array('EGT', strtotime("- 7days",$lasttime)));
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['a.puid']=0;
        $where['_string']="(a.paystyle=2 and b.pay_status=0 and ((a.ordertype=2 and a.yes_money_total>=a.total)or a.ordertype!=2)) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        $order=M('Order a')
            ->join("left join zz_order_time b on a.orderid=b.orderid")
            ->order(array('a.inputtime'=>'desc'))
            ->where($where)
            ->field("a.orderid")
            ->find();
        if(!empty($order)){
            $data['status']=1;
            $data['msg']="有一笔新订单";
            $data['order']=$order;
            //$data['sql']=M('Order a')->_sql();
            $this->ajaxReturn($data,'json');
        }else{
            $data['status']=0;
            $data['msg']="暂无新订单";
            $this->ajaxReturn($data,'json');
        }
    }
    public function ajax_getnewcompanyorder(){
        $lasttime=$_POST['lasttime'];
        $where=array();
        ///$where['a.inputtime']=array(array('ELT', $lasttime),array('EGT', strtotime("- 7days",$lasttime)));
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$this->storeid;
        }
        $where['a.ordertype']=3;
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['a.puid']=0;
        $where['_string']="(a.paystyle=2 and b.pay_status=0 and ((a.ordertype=2 and a.yes_money_total>=a.total)or a.ordertype!=2)) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        $order=M('Order a')
            ->join("left join zz_order_time b on a.orderid=b.orderid")
            ->order(array('a.inputtime'=>'desc'))
            ->where($where)
            ->field("a.orderid")
            ->find();
        if(!empty($order)){
            $data['status']=1;
            $data['msg']="有一笔新订单";
            $data['order']=$order;
            //$data['sql']=M('Order a')->_sql();
            $this->ajaxReturn($data,'json');
        }else{
            $data['status']=0;
            $data['msg']="暂无新订单";
            $this->ajaxReturn($data,'json');
        }
    }
    public function newordernotice(){
        $orderid=I('orderid');
        $data=M('Order a')
            ->join("left join zz_order_time b on a.orderid=b.orderid")
            ->where(array('a.orderid'=>$orderid))
            ->find();
        $this->assign("data",$data);
        $uid = $_SESSION["userid"];
        if (!$uid) {
            if (isset($_COOKIE['admin_auto'])) {
                $auto = explode('|', $this->authcode($_COOKIE['admin_auto']));
                $ip = get_client_ip();
                if ($auto[2] == $ip) {
                    $uid = $auto[0];
                }
            }else{
                $this->error('请先登录！', U('Admin/Public/Login')); 
            }

        } 
        $User = D("user")->where(array("id" => $uid))->find();
        $this->assign("User", $User);
        $this->display();
    }

    public function feedbacklog(){
        if(IS_POST){
            if(empty($_POST['loginfo'])){
                $this->error("记录内容不能为空！");
            }
            $id=M('feedback_dolog')->add(array(
                'varname'=>'order',
                'value'=>$_POST['orderid'],
                'loginfo'=>$_POST['loginfo'],
                'username'=>$_SESSION['user'],
                'status'=>1,
                'inputtime'=>time()
                ));

            if($id){
                $this->success("操作成功");
            }else{
                $this->error("操作失败");
            }
        }else{
            $orderid=I('orderid');
            $this->assign("orderid",$orderid);
            $data=M('feedback_dolog')->where(array('varname'=>'order','value'=>$orderid))->order(array('inputtime'=>'desc'))->select();
            $this->assign("data",$data);
            $this->display();
        }
        
    }
    //public function ajax_setnoticestatus(){
    //    $orderid=I('orderid');
    //    $uid = $_SESSION["userid"];
    //    M('noticestatus')->add(array(
    //        'orderid'=>$orderid,
    //        'uid'=>$uid,
    //        'type'=>'order',
    //        'inputtime'=>time()
    //        ));
    //}
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
            PushQueue($mid, $message_type,$receiver, $title, $description, serialize($extras),1);
        }
    }
    public function getchildren() {
        $parentid = $_GET['id'];
        $result = M("area")->where(array("parentid" => $parentid,'status'=>1))->select();
        $result = json_encode($result);
        echo $result;
    }
    public function printorder(){
        $orderid=I('orderid');
        $id=M('order_time')->where(array('orderid'=>$orderid))->save(array(
                    'print_status'=>1,
                    'print_time'=>time()
                    ));
        if($id){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }
}
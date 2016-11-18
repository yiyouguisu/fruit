<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class CompanyorderController extends CommonController {
    /*
     * 企业订单列表
     */
    public function index() {
        $search = I('get.search');
        $where = array();
        $where['a.ordertype']=3;
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
            // $ordertype = I('get.ordertype');
            // if ($ordertype != "" && $ordertype != null) {
            //     if($ordertype==4){
            //         $where['a.iscontainsweigh']=1;
            //         $where['a.ordertype']=1;
            //     }else{
            //         $where['a.ordertype']=$ordertype;
            //     }
            // }
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

        $count = M("order a")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
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
            \Api\Common\CommonController::sendsms($sms);
        }
    }
    
    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("order")->delete($id)) {
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
                M("order")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    public function deal() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            $area= I('get.area');
            if (!empty($area)) {
                $servicearea = end(explode(",", $area));
                $where['servicearea']=array('like',"%,".$servicearea.",%");
            }
        }
        $where["status"]= array("EQ", 2);
        $count = M("store")->where($where)->count();
        $page = new \Think\Page($count,5);
        $data = M("store")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $orderid=I('id',null,'intval');
        $this->assign("orderid", $orderid);
        $this->display();
    }
    /*
     * 派发处理
     */

    public function dealorder() {
        $orderid=I('orderid');
        $storeid=I('storeid');
        if (empty($orderid)||empty($storeid)) {
            $this->error("没有信息被选中！");
        }
        if(is_array($orderid)){
            $id=M("order")->where(array('id'=>array('in',$orderid)))->setField("storeid",$storeid);
        }else{
            $id=M("order")->where(array('id'=>array('eq',$orderid)))->setField("storeid",$storeid);
        }
        if($id){
            $this->success("派发成功！",U("Admin/Companyorder/index"));
        }else{
            $this->error("派发失败！",U("Admin/Companyorder/index"));
        }
    }


    /**
     * 数据导出
     * 
     */
    public function excel() {
        import("Vendor.Excel.Excel");
        $search = I('post.search');
        $where = array();
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

        $data = M("order a")
                ->join("left join zz_order_time b on a.orderid=b.orderid")
                ->where($where)
                ->order(array("a.id" => "desc"))
                ->field('a.orderid,a.uid,a.ruid,a.storeid,a.total,a.money,a.yes_money,a.no_money,a.discount,a.wallet,a.name,a.tel,a.addresstype,a.area,a.address,a.paystyle,a.paytype,a.ordertype,a.inputtime,a.ordersource,a.isspeed,a.start_sendtime,a.end_sendtime,a.buyerremark,b.pay_status,b.pay_time,b.delivery_status,b.delivery_time,b.package_status,b.package_time,b.package_donetime')
                ->select();
        $row=array();
		$row[0]=array('订单号', '用户名', '配送员', '门店', "订单金额","优惠金额","钱包金额", "收货人信息","收货地址信息","订单留言", "支付方式", "支付状态", "包装状态", "配送状态", "订单类型", "订单时间");
		$i=1;
		foreach($data as $v){
			$rs = array();
			$rs[] = $v['orderid'];
			$rs[] = getuser($v['uid']);
            $rs[] = getuser($v['ruid']);
            $rs[] = getstoreinfo($v['storeid']);
			$rs[] = $v['total'];
			$rs[] = $v['discount'];
            $rs[] = $v['wallet'];
            if($v['isspeed']==1){
                $rs[] = "[急速达]" . $v['name']."(".$v['tel'].")";
            }elseif($v['isspeed']==0){
                $rs[] = "[" . date("H:i", $v["start_sendtime"]) . "-" .date("H:i", $v["end_sendtime"]) . "]" . $v['name']."(".$v['tel'].")";
            }
			$rs[] = getarea($v['area'])."-".$v['address'];
            $rs[] = $v['buyerremark'];
            if ($v["paystyle"] == 1) {
                if ($v["paytype"] == 1) {
                    $rs[] = "在线支付-支付宝";
                } elseif ($v['paytype'] == 2) {
                    $rs[] = "在线支付-微信";
                }
            }elseif ($v["pay_status"] == 2) {
                $rs[] = "货到付款";
            }
            if ($v["package_status"] == 0) {
                $rs[] = "未包装";
            }elseif ($v["package_status"] == 1) {
                $rs[] = "包装中-" . date("Y-m-d H:i:s", $v["package_time"]);
            }elseif ($v["package_status"] == 2) {
                $rs[] = "包装完成-" . date("Y-m-d H:i:s", $v["package_donetime"]);
            }
            if ($v["delivery_status"] == 1) {
                $rs[] = "配送完成-" . date("Y-m-d H:i:s", $v["delivery_time"]);
            }elseif ($v["delivery_status"] == 0) {
                $rs[] = "未配送";
            }
            if ($v["pay_status"] == 1) {
                if ($v["money"] == $v['yes_money']) {
                    $rs[] = "已支付";
                } elseif ($v["money"] > $v['yes_money']) {
                    $rs[] = "已支付金额".$v['yes_money'];
                }
            }elseif ($v["pay_status"] == 0) {
                $rs[] = "未支付";
            }
            $rs[] = getordersource($v['ordersource']);
            $rs[] = date("Y-m-d H:i:s", $v["inputtime"]);
			$row[$i] = $rs;
			$i++;
		}
		$xls = new \Excel_XML('UTF-8', false, 'datalist');
		$xls->addArray($row);
		$xls->generateXML(date("Ymd-His"));
    }

    
}
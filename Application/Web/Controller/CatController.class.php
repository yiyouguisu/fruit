<?php

namespace Web\Controller;

use Web\Common\CommonController;

class CatController extends CommonController {
	public function _initialize() {
        parent::_initialize();
		Vendor("pingpp.init");
        $ConfigData=F("web_config");
        if(!$ConfigData){
            $ConfigData=D("Config")->order(array('id'=>'desc'))->select();
            F("web_config",$ConfigData);
        }
        $this->ConfigData=$ConfigData;
        $this->cart_total_num();
    }
	
    public function index() {
        $this->display();
    }
    public function cempty(){
        $this->display();
    }
	public function lists() {
		$storeid = session('storeid');
    	$uid     = session('uid');
    	$store = M('store')->where("id=" . $storeid)->find();
        $cart = M('cart')->where("uid=" . $uid)->find();
		if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } elseif (empty($cart))
        	$this->redirect('Web/Cat/cempty');

        //$temp = array();
        //$data = M('cartinfo')->where('cartid='.$cart['id'])->order(array('type'=>'desc'))->select();

        $data=M('cart')->where(array('uid'=>$uid))->field('id as cartid,nums,money')->order(array('inputtime'=>'desc'))->find();
        $subdata=M('cartinfo a')->join("left join zz_store b on a.storeid=b.id")->where(array('a.cartid'=>$data['cartid']))->group("a.storeid")->field('a.storeid,b.title,b.thumb')->select();

        $pids=M("Product")->where("( `type` = 3 ) AND ( `selltime` < ".time()." ) ) OR (  ( `type` = 2 ) AND ( `expiretime` < ".time()." )")->getField("id",true);
        foreach ($subdata as $key => $value) {
            # code...
            $catids=M('store_cate')->where(array('storeid'=>$value['storeid']))->getField('catid');
            $productcate=M('productcate')->where(array('id'=>array('in',explode(",", $catids)),'parentid'=>0))->order(array('listorder'=>'desc','id'=>'asc'))->field('id,catname')->select();

            $cartinfo=M('cartinfo a')
                ->join("left join zz_product b on a.pid=b.id")
                ->where(array('a.cartid'=>$data['cartid'],'a.storeid'=>$value['storeid'],'b.isoff'=>0,'b.stock'=>array('gt',0),'b.id'=>array('not in',$pids)))
                ->field('b.id,b.storeid,a.pid,a.num as cartnum,b.title,b.description,b.thumb,b.nowprice,b.oldprice,b.standard,b.unit,b.ishot,b.type,b.catid,b.advanceprice,b.selltime,b.expiretime')
                ->order(array('a.inputtime'=>'desc'))
                ->select();
                //$subdata[$key]['sql']=M('cartinfo a')->_sql();
            foreach ($cartinfo as $k => $v)
            {   
                $subdata[$key]['type'] = $v['type'];
                $cartinfo[$k]['unit']=getunit($v['unit']);
            }
            
            $subdata[$key]['cartinfo']=!empty($cartinfo)?$cartinfo:"";
        }
        $data['subdata']=$subdata;
        //dump($data);die;
        // $o=3;
        // $pids=M("Product")->where(array('storeid'=>$storeid))->where("( `type` = 3 ) AND ( `selltime` < ".time()." ) ) OR (  ( `type` = 2 ) AND ( `expiretime` < ".time()." )")->getField("id",true);
        // foreach ($data as $key=>$value){
        // 	if ($data[$key]['type'] == '0'){
        // 		$y = $data[$key]['storeid'];
        // 		$temp[$y]['title'] = M('store')->where("id=" . $data[$key]['storeid'])->getField('title').'|企业专区';
        //         $temp[$y]['type'] = $data[$key]['type'];
        // 		$temp[$y]['storeid'] = $data[$key]['storeid'];
        // 		$temp[$y]['pid'] = $data[$key]['pid'];
        // 		$temp[$y]['items'][$key] = M('product')->where("id=" . $data[$key]['pid'])->find();
        // 		$temp[$y]['items'][$key]['cartnum'] = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$data[$key]['storeid'],'pid'=>$data[$key]['pid']))->getField('num');
        // 		$temp[$y]['items'][$key]['unit'] = getunit($temp[$y]['items'][$key]['unit']);
        // 	}
        // 	elseif ($data[$key]['type'] == '3'){
        // 		$y = $data[$key]['storeid']+1+$o;
        // 		$temp[$y]['title'] = M('store')->where("id=" . $data[$key]['storeid'])->getField('title').'|预购专区';
        //         $temp[$y]['type'] = $data[$key]['type'];
        // 		$temp[$y]['storeid'] = $data[$key]['storeid'];
        // 		$temp[$y]['pid'] = $data[$key]['pid'];
        // 		$temp[$y]['items'][$key] = M('product')->where("id=" . $data[$key]['pid'])->find();
        // 		$temp[$y]['items'][$key]['cartnum'] = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$data[$key]['storeid'],'pid'=>$data[$key]['pid']))->getField('num');
        // 	    $temp[$y]['items'][$key]['unit'] = getunit($temp[$y]['items'][$key]['unit']);
        //     }
        // 	else {
        // 		$y = $data[$key]['storeid']+2;
        // 		$temp[$y]['title'] = M('store')->where("id=" . $data[$key]['storeid'])->getField('title').'|一般订单';
        // 		$temp[$y]['storeid'] = $data[$key]['storeid'];
        //         $temp[$y]['type'] = $data[$key]['type'];
        // 		$temp[$y]['pid'] = $data[$key]['pid'];
        // 		$temp[$y]['items'][$key] = M('product')->where("id=" . $data[$key]['pid'])->find();
        // 		$temp[$y]['items'][$key]['cartnum'] = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$data[$key]['storeid'],'pid'=>$data[$key]['pid']))->getField('num');
        // 	    $temp[$y]['items'][$key]['unit'] = getunit($temp[$y]['items'][$key]['unit']);
        //     }
        //     $o++;
        // }
        //dump($temp);
        $this->assign("storelist",$data);
        
        $this->display();
    }

    /**
     * 删除购物车中产品
     */
    public function delcar_products($pid){
        $uid=session('uid');
        $user=M('Member')->where(array('id'=>$uid))->find();
        $cart=M('cart')->where(array('uid'=>$uid))->find();
        $product=M('product')->where(array('id'=>$pid))->find();
        $status=M('cartinfo')->where(array('cartid'=>$cart['id'],'pid'=>$pid))->find();
        //dump($uid);
        //dump($pid);
        //dump($storeid);
        if($uid==''||$pid==''){
            $this->error('请求参数错误');
        }elseif(!$user){
            $this->error('用户不存在');
        }elseif(!$cart){
            $this->error('购物车不存在');
        }elseif(!$product){
            $this->error('产品不存在');
        }elseif(!$status){
            $this->error('购物车没有此产品');
        }else{
            $id=M('cartinfo')->where(array('id'=>$status['id']))->delete();
            if($id){
                $money  = $cart['money']-$status['money'];
                if($money == 0){
                    M('cart')->where(array('id'=>$cart['id']))->delete();
                }else{
                    M('cart')->where('uid='.$uid)->save(array(
                        //'num'=>$num,
                        'money'=>$cart['money']-$status['money'],
                        'updatetime'=>time()
                        ));
                }
                
            }else{
                $this->error('删除失败');
            }
        }
    }

    public function catupdate(){
    	$nums    = 0;
    	$pid     = 0;
        $storeid = session('storeid');
    	if (!empty($_REQUEST['pid']))
    	{
    		$nums    = $_REQUEST['num'];
    		$pid     = $_REQUEST['pid'];
            $storeid = $_REQUEST['storeid'];
    	}
    	
    	$uid     = session('uid');
    	//这里根据pid、num和userid 添加或者修改购物车
        $where['id']=$uid;
        $result = M('Member')->where($where)->find();
        $store = M('store')->where("id=" . $storeid)->find();
        $cart = M('cart')->where("uid=" . $uid)->find();
        $product=M('product')->where(array("id" => $pid))->find();
        $catcount = M('cartinfo')->where(array("pid"=>$pid,"cartid"=>$cart['id']))->setField('num');
        $pids=M("Product")->where("( `type` = 3 ) AND ( `selltime` < ".time()." ) ) OR (  ( `type` = 2 ) AND ( `expiretime` < ".time()." )")->getField("id",true);
            
        
        //$this->ajaxReturn($catcount);
        if($uid==''||$storeid==''){
            $this->error('登录失败');
        }elseif (!$result){
        	$this->error('用户不存在');
        }elseif (!$store){
        	$this->error('商户不存在');
        }elseif ($nums == 0){
        	//如果当前产品数量减到0了，那么直接将记录删除
            $id = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->delete();
        	if ($id){
        		//这里也要将主表刷新一下
        		$cartinfo = M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
                $money = 0.00;
                //总共的商品种类
                $totalp = 0;
                //总共的商品数量
                $totalnum = 0;
                foreach ($cartinfo as $value) {
                    $product = M('Product')->where(array('id'=>$value['pid']))->find();
                    $money += $product['nowprice']*$value['num'];
                }
                $totalp = M('cartinfo')->where(array('cartid'=>$cart['id']))->count();
                $totalnum = M('cartinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.cartid'=>$cart['id'],'b.id'=>array("not in",$pids)))->sum('num');
                M('cart')->where(array('id'=>$cart['id']))->setField(array(
                	"money"  => $money,
                	"nums"   => $totalp
                ));
                
        		//这里删除成功后统计下info表中还有没有当前用户的购物车了 如果没有的话把主表里的购物车信息也删除
        		$total = M('cartinfo')->where(array('cartid'=>$cart['id']))->count();
        		if ($total == 0){
        			$id = M('cart')->where(array('id'=>$cart['id']))->delete();
        		}
        		echo json_encode($totalnum);
        	}
        }else if ($product['stock'] < $nums){
            $int = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->save(array(
	                'num'        => $product['stock'],
	                'price'      => $product['nowprice'],
	                'money'      => $product['nowprice']*$product['stock'],
	                'updatetime' => time()
	                ));
            echo json_encode('stock');
        }
        else{
            $status=M('cart')->where(array('uid'=>$uid))->find();
            if($status){
                $id = M('cart')->where(array('uid'=>$uid))->save(array(
                      'nums'       => $nums,
                      'updatetime' => time()
                ));
            }else{
                $id = M('cart')->add(array(
                      'uid'        => $uid,
                      'nums'       => $nums,
                      'inputtime'  => time(),
                      'updatetime' => time()
                ));
            }
            if($id){
            	$cart = M('cart')->where("uid=" . $uid)->find();
                $money = 0.00;
                $product = M('Product')->where(array('id'=>$pid))->find();
                $is_result = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->find();
                
                //如果能查到值 那么直接更新
                if ($is_result)
                {
	                $int = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->save(array(
	                'num'        => $nums,
	                'price'      => $product['nowprice'],
	                'money'      => $product['nowprice']*$nums,
	                'updatetime' => time()
	                ));
                }
                //不能查到去添加
                else
                {
                	$cart = M('cart')->where("uid=" . $uid)->find();
                	$int = M('cartinfo')->add(array(
                        'cartid'	=>	$cart['id'],
                        'storeid'	=>	$storeid,
                        'pid'		=>	$pid,
                        'num'		=>	$nums,
                        'price'		=>	$product['nowprice'],
                        'money'		=>	$product['nowprice']*$nums,
                        'type'		=>	$product['type'],
                        'inputtime'	=>	time()
                        ));
                }
            	if($int){
	                $cartinfo = M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
	                $money = 0.00;
	                //总共的商品种类
	                $totalp = 0;
	                //总共的商品数量
	                $totalnum = 0;
	                foreach ($cartinfo as $value) {
	                    $product = M('Product')->where(array('id'=>$value['pid']))->find();
	                    $money += $product['nowprice']*$value['num'];
	                }
	                $totalp = M('cartinfo')->where(array('cartid'=>$cart['id']))->count();
	                $totalnum = M('cartinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.cartid'=>$cart['id'],'b.id'=>array("not in",$pids)))->sum('num');
                    M('cart')->where(array('id'=>$cart['id']))->setField(array(
	                	"money"  => $money,
	                	"nums"   => $totalp
	                ));
                    echo json_encode($totalnum);
	            }else{
                    $this->error('加入购物车失败');
	            }
            }else{
                $this->error('加入购物车失败');
            }
        }
    }
    
    //删除购物车
    public function catdel(){
    	$cartinfo = $_REQUEST['cartinfo'];
    	$storeid = $_REQUEST['storeid'];
        $type = $_REQUEST['type'];
    	$uid     = session('uid');
    	$cart = M('cart')->where("uid=" . $uid)->find();
		
        if($type == "1" || $type == "2" || $type == "4")
        {
            $type='1,2,4';
        }

    	if (empty($cartinfo))
    	{
    		$id = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'type'=>array('in',$type)))->delete();
    	}
    	else 
    	{
    		$ret = json_decode($cartinfo,TRUE);
    		$count = count($ret['Data']);
    		for ($i=0;$i<$count;$i++){
    			$id = M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$ret['Data'][$i]['storeid'],'pid'=>$ret['Data'][$i]['product_id']))->delete();
    		}
    	}
    	
    	if ($id){
        	//这里也要将主表刷新一下
        	$cartinfo = M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
            $money = 0.00;
            //总共的商品种类
            $totalp = 0;
            //总共的商品数量
            $totalnum = 0;
            foreach ($cartinfo as $value) {
                $product = M('Product')->where(array('id'=>$value['pid']))->find();
                $money += $product['nowprice']*$value['num'];
            }
            $totalp = M('cartinfo')->where(array('cartid'=>$cart['id']))->count();
            $totalnum = M('cartinfo')->where(array('cartid'=>$cart['id']))->sum('num');
            M('cart')->where(array('id'=>$cart['id']))->setField(array(
            "money"  => $money,
            "nums"   => $totalp
            ));
            
        	//这里删除成功后统计下info表中还有没有当前用户的购物车了 如果没有的话把主表里的购物车信息也删除
        	$total = M('cartinfo')->where(array('cartid'=>$cart['id']))->count();
        	if ($total == 0){
        		$id = M('cart')->where(array('id'=>$cart['id']))->delete();
        	}
            //echo json_encode($totalnum);
            echo json_decode('success');
        }
        else{
        	$this->error('删除购物车失败');
        }
    }
    
    
    public function submits(){
    	//先获得默认的收货地址，且这里的收货地址一直让他显示默认地址，如果他选择了其他的地址的话，那么也将他设置为默认地址
    	$uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } 

    	$cachefile = "goodslist".$uid;
    	$cachefiletotal = "goodstotal".$uid;
    	$cachefileordertype = "ordertype".$uid;
    	/***收货地址***/
    	$addressid = I('get.id'); 
    	if (!empty($addressid)){
    		$address = M('address')->where(array('id'=>$addressid))->find();
    	}else{
    		$address = M('address')->where(array('uid'=>$uid,'isdefault'=>'1'))->find();
    	}
    	$area = explode(',',$address['area']);
		$address['province'] = D("area")->where("id=".$area[0])->getField('name');
	    $address['city'] = D("area")->where("id=".$area[1])->getField('name');
	    if (count($area)>2){
	        $address['areas'] = D("area")->where("id=".$area[2])->getField('name');
	    }
    	$this->assign('address',$address);
    	
    	/***商品信息***/
    	$data = S($cachefile);
        //dump($data);
        $flag = 0;
        foreach ($data['items'] as $key=>$value)
        {
            $data['items'][$key]['ttotal'] = $data['items'][$key]['nowprice'] * $data['items'][$key]['goodsnum'];
            if($value['type'] == 4)
            {
                $flag = 1;
                //break;
            }
            if ($value['type'] == 3)
            {
            	$flag = 2;
                break;
            }
            if($value['type'] == 0)
            {
                $flag = 3;
                break;
            }
            $ordertype = $value['type'];
            
        }
        
        foreach ($data['items'] as $key=>$value)
        {
            $data['items'][$key]['unit'] = getunit($data['items'][$key]['unit']);
        }
        
        
        if($flag == 1)
            $this->assign('pricewait',"(待称重)");
        if($flag == 2)
            $this->assign('pricewait',"(预付款)");
        if($flag == 3)
            $this->assign('pricewait',"(企业结算)");

    	$this->assign('goodslist',$data['items']);
        if($flag == 1){
            $this->assign('isgoodswidth','1');
        }else{
            $this->assign('isgoodswidth','0');
        }
        //dump($data['items'][0]['advanceprice']);
        $dingjinprice = $data['items'][0]['advanceprice']*$data['items'][0]['goodsnum'];
        $this->assign('yufu',$data['items'][0]['advanceprice']*$data['items'][0]['goodsnum']);
    	$total = S($cachefiletotal);
    	$coupons_price = 0.00;
    	$account_price = 0.00;
    	$couponsid = 0;
    	$couponsname = '';
    	/***优惠券***/
    	//先把当前用户的优惠券全部拿出来
        $storeid = session('storeid');
        $storeidbox="0,".$storeid;
        $coupons_data = M('coupons_order a')
                ->join("left join zz_coupons b on a.catid=b.id")
                ->where(array("a.uid"=>$uid,'b.storeid'=>array('in',$storeidbox),'a.status'=>0,'b.validity_endtime'=>array('gt',time())))
                ->field('a.id as id,b.price as price,b.title as title,b.range,b.pid')
                ->select();
        $this->assign("coupons",$coupons_data);
        //然后将每一种优惠券都算一遍，得出最后的价格
        //dump($coupons_data);
    	foreach ($coupons_data as $key=>$value){
    		//将商品全部遍历一遍
            //dump($coupons_data[$key]['pid']);
    		if (!empty($coupons_data[$key]['pid'])){
	    		foreach ($data as $k=>$v){
	    			if ($coupons_data[$key]['pid'] == $data[$k]['id']){
	    				$thispric = floatval(trim($coupons_data[$key]['price']));
	    				if ($thispric > $coupons_price){
	    					$coupons_price = $thispric;
	    					$couponsid = $coupons_data[$key]['id'];
	    					$couponsname = $coupons_data[$key]['title'];
	    				}
	    			}
	    		}
    		}
    		elseif (!empty($coupons_data[$key]['storeid']))
    		{
    			foreach ($data as $k=>$v){
	    			if ($coupons_data[$key]['storeid'] == $data[$k]['storeid']){
	    				$thispric = floatval(trim($coupons_data[$key]['price']));
	    				if ($thispric > $coupons_price){
	    					$coupons_price = $thispric;
	    					$couponsid = $coupons_data[$key]['id'];
	    					$couponsname = $coupons_data[$key]['title'];
	    				}
	    			}
	    		}
    		}
    		else
    		{
    			if (floatval(trim($coupons_data[$key]['range'])) < floatval(trim($total))){
    				$thispric = floatval(trim($coupons_data[$key]['price']));
    				if ($thispric > $coupons_price){
    					$coupons_price = $thispric;
	    				$couponsid = $coupons_data[$key]['id'];
	    				$couponsname = $coupons_data[$key]['title'];
    				}
    			}
    		}
    	}
        if (S($cachefileordertype) == '3')
        	$this->assign("isdisplay","style='display:none'");
        
        
        
        
    	$this->assign('couponsid',$couponsid);
        
    	$this->assign('couponstitle',$couponsname);
    	$this->assign('ordertype',S($cachefileordertype));
    	/***钱包***/
    	//计算当前人的钱包总数
    	$data = M('account')->where(array('uid'=>$uid))->find();
        if (S($cachefileordertype) == '3')
        {
            $account_price = 0.00;
            $coupons_price = 0.00;
        }
        else{
            $account_price = floatval(trim($data['usemoney']));
        }
        //dump($total);
        if($account_price>= $total){
            $dikou = $total;
        }else{
            $dikou=$account_price;
        }
        
        if (S($cachefileordertype) == '2')
        {
            if ($account_price>=$dingjinprice)
            {
            	$dikou = $dingjinprice;
            }else{
                $dikou=$account_price;
            }
        }
        
    	$this->assign('accountmoney',$account_price);
        $this->assign('goodstotaltemp',$total);
        //这里判断如果总价格减去优惠券金额就已经等于0的话那么就不使用余额
        //$tempstotal = floatval(trim($total)) - floatval(trim($coupons_price));
        if (S($cachefileordertype) != '2')
        {
            $total =floatval(trim($total)) - floatval(trim($account_price)) - floatval(trim($coupons_price));
        }
        else{
            //$total =floatval(trim($dingjinprice)) - floatval(trim($account_price)) - floatval(trim($coupons_price));
            $total =floatval(trim($dingjinprice)) - floatval(trim($account_price));
        }
        if($total <= 0){
            $total =0.00; 
        }
        //dump($dikou);
        if($flag != 1){
            if ($coupons_price>0)
            {
                if($dikou != '0')
                {
                    $this->assign('qianbaodikou',$dikou);
                }
                else{
                    $this->assign('qianbaodikou','0.00');
                }
            }else{
                $this->assign('qianbaodikou',$dikou);
            }
            
            $this->assign('goodstotal',$total);
        }else{
            $this->assign('goodstotal',"0.00");
            $this->assign('qianbaodikou',"0.00");
        }
        $this->assign('total',$total);
        $this->assign('wallet',$dikou);
        //if ($tempstotal<=0)
        //{
        //    $this->assign('couponsid','');
        //    $coupons_price = 0.00;
        //}
        if($account_price == '0')
        {
            $this->assign('qianbaodikou','0.00');
        }
        $this->assign('coupons_price',$coupons_price);
        $integral = M('integral')->where(array("uid"=>$uid))->find();
        $this->assign('integral',$integral);
    	$this->display();
    }
    
    //购物车缓存商品
    public function goodscache(){
    	$cartinfo = $_REQUEST['cartinfo'];
    	$uid = session('uid');
    	$ret = json_decode($cartinfo,TRUE);
    	$count = count($ret['Data']);
    	
        $storeid=0;
    	$value =array();
    	for ($i=0;$i<$count;$i++){
    		$pinfo = M('product')->where('id='.$ret['Data'][$i]['product_id'])->find();
    		if (i==0){
    			$storeid = $pinfo['storeid'];
    			$value[$i] = $pinfo;
	    		$value[$i]['goodsnum'] = $ret['Data'][$i]['num'];
    		}
    		elseif (i>0 && $storeid!=$pinfo['storeid']){
    			continue;
    		}else{
	    		$value[$i] = $pinfo;
	    		$value[$i]['goodsnum'] = $ret['Data'][$i]['num'];
    		}
    	}
        
        //    	echo json_encode($storeid);
        //    	exit;
    	$final = array();
    	$flag = 0;
    	//这里是判断结算页面只接受一种类型的订单
    	foreach ($value as $k=>$v){
    		//一般订单
    		if ($value[$k]['type'] == '1' || $value[$k]['type'] == '2' || $value[$k]['type'] == '4'){
    			$final['items'][$k] = $value[$k];
    			$final['totalmoney'] += floatval(trim($value[$k]['nowprice'])) * floatval($value[$k]['goodsnum']);
    			$final['ordertype'] = 1;
    			$flag = 1;
    		}
    	}
        
    	if ($flag == 0){
	    	foreach ($value as $k=>$v){
	    		//企业订单
	    		if ($value[$k]['type'] == '0'){
	    			$final['items'][$k] = $value[$k];
	    			$final['totalmoney'] += floatval(trim($value[$k]['nowprice'])) * floatval($value[$k]['goodsnum']);
	    			$final['ordertype'] = 3;
	    			$flag = 1;
	    		}
	    	}
    	}
    	if ($flag == 0){
	    	foreach ($value as $k=>$v){
	    		//预购订单
	    		if ($value[$k]['type'] == '3'){
                    
	    			$final['items'][$k] = $value[$k];
                    
	    			$final['totalmoney'] += floatval(trim($value[$k]['nowprice'])) * floatval($value[$k]['goodsnum']);
	    			$final['ordertype'] = 2;
	    			$flag = 1;
	    			break;
	    		}
	    	}
	    	$value = $value[0];
    	}
    	$total = $final['totalmoney'];
    	$ordertype = $final['ordertype'];
        $final['storeid']=$storeid;
    	$cachefile = "goodslist".$uid;
    	$cachefiletotal = "goodstotal".$uid;
    	$cachefileordertype = "ordertype".$uid;
    	S($cachefile,$final,3600);
    	S($cachefiletotal,$total,3600);
    	S($cachefileordertype,$ordertype,3600);
        echo json_encode(S($cachefile));
    }
    
    public function getdatetime($date,$hour){
		$year=((int)substr("$date",0,4));//取得年份
		$month=((int)substr("$date",5,2));//取得月份
		$day=((int)substr("$date",8,2));//取得几号
		$today = mktime($hour,0,0,$month,$day,$year);
		return strtotime(date("Y-m-d H:i",$today));
    }
    
    public function addorder(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        }
		$cachefile = "goodslist".$uid;
        //商品信息
        $productinfo = S($cachefile);
        $storeid=$productinfo['storeid'];
		$productinfo = $productinfo['items'];
        
        //$integral=intval(trim($ret['integral']));
        //钱包金额
        //        $wallet=floatval(trim($ret['wallet']));
        $wallet = floatval(trim($_POST['wallet']));
        //优惠券id
        //        $couponsid=intval(trim($ret['couponsid']));
        $couponsid = intval(trim($_POST['couponsid']));
        $discount = floatval(trim($_POST['discount']));
		//实际支付金额 （在缓存中直接算好了传过来）
        if(trim($_POST['money']) == ''){
            $money = 0.00;
        }else{
            $money = floatval(trim($_POST['money']));
        }
        
        //订单类型 订单类型分为3种其中1是一般商品（团购商品，普通商品，称重商品）2是预购商品和3是企业商品
        $ordertype = intval(trim($_POST['ordertype']));

		//这里是买家推荐的配送时间，根据选择的选项自动筛选出开始时间和结束时间
		$sendday  = intval(trim($_POST['sendday']));
        if ($sendday == 0 && $ordertype!=2)
        {
        	$this->show('<script>alert("请选择收货时间！")</script>');
            echo "<script>history.go(-1)</script>";
            exit;
        }
		$sendtime = intval(trim($_POST['sendtime']));
        
        if ($sendday == 1){
           
        	$sendday = 0;
            $sendtime = 0;
            if(intval(date('H',time())) > 19 || intval(date('H',time())) < 8)
            {
                $this->show('<script>alert("此时间段内不支持极速达哦！")</script>');
                echo "<script>history.go(-1)</script>";
                exit;
            }
        }
        elseif($sendday == 2){
            $sendday = 0;
            if(intval(date('H',time())) > 19 || intval(date('H',time())) < 8)
            {
                $this->show('<script>alert("此时间段内不送货了哦！")</script>');
                echo "<script>history.go(-1)</script>";
                exit;
            }
        }
        else{
            $sendday = 1;
        }
        
		$tomorrow = date("Y-m-d",strtotime("+$sendday day"));
        
    	switch ($sendtime)
        {
			case "1":
				$start_sendtime = $this->getdatetime($tomorrow,8);
				$end_sendtime = $this->getdatetime($tomorrow,9);
                break;
			case "2":
				$start_sendtime = $this->getdatetime($tomorrow,9);
				$end_sendtime = $this->getdatetime($tomorrow,10);
                break;
			case "3":
				$start_sendtime = $this->getdatetime($tomorrow,10);
				$end_sendtime = $this->getdatetime($tomorrow,11);
                break;
			case "4":
				$start_sendtime = $this->getdatetime($tomorrow,11);
				$end_sendtime = $this->getdatetime($tomorrow,12);
                break;
			case "5":
				$start_sendtime = $this->getdatetime($tomorrow,12);
				$end_sendtime = $this->getdatetime($tomorrow,13);
                break;
			case "6":
				$start_sendtime = $this->getdatetime($tomorrow,13);
				$end_sendtime = $this->getdatetime($tomorrow,14);
                break;
            case "7":
				$start_sendtime = $this->getdatetime($tomorrow,14);
				$end_sendtime = $this->getdatetime($tomorrow,15);
                break;
            case "8":
				$start_sendtime = $this->getdatetime($tomorrow,15);
				$end_sendtime = $this->getdatetime($tomorrow,16);
                break;
            case "9":
				$start_sendtime = $this->getdatetime($tomorrow,16);
				$end_sendtime = $this->getdatetime($tomorrow,17);
                break;
            case "10":
				$start_sendtime = $this->getdatetime($tomorrow,17);
				$end_sendtime = $this->getdatetime($tomorrow,18);
                break;
            case "11":
				$start_sendtime = $this->getdatetime($tomorrow,18);
				$end_sendtime = $this->getdatetime($tomorrow,19);
                break;
            case 12:
				$start_sendtime = $this->getdatetime($tomorrow,19);
				$end_sendtime = $this->getdatetime($tomorrow,20);
                break;
            case "13":
				$start_sendtime = $this->getdatetime($tomorrow,20);
				$end_sendtime = $this->getdatetime($tomorrow,21);
                break;
            case "14":
				$start_sendtime = $this->getdatetime($tomorrow,21);
				$end_sendtime = $this->getdatetime($tomorrow,22);
                break;
            default:
				$start_sendtime = 0;
				$end_sendtime = 0;
        }
        
		//运费
		$delivery = floatval(trim($_POST['delivery']));
		
		//地址id
        $addressid = intval(trim($_POST['addressid']));
        //地址相关信息
        $addressinfo = M('address')->where(array('id'=>$addressid))->find();

        //订单留言
        $isorderremark = intval(trim($_POST['isorderremark']));
        
        if (!empty($isorderremark)){
            //贺卡留言
            $orderremark = trim($_POST['orderremark']);
        }else {
            //贺卡留言
            $orderremark = "";
        }

        
		//贺卡是否留言
        $iscardremark = intval(trim($_POST['iscardremark']));
        
        if (!empty($iscardremark)){
            //贺卡留言
            $cardremark = trim($_POST['cardremark']);
        }else {
            //贺卡留言
            $cardremark = "";
        }
        
        if ($ordertype == "2")
        {
            $start_sendtime = 0;
            $end_sendtime = 0;
        }
        $paytype='';
		//支付途径 1在线支付 2 货到付款
        $paystyle = intval(trim($_POST['paystyle']));
        if ($paystyle == '1'){
            //支付方式 1：支付宝，2：微信 微信端只有微信支付
            $paytype = 2;
        }

        if($money == 0 && !empty($wallet) && $wallet!=0){
            $paystyle = 3;
        }
        if($money == 0 && $wallet==0 && ($paystyle==0 || empty($paystyle))){
            $paystyle = 4;
        }
        

        if($ordertype == '3'){
            $money == '';
        }
        
        $iscontainsweigh = intval(trim($_POST['iscontainsweigh']));
        
		//ping++支付渠道，这里是string 而我只能是微信支付 ，那么就写个微信好了
		//ping++规定h5微信支付为wx_pub
        $channel = "wx_pub";
		
    	$user=M('Member')->where(array('id'=>$uid))->find();
        $store=M('store')->where(array('id'=>$storeid))->find();
        //$userintegral = M('integral')->where(array("uid"=>$uid))->find();
        
        $areaset=explode(",",$addressinfo['area']);
        $servicearea=explode(",",$store['servicearea']);


        if($uid==''||$addressid==''||$ordertype==''){
            if($addressid == ''){
                echo "<script>alert('请选择地址！');history.go(-1)</script>";
                exit;
            }elseif($uid==''){
                echo "<script>alert('用户尚未登录，请登录！');history.go(-1)</script>";
                exit;
            }else{
                echo "<script>alert('请求参数错误！');history.go(-1)</script>";
                exit;
            }
        }elseif(!$user){
            echo "<script>alert('用户不存在！');history.go(-1)</script>";
            exit;
        }elseif(!$store&&$ordertype!=3){
            echo "<script>alert('门店不存在！');history.go(-1)</script>";
            exit;
        }elseif($user['group_id']!=1){
            echo "<script>alert('非普通用户不能下单！');history.go(-1)</script>";
            exit;
        }elseif(!in_array($areaset[count($areaset)-1],$servicearea)&&$ordertype!=3){
            echo "<script>alert('亲，当前收货地址不属于此店铺配送范围，请修改收货地址或者更换店铺下单。');history.go(-1)</script>";
            exit;
        }else{

            switch ($ordertype) {
                case '1':
                    # code...
                    if($paystyle==''||$channel==''|| $paystyle == 0){
                        echo "<script>alert('请选择支付方式！');history.go(-1)</script>";
                        exit;
                    }
                    break;
                case '2':
                    # code...
                    if($paystyle==''||$channel=='' || $paystyle==0){
                        echo "<script>alert('请选择支付方式！');history.go(-1)</script>";
                        exit;
                    }
                    break;
                case '3':
                    # code...
                    if(empty($user['companyid'])){
                        echo "<script>alert('用户尚未进行企业认证，不能提交企业订单！');history.go(-1)</script>";
                        exit;
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
            if(!empty($wallet)){
                $account=M('account')->where('uid=' . $uid)->find();
                if($account['usemoney']<$wallet){
                    echo "<script>alert('钱包可用金额不足！');history.go(-1)</script>";
                    exit;
                }else{
                    $data['wallet']=$wallet;
                }
            }
            if(!empty($couponsid)){
                $coupons=M('coupons_order')->where(array('id'=>$couponsid))->find();
                if($coupons){
                    if($coupons['status']==1){
                        echo "<script>alert('优惠券已经被使用！');history.go(-1)</script>";
                        exit;
                    }
                }else{
                    echo "<script>alert('尚未购买此种优惠券！');history.go(-1)</script>";
                    exit;
                }
                $data['couponsid']=$couponsid;
                $data['discount']=$discount;
            }
            $data['buyerremark']=$orderremark;
            $data['cardremark']=$cardremark;
            
            $total=0.00;

            foreach ($productinfo as $key => $value) {
                # code...
                $product=M('product')->where(array('id'=>$value['id']))->find();
                if($product['isoff']==1){
                    echo "<script>alert('订单中有商品已被下架了！');history.go(-1)</script>";
                    exit;
                }
                if($product['type']==3&&$product['selltime']<time()){
                    echo "<script>alert('订单中有商品已过期啦！');history.go(-1)</script>";
                    exit;
                }
                if($product['type']==2&&$product['expiretime']<time()){
                    echo "<script>alert('订单中有商品已过期啦！');history.go(-1)</script>";
                    exit;
                }
                if($product['stock']==0){
                    echo "<script>alert('订单中有商品正在补货中！');history.go(-1)</script>";
                    exit;
                }
                if($value['goodsnum']>$product['stock']&&$product['stock']>0){
                    echo "<script>alert('订单中有商品库存不足！');history.go(-1)</script>";
                    exit;
                }
                if($product['type']==4){
                    $total+=$value['goodsnum']*$product['nowprice'];
                }elseif($product['type']==3){
                    $total+=$value['goodsnum']*$product['nowprice'];
                }else{
                    $total+=$value['goodsnum']*$product['nowprice']; 
                }
            }
            if(!empty($start_sendtime)||!empty($end_sendtime)){
                $data['isspeed']=0;
            }else{
                if ($ordertype!=2){
                	$useintegral=M('integral')->where(array('uid'=>$uid))->getField("useintegral");
                    if($total<199&&$useintegral<500){
                        echo "<script>alert('订单金额低于199元，且用户可用积分不足500分，不能提交极速达订单！');history.go(-1)</script>";
                        exit;
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
            $data['ordersource']=1;
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
                $sms=json_encode(array('phone'=>$user['phone'],'content'=>$c,'uid'=>$uid,'type'=>1));
                self::sendsms($sms);
                $total=0.00;
                $yes_money_total=0.00;
                $pflag = 0;
                $isnotice=1;
                foreach ($productinfo as $key => $value) {
                    # code...
                    $product=M('product')->where(array('id'=>$value['id']))->find();
                    
                    if($product['type']=='4'){
                        $total+=$value['goodsnum']*$product['nowprice'];
                    }elseif($product['type']=='3'){
                        $total+=$value['goodsnum']*$product['nowprice'];
                        $yes_money_total+=$value['goodsnum']*$product['advanceprice'];
                        $isnotice=0;
                    }else{
                        $total+=$value['goodsnum']*$product['nowprice']; 
                    }
                    
                    M('order_productinfo')->add(array(
                        'orderid'=>$data['orderid'],
                        'pid'=>$value['id'],
                        'nums'=>$value['goodsnum'],
                        'price'=>$product['nowprice'],
                        'product_type'=>$product['type'],
                        'isweigh'=>0,
                        'isnotice'=>$isnotice
                        ));
                    if ($product['type'] == '4')
                        $pflag = 1;
                    
                    //$total+=$value['goodsnum']*$product['nowprice'];
                    //在这里直接购物车信息删除
                    self::delcar_products($value['id']);
                }
                //dump($channel);
                //dump($productinfo);
                //eixt;
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
                        if($iscontainsweigh==1){
                            if ($data['paystyle'] == 1) {
                                self::wallet($data['orderid']);
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }elseif($data['paystyle'] == 2){
                                self::wallet($data['orderid']);
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }elseif($data['paystyle'] == 3){
                                self::wallet($data['orderid']);
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }elseif($data['paystyle'] == 4){
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }
                        }else{
                            if ($data['paystyle'] == 1) {
                                self::wallet($data['orderid']);
                                $pingpp=self::pingpp($data['orderid']);
                                exit($pingpp);
                            }elseif($data['paystyle'] == 2){
                                self::wallet($data['orderid']);
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }elseif($data['paystyle'] == 3){
                                self::wallet($data['orderid']);
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }elseif($data['paystyle'] == 4){
                                $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                            }
                        }
                        self::updatestock($data['orderid']);
                        break;
                    case '2':
                        # code...
                        if ($data['paystyle'] == 1) {
                            self::wallet($data['orderid']);
                            $pingpp=self::pingpp($data['orderid']);
                            exit($pingpp);
                        }elseif($data['paystyle'] == 2){
                            self::wallet($data['orderid']);
                            $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                        }elseif($data['paystyle'] == 3){
                            self::wallet($data['orderid']);
                            $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                        }elseif($data['paystyle'] == 4){
                            $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                        }
                        break;
                    case '3':
                        # code...s
                        self::updatestock($data['orderid']);
                        $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                        break;
                }

                //这里如果是在线支付的话 那就直接跳转到支付流程
                //if ($data['paystyle'] == 1) {
                //    if ($pflag == 0 && $money !=0)
                //    {
                //        $pingpp=self::pingpp($data['orderid']);
                //        exit($pingpp);
                //    }else{
                //        $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                //    }
                //    //exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                //}
                //else if($data['paystyle'] == 3){
                //    self::wallet($data['orderid']);
                //    $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                //} 
                //else{
                //    //如果货到付款的话 直接跳转到订单详情页
                //    $this->redirect('Web/Order/show',array("id"=>$data['orderid']));
                //    //                    exit(json_encode(array('code'=>200,'msg'=>"订单提交成功",'orderid'=>$data['orderid'])));
                //}
            }else{
                echo "<script>alert('订单提交失败！');history.go(-1)</script>";
                exit;
            }
        }
    }
    public function updatestock($orderid){
        $order_productinfo=M('order_productinfo')->where(array('orderid'=>$orderid))->select();
        foreach ($order_productinfo as $value)
        {
        	M('product')->where(array('id'=>$value['pid']))->setDec("stock",$value['nums']);

        }
    }
    public function speedrule($money,$integral){
        if ($money<199 || $integral<500)
        {
        	$this->show('<script>alert("积分未满500或订单未满199无法使用极速达！")</script>');
            echo "<script>history.go(-1)</script>";
            exit;
        }
        else{
            return true;
        }
        
    }

	public function sendsms($data){
        $CCPRest = A("Api/CCPRest");
        $data=json_decode($data,true);
        $datas=array($data['content']);
        $CCPRest->sendTemplateSMS($data['phone'],$datas,'59988');
        M("sms")->add(array(
            "phone" => $data['phone'],
            "content" => $data['content'],
            "s_id" => $data['uid'],
            "inputtime" => time(),
        ));
    }
    
    public function chosepaystatus()
    {
        $uid = session('uid');
        $account=M('account')->where('uid=' . $uid)->find();
        $orderid = $_REQUEST['orderid'];
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $orderid=$order['orderid'];
        $extra = array();
		$channel = "wx_pub";
		switch ($channel) {
		    case 'alipay_wap':
		        $extra = array(
		            'success_url' => 'http://www.yourdomain.com/success',
		            'cancel_url' => 'http://www.yourdomain.com/cancel'
		        );
		        break;
		    case 'upmp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'bfb_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'upacp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'wx_pub':
		        $extra = array(
		            'open_id' => $_COOKIE['web_user_openid']
		        );
		        break;
		    case 'wx_pub_qr':
		        $extra = array(
		            'product_id' => 'Productid'
		        );
		        break;
		}
        
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
                $money=$order['money'];
            }else{
                $money=$order['wait_money'];
                $orderid="p".$orderid;
            }
        }else{
            $money=$order['money'];
        }
        
        $wallet = $money;
        if($account['usemoney'] > $wallet){
            
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
            $this->ajaxReturn($orderid);
            exit;
            M('order_time')->where(array('orderid'=>$orderid))->save(array(
                               'pay_status'=>1,
                               'pay_time'=>time()
                               ));
            self::wallet($order['orderid'],$wallet);
            $this->ajaxReturn('wallet');
        }else{
            $this->ajaxReturn('nomore');
        }
    }
    
    
    public function orderpayagain(){
        $uid=session('uid');
        $orderid=trim($_REQUEST['orderid']);
        $paystyle=intval(trim($_REQUEST['paystyle']));
        $paytype=intval(trim($_REQUEST['paytype']));
        $money=floatval(trim($_REQUEST['money']));
        $channel=trim("wx_pub");
        $wallet=$_REQUEST['wallet'];
        $discount=$_REQUEST['discount'];

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
                        self::wallet($order['orderid'],floatval($wallet));
                        $pingpp=self::pingpp($orderid);
                        exit($pingpp);
                    }elseif($paystyle == 2){
                        self::wallet($order['orderid'],floatval($wallet));
                        exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
                    }elseif($paystyle == 3){
                        M('order_time')->where(array('orderid'=>$orderid))->save(array(
                                'pay_status'=>1,
                                'pay_time'=>time()
                                ));
                        self::wallet($order['orderid'],floatval($wallet));
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
                        self::wallet($order['orderid'],$wallet);
                        $pingpp=self::pingpp($orderid);
                        exit($pingpp);
                    }elseif($paystyle == 2){
                        self::wallet($order['orderid'],$wallet);
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
                        
                        self::wallet($order['orderid'],$wallet);
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
        $uid=session("uid");
        $orderid=I('orderid');
        
        $user=M('Member')->where(array('id'=>$uid))->find();
        $order=M('order')->where(array('orderid'=>$orderid))->find();

        $coupons=M('coupons_order')->where(array('id'=>$order['couponsid']))->find();
        if(!$user){
            $this->error("用户不存在");
        }elseif(!$order){
            $this->error("订单不存在");
        }elseif(!$coupons){
            $this->error("优惠券不存在");
        }elseif($coupons['status']==0){
            $this->error("该优惠券尚未使用，不能取消使用");
        }else{
            $id=M('coupons_order')->where(array('id'=>$order['couponsid']))->setField('status',0);
            if($id){ 
                if($order['ordertype']==1){
                    M('order')->where(array('orderid'=>$orderid))->save(array(
                        'money'=>$order['money']+floatval($order['discount']),
                    ));
                }elseif($order['ordertype']==2){

                    M('order')->where(array('orderid'=>$orderid))->save(array(
                        'yes_money'=>$order['yes_money']-floatval($order['discount']),
                        'wait_money'=>$order['wait_money']+floatval($order['discount'])
                    ));
                    
                    
                }
                $this->redirect('Web/Order/show',array('id'=>$orderid));
            }else{
                $this->redirect('Web/Order/show',array('id'=>$orderid));
            }
        }
    }
    public function onlinpingpp(){
        $orderid = $_REQUEST['orderid'];
        $uid=session("uid");
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $openid=M('member')->where(array('id'=>$uid))->getField('user_openid');
        $orderid=$order['orderid'];
        $extra = array();
        $channel = "wx_pub";
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://www.yourdomain.com/success',
                    'cancel_url' => 'http://www.yourdomain.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => $openid
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'Productid'
                );
                break;
        }
        
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
                $money=$order['wait_money']-$order['discount'];
            }
            
        }else{
            $money=$order['money'];
        }
        
        //$money=$order['money'];
        //$money=0.01;
        $orderid=$orderid.rand(100000, 999999);
        //$money=0.01;
        $ping_config=array();
        foreach ($this->ConfigData as $r) {
            if($r['groupid'] == 5){
                $ping_config[$r['varname']] = $r['value'];
            }
        }
        //dump($ping_config['pingAppid']);
        //dump($extra);
        
        //exit;
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
                    "channel"   => $channel,
                    "client_ip" => $_SERVER["REMOTE_ADDR"],
                    "app"       => array("id" => $ping_config['pingAppid'])
                )
            );
            
            //dump($ch);
            
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,\Think\Log::INFO);
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            cookie("temporderid",$order['orderid']);
            cookie("ping_charge",$ch);
            $this->ajaxReturn('success');
        }
        catch (\Pingpp\Error\Base $e) {
            $Status = $e->getHttpStatus();
            $this->ajaxReturn($e->getHttpBody());
            $body = $e->getHttpBody();
            return 'Status: ' . $Status ." body:".$body."all".$e;
        }
    }
    
	public function pingpp($orderid){
        $uid=session("uid");
        $order=M('order')->where(array('orderid'=>$orderid))->find();
        $openid=M('member')->where(array('id'=>$uid))->getField('user_openid');
        $orderid=$order['orderid'];
        $extra = array();
		$channel = "wx_pub";
		switch ($channel) {
		    case 'alipay_wap':
		        $extra = array(
		            'success_url' => 'http://www.yourdomain.com/success',
		            'cancel_url' => 'http://www.yourdomain.com/cancel'
		        );
		        break;
		    case 'upmp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'bfb_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'upacp_wap':
		        $extra = array(
		            'result_url' => 'http://www.yourdomain.com/result?code='
		        );
		        break;
		    case 'wx_pub':
		        $extra = array(
		            'open_id' => $openid
		        );
		        break;
		    case 'wx_pub_qr':
		        $extra = array(
		            'product_id' => 'Productid'
		        );
		        break;
		}
        
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
        
        //$money=$order['money'];
        //$money=0.01;
        $orderid=$orderid.rand(100000, 999999);
        $ping_config=array();
        foreach ($this->ConfigData as $r) {
            if($r['groupid'] == 5){
                $ping_config[$r['varname']] = $r['value'];
            }
        }
        //dump($ping_config['pingAppid']);
        //dump($extra);
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
            //dump($ch);
            \Think\Log::write('ping++生成支付Charge数据：'.$ch,\Think\Log::INFO);
            M('thirdparty_send')->add(array(
                'data'=>serialize(json_decode($ch,true)),
                'type'=>"ping++",
                'ispc'=>0,
                'inputtime'=>time()
                ));
            cookie("temporderid",$order['orderid']);
            cookie("ping_charge",$ch);
			//这里直接跳转到支付页面即可
            $this->redirect('Web/Pay/index');
        }
        catch (\Pingpp\Error\Base $e) {
            $Status = $e->getHttpStatus();
            $body = $e->getHttpBody();
            return 'Status: ' . $Status ." body:".$body."all".$e;
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
            PushQueue($mid, $message_type,$receiver, $title, serialize($extras));
        }
    }
    
    public function wallet($id){
        
        $order=M('order')->where(array('orderid'=>$id))->find();
        
        $orderid=$order['orderid'];
        
        $uid=$order['uid'];
        $num=$order['wallet'];
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
                'remark'=>'订单使用钱包支付,扣除金额'.$order['wallet'].'元',
                'addip'=>get_client_ip(),
                'addtime'=>time()
                ));

            $usr=M('member')->where('id=' . $uid)->field("id,phone")->find();
            $c="尊敬的". $usr['username'] ."，您好！，您在".date("Y年m月d日 H时i分s秒") ."一笔订单成功使用钱包支付".$order['wallet']."元";
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

            $sms=json_encode(array('phone'=>$usr['phone'],'content'=>$c,'uid'=>$usr['id'],'type'=>1));
            self::sendsms($sms);
        }
    }
}
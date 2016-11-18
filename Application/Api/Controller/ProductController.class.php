<?php

namespace Api\Controller;

use Api\Common\CommonController;

class ProductController extends CommonController {

    /**
     * 获取商品列表
     */
    public function get_productlist(){
    	$ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
        $type=intval(trim($ret['type']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $keyword=trim($ret['keyword']);

        if($p==''||$num==''||$type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            
            $where['isoff']=0;
            $where['isdel']=0;
            $where['status']=2;
            switch ($type) {
                case '1':
                    # code...
                    $where['isindex']=1;
                    if(!empty($keyword)){
                        $where['title']=array("like","%".$keyword."%");
                        $search_keyword=M('search_keyword')->where(array('keyword'=>$keyword))->find();
                        if($search_keyword){
                            M('search_keyword')->where(array('keyword'=>$keyword))->save(array('hit'=>$search_keyword['hit']+1,'lastupdatetime'=>time()));
                        }else{
                            M('search_keyword')->add(array(
                                'keyword'=>$keyword,
                                'hit'=>1,
                                'inputtime'=>time(),
                                'lastupdatetime'=>time()
                                ));
                        }
                        $where['isindex']=array('in','0,1');
                    }
                    
                    $where['storeid']=$storeid;
                    $order=array('listorder'=>'desc','id'=>'desc');
                    $field=array('id,storeid,thumb,extrathumb,title,description,nowprice,standard,unit,ishot,type,isoff,expiretime,selltime,nowprice,oldprice,stock');
                    break;
                case '2':
                    # code...
                    $where['type']=2;
                    $where['storeid']=$storeid;
                    //$where['expiretime']=array('lt',time());
                    $order=array('expiretime'=>'desc','stock'=>'desc','listorder'=>'desc','id'=>'desc');
                    $field=array('id,storeid,thumb,title,description,nowprice,oldprice,standard,unit,expiretime,type,isoff,stock,step');
                    break;
                case '3':
                    # code...
                    $where['type']=3;
                    $where['storeid']=$storeid;
                    //$where['selltime']=array('lt',time());
                    $order=array('selltime'=>'desc','stock'=>'desc','listorder'=>'desc','id'=>'desc');
                    $field=array('id,storeid,thumb,title,description,nowprice,standard,unit,selltime,type,isoff,advanceprice,stock,oldprice,nowprice,step');
                    break;
                case '4':
                    # code...
                    $where['type']=0;
                    $where['storeid']=0;
                    $order=array('stock'=>'desc','listorder'=>'desc','id'=>'desc');
                    $field=array('id,storeid,thumb,title,description,nowprice,standard,unit,type,isoff,expiretime,selltime,nowprice,oldprice,stock');
                    break;
                default:
                    # code...
                    $where['type']=array('in','1,4');
                    $where['storeid']=$storeid;
                    $order=array('stock'=>'desc','listorder'=>'desc','id'=>'desc');
                    $field=array('id,storeid,thumb,title,description,nowprice,standard,unit,type,isoff,stock');
                    break;
            }
            $data=M('product')->where($where)->order($order)->page($p,$num)->field($field)->select();
            foreach ($data as $key => $value) {
                # code...
                $sellnum=M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
                if(!empty($sellnum)){
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['sellnum']=$sellnum+$value['step'];
                    }else{
                        $data[$key]['sellnum']=$sellnum;
                    }
                    
                }else{
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['sellnum']=$value['step'];
                    }else{
                        $data[$key]['sellnum']=0;
                    }
                }
                $data[$key]['unit']=getunit($value['unit']);
                $data[$key]['percent']=getproduct_evaluation($value['id']);
                if(!empty($uid)){
                    $cartid=M("cart")->where(array('uid'=>$uid))->getField("id");
                    $cartnum=M('cartinfo')->where(array('cartid'=>$cartid,'pid'=>$value['id']))->getField("num");
                    if(!empty($cartnum)){
                        $data[$key]['cartnum']=$cartnum;
                    }else{
                        $data[$key]['cartnum']=0;
                    }

                    $status=M('collect')->where(array('varname'=>'product','value'=>$value['id'],'uid'=>$uid))->find();
                    if($status){
                        $data[$key]['iscollect']=1;
                    }else{
                        $data[$key]['iscollect']=0;
                    }
                }else{
                    $data[$key]['iscollect']=0;
                    $data[$key]['cartnum']=0;
                }
            }
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 商品详情
     */
    public function get_productinfo(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $pid=intval(trim($ret['pid']));

        if($pid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $type=getproduct_type($pid);
            switch ($type) {
                case '2':
                    # code...
                    $field=array('id,storeid,brand,thumb,title,description,nowprice,oldprice,unit,imglist,backimglist,standard,content,expiretime,ishot,type,stock');
                    break;
                case '3':
                    # code...
                    $field=array('id,storeid,brand,thumb,title,description,nowprice,oldprice,unit,imglist,backimglist,standard,content,selltime,advanceprice,ishot,type,stock');
                    break;
                default:
                    # code...
                    $field=array('id,storeid,brand,thumb,title,description,nowprice,oldprice,unit,imglist,backimglist,standard,content,ishot,type,stock');
                    break;
            }
            $data=M('product')->where(array('id'=>$pid))->field($field)->find();
            $data['imglist']=explode("|", $data['imglist']);
            $data['backimglist']=explode("|", $data['backimglist']);
            $data['unit']=getunit($data['unit']);
            $sellnum=M('order_productinfo')->where(array('pid'=>$data['id']))->sum("nums");
            if(!empty($sellnum)){
                if($data['type']==2||$data['type']==3){
                    $data['sellnum']=$sellnum+$data['step'];
                }else{
                    $data['sellnum']=$sellnum;
                }
                
            }else{
                if($data['type']==2||$data['type']==3){
                    $data['sellnum']=$data['step'];
                }else{
                    $data['sellnum']=0;
                }
            }
            $data['percent']=getproduct_evaluation($data['id']);
            $cartid=M("cart")->where(array('uid'=>$uid))->getField("id");
            

            if(!empty($uid)){
                $cartnum=M('cartinfo')->where(array('cartid'=>$cartid,'pid'=>$data['id']))->getField("num");
                    if(!empty($cartnum)){
                        $data['cartnum']=$cartnum;
                    }else{
                        $data['cartnum']=0;
                    }
                $status=M('collect')->where(array('varname'=>'product','value'=>$pid,'uid'=>$uid))->find();
                if($status){
                    $data['iscollect']=1;
                }else{
                    $data['iscollect']=0;
                }
            }else{
                $data['iscollect']=0;
                $data['cartnum']=0;
            }


            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 首页定位获取门店
     */
    public function getstore(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));

        if($lat==''||$lng==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $Map=A("Api/Map");
            $areadata=$Map->get_areainfo_baidu_simple($lat.",".$lng);
            $data=M('store')->where(array('servicearea'=>array('like','%,'.$areadata['district'].',%')))->field('id as storeid,title as storename')->find();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 首页获取门店列表
     */
    public function getstorelist(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $lat=floatval(trim($ret['lat']));
        $lng=floatval(trim($ret['lng']));
        $keyword=trim($ret['keyword']);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        if($lat==''||$lng==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }else{
            $Map=A("Api/Map");
            if(empty($keyword)){
                $areadata=$Map->get_areainfo_baidu_simple($lat.",".$lng);
                $data=M('store')->where(array('servicearea'=>array('like','%,'.$areadata['district'].',%')))->field('id as storeid,title as storename,thumb,lat,lng')->find();
            }else{
                $data=M('store')->where(array('title'=>array('like','%'.$keyword.'%')))->field('id as storeid,title as storename,thumb,lat,lng')->select();
            }
            foreach ($data as $key => $value) {
                # code...
                $areadata=$Map->get_distance_baidu("driving",$lat.",".$lng,$value['lat'].",".$value['lng']);
                $data[$key]['distance']=$areadata['distance']['value'];
                $data[$key]['distancetext']=$areadata['distance']['text'];
            }
            
            foreach ($data as $value) {
                $distance[] = $value['distance'];
            }
            array_multisort($distance, SORT_ASC, $data);
            
            $result=array_slice($data,($p-1)*$num,$num);
            if($result){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$result)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 我的购物车
     */
    public function mycar(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        if($uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }else{
            $data=M('cart')->where(array('uid'=>$uid))->field('id as cartid,nums,money')->order(array('inputtime'=>'desc'))->find();
            $subdata=M('cartinfo a')->join("left join zz_store b on a.storeid=b.id")->where(array('a.cartid'=>$data['cartid']))->group("a.storeid")->field('a.storeid,b.title as storename,b.thumb as storethumb')->select();

            $pids=M("Product")->where("( `type` = 3 ) AND ( `selltime` < ".time()." ) ) OR (  ( `type` = 2 ) AND ( `expiretime` < ".time()." )")->getField("id",true);
            foreach ($subdata as $key => $value) {
                # code...
                $catids=M('store_cate')->where(array('storeid'=>$value['storeid']))->getField('catid');
                $productcate=M('productcate')->where(array('id'=>array('in',explode(",", $catids)),'parentid'=>0))->order(array('listorder'=>'desc','id'=>'asc'))->field('id,catname')->select();

                $cartinfo=M('cartinfo a')
                    ->join("left join zz_product b on a.pid=b.id")
                    ->where(array('a.cartid'=>$data['cartid'],'a.storeid'=>$value['storeid'],'b.isoff'=>0,'b.stock'=>array('gt',0),'b.id'=>array('not in',$pids)))
                    ->field('a.pid,a.num,b.title as productname,b.description,b.thumb as productthumb,b.nowprice,b.oldprice,b.standard,b.unit,b.ishot,b.type,b.catid,b.advanceprice,b.selltime,b.expiretime')
                    ->order(array('a.inputtime'=>'desc'))
                    ->select();
                    //$subdata[$key]['sql']=M('cartinfo a')->_sql();
                foreach ($cartinfo as $k => $v)
                {
                	$cartinfo[$k]['unit']=getunit($v['unit']);
                }
                
                $subdata[$key]['cartinfo']=!empty($cartinfo)?$cartinfo:"";
                foreach($productcate  as $k => $v){
                    $productcate[$k]['num']=M('cartinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.cartid'=>$data['cartid'],'a.storeid'=>$value['storeid'],'b.isoff'=>0,'b.catid'=>$v['id']))->count();
                }
                $subdata[$key]['productcateinfo']=$productcate;
            }
            $data['subdata']=$subdata;
            $totalnum = M('cartinfo a')->join("left join zz_product b on a.pid=b.id")->where(array('a.cartid'=>$data['cartid'],'b.id'=>array("not in",$pids)))->sum('num');
            $data['num']=$totalnum;
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 加入购物车
     */
    public function addcar(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $storeid=intval(trim($ret['storeid']));
        $uid=intval(trim($ret['uid']));
        $pid=json_decode($ret['pid']);

        $where['id']=$uid;
        $result=M('Member')->where($where)->find();
        $store=M('store')->where("id=" . $storeid)->find();
        if($uid==''||$storeid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$result){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$store){
            exit(json_encode(array('code'=>-200,'msg'=>"商户不存在")));
        }elseif(empty($pid)){
            exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
        }else{
            $status=M('cart')->where(array('uid'=>$uid))->find();
            if($status){
                $id=M('cart')->where(array('uid'=>$uid))->save(array(
                    'nums'=>count($pid),
                    'updatetime'=>time()
                ));
            }else{
                $id=M('cart')->add(array(
                    'uid'=>$uid,
                    'nums'=>count($pid),
                    'inputtime'=>time(),
                    'updatetime'=>time()
                ));
            }

            if($id){
                $money=0.00;
                foreach ($pid as $key => $value) {
                    # code...
                    $product=M('Product')->where(array('id'=>$key))->find();
                    M('cartinfo')->add(array(
                        'cartid'=>$id,
                        'storeid'=>$storeid,
                        'pid'=>$key,
                        'num'=>$value,
                        'price'=>$product['nowprice'],
                        'money'=>$product['nowprice']*$value,
                        'type'=>$product['type'],
                        'inputtime'=>time()
                        ));
                    $money+=$product['nowprice']*$value;
                }
                M('cart')->where(array('id'=>$id))->setField("money",$money);
                exit(json_encode(array('code'=>200,'msg'=>"加入成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"加入失败")));
            }
        }
    }
    /**
     * 删除购物车
     */
    public function delcar(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
        $type=$ret['type'];
        $user=M('Member')->where(array('id'=>$uid))->find();
        if($uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }else{
            if(!empty($storeid)){
                if($type==''){
                    exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
                }else{
                    $ids=M('cart')->where(array('uid'=>$uid))->getField("id");
                    $id=M('cartinfo')->where(array('cartid'=>array('eq',$ids),'storeid'=>$storeid,'type'=>array('in',$type)))->delete();
                    if($id){
                        $cartinfo=M('cartinfo')->where(array('cartid'=>$ids))->select();
                        $money=0.00;
                        foreach ($cartinfo as $value) {
                            # code...
                            $product=M('Product')->where(array('id'=>$value['pid']))->find();
                            $money+=$product['nowprice']*$value['num'];
                        }
                        M('cart')->where(array("uid" => $uid))->save(array(
                            'nums'=>count($cartinfo),
                            'money'=>$money,
                            'updatetime'=>time()
                            ));
                        exit(json_encode(array('code'=>200,'msg'=>"删除成功")));
                    }else{
                        exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
                    }
                }
            }else{
                $ids=M('cart')->where(array('uid'=>$uid))->getField("id");
                $id=M('cart')->where(array('uid'=>$uid))->delete();
                if($id){
                    M('cartinfo')->where(array('cartid'=>array('eq',$ids)))->delete();
                    exit(json_encode(array('code'=>200,'msg'=>"删除成功")));
                }else{
                    exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
                }
            }

            
        }
    }
    /**
     * 删除购物车中产品
     */
    public function delcar_product(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
        $pid=trim($ret['pid']);

        $user=M('Member')->where(array('id'=>$uid))->find();
        $cart=M('cart')->where(array("uid" => $uid))->find();

        if($uid==''||$pid==''||$storeid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$cart){
            exit(json_encode(array('code'=>-200,'msg'=>"购物车不存在")));
        }else{
            $id=M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>array("in",$pid)))->delete();
            if($id){
                $cartinfo=M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
                $money0=0.00;
                foreach ($cartinfo as $value) {
                    # code...
                    $product0=M('Product')->where(array('id'=>$value['pid']))->find();
                    if($product0['type']==4){
                        $money0+=$value['num']*$product0['standard']*$product0['price'];
                    }elseif($product0['type']==3){
                        $money0+=$value['num']*$product0['advanceprice'];
                    }else{
                        $money0+=$value['num']*$product0['nowprice']; 
                    }
                }
                M('cart')->where(array("uid" => $uid))->save(array(
                    'nums'=>count($cartinfo),
                    'money'=>$money0,
                    'updatetime'=>time()
                    ));
                exit(json_encode(array('code'=>200,'msg'=>"删除成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
            }
        }
    }

    /**
     * 购物车产品数量增减
     */
    public function modifycar(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $storeid=intval(trim($ret['storeid']));
        $uid=intval(trim($ret['uid']));
        $pid=intval(trim($ret['pid']));
        $num=intval(trim($ret['num']));

        $user=M('Member')->where("id=" . $uid)->find();
        $cart=M('cart')->where("uid=" . $uid)->find();
        $store=M('Store')->where("id=" . $storeid)->find();
        $product=M('product')->where(array("id="=> $pid))->find();
        if($uid==''||$storeid==''||$pid==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$cart){
            exit(json_encode(array('code'=>-200,'msg'=>"购物车产品不存在")));
        }elseif(!$store){
            exit(json_encode(array('code'=>-200,'msg'=>"门店不存在")));
        }elseif(!$product){
            exit(json_encode(array('code'=>-200,'msg'=>"商品不存在")));
        }else{
            $product=M('Product')->where(array('id'=>$pid))->find();
            $id=M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->save(array(
                'num'=>$num,
                'price'=>$product['nowprice'],
                'money'=>$product['nowprice']*$num,
                'updatetime'=>time()
                ));
            if($id){
                $cartinfo=M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
                $money=0.00;
                foreach ($cartinfo as $value) {
                    # code...
                    $product=M('Product')->where(array('id'=>$value['pid']))->find();
                    $money+=$product['nowprice']*$value['num'];
                }
                M('cart')->where(array('id'=>$id))->setField("money",$money);
                exit(json_encode(array('code'=>200,'msg'=>"更新成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"更新失败")));
            }
        }
    }
     /*
    **添加购物车中产品
    */
    public function addcar_product(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
        $pid=intval(trim($ret['pid']));
        $num=intval(trim($ret['num']));

        $user=M('Member')->where(array('id'=>$uid))->find();
        $product=M('product')->where(array("id"=> $pid))->find();

        if($uid==''||$pid==''||$storeid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"请求参数错误")));
        }elseif(!$user){
            exit(json_encode(array('code'=>-200,'msg'=>"用户不存在")));
        }elseif(!$product){
            exit(json_encode(array('code'=>-200,'msg'=>"产品不存在")));
        }else{
            $cart=M('cart')->where(array("uid" => $uid))->find();
            if($product['type']==4){
                $money=$num*$product['standard']*$product['price'];
            }elseif($product['type']==3){
                $money=$num*$product['advanceprice'];
            }else{
                $money=$num*$product['nowprice']; 
            }
            if(!empty($cart)){
                $status=M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->find(); 
                if($status){
                    if($num==0){
                        $id=M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->delete($status['id']);
                    }else{
                        if($num>$status['num']){
                            if($product['type']==3&&$product['selltime']<time()){
                                exit(json_encode(array('code'=>-200,'msg'=>"商品已过期啦！！")));
                            }
                            if($product['type']==2&&$product['expiretime']<time()){
                                exit(json_encode(array('code'=>-200,'msg'=>"商品已过期啦！！")));
                            }
                            if($product['stock']==0){
                                exit(json_encode(array('code'=>-200,'msg'=>"该商品正在补货中！")));
                            }
                            if($num>$product['stock']&&$product['stock']>0){
                                exit(json_encode(array('code'=>-200,'msg'=>"该商品库存不足！")));
                            }
                        }
                        
                        $id=M('cartinfo')->where(array('cartid'=>$cart['id'],'storeid'=>$storeid,'pid'=>$pid))->save(array(
                            'cartid'=>$cart['id'],
                            'storeid'=>$storeid,
                            'pid'=>$pid,
                            'num'=>$num,
                            'price'=>$product['nowprice'],
                            'money'=>$money,
                            'type'=>$product['type']
                            ));
                    }
                    
                }else{
                    $id=M('cartinfo')->add(array(
                        'cartid'=>$cart['id'],
                        'storeid'=>$storeid,
                        'pid'=>$pid,
                        'num'=>$num,
                        'price'=>$product['nowprice'],
                        'money'=>$money,
                        'type'=>$product['type'],
                        'inputtime'=>time()
                        ));
                }
            }else{
                $cart['id']=M('cart')->add(array(
                    'uid'=>$uid,
                    'inputtime'=>time(),
                    'updatetime'=>time()
                ));
                $id=M('cartinfo')->add(array(
                    'cartid'=>$cart['id'],
                    'storeid'=>$storeid,
                    'pid'=>$pid,
                    'num'=>$num,
                    'price'=>$product['nowprice'],
                    'money'=>$money,
                    'type'=>$product['type'],
                    'inputtime'=>time()
                    ));
            }

            if($id){
                $cartinfo=M('cartinfo')->where(array('cartid'=>$cart['id']))->select();
                $money0=0.00;
                foreach ($cartinfo as $value) {
                    # code...
                    $product0=M('Product')->where(array('id'=>$value['pid']))->find();
                    if($product0['type']==4){
                        $money0+=$value['num']*$product0['standard']*$product0['price'];
                    }elseif($product0['type']==3){
                        $money0+=$value['num']*$product0['advanceprice'];
                    }else{
                        $money0+=$value['num']*$product0['nowprice']; 
                    }
                }
                M('cart')->where(array("uid" => $uid))->save(array(
                    'nums'=>count($cartinfo),
                    'money'=>$money,
                    'updatetime'=>time()
                    ));
                exit(json_encode(array('code'=>200,'msg'=>"提交成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"提交失败")));
            }
        }
    }
    /**
     * 获取商户分类
     */
    public function get_shopcate(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $storeid=intval(trim($ret['storeid']));

        $store=M('store')->where(array('id'=>$storeid))->find();
        if($storeid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(!$store){
            exit(json_encode(array('code'=>-200,'msg'=>"The Store is not exist!")));
        }else{
            $catids=M('store_cate')->where(array('storeid'=>$storeid))->getField('catid');
            $data=M('productcate')->where(array('id'=>array('in',explode(",", $catids)),'parentid'=>0))->order(array('listorder'=>'desc','id'=>'asc'))->field('id,catname')->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 根据门店获取商品列表
     */
    public function get_shop_productlist(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $storeid=intval(trim($ret['storeid']));
        $catid=intval(trim($ret['catid']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));

        $store=M('Store')->where(array('id'=>$storeid))->find();
        if($p==''||$num==''||$storeid==''||$catid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(!$store){
            exit(json_encode(array('code'=>-200,'msg'=>"The Store is not exist!")));
        }else{
            $where['catid']=$catid;
            $where['isoff']=0;
            $where['isdel']=0;
            $where['status']=2;
            $where['storeid']=$storeid;
            $where['type']=array('in','1,4');
            $data=M('product')->where($where)->order(array('stock'=>'desc','listorder'=>'desc','id'=>'desc'))->page($p,$num)->field('id,storeid,thumb,extrathumb,title,description,nowprice,oldprice,standard,unit,ishot,isoff,type,expiretime,selltime,stock')->select();
            foreach ($data as $key => $value) {
                $data[$key]['unit']=getunit($value['unit']);
                # code...
                if(!empty($uid)){
                    $cartid=M("cart")->where(array('uid'=>$uid))->getField("id");
                    $cartnum=M('cartinfo')->where(array('cartid'=>$cartid,'pid'=>$value['id']))->getField("num");
                    if(!empty($cartnum)){
                        $data[$key]['cartnum']=$cartnum;
                    }else{
                        $data[$key]['cartnum']=0;
                    }
                    $status=M('collect')->where(array('varname'=>'product','value'=>$value['id'],'uid'=>$uid))->find();
                    if($status){
                        $data[$key]['iscollect']=1;
                    }else{
                        $data[$key]['iscollect']=0;
                    }
                }else{
                    $data[$key]['iscollect']=0;
                    $data[$key]['cartnum']=0;
                }
            }

            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    /**
     * 获取商品评论列表
     */
    public function get_product_evaluatelist(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $pid=intval(trim($ret['pid']));
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $type=intval(trim($ret['type']));

        $product=M('Product')->where(array('id'=>$pid))->find();
        if($p==''||$num==''||$pid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(!$product){
            exit(json_encode(array('code'=>-200,'msg'=>"The Product is not exist!")));
        }else{
            if(!empty($type)){
                $where['varname']='product';
                $where['value']=$pid;
                if($type==1){
                    $where['total']=array('in','5');
                }elseif($type==2){
                    $where['total']=array('in','3,4');
                }elseif($type==3){
                    $where['total']=array('in','1,2');
                }
                $evaluateinfo=M('evaluation')->where($where)->order(array('id'=>'desc'))->page($p,$num)->field('id,uid,total,thumb,content,inputtime')->select();
                foreach ($evaluateinfo as $key => $value) {
                    # code...
                    $evaluateinfo[$key]['thumb']=explode("|", $value['thumb']);
                    $evaluateinfo[$key]['head']=M('member')->where(array('id'=>$value['uid']))->getField("head");
                    
                    $user=M('member')->where(array('id'=>$value['uid']))->field("username,phone")->find();
                    $username=!empty($user['phone'])?$user['phone']:$user['usernmae'];
                    $evaluateinfo[$key]['username']=$username;

                }
                if(!empty($evaluateinfo)){
                    $data['evaluateinfo']=$evaluateinfo;
                }

            }else{
                $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$pid))->order(array('id'=>'desc'))->page($p,$num)->field('id,uid,total,thumb,content,inputtime')->select();
                foreach ($evaluateinfo as $key => $value) {
                    # code...
                    $evaluateinfo[$key]['thumb']=explode("|", $value['thumb']);
                    $evaluateinfo[$key]['head']=M('member')->where(array('id'=>$value['uid']))->getField("head");
                    
                    $user=M('member')->where(array('id'=>$value['uid']))->field("username,phone")->find();
                    $username=!empty($user['phone'])?$user['phone']:$user['usernmae'];
                    $evaluateinfo[$key]['username']=$username;

                }
                if(!empty($evaluateinfo)){
                    $hign_percent=getproduct_evaluationpercent($pid,'5');
                    $middle_percent=getproduct_evaluationpercent($pid,'3,4');
                    $low_percent=getproduct_evaluationpercent($pid,'1,2');
                    $data['percent']=array(
                    'hign_percent'=>$hign_percent,
                    'middle_percent'=>$middle_percent,
                    'low_percent'=>$low_percent,
                    );
                    $data['evaluateinfo']=$evaluateinfo;
                }
            }
            
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
    public function hotsearch(){
        $ret = $GLOBALS['HTTP_RAW_POST_DATA'];
        $ret=json_decode($ret,true);
        $data=M('search_keyword')->order(array('listorder'=>'desc','hit'=>'desc','inputtime'=>'desc'))->limit(15)->field("keyword,hit")->select();
        if($data){
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }else{
            exit(json_encode(array('code'=>-201,'msg'=>"There is no such information!")));
        }
    }
}
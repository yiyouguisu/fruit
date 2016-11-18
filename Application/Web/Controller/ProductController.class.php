<?php

namespace Web\Controller;

use Web\Common\CommonController;

use Org\Util\Page;

class ProductController extends CommonController {
	public function _initialize() {
        parent::_initialize();
        $this->cart_total_num();
    }
    public function pempty(){
        $this->display();
    }
	//商品首页左侧初始化
    public function lists() {
        $uid = session('uid');
        $this->assign("uid",$uid);
        $storeid = session('storeid');
        if (!$storeid) {
            $this->error('请先选择店铺！',U('Web/Index/index'));
        }

        $catid = I('get.catid', null, 'intval');
        if(empty($catid)){
            $catids=M('store_cate')->where(array('storeid'=>$storeid))->getField('catid',true);
            $data=M('productcate')->where(array('id'=>array('in',$catids),'parentid'=>0))->order(array('listorder'=>'desc','id'=>'asc'))->select();
            if($data){
                $catid = $data[0]["id"];
            }
        }
        $this->assign("catid",$catid);
        
    	$goods_info_img = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$goods_info_title = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	

        $catids=M('store_cate')->where(array('storeid'=>$storeid))->getField('catid');
        $data=M('productcate')->where(array('id'=>array('in',explode(",", $catids)),'parentid'=>0))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        if($data){
            foreach ($data as $k => $r) {
                $data[$k]["id"] = $this->str_cut($r["id"], 30);
                $data[$k]["catname"] = $this->str_cut($r["catname"], 30);
            }
            $this->assign("goods_info_img" , $goods_info_img);
            $this->assign("goods_info_title" , $goods_info_title);
            $this->assign("data", $data);
        }

        
        /***一些店铺信息***/
    	$data = M('store')->where("id=".$storeid)->find();
    	$this->assign("place",$data["title"]);

        $this->display();
    }
    
    //商品列表json输出
    public function  infolist()
    {
    	$catid = isset($_REQUEST['catid']) ? intval($_REQUEST['catid']) :1;
    	$pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] :"";
        
    	$uid = session('uid');
    	$cart = M('cart')->where("uid=" . $uid)->find();
    	$where = array();
        
        $where['storeid'] = session('storeid');
        $where["isoff"]=array("EQ","0");
        $where['isdel']=array("EQ","0");
        
        if(!empty($keyword) ){
            $keyword = urldecode($keyword);
            $where["title"] = array("like", "%".$keyword."%");
        }else{
            $where['type']=array("IN","1,4");
        }
        
    	if (!empty($catid)) 
            $where["catid"] = array("EQ", $catid);

        $today = time();
    	$count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $this->assign("data", $data);
        $show    = $page->show();
        $data = D("Product")->where($where)->page($pagenum.',10')->order(array('stock'=>'desc','selltime'=>'desc','expiretime'=>'desc','isindex'=>'desc','listorder'=>'desc','id'=>'desc'))->select();
        foreach ($data as $key => $value) {
        	$data[$key]["catnum"] = M('cartinfo')->where(array('cartid'=>$cart['id'],'pid'=>$value['id']))->sum('num');
            $data[$key]['unit']=getunit($data[$key]['unit']);
            
            $lastset =$data[$key]["expiretime"] - $today;
            if($lastset<0){
                if($data[$key]["type"]=='2' || $data[$key]["type"]=='3'){
                    $data[$key]['isover'] = "1";
                }
            }
        	if(empty($data[$key]["catnum"]))
        		$data[$key]["catnum"] = 0;
        }
        echo (json_encode($data));	
    }
    
    public function group(){
    	$goods_info_img = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$goods_info_title = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$catid = I('get.catid', null, 'intval');
    	$data = D("productcate")->select();
    	foreach ($data as $k => $r) {
            $data[$k]["id"] = $this->str_cut($r["id"], 30);
            $data[$k]["catname"] = $this->str_cut($r["catname"], 30);
        }
        $this->assign("catid",$catid);
        $this->assign("goods_info_img" , $goods_info_img);
        $this->assign("goods_info_title" , $goods_info_title);
        $this->assign("data", $data);
        
        $this->display();
    }
    
    public function grouplist(){
    	$pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
    	$uid = session('uid');
    	$cart = M('cart')->where("uid=" . $uid)->find();
    	$where = array();
        $where['storeid'] = session('storeid');
        $where["type"] = array("EQ", "2");
        $where["isoff"]=array("EQ","0");
        $where['isdel']=array("EQ","0");
        
        $page = new \Think\Page($count, 10);
    	$count = D("Product")->where($where)->count();
        //    	echo json_encode(D("Product")->_sql());
        //    	exit;
        $this->assign("data", $data);
        $show    = $page->show();
        $data = D("Product")->where($where)->page($pagenum.',10')->order(array('expiretime'=>'desc','stock'=>'desc','listorder'=>'desc','id'=>'desc'))->select();
        $today = time();
        foreach ($data as $key => $value) {
            $lastset =$data[$key]["expiretime"] - $today;
            if($lastset<0){
                $data[$key]['isover'] = "1";
            }
            
            $data[$key]["today"] = date('Y-m-d H:i:s',$today);
            $data[$key]["lastset"] = $lastset;
            $data[$key]["expiretime"] = date('Y-m-d H:i:s',$data[$key]["expiretime"]);
            
            $day = floor($lastset/(3600*24));
            $lastset = $lastset%(3600*24);//除去整天之后剩余的时间
            $hour = floor($lastset/3600);
            $lastset = $lastset%3600;//除去整小时之后剩余的时间 
            $minute = floor($lastset/60);
            $lastset = $lastset%60;//除去整分钟之后剩余的时间 
            
        	$data[$key]["lday"] = $day;
            $data[$key]["lhour"] = $hour;
        	$data[$key]["lminit"] = $minute;
            
            $data[$key]['unit']=getunit($data[$key]['unit']);
            
        	$data[$key]["catnum"] = M('cartinfo')->where(array('cartid'=>$cart['id'],'pid'=>$value['id']))->sum('num');
        	if(empty($data[$key]["catnum"]))
        		$data[$key]["catnum"] = 0;
        	$data[$key]["paynum"] = M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
        	if (empty($data[$key]["paynum"]))
        		$data[$key]["paynum"] = 0;
        }
        $this->ajaxReturn($data);
        //echo (json_encode($data));	
    }
    
    
    
	public function reserve(){
    	$goods_info_img = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$goods_info_title = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$catid = I('get.catid', null, 'intval');
    	$data = D("productcate")->select();
    	foreach ($data as $k => $r) {
            $data[$k]["id"] = $this->str_cut($r["id"], 30);
            $data[$k]["catname"] = $this->str_cut($r["catname"], 30);
        }
        $this->assign("catid",$catid);
        $this->assign("goods_info_img" , $goods_info_img);
        $this->assign("goods_info_title" , $goods_info_title);
        $this->assign("data", $data);
        
        $this->display();
    }
    
    public function reservelist(){
    	$pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
    	$uid = session('uid');
    	$cart = M('cart')->where("uid=" . $uid)->find();
    	$where = array();
        $where['storeid'] = session('storeid');
        $where["type"] = array("EQ", "3");
        $where["isoff"]=array("EQ","0");
        $where['isdel']=array("EQ","0");
        
        $page = new \Think\Page($count, 10);
    	$count = D("Product")->where($where)->count();
        //    	echo json_encode(D("Product")->_sql());
        //    	exit;
        $this->assign("data", $data);
        $show    = $page->show();
        $data = D("Product")->where($where)->page($pagenum.',10')->order(array('selltime'=>'desc','stock'=>'desc','listorder'=>'desc','id'=>'desc'))->select();
        $today = time();
        foreach ($data as $key => $value) {
            $lastst = $data[$key]["selltime"] - $today;
            if($lastst<0){
                $data[$key]["isover"] = "1";
            }
            $data[$key]['unit']=getunit($data[$key]['unit']);
        	$data[$key]["slodtime"] = date('Y-m-d',$data[$key]["selltime"]);
        	$data[$key]["catnum"] = M('cartinfo')->where(array('cartid'=>$cart['id'],'pid'=>$value['id']))->sum('num');
        	if(empty($data[$key]["catnum"]))
        		$data[$key]["catnum"] = 0;
        	$data[$key]["paynum"] = M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
            $sellnum=M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
                if(!empty($sellnum)){
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['paynum']=$sellnum+$value['step'];
                    }else{
                        $data[$key]['paynum']=$sellnum;
                    }
                    
                }else{
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['paynum']=$value['step'];
                    }else{
                        $data[$key]['paynum']=0;
                    }
                }
        }
        echo (json_encode($data));	
    }
    
    
	public function company(){
    	$goods_info_img = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$goods_info_title = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$catid = I('get.catid', null, 'intval');
    	$data = D("productcate")->select();
    	foreach ($data as $k => $r) {
            $data[$k]["id"] = $this->str_cut($r["id"], 30);
            $data[$k]["catname"] = $this->str_cut($r["catname"], 30);
        }
        $this->assign("catid",$catid);
        $this->assign("goods_info_img" , $goods_info_img);
        $this->assign("goods_info_title" , $goods_info_title);
        $this->assign("data", $data);
        
        $this->display();
    }
    
    public function companylist(){
    	$pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
    	$uid = session('uid');
    	$cart = M('cart')->where("uid=" . $uid)->find();
    	$where = array();
        //$where['storeid'] = session('storeid');
        $where["type"] = array("EQ", "0");
        $where["isoff"]=array("EQ","0");
        $page = new \Think\Page($count, 10);
    	$count = D("Product")->where($where)->count();
        //    	echo json_encode(D("Product")->_sql());
        //    	exit;
        $this->assign("data", $data);
        $show    = $page->show();
        $data = D("Product")->where($where)->page($pagenum.',10')->order(array('selltime'=>'desc','stock'=>'desc','expiretime'=>'desc','listorder'=>'desc','isindex'=>'desc','id'=>'desc'))->select();
        //$this->ajaxReturn(D("Product")->_sql());
        foreach ($data as $key => $value) {
        	$data[$key]["slodtime"] = date('Y-m-d',$data[$key]["selltime"]);
        	$data[$key]["catnum"] = M('cartinfo')->where(array('cartid'=>$cart['id'],'pid'=>$value['id']))->sum('num');
            $data[$key]['unit']=getunit($data[$key]['unit']);
            
        	if(empty($data[$key]["catnum"]))
        		$data[$key]["catnum"] = 0;
        	$data[$key]["paynum"] = M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
        	$sellnum=M('order_productinfo')->where(array('pid'=>$value['id']))->sum("nums");
                if(!empty($sellnum)){
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['paynum']=$sellnum+$value['step'];
                    }else{
                        $data[$key]['paynum']=$sellnum;
                    }
                    
                }else{
                    if($value['type']==2||$value['type']==3){
                        $data[$key]['paynum']=$value['step'];
                    }else{
                        $data[$key]['paynum']=0;
                    }
                }
        }
        echo (json_encode($data));	
    }
    
    
    
    //商品详情
    public function infoview()
    {
    	$id= I('get.id', null, 'intval');
    	$uid = session('uid');
        $this->assign("uid",$uid);
    	if (empty($id)) {
            $this->show('<script>alert("当前产品已下架！")</script>');
            echo "<script>history.go(-1)</script>";
        }

        $data=D("Product")->where("id=".$id)->find();
        $today = time();
        if($data['type'] == '3'){
            $lastst = $data['selltime'] - $today;
            if($lastst<0)
                $data['issellover'] = '1';            

            if($data['issellover']=='1')
                $data['isover'] = 'display:none';
        }
        if($data['type'] == '2'){
            
            $lastst = $data['expiretime'] - $today;
            if($lastst<0)
                $data['isexpireover'] = '1';

            if($data['isexpireover']=='1')
                $data['isover'] = 'display:none';
        }

        $data['selltime'] = date('Y-m-d H:i:s',$data['selltime']);
        $data['expiretime'] = date('Y-m-d H:i:s',$data['expiretime']);
        $data['percent']=floatval(getproduct_evaluation($data['id'])) ;
        $data['unit']=getunit($data['unit']);
        //dump($data);
        $this->assign("data",$data);
        //图片集合
        $imglist=  array_filter(explode("|", $data["imglist"]));
        //echo var_dump($imglist);
        $this->assign("imglist", $imglist);
        $backimglist = array_filter(explode("|", $data["backimglist"]));
        $this->assign("backimglist", $backimglist);
        //这里需要把关注的相关信息取出来
        $data = M('attention')->where(array('pid'=>$id,'uid'=>$uid))->find();
        if (empty($data))
        	$this->assign("isatt","0");
        else 
        	$this->assign("isatt","1");
        //这里取出购物车的总金额	
        $data = M('cart')->where(array('uid'=>$uid))->find();
        if($data == null){
            $this->assign("carttotal",'0');
        }else{
            $this->assign("carttotal",$data['money']);
        }

        $data = M('cartinfo')->where(array('cartid'=>$data['id'],'pid'=>$id))->find();
        if($data == null){
            $this->assign("pnum",0);
        }
        else{
            $this->assign("pnum",$data['num']);
        }

        $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$id))->count();
        if ($evaluateinfo >0 )
        {
        	$this->assign('isdiscuss','1');
        }else{
        	$this->assign('isdiscuss','0');
        }
        
        //dump($data);
    	$this->display();
    }
    
    public function shareview(){
        $id= I('get.id', null, 'intval');
        $uid = I('get.id', null, 'intval');
        if(!empty($uid)){
            $uid = session('uid');
        }
    	if (empty($id)) {
            $this->show('<script>alert("当前产品已下架！")</script>');
            echo "<script>history.go(-1)</script>";
        }

        $data=D("Product")->where("id=".$id)->find();
        $today = time();
        if($data['type'] == '3'){
            $lastst = $data['selltime'] - $today;
            if($lastst<0)
                $data['issellover'] = '1';            

            if($data['issellover']=='1')
                $data['isover'] = 'display:none';
        }
        if($data['type'] == '2'){
            
            $lastst = $data['expiretime'] - $today;
            if($lastst<0)
                $data['isexpireover'] = '1';

            if($data['isexpireover']=='1')
                $data['isover'] = 'display:none';
        }

        $data['selltime'] = date('Y-m-d H:i:s',$data['selltime']);
        $data['expiretime'] = date('Y-m-d H:i:s',$data['expiretime']);
        $data['percent']=getproduct_evaluation($data['id']);
        $data['unit']=getunit($data['unit']);
        
        //dump($data);
        $this->assign("data",$data);
        //图片集合
        $imglist=  array_filter(explode("|", $data["imglist"]));
        //echo var_dump($imglist);
        $this->assign("imglist", $imglist);
        $backimglist = array_filter(explode("|", $data["backimglist"]));
        $this->assign("backimglist", $backimglist);
        //这里需要把关注的相关信息取出来
        $data = M('attention')->where(array('pid'=>$id,'uid'=>$uid))->find();
        if (empty($data))
        	$this->assign("isatt","0");
        else 
        	$this->assign("isatt","1");
        //这里取出购物车的总金额	
        $data = M('cart')->where(array('uid'=>$uid))->find();
        if($data == null){
            $this->assign("carttotal",'0');
        }else{
            $this->assign("carttotal",$data['money']);
        }

        $data = M('cartinfo')->where(array('cartid'=>$data['id'],'pid'=>$id))->find();
        if($data == null){
            $this->assign("pnum",0);
        }
        else{
            $this->assign("pnum",$data['num']);
        }

        $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$id))->count();
        if ($evaluateinfo >0 )
        {
        	$this->assign('isdiscuss','1');
        }else{
        	$this->assign('isdiscuss','0');
        }
        
        //dump($data);
    	$this->display();
    }

    public function discuss(){
        $pid=intval(trim(I('get.id')));
        $hign_percent=getproduct_evaluationpercent($pid,'5');
        $middle_percent=getproduct_evaluationpercent($pid,'3,4');
        $low_percent=getproduct_evaluationpercent($pid,'1,2');

        $this->assign('hign',$hign_percent);
        $this->assign('middle',$middle_percent);
        $this->assign('low',$low_percent);

        $this->display();
    }


    /**
     * 获取商品评论列表(需要翻页)
     */
    public function get_product_evaluatelist(){
        $uid=session('uid');
        $pid=intval(trim($_REQUEST['pid']));
        $pagenum = isset($_REQUEST['Page']) ? intval($_REQUEST['Page']) :1;
        $type=intval(trim($_REQUEST['type']));
        //$this->ajaxReturn($uid);
        //exit;
        $user=M('Member')->where(array('id'=>$uid))->find();
        $product=M('Product')->where(array('id'=>$pid))->find();
        if($pid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(!$product){
            exit(json_encode(array('code'=>-200,'msg'=>"The Product is not exist!")));
        }else{
            if($type==1){
                $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$pid,'total'=>5))->order(array('id'=>'desc'))->page($pagenum.',10')->field('id,uid,total,thumb,content,inputtime')->select();
            }elseif($type==2){
                $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$pid,'total'=>array('in','3,4')))->order(array('id'=>'desc'))->page($pagenum.',10')->field('id,uid,total,thumb,content,inputtime')->select();
            }elseif($type==3){
                $evaluateinfo=M('evaluation')->where(array('varname'=>'product','value'=>$pid,'total'=>array('in','1,2')))->order(array('id'=>'desc'))->page($pagenum.',10')->field('id,uid,total,thumb,content,inputtime')->select();
            }
            
            foreach ($evaluateinfo as $key => $value) {
                # code...
                $evaluateinfo[$key]['thumb']=explode("|", $value['thumb']);
                $evaluateinfo[$key]['head']=M('member')->where(array('id'=>$value['uid']))->getField("head");
                $username=M('member')->where(array('id'=>$value['uid']))->getField("username");
                //$username=!empty($user['phone'])?$user['phone']:$user['username'];

                $evaluateinfo[$key]['username']=substrreplace($username);
                $evaluateinfo[$key]['inputtime']=date("Y-m-d H:i:s",$value['inputtime']);
            }
            $hign_percent=getproduct_evaluationpercent($pid,'5');
            $middle_percent=getproduct_evaluationpercent($pid,'3,4');
            $low_percent=getproduct_evaluationpercent($pid,'1,2');
            $data['percent']=array(
                'hign_percent'=>$hign_percent,
                'middle_percent'=>$middle_percent,
                'low_percent'=>$low_percent,
                );
            $data['evaluateinfo']=$evaluateinfo;
            if($data){
                $this->ajaxReturn($evaluateinfo);
                //exit(json_encode(array('code'=>200,'msg'=>"获取数据成功",'data'=>$data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }
        }
    }
}
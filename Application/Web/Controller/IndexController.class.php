<?php
namespace Web\Controller;
use Web\Common\CommonController;

class IndexController extends CommonController {
	public function _initialize() {
        parent::_initialize();
        $this->cart_total_num();
    }
    
    public function index() {
        $uid = session('uid');
        if(!isset($_COOKIE['web_user_openid'])){
            $Wxhelp=A('Web/Wxhelp');
            $openId = $Wxhelp->GetOpenid();
            cookie("user_openid",$openId);
            if($uid){
                M('member')->where(array('user_openid'=>$openId))->setField('user_openid','');
                M('member')->where(array('id'=>$uid))->setField('user_openid',$openId);
            }
        }
        
		$storeid = I('get.id','',intval);
		if (!empty($storeid)){
			session("storeid",$storeid);
            $store = M('store')->where("id=".$storeid)->field('id as storeid,title as storename')->find();
		}else{
            if(!session('storeid')){
                $store = M('store')->where("id=".$storeid)->field('id as storeid,title as storename')->find();
                session("storeid",$store['id']);
                $storeid=$store['id'];
            }else{
                $storeid=session('storeid');
                $store = M('store')->where("id=".$storeid)->field('id as storeid,title as storename')->find();
            }
            
        }
        $this->assign("storeid",$storeid);   
        $this->assign("place",$store["storename"]);    
    	/***一些店铺信息***/
    	
        if (!empty($store)){
            $this->assign("storestatus","1");
        }else{
            $this->assign("place","选择店铺");
        }
        
    	/***广告banner***/
        if(!empty($storeid)){
            $data=M("ad")->where(array('status'=>1,'storeid'=>array('in',array('0',$storeid))))->order(array('isadmin'=>"desc",'listorder'=>"desc",'inputtime'=>"desc"))->field('id,title,image,pid,url,content,description,inputtime,type')->select();
        }else{
            $data=M("ad")->where(array('status'=>1,'storeid'=>0))->order(array('isadmin'=>"desc",'listorder'=>"desc",'inputtime'=>"desc"))->field('id,title,image,pid,url,content,description,inputtime,type')->select();
        }
        $this->assign("adimglist",$data);
        /***推荐商品图片***/
        $where = array();
        $where['isoff']=0;
        $where['isdel']=0;
        $where['status']=2;
        $where['isindex']=1;
        $where['storeid']=$storeid;
    	$data = M('product')->where($where)->order(array('listorder'=>'desc','id'=>'desc'))->select();
        $this->assign("indeximglist",$data);

        /***用户信息***/
        $data = M('Member')->where("id=".$uid)->find();
        $this->assign("userinfo",$data);
        $this->display();
    }
    
    public function getstore(){
        $lat = $_REQUEST['lat'];
        $lng = $_REQUEST['lng'];
    	//没正式进入微信状态这段代码注释掉
    	cookie("lng",$lng);
        cookie("lat",$lat);
    	$Map=A("Api/Map");
        $areadata=$Map->get_areainfo_baidu_simple($lat.",".$lng);
        $storeid=session('storeid');
        if (!$storeid)
        {
            $data=M('store')->where(array('servicearea'=>array('like','%,'.$areadata['district'].',%')))->field('id as storeid,title as storename')->find();
        }else
        {
            $data=M('store')->where('id='.$storeid)->field('id as storeid,title as storename')->find();
        }
        $data['sql']=M('store')->_sql();
    	//cookie("storeid",$data['storeid'],43200);
    	$this->ajaxReturn($data);
    }


    public function view(){
        $id=I('get.id');
        $data = M('ad')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->display();
    }
    
    public function sendview(){
        $id=I('get.id');
        $data = M('ad')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->display();
    }

    public function pushview(){
        $id=I('get.id');
        $data = M('push')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->display();
    }
}
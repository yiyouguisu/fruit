<?php

namespace Web\Controller;

use Web\Common\CommonController;

class AddressController extends CommonController {

	public function index() {
		$uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
			$data = M('address')->where("uid=".$uid)->select();
			foreach ($data as $key=>$value){
				$areatemp = explode(',',$data[$key]["area"]);
				$area = "";
				for ($i=0;$i<3;$i++){
					$area.=M('area')->where("id=".$areatemp[$i])->getField('name');
				}
				$data[$key]["editurl"] = U('Web/Address/edit',array("id"=>$data[$key]["id"]));
				$data[$key]["addressurl"] = U('Web/cat/submits',array("id"=>$data[$key]["id"]));
	            $data[$key]["fapiaourl"] = U('Web/Invoic/changeaddr',array("id"=>$data[$key]["id"]));
				$data[$key]["area"] = $area;
			}
			$orderid = I('get.addressid');
	        $fapiaoid = I('get.fapiaoid');
	        $this->assign('orderid',$orderid);
	        $this->assign('fapiaoid',$fapiaoid);
			if(!empty($orderid)){
				$orderid = 1;
			}
			else {
				$orderid = 0;
	            if(!empty($fapiaoid)){
	                $orderid = 2;
	            }else{
	                $orderid = 0;
	            }
			}

			$this->assign("addurl",$orderid);
			$this->assign("addlist",$data);
	        $this->display();
	    }
    }
    public function address_add(){
    	$uid = session('uid');
    	if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        }

    	if(IS_POST){
    		$addresscount = M('address')->where(array('uid'=>$uid))->count();
    		$id=M('address')->add(array(
				'uid'=>$uid,
				'area'=>$_POST['area'],
				'address'=>$_POST['address'],
				'remark'=>$_POST['remark'],
				'name'=>$_POST['name'],
				'tel'=>$_POST['tel'],
				'type'=>$_POST['type'],
				'isdefault'=>$_POST['isdefault'],
				'lat'=>$_POST['lat'],
				'lng'=>$_POST['lng'],
				'inputtime'=>time(),
				'updatetime'=>time()
			));

			if($id){
				if($isdefault==1){
					M('address')->where(array('id'=>array('neq'=>$id),'uid'=>$uid))->save(array('isdefault'=>0));
				}else{
	    			if ($addresscount == 0)
	    			{
	    				M('address')->where(array('id'=>array('eq'=>$id),'uid'=>$uid))->save(array('isdefault'=>1));
		    			M('address')->where(array('id'=>array('neq'=>$id),'uid'=>$uid))->save(array('isdefault'=>0));
	    			}
				}
				$this->success("添加成功",U('Web/Address/index'));
			}else{
				$this->error("添加失败");
			}
    	}else{
    		$lat = $_COOKIE['web_add_lat'];
	    	$lng = $_COOKIE['web_add_lng'];
	        $this->assign('lat',$lat);
	        $this->assign('lng',$lng);
	        $Data = D("area")->where(array('parentid' => 0,'status'=>1))->select();
			$this->assign("provincelist",$Data);
	        $this->display();
    	}
       	
    }
    public function address_edit(){
    	$uid = session('uid');

       	$lat = $_COOKIE['web_add_lat'];
    	$lng = $_COOKIE['web_add_lng'];

        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $this->display();
    }
    public function testmap(){
    	$uid = session('uid');

       	$lat = $_COOKIE['web_add_lat'];
    	$lng = $_COOKIE['web_add_lng'];

        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $Map=A("Api/Map");
        $data=$Map->get_addressinfo_pois($lat.",".$lng);
        $this->assign('data',$data);
        $this->display();
    }

    public function map(){
    	$uid = session('uid');

       	$lat = $_COOKIE['web_add_lat'];
    	$lng = $_COOKIE['web_add_lng'];

        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $this->display();
    }
    public  function edit(){
    	$uid = session('uid');

       	$lat = $_COOKIE['web_add_lat'];
    	$lng = $_COOKIE['web_add_lng'];

        $this->assign('lat',$lat);
        $this->assign('lng',$lng);


		$province = $_POST['province'];
		$city = $_POST['city'];
		$areas = $_POST['areas'];

		$isdefault = $_POST['isdefault'];
    	$id = I('get.id',0);
    	$this->assign("getid",$id);
        $orderid = I('get.addressid');
        $fapiaoid = I('get.fapiaoid');
        $this->assign('orderid',$orderid);
        $this->assign('fapiaoid',$fapiaoid);
		if(!empty($orderid)){
			$orderid = 1;
		}
		else {
			$orderid = 0;
            if(!empty($fapiaoid)){
                $orderid = 2;
            }else{
                $orderid = 0;
            }
		}
        $this->assign("addurl",$orderid);
    	if ($_POST) {
    		if(in_array($province, array('2,3,4,5'))){
				if(empty($city)){
					$this->error("请选择区域");
				}
			}else{
				if(empty($areas)){
					$this->error("请选择区域");
				}
			}
    		if (!empty($_POST['getid'])){
    			if($isdefault == "1")
                {
                	$id = M('address')->where(array('uid'=>$uid))->save(array(
	                      'isdefault'  => '0'
                	));
                }
    			if (D("address")->create()) {
    				D("address")->id = $_POST['getid'];
    				if (empty($areas)){
	                	D("address")->area = $province.','.$city;
	                }
	                else {
	                	D("address")->area = $province.','.$city.','.$areas;
	                } 
	                D("address")->updatetime = time();
	                D("address")->lat = $lat;
	                D("address")->lng = $lng;
	                $id = D("address")->save();
	                
	                if (!empty($id)) {
                        $addressid = I('get.addressid');
                        $fapiaoid = I('get.fapiaoid');
                        if(empty($addressid)){
                            if(empty($fapiaoid)){
                                $this->redirect("Web/Address/index");   
                            }else{
                                $this->redirect("Web/Address/index",array("fapiaoid"=>$fapiaoid));
                            }
                        }
                        else
                        {
                            $this->redirect("Web/Address/index",array("addressid"=>$addressid));
                        }
	                    
	                } else {
	                }
	            } else {
	                $this->error();
	            }
    		}
    		else{
    			if (!empty($_REQUEST['id'])){
    				$addresscount = M('address')->where(array('uid'=>$uid))->count();
    				
	        		if ($addresscount > 0)
	    			{
		    			if($_REQUEST['isdefault'] == "1")
		                {
		                	
		                	$id = M('address')->where(array('uid'=>$uid))->save(array(
			                      'isdefault'  => '0'
		                	));
		                }
	    			}

	        		$int = M('address')->where(array('id'=>$_REQUEST['id']))->save(array(
	                      'isdefault'  => $_REQUEST['isdefault'],
	                      'updatetime' => time()
	                ));
	                 
	        		if (!empty($int)) {
	                   echo "11";
	                 exit();
	                } else {
	                }
    			}
    			else {
	    			//在add的时候判定下他有没有收货地址 如果没有的话直接将第一个加上去的地址设置为默认收货地址
	    			$addresscount = M('address')->where(array('uid'=>$uid))->count();
	    			if ($addresscount > 0)
	    			{
		    			if($isdefault == "1")
		                {
		                	$id = M('address')->where(array('uid'=>$uid))->save(array(
			                      'isdefault'  => '0'
		                	));
		                }
	    			}
		            if (D("address")->create()) {
		            	if ($addresscount == 0) {
		            		D("address")->isdefault = "1";
		            	}
		                D("address")->uid = $uid;
		                if (empty($areas)){
		                	D("address")->area = $province.','.$city;
		                }
		                else {
		                	D("address")->area = $province.','.$city.','.$areas;
		                } 
		                D("address")->inputtime = time();
		                

		                $id = D("address")->add();
		                if (!empty($id)) {
		                    $addressid = I('get.addressid');
                            $fapiaoid = I('get.fapiaoid');
                            if(empty($addressid)){
                                if(empty($fapiaoid)){
                                    $this->redirect("Web/Address/index");   
                                }else{
                                    $this->redirect("Web/Address/index",array("fapiaoid"=>$fapiaoid));
                                }
                            }
                            else
                            {
                                $this->redirect("Web/Address/index",array("addressid"=>$addressid));
                            }
		                } else {
		                    $this->error("修改产品失败！");
		                }
		            } else {
		                $this->error();
		            }
    			}
    			
    		}
        } else {
        	if (!empty($id)){
	        	$data=D("address")->where("id=".$id)->find();
	        	$area = explode(',',$data['area']);
	        	$data['province'] = D("area")->where("id=".$area[0])->getField('name');
	        	$data['provinceid'] = $area[0];
	        	$data['city'] = D("area")->where("id=".$area[1])->getField('name');
	        	$data['cityid'] = $area[1];
	        	if (count($area)>2){
	        		$data['areas'] = D("area")->where("id=".$area[2])->getField('name');
	        		$data['areasid'] = $area[2];
	        	}
	            $this->assign("data", $data);
        	}
        	else {
        		$addresscount = M('address')->where(array('uid'=>$uid))->count();
        		if ($addresscount > 0)
    			{
	    			if(I('get.isdefault') == "1")
	                {
	                	$id = M('address')->where(array('uid'=>$uid))->save(array(
		                      'isdefault'  => '0'
	                	));
	                }
    			}
    			echo I('get.isdefault');

        	}
        }
        $Data = D("area")->where(array('parentid' => 0,'status'=>1))->select();
		$this->assign("provincelist",$Data);
        $this->display();
        
    }
    
    /*
     **删除地址
     */
	public function address_del(){
		$uid = session('uid');
		$addressid=intval(trim($_REQUEST['addressid']));
        
		$where['id']=$uid;
		$user=M('Member')->where($where)->find();
		$address=M('address')->where(array('id'=>$addressid))->find();
		if($uid==''||$addressid==''){
			exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
		}elseif(!$user){
			exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
		}elseif(!$address){
			exit(json_encode(array('code'=>-200,'msg'=>"The Address is not exist!")));
		}else{
			$select['uid']=$user['id'];
			$select['id']=$addressid;
			$id=M('address')->where($select)->delete();
			if($id){
				exit(json_encode(array('code'=>200,'msg'=>"删除成功")));     
			}else{
				exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
			}
		}
	}
    
	public function getchildren() {
        $parentid = $_REQUEST['id'];
        $result = M("area")->where(array('parentid' => $parentid,'status'=>1))->select();
        $result = json_encode($result);
        echo $result;
    }
    
	public function getadd(){
        $lat = $_REQUEST['lat'];
        $lng = $_REQUEST['lng'];
        cookie('add_lat',$lat);
        cookie('add_lng',$lng);
        $result = array('lat'=>$lat,'lng'=>$lng);
        $Map=A("Api/Map");
        $data=$Map->get_addressinfo_pois($lat.",".$lng);
        $this->assign("data",$data);
        
        $datas=$this->fetch("morelist_index");

        $this->ajaxReturn($datas);
        //$this->display();
    }

    public function getqueryinfo(){
        $keyword = $_REQUEST['keyword'];
        $lat = cookie('add_lat');
       	$lng =  cookie('add_lng');
        $Map=A("Api/Map");
        $data=$Map->get_queryinfo($keyword,$lat.",".$lng);
        $this->assign("data",$data);
        
        $datas=$this->fetch("morelist_index1");

        $this->ajaxReturn($datas);
        //$this->display();
    }
    
    
}
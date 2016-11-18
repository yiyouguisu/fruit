<?php

namespace Web\Controller;

use Web\Common\CommonController;

class FocusController extends CommonController {
	
	public  function index(){
		$uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $data=M('collect a')
                    ->join("left join zz_product b on a.value=b.id")
                    ->where(array('a.uid'=>$uid,'a.varname'=>'product','b.isdel'=>0))
                    ->field('a.value as pid,b.title,b.thumb,b.description,b.nowprice,b.oldprice,b.unit,b.ishot,b.type,b.expiretime,b.selltime,b.standard,b.unit')
                    ->select();
            foreach ($data as $key => $value)
            {
                $data[$key]['unit']=getunit($value['unit']);
            }
    		$this->assign("datalist",$data);
    		$this->display();
        }
	}
	
    public  function  add(){
    	$uid = session('uid');
    	$pid = $_REQUEST['pid'];
    	if (empty($uid)) $this->error('请登录后再关注');
    	$id = M('attention')->add(array(
                      'uid'        => $uid,
                      'pid'        => $pid,
                      'inputtime'  => time()
                ));
    	if($id){
            echo json_encode($id);
        }
        else{
            $this->error('关注失败');
        }
    }

	public  function  del(){
    	$uid = session('uid');
    	$pid = $_REQUEST['pid'];
    	if (empty($uid)) $this->error('请登录后再关注');
    	$id = M('attention')->where(array('uid'=>$uid,'pid'=>$pid))->delete();
    	if($id){
            echo json_encode($id);
        }
        else{
            $this->error('关注失败');
        }
    }
}
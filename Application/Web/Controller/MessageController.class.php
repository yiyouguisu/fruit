<?php

namespace Web\Controller;

use Web\Common\CommonController;

class MessageController extends CommonController {
    
    public function index(){
        $uid = session('uid');
        $data = M('message')->where(array('tuid'=>$uid,'varname'=>'system','isdel'=>'0'))->order(array('inputtime'=>"desc"))->select();
        $this->assign('words',$data[0]);
        $datacount = M('message')->where(array('tuid'=>$uid,'varname'=>'system','isdel'=>'0','status'=>'0'))->order(array('inputtime'=>"desc"))->select();
        $this->assign('wordcount',count($datacount));

        $data = M("activity")->where(array('status'=>1))->order(array('inputtime'=>"desc"))->select();
        $this->assign('imgview',$data[0]);
        //$this->assign('wordcount',count($data));

        $this->display();
    }
    
    public function wordsview(){
        $uid = session('uid');
        $data=M("message")->where(array('tuid'=>$uid,'varname'=>'system','isdel'=>'0'))->order(array('inputtime'=>"desc"))->select();
        if($data){
            foreach ($data as $key=>$value)
            {
                //dump()
                $data[$key]['startDate'] = date('Y-m-d H:i:s',$data[$key]['inputtime']);
            }
            //dump($data);
            $this->assign('list',$data);
        }else{
            
        }  
        $this->display();
    }

    public function items(){
        $id = I('get.id');
        $data = M('message')->where('id='.$id)->find();
        $data['startDate'] = date('Y-m-d H:i:s',$data['inputtime']);
        $this->assign('data',$data);
        //标记已读
        $id = M("message")->where('id='.$id)->save(array('status'=>1)); 
        $this->display();
    }

    public function imageview(){
        $uid = session('uid');
        if (!$uid) {
            $this->error('请先登录！',U('Web/Member/login'));
        } else {
            $data=M('message')->where(array('tuid'=>$uid,'varname'=>array('in','hot,hotproduct'),'isdel'=>0))->order(array('id'=>"desc"))->field('id,title,value,varname,description,content,inputtime,status')->page($p,$num)->select();           
            foreach ($data as $key => $value)
            {
                if($value['varname']=='hot'){
                    $data[$key]['thumb']=M('activity')->where(array('id'=>$value['value']))->getField("thumb");
                }elseif($value['varname']=='hotproduct'){
                    $data[$key]['thumb']=M('product')->where(array('id'=>$value['value']))->getField("thumb");
                }
                $data[$key]['startDate'] = date('Y-m-d H:i:s',$data[$key]['inputtime']);
                
            }
            $this->assign('list',$data);
            $this->display();
        }
    }


    /*
     **系统通知消息  清空消息
     */
    public function delall(){
        $uid=intval(trim(session('uid')));
        $type=intval(trim("2"));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            if($type==1){
                $id=M("message")->where(array('uid'=>array('eq',$uid)))->save(array('isdel'=>1,'deletetime'=>time())); 
            }elseif($type==2){
                $id=M("message")->where(array('tuid'=>array('eq',$uid)))->save(array('isdel'=>1,'deletetime'=>time()));
            }
            if($id){
                $this->ajaxReturn('success');
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"清空消息失败")));
            }  
        }
    }
}
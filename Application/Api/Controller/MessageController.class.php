<?php

namespace Api\Controller;

use Api\Common\CommonController;

class MessageController extends CommonController {
	/*
    **系统通知消息
    */
    public function message(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M("message")->where(array('tuid'=>$uid,'varname'=>'system','isdel'=>'0'))->order(array('id'=>"desc"))->field("id,title,description,content,status,inputtime")->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"无系统消息")));
            }  
        }
    }
    public function show(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $mid=intval(trim($ret['mid']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$mid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M("message")->where(array('id'=>$mid))->field("id,title,description,content,status,inputtime")->find();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"数据为空")));
            }  
        }
    }
    /*
    **系统通知消息  更改已读未读状态
    */
    public function update_messages_status(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $mid=trim($ret['mid']);
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$mid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $mids=explode(",", $mid);
            if(is_array($mids)){
                $id=M("message")->where(array('id'=>array('in',$mid)))->setField("status",1);
            }else{
                $id=M("message")->where(array('id'=>array('eq',$mid)))->setField("status",1);
            }
            if($id){
                exit(json_encode(array('code'=>200,'msg'=>"更新成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"更新失败")));
            }  
        }
    }
    /*
    **系统通知消息  删除消息
    */
    public function delete_messages(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $mid=trim($ret['mid']);
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$mid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $mids=explode(",", $mid);
            if(is_array($mids)){
                $id=M("message")->where(array('id'=>array('in',$mid)))->save(array('isdel'=>1,'deletetime'=>time()));
            }else{
                $id=M("message")->where(array('id'=>array('eq',$mid)))->save(array('isdel'=>1,'deletetime'=>time()));
            }
            if($id){
                exit(json_encode(array('code'=>200,'msg'=>"删除成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"删除失败")));
            }  
        }
    }
    /*
     **配送端系统通知消息  清空消息
     */
    public function delall(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $type=intval(trim($ret['type']));

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
                exit(json_encode(array('code'=>200,'msg'=>"清空消息成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"清空消息失败")));
            }  
        }
    }
     /*
     **用户端系统通知消息  清空消息
     */
    public function delall_member(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $type=intval(trim($ret['type']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$type==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            if($type==1){
                $id=M("message")->where(array('tuid'=>$uid,'varname'=>'system'))->save(array('isdel'=>1,'deletetime'=>time())); 
            }elseif($type==2){
                $id=M("message")->where(array('tuid'=>$uid,'varname'=>array('in','hot,hotproduct')))->save(array('isdel'=>1,'deletetime'=>time()));
            }
            if($id){
                exit(json_encode(array('code'=>200,'msg'=>"清空消息成功")));
            }else{
                exit(json_encode(array('code'=>-202,'msg'=>"清空消息失败")));
            }  
        }
    }

    /*
    **配送员通知消息  收到
    */
    public function runer_message_in(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M("message")->where(array('tuid'=>array('eq',$uid),'varname'=>'runner','isdel'=>'0'))->order(array('id'=>"desc"))->field("id,title,description,content,status,inputtime")->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"无系统消息")));
            }  
        }
    }

    /*
    **配送员通知消息  发出
    */
    public function runer_message_out(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $p=intval(trim($ret['p']));
        $num=intval(trim($ret['num']));
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();
        
        if($uid==''||$p==''||$num==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $data=M("message")->where(array('uid'=>$uid,'varname'=>'runner','isdel'=>'0'))->order(array('id'=>"desc"))->field("id,title,description,content,status,inputtime")->page($p,$num)->select();
            if($data){
                exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
            }else{
                exit(json_encode(array('code'=>-201,'msg'=>"无系统消息")));
            }  
        }
    }

    /*
    **发送消息
    */
    public function messageadd(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));
        $content=trim($ret['content']);

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();

        if($uid==''||$content==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $uids=M('Member')->where(array('isleader'=>0,'leader'=>$uid,'group_id'=>2,'status'=>1))->field('id')->select();
            if(empty($uids)){
                exit(json_encode(array('code'=>-200,'msg'=>"该配送组长没有组员!")));
            }
            foreach($uids as $value){
                $ids[]=M("message")->add(array(
                        'uid'=>$uid,
                        'tuid'=>$value['id'],
                        'title'=>'组长'.$user['realname'].'发布通知',
                        'description'=>$content,
                        'content'=>$content,
                        'varname'=>"runner",
                        'inputtime'=>time()
                    ));
            }
            
            if($ids){
                exit(json_encode(array('code'=>200,'desc'=>"发送成功")));
            }else{
                exit(json_encode(array('code'=>-202,'desc'=>"发送失败")));
            }    
        }
    }
    public function getmessgenum(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();

        if($uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $messagenum=M("message")->where(array('tuid'=>$uid,'varname'=>'system','isdel'=>'0','status'=>0))->count();
            $partynum=M("message")->where(array('tuid'=>$uid,'varname'=>array('in','hot,hotproduct'),'isdel'=>'0','status'=>0))->count();
            $data=array(
                'message'=>!empty($messagenum)?$messagenum:0,
                'hot'=>!empty($partynum)?$partynum:0,
                );
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }
    }
    public function getmessgenum_runer(){
        $ret=$GLOBALS['HTTP_RAW_POST_DATA']; 
        $ret=json_decode($ret,true);
        $uid=intval(trim($ret['uid']));

        $where['id']=$uid;
        $user=M('Member')->where($where)->find();

        if($uid==''){
            exit(json_encode(array('code'=>-200,'msg'=>"Request parameter is null!")));
        }elseif(empty($user)){
            exit(json_encode(array('code'=>-200,'msg'=>"The User is not exist!")));
        }else{
            $messagenum=M("message")->where(array('tuid'=>$uid,'varname'=>'runner','isdel'=>'0','status'=>0))->count();
            $data=array(
                'message'=>!empty($messagenum)?$messagenum:0
                );
            exit(json_encode(array('code'=>200,'msg'=>"Load success!",'data' => $data)));
        }
    }
}

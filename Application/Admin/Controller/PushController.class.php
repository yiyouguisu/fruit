<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class PushController extends CommonController {
    public function _initialize() {     
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $search = I('post.search');
        $where = array();
        $where['isadmin']=1;
        $where['username']=array('eq',$_SESSION['user']);
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
            //状态
            $status = $_POST["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            //搜索关键字
            $keyword = I('post.keyword');
            if(!empty($keyword)){
                $where["title"] = array("like", "%{$keyword}%");
            }
        }
        $count = M("push")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("push")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->display();
    }


    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("Push")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
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
                M("Push")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    
    public function add() {
        if(IS_POST){
            if(!empty($_POST['pnumber'])){
                $pid=$_POST['pnumber'];
            }else{
                $pid=$_POST['pid'];
            }
            if (D("Push")->create()) {
                D("Push")->isadmin = 1;
                D("Push")->pid = $pid;
                D("Push")->inputtime = time();
                D("Push")->preference=implode(",", $_POST['preference']);
                D("Push")->username = $_SESSION['user'];
                $mid = D("Push")->add();
                if ($mid) {
                    $uids=array();
                    $message_type='';
                    if($_POST['scale']==1){
                        $uids=M('member')->where(array('status'=>1,'group_id'=>1))->getField("id",true);
                    }elseif($_POST['scale']==2){
                        $preference=$_POST['preference'];
                        foreach ($preference as $value) {
                            # code...
                            $uidss=M('member')->where(array('status'=>1,'group_id'=>1,'preference'=>array('like',"%".$value."%")))->getField("id",true);
                            $uids=array_merge($uidss,$uids);
                        }
                        $uids=array_unique($uids);
                    }elseif($_POST['scale']==3){
                        $uids=M('member')->where(array('username|phone'=>$_POST['name']))->getField("id",true);
                    }elseif($_POST['scale']==4){
                        $uids=getuid_level($_POST['level']);
                    }
                    if($_POST['type']==1){
                        $message_type="product";
                        $value=$pid;
                    }elseif($_POST['type']==2){
                        $message_type="imagetext";
                        $value=C("WEB_URL")."/index.php/Web/Index/pushview/id/".$mid.".html";
                    }
                    $num=count($uids);
                    $i=0;
                    $j=1;
                    do
                    {
                        $registration_id=M('member')->where(array('id'=>array('in',$uids)))->page($j,1000)->getField("deviceToken",true);
                        $receiver = implode(",", array_filter($registration_id));//接收者
                        $extras = array("mid"=>$value,'message_type'=>$message_type);
                        if(!empty($receiver)){
                            PushQueue($mid,$message_type,$receiver, $_POST['title'], $_POST['description'], serialize($extras),1);
                        }
                        $j++;
                        $i=$i+1000;
                    }while ($i<=$num);
                    if($_POST['type']==1){
                        $uids=M('member')->where(array('group_id'=>1))->getField("id",true);
                        foreach ($uids as $uid) {
                            # code...
                            M("message")->add(array(
                                'uid'=>0,
                                'tuid'=>$uid,
                                'title'=>$_POST['title'],
                                'description'=>$_POST['description'],
                                'content'=>$_POST['description'],
                                'value'=>$pid,
                                'varname'=>"hotproduct",
                                'inputtime'=>time()
                            ));
                        }
                    }
                    $this->success("新增推送消息成功！", U("Admin/Push/index"));
                } else {
                    $this->error("新增推送消息失败！");
                }
            } else {
                $this->error(D("Push")->getError());
            }
        }else{
            $shop=M('store')->where(array('status'=>2))->order(array('listorder'=>'desc','id'=>'desc'))->select();
            $this->assign("shop",$shop);
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store",$store);
            $levelConfig = F("levelConfig",'',CACHEDATA_PATH);
            $this->assign("levelConfig", $levelConfig);
            $this->display();
        }
    }
    public function pushagain() {
        $id=I('get.id',0,intval);
        if(empty($id)||$id==0){
            $this->error("参数错误");
        }
        $data=M('push')->where(array('id'=>$id))->find();
        $data['status']=1;
        unset($data['id']);
        unset($data['inputtime']);
        $data['inputtime']=time();
        $mid = M("Push")->add($data);
        if ($mid) {
            $uids=array();
            if($data['type']==1){
                $uidset=M('member')->where(array('group_id'=>1))->getField("id",true);
                foreach ($uidset as $uid) {
                    # code...
                    M("message")->add(array(
                        'uid'=>0,
                        'tuid'=>$uid,
                        'title'=>$data['title'],
                        'description'=>$data['description'],
                        'content'=>$data['description'],
                        'value'=>$data['pid'],
                        'varname'=>"hotproduct",
                        'inputtime'=>time()
                    ));
                }
            }
            $pushinfo=M('sendpush_queue')->where(array('mid'=>$id))->find();
            if(!empty($data['scale'])){
                if($data['scale']==1){
                    $uids=M('member')->where(array('status'=>1,'group_id'=>1))->getField("id",true);
                }elseif($data['scale']==2){
                    $preference=$data['preference'];
                    foreach ($preference as $value) {
                        # code...
                        $uidss=M('member')->where(array('status'=>1,'group_id'=>1,'preference'=>array('like',"%".$value."%")))->getField("id",true);
                        $uids=array_merge($uidss,$uids);
                    }
                    $uids=array_unique($uids);
                }elseif($data['scale']==3){
                    $uids=M('member')->where(array('username|phone'=>$data['name']))->getField("id",true);
                }elseif($data['scale']==4){
                    $uids=getuid_level($data['level']);
                }
                $num=count($uids);
                $i=0;
                $j=1;
                do
                {
                    $registration_id=M('member')->where(array('id'=>array('in',$uids)))->page($j,1000)->getField("deviceToken",true);
                    $receiver = implode(",", array_filter($registration_id));//接收者
                    if(!empty($receiver)){
                        PushQueue($mid,$pushinfo['varname'],$receiver, $pushinfo['title'],$pushinfo['description'], $pushinfo['extras'],$pushinfo['type']);
                    }
                    $j++;
                    $i=$i+1000;
                }while ($i<=$num);
            }else{
                PushQueue($mid,$pushinfo['varname'],$pushinfo['receiver'], $pushinfo['title'], $pushinfo['description'], $pushinfo['extras'], $pushinfo['type']);
            }

            
            
            

            $this->success("再次推送消息成功！", U("Admin/Push/index"));
        } else {
            $this->error("再次推送消息失败！");
        }
    }
    public function store() {
        $search = I('post.search');
        $where = array();
        $where['isadmin']=1;
        $where['username']=array('eq',$_SESSION['user']);
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
            //状态
            $status = $_POST["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            //搜索关键字
            $keyword = I('post.keyword');
            if(!empty($keyword)){
                $where["title"] = array("like", "%{$keyword}%");
            }
        }
        $count = M("push")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("push")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->display();
    }


    /**
     *  删除
     */
    public function sdelete() {
        $id = $_GET['id'];
        if (D("Push")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    /*
     * 删除内容
     */

    public function sdel() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Push")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    
    public function sadd() {
        if(IS_POST){
            if (D("Push")->create()) {
                D("Push")->isadmin = 1;
                D("Push")->inputtime = time();
                D("Push")->username = $_SESSION['user'];
                $mid = D("Push")->add();
                if ($mid) {
                    $uids=array();
                    $message_type='';
                    $uids=M('store_member')->where(array('storeid'=>$this->storeid))->getField("ruid",true);
                    $message_type="imagetext";

                    $num=count($uids);
                    $i=0;
                    $j=1;
                    do
                    {
                        $registration_id=M('member')->where(array('id'=>array('in',$uids)))->page($j,1000)->getField("deviceToken",true);
                        $receiver = implode(",", array_filter($registration_id));//接收者
                        $extras = array("mid"=>C("WEB_URL")."/index.php/Web/Index/pushview/id/".$mid.".html",'message_type'=>$message_type);
                        if(!empty($receiver)){
                            PushQueue($mid,$message_type,$receiver, $_POST['title'], $_POST['description'], serialize($extras),2);
                        }
                        $j++;
                        $i=$i+1000;
                    }while ($i<=$num);

                    $this->success("新增推送消息成功！", U("Admin/Push/store"));
                } else {
                    $this->error("新增推送消息失败！");
                }
            } else {
                $this->error(D("Push")->getError());
            }
        }else{
            $shop=M('store')->where(array('status'=>2))->order(array('listorder'=>'desc','id'=>'desc'))->select();
            $this->assign("shop",$shop);
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store",$store);
            $this->display();
        }
    }
    public function pushsagain() {
        $id=I('get.id',0,intval);
        if(empty($id)||$id==0){
            $this->error("参数错误");
        }
        $data=M('push')->where(array('id'=>$id))->find();
        $data['status']=1;
        unset($data['id']);
        unset($data['inputtime']);
        $data['inputtime']=time();
        $mid = M("Push")->add($data);
        if ($mid) {
            $pushinfo=M('sendpush_queue')->where(array('mid'=>$id))->find();

            $uids=M('store_member')->where(array('storeid'=>$this->storeid))->getField("ruid",true);
            $num=count($uids);
            $i=0;
            $j=1;
            do
            {
                $registration_id=M('member')->where(array('id'=>array('in',$uids)))->page($j,1000)->getField("deviceToken",true);
                $receiver = implode(",", array_filter($registration_id));//接收者
                if(!empty($receiver)){
                    PushQueue($mid,$pushinfo['varname'],$receiver, $pushinfo['title'],$pushinfo['description'], $pushinfo['extras'],$pushinfo['type']);
                }
                $j++;
                $i=$i+1000;
            }while ($i<=$num);
            $this->success("再次推送消息成功！", U("Admin/Push/store"));
        } else {
            $this->error("再次推送消息失败！");
        }
    }
}
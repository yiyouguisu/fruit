<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class MessageController extends CommonController {

     public function index() {
        $search = I('post.search');
        $where = array();
        $where['varname']=array('in','system,hot,hotproduct');
        $uids=M('Member')->where(array('group_id'=>1))->getField("id",true);
        $where['tuid']=array('in',$uids);
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
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }elseif ($searchtype == 1) {
                    $select["username"] = array("LIKE", "%" . $keyword . "%");
                    $uids=M('member')->where($select)->getField("id",true);
                    $where['uid']=array('in',$uids);
                }elseif($searchtype == 0){
                    $where["content"] = array("like", "%{$keyword}%");
                }
            }
        }
        $count = M("message")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("message")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->cache(true)->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->assign("searchtype", $searchtype);

        $this->display();
    }


    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("message")->delete($id)) {
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
                M("message")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    /*
     * 删除短信记录
     */

    public function deletesms() {
        $id = $_GET['id'];
        if (D("sms")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 删除内容
     */

    public function smsdel() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("sms")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function sms() {
        $search = I('post.search');
        $where = array();
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
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }elseif($searchtype == 0){
                    $where["content"] = array("like", "%{$keyword}%");
                }elseif($searchtype == 1){
                    $where["phone"] = array("eq", $keyword);
                }
            }
            
        }
        $count = M("sms")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("sms")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function send() {
        if(IS_POST){
            $search = I('post.search');
            $where = array();
            if (!empty($search)) {
                 //添加开始时间
                $start_time = I('post.start_time');
                if (!empty($start_time)) {
                    $start_time = strtotime($start_time);
                    $where["reg_time"] = array("EGT", $start_time);
                }
                //添加结束时间
                $end_time = I('post.end_time');
                if (!empty($end_time)) {
                    $end_time = strtotime($end_time);
                    $where["reg_time"] = array("ELT", $end_time);
                }
                if ($end_time > 0 && $start_time > 0) {
                    $where['reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
                }
                //栏目
                $catid = I('post.catid', null, 'intval');
                $catid1 = I('post.catid1', null, 'intval');
                if (!empty($catid)&&!empty($catid1)) {
                    $keywords=M('interest')->select();
                    foreach ($keywords as $key=>$val) {
                        # code...
                        $catids=explode(",", $val['catid']);
                        if (in_array($catid, $catids)&&in_array($catid1, $catids)) {
                            $ids[]=$val['uid'];
                        }
                    }
                    $where['id']=array('in',$ids);

                }else if(!empty($catid)){
                    $keywords=M('interest')->select();
                    foreach ($keywords as $key=>$val) {
                        # code...
                        $catids=explode(",", $val['catid']);
                        if (in_array($catid, $catids)) {
                            $ids[]=$val['uid'];
                        }
                    }
                    $where['id']=array('in',$ids);
                }else if(!empty($catid1)){
                    $keywords=M('interest')->select();
                    foreach ($keywords as $key=>$val) {
                        # code...
                        $catids=explode(",", $val['catid']);
                        if (in_array($catid1, $catids)) {
                            $ids[]=$val['uid'];
                        }
                    }
                    $where['id']=array('in',$ids);
                }
              //性别
                $sex= I('post.sex', null, 'intval');
                if (!empty($sex)) {
                    $where["sex"] = array("EQ", $sex);
                }
                //搜索字段
                $searchtype = I('post.searchtype', null, 'intval');
                //搜索关键字
                $keyword = I('post.keyword');
                if (!empty($keyword)) {
                    $type_array = array('nickname','realname','invitation','phone');
                    if ($searchtype < 4) {
                        $searchtype = $type_array[$searchtype];
                        $where[$searchtype] = array("LIKE", "%{$keyword}%");
                    } elseif ($searchtype == 4) {
                        $where["id"] = array("EQ", (int)$keyword);
                    }
                }
            }
            $data = M("member")->where($where)->field('id,nickname')->select();
            $content=$_POST['content'];
            if(empty($content)){
                $this->error("发送内容不能为空！",U('Admin/Message/send'));
            }
            foreach ($data as $key => $value) {
                # code...
                $log['uid']=$value['id'];
                $log['content']=str_replace("{#nickname#}", $value['nickname'], $content);
                $log['inputtime']=time();
                $id[]=M("message")->add($log);
            }
            if(!empty($id)){
                $this->success("发送成功！");
            }else{
                $this->error("发送失败！");
            }
            
        }else{
            $category=M('category')->where('parentid=37')->select();
            $this->assign("category", $category);
            $category1=M('category')->where('parentid=38')->select();
            $this->assign("category1", $category1);
            $this->display();
        }
    }
    public function smstemplete() {
        $this->display();
    }
}
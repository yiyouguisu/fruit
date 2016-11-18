<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class PartyController extends CommonController {

    public function index() {
        $search = I('get.search');
        $where = array();
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            //栏目
            $catid = I('get.catid', null, 'intval');
            if (!empty($catid)) {
                $where["catid"] = array("EQ", $catid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'description', 'username');
                if ($searchtype < 3) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = D("Activity")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Activity")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }

    /**
     * 编辑活动
     */
    public function edit() {
        if ($_POST) {
            if (D("Activity")->create()) {
                D("Activity")->updatetime = time();
                $id = D("Activity")->save();
                if (!empty($id)) {
                    $this->success("修改活动成功！", U("Admin/Party/index"));
                } else {
                    $this->error("修改活动失败！");
                }
            } else {
                $this->error(D("Activity")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("活动ID参数错误");
            }
            $data=D("Activity")->where("id=".$id)->find();
            $this->assign("data", $data);
            $shop=M('store')->where(array('status'=>2))->order(array('listorder'=>'desc','id'=>'desc'))->select();
            $this->assign("shop",$shop);
            $this->display();
        }
        
    }

    /*
     * 添加活动
     */

    public function add() {
        if ($_POST) {
            if (D("Activity")->create()) {
                D("Activity")->inputtime = time();
                D("Activity")->username = $_SESSION['user'];
                $id = D("Activity")->add();
                if (!empty($id)) {
                    $uids=M('member')->where(array('group_id'=>1))->getField("id",true);
                    foreach ($uids as $uid) {
                        # code...
                        M("message")->add(array(
                            'uid'=>0,
                            'tuid'=>$uid,
                            'title'=>$_POST['title'],
                            'description'=>$_POST['description'],
                            'content'=>$_POST['content'],
                            'value'=>$_POST['pid'],
                            'varname'=>"hot",
                            'inputtime'=>time()
                        ));
                    }
                    
                    $this->success("新增活动成功！", U("Admin/Party/index"));
                } else {
                    $this->error("新增活动失败！");
                }
            } else {
                $this->error(D("Activity")->getError());
            }
        } else {
            $shop=M('store')->where(array('status'=>2))->order(array('listorder'=>'desc','id'=>'desc'))->select();
            $this->assign("shop",$shop);
            $this->display();
        }
    }

    /*
     * 操作判断
     */

    public function action() {
        $submit = trim($_POST["submit"]);
        if ($submit == "listorder") {
            $this->listorder();
        } elseif ($submit == "del") {
            $this->del();
        } elseif ($submit == "review") {
            $this->review();
        } elseif ($submit == "unreview") {
            $this->unreview();
        } elseif ($submit == "pushs") {
            $this->pushs();
        } elseif ($submit == "unpushs") {
            $this->unpushs();
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("Activity")->delete($id)) {
            $this->success("删除活动成功！");
        } else {
            $this->error("删除活动失败！");
        }
    }

    /*
     * 删除活动
     */

    public function del() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Activity")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 活动审核
     */

    public function review() {
        $data['status'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Activity")->where(array("id" => $id))->save($data);
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
    }

    /*
     * 活动取消审核
     */

    public function unreview() {
        $data['status'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Activity")->where(array("id" => $id))->save($data);
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
    }

    /*
     * 活动推荐
     */

    public function pushs() {
        $data['type'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Activity")->where(array("id" => $id))->save($data);
            }
            $this->success("推荐成功！");
        } else {
            $this->error("推荐成功！");
        }
    }

    /*
     * 活动推荐
     */

    public function unpushs() {
        $data['type'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Activity")->where(array("id" => $id))->save($data);
            }
            $this->success("取消推荐成功！");
        } else {
            $this->error("取消推荐失败！");
        }
    }

    /*
     * 活动排序
     */

    public function listorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Activity")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Activity")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    public function ajax_getproduct(){
        $data=M('product')->where(array('storeid'=>$_POST['storeid']))->order(array('listorder'=>'desc','id'=>'desc'))->select();
        echo json_encode($data);
    }

}
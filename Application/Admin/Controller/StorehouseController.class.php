<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class StorehouseController extends CommonController {
    public function _initialize() {
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        if (!empty($search)) {
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            $where['title'] = array("LIKE", "%{$keyword}%");
        }
      
        $count = D("Storehouse")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Storehouse")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("listorder" => "desc","id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("storeid", $this->storeid);
        $this->display();
    }

    /**
     * 编辑内容
     */
    public function edit() {
        if ($_POST) {
            if (D("Storehouse")->create()) {
                D("Storehouse")->updatetime = time();
                $id = D("Storehouse")->save();
                if (!empty($id)) {
                    $this->success("修改仓库成功！", U("Admin/Storehouse/index"));
                } else {
                    $this->error("修改仓库失败！");
                }
            } else {
                $this->error(D("Storehouse")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
         	if (empty($id)) {
                $this->error("ID参数错误");
          	}
       		$data=D("Storehouse")->where("id=".$id)->find();
        	$this->assign("data", $data);
        	$this->display();
        }
        
    }

    /*
     * 添加内容
     */

    public function add() {
        if ($_POST) {
            if (D("Storehouse")->create()) {
                D("Storehouse")->storeid=$this->storeid;
                D("Storehouse")->ip=get_client_ip();
                D("Storehouse")->inputtime = time();
                $id = D("Storehouse")->add();
                if (!empty($id)) {
                    $url='http://' . $_SERVER['HTTP_HOST'] . U('Admin/Public/storehouse',array('sid'=>$id));
                    M('Storehouse')->where(array('id'=>$id))->setField("url",$url);
                    $this->success("新增仓库成功！", U("Admin/Storehouse/index"));
                } else {
                    $this->error("新增仓库失败！");
                }
            } else {
                $this->error(D("Storehouse")->getError());
            }
        } else {
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
        } 
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("Storehouse")->delete($id)) {
            $this->success("删除仓库成功！");
        } else {
            $this->error("删除仓库失败！");
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
                M("Storehouse")->delete($id);
            }
            $this->success("删除仓库成功！");
        } else {
            $this->error("删除仓库失败！");
        }
    }

    /*
     * 内容审核
     */

    public function review() {
        $data['status'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Storehouse")->where(array("id" => $id))->save($data);
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
    }

    /*
     * 内容取消审核
     */

    public function unreview() {
        $data['status'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Storehouse")->where(array("id" => $id))->save($data);
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
    }

  
    /*
     * 内容排序
     */

    public function listorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Storehouse")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Storehouse")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    
}
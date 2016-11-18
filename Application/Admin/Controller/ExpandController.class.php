<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ExpandController extends CommonController {

    /*
     * 地区管理
     */

    public function area() {
        $parentid = I('get.parentid', 0, 'intval');
        $where["parentid"] = $parentid;
        $count = M("area")->cache(true)->where($where)->count();
        $page = new \Think\Page($count, 15);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("area")->cache(true)->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "asc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }

    /*
     * 地区添加
     */

    public function areaadd() {
        if (IS_POST) {
            $arrparentid = $_POST["arrparentid"];
            $parentid = end(explode(",", $arrparentid));
            if (D("area")->create()) {
                D("area")->arrparentid = "," . $arrparentid . ",";
                D("area")->parentid = !empty($parentid)?$parentid:0;
                $catid = D("area")->add();
                if ($catid) {
                    $this->success("新增地区成功！", U("Admin/Expand/area", array("parentid" => $parentid)));
                } else {
                    $this->error("新增地区失败！");
                }
            } else {
                $this->error(D("area")->getError());
            }
        } else {
            $parentid = $_GET['parentid'];
            $arrparentid = D("area")->where("id=" . $parentid)->getField("arrparentid");
            $this->assign("arrparentid", $arrparentid);
            $this->display();
        }
    }

    /*
     * 地区修改
     */

    public function areaedit() {
        if (IS_POST) {
            $arrparentid = $_POST["arrparentid"];
            $parentid = end(explode(",",$arrparentid));
            if (D("area")->create()) {
                D("area")->arrparentid = "," . $arrparentid . ",";
                D("area")->parentid = !empty($parentid)?$parentid:0;
                $catid = D("area")->save();
                if ($catid) {
                    $this->success("修改地区成功！", U("Admin/Expand/area", array("parentid" => $parentid)));
                } else {
                    $this->error("修改地区失败！");
                }
            } else {
                $this->error(D("area")->getError());
            }
        } else {
            $data = D("area")->where("id=" . $_GET["id"])->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    /**
     *  地区删除
     */
    public function areadel() {
        $id = $_GET['id'];
        if (D("area")->delete($id)) {
            $this->success("删除地区成功！");
        } else {
            $this->error("删除地区失败！");
        }
    }

    /**
     *  地区排序
     */
    public function arealistorder() {
        $pk = D("area")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("area")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
     /*
     * ajax获取子地区列表
     */

    function getareachildren() {
        $parentid = $_GET['id'];
        $result = M("area")->where(array("parentid" => $parentid))->cache(true)->select();
        $result = json_encode($result);
        echo $result;
    }
    /*
     * ajax获取子地区列表
     */

    function getchildren() {
        $parentid = $_GET['id'];
        $result = M("area")->where(array("parentid" => $parentid,'status'=>1))->cache(true)->select();
        $result = json_encode($result);
        echo $result;
    }

    /*
     * 联动菜单
     */

    public function index() {
        $catid = I('get.catid', null, 'intval');
        if (empty($catid)) {
            $count = D("linkagetype")->count();
            $page = new \Think\Page($count, 15);
            $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
            $page->setConfig("prev","上一页");
            $page->setConfig("next","下一页");
            $page->setConfig("first","第一页");
            $page->setConfig("last","最后一页");
            $data = D("linkagetype")->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
            $show = $page->show();
            $this->assign("data", $data);
            $this->assign("Page", $show);
            $this->display();
        } else {
            $count = D("linkage")->where("catid=".$catid)->count();
            $page = new \Think\Page($count, 15);
            $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
            $page->setConfig("prev","上一页");
            $page->setConfig("next","下一页");
            $page->setConfig("first","第一页");
            $page->setConfig("last","最后一页");
            $data = D("linkage")->where("catid=".$catid)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
            $show = $page->show();
            $this->assign("data", $data);
            $this->assign("Page", $show);
            $this->display(linkage);
        }
    }

    /*
     * 添加菜单
     */

    public function add() {
        if (IS_POST) {
              if($_POST["catid"]==""){
                  $this->error("请选择类型！");
              }
              if($_POST["name"]==""){
                  $this->error("请输入名称！");
              }
              if($_POST["value"]==""){
                  $this->error("请输入值！");
              }
            if (D("linkage")->create()) {
                $catid = D("linkage")->add();
                if ($catid) {
                    $this->success("新增菜单成功！", U("Admin/Expand/add", array("catid" => $_POST["catid"])));
                } else {
                    $this->error("新增菜单失败！");
                }
            } else {
                $this->error(D("linkage")->getError());
            }
        } else {
            $catid= $_GET['catid'];
            $cat = D("linkagetype")->select();
            $this->assign("cat", $cat);
            $this->assign("catid", $catid);
            $this->display();
        }
    }

    /*
     * 修改菜单
     */

    public function edit() {
         if (IS_POST) {
               if($_POST["catid"]==""){
                  $this->error("请选择类型！");
              }
              if($_POST["name"]==""){
                  $this->error("请输入名称！");
              }
              if($_POST["value"]==""){
                  $this->error("请输入值！");
              }
            if (D("linkage")->create()) {
                $catid = D("linkage")->save();
                if ($catid) {
                    $this->success("修改菜单成功！", U("Admin/Expand/index", array("catid" => $_POST["catid"])));
                } else {
                    $this->error("修改菜单失败！");
                }
            } else {
                $this->error(D("linkage")->getError());
            }
        } else {
            $id= $_GET['id'];
            $cat = D("linkagetype")->select();
            $data=M("linkage")->where("id=".$id)->find();
            $this->assign("cat", $cat);
            $this->assign("catid", $data["catid"]);
            $this->assign("data", $data);
            $this->display();
        }
    }

    /*
     * 删除菜单
     */

    public function del() {
        $id = $_GET['id'];
        if (D("linkage")->delete($id)) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除菜单失败！");
        }
    }

    /*
     * 菜单排序
     */

    public function listorder() {
        $pk = D("linkage")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("linkage")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

    /*
     * 联动菜单类型
     */

    public function type() {
        $count = D("linkagetype")->where($where)->count();
        $page = new \Think\Page($count, 15);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("linkagetype")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();

        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }

    /*
     * 添加菜单类型
     */

    public function typeadd() {
          if (IS_POST) {
              if($_POST["catname"]==""){
                  $this->error("请输入名称！");
              }
            if (D("linkagetype")->create()) {
                $catid = D("linkagetype")->add();
                if ($catid) {
                    $this->success("增加菜单类型成功！", U("Admin/Expand/type"));
                } else {
                    $this->error("增加菜单类型失败！");
                }
            } else {
                $this->error(D("linkagetype")->getError());
            }
        } else {
            $this->display();
        }
    }

    /*
     * 修改菜单类型
     */

    public function typeedit() {
           if (IS_POST) {
            if (D("linkagetype")->create()) {
                $catid = D("linkagetype")->save();
                if ($catid) {
                    $this->success("修改菜单类型成功！", U("Admin/Expand/type"));
                } else {
                    $this->error("修改菜单类型失败！");
                }
            } else {
                $this->error(D("linkagetype")->getError());
            }
        } else {
            $id= $_GET['id'];
            $data=M("linkagetype")->where("id=".$id)->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    /*
     * 删除菜单类型
     */

    public function typedel() {
        $id = $_GET['id'];
        $count = D("linkage")->where(array("catid" => $id))->count();
        if ($count > 0) {
            $this->error("该类型下还有子菜单，无法删除！");
        }
        if (D("linkagetype")->delete($id)) {
            $this->success("删除菜单类型成功！");
        } else {
            $this->error("删除菜单类型失败！");
        }
    }

}
<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ArticleController extends CommonController {

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
    
        $count = D("Article")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Article")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = D("category")->where("id=" . $r["catid"])->getField("catname");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $tree = new \Think\Tree();
        $catid = $_POST['catid'];
        $where1['id']=array('not in','1,3,4,6');
        $result = D("category")->where($where1)->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['child'] != '1'||$r['modelid'] == 1) {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("category", $select_categorys);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }

    /**
     * 编辑内容
     */
    public function edit() {
        if ($_POST) {
             $imglist=  implode("|", $_POST["imglist"]);
            $description = trim($_POST["description"]);
            if (empty($description)) {
                $description = $this->str_cut(trim(strip_tags($_POST['content'])), 250);
            }
            if (D("Article")->create()) {
                D("Article")->imglist = $imglist;
                D("Article")->updatetime = time();
                D("Article")->description = $description;
                $id = D("Article")->save();
                if (!empty($id)) {
                    $this->success("修改内容成功！", U("Admin/Article/index"));
                } else {
                    $this->error("修改内容失败！");
                }
            } else {
                $this->error(D("Article")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
         if (empty($id)) {
                $this->error("文章ID参数错误");
          }
        $data=D("Article")->where("id=".$id)->find();
        $tree = new \Think\Tree();
        $where1['id']=array('not in','1,3,4,6');
        $result = D("category")->where($where1)->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $data['catid'] ? 'selected' : '';
              if ($r['child'] != '1'||$r['modelid'] == 1) {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("category", $select_categorys);
        $this->assign("data", $data);
         $imglist=  array_filter(explode("|", $data["imglist"]));
         $this->assign("imglist", $imglist);
        $this->display();
        }
        
    }

    /*
     * 添加内容
     */

    public function add() {
        if ($_POST) {
            $description = trim($_POST["description"]);
            $imglist=  implode("|", $_POST["imglist"]);
            if (empty($description)) {
                $description = $this->str_cut(trim(strip_tags($_POST['content'])), 250);
            }
            if (D("Article")->create()) {
                D("Article")->imglist = $imglist;
                D("Article")->inputtime = time();
                D("Article")->username = $_SESSION['user'];
                D("Article")->description = $description;
                $id = D("Article")->add();
                if (!empty($id)) {
                    $this->success("新增内容成功！", U("Admin/Article/index"));
                } else {
                    $this->error("新增内容失败！");
                }
            } else {
                $this->error(D("Article")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $catid = $_POST['catid'];
            $where1['id']=array('not in','1,3,4,6');
            $result = D("category")->where($where1)->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
                  if ($r['child'] != '1'||$r['modelid'] == 1) {
                    $r['disabled'] = "disabled";
                } else {
                    $r['disabled'] = "";
                }
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("category", $select_categorys);
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
        if (D("Article")->delete($id)) {
            $this->success("删除内容成功！");
        } else {
            $this->error("删除内容失败！");
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
                M("Article")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
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
                M("article")->where(array("id" => $id))->save($data);
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
                M("article")->where(array("id" => $id))->save($data);
            }
            $this->success("审核成功！");
        } else {
            $this->error("审核失败！");
        }
    }

    /*
     * 内容推荐
     */

    public function pushs() {
        $data['type'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("article")->where(array("id" => $id))->save($data);
            }
            $this->success("推荐成功！");
        } else {
            $this->error("推荐成功！");
        }
    }

    /*
     * 内容推荐
     */

    public function unpushs() {
        $data['type'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("article")->where(array("id" => $id))->save($data);
            }
            $this->success("取消推荐成功！");
        } else {
            $this->error("取消推荐失败！");
        }
    }

    /*
     * 内容排序
     */

    public function listorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Article")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Article")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

}
<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class LinkController extends CommonController {
    public function _initialize() {
        $linktype=F("linktype");
        if(!$linktype){
            $linktype=M('linktype')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("linktype",$linktype);
        }
        $this->linktype=$linktype;
        
    }
    public function index() {
        $search = I('post.search');
        $where = array();
        if (!empty($search)) {
            //栏目
            $catid = I('post.catid', null, 'intval');
            if (!empty($catid)) {
                $where["catid"] = array("EQ", $catid);
            }
            //状态
            $status = $_POST["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
      
        $count = D("Link")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Link")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = D("linktype")->where("id=" . $r["catid"])->getField("catname");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $tree = new \Think\Tree();
        $catid = $_POST['catid'];
        $result = $this->linktype;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
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
            if (D("Link")->create()) {
                D("Link")->updatetime = time();
                $id = D("Link")->save();
                if (!empty($id)) {
                    $this->success("修改链接成功！", U("Admin/link/index"));
                } else {
                    $this->error("修改链接失败！");
                }
            } else {
                $this->error(D("Link")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
         if (empty($id)) {
                $this->error("链接ID参数错误");
          }
        $data=D("Link")->where("id=".$id)->find();
        $tree = new \Think\Tree();
        $result = $this->linktype;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $data['catid'] ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("category", $select_categorys);
        $this->assign("data", $data);
        $this->display();
        }
        
    }

    /*
     * 添加内容
     */

    public function add() {
        if ($_POST) {
            if (D("Link")->create()) {
                D("Link")->inputtime = time();
                D("Link")->username = $_SESSION['user'];
                $id = D("Link")->add();
                if (!empty($id)) {
                    $this->success("新增链接成功！", U("Admin/Link/index"));
                } else {
                    $this->error("新增链接失败！");
                }
            } else {
                $this->error(D("Link")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $catid = $_POST['catid'];
            $result = $this->linktype;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
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
        } 
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("Link")->delete($id)) {
            $this->success("删除链接成功！");
        } else {
            $this->error("删除链接失败！");
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
                M("Link")->delete($id);
            }
            $this->success("删除链接成功！");
        } else {
            $this->error("删除链接失败！");
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
                M("Link")->where(array("id" => $id))->save($data);
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
                M("Link")->where(array("id" => $id))->save($data);
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
        $pk = D("Link")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Link")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

     public function type() {
        $result = $this->linktype;
        $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $r['str_manage'] = '';
        foreach ($result as $r) {
            $r['str_manage'] .= '<a href="' . U("Admin/link/typeadd", array("parentid" => $r['id'])) . '">添加子类</a> | ';
            $r['str_manage'] .= '<a href="' . U("Admin/link/typeedit", array("catid" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a>  | <a class="del"  href="' . U("Admin/link/typedel", array("catid" => $r['id'], "menuid" => $_GET["menuid"])) . '">删除</a>';
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
	<td align='center'><input name='listorders[\$id]' type='number' size='3' value='\$listorder' class='input length_1'></td>
	<td align='center'>\$id</td>
	<td>\$spacer\$catname</td>
                
	<td align='center'>\$str_manage</td>
	</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        $this->display();
    }
    
    
        /**
     *  删除
     */
    public function typedel() {
        $id = $_GET['catid'];
         $count = D("linktype")->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该栏目下还有子类型，无法删除！");
        }
        if (D("linktype")->delete($id)) {
            $this->success("删除类型成功！");
        } else {
            $this->error("删除类型失败！");
        }
    }

     /**
     *  添加类型
     */
    public function typeadd() {
        if (IS_POST) {
             if (D("linktype")->create()) {
                $catid=D("linktype")->add();
                if ($catid) {
                    $this->success("增加类型成功！", U("Admin/link/type"));
                } else {
                    $this->error("新增类型失败！");
                }
            } else {
                $this->error(D("linktype")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->linktype;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected >\$spacer \$catname</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("category", $select_categorys);
            $this->display();
        }
    }
    
     /**
     *  编辑类型
     */
    public function typeedit() {
        $id = $_GET['catid'];
        if (IS_POST) {
            $data = D("linktype")->create();
            if ($data) {
                if (D("linktype")->save($data)) {
                    $this->success("修改类型成功！", U("Admin/link/type"));
                } else {
                    $this->error("修改类型失败！");
                }
            } else {
                $this->error(D("linktype")->getError());
            }
        } else {
            $data = M("linktype")->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该栏目不存在！");
            }
             $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result =$this->linktype;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $data["parentid"] ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected >\$spacer \$catname</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("category", $select_categorys);
            $this->assign("data", $data);
            $this->display();
         
        }
    }
    
        //类型排序 
    public function typelistorder() {
        if (IS_POST) {
            $Category = D("linktype");
            foreach ($_POST['listorders'] as $id => $listorder) {
                $Category->where(array('id' => $id))->save(array('listorder' => $listorder));
            }
            $this->success("栏目排序更新成功！");
        } else {
            $this->error("栏目信息提交有误！");
        }
    }
}
<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class CategoryController extends CommonController {

    public function _initialize() {
        $articlecate=F("articlecate");
        if(!$productcate){
            $articlecate=M('category')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("articlecate",$articlecate);
        }
        $this->articlecate=$articlecate;
        
    }
    public function index() {
        $result = $this->articlecate;
        $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $r['str_manage'] = '';
        foreach ($result as $r) {
            if ($r['child']==0) {
                $r['str_manage'] .= '<a href="' . U("Admin/Category/add", array("parentid" => $r['id'])) . '">添加子栏目</a> | ';
            }
            $r['str_manage'] .= '<a href="' . U("Admin/category/edit", array("catid" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a>  | <a class="del"  href="' . U("Admin/category/delete", array("catid" => $r['id'], "menuid" => $_GET["menuid"])) . '">删除</a>';
            if ($r['modelid'] == '1'||$r['modelid'] == '2') {
                $r['str_manage'] .= ' | <a href="' . U("Admin/Category/change", array("catid" => $r['id'])) . '">终极属性转换</a> ';
            }
            $r['ismenu'] = $r['ismenu'] ? "显示" : "不显示";
            if ($r['child'] == '1') {
                $r['disabled'] = "是";
                 $r['yesadd'] = 'blue';
            } else {
                $r['disabled'] = "否";
                $r['yesadd'] = '';
            }
            if ($r['type'] == '1') {
                $r['type'] = "内部栏目";
            } elseif ($r['type'] == '2') {
                $r['type'] = "<font color='red'>外部链接</font>";
                $r['yesadd'] = '';
            }
            if ($r['modelid'] == '1') {
                $r['model'] = "<font color='blue'>单页模块</font>";
            } elseif ($r['modelid'] == '2') {
                $r['model'] = "列表模块";
            }else{
                $r['model'] = "<font color='red'>外部链接</font>";
                
            }
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
	<td align='center'><input name='listorders[\$id]' type='number' size='3' value='\$listorder' class='input length_1'></td>
	<td align='center'><font color='\$yesadd'>\$id</font></td>
	<td>\$spacer\$catname</td>
                    <td align='center'>\$type</td>
                    <td align='center'>\$model</td>
                    <td align='center'>\$ismenu</td>
                    <td align='center'>\$disabled</td>
	<td align='center'>\$str_manage</td>
	</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        $this->display();
    }

    /**
     *  添加
     */
    public function add() {
        if (IS_POST) {
             if (D("Category")->create()) {
                $catid=D("Category")->add();
                if ($catid) {
                    if($_POST['modelid']==1){
                        $data["catid"]=$catid;
                        $data["id"]=$catid;
                        $data["title"]=$_POST['catname'];
                        M("Page")->add($data);
                    }
                    $this->success("增加栏目成功！", U("Admin/Category/index"));
                } else {
                    $this->error("新增栏目失败！");
                }
            } else {
                $this->error(D("Category")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->articlecate;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                if ($r['child'] == '1') {
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
            $Ca= $this->categoryinfo($parentid);
            $this->assign('parentid_modelid', $Ca['modelid']);
            $this->display();
        }
    }

    //添加外部链接
    public function addurl() {
         if (IS_POST) {
             $this->add();
         }  else {
             $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->articlecate;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                if ($r['child'] == '1') {
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
    
    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['catid'];
         $count = D("category")->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该栏目下还有子栏目，无法删除！");
        }
        if (D("category")->delete($id)) {
            $this->success("删除栏目成功！");
        } else {
            $this->error("删除栏目失败！");
        }
    }

    /**
     *  编辑
     */
    public function edit() {
        $id = $_GET['catid'];
        if (IS_POST) {
            $data = D("category")->create();
            if ($data) {
                if (D("category")->save($data)) {
                     if($_POST['modelid']==1){
                         $page= M("Page")->where("catid=".$_POST['id'])->find();
                         if(empty($page)){
                             $data["title"]=$_POST['catname'];
                            $data["catid"]=$catid;
                            $data["id"]=$catid;
                            $data["title"]=$_POST['catname'];
                            M("Page")->add($data);
                         } 
                    }
                    $this->success("修改栏目成功！", U("Admin/category/index"));
                } else {
                    $this->error("修改栏目失败！");
                }
            } else {
                $this->error(D("category")->getError());
            }
        } else {
            $data = M("category")->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该栏目不存在！");
            }
             $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->articlecate;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $data["parentid"] ? 'selected' : '';
                if ($r['child'] == '1') {
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
            if($data["type"]==2){
                $this->display(editurl);
            }  else {
                $this->display();
            }
        }
    }
    
    //栏目属性转换 
      public function change() {
        $catid = I('get.catid', 0, 'intval');
        $r = M("Category")->where(array("id" => $catid))->find();
        if ($r) {
            //栏目类型非0，不允许使用属性转换
            $count = M("Category")->where(array("parentid" => $catid))->count();
            if ($count > 0) {
                $this->error("该栏目下已经存在栏目，无法转换！");
            } else {
                $child = $r['child'] ? 0 : 1;
                $status = D("Category")->where(array("id" => $catid))->save(array("child" => $child));
                if ($status !== false) {
                    $this->success("栏目属性转换成功！");
                } else {
                    $this->error("栏目属性转换失败！");
                }
            }
        } else {
            $this->error("栏目不存在！");
        }
    }

    //栏目排序 
    public function listorder() {
        if (IS_POST) {
            $Category = M("Category");
            foreach ($_POST['listorders'] as $id => $listorder) {
                $Category->where(array('id' => $id))->save(array('listorder' => $listorder));
            }
            $this->success("栏目排序更新成功！");
        } else {
            $this->error("栏目信息提交有误！");
        }
    }
   

    /**
     * 根据栏目ID获取栏目信息
     * @param int $id 栏目ID
     * @return array 栏目信息
     * @author oydm<389602549@qq.com>  time|20140423
     */
      public function categoryinfo($id) {
          $data=D("Category")->where("id=".$id)->find();
          return $data;
      }
    

}
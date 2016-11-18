<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ProductcateController extends CommonController {

    public function _initialize() {
        $productcate=F("productcate");
        if(!$productcate){
            $productcate=M('productcate')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("productcate",$productcate);
        }
        $this->productcate=$productcate;       
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $result = $this->productcate;
        $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $r['str_manage'] = '';
        foreach ($result as $r) {
            $r['str_manage'] .= '<a href="' . U("Admin/Productcate/add", array("parentid" => $r['id'])) . '">添加子类</a> | ';
            $r['str_manage'] .= '<a href="' . U("Admin/Productcate/edit", array("catid" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a>  | <a class="del"  href="' . U("Admin/Productcate/delete", array("catid" => $r['id'], "menuid" => $_GET["menuid"])) . '">删除</a>';
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
    <td align='center'><input name='listorders[\$id]' type='number' size='3' value='\$listorder' class='input length_1'></td>
    <td align='center'>\$id</td>
    <td>\$spacer\$catname</td>
                    <td>\$description</td>
    <td align='center'>\$str_manage</td>
    </tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("Productcate", $categorys);
        $this->display();
    }

    /**
     *  添加
     */
    public function add() {
        if (IS_POST) {
             if (D("productcate")->create()) {
                $catid=D("productcate")->add();
                if ($catid) {
                    $this->success("增加栏目成功！", U("Admin/productcate/index"));
                } else {
                    $this->error("新增栏目失败！");
                }
            } else {
                $this->error(D("productcate")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->productcate;
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
            $select_productcates = $tree->get_tree(0, $str);
            $this->assign("Productcate", $select_productcates);
            $Ca= $this->productcateinfo($parentid);
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
            $result = $this->productcate;
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
            $select_productcates = $tree->get_tree(0, $str);
            $this->assign("Productcate", $select_productcates);
            $this->display();
        }
    }
    
    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['catid'];
         $count = D("productcate")->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该栏目下还有子栏目，无法删除！");
        }
        if (D("productcate")->delete($id)) {
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
            $data = D("productcate")->create();
            if ($data) {
                if (D("productcate")->save($data)) {
                    $this->success("修改栏目成功！", U("Admin/productcate/index"));
                } else {
                    $this->error("修改栏目失败！");
                }
            } else {
                $this->error(D("productcate")->getError());
            }
        } else {
            $data = D("productcate")->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该栏目不存在！");
            }
             $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->productcate;
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
            $select_productcates = $tree->get_tree(0, $str);
            $this->assign("Productcate", $select_productcates);
            $this->assign("data", $data);
            if($data["type"]==2){
                $this->display(editurl);
            }  else {
                $this->display();
            }
        }
    }
   
    //栏目排序 
    public function listorder() {
        if (IS_POST) {
            $productcate = D("productcate");
            foreach ($_POST['listorders'] as $id => $listorder) {
                $productcate->where(array('id' => $id))->save(array('listorder' => $listorder));
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
      public function productcateinfo($id) {
          $data=D("productcate")->where("id=".$id)->find();
          return $data;
      }
    
    public function apply() {
        if (IS_POST) {
            $menuid=$_POST["menuid"];
            $data["catid"]=implode(",",$menuid);
            $status=M("store_cate")->where("storeid=".$this->storeid)->find();
            if($status){
                $data['updatetime']=time();
                $result = M("store_cate")->where("storeid=".$this->storeid)->save($data);
            }else{
                $data['storeid']=$this->storeid;
                $data['inputtime']=time();
                $result = M("store_cate")->add($data);
            }
            
            if ($result) {
                $this->success("应用成功！", U("Admin/Productcate/apply"));
            } else {
                $this->error("应用失败！");
            }
        } else {
            $menu = new \Think\Tree();
            $menu->icon = array('│ ', '├─ ', '└─ ');
            $menu->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = $this->productcate;
            foreach ($result as $n => $t) {
                $result[$n]['checked'] = ($this->is_checked($t["id"], $this->storeid)) ? ' checked' : '';
                $result[$n]['level'] = $this->get_level($t['id'], $result);
                $result[$n]['parentid_node'] = ($t['parentid']) ? ' class="child-of-node-' . $t['parentid'] . '"' : '';
            }
            //dump($result);
            $str = "<tr id='node-\$id' \$parentid_node>
                           <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$catname</td>
        </tr>";
            $menu->init($result);
            $categorys = $menu->get_tree(0, $str);
            $this->assign("categorys", $categorys);
            $this->display();
        }
    }
    protected function is_checked($catid, $storeid) {
         $data = M("store_cate")->where(array("storeid" => $storeid))->getField('catid');
         $data=  explode(",", $data);
         $info = in_array($catid, $data);
        if ($info) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function get_level($id, $array = array(), $i = 0) {
        foreach ($array as $n => $value) {
            if ($value['id'] == $id) {
                if ($value['parentid'] == '0')
                    return $i;
                $i++;
                return $this->get_level($value['parentid'], $array, $i);
            }
        }
    }

}
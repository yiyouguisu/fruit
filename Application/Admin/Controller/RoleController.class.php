<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class RoleController extends CommonController {
    public function index() {
       $data = M("auth_group")->order(array("id" => "asc"))->select();
        $this->assign("data", $data);
        $this->display();
    }
    /**
     *  添加
     */
    public function add() {
        if (IS_POST) {
            if (M("auth_group")->create()) {
                if (M("auth_group")->add()) {
                    $this->success("添加角色成功！",U("Admin/Role/index"));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error(M("auth_group")->getError());
            }
        } else {
            $this->display();
        }
    }

  /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if ($id == 1) {
            $this->error("超级管理员角色不能被删除！");
        }
        $status =  M("auth_group")->delete($id);
        if ($status) {
            $this->success("删除成功！", U("Admin/Role/index"));
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     *  编辑
     */
    public function edit() {
       $id = $_GET['id'];
        if ($id == 1) {
            $this->error("超级管理员角色不能被修改！");
        }
        if (IS_POST) {
            $data = M("auth_group")->create();
            if ($data) {
                if (M("auth_group")->save($data)) {
                    $this->success("修改成功！", U("Admin/Role/index"));
                } else {
                    $this->error("修改失败！");
                }
            } else {
                $this->error(M("auth_group")->getError());
            }
        } else {
            $data = M("auth_group")->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该角色不存在！");
            }
            $this->assign("data", $data);
            $this->display();
        }
    }
    //权限管理
      public function auth() {
        if (IS_POST) {
           $menuid=$_POST["menuid"];
           $data["rules"]=implode(",",$menuid);
           $result = M("auth_group")->where("id=".$_POST["id"])->save($data);
            if ($result) {
                    $this->success("授权成功！", U("Admin/Role/index"));
                } else {
                    $this->error("授权失败！");
                }
        } else {
            //角色ID
            $id = (int) $_GET["id"];
            if (!$id) {
                $this->error("参数错误！");
            }
           $menu = new \Think\Tree();
            $menu->icon = array('│ ', '├─ ', '└─ ');
            $menu->nbsp = '&nbsp;&nbsp;&nbsp;';
           $result = M("menu")->where("status=1 and type in (2,3)")->order(array("listorder" => "DESC"))->select();
            foreach ($result as $n => $t) {
                $result[$n]['checked'] = ($this->is_checked($t["id"], $id)) ? ' checked' : '';
                $result[$n]['level'] = $this->get_level($t['id'], $result);
                $result[$n]['parentid_node'] = ($t['parentid']) ? ' class="child-of-node-' . $t['parentid'] . '"' : '';
            }
            //print_r($result);
            $str = "<tr id='node-\$id' \$parentid_node>
                           <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$title</td>
	    </tr>";
            $menu->init($result);
            $categorys = $menu->get_tree(0, $str);

            $this->assign("categorys", $categorys);
            $this->assign("id", $id);
            $this->display();
        }
      }
   /**
     *  检查指定菜单是否有权限
     * @param int $menuid 权限ID
     * @param int $roleid 需要检查的角色ID
     */
    protected function is_checked($menuid, $roleid) {
         $data = M("auth_group")->where(array("id" => $roleid))->getField('rules');
         $data=  explode(",", $data);
         $info = in_array($menuid, $data);
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
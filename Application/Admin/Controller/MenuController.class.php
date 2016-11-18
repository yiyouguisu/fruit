<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class MenuController extends CommonController {

    public function _initialize() {
        parent::_initialize();
        $menu=F("menu");
        if(!$menu){
            $menu=M('menu')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("menu",$menu);
        }
        $this->menu=$menu;
        
    }
    public function index() {
         $result = $this->menu;
         $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        //class="J_ajax_del";
       
        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("Admin/Menu/add", array("parentid" => $r['id'], "menuid" => $_GET['menuid'])) . '">添加子菜单</a> | <a href="' . U("Admin/Menu/edit", array("id" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a>  | <a class="del"  href="' . U("Admin/Menu/delete", array("id" => $r['id'], "menuid" => $_GET["menuid"])) . '">删除</a>';
            $r['ismenu'] = $r['ismenu'] ? "显示" : "不显示";
            if($r['type']==3){
                $r['type']="菜单+权限验证";
            }elseif($r['type']==2){
                 $r['type']="只为权限验证";
            }elseif($r['type']==1){
                 $r['type']="只为菜单";
            }
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
	<td align='center'><input name='listorders[\$id]' type='number' size='3' value='\$listorder' class='input length_1'></td>
	<td>\$id</td>
	<td>\$spacer\$title</td>
                    <td>\$name</td>
                    <td>\$condition</td>
                    <td>\$type</td>
                    <td>\$ismenu</td>
	<td>\$str_manage</td>
	</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        $this->display();
    }
    /**
     *  添加菜单
     */
    public function add() {
         if (IS_POST) {
            if (D("menu")->create()) {
                $catid=D("menu")->add();
                if ($catid) {
                    $this->success("新增成功！", U("Admin/Menu/index"));
                } else {
                    $this->error("新增失败！");
                }
            } else {
                $this->error(D("menu")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $parentid =$_GET['parentid'];
            $result = $this->menu;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            $this->display();
        }
    }

  /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        $count = D("menu")->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该菜单下还有子菜单，无法删除！");
        }
        if (D("menu")->delete($id)) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     *  编辑
     */
    public function edit() {
        if (IS_POST) {
            if (D("menu")->create()) {
                if (D("menu")->save() !== false) {
                    $this->success("更新成功！",U("Admin/Menu/index"));
                } else {
                    $this->error("更新失败！");
                }
            } else {
                $this->error(D("menu")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $id =  $_GET['id'];
            $rs = D("menu")->where(array("id" => $id))->find();
            $result = $this->menu;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("data", $rs);
            $this->assign("select_categorys", $select_categorys);
            $this->display();
        }
    }

    //排序
    public function listorders() {
        $pk = D("Menu")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status=D("Menu")->where(array($pk => $key))->save($data);
        }
         if ($status!== false) {
                  $this->success("排序更新成功！");
            } else {
             $this->error("排序更新失败！");
              }
    }
    
     //常用菜单
    public function public_changyong() {
         if (IS_POST) {
            $menu = $_POST['menu'];
            if(count($menu) > 15){
                $this->error("常用菜单设置不宜超过15个！");
            }
            //先删除旧的
            M("adminpanel")->where(array("userid"=> $_SESSION["userid"]))->delete();
            foreach($menu as $k=>$menuid){
                $info = M("Menu")->where(array("id"=>$menuid))->find();
                if($info){
                    $url = $info['name'];
                    $url = U("$url","&menuid=$menuid");
                    M("adminpanel")->add(array(
                        "menuid" => $menuid,
                        "userid" => $_SESSION["userid"],
                        "name" => $info['title'],
                        "url" => $url,
                        "datetime" => time(),
                    ));
                }
            }
            $this->success("添加成功！",U('Admin/Index/index'));
        } else {
            if($_SESSION["userid"]==1){
                $cate =M('menu')->where(array('ismenu'=>1))->field("id,title,parentid,type,name")->order(array('listorder'=>'desc','id'=>'asc'))->select();
                $data = self::unlimitedForLayer($cate,0);
                //p($data);die;
            }else{
                $data = array();
                $roleid = $_SESSION["group_id"];
                $access = M("auth_group")->where(array("id"=>$roleid))->find();

                if($access){
                    $where['id']=array('in',$access['rules']);
                    $where['ismenu']=1;
                    $cate = D("Menu")->where($where)->field("id,title,parentid,type,name")->order(array('listorder'=>"desc"))->select();
                    $data = self::unlimitedForLayer($cate,0);
                }else{
                    $data = array();
                }
            }
            $Panel_data = M("adminpanel")->where(array("userid"=> $_SESSION["userid"]))->field("menuid")->select();
            foreach($Panel_data as $r){
                $Panel[] = $r['menuid'];
            }

            $this->assign("data", $data);
            $this->assign("panel", $Panel);
            $this->display();
        }
    }
    Static public function unlimitedForLayer($cate,$pid){
        $arr=array();
        foreach ($cate as $v) {
            # code...
            if($v['parentid']==$pid){
                $v['child']=self::unlimitedForLayer($cate,$v['id']);
                $arr[]=$v;
            }
        }
        return $arr;
    }

}
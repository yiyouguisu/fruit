<?php
namespace Admin\Model;
use  Admin\Model\CommonModel;
class MenuModel extends CommonModel {

     //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('title', 'require', '名称不能为空！', 1, 'regex', 3),
        array('name', 'require', '规则不能为空！', 1, 'regex', 3),
     //array('name','','同样的记录已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
    // array('title','','同样的记录已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
       // array('title,name', 'checkAction', '同样的记录已经存在！', 0, 'callback', 1),
     array('parentid', 'checkParentid', '菜单只支持四级！', 1, 'callback', 1),
    );
  //自动完成
    protected $_auto = array(
            //array(填充字段,填充内容,填充条件,附加规则)
    );

    //验证菜单是否超出三级
    public function checkParentid($parentid) {
        $find = $this->where(array("id" => $parentid))->getField("parentid");
        if ($find) {
            $find2 = $this->where(array("id" => $find))->getField("parentid");
            if ($find2) {
                $find3 = $this->where(array("id" => $find2))->getField("parentid");
                if ($find3) {
                    return false;
                }
            }
        }
        return true;
    }

    //验证action是否重复添加
    public function checkAction($data) {
        //检查是否重复添加
        $find = $this->where($data)->find();
        if ($find) {
            return false;
        }
        return true;
    }
     /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID  
     * @param integer $with_self  是否包括他自己
     */
    public function admin_menu($parentid, $with_self = false) {
        //父节点ID
        $uid=$_SESSION["userid"];
        if($uid!=1){
         $User = M("user")->where(array("id" => $uid))->cache(true)->find();
         $rules=M("auth_group")->where("id=".$User["group_id"])->cache(true)->getField('rules');
         $rules= explode(',',$rules);
           $select["id"]=array('in',$rules);
        }
        $parentid = (int) $parentid;
        $select["parentid"]=$parentid;
        $select["ismenu"]=1;
        $select["type"]=array('in','1,3');
      
        $result = $this->where($select)->order(array("listorder" => "DESC"))->cache(true)->select();
        if ($with_self) {
            $result2[] = $this->where(array('id' => $parentid))->cache(true)->find();
            $result = array_merge($result2, $result);
        }
     
        return $result;
    }

    /**
     * 获取菜单 头部菜单导航
     * @param $parentid 菜单id
     */
    public function submenu($parentid = '', $big_menu = false) {
        $array = $this->admin_menu($parentid, 1);
        $numbers = count($array);
        if ($numbers == 1 && !$big_menu) {
            return '';
        }
        return $array;
    }

    /**
     * 菜单树状结构集合
     */
    public function menu_json() {
        $Panel = M("adminpanel")->where(array("userid" => $_SESSION["userid"]))->cache(true)->select();
        $items['0changyong'] = array(
            "id" => "",
            "name" => "常用菜单",
            "parent" => "changyong",
            "url" => U("Menu/public_changyong"),
        );
        foreach ($Panel as $r) {
            $items[$r['menuid'] . '0changyong'] = array(
                "icon" => "",
                "id" => $r['menuid'] . '0changyong',
                "name" => $r['name'],
                "parent" => "changyong",
                "url" => $r['url'],
            );
        }
       $changyong = array(
            "changyong" => array(
                "icon" => "",
                "id" => "changyong",
                "name" => "常用",
                "parent" => "",
                "url" => "",
                "items" => $items
            )
        );
        $data = $this->get_tree(0);
        //return $data;
        return array_merge($changyong, $data);
    }

    //取得树形结构的菜单
    public function get_tree($myid, $parent = "", $Level = 1) {
        $ret=array();
        $data = $this->admin_menu($myid);
        $Level++;
        if (is_array($data)) {
            foreach ($data as $a) {
                $id = $a['id'];
                $name =$a['name'];
                $app=explode("/",$a['name']);
                $app=$app[0];
                $array = array(
                    "icon" => "",
                    "id" => $id . $app,
                    "name" => $a['title'],
                    "parent" => $parent,
                    "url" => U("{$name}", array("menuid" => $id)),
                );
                $ret[$id . $app] = $array;
                $child = $this->get_tree($a['id'], $id, $Level);
                //由于后台管理界面只支持三层，超出的不层级的不显示
                if ($child && $Level <= 3) {
                    $ret[$id . $app]['items'] = $child;
                }
            }
        }
        return $ret;
    }
    public function config_cache() {
        $data = $this->order(array('listorder'=>'desc','id'=>'asc'))->select();
        F("menu", $data);
        return $data;
    }

    /**
     * 后台有更新/编辑则删除缓存
     * @param type $data
     */
    public function _before_write(&$data) {
        parent::_before_write($data);
        F("menu", NULL);
    }

    //删除操作时删除缓存
    public function _after_delete($data, $options) {
        parent::_after_delete($data, $options);
        $this->config_cache();
    }

    //更新数据后更新缓存
    public function _after_update($data, $options) {
        parent::_after_update($data, $options);
        $this->config_cache();
    }

    //插入数据后更新缓存
    public function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $this->config_cache();
    }
   
}

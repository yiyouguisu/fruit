<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class UseruploadController extends CommonController {
    public function _initialize() {
        $user_upload_type=F("user_upload_type");
        if(!$user_upload_type){
            $user_upload_type=M('user_upload_type')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("user_upload_type",$user_upload_type);
        }
        $this->user_upload_type=$user_upload_type;
        
    }
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
            //状态
            $status = I('get.status', null, 'intval');
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
            $username=I('username');
            $realname=I('realname');
            if(!empty($username)){
                $select['username']=$username;
            }
            if(!empty($realname)){
                $select['realname']=$realname;
            }
            $uid=M('Member')->where($select)->getField('id');
            if(!empty($uid)){
                $where['uid']=$uid;
            }
            
        }
      
        $count = D("User_upload")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("User_upload")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["filename"], 30);
            $data[$k]["catname"] = D("user_upload_type")->where("id=" . $r["catid"])->getField("catname");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $tree = new \Think\Tree();
        $catid = $_GET['catid'];
        $result = $this->user_upload_type;
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


    /*
     * 内容审核
     */

    public function review() {
        if (IS_POST) {
            $status=I('status');
            $data=M('User_upload')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('User_upload')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功！");
            }elseif($id>0&&$status==3){
                $this->success("审核成功！");
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id=I('id');
            $data=M('User_upload')->where(array('id'=>$id))->find();
            $data['catname']=D("user_upload_type")->where("id=" . $data["catid"])->getField("catname");
            $this->assign("data",$data);
            $this->display();
        }
    }

    public function type() {
        $result = $this->user_upload_type;
        $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $r['str_manage'] = '';
        foreach ($result as $r) {
            $r['str_manage'] .= '<a href="' . U("Admin/Userupload/typeadd", array("parentid" => $r['id'])) . '">添加子类</a> | ';
            $r['str_manage'] .= '<a href="' . U("Admin/Userupload/typeedit", array("catid" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a>  | <a class="del"  href="' . U("Admin/ad/typedel", array("catid" => $r['id'], "menuid" => $_GET["menuid"])) . '">删除</a>';
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
        $this->assign("categorys", $categorys);
        $this->display();
    }
    
    
    /**
     *  删除
     */
    public function typedel() {
        $id = $_GET['catid'];
         $count = D("user_upload_type")->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该栏目下还有子类型，无法删除！");
        }
        if (D("user_upload_type")->delete($id)) {
            $this->success("删除类型成功！");
        } else {
            $this->error("删除类型失败！");
        }
    }
    public function _after_typedel(){
        $user_upload_type=M('user_upload_type')->order(array('listorder'=>'desc','id'=>'asc'))->select();
        F("user_upload_type",$user_upload_type);
    }

     /**
     *  添加类型
     */
    public function typeadd() {
        if (IS_POST) {
             if (D("user_upload_type")->create()) {
                $catid=D("user_upload_type")->add();
                if ($catid) {
                    $this->success("增加类型成功！", U("Admin/Userupload/type"));
                } else {
                    $this->error("新增类型失败！");
                }
            } else {
                $this->error(D("user_upload_type")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->user_upload_type;
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
    public function _after_typeadd(){
        $user_upload_type=M('user_upload_type')->order(array('listorder'=>'desc','id'=>'asc'))->select();
        F("user_upload_type",$user_upload_type);
    }
    
     /**
     *  编辑类型
     */
    public function typeedit() {
        $id = $_GET['catid'];
        if (IS_POST) {
            $data = D("user_upload_type")->create();
            if ($data) {
                if (D("user_upload_type")->save($data)) {
                    $this->success("修改类型成功！", U("Admin/Userupload/type"));
                } else {
                    $this->error("修改类型失败！");
                }
            } else {
                $this->error(D("user_upload_type")->getError());
            }
        } else {
            $data = M("user_upload_type")->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该栏目不存在！");
            }
             $tree = new \Think\Tree();
            $parentid = $_GET['parentid'];
            $result = $this->user_upload_type;
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
    public function _after_typeedit(){
        $user_upload_type=M('user_upload_type')->order(array('listorder'=>'desc','id'=>'asc'))->select();
        F("user_upload_type",$user_upload_type);
    }
    
        //类型排序 
    public function typelistorder() {
        if (IS_POST) {
            $Category = M("user_upload_type");
            foreach ($_POST['listorders'] as $id => $listorder) {
                $Category->where(array('id' => $id))->save(array('listorder' => $listorder));
            }
            $this->success("栏目排序更新成功！");
        } else {
            $this->error("栏目信息提交有误！");
        }
    }
    public function _after_typelistorder(){
        $user_upload_type=M('user_upload_type')->order(array('listorder'=>'desc','id'=>'asc'))->select();
        F("user_upload_type",$user_upload_type);
    }

}
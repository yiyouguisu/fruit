<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ManagerController extends CommonController {

    public function index() {
        $group_id = $_GET["group_id"];
        $UserView = D("user");
        if (empty($group_id)) {
            $User = $UserView->where(array("role" => 1))->select();
        } else {
            $User = $UserView->where(array("role" => 1,"group_id" => $group_id))->select();
        }
        foreach ($User as $key => $value) {
            $User[$key]["role_name"] = M("auth_group")->where("id=" . $value["group_id"])->getField('title');
        }
        $this->assign("Userlist", $User);
        $this->display();
    }

    /**
     *  添加
     */
    public function add() {
        if (IS_POST) {
            if (D("user")->addUser($_POST)) {
                $this->success("添加管理员成功！", U("Admin/Manager/index"));
            } else {
                $this->error(D("user")->getError());
            }
        } else {
            $role = M("auth_group")->select();
            $this->assign("role", $role);
            $this->display();
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        if ($id == 1) {
            $this->error("该用户不能被删除！");
        }
        if ((int) $id == $_SESSION["userid"]) {
            $this->error("你不能删除你自己！");
        }
        //执行删除
        if (D("user")->delUser($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(D("user")->getError());
        }
    }

    /**
     *  编辑
     */
    public function edit() {
        if (IS_POST) {
            if (false !== D("user")->editUser($_POST)) {
                $this->success("更新成功！", U("Admin/Manager/index"));
            } else {
                $this->error(D("user")->getError());
            }
        } else {
            $role = M("auth_group")->select();
            $this->assign("role", $role);
            $data = D("user")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    /**
     * 修改个人信息
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function myinfo() {
        if (IS_POST) {
            $User = D("User");
            $data = $User->create();
            //判断是否修改的是否本人
            if ($_POST["id"] != $_SESSION['userid']) {
                $this->error("只能修改本人！");
            }
            if ($data) {
                //过滤数据，防止自己修改自己信息给自己提权，比如，角色提升
                $noq = array("password", "last_login_time", "last_login_ip", "login_count", "role_id", "verify");
                foreach ($noq as $key => $value) {
                    unset($data[$value]);
                }
                if ($User->save($data) !== false) {
                    $this->success("资料修改成功！");
                } else {
                    $this->error("更新失败！");
                }
            } else {
                $this->error($User->getError());
            }
        } else {
            $data = M("User")->where(array("id" => $_SESSION['userid']))->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    /**
     * 修改密码
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function chanpass() {
        if (IS_POST) {
            $userid = intval($_SESSION['userid']);
            if (trim($_POST["password"]) == "") {
                $this->error("请输入旧密码！");
            }
            //检验原密码是否正确
            $user = D("User")->getLocalAdminUser($userid, I("post.password", "", "trim"));
                
            //$user =M("User")->where(array("id" =>$_SESSION['userid'],"password"=>  md5(trim($_POST["password"]))))->find();
            if ($user == false) {
                $this->error("旧密码输入错误！");
            }
            if (trim($_POST["new_password"]) == "") {
                $this->error("请输入新密码！");
            }
            if (trim($_POST["new_pwdconfirm"]) == "") {
                $this->error("请输入重复密码！");
            }
            if (trim($_POST["new_password"]) != trim($_POST["new_pwdconfirm"])) {
                $this->error("两次密码不相同！");
            }
            $pass = I("post.new_password");
       
            $up = D("User")->ChangePassword($userid, $pass);
            if ($up) {
                //退出登陆
                \Admin\Controller\PublicController::outlogin();
                $this->success("密码已经更新，请重新登陆！", U("Admin/Public/login"));
            } else {
                $this->error("密码更新失败！", U("Admin/Manage/chanpass"));
            }
        } else {
            $this->display();
        }
    }

}
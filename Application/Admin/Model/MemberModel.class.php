<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class MemberModel extends RelationModel {

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'),
        //array('username', array(6,25), '用户名长度在6-12之间！',1,'length'),
        array('password', 'require', '密码不能为空！', 0, 'regex', 1),
        array('pwdconfirm', 'password', '两次输入的密码不一样！', 0, 'confirm'),
        //array('password', array(5,28), '密码长度太短！',1,'length'),
        //array('realname', 'require', '真实姓名不能为空！'),
        // array('email', 'require', '邮箱地址不能为空！'),
        // array('email', 'email', '邮箱地址有误！'),
        // array('email', '', '邮箱已经存在！', 0, 'unique', 3),
        array('username', '', '用户名已经存在！', 0, 'unique', 3),
    );

     /**
     * 对明文密码，进行加密，返回加密后的密码
     * @param $identifier 为数字时，表示uid，其他为用户名
     * @param type $pass 明文密码，不能为空
     * @return type 返回加密后的密码
     */
    public function encryption($identifier, $pass, $verify = "") {
        $v = array();
        if (is_numeric($identifier)) {
            $v["id"] = $identifier;
        } else {
            $v["username"] = $identifier;
        }
        $pass = md5($pass . md5($verify));
        return $pass;
    }
    
      /**
     * 根据标识修改对应用户密码
     * @param type $identifier
     * @param type $password
     * @return type 
     */
    public function ChangePassword($identifier, $password) {
        if (empty($identifier) || empty($password)) {
            return false;
        }
        $term = array();
        if (is_int($identifier)) {
            $term['id'] = $identifier;
        } else {
            $term['username'] = $identifier;
        }
        $verify =  \Admin\Common\CommonController::genRandomString(6);
        $data['verify'] = $verify;
        $data['password'] = $this->encryption($identifier, $password, $verify);
        $up = $this->where($term)->save($data);
        if ($up !== false) {
            return true;
        }
        return false;
    }

    /**
     * 编辑用户信息
     * @param type $data
     * @return boolean
     */
    public function editUser($data) {
        if (empty($data) || !isset($data['id'])) {
            $this->error = '数据不能为空！';
            return false;
        }
        //角色Id
        $id = (int) $data['id'];
        $email=$data['email'];
        $username=$data['username'];
        unset($data['id']);
        unset($data['username']);
         unset($data['email']);
        //取得原本用户信息
        $userInfo = $this->where(array("id" => $id))->getField('id,verify');
        if (empty($userInfo)) {
            $this->error = '该用户不存在！';
            return false;
        }
        $data = $this->create($data, 2);
        if ($data) {
            //密码
            $password = $data['password'];
            if (!empty($password)) {
                //生成随机认证码
                $data['verify'] = \Admin\Common\CommonController::genRandomString(6);
                //进行加密
                $pass = $this->encryption($username, $password, $data['verify']);
                $data['password'] = $pass;
            } else {
                unset($data['password']);
            }
            $data['username']=$username;
            $data['email']=$email;
            if ($this->where(array('id' => $id))->save($data) !== false) {
                return true;
            } else {
                $this->error = '更新失败！';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 添加管理员
     * @param type $data
     * @return boolean
     */
    public function addUser($data) {
        if (empty($data)) {
            $this->error = '数据不能为空！';
            return false;
        }
        //检验数据
        $data = $this->create($data, 1);
        //dump($data);die;
        if ($data) {
            $data['reg_time']=time();
            $data['reg_ip']=get_client_ip();

            //生成随机认证码
            $data['verify'] = \Admin\Common\CommonController::genRandomString(6);
            $data['tuijiancode'] = \Admin\Common\CommonController::genNumberString(7);//推荐邀请码
            //利用认证码和明文密码加密得到加密后的
            $data['password'] = $this->encryption($data['username'], $data['password'], $data['verify']);
            $id = $this->add($data);
            if ($id) {
                return $id;
            } else {
                $this->error = '添加用户失败！';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 删除管理员
     * @param type $userId
     * @return boolean
     */
    public function delUser($userId) {
        $userId = (int) $userId;
        if (empty($userId)) {
            $this->error = '请指定需要删除的用户ID！';
            return false;
        }
        if (false !== $this->where(array('id' => $userId))->delete()) {
            M("member_info")->where(array("uid"=>$userId))->delete();
            M("account")->where(array("uid"=>$userId))->delete();
            M("account_log")->where(array("uid"=>$userId))->delete();
            M("integral")->where(array("uid"=>$userId))->delete();
            M("integral_log")->where(array("uid"=>$userId))->delete();
            $cart=M('cart')->where(array('uid'=>$userId))->find();
            M('cart')->where(array('uid'=>$userId))->delete();
            M('cartinfo')->where(array('cartid'=>$cart['id']))->delete();
            M("address")->where(array("uid"=>$userId))->delete();
            M("coupons_order")->where(array("uid"=>$userId))->delete();
            M("signlog")->where(array("uid"=>$userId))->delete();
            M("message")->where(array("tuid"=>$userId))->delete();
            M("evaluation")->where(array("uid"=>$userId))->delete();
            M("collect")->where(array("uid"=>$userId))->delete();
            M("recharge")->where(array("uid"=>$userId))->delete();
            M("feedback")->where(array("uid"=>$userId))->delete();
            M('oauth')->where(array('bind_uid'=>$userId))->delete();
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }
  /**
     * 根据提示符(username)和未加密的密码(密码为空时不参与验证)获取本地用户信息
     * @param type $identifier 为数字时，表示uid，其他为用户名
     * @param type $password 
     * @return 成功返回用户信息array()，否则返回布尔值false
     */
    public function getLocalAdminUser($identifier, $password = null) {
        if (empty($identifier)) {
            return false;
        }
        $map = array();
        if (is_int($identifier)) {
            $map['id'] = $identifier;
        } else {
            $map['username'] = $identifier;
        }
         
        $UserMode = D("User");
        $user = $UserMode->where($map)->find();
           
        if (!$user) {
            return false;
        }
        if ($password) {
            //验证本地密码是否正确
            if ($this->encryption($user['username'], $password, $user['verify']) != $user['password']) {
                return false;
            }
        }
        return $user;
    }
  
}

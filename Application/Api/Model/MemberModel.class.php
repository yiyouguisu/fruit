<?php
namespace Api\Model;
use Think\Model;
class MemberModel extends Model {

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'),
        array('username', '', '用户名已经存在！', 0, 'unique', 3),
        array('password', 'require', '密码不能为空！', 0, 'regex', 1),
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
        $verify =  \Api\Common\CommonController::genRandomString(6);
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
        $phone=$data['phone'];
        $username=$data['username'];
        unset($data['id']);
        unset($data['username']);
        unset($data['phone']);
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
                $data['verify'] = \Api\Common\CommonController::genRandomString(6);
                //进行加密
                $pass = $this->encryption($data['username'], $password, $data['verify']);
                $data['password'] = $pass;
            } else {
                unset($data['password']);
            }
            $data['username']=$username;
            $data['phone']=$phone;
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
            return false;
        }
        //检验数据
        $data = $this->create($data, 1);
        if ($data) {
            $data['reg_time']=time();
            $data['reg_ip']=get_client_ip();

            //生成随机认证码
            $data['verify'] = \Api\Common\CommonController::genRandomString(6);
            $data['tuijiancode'] = \Api\Common\CommonController::genNumberString(7);//推荐邀请码
            //利用认证码和明文密码加密得到加密后的
            $data['password'] = $this->encryption($data['username'], $data['password'], $data['verify']);
            $id = $this->add($data);
            if ($id) {
                M("member_info")->add(array("uid"=>$id));
                M("account")->add(array("uid"=>$id));
                M("integral")->add(array("uid"=>$id));
                return $id;
            } else {
                return false;
            }
        } else {
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
            $map['username|phone'] = $identifier;
        }
        $UserMode =D("Member");
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

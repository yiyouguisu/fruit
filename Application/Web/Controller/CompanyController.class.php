<?php

namespace Web\Controller;

use Web\Common\CommonController;

class CompanyController extends CommonController {
    
    //绑定企业帐号
    public function bind(){
        $uid = intval(trim(session('uid')));
        $companyid = $_POST['companyid'];
        $company=M('company')->where(array('companynumber'=>$companyid))->find();
        if ($_POST) {
            if (D("company_member")->create()) {
                D("company_member")->inputtime = time();
                D("company_member")->companyid = $company['id'];
                D("company_member")->uid =$uid;
                $id = D("company_member")->add();
                if (!empty($id)) {
                    //echo "<SCRIPT language=JavaScript>alert('申请成功！');location.href='Web/Public/index';</SCRIPT>";
                    //header("Content-type: text/html; charset=utf-8");
                    $this->success("申请成功",U('Web/Index/index'));
                } else {
                    $this->error("申请失败！");
                }
            } else {
                $this->error(D("company_member")->getError());
            }
        }
        $this->display();
    }

    //合作申请
    public function apply(){
        $uid = intval(trim(session('uid')));
        if ($_POST) {
            if (D("cooperation")->create()) {
                D("cooperation")->inputtime = time();
                D("cooperation")->uid =$uid;
                $id = D("cooperation")->add();
                if (!empty($id)) {
                    //echo "<SCRIPT language=JavaScript>alert('申请成功！');location.href='Web/Public/index';</SCRIPT>";
                    //header("Content-type: text/html; charset=utf-8");
                    $this->success("申请成功",U('Web/Index/index'));
                } else {
                    $this->error("申请失败！");
                }
            } else {
                $this->error(D("cooperation")->getError());
            }
        }
        $this->display();
    }
}
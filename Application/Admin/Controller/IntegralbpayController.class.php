<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class IntegralbpayController extends CommonController {

     
    /**
     * 积分列表
     * 
     */
    public function index() {
        $search = I('post.search');
        $where = array();
        if (!empty($search)) {
             
            $start_integral = I('post.start_integral');
            if (!empty($start_integral)) {
                $start_integral = intval($start_integral);
                $where["useintegral"] = array("EGT", $start_integral);
            }
          
            $end_integral = I('post.end_integral');
            if (!empty($end_integral)) {
                $end_integral = intval($end_integral);
                $where["useintegral"] = array("ELT", $end_integral);
            }
            if ($end_integral > 0 && $start_integral > 0) {
                $where['useintegral'] = array(array('EGT', $start_integral), array('ELT', $end_integral));
            }
            //搜索字段
            $searchtype = I('searchtype');
            $searchtype = intval($searchtype);
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 0) {
                    $select["username"] = array("LIKE", "%" . $keyword . "%");
                    $uids=M('member')->where($select)->getField("id",true);
                    $where['uid']=array('in',$uids);
                }elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("Integralbpay")->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M("Integralbpay")->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
        foreach ($data as $key => $value) {
            # code...
            $data[$key]['username']=M('Member')->where('id=' . $value['uid'])->getField("username");
            $data[$key]['sex']=M('Member')->where('id=' . $value['uid'])->getField("sex");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

    /**
     *  修改积分
     * @author 
     */
    public function edit() {
        if (IS_POST) {
            $step=I('addintegral');
            $integral= M("Integralbpay")->where('id=' . $_POST['id'])->find();
            $id = M("integral")->where('id=' . $_POST['id'])->save(array(
                'useintegral'=>$integral['useintegral'] + $step,
                'giveintegral'=>$integral['giveintegral'] + $step,
                'totalintegral'=>$integral['totalintegral'] + $step
            ));
            if(!empty($id)){
                $log['type']=0;
                $log['uid']=$_POST['id'];
                $log['content']="管理员" . $_SESSION['user'] . "赠送" . $step . "积分";
                $log['useintegral']=$integral['useintegral'] + $step;
                $log['totalintegral']=$integral['totalintegral'] + $step;
                $log['inputtime']=time();
                $id=M('integrallog')->add($log);
                
                M("message")->add(array(
                    'uid'=>$_POST['id'],
                    'title'=>"系统赠送积分",
                    'valname'=>"giveintegral",
                    'value'=>$_POST['id'],
                    'content'=>"为了感谢您的支持和努力，您获得运营团队赠送的" . $step . "积分！当前可用积分为:".$log['useintegral'],
                    'inputtime'=>time()
                ));
                $user1=M('member')->where("id=" . $_POST['id'])->find();
                $c="为了感谢您的支持和努力，您获得运营团队赠送的" . $step . "积分！当前可用积分为:".$log['useintegral'];
                    
                $sms=json_encode(array('phone'=>$user1['phone'],'content'=>$c,'uid'=>$_POST['id']));
                $re= \Api\Common\CommonController::sendsms($sms);
                $this->success("赠送积分成功！", U("Admin/integral/index"));
            }else{
                $this->error("赠送积分失败！", U("Admin/integral/index"));
            }
        } else {
            $data = D("integral")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $username=M('member')->where('id=' . $data['uid'])->getField("username");
            $this->assign("username", $username);
            $this->display();
        }
    }

    public function log() {
        $search = I('post.search');
        $where = array();
        $where['uid']=I('id');
        if (!empty($search)) {
             
            $start_integral = I('post.start_integral');
            if (!empty($start_integral)) {
                $start_integral = intval($start_integral);
                $where["useintegral"] = array("EGT", $start_integral);
            }
          
            $end_integral = I('post.end_integral');
            if (!empty($end_integral)) {
                $end_integral = intval($end_integral);
                $where["useintegral"] = array("ELT", $end_integral);
            }
            if ($end_integral > 0 && $start_integral > 0) {
                $where['useintegral'] = array(array('EGT', $start_integral), array('ELT', $end_integral));
            }
          // //认证
          //   $type= I('post.type', null, 'intval');
          //   if (!empty($type)) {
          //       $where["type"] = array("EQ", $type);
          //   }
            //搜索字段
            $searchtype = I('searchtype');
            $searchtype = intval($searchtype);
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                if ($searchtype == 0) {
                    $select["username"] = array("LIKE", "%" . $keyword . "%");
                    $uids=M('member')->where($select)->getField("id",true);
                    $where['uid']=array('in',$uids);
                }elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("integrallog")->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M("integrallog")->where($where)->order(array('id'=>desc))->limit($page->firstRow . ',' . $page->listRows)->select();
        foreach ($data as $key => $value) {
            # code...
            $data[$key]['username']=M('Member')->where('id=' . $value['uid'])->getField("username");
            $data[$key]['sex']=M('Member')->where('id=' . $value['uid'])->getField("sex");
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }
    /**
     *  删除
     */
    public function dellog() {
        $id = $_GET['id'];
        if (D("integrallog")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

}
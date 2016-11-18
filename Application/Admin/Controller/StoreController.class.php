<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class StoreController extends CommonController {

    public function _initialize(){
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
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
            // //栏目
            // $catid = I('get.catid', null, 'intval');
            // if (!empty($catid)) {
            //     $where["catid"] = array("EQ", $catid);
            // }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'content', 'username');
                if ($searchtype < 3) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 3) {
                    $where["id"] = array("EQ", (int) $keyword);
                }
            }
            //状态
            $status = $_GET["status"];
            if ($status != "" && $status != null) {
                $where["status"] = array("EQ", $status);
            }
        }
    
        $count = D("Store")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Store")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("listorder" => "desc","id" => "desc"))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }

    /**
     * 编辑连锁店
     */
    public function edit() {
        if ($_POST) {
            $Map=A("Api/Map");
            $location=$Map->get_position_complex($_POST['area'],$_POST['address']);
            if (D("Store")->create()) {
                D("Store")->updatetime = time();
                D("Store")->lng = $location['lng'];
                D("Store")->lat = $location['lat'];
                D("Store")->servicearea = ','.implode(",", $_POST["servicearea"]).',';
                $id = D("Store")->save();
                if (!empty($id)) {
                    $member['username']=$_POST['loginname'];
                    $member['password']=$_POST['password'];
                    $member['id']=$_POST['uid'];
                    D("user")->editUser($member);
                    $this->success("修改店铺成功！", U("Admin/Store/index"));
                } else {
                    $this->error("修改店铺失败！");
                }
            } else {
                $this->error(D("Store")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("店铺ID参数错误");
            }
            $data=D("Store")->where("id=".$id)->find();
            $data['servicearea']=trim($data['servicearea'],',');
            $this->assign("data", $data);
            $storeuser=D("user")->where("id=".$data['uid'])->find();
            $this->assign("storeuser", $storeuser);


            $this->display();
        }
        
    }

    /*
     * 添加连锁店
     */

    public function add() {
        if ($_POST) {
            $Map=A("Api/Map");
            $location=$Map->get_position_complex($_POST['area'],$_POST['address']);
            if (D("Store")->create()) {
                D("Store")->lng = $location['lng'];
                D("Store")->lat = $location['lat'];
                D("Store")->servicearea = ','.implode(",", $_POST["servicearea"]).',';
                D("Store")->inputtime = time();
                D("Store")->status = 2;
                D("Store")->verify_user=$_SESSION['user'];
                D("Store")->verify_time = time();
                $id = D("Store")->add();
                if ($id) {
                    $member['username']=$_POST['loginname'];
                    $member['password']=$_POST['password'];
                    $member['role']=3;
                    $member['group_id']=3;
                    $member['storeid']=$id;
                    $uid=D("user")->addUser($member);
                    if($uid){
                        M('Store')->where(array('id'=>$id))->setField("uid",$uid);
                    }
                    M("store_cate")->add(array("storeid"=>$id));
                    $this->success("新增店铺成功！", U("Admin/Store/index"));
                } else {
                    $this->error("新增店铺失败！");
                }
            } else {
                $this->error(D("Store")->getError());
            }
        } else {
            $data=M('store_apply')->where('id=' . I('applyid'))->find();
            $this->assign("data",$data);
            $this->display();
        }
    }

    /*
     * 操作判断
     */

    public function action() {
        $submit = trim($_POST["submit"]);
        if ($submit == "listorder") {
            $this->listorder();
        } elseif ($submit == "del") {
            $this->del();
        } elseif ($submit == "pushs") {
            $this->pushs();
        } elseif ($submit == "unpushs") {
            $this->unpushs();
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (D("Store")->delete($id)) {
            $this->success("删除连锁店成功！");
        } else {
            $this->error("删除连锁店失败！");
        }
    }

    /*
     * 删除连锁店
     */

    public function del() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Store")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    public function review(){
        if (IS_POST) {
            $status=I('status');
            $data=M('store')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('store')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功", U("Admin/store/index"));
            }elseif($id>0&&$status==3){
                $this->success("审核成功！", U("Admin/store/index"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id = I('get.id', null, 'intval');
            $data = D("store")->where("id=" . $id)->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    /*
     * 连锁店推荐
     */

    public function pushs() {
        $data['type'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Store")->where(array("id" => $id))->save($data);
            }
            $this->success("推荐成功！");
        } else {
            $this->error("推荐成功！");
        }
    }

    /*
     * 连锁店推荐
     */

    public function unpushs() {
        $data['type'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Store")->where(array("id" => $id))->save($data);
            }
            $this->success("取消推荐成功！");
        } else {
            $this->error("取消推荐失败！");
        }
    }

    /*
     * 连锁店排序
     */

    public function listorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Store")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Store")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    public function owner(){
        if ($_POST) {
            $location=getposition($_POST['area'],$_POST['address']);
            if (D("Store")->create()) {
                D("Store")->updatetime = time();
                D("Store")->lng = $location['lng'];
                D("Store")->lat = $location['lat'];
                D("Store")->servicearea = implode(",", $_POST["servicearea"]);
                $id = D("Store")->save();
                if (!empty($id)) {
                    $member['username']=$_POST['loginname'];
                    $member['password']=$_POST['password'];
                    $member['id']=$_POST['uid'];
                    D("user")->editUser($member);
                    $this->success("修改店铺成功！", U("Admin/Store/index"));
                } else {
                    $this->error("修改店铺失败！");
                }
            } else {
                $this->error(D("Store")->getError());
            }
        } else {
            $storeid=$_SESSION['storeid'];
            $data=D("Store")->where("id=".$storeid)->find();
            $this->assign("data", $data);
            $storeuser=D("user")->where("id=".$data['uid'])->find();
            $this->assign("storeuser", $storeuser);

            $this->display();
        }
    
    }
    public function packing() {
        $search = I('post.search');
        $where = array();
        $where['role']=2;
        $where['storeid']=$this->storeid;
        if (!empty($search)) {
             //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["reg_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["reg_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
          //性别
            $sex= I('post.sex', null, 'intval');
            if (!empty($sex)) {
                $where["sex"] = array("EQ", $sex);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $type_array = array('username','realname','email','phone');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("User")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("User")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("sex", $sex);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

     /**
     *  添加
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function packingadd() {
        if (IS_POST) {
            if (D("User")->addUser($_POST)) {
                $this->success("添加包装员成功！", U("Admin/Store/packing"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
    /**
     *  删除
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function packingdelete() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        //执行删除
        if (D("User")->delUser($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(D("User")->getError());
        }
    }

    /**
     *  编辑
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function packingedit() {
        if (IS_POST) {
            if (false !== D("User")->editUser($_POST)) {
                $this->success("更新成功！", U("Admin/Store/packing"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $data = D("User")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
    public function financial() {
        $search = I('post.search');
        $where = array();
        $where['role']=4;
        $where['storeid']=$this->storeid;
        if (!empty($search)) {
             //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["reg_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["reg_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
          //性别
            $sex= I('post.sex', null, 'intval');
            if (!empty($sex)) {
                $where["sex"] = array("EQ", $sex);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $type_array = array('username','realname','email','phone');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("User")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("User")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("sex", $sex);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

    /**
     *  添加
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function financialadd() {
        if (IS_POST) {
            if (D("User")->addUser($_POST)) {
                $this->success("添加财务员成功！", U("Admin/Store/financial"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
    /**
     *  删除
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function financialdelete() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        //执行删除
        if (D("User")->delUser($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(D("User")->getError());
        }
    }

    /**
     *  编辑
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function financialedit() {
        if (IS_POST) {
            if (false !== D("User")->editUser($_POST)) {
                $this->success("更新成功！", U("Admin/Store/financial"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $data = D("User")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
    public function couriermap(){
        $store=M('store')->where(array('id'=>$this->storeid))->find();
        $this->assign("store", $store);

        $runer=M('store_member a')->join("join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid))->select();
        $a = $store['lng'] . "|" . $store['lat'];
        $markerArrStr="[{ title: '".$store['title']."', content: \"门店所在位置\", point: '".$a."'}";
        foreach ($runer as $key => $value) {
            # code...$
            $runer[$key]['ordernum']=M('order a')->join("left join zz_order_time b on a.orderid=b.orderid")->where(array('a.ruid'=>$value['ruid'],'b.delivery_status'=>1))->count();
            $ordernum[]=$runer[$key]['ordernum'];
            $lastposition=M('runerposition')->where(array('uid'=>$value['ruid']))->getField("lastposition");
            $position=$store['lat'].",".$store['lng'];
            $Map=A("Api/Map");
            $distanceinfo=$Map->get_distance_baidu("driving",$position,$lastposition);
            $runer[$key]['distance']=$distanceinfo['distance']['value']/1000;
            $runer[$key]['lastposition']=$lastposition;
            $distance[]=$distanceinfo['distance']['value']/1000;
        }

        array_multisort($ordernum, SORT_ASC, $distance, SORT_ASC, $runer); 
        foreach ($runer as $value) {
            # code...$
            
            if(!empty($value['lastposition'])){
                $lastpositions=explode(",", $value['lastposition']);
                $a = $lastpositions[1] . "|" . $lastpositions[0];
                $markerArrStr.=",{ title: '".$value['realname']."', content: \"距离门店   {$runer[$key]['distance']}千米<br/>有{$runer[$key]['ordernum']}笔订单在派送\", point: '".$a."'}";
            }
            
        }
        $markerArrStr.="]";
        $this->assign("markerArrStr", $markerArrStr);
        $this->display();
    }

    public function courier() {
        $search = I('post.search');
        $where = array();
        $where['b.group_id']=2;
        $where['a.storeid']=$this->storeid;
        if (!empty($search)) {
             //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["b.reg_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["b.reg_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['b.reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            //性别
            $sex= I('post.sex', null, 'intval');
            if (!empty($sex)) {
                $where["b.sex"] = array("EQ", $sex);
            }
            $isleader = $_POST['isleader'];
            if ($isleader!='') {
                $where["b.isleader"] = array("EQ", $isleader);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $type_array = array('b.username','b.realname','b.email','b.phone');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("store_member a")->join("left join zz_Member b on a.ruid=b.id")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("store_member a")->join("left join zz_Member b on a.ruid=b.id")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->field('b.*')->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("sex", $sex);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

    /**
     *  添加
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function courieradd() {
        if (IS_POST) {
            $id=D("member")->addUser($_POST);
            if ($id) {
                M('store_member')->add(array(
                    'storeid'=>$this->storeid,
                    'ruid'=>$id,
                    'inputtime'=>time()
                    ));
                $this->success("添加配送员成功！", U("Admin/Store/courier"));
            } else {
                $this->error(D("member")->getError());
            }
        } else {
            $leader=M('store_member a')->join("left join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid,'b.isleader'=>1))->order(array('b.id'=>'desc'))->field('b.id,b.username')->select();
            $this->assign("leader",$leader);
            $this->display();
        }
    }
    /**
     *  删除
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function courierdel() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        //执行删除
        if (D("member")->delUser($id)) {
            M('store_member')->where(array('storeid'=>$this->storeid,'ruid'=>$id))->delete();
            $this->success("删除成功！");
        } else {
            $this->error(D("member")->getError());
        }
    }

    /**
     *  编辑
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function courieredit() {
        if (IS_POST) {
            if (false !== D("member")->editUser($_POST)) {
                $this->success("更新成功！", U("Admin/Store/courier"));
            } else {
                $this->error(D("member")->getError());
            }
        } else {
            $data = D("member")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $leader=M('store_member a')->join("left join zz_member b on a.ruid=b.id")->where(array('a.storeid'=>$this->storeid,'b.isleader'=>1))->order(array('b.id'=>'desc'))->field('b.id,b.username')->select();
            $this->assign("leader",$leader);
            $this->display();
        }
    }
    public function ajax_checkservicearea() {
        $ret=$_POST; 
        $servicearea=trim($ret['servicearea']);

        $where['uid']=array('neq',$this->userid);
        $where['servicearea']=array('like',"%,".$servicearea.",%");
        $result=M('Store')->where($where)->find();
        if($result){
            $data['status']=0;
            $data['msg']="该区域已被签约";
            $this->ajaxReturn($data,"json");
        }else{
            $data['status']=1;
            $data['msg']="该区域未被签约";
            $this->ajaxReturn($data,"json");
        }
    }
    public function stock() {
        $search = I('post.search');
        $where = array();
        $where['role']=6;
        $where['storeid']=$this->storeid;
        if (!empty($search)) {
             //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where["reg_time"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["reg_time"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['reg_time'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
          //性别
            $sex= I('post.sex', null, 'intval');
            if (!empty($sex)) {
                $where["sex"] = array("EQ", $sex);
            }
            //搜索字段
            $searchtype = I('post.searchtype', null, 'intval');
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $type_array = array('username','realname','email','phone');
                if ($searchtype < 4) {
                    $searchtype = $type_array[$searchtype];
                    $where[$searchtype] = array("LIKE", "%{$keyword}%");
                } elseif ($searchtype == 4) {
                    $where["id"] = array("EQ", (int)$keyword);
                }
            }
        }
        $count = M("User")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = M("User")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->assign("sex", $sex);
        $this->assign("searchtype", $searchtype);
        $this->display();
    }

    /**
     *  添加
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function stockadd() {
        if (IS_POST) {
            if (D("User")->addUser($_POST)) {
                $this->success("添加财务员成功！", U("Admin/Store/stock"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
    /**
     *  删除
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function stockdelete() {
        $id = I('get.id');
        if (empty($id)) {
            $this->error("没有指定删除对象！");
        }
        //执行删除
        if (D("User")->delUser($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(D("User")->getError());
        }
    }

    /**
     *  编辑
     * @author oydm<389602549@qq.com>  time|20140507
     */
    public function stockedit() {
        if (IS_POST) {
            if (false !== D("User")->editUser($_POST)) {
                $this->success("更新成功！", U("Admin/Store/stock"));
            } else {
                $this->error(D("User")->getError());
            }
        } else {
            $data = D("User")->where(array("id" => $_GET["id"]))->find();
            $this->assign("data", $data);
            $store=M('store')->where(array('id'=>$this->storeid))->find();
            $this->assign("store", $store);
            $this->display();
        }
    }
}
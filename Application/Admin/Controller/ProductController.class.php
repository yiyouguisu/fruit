<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class ProductController extends CommonController {
    public function _initialize() {
        $productcate=F("productcate");
        if(!$productcate){
            $productcate=M('productcate')->order(array('listorder'=>'desc','id'=>'asc'))->select();
            F("productcate",$productcate);
        }
        $this->productcate=$productcate;

        $ProductUnitConfig=F("ProductUnitConfig",'',CACHEDATA_PATH);
        $this->ProductUnitConfig=$ProductUnitConfig;        
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    public function index() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=0;
        $where['type']=0;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function edit() {
        if ($_POST) {
            $product=M('product')->where(array('id'=>$_POST['id']))->find();
            if(empty($_POST['title'])){
                $this->error("请填写商品名称");
            }
            if(empty($_POST['subcatid'])){
                $this->error("请选择商品分类");
            }
            if(empty($_POST['productnumber'])){
                $this->error("请输入产品编号");
            }elseif($product['productnumber']!=$_POST['productnumber']){
                $checkstatus=M('product')->where(array('productnumber'=>$_POST['productnumber']))->find();
                if($checkstatus){
                    $this->error("产品编号已存在"); 
                }
            }
            if(empty($_POST['brand'])){
                $this->error("请填写产品品牌");
            }
            if(empty($_POST['standard'])){
                $this->error("请填写产品规格");
            }
            // if(empty($_POST['storehouse'])){
            //     $this->error("请选择产品仓库");
            // }
            
            foreach ($this->productcate as $value) {
                # code...
                if($value['id']==$_POST['subcatid']){
                    $catid=$value['parentid'];
                }
            }
            $imglist=  implode("|", $_POST["imglist"]);
            $backimglist=  implode("|", $_POST["backimglist"]);
            if (D("Product")->create()) {
                D("Product")->catid = $catid;
                D("Product")->imglist = $imglist;
                D("Product")->backimglist = $backimglist;
                D("Product")->updatetime = time();
                $id = D("Product")->save();
                if (!empty($id)) {
                    $this->success("修改产品成功！", U("Admin/Product/index"));
                } else {
                    $this->error("修改产品失败！");
                }
            } else {
                $this->error(D("Product")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("产品ID参数错误");
            }
            $data=D("Product")->where("id=".$id)->find();
            $this->assign("data", $data);
            $tree = new \Think\Tree();
            $catid = $data['subcatid'];
            $result = $this->productcate;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
                if ($r['parentid'] == '0') {
                    $r['disabled'] = "disabled";
                } else {
                    $r['disabled'] = "";
                }
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
            $tree->init($array);
            $select_Productcates = $tree->get_tree(0, $str);
            $this->assign("category", $select_Productcates);
            
            $imglist=  array_filter(explode("|", $data["imglist"]));
            $this->assign("imglist", $imglist);
            $backimglist=  array_filter(explode("|", $data["backimglist"]));
            $this->assign("backimglist", $backimglist);
            $this->assign("ProductUnitConfig",$this->ProductUnitConfig);
            $this->display();
        }
        
    }
    public function add() {
        if ($_POST) {
            if(empty($_POST['title'])){
                $this->error("请填写商品名称");
            }
            if(empty($_POST['subcatid'])){
                $this->error("请选择商品分类");
            }
            if(empty($_POST['productnumber'])){
                $this->error("请输入产品编号");
            }else{
                $checkstatus=M('product')->where(array('productnumber'=>$_POST['productnumber']))->find();
                if($checkstatus){
                    $this->error("产品编号已存在"); 
                }
            }
            if(empty($_POST['brand'])){
                $this->error("请填写产品品牌");
            }
            if(empty($_POST['standard'])){
                $this->error("请填写产品规格");
            }
            // if(empty($_POST['storehouse'])){
            //     $this->error("请选择产品仓库");
            // }
            foreach ($this->productcate as $value) {
                # code...
                if($value['id']==$_POST['subcatid']){
                    $catid=$value['parentid'];
                }
            }
            $imglist=  implode("|", $_POST["imglist"]);
            $backimglist=  implode("|", $_POST["backimglist"]);
            if (D("Product")->create()) {
                D("Product")->catid = $catid;
                D("Product")->imglist = $imglist;
                D("Product")->backimglist = $backimglist;
                D("Product")->inputtime = time();
                D("Product")->shelvestime = time();
                D("Product")->status = 2;
                D("Product")->verify_user=$_SESSION['user'];
                D("Product")->verify_time = time();
                $id = D("Product")->add();
                if (!empty($id)) {
                    $this->success("新增产品成功！", U("Admin/Product/index"));
                } else {
                    $this->error("新增产品失败！");
                }
            } else {
                $this->error(D("Product")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $catid = $data['subcatid'];
            $result =$this->productcate;
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
                if ($r['parentid'] == '0') {
                    $r['disabled'] = "disabled";
                } else {
                    $r['disabled'] = "";
                }
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
            $tree->init($array);
            $select_Productcates = $tree->get_tree(0, $str);
            $this->assign("category", $select_Productcates);

            $this->assign("ProductUnitConfig",$this->ProductUnitConfig);
            $this->display();
        }
    }
    public function ajax_getcategory(){
        $data=M('store_dishcate')->where(array('storeid'=>$_POST['storeid']))->select();
        echo json_encode($data);
    }
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
        } elseif ($submit == "off") {
            $this->off();
        } elseif ($submit == "unoff") {
            $this->unoff();
        } elseif ($submit == "searchlistorder") {
            $this->searchlistorder();
        } elseif ($submit == "searchdel") {
            $this->searchdel();
        } elseif ($submit == "outs") {
            $this->outs();
        } elseif ($submit == "unouts") {
            $this->unouts();
        }
    }
    public function delete() {
        $id = $_GET['id'];
        $did=M("Product")->where(array('id'=>$id))->save(array(
                    'isdel'=>1,
                    'deletetime'=>time()
                    ));
        if ($did) {
            $this->success("删除产品成功！");
        } else {
            $this->error("删除产品失败！");
        }
    }
    public function del() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array('id'=>$id))->save(array(
                    'isdel'=>1,
                    'deletetime'=>time()
                    ));
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function review(){
        if (IS_POST) {
            $status=I('status');
            $data=M('product')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('product')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功", U("Admin/Product/index"));
            }elseif($id>0&&$status==3){
                $this->success("审核成功！", U("Admin/Product/index"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id = I('get.id', null, 'intval');
            $data = D("product")->where("id=" . $id)->find();
            $data["catname"] = getsnav($data['catid']) . '-' . D("store_dishcate")->where("id=" . $data["subcatid"])->getField("catname");
            $this->assign("data", $data);
            $this->display();
        }
    }
    public function pushs() {
        $data['isindex'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("置顶成功！");
        } else {
            $this->error("置顶成功！");
        }
    }
    public function unpushs() {
        $data['isindex'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("取消置顶成功！");
        } else {
            $this->error("取消置顶失败！");
        }
    }
    public function off() {
        $data['isoff'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("下架成功！");
        } else {
            $this->error("下架成功！");
        }
    }
    public function unoff() {
        $data['isoff'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("取消下架成功！");
        } else {
            $this->error("取消下架失败！");
        }
    }
    public function listorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Product")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Product")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    public function searchlistorder() {
        $listorders = $_POST['listorders'];
        $pk = D("search_keyword")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("search_keyword")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    public function searchdelete() {
        $id = $_GET['id'];
        if (D("search_keyword")->delete($id)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function searchdel() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("search_keyword")->delete($id);
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function product() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=array('neq',0);
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
            
        }

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $catid = $_GET['subcatid'];
        $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
        $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function pedit() {
        if ($_POST) {
            $product=M('product')->where(array('id'=>$_POST['id']))->find();
            if(empty($_POST['title'])){
                $this->error("请填写商品名称");
            }
            if(empty($_POST['subcatid'])){
                $this->error("请选择商品分类");
            }
            if(empty($_POST['productnumber'])){
                $this->error("请输入产品编号");
            }elseif($product['productnumber']!=$_POST['productnumber']){
                $checkstatus=M('product')->where(array('productnumber'=>$_POST['productnumber']))->find();
                if($checkstatus){
                    $this->error("产品编号已存在"); 
                }
            }
            if(empty($_POST['brand'])){
                $this->error("请填写产品品牌");
            }
            if(empty($_POST['standard'])){
                $this->error("请填写产品规格");
            }
            if(empty($_POST['storehouse'])){
                $this->error("请选择产品仓库");
            }
            foreach ($this->productcate as $value) {
                # code...
                if($value['id']==$_POST['subcatid']){
                    $catid=$value['parentid'];
                }
            }
            $imglist=  implode("|", $_POST["imglist"]);
            $backimglist=  implode("|", $_POST["backimglist"]);
            if (D("Product")->create()) {
                D("Product")->catid = $catid;
                D("Product")->imglist = $imglist;
                D("Product")->backimglist = $backimglist;
                D("Product")->expiretime = strtotime($_POST['expiretime']);
                D("Product")->selltime = strtotime($_POST['selltime']);
                D("Product")->updatetime = time();
                $id = D("Product")->save();
                if (!empty($id)) {
                    $this->success("修改产品成功！", U("Admin/Product/product"));
                } else {
                    $this->error("修改产品失败！");
                }
            } else {
                $this->error(D("Product")->getError());
            }
        } else {
            $id= I('get.id', null, 'intval');
            if (empty($id)) {
                $this->error("产品ID参数错误");
            }
            $data=D("Product")->where("id=".$id)->find();
            $data['expiretime']=date("Y-m-d H:i:s",$data['expiretime']);
            $data['selltime']=date("Y-m-d H:i:s",$data['selltime']);
            $this->assign("data", $data);
            $tree = new \Think\Tree();
            $catid = $data['subcatid'];
            $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
            $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
                if ($r['parentid'] == '0') {
                    $r['disabled'] = "disabled";
                } else {
                    $r['disabled'] = "";
                }
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
            $tree->init($array);
            $select_Productcates = $tree->get_tree(0, $str);
            $this->assign("category", $select_Productcates);
            
            $imglist=  array_filter(explode("|", $data["imglist"]));
            $this->assign("imglist", $imglist);
            $backimglist=  array_filter(explode("|", $data["backimglist"]));
            $this->assign("backimglist", $backimglist);
            $this->assign("ProductUnitConfig",$this->ProductUnitConfig);
            $storehouse=M('storehouse')->where(array('storeid'=>$this->storeid,'status'=>1))->order(array('id'=>'desc'))->select();
            $this->assign("storehouse",$storehouse);
            $this->display();
        }
        
    }
    public function padd() {
        if ($_POST) {
            if(empty($_POST['title'])){
                $this->error("请填写商品名称");
            }
            if(empty($_POST['subcatid'])){
                $this->error("请选择商品分类");
            }
            if(empty($_POST['productnumber'])){
                $this->error("请输入产品编号");
            }else{
                $checkstatus=M('product')->where(array('productnumber'=>$_POST['productnumber']))->find();
                if($checkstatus){
                    $this->error("产品编号已存在"); 
                }
            }
            if(empty($_POST['brand'])){
                $this->error("请填写产品品牌");
            }
            if(empty($_POST['standard'])){
                $this->error("请填写产品规格");
            }
            if(empty($_POST['storehouse'])){
                $this->error("请选择产品仓库");
            }
            foreach ($this->productcate as $value) {
                # code...
                if($value['id']==$_POST['subcatid']){
                    $catid=$value['parentid'];
                }
            }
            $imglist=  implode("|", $_POST["imglist"]);
            $backimglist=  implode("|", $_POST["backimglist"]);
            if (D("Product")->create()) {
                D("Product")->storeid=$this->storeid;
                D("Product")->catid = $catid;
                D("Product")->imglist = $imglist;
                D("Product")->backimglist = $backimglist;
                D("Product")->expiretime = strtotime($_POST['expiretime']);
                D("Product")->selltime = strtotime($_POST['selltime']);
                D("Product")->inputtime = time();
                D("Product")->shelvestime = time();
                D("Product")->status = 2;
                D("Product")->verify_user=$_SESSION['user'];
                D("Product")->verify_time = time();
                $id = D("Product")->add();
                //dump(D("Product")->_sql());die;
                if (!empty($id)) {
                    $this->success("新增产品成功！", U("Admin/Product/product"));
                } else {
                    $this->error("新增产品失败！");
                }
            } else {
                $this->error(D("Product")->getError());
            }
        } else {
            $tree = new \Think\Tree();
            $catid = $data['subcatid'];
            $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
            $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $catid ? 'selected' : '';
                if ($r['parentid'] == '0') {
                    $r['disabled'] = "disabled";
                } else {
                    $r['disabled'] = "";
                }
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
            $tree->init($array);
            $select_Productcates = $tree->get_tree(0, $str);
            $this->assign("category", $select_Productcates);

            $this->assign("ProductUnitConfig",$this->ProductUnitConfig);
            $storehouse=M('storehouse')->where(array('storeid'=>$this->storeid,'status'=>1))->order(array('id'=>'desc'))->select();
            $this->assign("storehouse",$storehouse);
            $this->display();
        }
    }
    public function paction() {
        $submit = trim($_POST["submit"]);
        if ($submit == "listorder") {
            $this->listorder();
        } elseif ($submit == "del") {
            $this->del();
        } elseif ($submit == "pushs") {
            $this->pushs();
        } elseif ($submit == "unpushs") {
            $this->unpushs();
        } elseif ($submit == "off") {
            $this->off();
        } elseif ($submit == "unoff") {
            $this->unoff();
        } elseif ($submit == "outs") {
            $this->outs();
        } elseif ($submit == "unouts") {
            $this->unouts();
        }
    }
    public function pdelete() {
        $id = $_GET['id'];
        $did=M("Product")->where(array('id'=>$id))->save(array(
                    'isdel'=>1,
                    'deletetime'=>time()
                    ));
        if ($did) {
            $this->success("删除产品成功！");
        } else {
            $this->error("删除产品失败！");
        }
    }
    public function pdel() {
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array('id'=>$id))->save(array(
                    'isdel'=>1,
                    'deletetime'=>time()
                    ));
            }
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function preview(){
        if (IS_POST) {
            $status=I('status');
            $data=M('product')->where('id=' . I('id'))->find();
            if($data['status']!=1){
                $this->error("不能重复审核！");
            }
            $id=M('product')->where('id=' . I('id'))->save(array(
                'status'=>$status,
                'verify_time' => time(),
                'verify_user' => $_SESSION['user'],
                'remark'=>I('remark')
            ));
            if($id>0&&$status==2){
                $this->success("审核成功", U("Admin/Product/index"));
            }elseif($id>0&&$status==3){
                $this->success("审核成功！", U("Admin/Product/index"));
            }elseif(!$id){
                $this->error("审核失败！");
            }
        } else {
            $id = I('get.id', null, 'intval');
            $data = D("product")->where("id=" . $id)->find();
            $data["catname"] = getsnav($data['catid']) . '-' . D("store_dishcate")->where("id=" . $data["subcatid"])->getField("catname");
            $this->assign("data", $data);
            $this->display();
        }
    }
    public function ppushs() {
        $data['isindex'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("置顶成功！");
        } else {
            $this->error("置顶成功！");
        }
    }
    public function punpushs() {
        $data['isindex'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("取消置顶成功！");
        } else {
            $this->error("取消置顶失败！");
        }
    }
    public function poff() {
        $data['isoff'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("下架成功！");
        } else {
            $this->error("下架成功！");
        }
    }
    public function punoff() {
        $data['isoff'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("取消下架成功！");
        } else {
            $this->error("取消下架失败！");
        }
    }
    public function plistorder() {
        $listorders = $_POST['listorders'];
        $pk = D("Product")->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $status = D("Product")->where(array($pk => $key))->save($data);
        }
        if ($status !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    public function storehouse() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=0;
        $where['type']=0;
        $where['isoff']=1;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function selling() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=0;
        $where['type']=0;
        $where['isoff']=0;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function outoff() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=0;
        $where['type']=0;
        $where['isout']=1;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function hot() {
        $search = I('get.search');
        $where = array();
        $where['a.storeid']=0;
        $where['a.type']=0;
        $where['a.isoff']=0;
        $where['a.isdel']=0;
        //$mapI['d.status']=5;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $mapI["c.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $mapI["c.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $mapI['c.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            $querytype=I('get.querytype');
            if(!empty($_GET['buynum'])){
                $where["itotalx.buynum"] = array($querytype, $_GET['buynum']);
            }
            
        }
        $sqlI = M("order_productinfo i")->field("i.pid,count(i.orderid) as buynum")
                ->join("left join zz_order c on i.orderid=c.orderid")
                ->join("left join zz_order_time d on i.orderid=d.orderid")
                ->where($mapI)
                ->group("i.pid")
                ->buildSql();
        $sqlM = M("order_productinfo i")->field("i.pid,sum(i.nums*i.price) as totalmoney")
                ->join("left join zz_order c on i.orderid=c.orderid")
                ->join("left join zz_order_time d on i.orderid=d.orderid")
                ->where($mapI)
                ->group("i.pid")
                ->buildSql();
        $count = D("Product a")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product a")
                ->join("left join ({$sqlI}) itotalx ON itotalx.pid = a.id")
                ->join("left join ({$sqlM}) itotaly ON itotaly.pid = a.id")
                ->where($where)
                ->limit($page->firstRow . ',' . $page->listRows)
                ->order(array("itotalx.buynum"=>'desc',"itotaly.totalmoney"=>'desc',"a.id" => "desc"))
                ->group("a.id")
                ->field("a.*,itotalx.buynum,itotaly.totalmoney")
                ->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        //dump(D('Product a')->_sql());die;
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function phot() {
        $search = I('get.search');
        $where = array();
        $where['a.storeid']=$this->storeid;
        $where['a.type']=array('neq',0);
        $where['a.isoff']=0;
        $where['a.isdel']=0;
        //$mapI['d.status']=5;
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $mapI["c.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $mapI["c.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $mapI['c.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            $querytype=I('get.querytype');
            if(!empty($_GET['buynum'])){
                $where["itotalx.buynum"] = array($querytype, $_GET['buynum']);
            }
            
        }
        $sqlI = M("order_productinfo i")->field("i.pid,count(i.orderid) as buynum")
                ->join("left join zz_order c on i.orderid=c.orderid")
                ->join("left join zz_order_time d on i.orderid=d.orderid")
                ->where($mapI)
                ->group("i.pid")
                ->buildSql();
        $sqlM = M("order_productinfo i")->field("i.pid,sum(i.nums*i.price) as totalmoney")
                ->join("left join zz_order c on i.orderid=c.orderid")
                ->join("left join zz_order_time d on i.orderid=d.orderid")
                ->where($mapI)
                ->group("i.pid")
                ->buildSql();
        $count = D("Product a")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product a")
                ->join("left join ({$sqlI}) itotalx ON itotalx.pid = a.id")
                ->join("left join ({$sqlM}) itotaly ON itotaly.pid = a.id")
                ->where($where)
                ->limit($page->firstRow . ',' . $page->listRows)
                ->order(array("itotalx.buynum"=>'desc',"itotaly.totalmoney"=>'desc',"a.id" => "desc"))
                ->group("a.id")
                ->field("a.*,itotalx.buynum,itotaly.totalmoney")
                ->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        //dump(D('Product a')->_sql());die;
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function search() {
        $search = I('get.search');
        $where = array();
        
        if (!empty($search)) {
            
            $querytype=I('get.querytype');
            if(!empty($_GET['hit'])){
                $where["hit"] = array($querytype, $_GET['hit']);
            }
            
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $where['keyword'] = array("LIKE", "%{$keyword}%");
            }
            
        }
        
        $count = D("search_keyword")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("search_keyword")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("listorder"=>'desc',"id" => "desc"))->select();
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $this->display();
    }
    public function pstorehouse() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=array('neq',0);
        $where['isoff']=1;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function pselling() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=array('neq',0);
        $where['isoff']=0;
        $where['isdel']=0;
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
            
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function poutoff() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=array('neq',0);
        $where['isout']=1;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $parentid = $_GET['subcatid'];
        $result = $this->productcate;
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function simple() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=1;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
            
        }

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $catid = $_GET['subcatid'];
        $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
        $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function book() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=3;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
            
        }

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $catid = $_GET['subcatid'];
        $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
        $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function group() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=2;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
            
        }

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $catid = $_GET['subcatid'];
        $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
        $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function weigh() {
        $search = I('get.search');
        $where = array();
        $where['storeid']=$this->storeid;
        $where['type']=4;
        $where['isdel']=0;
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
            $stocktype=I('get.stocktype');
            if(!empty($stocktype)){
                $where["stock"] = array($stocktype, $_GET['stock']);
            }
            $type=I('get.type');
            if(!empty($_GET['type'])){
                $where['type']=$type;
            }
            //栏目
            $subcatid = I('get.subcatid', null, 'intval');
            if (!empty($subcatid)) {
                $where["subcatid"] = array("EQ", $subcatid);
            }
            //搜索字段
            $searchtype = I('get.searchtype', null, 'intval');
            //搜索关键字
            $keyword = urldecode(I('get.keyword'));
            if (!empty($keyword)) {
                $type_array = array('title', 'info', 'username');
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
        
        $count = D("Product")->where($where)->count();
        $page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("Product")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('listorder'=>'desc','isindex'=>'desc','id'=>'asc'))->select();
        foreach ($data as $k => $r) {
            $data[$k]["sortitle"] = $this->str_cut($r["title"], 30);
            $data[$k]["catname"] = getsnav($r['subcatid']);
            
        }

        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);
        $tree = new \Think\Tree();
        $catid = $_GET['subcatid'];
        $store_cate=M('store_cate')->where(array('storeid'=>$this->storeid))->getField("catid");
        $result = M('productcate')->where(array('id'=>array('in',explode(",", $store_cate))))->order(array('listorder'=>'desc','id'=>'asc'))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $catid ? 'selected' : '';
            if ($r['parentid'] == '0') {
                $r['disabled'] = "disabled";
            } else {
                $r['disabled'] = "";
            }
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected \$disabled>\$spacer \$catname</option>";
        $tree->init($array);
        $select_Productcates = $tree->get_tree(0, $str);
        $this->assign("category", $select_Productcates);
        $this->assign("status", $status);
        $this->assign("searchtype", $searchtype);

        $this->display();
    }
    public function pbookorder() {
        $search = I('get.search');
        $where = array();
        $where['close_status']=0;
        $where['cancel_status']=0;
        $where['c.pid']=I('id');
        if(!empty($_SESSION['storeid'])){
            $where['storeid']=$this->storeid;
        }
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('get.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('get.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('get.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('get.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }

            $issend = I('get.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('get.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('get.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('get.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
        }

        $count = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->join("left join zz_order_productinfo c on a.orderid=c.orderid")->where($where)->count();
        $page = new \Think\Page($count, 12);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $data = D("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->join("left join zz_order_productinfo c on a.orderid=c.orderid")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("a.id" => "desc"))->select();
        foreach($data as $key => $value){
            $data[$key]['productinfo']= M('order_productinfo a')->join("zz_product b on a.pid=b.id")->field("a.*,b.title,b.unit,b.standard")->where(array('a.orderid'=>$value['orderid']))->select();
        }
        $show = $page->show();
        $this->assign("data", $data);
        $this->assign("Page", $show);

        $store = M("store")->where(array('status'=>2))->select();
        $this->assign("store", $store);
        $this->assign("storeid", $_SESSION['storeid']);
        $this->display();
    }
    public function out() {
        $id = $_GET['id'];
        $orderid = $_GET['orderid'];
        $did=M("Product")->where(array('id'=>$id))->save(array(
                    'isout'=>1,
                    'stock'=>0,
                    'updatetime'=>time()
                    ));
        if ($did!==false) {
            // $where=array();
            // $where['b.status']=2;
            // $where['b.package_status']=array('in','0,1');
            // $where['a.puid']=array('neq',0);
            // $where['b.delivery_status']=0;
            // $where['b.close_status']=0;
            // $where['b.cancel_status']=0;
            // $orderids=M("order a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->field("a.orderid")->select();
            // foreach ($orderids as $value)
            // {
            //     $orderidbox[]=$value['orderid'];
            // }
            // $where=array();
            // $orderids=M('order_productinfo')->where(array('pid'=>$id,'orderid'=>array('in',$orderidbox)))->distinct(true)->getField("orderid",true);
            
            // M('order_time')->where(array('orderid'=>array('in',$orderids)))->save(array('packageerror_status'=>1,'packageerror_time'=>time()));

            $this->success("设置缺货成功！");
        } else {
            $this->error("设置缺货失败！");
        }
    }
    public function doout(){
        $id = $_GET['id'];
        $orderid = $_GET['orderid'];
        $did=M('order_productinfo')->where(array('orderid'=>$orderid,'pid'=>$id))->setField("isdo",1);
        if ($did!==false) {

            $this->success("提交成功！");
        } else {
            $this->error("提交失败！");
        }
    }
    public function unout() {
        $id = $_GET['id'];
        $did=M("Product")->where(array('id'=>$id))->save(array(
                    'isout'=>0,
                    'updatetime'=>time()
                    ));
        if ($did) {
            $this->success("取消设置缺货成功！");
        } else {
            $this->error("取消设置缺货失败！");
        }
    }
    public function outs() {
        $data['isout'] = 1;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("设置缺货成功！");
        } else {
            $this->error("设置缺货成功！");
        }
    }
    public function unouts() {
        $data['isout'] = 0;
        if (IS_POST) {
            if (empty($_POST['ids'])) {
                $this->error("没有信息被选中！");
            }
            foreach ($_POST['ids'] as $id) {
                M("Product")->where(array("id" => $id))->save($data);
            }
            $this->success("取消设置缺货成功！");
        } else {
            $this->error("取消设置缺货失败！");
        }
    }
    public function ajax_getproduct(){
        if(IS_POST){
            $keyword=I('keyword');
            $num=I('num');
            if(empty($keyword)){
                $this->ajaxReturn(array('status'=>0,'msg'=>'请输入商品编号或商品id'),'json');
            }
            $product=M('product')->where(array('productnumber|id'=>$keyword))->find();
            if($product){
                if($product['isout']==1){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'商品缺货'),'json');
                }
                if($product['isoff']==1){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'商品已被下架了'),'json');
                }
                if($product['type']==3&&$product['selltime']<time()){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'商品已过期啦'),'json');
                }
                if($product['type']==2&&$product['expiretime']<time()){
                    exit(json_encode(array('code'=>-200,'msg'=>"商品已过期啦！！")));
                }
                if($product['stock']==0){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'商品正在补货中'),'json');
                }
                if($num>$product['stock']&&$product['stock']>0){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'商品库存不足'),'json');
                }
                $product['unit']=getunit($product['unit']);
                $this->ajaxReturn(array('status'=>1,'msg'=>'success','product'=>$product),'json');
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>'商品不存在'),'json');
            }
            
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'请求非法'),'json');
        }
    }
    public function ajax_unout() {
        $pid = $_POST['pid'];
        $stock = $_POST['stock'];
        $did=M("Product")->where(array('id'=>$pid))->save(array(
                    'isout'=>0,
                    'stock'=>$stock,
                    'updatetime'=>time()
                    ));
        if ($did) {
            $this->ajaxReturn(array('status'=>1,'msg'=>'取消设置缺货成功'),'json');
        } else {
            $this->ajaxReturn(array('status'=>0,'msg'=>'取消设置缺货失败'),'json');
        }
    }
}
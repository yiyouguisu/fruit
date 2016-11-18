<?php
namespace Admin\Controller;
use Admin\Common\CommonController;
class IndexController extends CommonController {
    /**
     * 后台框架
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function index() {
        $this->assign("SUBMENU_CONFIG", json_encode(D("Menu")->menu_json()));
        $this->display();
    }
    /**
     * 后台首页
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function main() {
        
        //服务器信息
        $info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );

        
        $this->assign('server_info', $info);

        $uid = $_SESSION["userid"];
        $username = M("user")->where(array("id" => $uid))->getField("username");
        $lastLogin = M("loginlog")->field('loginip,logintime')->where(array("username"=>$username))->order(array('loginid'=>"desc"))->find();
        if(empty($lastLogin)){
            $lastLogin['add_time'] = "首次登陆";    
            $lastLogin['area'] = "首次登陆";    
        }else{
            $lastLogin['add_time'] = $lastLogin['logintime'];   
            $lastLogin['area'] = ip2area($lastLogin['loginip']);    
        }
        $this->assign("lastLogin",$lastLogin);
        $where=array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$_SESSION['storeid'];
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['a.puid']=0;
        $where['_string']="(a.paystyle=2 and b.pay_status=0 and ((a.ordertype=2 and a.yes_money_total>=a.total)or a.ordertype!=2)) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        $waitdistribute=M("order")->alias("a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitdistribute",$waitdistribute);
        
        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$_SESSION['storeid'];
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['a.puid']=array('neq',0);
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $waitpackage=M("order")->alias("a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitpackage",$waitpackage);

        $where = array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$_SESSION['storeid'];
        }
        $where['b.status']=2;
        $where['b.package_status']=2;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $waitdelivery=M("order")->alias("a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitdelivery",$waitdelivery);

        $where=array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$_SESSION['storeid'];
        }
        $where['b.status']=2;
        $where['b.package_status']=0;
        $where['b.delivery_status']=0;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['a.puid']=0;
        $where['a.ordersource']=array('in','3,4');
        $where['_string']="(a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        $waitdistribute_third=M("order")->alias("a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitdistribute_third",$waitdistribute_third);

        $where=array();
        $where['status']=1;
        $waitreview_company = M("cooperation")->where($where)->count();
        $this->assign("waitreview_company",$waitreview_company);

        $where=array();
        if(!empty($_SESSION['storeid'])){
            $where['a.storeid']=$_SESSION['storeid'];
        }
        $where['b.status']=2;
        $where['b.close_status']=0;
        $where['b.cancel_status']=0;
        $where['b.error_status']=1;
        $waitcheckerror=M("order")->alias("a")->join("left join zz_order_time b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitcheckerror",$waitcheckerror);

        $where=array();
        if(!empty($_SESSION['storeid'])){
            $where['b.storeid']=$_SESSION['storeid'];
        }
        $where['a.status']=1;
        $waitcheckservice=M("order_feedback")->alias("a")->join("left join zz_order b on a.orderid=b.orderid")->where($where)->cache(true)->count();
        $this->assign("waitcheckservice",$waitcheckservice);
        
        $uid = $_SESSION["userid"];
        $User = M("user")->where(array("id" => $uid))->find();
        if($User['role']==1){
            $this->charts();
            //$this->weblog();
            $this->display();
        }else{
            $this->display("maindefault");
        }
        
    }
    public function charts(){
        $user=array();
        $simpleorder=array();
        $bookorder=array();
        $companyorder=array();
        $speedorder=array();
        $weighorder=array();
        $date=array();
        for($i=14;$i>=0;$i--){
            $time=strtotime("-". $i ." days");
            $_time=date("m-d",$time);
            $starttime=mktime(0,0,0,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
            $endtime=mktime(23,59,59,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
            $date[]=$_time;

            $where = array();
            $where['reg_time']=array(array('EGT', $starttime), array('ELT', $endtime));
            $where['group_id']=1;
            $usernums=M('member')->where($where)->cache(true)->count();
            $user[]=!empty($usernums)?$usernums:0;

            $where = array();
            $where['inputtime']=array(array('EGT', $starttime), array('ELT', $endtime));
            $where['ordertype']=1;
            $simpleordernums=M('order')->where($where)->cache(true)->count();
            $simpleorder[]=!empty($simpleordernums)?$simpleordernums:0;

            $where['ordertype']=2;
            $bookordernums=M('order')->where($where)->cache(true)->count();
            $bookorder[]=!empty($bookordernums)?$bookordernums:0;

            $where['ordertype']=3;
            $companyordernums=M('order')->where($where)->cache(true)->count();
            $companyorder[]=!empty($companyordernums)?$companyordernums:0;

            unset($where['ordertype']);
            $where['isspeed']=1;
            $speedordernums=M('order')->where($where)->cache(true)->count();
            $speedorder[]=!empty($speedordernums)?$speedordernums:0;

            unset($where['isspeed']);
            $where['ordertype']=1;
            $where['iscontainsweigh']=1;
            $weighordernums=M('order')->where($where)->cache(true)->count();
            $weighorder[]=!empty($weighordernums)?$weighordernums:0;
        }
        $_user=trim(implode(",",$user),",");
        $_simpleorder=trim(implode(",",$simpleorder),",");
        $_bookorder=trim(implode(",",$bookorder),",");
        $_companyorder=trim(implode(",",$companyorder),",");
        $_speedorder=trim(implode(",",$speedorder),",");
        $_weighorder=trim(implode(",",$weighorder),",");

        
        $this->assign("date", json_encode($date));
        $this->assign("user", $_user);
        $this->assign("simpleorder", $_simpleorder);
        $this->assign("bookorder", $_bookorder);
        $this->assign("companyorder", $_companyorder);
        $this->assign("speedorder", $_speedorder);
        $this->assign("weighorder", $_weighorder);
    }
    // public function charts1(){
    //     $user=array();
    //     $simpleorder=array();
    //     $bookorder=array();
    //     $companyorder=array();
    //     $speedorder=array();
    //     $weighorder=array();
    //     $date=array();

    //     $time=strtotime("-14 days");
    //     $starttime=mktime(0,0,0,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
        
    //     $where['reg_time']=array('EGT', $starttime));
    //     $where['group_id']=1;
    //     $sqlI=M('member')->field("date_format(FROM_UNIXTIME(`reg_time`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $usernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
    //     dump($usernum);die;
        
    //     $where = array();
    //     $where['inputtime']=array('EGT', $starttime));
    //     $where['ordertype']=1;
    //     $sqlI=M('order')->field("date_format(FROM_UNIXTIME(`inputtime`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $simpleordernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
       
    //     $where['ordertype']=2;
    //     $sqlI=M('order')->field("date_format(FROM_UNIXTIME(`inputtime`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $bookordernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
       
    //     $where['ordertype']=3;
    //     $sqlI=M('order')->field("date_format(FROM_UNIXTIME(`inputtime`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $companyordernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
       
    //     unset($where['ordertype']);
    //     $where['isspeed']=1;
    //     $sqlI=M('order')->field("date_format(FROM_UNIXTIME(`inputtime`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $speedordernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
       

    //     unset($where['isspeed']);
    //     $where['ordertype']=1;
    //     $where['iscontainsweigh']=1;
    //     $sqlI=M('order')->field("date_format(FROM_UNIXTIME(`inputtime`),'%Y-%m-%d') time")->where($where)->buildsql();
    //     $weighordernum=M()->table("{$sqlI}")->alias("a")->group("time")->order(array("time"=>'desc'))->getField("time,count(time) as num");
       

    //     for($i=14;$i>=0;$i--){
    //         $time=strtotime("-". $i ." days");
    //         $_time=date("m-d",$time);
    //         $date[]=$_time;
    //         $temptime=date("Y-m-d",$time);

    //         if(!isset($usernum[$temptime])){
    //             $user[]=0;
    //         }else{
    //             $user[]=(int)$usernum[$temptime];
    //         }
    //         if(!isset($simpleordernum[$temptime])){
    //             $simpleorder[]=0;
    //         }else{
    //             $simpleorder[]=(int)$simpleordernum[$temptime];
    //         }
    //         if(!isset($bookordernum[$temptime])){
    //             $bookorder[]=0;
    //         }else{
    //             $bookorder[]=(int)$bookordernum[$temptime];
    //         }
    //         if(!isset($companyordernum[$temptime])){
    //             $companyorder[]=0;
    //         }else{
    //             $companyorder[]=(int)$companyordernum[$temptime];
    //         }
    //         if(!isset($speedordernum[$temptime])){
    //             $speedorder[]=0;
    //         }else{
    //             $speedorder[]=(int)$speedordernum[$temptime];
    //         }
    //         if(!isset($weighordernum[$temptime])){
    //             $weighorder[]=0;
    //         }else{
    //             $weighorder[]=(int)$weighordernum[$temptime];
    //         }
            
    //     }
    //     $_user=trim(implode(",",$user),",");
    //     $_simpleorder=trim(implode(",",$simpleorder),",");
    //     $_bookorder=trim(implode(",",$bookorder),",");
    //     $_companyorder=trim(implode(",",$companyorder),",");
    //     $_speedorder=trim(implode(",",$speedorder),",");
    //     $_weighorder=trim(implode(",",$weighorder),",");

        
    //     $this->assign("date", json_encode($date));
    //     $this->assign("user", $_user);
    //     $this->assign("simpleorder", $_simpleorder);
    //     $this->assign("bookorder", $_bookorder);
    //     $this->assign("companyorder", $_companyorder);
    //     $this->assign("speedorder", $_speedorder);
    //     $this->assign("weighorder", $_weighorder);
    // }
  //   public function weblog(){
  //       $time=time();
  //       $starttime=mktime(0,0,0,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));
  //       $endtime=mktime(23,59,59,intval(date("m",$time)),intval(date("d",$time)),intval(date("Y",$time)));

  //       $where = array();
  //       $where['reg_time']=array(array('EGT', $starttime), array('ELT', $endtime));
  //       $where['group_id']=1;
  //       $usernums=M('member')->where($where)->count();
  //       $user=!empty($usernums)?$usernums:0;

  //       $where = array();
  //       $where['inputtime']=array(array('EGT', $starttime), array('ELT', $endtime));
  //       $where['ordertype']=1;
  //       $simpleorder_count=M('order')->where($where)->count();
  //       $simpleorder_money=M('order')->where($where)->sum("total");

  //       $where['ordertype']=2;
  //       $bookorder_count=M('order')->where($where)->count();
  //       $bookorder_money=M('order')->where($where)->sum("total");

  //       $where['ordertype']=3;
  //       $companyorder_count=M('order')->where($where)->count();
  //       $companyorder_money=M('order')->where($where)->sum("total");

  //       unset($where['ordertype']);
  //       $where['isspeed']=1;
  //       $speedorder_count=M('order')->where($where)->count();
  //       $speedorder_money=M('order')->where($where)->sum("total");

  //       unset($where['isspeed']);
  //       $where['ordertype']=1;
  //       $where['iscontainsweigh']=1;
  //       $weighorder_count=M('order')->where($where)->count();
  //       $weighorder_money=M('order')->where($where)->sum("total");

  //       $company=M("cooperation")->where(array('status'=>1,'inputtime'=>array(array('EGT', $starttime), array('ELT', $endtime))))->count();

  //       $where = array();
  //       $where['a.status']=5;
  //       $where['b.ordertype']=array("neq",3);
  //       $where['b.money']=array('gt',0);
  //       $where['a.bill_apply_status']=array("gt",0);
  //       $where['c.donetime']=array(array('EGT', $starttime), array('ELT', $endtime));
  //       $bill = M("order_time a")->join("left join zz_order b on a.orderid=b.orderid")->where($where)->count();

  //       $save=array();
		// $save['users'] = $user;
		// $save['simpleorder'] = floatval($simpleorder_money);
		// $save['bookorder'] = floatval($bookorder_money);
		// $save['companyorder'] = floatval($companyorder_money);
		// $save['speedorder'] = floatval($speedorder_money);
		// $save['weighorder'] = floatval($weighorder_money);
  //       $save['simpleorder_count'] = "(".intval($simpleorder_count)."单)";
		// $save['bookorder_count'] = "(".intval($bookorder_count)."单)";
		// $save['companyorder_count'] = "(".intval($companyorder_count)."单)";
		// $save['speedorder_count'] = "(".intval($speedorder_count)."单)";
		// $save['weighorder_count'] = "(".intval($weighorder_count)."单)";
		// $save['company'] = intval($company);
		// $save['bill'] = intval($bill);
		// $this->assign("weblog",$save);
  //   }
    /**
     * 菜单搜索
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function public_find() {
        $keyword = I('get.keyword');
        if (!$keyword) {
            $this->error("请输入需要搜索的关键词！");
        }
        
        $where = array();
        $where['name'] = array("LIKE", "%$keyword%");
        $where['status'] = array("EQ", 1);
        $where['type'] = array("EQ", 1);
        $data = M("Menu")->where($where)->select();
        $menuData = $menuName = array();
        $Module = F("Module");
        foreach ($data as $k => $v) {
            $menuData[ucwords($v['app'])][] = $v;
            $menuName[ucwords($v['app'])] = $Module[ucwords($v['app'])]['name'];
        }
        $this->assign("menuData", $menuData);
        $this->assign("menuName", $menuName);
        $this->assign("keyword", $keyword);
        $this->display();
    }
    
    /**
     * 缓存更新
     * @author oydm<389602549@qq.com>  time|20140421
     */
    public function public_cache() {
        if (isset($_GET['type'])) {
            $Dir = new \Think\Dir();
            $type = I('get.type');
            switch ($type) {
                case "site":
                    //删除缓存目录下的文件
                    $Dir->del(DATA_PATH);
                    $this->success("站点数据缓存清理成功！");
                    break;
                case "Atemplate":
                    //删除缓存目录下的文件
                    $Dir->del(RUNTIME_PATH);
                    $Dir->delDir(RUNTIME_PATH . "Cache/Admin/");
                    $Dir->delDir(RUNTIME_PATH . "Temp/");
                    $this->success("后台模板缓存清理成功！");
                    break;
                case "Htemplate":
                    //删除缓存目录下的文件
                    $Dir->del(RUNTIME_PATH);
                    $Dir->delDir(RUNTIME_PATH . "Cache/Home/");
                    $Dir->delDir(RUNTIME_PATH . "Temp/");
                    $this->success("前台模板缓存清理成功！");
                    break;
                case "Wtemplate":
                    //删除缓存目录下的文件
                    $Dir->del(RUNTIME_PATH);
                    $Dir->delDir(RUNTIME_PATH . "Cache/Web/");
                    $Dir->delDir(RUNTIME_PATH . "Temp/");
                    $this->success("WEB模板缓存清理成功！");
                    break;
                case "logs":
                    $Dir->delDir(RUNTIME_PATH . "Logs/");
                    $this->success("站点日志清理成功！");
                    break;
                default:
                    $this->error("请选择清楚缓存类型！");
                    break;
            }
        } else {
            $this->display("Index:cache");
        }
    }
    public function ajax_getneworder(){
        $endSig = "\n\n";
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $time = date('r');
        $order=$this->orders();
        if(!empty($order)){
            $response = array(
                'order'=>$order,
                'status' => 1
            );
        }else{
            $response = array(
                'status' => 0
            );
        }
        
        $res = json_encode($response);
        echo "data:{$res}{$endSig}";
        flush();

    }
    protected function orders(){
        $where=array();
        // if(!empty($_SESSION['storeid'])){
        //     $where['a.storeid']=$_SESSION['storeid'];
        // }
        // $where['b.status']=2;
        // $where['b.package_status']=0;
        // $where['b.delivery_status']=0;
        // $where['b.close_status']=0;
        // $where['b.cancel_status']=0;
        // $where['a.puid']=0;
        // $where['_string']="(a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
        // $order=M('Order a')
        //     ->join("left join zz_order_time b on a.orderid=b.orderid")
        //     ->order(array('a.inputtime'=>'desc'))
        //     ->where($where)
        //     ->field("a.orderid")
        //     ->find();

        if(!empty($_SESSION['storeid'])){
            $sql="SELECT a.orderid FROM zz_order a left join zz_order_time b on a.orderid=b.orderid WHERE ( a.storeid = ".$_SESSION['storeid']." ) AND ( b.status = 2 ) AND ( b.package_status = 0 ) AND ( b.delivery_status = 0 ) AND ( b.close_status = 0 ) AND ( b.cancel_status = 0 ) AND ( a.puid = 0 ) AND ( (a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1 ) ORDER BY a.inputtime desc LIMIT 1";
        }else{
            $sql="SELECT a.orderid FROM zz_order a left join zz_order_time b on a.orderid=b.orderid WHERE ( b.status = 2 ) AND ( b.package_status = 0 ) AND ( b.delivery_status = 0 ) AND ( b.close_status = 0 ) AND ( b.cancel_status = 0 ) AND ( a.puid = 0 ) AND ( (a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1 ) ORDER BY a.inputtime desc LIMIT 1";
        }
        
        $order=M()->query($sql);
        return $order;
    }
}
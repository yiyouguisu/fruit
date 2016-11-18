<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

use Org\Util\PHPZip;

class OrderexcelController extends CommonController {
    public function _initialize() {
        set_time_limit(0);
        $this->userid=!empty($_SESSION['userid'])? $_SESSION['userid'] : 1;
        $this->storeid=!empty($_SESSION['storeid'])? $_SESSION['storeid'] : 0;
    }
    /**
     * 数据导出
     * 
     */
    public function excel(){
        $search = I('post.search');
        $where = array();
        
        if (!empty($search)) {
            //添加开始时间
            $start_time = I('post.start_time');
            if (!empty($start_timgete)) {
                $start_time = strtotime($start_time);
                $where["a.inputtime"] = array("EGT", $start_time);
            }
            //添加结束时间
            $end_time = I('post.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where["a.inputtime"] = array("ELT", $end_time);
            }
            if ($end_time > 0 && $start_time > 0) {
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }

            $isthirdparty = I('post.isthirdparty');
            if ($isthirdparty != "" && $isthirdparty != null) {
                if($isthirdparty==1){
                    $where['a.ordersource']=array('in','3,4');
                }else{
                    $where['a.ordersource']=array('in','1,2');
                }
            }
            $ordertype = I('post.ordertype');
            if ($ordertype != "" && $ordertype != null) {
                if($ordertype==4){
                    $where['a.iscontainsweigh']=1;
                    $where['a.ordertype']=1;
                }else{
                    $where['a.ordertype']=$ordertype;
                }
            }

            $issend = I('post.issend');
            if ($issend != "" && $issend != null) {
                if($issend==1){
                    $where['a.storeid']=array('gt',0);
                }else{
                    $where['a.storeid']=array('eq',0);
                }
            }
            $ordersource = I('post.ordersource');
            if ($ordersource != "" && $ordersource != null) {
                $where["a.ordersource"] = array("EQ", $ordersource);
            }
            $storeid = I('post.storeid');
            if ($storeid != "" && $storeid != null) {
                $where["a.storeid"] = array("EQ", $storeid);
            }
            //搜索关键字
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $where["a.orderid"] = array("LIKE", "%{$keyword}%");
            }
            if(isset($_POST['close_status'])){
                $where['b.status']=6;
                $where['b.close_status']=1;
            }else{
                $where['b.close_status']=0;
            }
            if(isset($_POST['cancel_status'])){
                $where['b.status']=3;
                $where['b.cancel_status']=1;
            }else{
                $where['b.cancel_status']=0;
            }
            if(isset($_POST['error_status'])){
                $where['b.status']=array('in','2,4');
                $where['b.error_status']=array('in','1,2');
            }
            
            if(isset($_POST['isdone'])){
                $where['b.status']=5;
                $where['b.delivery_status']=4;
            }
            if(isset($_POST['isdivery'])){
                $where['b.status']=2;
                $where['b.package_status']=2;
                $where['b.delivery_status']=1;
            }
            if(isset($_POST['isdistributedone'])){
                $where['b.status']=2;
                $where['b.package_status']=0;
                $where['a.puid']=array('neq',0);
                $where['b.delivery_status']=0;
                $where['_string']="(a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
                
            }
            
            if(isset($_POST['isnew'])){
                $end_time=time();
                $start_time=strtotime("-10 hours");
                $where['a.inputtime'] = array(array('EGT', $start_time), array('ELT', $end_time));
            }
            
            if(isset($_POST['ispackagedone'])){
                $where['b.status']=2;
                $where['b.package_status']=2;
                $where['b.delivery_status']=0;
            }
            if(isset($_POST['ispackageing'])){
                $where['b.status']=2;
                $where['b.package_status']=1;
                $where['b.delivery_status']=0;   
            }
            if(isset($_POST['isspeed'])){
                $where['a.isspeed']=1;
            }
            if(isset($_POST['iswaitdistribute'])){
                $where['b.status']=2;
                $where['b.package_status']=0;
                $where['b.delivery_status']=0;
                $where['a.puid']=0;
                $where['_string']="(a.paystyle=2 and b.pay_status=0) or (a.paystyle!=2 and b.pay_status=1) or (a.iscontainsweigh=0 and b.pay_status=1) or a.iscontainsweigh=1";
            }
            
            if(isset($_POST['iswaitpay'])){
                $where['b.status']=2;
                $where['_string']="a.paystyle!=2 and b.pay_status=0";
            }
            
            if(!empty($_SESSION['storeid'])){
                $where['a.storeid']=$this->storeid;
            }
        }
        $count = D("order a")
            ->join("left join zz_order_time b on a.orderid=b.orderid")
            ->where($where)
            ->count();
        $filename=date("Ymd-His");
        $i=0;
        $j=1;
        do{
            $data = D("order a")
                ->join("left join zz_order_time b on a.orderid=b.orderid")
                ->where($where)
                ->page($j,1000)
                ->order(array("a.id" => "desc"))
                ->select();
            //foreach($data as $key => $value){
            //    $data[$key]['productinfo']= M('order_productinfo a')
            //        ->join("zz_product b on a.pid=b.id")
            //        ->field("a.*,b.title,b.unit,b.standard")
            //        ->where(array('a.orderid'=>$value['orderid']))
            //        ->select();
            //}
            $outputdata=array();
            foreach ($data as $key => $value) {
                $phone=M("member")->where("id=".$value['uid'])->getField("phone");
                $username=M("member")->where("id=".$value['uid'])->getField("username");
                $username=!empty($phone)?$phone:$username;

                $puname=M("user")->where("id=".$value['puid'])->getField("realname");
                $puname=!empty($value['puid'])?$puname:"待派发";
                $runame=M("user")->where("id=".$value['ruid'])->getField("realname");
                $runame=!empty($value['ruid'])?$runame:"待配送";
                $donetime=!empty($value['donetime'])?date("Y-m-d H:i:s", $value["donetime"]):"";
                $outputdata[$key]=array(
                    'orderid'=>$value['orderid'],
                    'uid'=>$username,
                    'puid'=>$puname,
                    'ruid'=>$runame,
                    'ordersource'=>getordersource($value['ordersource']),
                    'ordertype'=>getordertype($value['orderid']),
                    'storeid'=>getstoreinfo($value['storeid']),
                    'status'=>$value['status'],
                    'total'=>$value['total'],
                    'money'=>$value['money'],
                    'wallet'=>$value['wallet'],
                    'discount'=>$value['discount'],
                    'paystyle'=>$value['paystyle'],
                    'pay_status'=>$value['pay_status'],
                    'package_status'=>$value['package_status'],
                    'delivery_status'=>$value['delivery_status'],
                    'name'=>$value['name'],
                    'tel'=>$value['tel'],
                    'area'=>getarea($value['area']),
                    'address'=>$value['address'],
                    'sendtime'=>$value['sendtime'],
                    'buyerremark'=>$value['buyerremark'],
                    'inputtime'=>date("Y-m-d H:i:s", $value["inputtime"]),
                    'donetime'=>$donetime
                    );
                switch ($value['status']) {
                    case '0':
                        # code...
                        $outputdata[$key]["status"]="默认";
                        break;
                    case '1':
                        # code...
                        $outputdata[$key]["status"]="用户确认订单成功";
                        break;
                    case '2':
                        # code...
                        $outputdata[$key]["status"]="订单审核成功";
                        break;
                    case '3':
                        # code...
                        $outputdata[$key]["status"]="取消订单";
                        break;
                    case '4':
                        # code...
                        $outputdata[$key]["status"]="异常订单";
                        break;
                    case '5':
                        # code...
                        $outputdata[$key]["status"]="已完成";
                        break;
                }
                if($value['paystyle']==1){
                    if($value['paytype']==1){
                        $outputdata[$key]["paystyle"]="在线支付(支付宝)";
                    }elseif($value['paytype']==2){
                        $outputdata[$key]["paystyle"]="在线支付(微信)";
                    }
                }elseif($value['paystyle']==2){
                    $outputdata[$key]["paystyle"]="货到付款";
                }elseif($value['paystyle']==3){
                    $outputdata[$key]["paystyle"]="钱包支付";
                }elseif($value['paystyle']==4){
                    $outputdata[$key]["paystyle"]="优惠券抵扣";
                }else{
                    $outputdata[$key]["paystyle"]="";
                }
                switch ($value['pay_status']) {
                    case '0':
                        # code...
                        $outputdata[$key]["pay_status"]="未支付";
                        
                        break;
                    case '1':
                        # code...
                        $outputdata[$key]["pay_status"]="已支付（".date("Y-m-d H:i:s",$value['pay_time']).")";
                        break;
                }
                switch ($value['package_status']) {
                    case '0':
                        # code...
                        $outputdata[$key]["package_status"]="未包装";
                        
                        break;
                    case '1':
                        # code...
                        $outputdata[$key]["package_status"]="包装中（".date("Y-m-d H:i:s",$value['package_time']).")";
                        break;
                    case '2':
                        # code...s
                        $outputdata[$key]["package_status"]="包装完成（".date("Y-m-d H:i:s",$value['package_donetime']).")";
                        break;
                }
                switch ($value['delivery_status']) {
                    case '0':
                        # code...
                        $outputdata[$key]["delivery_status"]="未发货";
                        
                        break;
                    case '1':
                        # code...
                        $outputdata[$key]["delivery_status"]="配送中";
                        break;
                    case '4':
                        # code...s
                        $outputdata[$key]["delivery_status"]="已完成（".date("Y-m-d H:i:s",$value['delivery_time']).")";
                        break;
                }
                if($value['isserviceorder']==1){
                    $outputdata[$key]["sendtime"]="售后订单";
                }else{
                    if($value['ordertype']==2){
                        $outputdata[$key]["sendtime"]="预购订单";
                    }elseif($value['ordertype']==3){
                        $outputdata[$key]["sendtime"]="企业订单";
                    }else{
                        if($value['isspeed']==1){
                            $outputdata[$key]["sendtime"]="极速达订单";
                        }else{
                            $outputdata[$key]["sendtime"]=date("Y-m-d H:i:s",$value['start_sendtime'])."--".date("Y-m-d H:i:s",$value['end_sendtime']);
                        }
                    }
                }
                
            }
            //设置要导出excel的表头
            $title = array('订单号','下单用户','包装员','配送员','订单来源','订单类型','所属门店','订单状态','订单总额','实际支付金额','钱包抵扣','优惠券抵扣','支付方式','支付状态','包装状态','配送状态','收货人姓名', '收货人手机号', "收货人居住地", "收货人详细地址",  "配送时间","订单留言","订单提交时间", "订单完成时间");
            //进行导出
            self::excelexport($outputdata, date("Ymd-His")."-".$j,$title,$filename);

            unset($outputdata);
            unset($data);
            //ob_flush();//修改部分
            //flush();
            $j++;
            $i+=1000;
        }while($i<=$count);
        
        $dbpath = "./Uploads/files/";
        $zippath = $dbpath;

        $bakup=$filename;
        //zip文件名
        $zipname=$bakup.".zip";
        $z=new \Org\Util\PHPZip(); //新建立一个zip的类
        $res = $z->Zip($dbpath."/".$bakup,$zippath."/".$zipname); //添加指定目录
        if($res==1){
            self::downzip($zippath,$zipname);
        }else{
            $this->error("压缩失败");
        }
    }
    //下载备份
    public function downzip($zippath,$file_name){
        if(!file_exists($zippath."/".$file_name))   {   //检查文件是否存在  
            $this->error("文件找不到");
        }else{  
            $file = fopen($zippath."/".$file_name,"r"); // 打开文件
            // 输入文件标签
            header("Content-type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Accept-Length: ".filesize($zippath."/".$file_name));
            header("Content-Disposition: attachment; filename=" . $file_name);
            // 输出文件内容
            echo fread($file,filesize($zippath."/".$file_name));
            fclose($file);
            @unlink($zippath."/".$file_name);
            exit();
        }
    }
    //导入excel内容转换成数组 
    public function excelimport($filePath){
        import("Org.Util.PHPExcel");
        $PHPExcel = new \PHPExcel(); 
        /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
        $PHPReader = new \PHPExcel_Reader_Excel2007(); 
        if(!$PHPReader->canRead($filePath)){ 
            $PHPReader = new \PHPExcel_Reader_Excel5(); 
            if(!$PHPReader->canRead($filePath)){ 
                $PHPReader = new \PHPExcel_Reader_CSV();
                if(!$PHPReader->canRead($filePath)){
                    echo 'no Excel'; 
                    return false; 
                }
            } 
        } 
        
        $PHPExcel = $PHPReader->load($filePath); 
        $currentSheet = $PHPExcel->getSheet(0)->toArray();  //读取excel文件中的第一个工作表
        return $currentSheet;
    }
    //导出Excel表格
    public function excelexport($data,$excelFileName,$sheetTitle,$filename){
        import("Org.Util.PHPExcel");
        /* 实例化类 */
        $objPHPExcel = new \PHPExcel(); 
        
        /* 设置输出的excel文件为2007兼容格式 */
        //$objWriter=new PHPExcel_Writer_Excel5($objPHPExcel);//非2007格式
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        
        /* 设置当前的sheet */
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objActSheet = $objPHPExcel->getActiveSheet();
        $k = 'A';
        foreach($sheetTitle as $vo)
        { 
            $objActSheet->setCellValue($k.'1',$vo);
            $k++;
        }
        
        $i = 2;
        foreach($data as $value)
        {   
            /* excel文件内容 */
            $j = 'A';
            foreach($value as $value2)
            { 
                //            $value2=iconv("gbk","utf-8",$value2);
                $objActSheet->setCellValue($j.$i,' '.$value2);
                $j++;
            }
            $i++;
        }
        if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/Uploads/files/".$filename."/")){
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/Uploads/files/".$filename."/",0777);
            chmod($_SERVER['DOCUMENT_ROOT'] . "/Uploads/files/".$filename."/",0777);
        } 
        $objWriter->save($_SERVER['DOCUMENT_ROOT']."/Uploads/files/".$filename."/" . $excelFileName.'.xls');
        //ob_end_clean();  //清空缓存


        ///* 生成到浏览器，提供下载 */ 
        //ob_end_clean();  //清空缓存             
        //header("Pragma: public");
        //header("Expires: 0");
        //header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        ////header("Content-Type:application/force-download");
        //header("Content-Type:application/vnd.ms-execl");
        //header("Content-Type:application/octet-stream");
        ////header("Content-Type:application/download");
        //header('Content-Disposition:attachment;filename="'.$excelFileName.'.xls"');
        //header("Content-Transfer-Encoding:binary"); 
        //$objWriter->save('php://output');
    }
}
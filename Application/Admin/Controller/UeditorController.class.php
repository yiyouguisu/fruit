<?php
/**
* 百度编辑器控制器
*/
namespace Admin\Controller;
use Think\Controller;
@ini_set('upload_max_filesize', '20M');
class UeditorController extends Controller{
	 
	private $thumb;//是否开启缩略图
	private $water;	//是否加水印(0:无水印,1:水印文字,2水印图片)
	private $waterText;//水印文字
	private $waterTextColor;//水印文字颜色
	private $waterTextFontsize;//水印文字大小
	private $thumbType;//缩略图类型
	private $waterPosition;//水印位置
	private $savePath; //保存位置
	private $userid; //操作用户名
	private $upload_file_type=1;


	public function _initialize(){
		$ConfigData=F("web_config");
		foreach ($ConfigData as $key => $r) {
            if($r['groupid'] == 4){
                $this->config[$r['varname']] = $r['value'];
            }
        }
		$this->userid=empty($_SESSION['userid'])? $_GET['userid'] : $_SESSION['userid'];
		if(empty($this->userid)){
			$this->userid= '1';
		}

		$this->imagessavePath= '/Uploads/images/pc/';
		$this->filesavePath= '/Uploads/files/';
		$this->videosavePath= '/Uploads/video/';
		$this->remotesavePath= '/Uploads/remote/';
		$this->scrawlsavePath= '/Uploads/scrawl/';
		$this->thumb=$this->config['thumbShow'];
		$this->water=$this->config['waterShow'];
		$this->thumbType=$this->config['thumbType'];
		$this->waterText=$this->config['waterText'];
		$this->waterTextColor=$this->config['waterColor'];
		$this->waterTextFontsize=$this->config['waterFontsize'];
 		$this->waterPosition= $this->config['waterPos'];
 		$this->filelistpath='/Uploads/files/';
 		$this->imageslistpath='/Uploads/images/';
 		$this->saveRule = date('His')."_".rand(1000,9999);
        $this->uploadDir = "/Uploads/images/pc/";
        $this->autoSub= true;
        $this->subNameRule = array('date','Ymd');
	}

	public function index(){
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(COMMON_PATH."Conf/ueditorconfig.json")), true);
		$action = htmlspecialchars($_GET['action']);
		switch ($action) {
		    case 'config':
		        $result =  json_encode($CONFIG);
		        break;

		    /* 上传图片 */
		    case 'uploadimage':
		        $config = array(
		            "pathFormat" => $this->imagessavePath,
		            "maxSize" => $CONFIG['imageMaxSize'],
		            "allowFiles" => $CONFIG['imageAllowFiles']
		        );
		        $fieldName = $CONFIG['imageFieldName'];
		        $result=$this->upFile($config, $fieldName);
		        break;

		    /* 上传涂鸦 */
		    case 'uploadscrawl':
		        $config = array(
		            "pathFormat" => $this->scrawlsavePath,
		            "maxSize" => $CONFIG['scrawlMaxSize'],
		            "allowFiles" => $CONFIG['scrawlAllowFiles'],
		            "oriName" => "scrawl.png"
		        );
		        $fieldName = $CONFIG['scrawlFieldName'];
		        $base64 = "base64";
		        $result=$this->upBase64($config,$fieldName);
		        break;

		    /* 上传视频 */
		    case 'uploadvideo':
		        $config = array(
		            "pathFormat" => $this->videosavePath,
		            "maxSize" => $CONFIG['videoMaxSize'],
		            "allowFiles" => $CONFIG['videoAllowFiles']
		        );
		        $fieldName = $CONFIG['videoFieldName'];
		        $result=$this->upFile($config, $fieldName);
		        break;

		    /* 上传文件 */
		    case 'uploadfile':
		    // default:
		        $config = array(
		            "pathFormat" => $this->filesavePath,
		            "maxSize" => $CONFIG['fileMaxSize'],
		            "allowFiles" => $CONFIG['fileAllowFiles']
		        );
		        $fieldName = $CONFIG['fileFieldName'];
		        $result=$this->upFile($config, $fieldName);
		        break;

		    /* 列出图片 */
		    case 'listimage':
			$allowFiles = $CONFIG['imageManagerAllowFiles'];
			$listSize = $CONFIG['imageManagerListSize'];
			$path = $this->imageslistpath;
			$get=$_GET;
			$result =$this->file_list($allowFiles, $listSize, $get,$path);
		        	break;
		    /* 列出文件 */
		    case 'listfile':
			$allowFiles = $CONFIG['fileManagerAllowFiles'];
			$listSize = $CONFIG['fileManagerListSize'];
			$path = $this->filelistpath;
			$get=$_GET;
			$result =$this->file_list($allowFiles, $listSize, $get,$path);
	    	            break;

		    /* 抓取远程文件 */
		    case 'catchimage':
		    	$config = array(
			    "pathFormat" => $this->remotesavePath,
			    "maxSize" => $CONFIG['catcherMaxSize'],
			    "allowFiles" => $CONFIG['catcherAllowFiles'],
			    "oriName" => "remote.png"
			);
			$fieldName = $CONFIG['catcherFieldName'];
			/* 抓取远程图片 */
			$list = array();
			if (isset($_POST[$fieldName])) {
			    $source = $_POST[$fieldName];
			} else {
			    $source = $_GET[$fieldName];
			}
			foreach ($source as $imgUrl) {
			    $info=json_decode($this->saveRemote($config, $imgUrl),true);
			    // dump($info);
			    array_push($list, array(
			        "state" => $info["state"],
			        "url" => $info["url"],
			        "size" => $info["size"],
			        "title" => htmlspecialchars($info["title"]),
			        "original" => htmlspecialchars($info["original"]),
			        "source" => htmlspecialchars($imgUrl)
			    ));
			}

			$result= json_encode(array(
			    'state'=> count($list) ? 'SUCCESS':'ERROR',
			    'list'=> $list
			));
		        break;

		    default:
		        $result = json_encode(array(
		            'state'=> '请求地址出错'
		        ));
		        break;
		}

		/* 输出结果 */
		if (isset($_GET["callback"])) {
		    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
		        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
		    } else {
		        echo json_encode(array(
		            'state'=> 'callback参数不合法'
		        ));
		    }
		} else {
		    echo $result;
		}

	}
	/**
	     * 上传文件的主处理方法
	     * @return mixed
	     */
	private function upFile($config,$fieldName){
		$upload = new \Think\Upload();
        $upload->maxSize = $this->config['uploadASize'];
        $upload->exts= explode("|",$this->config['uploadAType']);// 设置附件上传类型
        $upload->savePath = $config['pathFormat'];
        $upload->autoSub= $this->autoSub;
        $upload->saveName = $this->saveRule;
        $upload->subName  = $this->subNameRule;


		$info=$upload->uploadOne($_FILES[$fieldName]);
		if($info){
			$fname=$info['savepath'].$info['savename'];
			$imagearr = explode(',', 'jpg,gif,png,jpeg,bmp,ttf,tif'); 
			$info['ext']= strtolower($info['ext']);
			\Admin\Common\CommonController::save_uploadinfo($this->userid,$this->upload_file_type,$info,$info['name'], $isthumb = 0, $isadmin = 1,  $time = time());
			$isimage = in_array($info['ext'],$imagearr) ? 1 : 0;
			if ($isimage) {
				$image=new \Think\Image();
				$image->Open(".".$fname);

				$thumbsrc=$info['savepath'] . $upload->saveName . "_thumb." . $info['ext'];
				if($this->thumb==1){
					$fname=$thumbsrc;
				}
				
				if($this->thumb==1){
					$image->thumb($this->config['thumbW'],$this->config['thumbH'],$this->config['thumbType'])->save(".".$thumbsrc);
				}
				if ($this->water==1) {
					if($this->thumb==1){
						$image->text($this->waterText,'./Public/Public/font/STXINGKA.TTF',$this->config['waterFontsize'],$this->config['waterColor'],$this->waterPosition,array(-2,0))->save(".".$thumbsrc); 
					}else{
						$image->text($this->waterText,'./Public/Public/font/STXINGKA.TTF',$this->config['waterFontsize'],$this->config['waterColor'],$this->waterPosition,array(-2,0))->save(".".$$fname); 
					}
				}
				if ($this->water==2) {
					if($this->thumb==1){
						$image->water(".".$this->config['waterImg'],$this->waterPosition,$this->config['waterTran'])->save(".".$thumbsrc);
					}else{
						$image->water(".".$this->config['waterImg'],$this->waterPosition,$this->config['waterTran'])->save(".".$fname);
					}
				}	
			}

			$data=array(
				'state'=>'SUCCESS',
				'url'=>__ROOT__.$fname,
				'title'=>$info['savename'],
				'original'=>$info['name'],
				'type'=>'.' . $info['ext'],
				'size'=>$info['size'],
				);
		}else{
			$data=array(
				'state'=>$upload->getError(),
				);
		}
		return json_encode($data);
	}

	/**
	 * 处理base64编码的图片上传
	 * @return mixed
	 */
	private function upBase64($config,$fieldName)
	{
	    $base64Data = $_POST[$fieldName];
	    $img = base64_decode($base64Data);

	    $dirname=$config['pathFormat'];
	    $file['filesize']=strlen($img);
	    $file['oriName']=$config['oriName'];
	    $file['ext']=strtolower(strrchr($config['oriName'], '.'));
	    $file['name']= uniqid() .  $file['ext'];
	    $file['fullName']=$dirname . $file['name'];
	    $fullName=$file['fullName'];
	    // dump($file);

 	//检查文件大小是否超出限制
	    if ($file['filesize'] >= ($config["maxSize"])) {
  		$data=array(
			'state'=>'文件大小超出网站限制',
		);
		return json_encode($data);
	    }

	    //创建目录失败
	    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $dirname) && !mkdir($_SERVER['DOCUMENT_ROOT'] . $dirname, 0777, true)) {
	           $data=array(
			'state'=>'目录创建失败',
		);

		return json_encode($data);
	    } else if (!is_writeable($_SERVER['DOCUMENT_ROOT'] . $dirname)) {
	         $data=array(
			'state'=>'目录没有写权限',
		);
		return json_encode($data);
	    }

	    //移动文件
	    if (!(file_put_contents(substr($fullName, 1), $img) && file_exists(substr($fullName, 1)))) { //移动失败
        	         $data=array(
		'state'=>'写入文件内容错误',
		);
	    } else { //移动成功	   
	    	$info=array(
	    		'savename'=>$file['name'],
	            'ext'=>substr($file['ext'], 1),
	            'size'=>$file['filesize'],
	            'savepath'=>$dirname,
	            'name'=>$file['oriName']
	    		);
	    	\Admin\Common\CommonController::save_uploadinfo($this->userid,3,$info,$file['oriName'], $isthumb = 0, $isadmin = 1,  $time = time());    
	        $data=array(
			'state'=>'SUCCESS',
			'url'=>__ROOT__ .$file['fullName'],
			'title'=>$file['name'],
			'original'=>$file['oriName'],
			'type'=>$file['ext'],
			'size'=>$file['filesize'],
		);
	    }
	    return json_encode($data);
	}

	/**
	 * 拉取远程图片
	 * @return mixed
	 */
	private function saveRemote($config, $fieldName)
	{
	    $imgUrl = htmlspecialchars($fieldName);
	    $imgUrl = str_replace("&amp;", "&", $imgUrl);

	    //http开头验证
	    if (strpos($imgUrl, "http") !== 0) {
	         $data=array(
		'state'=>'链接不是http链接',
		);
	         return json_encode($data);
	    }
	    //获取请求头并检测死链
	    $heads = get_headers($imgUrl);
	    if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
	         $data=array(
		'state'=>'链接不可用',
		);
	         return json_encode($data);
	    }
	    //格式验证(扩展名验证和Content-Type验证)
	    $fileType = strtolower(strrchr($imgUrl, '.'));
	    if (!in_array($fileType, $config['allowFiles']) || stristr($heads['Content-Type'], "image")) {
	        $data=array(
		'state'=>'链接contentType不正确',
		);
	         return json_encode($data);
	    }

	    //打开输出缓冲区并获取远程图片
	    ob_start();
	    $context = stream_context_create(
	        array('http' => array(
	            'follow_location' => false // don't follow redirects
	        ))
	    );
	    readfile($imgUrl, false, $context);
	    $img = ob_get_contents();
	    ob_end_clean();
	    preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

	    $dirname=$config['pathFormat'];
	    $file['oriName']=$m ? $m[1]:"";
	    $file['filesize']=strlen($img);
	    $file['ext']=strtolower(strrchr($config['oriName'], '.'));
	    $file['name']= uniqid() .  $file['ext'];
	    $file['fullName']=$dirname . $file['name'];
	    $fullName=$file['fullName'];
	    
	    //检查文件大小是否超出限制
	    if ($file['filesize'] >= ($config["maxSize"])) {
  		$data=array(
			'state'=>'文件大小超出网站限制',
		);
		return json_encode($data);
	    }

	    //创建目录失败
	    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $dirname) && !mkdir($_SERVER['DOCUMENT_ROOT'] . $dirname, 0777, true)) {
  		$data=array(
			'state'=>'目录创建失败',
		);
		return json_encode($data);
	    } else if (!is_writeable($_SERVER['DOCUMENT_ROOT'] . $dirname)) {
  		$data=array(
			'state'=>'目录没有写权限',
		);
		return json_encode($data);
	    }

	    //移动文件
	    if (!(file_put_contents(substr($fullName, 1), $img) && file_exists(substr($fullName, 1)))) { //移动失败
  		$data=array(
			'state'=>'写入文件内容错误',
		);
		return json_encode($data);
	    } else { //移动成功
	    	$info=array(
	    		'savename'=>$file['name'],
	            'ext'=>substr($file['ext'], 1),
	            'size'=>$file['filesize'],
	            'savepath'=>$dirname,
	            'name'=>$file['oriName']
	    		);
	    	\Admin\Common\CommonController::save_uploadinfo($this->userid,$this->upload_file_type,$info,$file['oriName']);    
	        $data=array(
			'state'=>'SUCCESS',
			'url'=>__ROOT__ . $file['fullName'],
			'title'=>$file['name'],
			'original'=>$file['oriName'],
			'type'=>$file['ext'],
			'size'=>$file['filesize'],
		);
	    }
	    return json_encode($data);
	}
	private function file_list($allowFiles, $listSize, $get,$dirname){
		$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

		/* 获取参数 */
		$size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
		$start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
		$end = $start + $size;

		/* 获取文件列表 */
		//$path = $_SERVER['DOCUMENT_ROOT'] . (substr($dirname, 0, 1) == "/" ? "":"/") . $dirname;
		$path=".".$dirname;
		$files = $this->getfiles($path, $allowFiles);
		if (!count($files)) {
		    return json_encode(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}

		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}

		/* 返回数据 */
		$result = json_encode(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));

		return $result;
	}

   	 /**
	     * 遍历获取目录下的指定类型的文件
	     * @param $path
	     * @param array $files
	     * @return array
	     */
	    private function getfiles( $path , $allowFiles, &$files = array() ) {
	        if ( !is_dir( $path ) ) return null;
	        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
	        $handle = opendir( $path);
	        while ( false !== ( $file = readdir( $handle ) ) ) {
	            if ( $file != '.' && $file != '..' ) {
	                $path2 = $path . $file;
	                if ( is_dir( $path2)) {
	                    $this->getfiles( $path2 ,$allowFiles,  $files );
	                } else {
		                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
		                    $files[] = array(
		                        'url'=> __ROOT__ . substr($path2, 1),
		                        'mtime'=> filemtime($path2)
		                    );
		                }
	                }
	            }
	        }
	        return $files;
	    }
	    /**
	     * [formatUrl 格式化url，用于将getfiles返回的文件路径进行格式化，起因是中文文件名的不支持浏览]
	     * @param  [type] $files [文件数组]
	     * @return [type]        [格式化后的文件数组]
	     */
	    private function formatUrl($files){
	    	if(!is_array($files)) return $files;
	    	foreach ($files as  $key => $value) {
	    		$data=array();
	    		$data=explode('/', $value);
	    		foreach ($data as $k=>$v) {
	    			if($v!='.' && $v!='..'){
	    				$data[$k]=urlencode($v);
	    				$data[$k] = str_replace("+", "%20", $data[$k]); 
	    			}
	    		}
	    		$files[$key]=implode('/', $data);
	    	}
	    	return $files;
	    }	


	private function format_exts($exts){
		$data=array();
		foreach ($exts as $key => $value) {
			$data[]=ltrim($value,'.');
		}
		return $data;
	}

}
?>
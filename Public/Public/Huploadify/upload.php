<?php
header("Content-Type:text/html;charset=utf-8");
// $filename = $_FILES['file']['name'];
// $key = $_POST['key'];
// $key2 = $_POST['key2'];
// if ($filename) {
//     move_uploaded_file($_FILES["file"]["tmp_name"],
//       "uploads/" . $filename);
// }
// echo $key;
// echo $key2;


//$filename = $_FILES['file']['name'];
//header('Content-type: application/octet-stream;charset=UTF-8'); 
$filename = $_POST['fileName'];
$filename = md5($filename.$_REQUEST['uid']) .'.'. getExt($filename);
$array = array();
if ($filename) {
    $filename = iconv('UTF-8', 'GBK', $filename);
	/*$xmlstr =  $GLOBALS[HTTP_RAW_POST_DATA];//$_POST["data"];//
	if(empty($xmlstr)) $xmlstr = file_get_contents('php://input');
	$raw = $xmlstr;//得到post过来的二进制原始数据*/
    //file_put_contents('uploads/'.$filename,$_FILES["file"]["tmp_name"],FILE_APPEND);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/'.$filename,file_get_contents($_FILES["file"]["tmp_name"]),FILE_APPEND);
    $array['getsize'] = $_FILES['file']['size'];
    $array['success'] = true;
    $array['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/'.$filename);
    $array['src'] = $_REQUEST['folder'] . '/'.$filename;
    $array['uid'] = $_REQUEST['uid'];
    echo json_encode($array);
}
function getExt($fileName){
    $ext = explode(".", $fileName);
    $ext = $ext[count($ext) - 1];
    return strtolower($ext);
}

?>
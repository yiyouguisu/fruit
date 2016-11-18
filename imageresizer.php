<?php
/**
 * @author Nishchal Gautam <gautam.nishchal@gmail.com>
 * @category images
 * @copyright (c) 2013, Nishchal Gautam
 * @license http://www.wl-dm.blogspot.com/2013/01/terms-and-conditions.html
 * @version 1.0 Beta
 */

$image=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'];
if(file_exists($image))
{
	$extension=explode(".",$image);
	$extension=end($extension);
  //创建水印图像资源
  $info = getimagesize($image);

  //创建水印图像资源
  $fun   = 'imagecreatefrom' . image_type_to_extension($info[2], false);
  $source_image = $fun($image);

  $source_height = imagesy($source_image);
  $source_width = imagesx($source_image);

  if(isset($_GET['h'])){
    $destination_height=$_GET['h'];
  }
  if(isset($_GET['w'])){
    $destination_width=$_GET['w'];
  }
  if(empty($destination_height))
  {
      $destination_height=$source_height;
  }
  if(empty($destination_width))
  {
      $destination_width=$source_width;
  }
  
  if(!empty($destination_width)&&!empty($destination_height)){
    $dst_x=0;
    $dst_y=0;
    $src_x=0;
    $src_y=0;
    $destination_image=  imagecreatetruecolor($destination_width, $destination_height);
    imagecopyresized($destination_image,$source_image,$dst_x,$dst_y,$src_x,$src_y,$destination_width,$destination_height,$source_width,$source_height);
  }else{
    $destination_image=$source_image;
  }
  switch ($extension) {
    case "jpg":
        imagejpeg($destination_image);
        header("content-type:image/jpeg");
        break;
    case "jpeg":
        header("content-type:image/jpeg");
        imagejpeg($destination_image);
        break;
    case "gif":
        header("content-type:image/gif");
        imagegif($destination_image);
        break;
    case "png":
        header("content-type:image/png");
        imagepng($destination_image);
        break;
    default:

        break;
  }
  imagedestroy($source_image);
  imagedestroy($destination_image);
}
?>

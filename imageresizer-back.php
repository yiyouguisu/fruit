<?php
/**
 * @author Nishchal Gautam <gautam.nishchal@gmail.com>
 * @category images
 * @copyright (c) 2013, Nishchal Gautam
 * @license http://www.wl-dm.blogspot.com/2013/01/terms-and-conditions.html
 * @version 1.0 Beta
 */
if(isset($_GET['h'])){
  $destination_height=$_GET['h'];
}
if(isset($_GET['w'])){
  $destination_width=$_GET['w'];
}
$image=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'];
if(file_exists($image))
{
	$extension=explode(".",$image);
	$extension=end($extension);
	switch ($extension) {
	    case "jpg":
	        $source_image=  imagecreatefromjpeg($image);
	        break;
		  case "jpeg":
	        $source_image=  imagecreatefromjpeg($image);
	        break;
	    case "gif":
	        $source_image=  imagecreatefromgif($image);
	        break;
	    case "png":
	        $source_image=  imagecreatefrompng($image);
	        break;    
	    default:
	 		exit();
	        break;
  }
  if(empty($destination_height))
  {
      $destination_height=$source_height;
  }
  if(empty($destination_width))
  {
      $destination_width=$source_width;
  }
  
  $destination_image=resizeImage($source_image,$destination_width,$destination_height,$extension);
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
}
function resizeImage($im,$maxwidth,$maxheight,$filetype)
{
    $pic_width = imagesx($im);
    $pic_height = imagesy($im);

    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
    {
        if($maxwidth && $pic_width>$maxwidth)
        {
            $widthratio = $maxwidth/$pic_width;
            $resizewidth_tag = true;
        }

        if($maxheight && $pic_height>$maxheight)
        {
            $heightratio = $maxheight/$pic_height;
            $resizeheight_tag = true;
        }

        if($resizewidth_tag && $resizeheight_tag)
        {
            if($widthratio<$heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }

        if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;

        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if(function_exists("imagecopyresampled"))
        {
            $newim = imagecreatetruecolor($newwidth,$newheight);
           imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }
        else
        {
            $newim = imagecreate($newwidth,$newheight);
           imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }

        return $newim;
    }
    else
    {
      return $im;
    }           
}
?>

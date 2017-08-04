<?php 
namespace common\classes;
class Resizeimage
{
	/**
	 * 
	 * @param unknown_type 文件名
	 * @param unknown_type 目标宽
	 * @param unknown_type 目标高
	 * @param unknown_type 目标文件路径
	 * @return boolean
	 */
	function resizeImage($columnName,$maxwidth,$maxheight,$storePath){
		if(!$_FILES[$columnName]['size']){
			return array('url'=>'','info'=>'文件不存在！','error'=>$_FILES[$columnName]["error"]);
		}
		if(!is_dir($storePath)){
			mkdir($storePath, 0777, true);
		}
		$tmp_name = uniqid().'.jpg';
		$name = $storePath.$tmp_name;
		
		if(file_exists($name)){
			unlink($name);
		}
		
		$img_type = exif_imagetype($_FILES[$columnName]['tmp_name']);
		if($img_type==IMAGETYPE_JPEG){
			$im = imagecreatefromjpeg($_FILES[$columnName]['tmp_name']);
		}else if($img_type==IMAGETYPE_PNG){
			$im = imagecreatefrompng($_FILES[$columnName]['tmp_name']);
		}else if($img_type==IMAGETYPE_GIF){
			$im = imagecreatefromgif($_FILES[$columnName]['tmp_name']);
		}else {
			return array('url'=>'','info'=>'图片格式不对！','error'=>$_FILES[$columnName]["error"]);
		}
		
		//取得当前图片大小
		$width = imagesx($im);
		$height = imagesy($im);
		//生成缩略图的大小
		if(($width > $maxwidth) || ($height > $maxheight)){
			$widthratio = $maxwidth/$width;
			$heightratio = $maxheight/$height;
			if($widthratio < $heightratio){
				$ratio = $widthratio;
			}else{
				$ratio = $heightratio;
			}
			$newwidth = $width * $ratio;
			$newheight = $height * $ratio;
	
			//			if(function_exists("imagecopyresampled")){
			$newim = imagecreatetruecolor($newwidth, $newheight);
			imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			//			}else{
			//				$newim = imagecreate($newwidth, $newheight);
			//				imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			//			}
			ImageJpeg ($newim,$name);
			ImageDestroy ($newim);
		}else{
			ImageJpeg ($im,$name);
		}
		ImageDestroy ($im);
		return array('url'=>$name,'name'=>$tmp_name,'info'=>'','error'=>0);
	}
}
?>
<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 图片操作类
	 * @file class/image.php
	 * @author  胡志宇
	 * @version 1.0
	 */

	class Image{

		/**
		 * 将一张图片处理为png格式
		 *
		 * @param string  $file   原文件路径
		 * @param string  $path   新文件路径
		 * @param integer $width  宽度 为0时不缩放
		 * @param integer $height 高度 为0时不缩放
		 * @access public
		 * @retrun null
		 */
		public function img_to_png($file, $path, $width = 0, $height = 0){
			$size = GetImageSize($file);
			if($size[2] == 1)
				$im_in = imagecreatefromgif($file);
			if($size[2] == 2)
				$im_in = imagecreatefromjpeg($file);
			if($size[2] == 3)
				$im_in = imagecreatefrompng($file);
			if($width == 0 || $height == 0){
				$width = $size[0];
				$height = $size[1];
			}
			$im_out = ImageCreateTrueColor($width, $height);
			imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			Imagepng($im_out, $path);
			ImageDestroy($im_in);
			ImageDestroy($im_out);
		}
	}

?>
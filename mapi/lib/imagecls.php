<?php
class imagecls{
/**
 * 
 * @param unknown_type $image   图片的物理路径
 * @param unknown_type $maxWidth   宽度
 * @param unknown_type $maxHeight  高度
 * @param unknown_type $gen   0:缩放 1:剪裁  
 * @param unknown_type $interlace    对jpeg进行隔行扫描
 * @param unknown_type $filepath	 存储的物理路径的路径
 * @param unknown_type $urlpath		 存储的url的路径
 */
public function thumb($image,$maxWidth=0,$maxHeight=0,$gen = 0,$interlace=true,$filepath,$urlpath)
    {
        $info  = imagecls::getImageInfo($image);
        if($info !== false)
		{
            $srcWidth  = $info[0];
            $srcHeight = $info[1];
			$type = $info['type'];

            $interlace  =  $interlace? 1:0;
            unset($info);

			if($maxWidth > 0 && $maxHeight > 0)
				$scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // 计算缩放比例
			elseif($maxWidth == 0)
				$scale = $maxHeight/$srcHeight;
			elseif($maxHeight == 0)
				$scale = $maxWidth/$srcWidth;

            if($scale >= 1)
			{
                // 超过原图大小不再缩略
                $width   =  $srcWidth;
                $height  =  $srcHeight;
            }
			else
			{
                // 缩略图尺寸
                $width  = (int)($srcWidth*$scale);
                $height = (int)($srcHeight*$scale);
            }

			if($gen == 1)
			{
				$width = $maxWidth;
				$height = $maxHeight;
			}

			$paths = pathinfo($image);
			$thumbpath = $filepath;
			$thumburl =  $urlpath;

            // 载入原图
            $createFun = 'imagecreatefrom'.($type=='jpg'?'jpeg':$type);
			if(!function_exists($createFun))
				$createFun = 'imagecreatefromjpeg';

            $srcImg = $createFun($image);

            //创建缩略图
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

			$x = 0;
			$y = 0;

			if($gen == 1 && $maxWidth > 0 && $maxHeight > 0)
			{
				$resize_ratio = $maxWidth/$maxHeight;
				$src_ratio = $srcWidth/$srcHeight;
				if($src_ratio >= $resize_ratio)
				{
					$x = ($srcWidth - ($resize_ratio * $srcHeight)) / 2;
					$width = ($height * $srcWidth) / $srcHeight;
				}
				else
				{
					$y = ($srcHeight - ( (1 / $resize_ratio) * $srcWidth)) / 2;
					$height = ($width * $srcHeight) / $srcWidth;
				}
			}

            // 复制图片
            if(function_exists("imagecopyresampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height, $srcWidth,$srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height,  $srcWidth,$srcHeight);
            if('gif'==$type || 'png'==$type) {
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
				imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }

            // 对jpeg图形设置隔行扫描
            if('jpg'==$type || 'jpeg'==$type)
				imageinterlace($thumbImg,$interlace);

            // 生成图片
            imagejpeg($thumbImg,$thumbpath,100);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);

			return array('url'=>$thumburl,'path'=>$thumbpath);
         }
         return false;
    }
	/**
	 * 获取图像信息
	 * @param string $target 文件路径
	 * @return mixed
	 */
	function getImageInfo($target)
	{
		$ext = imagecls::fileExt($target);
		$is_image = imagecls::isImageExt($ext);

		if(!$is_image)
			return false;
		elseif(!is_readable($target))
			return false;
		elseif($image_info = @getimagesize($target))
		{
			list($width, $height, $type) = !empty($image_info) ? $image_info : array('', '', '');
			$size = $width * $height;
			if($is_image && !in_array($type, array(1,2,3,6,13)))
				return false;

			$image_info['type'] = strtolower(substr(image_type_to_extension($image_info[2]),1));
			return $image_info;
		}
		else
			return false;
	}
	
	/**
	 * 获取文件扩展名
	 * @return string
	 */
	function fileExt($file_name)
	{
		return addslashes(strtolower(substr(strrchr($file_name, '.'), 1, 10)));
	}

	/**
	 * 根据扩展名判断文件是否为图像
	 * @param string $ext 扩展名
	 * @return bool
	 */
	function isImageExt($ext)
	{
		static $img_ext  = array('jpg', 'jpeg', 'png', 'bmp','gif','giff');
		return in_array($ext, $img_ext) ? 1 : 0;
	}
}


if(!function_exists('image_type_to_extension'))
{
	function image_type_to_extension($imagetype)
	{
		if(empty($imagetype))
			return false;

		switch($imagetype)
		{
			case IMAGETYPE_GIF    : return '.gif';
			case IMAGETYPE_JPEG   : return '.jpeg';
			case IMAGETYPE_PNG    : return '.png';
			case IMAGETYPE_SWF    : return '.swf';
			case IMAGETYPE_PSD    : return '.psd';
			case IMAGETYPE_BMP    : return '.bmp';
			case IMAGETYPE_TIFF_II : return '.tiff';
			case IMAGETYPE_TIFF_MM : return '.tiff';
			case IMAGETYPE_JPC    : return '.jpc';
			case IMAGETYPE_JP2    : return '.jp2';
			case IMAGETYPE_JPX    : return '.jpf';
			case IMAGETYPE_JB2    : return '.jb2';
			case IMAGETYPE_SWC    : return '.swc';
			case IMAGETYPE_IFF    : return '.aiff';
			case IMAGETYPE_WBMP   : return '.wbmp';
			case IMAGETYPE_XBM    : return '.xbm';
			default               : return false;
		}
	}
}
?>
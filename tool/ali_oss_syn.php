<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

//同步本地文件至阿里oss
define("FILE_PATH","/tool"); //文件目录，空为根目录
require_once '../system/system_init.php';
set_time_limit(0);
$paths = array(
		"public/attachment/",
		"public/avatar/",
		"public/emoticons/",
 );


function syn_path($service,$bucket,$path)
{
	if ( $dir = opendir( $path ) )
	{
		while ( $file = readdir( $dir ) )
		{
			$check = is_dir( $path. $file );
			if ( !$check )
			{
				if(!preg_match("/_(\d+)x(\d+)/i",$file,$matches))
				{
					//同步
					$file_dir = str_replace(APP_ROOT_PATH, "", $path);	
					$object = $file_dir.$file;
					$file_path = $path. $file;	
					$service->upload_file_by_file($bucket,$object,$file_path);
				}
			}
			else
			{
				if($file!='.'&&$file!='..')
				{
					syn_path($service,$bucket,$path.$file."/");
				}
			}
		}
		closedir( $dir );
	}
}

if($GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
{
	require_once APP_ROOT_PATH."system/alioss/sdk.class.php";
	$oss_sdk_service = new ALIOSS();
	//设置是否打开curl调试模式
	$oss_sdk_service->set_debug_mode(FALSE);
	
	$bucket = $GLOBALS['distribution_cfg']['OSS_BUCKET_NAME'];
	
	foreach($GLOBALS['paths'] as $path)
	{
		syn_path($oss_sdk_service,$bucket,APP_ROOT_PATH.$path);
	}

}

?>
<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

// 前后台加载的系统配置文件



// 加载数据库中的配置与数据库配置
if(file_exists(APP_ROOT_PATH.'public/db_config.php'))
{
	$db_config	=	require APP_ROOT_PATH.'public/db_config.php';
}

//加载系统配置信息
if(file_exists(APP_ROOT_PATH.'public/sys_config.php'))
{
	$db_conf	=	require APP_ROOT_PATH.'public/sys_config.php';
}

//加载系统信息
if(file_exists(APP_ROOT_PATH.'public/version.php'))
{
	$version	=	require APP_ROOT_PATH.'public/version.php';
}

//加载时区信息
if(file_exists(APP_ROOT_PATH.'public/timezone_config.php'))
{
	$timezone	=	require APP_ROOT_PATH.'public/timezone_config.php';
}

if(is_array($db_config))
$config = array_merge($db_conf,$db_config,$version,$timezone);
elseif(is_array($db_conf))
$config = array_merge($db_conf,$version,$timezone);
else
$config = array_merge($version,$timezone);

$config['REWRITER_DEPART'] = '-';
$config['SITE_DOMAIN'] = "";

return $config;
?>
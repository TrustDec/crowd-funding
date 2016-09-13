<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------\
error_reporting(0);
if(!defined('APP_ROOT_PATH')) 
define('APP_ROOT_PATH', str_replace('verify.php', '', str_replace('\\', '/', __FILE__)));
require './system/system_init.php';
require APP_ROOT_PATH."system/utils/es_image.php";
$very_name = strim($_REQUEST['name'])?strim($_REQUEST['name']):'verify';
es_image::buildImageVerify(4,1,'gif',48,22,$very_name);
?>
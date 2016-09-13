<?php 
// +----------------------------------------------------------------------
// | ThinkPHP                                                             
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.      
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>                                  
// +----------------------------------------------------------------------
// $Id$

// 定义ThinkPHP框架路径
define('BASE_PATH','./');
define('THINK_PATH', './ThinkPHP');
//定义项目名称和路径
define('APP_NAME', 'install');
define('APP_PATH', '.');
define('APP_ROOT_PATH', str_replace('install/index.php', '', str_replace('\\', '/', __FILE__)));
 // 加载框架入口文件 
require(THINK_PATH."/ThinkPHP.php");

//实例化一个网站应用实例
$AppWeb = new App(); 
//应用程序初始化
$AppWeb->run();

?>
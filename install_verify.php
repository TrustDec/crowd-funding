<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

//用于队列的发送
 
require './system/system_init.php';
require './app/Lib/common.php';
define("TIME_UTC",get_gmtime());  
es_session::close();
set_time_limit(0);
//处理短信
$n_time=get_gmtime()-300;
		//删除超过5分钟的验证码
$GLOBALS['db']->query("TRUNCATE TABLE ".DB_PREFIX."file_verifies  ");

echo APP_ROOT_PATH;

?>
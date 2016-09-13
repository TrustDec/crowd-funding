<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require './system/system_init.php';
require './app/Lib/App.class.php';

$pay_list=$GLOBALS['db']->getAll("select create_time from ".DB_PREFIX."deal_pay_log");
foreach($pay_list as $k=>$v)
{
	$pay_list[$k]['create_time_f']=to_date($v['create_time']);
}
print_r($pay_list);
?>
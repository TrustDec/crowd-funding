<?php
require_once './system/system_init.php';

$_REQUEST['ctl'] = 'payment';	
$_REQUEST['act'] = 'notify';
$_REQUEST['class_name'] = 'UnionpayPc';

require './app/Lib/App.class.php';

//实例化一个网站应用实例
$AppWeb = new App();
?>
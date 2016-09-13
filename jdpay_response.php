<?php
require_once './system/system_init.php';

$_REQUEST['ctl'] = 'payment';	
$_REQUEST['act'] = 'response';
$_REQUEST['class_name'] = 'Jdpay';
require_once './app/Lib/App.class.php';
//实例化一个网站应用实例
$AppWeb = new App();
?>
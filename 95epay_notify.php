<?php
if(!defined('ROOT_PATH'))
define('ROOT_PATH', str_replace('95epay_notify.php', '', str_replace('\\', '/', __FILE__)));

global $pay_req;
$pay_req['ctl'] = "payment";
$pay_req['act'] = "notify";
$pay_req['class_name'] = "Sqepay";
include ROOT_PATH."index.php";
?>
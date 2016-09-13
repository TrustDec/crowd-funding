<?php
define("FILE_PATH","/system/api_login/qqv2"); //文件目录，空为根目录
require_once '../../system_init.php';

require_once(APP_ROOT_PATH."system/api_login/qqv2/qqConnectAPI.php");
$qc = new QC();
$qc->qq_login();

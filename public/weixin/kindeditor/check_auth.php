<?php
/*define('SITE_PATH_PATH', str_replace('app/plugins/kindeditor/check_auth.php', '', str_replace('\\', '/', __FILE__)));
if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',  rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
if(!defined('SITE_PATH')) {
        // 网站URL根目录
        $_root = dirname(_PHP_FILE_);
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        $_root = str_replace("/app/plugins/kindeditor/php","",$_root);
        define('SITE_PATH', $_root  );
}


//定义DB
require SITE_PATH_PATH.'system/utils/es_cookie.php';
require SITE_PATH_PATH.'system/utils/es_session.php';
require_once SITE_PATH_PATH.'system/db/db.php';
define('DB_PREFIX', app_conf('DB_PREFIX'));
if(!file_exists(SITE_PATH_PATH.'public/runtime/db_cache/'))
	mkdir(SITE_PATH_PATH.'public/runtime/db_cache/',0777);
$pconnect = false;
$db = new mysql_db(app_conf('DB_HOST').":".app_conf('DB_PORT'), app_conf('DB_USER'),app_conf('DB_PWD'),app_conf('DB_NAME'),'utf8',$pconnect);
//end 定义DB

if(es_cookie::is_set("fanwe_member_data"))
{
	$member_data = es_cookie::get("fanwe_member_data");
	$member_data =  unserialize(base64_decode($member_data));
	$cookie_seller  = $db->getRow("select * from ".DB_PREFIX."seller_account where name = '".$member_data['name']."'");
	if($cookie_seller&&$cookie_seller['status']==1&&md5("cookie_".$cookie_seller['password'])==$member_data['password'])
	{
		$GLOBALS['seller_info'] = $cookie_seller;
	}
}

if(!$GLOBALS['seller_info'])
{
	header("location:404.html");
	exit();
}	
$GLOBALS['sys_config'] = require SITE_PATH_PATH.'system/config.php';
*/
?>

<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_add_bank
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){

			$root['user_login_status'] = 1;
			
			if(intval($user['idcardpassed'])==0){
				$root['response_code'] = 0;
				$root['show_err'] ="您的实名信息尚未认证,为保护您的账户安全，请先完成实名认证。";
			}else{
				$root['response_code'] = 1;
				$root['real_name'] = $user['real_name'];
				$bank_list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."bank ORDER BY is_rec DESC,sort DESC,id ASC");				
				$root['item'] = $bank_list;
			}			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>

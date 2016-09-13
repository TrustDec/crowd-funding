<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/message.php';
class deal_msg
{
	public function index(){
		
		//分页
		$page = intval($GLOBALS['request']['p']);
		if($page==0)
			$page = 1;
		
		
		$deal_id = intval( $GLOBALS ['request']['deal_id']);
		
	
		$rel_table = 'deal';

		$condition = "rel_table = '".$rel_table."' and rel_id = ".$deal_id;
	
		if(app_conf("USER_MESSAGE_AUTO_EFFECT")==0)
		{
			$condition.= " and user_id = ".intval($GLOBALS['user_info']['id']);
		}
		else 
		{
			//留言是否需要审核
			$message_type = $GLOBALS['db']->getOne("select is_effect from ".DB_PREFIX."message_type where type_name='".$rel_table."'");			
			if($message_type ==0)
			{
				$condition.= " and user_id = ".intval($GLOBALS['user_info']['id']);
			}
		}

		$root = array();
		
		//分页
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");
		$msg_condition = $condition." AND is_effect = 1 ";
		
		$message = get_message_list($limit,$msg_condition);
						
		$root['page'] = array("page"=>$page,"page_total"=>ceil($message['count']/app_conf("PAGE_SIZE")));
		
		foreach($message['list'] as $k=>$v){
			$msg_sub = get_message_list("","pid=".$v['id'],false);
			$message['list'][$k]["sub"] = $msg_sub["list"];
		}		
		
		$root['response_code'] = 1;
		//$root['show_err'] = ;  //当response_code != 0 时，返回：错误内容
		$root['message_list'] = $message['list'];
		
		output($root);		
	}
}
?>

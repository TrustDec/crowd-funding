<?php
// +----------------------------------------------------------------------
// | 问卷调查
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/page.php';
class deal_voteModule extends BaseModule
{
	public function index()
	{
			if(!$GLOBALS['user_info'])
			app_redirect(url("user#login"));
			$user_id=intval($GLOBALS['user_info']['id']);
			$now =NOW_TIME;;
			$deal_id = intval($_REQUEST['id']);	
			$deal_vote = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_vote where deal_id = $deal_id and begin_time < ".$now." and (end_time = 0 or end_time > ".$now.") order by id desc limit 1");
			$deal_name = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal where id=$deal_id");
			$GLOBALS['tmpl']->assign("deal_name",$deal_name);
			$user_list =$GLOBALS['db']->getAll("select user_id from ".DB_PREFIX."deal_order where order_status =3 and deal_id = ".$deal_id." group by user_id");
			
			//$user_num 总的投票人数
			$user_num =$GLOBALS['db']->getOne("select count(DISTINCT user_id) from ".DB_PREFIX."deal_order where order_status =3 and deal_id = ".$deal_id);
			
			$deal_vote_log =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_vote where deal_id =".$deal_id." order by id desc limit 1");
			$deal_vote_log_sum =$deal_vote_log['yes_num']+$deal_vote_log['no_num'];
			if($user_num == $deal_vote_log_sum){
				if($deal_vote_log['yes_num'] > $deal_vote_log['no_num']){
					$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set status =1 where id = ".$deal_vote_log['id']);
					showErr("该项目投票已经结束了，最终的结果是同意！");
				}
				else{
					$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set status =2 where id = ".$deal_vote_log['id']);
				}
			}
			else{
				if($deal_vote_log['end_time']<$now){
					//投票已经结束了，未投票的系统，系统默认为同意
					if($user_num > $deal_vote_log_sum){
						$deal_vote_user =$GLOBALS['db']->getAll("select user_id from ".DB_PREFIX."deal_vote_log where deal_vote_id =".$deal_vote_log['id']);
						foreach($user_list as $k=>$v)
						{
							if($deal_vote_user){
								foreach($deal_vote_user as $kk=>$vv)
								{
									if($v['user_id'] !=$vv['user_id']){
										$data['user_id'] = $v['user_id'];
										$data['deal_vote_id'] = $deal_vote_log['id'];	
										$data['vote_status'] = 1;
										
										$GLOBALS['db']->autoExecute(DB_PREFIX."deal_vote_log",$data,"INSERT","","SILENT");
										$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set yes_num = yes_num + 1 where id = ".$deal_vote_log['id']);	
									}
								}
								
							}
							else{
								$data['user_id'] = $v['user_id'];
								$data['deal_vote_id'] = $deal_vote_log['id'];	
								$data['vote_status'] = 1;
								
								$GLOBALS['db']->autoExecute(DB_PREFIX."deal_vote_log",$data,"INSERT","","SILENT");
								$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set yes_num = yes_num + 1 where id = ".$deal_vote_log['id']);	
							
							}
							
						}
					}
						//showErr("该项目投票已经结束了，未投票的系统，系统默认为同意！");
				}
			}
			$count=0;
			foreach($user_list as $k=>$v)
			{
				if($v['user_id'] ==$user_id){
					$count=$count+1;
				}
			}
			if($count <1){
				showErr("您不是该项目的支持人，不能进行投票！");	
			}
			$deal_vote_log_num =$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_vote_log where user_id =".$user_id."  and deal_vote_id =".$deal_vote['id']);
			if($deal_vote_log_num >0){
				showErr("你已经投过票了，不能再投票");	
			}	
			if($deal_vote)
			{
				$GLOBALS['tmpl']->assign("deal_vote",$deal_vote);
				$GLOBALS['tmpl']->assign("page_title","投票");

			}
			else
			{
				showErr("投票不存在");	
			}
			
			$GLOBALS['tmpl']->display("deal_vote.html");
	}
	
	public function do_deal_vote()
	{
		
		$ajax = intval($_REQUEST['ajax']);	
		if(!$GLOBALS['user_info'])
		{
			showErr("",$ajax,url("user#login"));
		}

		$data['user_id'] = intval($GLOBALS['user_info']['id']);
		$data['deal_vote_id'] = intval($_REQUEST['deal_vote_id']);	
		$data['vote_status'] = intval($_REQUEST['vote_status']);
		
		if($data['vote_status']==0){
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set no_num = no_num + 1 where id = ".$data['deal_vote_id']);
		}else{
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_vote set yes_num = yes_num + 1 where id = ".$data['deal_vote_id']);
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_vote_log",$data,"INSERT","","SILENT");
		$data_id = intval($GLOBALS['db']->insert_id());
		if($data_id>0)
		{
			showSuccess("投票成功",$ajax,'');
		}
		else
		{
			showErr("投票失败",$ajax,'');
		}
}
}
?>
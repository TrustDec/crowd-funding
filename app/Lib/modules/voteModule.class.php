<?php
// +----------------------------------------------------------------------
// | 问卷调查
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/page.php';
class voteModule extends BaseModule
{
	public function index($debug=false,$data=array())
	{
		$now =NOW_TIME;
		$id=intval($_REQUEST['id']);
		if($debug)$id=intval($data['id']);
		$vote = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."vote where is_effect = 1 and id=".$id);
		if($vote)
		{
			if($vote['begin_time']>NOW_TIME){
				showErr("问卷还未开始,感谢参与！");
			}elseif($vote['end_time']<NOW_TIME){
				showErr("问卷已结束,感谢参与！");
			}else{
				$vote_ask = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."vote_ask where vote_id = ".intval($vote['id'])." order by is_fill desc,sort asc");
				$vote_ask_num= $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."vote_ask where vote_id = ".intval($vote['id']));
				//必答数量
				$vote_fill_num=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."vote_ask where vote_id = ".intval($vote['id'])." and is_fill=1");
				if($vote_ask_num==0)
				{
					showErr("问卷内容为空,感谢参与！");
				}
				if($debug){
					echo '问题数量：'.sizeof($vote_ask).'<br />';
					echo '问题详细：<br />';
				}
				foreach($vote_ask as $k=>$v)
				{
					$vote_ask[$k]['val_scope'] = preg_split("/[\s]+/",$v['val_scope']);
					if($debug){
						echo "问题:".$vote_ask[$k]['name'].'  答案选项：'.sizeof($vote_ask[$k]['val_scope']).'<br />';
					}
				}
				$GLOBALS['tmpl']->assign("vote_fill_num",$vote_fill_num);
				$GLOBALS['tmpl']->assign("vote",$vote);
				$GLOBALS['tmpl']->assign("vote_ask",$vote_ask);
				$GLOBALS['tmpl']->assign("other_vote","其他");
				$GLOBALS['tmpl']->assign("page_title","问卷调查");
			}
		}
		else
		{
			showErr("当前没有进行中的调查,感谢参与！");	
		}
		$GLOBALS['tmpl']->assign("mobile",$GLOBALS['user_info']['mobile']);
		$GLOBALS['tmpl']->assign("email",$GLOBALS['user_info']['email']);
		if(!$debug){
			$GLOBALS['tmpl']->display("vote_index.html");
		}
	}
	
	public function dovote($debug=false,$data=array())
	{
		if($debug){
			$_REQUEST = $data;
		}
		$ajax = intval($_REQUEST['ajax']);
		$answers=$_REQUEST['name'];
		$is_fills=0;//必答数量
		if(sizeof($answers)>0){
			foreach($answers as $vote_ask_id=>$names){
				$ask_id_type="ask_fill_".$vote_ask_id;
				$ask_type=$_REQUEST[$ask_id_type];	
				if($ask_type){
					$is_fills++;
				}
			}
			if($is_fills<$_REQUEST['vote_fill_num']){
				showErr("还有未答完的题目，其中带*的为必答，请查看！",$ajax,'');
			}
		}else{
			showErr("未回答任何题目，请回答！",$ajax,'');
		}	
		$mobile=trim($_REQUEST['mobile']);
		$email=trim($_REQUEST['email']);
		if($mobile){
			if(!check_mobile($mobile)) showErr("手机格式错误，请重新填写！",$ajax,'');
		}
		if($email){
			if(!check_email($email)) showErr("邮箱格式错误，请重新填写！",$ajax,'');
		}
		$vote_id = intval($_REQUEST['vote_id']);
		if(check_ipop_limit(get_client_ip(),"vote",0,$vote_id))
		{
			$value=$answers;
			$other_vote=$_REQUEST['vote_other'];
			foreach($answers as $vote_ask_id=>$names)
			{
				foreach($names as $kk=>$name)
				{
					$name = htmlspecialchars(addslashes(trim($name)));
					$result = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."vote_result where name = '".$name."' and vote_id = ".$vote_id." and vote_ask_id = ".$vote_ask_id);
					$is_add = true;
					if($result)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."vote_result set count = count + 1 where name = '".$name."' and vote_id = ".$vote_id." and vote_ask_id = ".$vote_ask_id);
						if(intval($GLOBALS['db']->affected_rows())!=0)
						{
							$is_add = false;
						}
					}
					if($is_add)
					{
						if($name!='')
						{
							$result = array();
							$other_ask_id="other_".$vote_ask_id;
							if($name==$other_vote){
								$result['name'] =trim($_REQUEST[$other_ask_id]);
								$result['type']=1;
							}else{
								$result['name'] = $name;
								$result['type']=0;
							}
							$result['vote_id'] = $vote_id;
							$result['vote_ask_id'] = $vote_ask_id;
							$result['count'] = 1;
							$insert_result=$GLOBALS['db']->autoExecute(DB_PREFIX."vote_result",$result);
						}
					}
				}
				
			}
			$vote_list = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."vote_list where vote_id = ".$vote_id);
			$vote_list = array();
			$value=$answers;
			foreach($value as $vote_ask_id=>$names){
				foreach($names as $kk=>$name){
					$name = htmlspecialchars(addslashes(trim($name)));
					$other_ask_id="other_".$vote_ask_id;
					if($name==$other_vote) $value[$vote_ask_id][$kk]=trim($_REQUEST[$other_ask_id]);
				}
			}
			$vote_list['vote_id'] = $vote_id;
			$vote_list['value'] = serialize($value);
			$vote_list['user_id']=$GLOBALS['user_info']['id'];
			$vote_list['mobile'] = $mobile;
			$vote_list['email'] = $email;
			$GLOBALS['db']->autoExecute(DB_PREFIX."vote_list",$vote_list);
			if(!$debug)
				showSuccess("问卷提交成功",$ajax,url("vote#index",array('id'=>$vote_id)));
		}
		else
		{
			showErr("你已经提交过该问卷",$ajax,'');
		}
	}
}
?>
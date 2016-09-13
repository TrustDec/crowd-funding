<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 领投资料提交
 */
class led_info_submit
{
	public function index()
	{
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$introduce = strim ( $GLOBALS ['request'] ['introduce'] ); // 领投人的个人简介
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); // 股权众筹ID
		$cates = $GLOBALS ['request'] ['cates']; // 选择分类
		                              
		// 验证参数
		if (strlen ( $introduce ) <= 100)
		{
			$data = responseErrorInfo ( "个人简历必须大于100字" );
			output ( $data );
		}
		$cates = $this->catesSerialize ( $cates );
		if (! $cates)
		{
			$data = responseErrorInfo ( "cates参数错误" );
			output ( $data );
		}
		if (dealIdIsExist ( $deal_id, 1 ) != 1)
		{
			$data = responseErrorInfo ( "deal_id参数错误" );
			output ( $data );
		}
		$user_id = getUserID ( $email, $password );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		$deal_user_id = $GLOBALS ['db']->getRow ( "SELECT user_id FROM " . DB_PREFIX . "deal WHERE id=" . $deal_id );
		if ($deal_user_id == $user_id)
		{
			$data = responseErrorInfo ( "不能投资自己发起的项目" );
			output ( $data );
		}
		
		// 判断投资者认证成功
		$is_investor = $GLOBALS ['db']->getOne ( "select is_investor from " . DB_PREFIX . "user where id=" . $user_id );
		if ($is_investor == 0)
		{
			$data = responseErrorInfo ( "投资者认证未通过" );
			output ( $data );
		}
		
		$followed_is_exist = investorOrFollowedIsExist ( $user_id, $deal_id, 2 );
		if ($followed_is_exist == 0)
		{
			// 不存在跟投不做处理继续执行程序
		} else if ($followed_is_exist == 1)
		{
			$data = responseErrorInfo ( "您已申请跟投，不能再领投" );
			output ( $data );
		} else
		{
			// 跟投大于1的情况,一个人对一个项目只允许一次跟投或者领投
			$data = responseErrorInfo ( "发生错误" );
			output ( $data );
		}
		
		$investor_is_exist = investorOrFollowedIsExist ( $deal_id, $user_id, 1 );
		
		if ($investor_is_exist == 0)
		{
			$dbObject = array ();
			$dbObject ['type'] = 1;
			$dbObject ['introduce'] = $introduce;
			$dbObject ['deal_id'] = $deal_id;
			$dbObject ['user_id'] = $user_id;
			$dbObject ['cates'] = $cates;
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "investment_list", $dbObject );
			$id = $GLOBALS ['db']->insert_id ();
			if ($id > 0)
			{
				$data = responseSuccessInfo ( "提交领投资料成功" );
				output ( $data );
			} else
			{
				$data = responseErrorInfo ( "提交领投资料失败" );
				output ( $data );
			}
		} else if ($investor_is_exist == 1)
		{
			$sql = "select status from " . DB_PREFIX . "investment_list where deal_id = " . $deal_id . " and user_id=" . $user_id;
			$result_investment_list = $GLOBALS ['db']->getRow ( $sql );
			$status = $result_investment_list ['status'];
			
			if ($status == 0)
			{
				$data = responseErrorInfo ( "领投资料正在审核" );
				output ( $data );
			}
			if ($status == 1)
			{
				$data = responseErrorInfo ( "该用户已经通过审核" );
				output ( $data );
			}
			
			$sql = "update " . DB_PREFIX . "investment_list set status=0,introduce=" . "'" . $introduce . "'" . ",cates = '" . $cates . "' where user_id = " . $user_id . " and deal_id = " . $deal_id;
			
			$GLOBALS ['db']->query ( $sql );
			$data = responseSuccessInfo ( "审核资料修改成功");
			output ( $data );
		} else
		{
			// 领投大于1的情况,一个人对一个项目只允许一次跟投或者领投
			$data = responseErrorInfo ( "发生错误" );
			output ( $data );
		}
	}
	
	public function edit()
	{
		$root = array ();
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                    
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		$GLOBALS['user_info']=$user;
		if(!$user_id)
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
			output($root);
		}else
		{
			$root ['user_login_status'] = 1;
		}
		
		$investor_id = $GLOBALS ['request'] ['id']; // 编辑的id
		$introduce = strim ( $GLOBALS ['request'] ['introduce'] ); // 领投人的个人简介
		$cates = $GLOBALS ['request'] ['cates']; // 选择分类
		
		// 验证参数
		if (strlen ( $introduce ) <= 100)
		{
			$root = responseErrorInfo ( "个人简历必须大于100字" );
			output ( $root );
		}
		$cates = $this->catesSerialize ( $cates );
		if (! $cates)
		{
			$root = responseErrorInfo ( "cates参数错误" );
			output ( $root );
		}
		
		
		$investor_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "investment_list where id=".$investor_id." and user_id= " . $user_id . " and type=1");
		if( !$investor_info)
		{
			$root = responseErrorInfo ( "没有找到领头申请" );
			output ( $root );
		}
		
		if (dealIdIsExist ( $investor_info['deal_id'], 1 ) != 1)
		{
			$root = responseErrorInfo ( "deal_id参数错误" );
			output ( $root );
		}
		
		if($investor_info['status'] ==1)
		{
			$root = responseErrorInfo ( "已通过审核" );
			output ( $root );
		}
		
		if($investor_info['status']==2){
 			$investor_info['status']=0;
 		}
 		
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."investment_list set introduce='".$introduce."',cates='".$cates."',create_time=".NOW_TIME.",status=".$investor_info['status']." where id=$investor_id ");
		$root ['response_code'] = 1;
		$root ['info'] = "编辑成功";
		output ( $root );
	}
	
	private function catesSerialize($cates)
	{
		$catesArray = array ();
		$de_json = json_decode ( $cates, TRUE );
		$count_json = count ( $de_json );
		if ($count_json > 3 || $count_json == 0)
		{
			return false;
		}
		
		for($i = 0; $i < $count_json; $i ++)
		{
			$name = $GLOBALS ['db']->getOne ( "select name from " . DB_PREFIX . "deal_cate where id=" . $de_json [$i] );
			if ($name == '')
			{
				goto end;
			}
			$catesArray [$de_json [$i]] = $name;
		}
		$cates = serialize ( $catesArray );
		return $cates;
		end:
		return false;
	}
}
?>
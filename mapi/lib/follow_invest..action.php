<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 跟投接口
 */
class follow_invest
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); // 股权众筹ID
		                                                        
		// 验证参数
		$deal_id_is_exist = dealIdIsExist ( $deal_id, 1 );
		if ($deal_id_is_exist != 1)
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
		
		// 判断投资者认证成功
		$is_investor = $GLOBALS ['db']->getOne ( "select is_investor from " . DB_PREFIX . "user where id=" . $user_id );
		if ($is_investor == 0)
		{
			$data = responseErrorInfo ( "投资者认证未通过" );
			output ( $data );
		}
		
		// 领投是否申请、领投申请状态
		$whether_apply = $GLOBALS ['db']->getRow ( "select count(*) as count,status from " . DB_PREFIX . "investment_list where type=1 and user_id=" . $user_id . " and deal_id=" . $deal_id );
		
		if ($whether_apply ['count'] == 0)
		{
			/* 1.“领投”未申请->进入跟投流程 */
			$money = need_mortgate ();
			if ($GLOBALS ['user_info'] ['mortgage_money'] < $money)
			{
				$result ['status'] = 1;
				$result ['info'] = "请交纳诚意金！";
				$result ['url'] = url ( "account#mortgate_pay" );
			}
			if ($GLOBALS ['user_info'] ['mortgage_money'] == 0)
			{
				$result ['status'] = 0;
				$result ['info'] = "您未交纳诚意金！";
				$result ['url'] = url ( "investor#pay_mortgage_money", array (
						'deal_id' => $deal_id,
						'money' => $money 
				) );
			} else
			{
				// 调用“询价认筹”页面
				$result ['status'] = 3;
				// 剩余询价次数(type=0表示询价)
				$num = $GLOBALS ['db']->getOne ( "SELECT count(*) FROM " . DB_PREFIX . "investment_list WHERE deal_id=" . $deal_id . " AND user_id=" . $user_id . " AND type=0" );
				
				$inquiry_num = get_ask () - $num;
				$GLOBALS ['tmpl']->assign ( "user_id", $user_id );
				$GLOBALS ['tmpl']->assign ( "deal_id", $deal_id );
				$GLOBALS ['tmpl']->assign ( "inquiry_num", $inquiry_num );
				$result ['html'] = $GLOBALS ['tmpl']->fetch ( "inc/enquiry_index.html" );
			}
		}
		if ($whether_apply ['count'] == 1)
		{
			/**
			 * 2.“领投”已申请
			 * (1)申请领投未通过->进入跟投流程（提示申请领投未通过，是否进入跟投【是，删除“领投”数据】）
			 * (2)申请领投已通过->提示:（你申请领投人成功，请去领投进行投资）
			 * (3)申请领投审核中->提示:（是否取消“领投申请，进行跟投”【是，删除“领投申请数据”】）
			 */
			// leader_status(此用户的领投状态):0表示“领投申请”未审核，1“领投申请”通过，2“领投申请”不通过
			$leader_status = $whether_apply ['status'];
			
			if ($leader_status == 0)
			{
				// “领投申请”未审核
				$result ['status'] = 7;
				$result ['info'] = "您已申请领投,是否取消!";
			}
			if ($leader_status == 1)
			{
				// “领投申请”通过
				$result ['status'] = 8;
				$result ['info'] = "您已为领投人,无需再进行跟投！";
			}
			if ($leader_status == 2)
			{
				// “领投申请”不通过
				$result ['status'] = 9;
				if ($GLOBALS ['db']->query ( "DELETE FROM " . DB_PREFIX . "investment_list WHERE user_id=" . $user_id . " AND deal_id=" . $deal_id . " AND type=1 AND status=2" ) > 0)
				{
					$this->ajax_continue_investor ();
					$result ['info'] = "欢迎跟投！";
				} else
				{
					$result ['info'] = "系统繁忙，请您稍后重试！";
				}
			}
		} else
		{
			$data = responseErrorInfo ( "发生错误" );
			output ( $data );
		}
	}
}

?>
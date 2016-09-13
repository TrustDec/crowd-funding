<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 获取用户领投资料
 */
class uc_investment
{
	public function index()
	{
		$type = 1; // 1表示领投
		           
		// 获取参数
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
		
		$investor_is_exist = investorOrFollowedIsExist ( $deal_id, $user_id, $type );
		
		if ($investor_is_exist == 0)
		{
			$data = responseSuccessInfo ( "成功" );
			$data ['investor_is_exist'] = $investor_is_exist;
			$data ['result_investment'] = Null;
			output ( $data );
		} else if ($investor_is_exist == 1)
		{
			$sql = "select cates,introduce,status from " . DB_PREFIX . "investment_list where deal_id = " . $deal_id . " and user_id=" . $user_id . " and type=" . $type;
			$result_investment = $GLOBALS ['db']->getRow ( $sql );
			/* status 0 未审核 1 审核通过 2审核未通过 */
			$status = $result_investment ['status'];
			
			if ($status == 0)
			{
				$data = responseSuccessInfo ( "您的申请已在审核中!" );
				$data ['investor_is_exist'] = $investor_is_exist;
				$result_investment ['cates'] = unserialize ( $result_investment ['cates'] );
				
				$cates = formateCates ( $result_investment ['cates'] );
				$result_investment ['cates'] = $cates;
				$data ['result_investment'] = $result_investment;
				output ( $data );
			} else if ($status == 1)
			{
				/*
				 * handling_status:0表示错误，1表示进行投资，2表示申请领头权限，3需要支付诚意金,4 追加投资
				 * 5追加资金审核不通过 6申请领头权限不通过(PC端参数所需返回6种，手机端只用，其他用$status判断如何处理)
				 */
				
				// 判断是否有诚意金
				$margator_money = $GLOBALS ['db']->getOne ( "SELECT mortgage_money FROM " . DB_PREFIX . "user WHERE id=" . $user_id );
				if ($margator_money >= need_mortgate ())
				{
					// 是否已经支付过了(判断钱)
					$num = $GLOBALS ['db']->getOne ( "SELECT count(*) from " . DB_PREFIX . "investment_list WHERE user_id=" . $user_id . " AND money!=''" );
					if ($num > 0)
					{
						// 调用追加资金页面"
						$data = responseSuccessInfo ( "调用追加资金页面" );
						$data ['handling_status'] = 4;
						output ( $data );
					} else
					{
						// 调用领投资金页面
						$data = responseSuccessInfo ( "调用领投资金页面" );
						$data ['handling_status'] = 1;
						output ( $data );
					}
				} else
				{
					$data = responseSuccessInfo ( "需要支付诚意金" );
					$data ['handling_status'] = 3;
					output ( $data );
				}
			} else if ($status == 2)
			{
				$data = responseSuccessInfo ( "审核未通过请重新提交信息" );
				$data ['investor_is_exist'] = $investor_is_exist;
				$result_investment ['cates'] = unserialize ( $result_investment ['cates'] );
				$cates = formateCates ( $result_investment ['cates'] );
				$data ['result_investment'] = $result_investment;
				output ( $data );
			} else
			{
				$data = responseErrorInfo ( "发生异常" );
				output ( $data );
			}
		} else
		{
			// 跟投大于1的情况,一个人对一个项目只允许一次跟投或者领投
			$data = responseErrorInfo ( "发生异常" );
			output ( $data );
		}
	}
}

?>
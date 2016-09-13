<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 删除领投,是未审核的数据接口
 */
class delete_leader_investor
{
	public function index()
	{
		$email = strim ($GLOBALS ['request']['email'] );
		$password = strim ($GLOBALS ['request'] ['pwd'] );
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
		
		if ($GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "investment_list where user_id=" . $user_id . " and deal_id=" . $deal_id . " and type=1 and status=0" ) > 0)
		{
			$data = responseSuccessInfo ( "领投申请取消成功,请进行跟投!" );
		} else
		{
			$data = responseErrorInfo ( "领投申请取消失败!" );
		}
		output ( $data );
	}
}

?>
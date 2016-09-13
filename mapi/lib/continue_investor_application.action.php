<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 跟投按钮判断接口
 */
class continue_investor_application
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); // 股权众筹ID
		
		if (dealIdIsExist ( $deal_id, 1 ) != 1)
		{
			$data = responseErrorInfo ( "deal_id参数错误" );
			output ( $data );
		}
		
		$user = user_check ( $email, $password );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$result = investor_continue ( $user_id, $deal_id, 'app', $user );
		$result ['response_code'] = 1;
		if ($result ['status'] == 1)
		{
			// 剩余询价次数(type=0表示询价)
			$num = $GLOBALS ['db']->getOne ( "SELECT count(*) FROM " . DB_PREFIX . "investment_list WHERE deal_id=" . $deal_id . " AND user_id=" . $user_id . " AND type=0" );
			$inquiry_num = get_ask () - $num;
			$result ['inquiry_num'] = $inquiry_num;
		}
		
		output ( $result );
	}
}

?>
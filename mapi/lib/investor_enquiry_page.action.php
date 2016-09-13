<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 询价下一步判断
 */
class investor_enquiry_page
{
	public function index()
	{
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); // 股权众筹ID
		$enquiry = intval ( $GLOBALS ['request'] ['enquiry'] ); // 0不参与询价无条件接受项目最终估值 1询价
		
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
	
		if ($enquiry<0 || $enquiry >1)
		{
			$data = responseErrorInfo ( "enquiry参数错误" );
			output ( $data );
		}
		$result = investor_enquiry_page ( $user_id, $deal_id, $enquiry );
	
		$result ['response_code'] = 1;
		output ( $result );
	}
}

?>
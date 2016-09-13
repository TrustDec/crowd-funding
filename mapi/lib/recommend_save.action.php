<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 推荐自己的项目给他人
 */
class recommend_save
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$id = intval ( $_REQUEST ['id'] ); // 被推荐人ID
		$deal_id = intval ( $GLOBALS ['request'] ['deal_id'] ); // 推荐项目id
		$memo = strim ( $GLOBALS ['request'] ['memo'] ); // 推荐理由
		
		$user = user_check ( $email, $pwd );
		$recommend_user_id = intval ( $user ['id'] ); // 推荐人
		if ($recommend_user_id <= 0)
		{
			$result = responseNoLoginParams ();
			output ( $result );
		}
		
		if ($recommend_user_id == $id)
		{
			$result = responseErrorInfo ( "亲!不能给自己推荐项目哦!" );
			output ( $result );
		}
		
		$sql = "select name,image,type,user_id from " . DB_PREFIX . "deal where id = " . $deal_id." and user_id = ".$recommend_user_id;
		$deal_info = $GLOBALS ['db']->getRow ( $sql );
		if (! $deal_info)
		{
			$result = responseErrorInfo ( "deal_id参数错误！" );
			output ( $result );
		}
		if ($memo == '')
		{
			$result = responseErrorInfo ( "推荐理由不能为空！" );
			output ( $result );
		}
		
		$create_time = NOW_TIME;
		if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "recommend", array (
				"memo" => $memo,
				"deal_id" => $deal_id,
				"user_id" => $id,
				"recommend_user_id" => $recommend_user_id,
				"create_time" => $create_time,
				"deal_type" => $deal_info ['type'],
				"deal_name" => $deal_info ['name'],
				"deal_image" => $deal_info ['image'] 
		), "INSERT" ) > 0)
		{
			$result = responseSuccessInfo ( "推荐成功!" );
			output ( $result );
		} else
		{
			$result = responseErrorInfo ( "系统繁忙,请您稍后重试！" );
			output ( $result );
		}
	}
}

?>
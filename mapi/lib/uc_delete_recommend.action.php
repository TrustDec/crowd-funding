<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 用户中心删除推荐项目
 */
class uc_delete_recommend
{
	
	public function index()
	{
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$id = intval ( $GLOBALS ['request'] ['id'] );//必须是推荐列表的ID
		
		$user = user_check ( $email, $password );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$result = responseNoLoginParams ();
			output ( $result );
		}
		if ($id <= 0)
		{
			$result = responseErrorInfo ( "id参数错误" );
			output ( $result );
		}
		
		$is_recommend = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "recommend where id = " . $id . " and user_id = " . $user_id );
		if ($is_recommend)
		{
			if ($GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "recommend where id = " . $id ) > 0)
			{
				$result = responseSuccessInfo ( "删除成功！" );
				output ( $result );
			} else
			{
				$result = responseErrorInfo ( "删除失败！" );
				output ( $result );
			}
		} else
		{
			$result = responseErrorInfo ( "系统繁忙,请您稍后重试！" );
			output ( $result );
		}
	}
}


?>
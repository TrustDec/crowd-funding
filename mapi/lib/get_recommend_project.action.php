<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 获取用户推荐项目 
 */
class get_recommend_project
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['id'] ); // 被推荐人ID
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] ); // 推荐人
		if ($user_id <= 0)
		{
			$result = responseNoLoginParams ();
			output ( $result );
		}
		/*
		 * if ($id == $user_id) { $result = responseErrorInfo ( "亲!不能给自己推荐项目哦!"
		 * ); output ( $result ); }
		 */
		
		$effective_deal_info = get_effective_deal_info ( $user_id );
		if (! $effective_deal_info)
		{
			$result = responseErrorInfo ( "请您先去PC端创建项目！" );
			output ( $result );
		} else
		{		
			$result = responseSuccessInfo ( "", 1, "用户项目列表" );
			$result ['effective_deal_info'] = $this->formatEffective_deal_info ( $effective_deal_info );
			output ( $result );
			return false;
		}
	}
	// 格式化参数
	private function formatEffective_deal_info($effective_deal_info)
	{
		foreach ( $effective_deal_info as $k => $v )
		{
			$effective_deal_info [$k] ['image'] = get_abs_img_root ( $effective_deal_info [$k] ['image'] );
		}
		return $effective_deal_info;
	}
}

?>
<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 个人中心推荐的项目
 */
class uc_recommend
{
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		$page = $page == 0 ? 1 : $page;
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$recommend_info = $GLOBALS ['db']->getAll ( "SELECT * FROM " . DB_PREFIX . "recommend WHERE user_id=" . $user_id . " ORDER BY create_time DESC limit $limit" );
		$recommend_count = $GLOBALS ['db']->getOne ( "SELECT count(*) FROM " . DB_PREFIX . "recommend WHERE user_id=" . $user_id );
		
		$result = responseSuccessInfo ( "", 1, "个人中心推荐的项目" );
		$result ['recommend_info'] = $this->formateRecommend_info ( $recommend_info );
		$result ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $recommend_count / $page_size ),
				"page_size" => intval ( $page_size ) 
		);
		output ( $result );
	}
	private function formateRecommend_info($recommend_info)
	{
		foreach ( $recommend_info as $k => $v )
		{
			$recommend_info [$k] ['create_time'] = to_date ( $recommend_info [$k] ['create_time'] );
			$recommend_info [$k] ['deal_image'] = get_abs_img_root ( $recommend_info [$k] ['deal_image'] );
		}
		return $recommend_info;
	}
}

?>
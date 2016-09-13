<?php
/**
 * @author 作者
 * @version 创建时间：2015-5-20  类说明 个人中心我的项目列表
 */
class uc_account_project
{
	//会员中心 项目列表
	//产品
	public function index()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		if(app_conf ( "INVEST_STATUS" ) == 2)
		{
			$data = responseErrorInfo("不支持产品众筹");
			output ( $data );
		}
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$condition = " type=0 ";
		
		$deal_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where $condition and user_id = " . $user_id . " and is_delete = 0 order by id desc,create_time desc limit " . $limit );
		
		$deal_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal where $condition and user_id = " . $user_id . " and is_delete = 0" );
		foreach ( $deal_list as $k => $v )
		{
			$deal_list [$k] = formateType1DealInfo ( $deal_list [$k] );
		}
		
		$data = responseSuccessInfo ( null, 1, "个人中心我的项目列表" );
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_count / $page_size ) 
		);
		
		$data ['deal_list'] = $deal_list;
		output ( $data );
	}
	
	//股权
	public function invest()
	{
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] );
		$page = intval ( $GLOBALS ['request'] ['page'] );
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		if(app_conf ( "INVEST_STATUS" ) == 1)
		{
			$data = responseErrorInfo("不支持产品众筹");
			output ( $data );
		}
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$condition = " type=1 ";
		
		$deal_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where $condition and user_id = " . $user_id . " and is_delete = 0 order by id desc,create_time desc limit " . $limit );
		$deal_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal where $condition and user_id = " . $user_id . " and is_delete = 0" );
		foreach ( $deal_list as $k => $v )
		{
			$deal_list [$k] = formateType2DealInfo ( $deal_list [$k] );
		}
		
		$data = responseSuccessInfo ( null, 1, "个人中心我的项目列表" );
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_count / $page_size ) 
		);
		
		$data ['deal_list'] = $deal_list;
		output ( $data );
	}
	
	
}
?>
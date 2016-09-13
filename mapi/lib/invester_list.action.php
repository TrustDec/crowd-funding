<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 天使投资人列表接口
 */
class invester_list
{
	public function index()
	{
		
		// 0 普通用户 1 投资人 2机构投资人 -1全部
		$is_investor = intval ( ($GLOBALS ['request'] ['is_investor']) );
		$loc = strim ( $GLOBALS ['request'] ['loc'] ); // 地区
		$city = strim ( $GLOBALS ['request'] ['city'] ); // 城市
		$page = intval ( $GLOBALS ['request'] ['page'] );
		
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$param = array ();
		
		if ($loc != "")
		{
			$param = array_merge ( $param, array (
					"loc" => $loc 
			) );
		}
		if ($city != "")
		{
			$param = array_merge ( $param, array (
					"city" => $city 
			) );
		}
		
		$city_list = load_dynamic_cache ( "INDEX_CITY_LIST" );
		if (! $city_list)
		{
			$city_list = $GLOBALS ['db']->getAll ( "select province from " . DB_PREFIX . "user group by province order by create_time desc" );
			set_dynamic_cache ( "INDEX_CITY_LIST", $city_list );
		}
		foreach ( $city_list as $k => $v )
		{
			$temp_param = $param;
			unset ( $temp_param ['city'] );
			$temp_param ['loc'] = $v ['province'];
			$city_list [$k] ['url'] = url ( "investor#invester_list", $temp_param );
		}
		
		$next_pid = 0;
		$region_lv2 = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "region_conf where region_level = 2 order by py asc" ); // 二级地址
		foreach ( $region_lv2 as $k => $v )
		{
			$temp_param = $param;
			unset ( $temp_param ['city'] );
			$temp_param ['loc'] = $v ['name'];
			
			$region_lv2 [$k] ['url'] = url ( "investor#invester_list", $temp_param );
			
			if ($loc == $v ['name'])
			{
				$next_pid = $v ['id'];
			}
		}
		
		if ($next_pid > 0)
		{
			$region_lv3 = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "region_conf where region_level = 3 and `pid`='" . $next_pid . "' order by py asc" ); // 二级地址
			foreach ( $region_lv3 as $k => $v )
			{
				$temp_param = $param;
				$temp_param ['city'] = $v ['name'];
				$region_lv3 [$k] ['url'] = url ( "investor#invester_list", $temp_param );
			}
		}
		
		$condition = "is_effect = 1 ";
		
		if ($loc != "" && loc != '全部')
		{
			$condition .= " and (province = '" . $loc . "') ";
		}
		if ($city != "")
		{
			$condition .= " and (province = '" . $loc . "' and city = '" . $city . "') ";
		}
		
		if ($is_investor < 3 && $is_investor >= 0)
		{
			$condition .= " and is_investor = " . $is_investor;
		}
		
		/* 投资人列表 */
		$invester_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user where " . $condition . " order by create_time desc limit " . $limit );
		$user_level = load_auto_cache ( "user_level" );
		foreach ( $invester_list as $k => $v )
		{
			$invester_list [$k] ['image'] = get_user_avatar_root ( $v ["id"], "middle" ); // 用户头像
			$invester_list [$k] ['user_level_icon'] = get_mapi_user_level_icon ( $user_level, $v ["user_level"] ); // 等级图片
			unset ( $invester_list [$k] ['user_pwd'] ); // 过滤密码参数
		}
		$invester_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where " . $condition );
		
		$data = responseSuccessInfo ( "", 0, "成功" );
		$data ['city_list'] = $city_list;
		$data ['invester_list'] = $invester_list;
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $invester_count / $page_size ),
				"page_size" => intval ( $page_size ) 
		);
		output ( $data );
	}
}

?>
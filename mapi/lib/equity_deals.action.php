<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 股权详情列表页接口
 */
class equity_deals
{
	public function index()
	{
		$key = intval ( $GLOBALS ['request'] ['key'] );
		$cate_id = intval ( $GLOBALS ['request'] ['cate_id'] );
		$state = intval ( $GLOBALS ['request'] ['state'] );
		$loc = strim ( $GLOBALS ['request'] ['loc'] ); // 地区
		$r = strim ( $GLOBALS ['request'] ['r'] ); // 地区
		$page = intval ( $GLOBALS ['request'] ['page'] );
		$page = $page == 0 ? 1 : $page;
		
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		$condition = " type=1 and is_delete = 0 and is_effect = 1 ";
		if ($cate_id > 0)
		{
			$condition .= " and cate_id=" . $cate_id;
		}
		if ($loc != "" && $loc != '全部')
		{
			$condition .= " and (province = '" . $loc . "' or city = '" . $loc . "') ";
		}
		if ($r != "")
		{
			if ($r == "new")
			{
				// "最新上线"
				$condition .= " and " . NOW_TIME . " - begin_time < " . (7*24 * 3600) . " and " . NOW_TIME . " - begin_time > 0 "; // 上线不超过一天
			}
			if ($r == "rec")
			{
				// "推荐项目"
				$condition .= " and is_recommend = 1 ";
			}
			if ($r == "yure")
			{
				// "正在预热"
				$condition .= " and " . NOW_TIME . " < begin_time"; // 上线不超过一天
			}
			if ($r == "nend")
			{
				// "即将结束"
				$condition .= " and end_time - " . NOW_TIME . " < " . (7*24 * 3600) . " and end_time - " . NOW_TIME . " > 0 "; // 当天就要结束
			}
			if ($r == "classic")
			{
				// "经典项目"
				$condition .= " and is_classic = 1 ";
			}
			if ($r == "limit_price")
			{
				// "最高目标金额"
				$condition .= " and max(limit_price) ";
			}
		}
		switch ($state)
		{
			// 筹资成功
			case 1 :
				$condition .= " and is_success=1  ";
				
				break;
			// 筹资失败
			case 2 :
				$condition .= " and end_time < " . NOW_TIME . " and end_time!=0  and is_success=0  ";
				
				break;
			// 融资中
			case 3 :
				$condition .= " and (end_time > " . NOW_TIME . " or end_time=0 ) and begin_time < " . NOW_TIME . " and is_success=0  ";
				break;
		}
		
		if ($key != "")
		{
			$kws_div = div_str ( $key );
			foreach ( $kws_div as $k => $item )
			{
				$kws [$k] = str_to_unicode_string ( $item );
			}
			$ukeyword = implode ( " ", $kws );
			$condition .= " and (match(name_match) against('" . $ukeyword . "'  IN BOOLEAN MODE) or match(tags_match) against('" . $ukeyword . "'  IN BOOLEAN MODE)  or name like '%" . $key . "%') ";
		}
		
		$deal_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal where " . $condition );
		$deal_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where " . $condition . " order by sort asc limit " . $limit );
		foreach ( $deal_list as $k => $v )
		{
			$deal_list [$k] = formateType2DealInfo ( $deal_list [$k] );
		}
		$data = responseSuccessInfo ( "", 0, "股权列表" );
		$data ['deal_list'] = $deal_list;
		$data ['deal_list'] = $deal_list;
		$data ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_count / $page_size ) 
		);
		
		output ( $data );
	}
}

?>
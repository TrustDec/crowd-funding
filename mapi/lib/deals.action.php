<?php
class deals
{
	public function index()
	{
		// $type = intval ( $GLOBALS ['request'] ['type'] );
		// $type = $type == 1 ? 1 : 0; // type 0 是普通众筹，1是股权众筹
		$loc = strim ( $GLOBALS ['request'] ['loc'] ); // 地区
		$state = intval ( $GLOBALS ['request'] ['state'] ); // 状态1
		$tag = strim ( $GLOBALS ['request'] ['tag'] ); // 标签
		$kw = strim ( $GLOBALS ['request'] ['key'] ); // 关键词
		$r = strim ( $GLOBALS ['request'] ['r'] ); // 推荐类型
		//$cate_id = intval ( $GLOBALS ['request'] ['id'] ); // 分类id3
		$cate_id = intval ( $GLOBALS ['request'] ['cate_id'] ); // 分类id3
		
		$page = intval ( $GLOBALS ['request'] ['page'] ); // 分页
		$page = $page == 0 ? 1 : $page;
		$page_size = $GLOBALS ['m_config'] ['page_size'];
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		// ios标志
		if ($cate_id == 0)
		{
			$isAll = 1;
		} else
		{
			$isAll = 0;
		}
		
		$type = 0;
		$condition = " type = " . $type . " and is_delete = 0 and is_effect =1 ";
		// $condition = " is_delete = 0 and is_effect = 1 ";
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
				$condition .= " and end_time - " . NOW_TIME . " < " . (24 * 3600) . " and end_time - " . NOW_TIME . " > 0 "; // 当天就要结束
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
			// 全部
			case 0 :
		
				break;
			// 筹资成功
			case 1 :
				$condition .= " and end_time < " . NOW_TIME . "  and support_amount >= limit_price";
				break;
			// 筹资失败
			case 2 :
				$condition .= " and end_time < " . NOW_TIME . "  and  support_amount < limit_price ";
				break;
			// 筹资中
			case 3 :
				$condition .= " and end_time > " . NOW_TIME . "  and begin_time < " . NOW_TIME . " ";
				break;
		}
		if ($cate_id > 0)
		{
			$condition .= " and cate_id = " . $cate_id;
		}
		if ($loc != "" && $loc != '全部')
		{
			$condition .= " and (province = '" . $loc . "' or city = '" . $loc . "') ";
		}
		if ($tag != "")
		{
			$unicode_tag = str_to_unicode_string ( $tag );
			$condition .= " and match(tags_match) against('" . $unicode_tag . "'  IN BOOLEAN MODE) ";
		}
		
		if ($kw != "")
		{
			$kws_div = div_str ( $kw );
			foreach ( $kws_div as $k => $item )
			{
				$kws [$k] = str_to_unicode_string ( $item );
			}
			$ukeyword = implode ( " ", $kws );
			$condition .= " and (match(name_match) against('" . $ukeyword . "'  IN BOOLEAN MODE) or match(tags_match) against('" . $ukeyword . "'  IN BOOLEAN MODE)  or name like '%" . $kw . "%') ";
		}
		
		//$condition .= " AND (user_level =0 or user_level =1 or user_level ='') ";
		
		$deal_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal where " . $condition );
		
		if ($deal_count > 0)
		{
			$deal_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where " . $condition . " order by sort asc limit " . $limit );
			foreach ( $deal_list as $k => $v )
			{
				$deal_list [$k] = formateType1DealInfo ( $v );
			}
		}
		
		$root ['response_code'] = 1;
		$root ['isAll'] = $isAll;
		$root ['deal_list'] = $deal_list;
		$root ['page'] = array (
				"page" => $page,
				"page_total" => ceil ( $deal_count / $page_size ) 
		);
		
		output ( $root );
	}
}
?>

<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 首页初始化接口
 */
require './lib/init_filter_list.action.php';
// require './lib/deal_func.php';
class init
{
	public function index()
	{
		$root = $this->getMconfig ( $root );
		
		$ios_pack_version = strim ( $GLOBALS ['request'] ['ios_pack_version'] );
		$ios_is_check = check_ios_is_audit ( $GLOBALS ['m_config'] ['ios_check_version'], $ios_pack_version );
		if ($ios_is_check != 1)
		{
			$root = $this->getAppKey ( $root );
		}
		$root ['adv_list'] = $this->getAdvList ( 'top' ); // 首页面广告
		$root ['adv_list_start'] = $this->getAdvList ( 'start' ); // 启动广告
		
		$intval_status = app_conf ( "INVEST_STATUS" );
		$root ['intval_status'] = $intval_status;
		// 首页分类
		// $root ['index_cates'] = file_get_contents(get_domain ().url_mapi_html
		// ( "index_cates#index"));
		$root ['index_cates_url'] = get_domain () . url_mapi_html ( "index_cates#index" );
		// 权限控制
		$new_condition = '';
		$hot_conditon .= ' and d.is_hot=1 ';
		if ($intval_status == 0)
		{
			$new_condition .= 'and d.type=0';
			$hot_conditon .= ' and d.type=1';
		}
		
		// 最新的项目 只支持投权，获取股权的项目，其它取产品项目
		$deal_new_result = mapi_get_deal_list ( '0,4', $new_condition, 'id desc' );
		$deal_new_list = $deal_new_result ['list'];
		if ($intval_status == 2)
		{
			foreach ( $deal_new_list as $k => $v )
			{
				$deal_new_list [$k] = formateType2DealInfo ( $v );
			}
		} else
		{
			foreach ( $deal_new_list as $k => $v )
			{
				$deal_new_list [$k] = formateType1DealInfo ( $v );
			}
		}
		$root ['deal_new_list'] = $deal_new_list;
		
		// 热门的项目 只获取股权项目，当只支持产品项目时，不显示
		if ($intval_status == 0 || $intval_status == 2)
		{
			$deal_hot_result = mapi_get_deal_list ( '0,4', $hot_conditon, 'support_count desc' );
			$deal_hot_list = $deal_hot_result ['list'];
			foreach ( $deal_hot_list as $k => $v )
			{
				$deal_hot_list [$k] = formateType2DealInfo ( $v );
			}
			$root ['deal_hot_list'] = $deal_hot_list;
		}
		
		output ( $root );
	}
	/**
	 * 获得Config信息等
	 */
	private function getMconfig($root)
	{
		$root ['response_code'] = 1;
		$root ['sys_invest_status'] = app_conf ( "INVEST_STATUS" );
		$root ['user_verify'] = app_conf ( "USER_VERIFY" );
		$root ['kf_phone'] = $GLOBALS ['m_config'] ['kf_phone']; // 客服电话
		$root ['kf_email'] = $GLOBALS ['m_config'] ['kf_email']; // 客服邮箱
		$root ['about_info'] = intval ( $GLOBALS ['m_config'] ['about_info'] );
		$root ['version'] = VERSION; // 接口版本号int
		$root ['page_size'] = PAGE_SIZE; // 默认分页大小
		$root ['program_title'] = $GLOBALS ['m_config'] ['program_title'];
		$root ['site_domain'] = str_replace ( "/mapi", "", SITE_DOMAIN . APP_ROOT ); // 站点域名;
		$root ['site_domain'] = str_replace ( "http://", "", $root ['site_domain'] ); // 站点域名;
		$root ['site_domain'] = str_replace ( "https://", "", $root ['site_domain'] ); // 站点域名;
		$root ['reply_email'] = app_conf ( "REPLY_ADDRESS" ); // 回复邮箱
		/* http://zc.fanwe.com/wap/index.php?ctl=help 协议wap地址 */
		$root ['terms_url'] = get_domain () . str_replace ( "/mapi", "", url_wap ( "help" ) );
		/* 虚拟的累计项目总个数，支持总人数，项目支持总金额 */
		$virtual_effect = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "deal where is_effect = 1 and is_delete=0" );
		$virtual_person = $GLOBALS ['db']->getOne ( "select sum((support_count+virtual_person)) from " . DB_PREFIX . "deal_item" );
		$virtual_money = $GLOBALS ['db']->getOne ( "select sum((support_count+virtual_person)*price) from " . DB_PREFIX . "deal_item" );
		$root ['virtual_effect'] = $virtual_effect; // 项目总个数
		$root ['virtual_person'] = $virtual_person; // 累计支持人
		$root ['virtual_money'] = floatval ( $virtual_money ); // 筹资总金额
		$root ['score_trade_number'] = app_conf ( "SCORE_TRADE_NUMBER" ); // 兑换1元所需积分数(填0~900,10的倍数)
		$root ['is_tg'] = is_tg (); // 是否有安装第三方托管
		return $root;
	}
	/**
	 * 首页列表数据
	 */
	private function getInitDealList()
	{
		$index_pro_num = $GLOBALS ['m_config'] ['index_pro_num'];
		if ($index_pro_num > 0)
		{
			$limit = " limit 0,$index_pro_num";
		} else
		{
			$limit = "";
		}
		
		// 权限控制
		$condition = " is_delete = 0 and is_effect = 1 ";
		$condition .= " AND is_recommend='1'";
		if (app_conf ( "INVEST_STATUS" ) == 1)
		{
			$condition .= " AND type=0";
		} else if (app_conf ( "INVEST_STATUS" ) == 2)
		{
			$condition .= " AND type=1";
		}
		$deal_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal where " . $condition . " order by sort asc  " . $limit );
		foreach ( $deal_list as $k => $v )
		{
			if ($deal_list [$k] ['type'] == 1)
			{
				$deal_list [$k] = formateType2DealInfo ( $deal_list [$k] );
			} else
			{
				$deal_list [$k] = formateType1DealInfo ( $deal_list [$k] );
			}
		}
		return $deal_list;
	}
	
	/**
	 * 首页广告
	 * $page top(首页广告),start(启动页)
	 */
	private function getAdvList($page)
	{
		$adv_num = intval ( $GLOBALS ['m_config'] ['adv_num'] ) ? $GLOBALS ['m_config'] ['adv_num'] : 5;
		$where = " and page='" . $page . "'";
		if ($page == 'top')
			$limit = "limit 0,$adv_num";
		else
			$limit = '';
		$index_list = $GLOBALS ['db']->getAll ( " select * from " . DB_PREFIX . "m_adv where status = 1 " . $where . " order by sort asc " . $limit );
		$adv_list = array ();
		foreach ( $index_list as $k => $v )
		{
			if ($v ['img'] != '')
				$v ['img'] = get_abs_img_root ( get_spec_image ( $v ['img'], 640, 240, 1 ) );
			$adv_list [] = $v;
		}
		return $adv_list;
	}
	/**
	 * 新浪 腾讯 微信 appkey
	 */
	private function getAppKey($data)
	{
		if ($GLOBALS ['m_config'] ['wx_app_key'] != '' && $GLOBALS ['m_config'] ['wx_app_secret'] != '')
		{
			$data ['wx_app_api'] = 1;
		} else
		{
			$data ['wx_app_api'] = 0;
		}
		$data ['wx_app_key'] = $GLOBALS ['m_config'] ['wx_app_key'];
		$data ['wx_app_secret'] = $GLOBALS ['m_config'] ['wx_app_secret'];
		if ($GLOBALS ['m_config'] ['qq_app_secret'] != '' && $GLOBALS ['m_config'] ['qq_app_key'] != '')
		{
			$data ['qq_app_api'] = 1;
		} else
		{
			$data ['qq_app_api'] = 0;
		}
		$data ['qq_app_secret'] = $GLOBALS ['m_config'] ['qq_app_secret'];
		$data ['qq_app_key'] = $GLOBALS ['m_config'] ['qq_app_key'];
		if ($GLOBALS ['m_config'] ['sina_app_key'] != '' && $GLOBALS ['m_config'] ['sina_app_secret'] != '')
		{
			$data ['sina_app_api'] = 1;
		} else
		{
			$data ['sina_app_api'] = 0;
		}
		$data ['sina_bind_url'] = $GLOBALS ['m_config'] ['sina_bind_url'];
		$data ['sina_app_secret'] = $GLOBALS ['m_config'] ['sina_app_secret'];
		$data ['sina_app_key'] = $GLOBALS ['m_config'] ['sina_app_key'];
		
		return $data;
	}
	// 获取当前项目列表下的所有子项目
	// 重新组装一个以项目ID为KEY的 统计所有的虚拟人数和虚拟价格
	private function getDealItemVirtualInfo($deal_list)
	{
		$deal_ids = array ();
		foreach ( $deal_list as $k => $v )
		{
			$deal_ids [$k] = $deal_list [$k] ['id'];
		}
		
		$temp_virtual_person_list = $GLOBALS ['db']->getAll ( "select deal_id,virtual_person,price from " . DB_PREFIX . "deal_item where deal_id in(" . implode ( ",", $deal_ids ) . ") " );
		$virtual_person_list = array ();
		
		foreach ( $temp_virtual_person_list as $k => $v )
		{
			$virtual_person_list [$v ['deal_id']] ['total_virtual_person'] += $v ['virtual_person'];
			$virtual_person_list [$v ['deal_id']] ['total_virtual_price'] += $v ['price'] * $v ['virtual_person'];
		}
		return $virtual_person_list;
	}
}

?>
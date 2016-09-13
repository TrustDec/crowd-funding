<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 普通众筹项目详情
 */
require '../system/utils/weixin.php';
class deal_detail
{
	public function index()
	{
		$id = intval ( $GLOBALS ['request'] ['id'] );
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码 // 检查用户,用户密码
		
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		
		if ($user_id > 0)
		{
			$is_focus = $GLOBALS ['db']->getOne ( "select  count(*) from " . DB_PREFIX . "deal_focus_log where deal_id = " . $id . " and user_id = " . $user_id );
			$root ['is_focus'] = $is_focus;
		}else
		{
			$root ['is_focus']=0;
		}
		
		// 权限控制
		$condition = " is_delete = 0 and id = $id";
		
		$deal_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal where " . $condition );
	
		//判断用户是否有权限
		//0 表示未登陆 1表示正常 2表示等级不够 3表示没有认证手机 4表示没有身份认证 5表示身份认证审核中 6表示身份认证审核失败
		$access=mapi_get_level_access($user,$deal_info);
		$root['access']=$access['access'];
		$root['access_info']=$access['access_info'];
		
		$deal_info = $this->formateDealInfo ( $deal_info );
		$biref_url=get_domain().url_mapi_html ( "deal#biref", array ("id" => $id ,"user_id"=>$user_id) );
		$deal_info['biref_url'] =$biref_url;
		//$deal_info['content_html'] =file_get_contents($biref_url);
		
		$root ['deal_list'] = $deal_info;
		$root ['share_url'] = get_domain () . str_replace ( "/mapi", "", url_wap ( "deal#show", array (
				"id" => $id 
		) ) );
		$url = replace_mapi ( url_wap ( "deal#show", array (
				"id" => $id 
		) ) );
		$result ['share_url'] = $url;
		if ($GLOBALS ['m_config'] ['wx_appid'] != '' && $GLOBALS ['m_config'] ['wx_secrit'] != '')
		{
			$weixin_1 = new weixin ( $GLOBALS ['m_config'] ['wx_appid'], $GLOBALS ['m_config'] ['wx_secrit'], $url );
			$wx_url = $weixin_1->scope_get_code ();
			$result ['wx_share_url'] = $wx_url;
		}
		
		output ( $root );
	}
	private function formateDealInfo($deal_info)
	{
		$deal_info_type1 = array ();
		$deal_info_type1 ['id'] = $deal_info ['id'];
		$deal_info_type1 ['type'] = $deal_info ['type'];
		$deal_info_type1 ['name'] = $deal_info ['name'];
		$deal_info_type1 ['user_name'] = $deal_info ['user_name'];
		$deal_info_type1 ['user_level'] = $deal_info ['user_level'];
		$deal_info_type1 ['image'] = get_abs_img_root ( $deal_info ['image'] );
		$deal_info_type1 ['num_days'] = ceil ( ($deal_info ['end_time'] - $deal_info ['begin_time']) / (24 * 3600) );
		$deal_info_type1 ['end_time'] = to_date ( $deal_info ['end_time'], 'Y-m-d' );
		$deal_info_type1 ['begin_time'] = to_date ( $deal_info ['begin_time'], 'Y-m-d' );
		$deal_info_type1 ['create_time'] = to_date ( $deal_info ['create_time'], 'Y-m-d' );
		$deal_info_type1 ['percent'] = round ( (($deal_info ['support_amount'] + $deal_info ['virtual_price']) / $deal_info ['limit_price']) * 100 );
		$deal_info_type1 ['limit_price'] = $deal_info ['limit_price'];
		$deal_info_type1 ['support_amount'] = $deal_info ['support_amount'];
		$deal_info_type1 ['person'] = $deal_info ['support_count'] + $deal_info ['virtual_num'];
		$deal_info_type1 ['total_virtual_price'] = $deal_info ['support_amount'] + $deal_info ['virtual_price'];
		$deal_info_type1 ['total_virtual_price_format'] = $deal_info ['support_amount'] + $deal_info ['virtual_price'] . '元';
		$deal_info_type1 ['focus_count'] = $deal_info ['focus_count'];
		$deal_info_type1 ['support_count'] = $deal_info ['support_count'];
		$deal_info_type1 ['source_vedio'] = $deal_info ['source_vedio'];
		
		$deal_info_type1 ['virtual_person'] = $GLOBALS ['db']->getOne ( "select sum(virtual_person) from " . DB_PREFIX . "deal_item where deal_id=" . $deal_info ['id'] );
		
		$deal_info_type1 ['vedio'] = '<div style="text-align: center;"><iframe width=100% height=300px src="' . $deal_info ['source_vedio'] . '" frameborder=0 allowfullscreen></iframe></div>';
		if ($deal_info ['source_vedio'] == '')
		{
			$deal_info_type1 ['vedio'] = null;
		}
		
		$deal_info_type1 ['ips_bill_no'] = $deal_info ['ips_bill_no'];
		$is_tg=is_tg();
		if($deal_info ['ips_bill_no']&& $is_tg)
		{
			$deal_info_type1 ['ips_bill_no_pay']=1;//用托管支付
		}
		else{
			$deal_info_type1 ['ips_bill_no_pay']=0;//网站支付
		}
		
		//$pattern = "/<img([^>]*)\/>/i";
		//$replacement = "<img width=100% $1 />";
		//$deal_info_type1 ['content'] = preg_replace ( $pattern, $replacement, get_abs_img_root ( $deal_info ['description'] ) );
		
		$deal_info_type1['brief']=$deal_info['brief'];//简介
		return handleDealType0Status ( $deal_info, $deal_info_type1 );
	}
}

?>

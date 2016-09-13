<?php
 

/**
* 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
*
* @access  public
* @param   string      $sql        SQL查询串
* @return  string      返回已过滤掉注释的SQL查询串。
*/
function remove_comment($sql)
{
	/* 删除SQL行注释，行注释不匹配换行符 */
	$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);

	/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
	//$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
	$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);

	return $sql;
}

function emptyTag($string)
{
		if(empty($string))
			return "";

		$string = strip_tags(trim($string));
		$string = preg_replace("|&.+?;|",'',$string);

		return $string;
}

function get_abs_img_root($content)
{	

	return str_replace("./public/",get_domain().APP_ROOT."/../public/",$content);
	//return str_replace('/mapi/','/',$str);
}
//
function get_abs_img_root_wap($content)
{	
	return str_replace("./public/",get_domain().APP_ROOT."/public/",$content);
	//return str_replace('/mapi/','/',$str);
}

function get_abs_url_root($content)
{
	$content = str_replace("./",get_domain().APP_ROOT."/../",$content);	
	return $content;
}


function user_check($username_email,$pwd)
{
	//$username_email = addslashes($username_email);
	//$pwd = addslashes($pwd);
	if($username_email&&$pwd)
	{
		//$sql = "select *,id as uid from ".DB_PREFIX."user where (user_name='".$username_email."' or email = '".$username_email."') and is_delete = 0";
		$sql = "select *,id as uid from ".DB_PREFIX."user where (user_name='".$username_email."' or email = '".$username_email."' or mobile = '".$username_email."') ";
		$user_info = $GLOBALS['db']->getRow($sql);

		$is_use_pass = false;
		if (strlen($pwd) != 32){					
			if($user_info['user_pwd']==md5($pwd.$user_info['code']) || $user_info['user_pwd']==md5($pwd)){
				$is_use_pass = true;
				
			}
		}
		else{
			if($user_info['user_pwd']==$pwd){
				$is_use_pass = true;
			}
		}
		if($is_use_pass)
		{
			es_session::set("user_info",$user_info);
			$GLOBALS['user_info'] = $user_info;
			return $user_info;
		}
		else
			return null;
	}
	else
	{
		return null;
	}
}

function user_login($username_email,$pwd)
{	
	require_once APP_ROOT_PATH."system/libs/user.php";
	if(check_ipop_limit(get_client_ip(),"user_dologin",intval(app_conf("SUBMIT_DELAY")))){
		$result = do_login_user($username_email,$pwd);
	}
	else{
		//showErr($GLOBALS['lang']['SUBMIT_TOO_FAST'],$ajax,url("shop","user#login"));
		$result['status'] = 0;
		$result['msg'] = $GLOBALS['lang']['SUBMIT_TOO_FAST'];
		return $result;
	}
	
	if($result['status'])
	{
		//$GLOBALS['user_info'] = $result["user"];
		return $result;	
	}
	else
	{
		$GLOBALS['user_info'] = null;
		unset($GLOBALS['user_info']);
		
		if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
		{
			$err = $GLOBALS['lang']['USER_NOT_EXIST'];
		}
		if($result['data'] == ACCOUNT_PASSWORD_ERROR)
		{
			$err = $GLOBALS['lang']['PASSWORD_ERROR'];
		}
		if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
		{
			$err = $GLOBALS['lang']['USER_NOT_VERIFY'];			
		}
		
		$result['msg'] = $err;
		return $result;
	}	
}
function init_deal_page($deal_info)
{
	$root['page_title'] = $deal_info['name'];     
	if($deal_info['seo_title']!="")
	$root['seo_title'] = $deal_info['seo_title']; 
	if($deal_info['seo_keyword']!="")
	$root['seo_keyword'] = $deal_info['seo_keyword']; 
	if($deal_info['seo_description']!="")
	$root['seo_description'] = $deal_info['seo_description']; 
	$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);		

	
	$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
	$deal_info['limit_price_format'] = number_price_format($deal_info['limit_price']);
	
	$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
	$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100);
	$root['deal_info'] = $deal_info; 
	$deal_item_list = $deal_info['deal_item_list'];
	$root['deal_item_list'] = $deal_item_list; 
	if($deal_info['user_id']>0)
	{
		$deal_user_info = $GLOBALS['db']->getRow("select id,user_name,province,city,intro,login_time from ".DB_PREFIX."user where id = ".$deal_info['user_id']." and is_effect = 1");
		if($deal_user_info)
		{
			$deal_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$deal_user_info['id']);
			$root['deal_user_info'] = $deal_user_info; 
		}
	}
	
	if($GLOBALS['user_info'])
	{
		$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
		$root['is_focus'] = $is_focus; 
	}	
}
function init_deal_page_wap($deal_info)
{
	$GLOBALS['tmpl']->assign("page_title",$deal_info['name']);
	if($deal_info['seo_title']!="")
	$GLOBALS['tmpl']->assign("seo_title",$deal_info['seo_title']);
	if($deal_info['seo_keyword']!="")
	$GLOBALS['tmpl']->assign("seo_keyword",$deal_info['seo_keyword']);
	if($deal_info['seo_description']!="")
	$GLOBALS['tmpl']->assign("seo_description",$deal_info['seo_description']);
	
	//开启限购后剩余几位
	$deal_info['deal_item_count']=0;
	foreach ($deal_info['deal_item_list'] as $k=>$v){
			// 统计所有真实+虚拟（钱）
			$deal_info['total_virtual_person']+= $v['virtual_person'];
			$deal_info['total_virtual_price']+=$v['price'] * $v['virtual_person']+$v['support_amount'];
 			//统计每个子项目真实+虚拟（钱）
 			$deal_info['deal_item_list'][$k]['person']=$v['virtual_person']+$v['support_count'];
 			$deal_info['deal_item_list'][$k]['money']=$v['price'] * $v['virtual_person']+$v['support_amount'];
			$deal_info['deal_item_list'][$k]['cart_url']=url_wap("cart#index",array("id"=>$v['id']));
			if($v['limit_user']){
				$deal_info['deal_item_list'][$k]['remain_person']=$v['limit_user']-$v['virtual_person']-$v['support_count'];
			}
			$deal_info['deal_item_count']++;
		}
//	$deal_info['deal_type']=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."deal_cate where id=".$deal_info['cate_id']);
	$deal_info['tags_arr'] = preg_split("/[ ,]/",$deal_info['tags']);
	
	$deal_info['support_amount_format'] = number_price_format($deal_info['support_amount']);
	$deal_info['limit_price_format'] = number_price_format($deal_info['limit_price']);
 	$deal_info['total_virtual_price_format']=number_price_format(intval($deal_info['total_virtual_price']));
	$deal_info['remain_days'] = ceil(($deal_info['end_time'] - NOW_TIME)/(24*3600));
	$deal_info['percent'] = round($deal_info['support_amount']/$deal_info['limit_price']*100);
	
	//$deal_info['deal_level']=$GLOBALS['db']->getOne("select level from ".DB_PREFIX."deal_level where id=".intval($deal_info['user_level']));
	$deal_info['person']=$deal_info['total_virtual_person']+$deal_info['support_count'];
	$deal_info['percent']=round(($deal_info['total_virtual_price']/$deal_info['limit_price'])*100);
	
	$deal_info['update_url']=url_wap("deal#update",array("id"=>$deal_info['id']));
	$deal_info['comment_url']=url_wap("deal#comment",array("id"=>$deal_info['id']));
	$deal_info['info_url']=url_wap("deal#info",array("id"=>$deal_info['id']));
	
 
	if($deal_info['begin_time'] > NOW_TIME){
		$deal_info['status']= '0';  
		$deal_info['left_days']  = ceil(($deal_info['begin_time'] - NOW_TIME)/(24*3600));                               
	}
	elseif($deal_info['end_time'] < NOW_TIME && $deal_info['end_time']>0){
		if($deal_info['percent'] >=100){
			$deal_info['status']= '1';  
		}
		else{
				$deal_info['status']= '2'; 
		}
	} 
	else{
			if ($deal_info['end_time'] > 0) {
				$deal_info['status']= '3'; 
			}
			else
				$deal_info['status']= '4'; 
	}
	if($GLOBALS['user_info'])
	{
		$is_focus = $GLOBALS['db']->getOne("select  count(*) from ".DB_PREFIX."deal_focus_log where deal_id = ".$deal_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("is_focus",$is_focus);
	}
	if($deal_info['user_id']>0)
	{
		$deal_user_info = $GLOBALS['db']->getRow("select id,user_name,province,city,intro,login_time from ".DB_PREFIX."user where id = ".$deal_info['user_id']." and is_effect = 1");
		if($deal_user_info)
		{
			$deal_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$deal_user_info['id']);
			$deal_user_info['image']=get_user_avatar($deal_user_info['id'],'middle'); 
			$deal_info['user_info']=$deal_user_info;
		}
	}
	if(!empty($deal_info['vedio'])&&!preg_match("/http://player.youku.com/embed/i",$deal_info['source_video'])){
 		$deal_info['source_vedio']= preg_replace("/id_(.*)\.html(.*)/i","http://player.youku.com/embed/\${1}",baseName($deal_info['vedio'])); 
  		$GLOBALS['db']->query("update ".DB_PREFIX."deal set source_vedio='".$deal_info['source_vedio']."'  where id=".$deal_info['id']);
  	}
  	$GLOBALS['tmpl']->assign("deal_info",$deal_info);
}

function get_pre_wap(){
	if((ACT=="index")||
		(ACT == "project"&&ACT_2=="add")||
		(ACT == "project"&&ACT_2=="edit")||
		(ACT == "project"&&ACT_2=="add_item")||
		(ACT == "project"&&ACT_2=="edit_item")||
		(ACT == "deals"&&ACT_2=="index")||
		(ACT == "deal"&&ACT_2=="index")||
		(ACT == "deal"&&ACT_2=="show")||
		(ACT == "deal"&&ACT_2=="update")||
		(ACT == "deal"&&ACT_2=="updatedetail")||
		(ACT == "deal"&&ACT_2=="comment")||
		(ACT == "cart"&&ACT_2=="index")||
		(ACT == "cart"&&ACT_2=="pay")||
		(ACT == "faq")||(ACT == "help")||
		(ACT == "account"&&ACT_2=="index")||
		(ACT == "account"&&ACT_2=="incharge")||
		(ACT == "account"&&ACT_2=="pay")||
		(ACT == "account"&&ACT_2=="project")||
		(ACT == "account"&&ACT_2=="credit")||
		(ACT == "account"&&ACT_2=="view_order")||
		(ACT == "account"&&ACT_2=="focus")||
		(ACT == "account"&&ACT_2=="support")||
		(ACT == "account"&&ACT_2=="paid")||
		(ACT == "account"&&ACT_2=="refund")||
		(ACT == "news"&&ACT_2=="index")||
		(ACT == "news"&&ACT_2=="fav")||
		(ACT == "comment"&&ACT_2=="index")||
		(ACT == "comment"&&ACT_2=="send")||
		(ACT == "message"&&ACT_2=="index")||
		(ACT == "message"&&ACT_2=="history")||
		(ACT == "notify"&&ACT_2=="index")||
		(ACT=="settings"&&ACT_2=="index")||
		(ACT == "settings"&&ACT_2=="password")||
		(ACT == "settings"&&ACT_2=="bind")||
		(ACT == "settings"&&ACT_2=="consignee"))
		{	
			set_gopreview();
		}	
}
?>
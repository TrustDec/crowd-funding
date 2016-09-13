<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------


class ajaxModule extends BaseModule
{
	public function __construct(){
		parent::__construct();
		if(!check_hash_key()&&$GLOBALS['action']=='send_mobile_verify_code'){
			showErr("非法请求!",1);
		}
	}
	public function index()
	{
		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;		
		
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_effect = 1  and is_recommend = 1 and is_delete = 0 ");
		
		$GLOBALS['tmpl']->assign("deal_list",$deal_list);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/deal_list.html");
		
		if($step*$step_size<$page_size)
		{			
			if($deal_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
		
	}
	
	//这里会导致多出最后一个的bug=================================
	public function deals()
	{		

		$r = strim($_REQUEST['r']);   //推荐类型
		$param['r'] = $r?$r:'';
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$tag = strim($_REQUEST['tag']);  //标签
		$param['tag'] = $tag;
		$type = intval($_REQUEST['type']);  //类型
		$param['type'] = $type;
		$kw = strim($_REQUEST['k']);    //关键词
		$param['tag'] = $tag;
		$state = intval($_REQUEST['state']);  //状态
		$param['state'] = $state;
		$step = intval($_REQUEST['step']);
		$param['step'] = $step;
		
		//融资金额
		$price = intval($_REQUEST['price']);  
		if($price>0){
			$param['price'] = $price;
			//$GLOBALS['tmpl']->assign("price",$price);
		} 
 		//关注数
		$focus = intval($_REQUEST['focus']);   
		if($focus>0){
	        $param['focus'] = $focus;
			//$GLOBALS['tmpl']->assign("focus",$focus);
		} 
		
		//剩余时间
		$time = intval($_REQUEST['time']);   
		if($time>0){
	        $param['time'] = $time;
			//$GLOBALS['tmpl']->assign("time",$time);
		} 
		//完成比例
		$cp = intval($_REQUEST['cp']); 
		if($cp>0){  
	        $param['cp'] = $cp;
			//$GLOBALS['tmpl']->assign("cp",$cp);
		} 
		
		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;	
		
		$condition = " d.is_delete = 0 and d.is_effect = 1 "; 
		if($r!="")
		{
			if($r=="new")
			{
				$condition.=" and ".NOW_TIME." - d.begin_time < ".(7*24*3600)." and ".NOW_TIME." - d.begin_time > 0 ";  //上线不超过一天
			}
			elseif($r=="rec")
			{
				$condition.="  and d.is_recommend = 1 ";
			}
            elseif($r=="yure")
			{
				$condition.="   and ".NOW_TIME." <  d.begin_time ";   
			}
			elseif($r=="nend")
			{
				$condition.=" and d.end_time - ".NOW_TIME." < ".(7*24*3600)." and d.end_time - ".NOW_TIME." > 0 ";  //三天就要结束
			}
			elseif($r=="classic")
			{
				$condition.=" and d.is_classic = 1 ";
			}
			elseif($r=="limit_price")
			{
				$condition.=" and max(d.limit_price) ";
			}
		}
		
		switch($state)
		{
			//筹资成功
			case 1 : 
				$condition.=" and d.is_success=1  and d.end_time < ".NOW_TIME; 
				$GLOBALS['tmpl']->assign("page_title","筹资成功");
				break;
			//筹资失败
			case 2 : 
				$condition.=" and d.end_time < ".NOW_TIME." and d.end_time!=0  and d.is_success=0  "; 
				$GLOBALS['tmpl']->assign("page_title","筹资失败");
				break;
			//筹资中
			case 3 : 
				$condition.=" and (d.end_time > ".NOW_TIME." or d.end_time=0 ) and d.begin_time < ".NOW_TIME."   ";  
				$GLOBALS['tmpl']->assign("page_title","筹资中");
				break;
			case 4 : 
				$condition.=" and ub.status = 1 ";  
				$GLOBALS['tmpl']->assign("page_title","收益中");
			break;
		}
		
		if($type !=0) //房产分类
		{
			if($id >0)
			{
				if($type == 1){
					$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_investor_cate'));
				}elseif($type == 2){
					$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_house_cate'));
				}elseif($type == 3){
					$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_selfless_cate'));
				}elseif($type == 4){
					$nav_cate_array=load_auto_cache("deal_nav_cate",array('name'=>'deal_finance_cate'));
				}
				$nav_cate_all=$nav_cate_array['deal_cate_all'];
				if($nav_cate_all[$id]['pid'] ==0)
				{
					if($nav_cate_all[$id]['sub_list'])
					{
						$cate_ids=array_map('array_shift',$nav_cate_all[$id]['sub_list']);
					}
					$cate_ids[]=$id;
					
				}
				else
				{
					$cate_ids[] = $id;
				}
			}else
			{
				$cate_ids=array();
			}
		}
		else
		{
			$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
			if(!$cate_list)
			{
				$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
				set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
			}
			$cate_result = array();
			$kk = 0 ;
			foreach($cate_list as $k=>$v){
				if($v['pid'] == 0){
					$temp_param = $param;
					$cate_result[$k+1]['id'] = $v['id'];
					$cate_result[$k+1]['name'] = $v['name'];
					$temp_param['id'] = $v['id'];
					$cate_result[$k+1]['url'] = url("deals",$temp_param);
					$kk ++;
				}
			}
			
			$GLOBALS['tmpl']->assign("cate_list",$cate_result);
			
			$pid = 0;
			//获取父类id
			if($cate_list){
				foreach($cate_list as $k=>$v)
				{
					if($v['id'] ==  $id){
						if($v['pid'] > 0){
							$pid = $v['pid'];
						}
						else{
							$pid = $id;
						}
					}
				}
			}
			
			/*子分类 start*/
			$cate_ids = array();
			$is_has_child = false;
			$temp_cate_ids = array();
			if($cate_list){
				$child_cate_result= array();
				foreach($cate_list as $k=>$v)
				{
					if($v['pid'] == $pid){
						if($v['pid'] > 0){
							$temp_param = $param;
							$child_cate_result[$v['id']]['id'] = $v['id'];
							$child_cate_result[$v['id']]['name'] = $v['name'];
							$temp_param['id'] = $v['id'];
							$child_cate_result[$v['id']]['url'] = url("deals",$temp_param);
								
							if($v['id'] == $id){
								$is_has_child = true;
							}
						}
					}
					if($v['pid'] == $pid || $pid==0){
						$temp_cate_ids[] = $v['id'];
					}
				}
			}
			
			//假如选择了子类 那么使用子类ID  否则使用 父类和其子类
			if($is_has_child){
				$cate_ids[] = $id;
			}
			else{
				$cate_ids[] = $pid;
				$cate_ids = array_merge($cate_ids,$temp_cate_ids);
			}
		}
		
		
		
		
		if(count($cate_ids)>0)
		{
			$condition.= " and d.cate_id in (".implode(",",$cate_ids).")";
		
		}
		
		if($loc!="")
        {
            $condition.=" and (d.province = '".$loc."' or d.city = '".$loc."') ";         
		}
		if($tag!="")
		{
			$unicode_tag = str_to_unicode_string($tag);
			$condition.=" and match(d.tags_match) against('".$unicode_tag."'  IN BOOLEAN MODE) ";
		}
		
		if($kw!="")
		{		
			$kws_div = div_str($kw);
			foreach($kws_div as $k=>$item)
			{
				
				$kws[$k] = str_to_unicode_string($item);
			}
			$ukeyword = implode(" ",$kws);
			$condition.=" and (match(d.name_match) against('".$ukeyword."'  IN BOOLEAN MODE) or match(d.tags_match) against('".$ukeyword."'  IN BOOLEAN MODE)  or name like '%".$kw."%') ";

		}
		
		$condition.=" and d.type=$type ";
		
		
//		if($r=="new")
//		{
//			$orderby ="  d.begin_time desc ";
//		}
//		elseif($r=="rec")
//		{
//			$orderby.="   d.begin_time desc  ";
//		}
//        else 
//		{
//			$orderby ="  sort asc ";
//		}
		
		//========
		if($price>0){
			if($price==1){
				if($type==1){
					$orderby.=" d.invote_money desc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price) desc";
				}
				
				$param_new['price']=2;
 			}elseif($price==2){
 				if($type==1){
					$orderby.=" d.invote_money asc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price) asc";
				}
 				$param_new['price']=1;
 			}
			$url_list['price_url']=url("deals",$param_new);
		 
 		}elseif($focus>0){
			if($focus ==1){
				$orderby.=" d.focus_count desc";
  			}elseif($focus ==2){
				$orderby.=" d.focus_count asc";
  			}
  		}elseif($time>0){
			if($time ==1){
				$orderby.=" d.end_time desc";
 			}elseif($time ==2){
				$orderby.=" d.end_time asc";
  			}
 		}elseif($cp>0){
			if($cp ==1){
				if($type==1){
					$orderby.=" d.invote_money/d.limit_price desc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price)/d.limit_price desc";
				}
				$param_new['cp']=2;
 			}else{
 				if($type==1){
					$orderby.=" d.invote_money/d.limit_price asc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price)/d.limit_price asc";
				}
				$param_new['cp']=1;
 			}
			$url_list['cp_url']=url("deals",$param_new);
		}else{
 			$orderby ="  d.begin_time desc ";
 		}
// 		var_dump($limit);
//		var_dump($condition);
//		var_dump($orderby);
		$result = get_deal_list($limit,$condition,$orderby);
		$GLOBALS['tmpl']->assign("deal_list",$result['list']);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/deal_list.html");
		
		if($step*$step_size<$page_size)
		{			
			if($result['rs_count']<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
		
		
	}
	
	#---------------------------------------------------------------------------
	
	
	//转账用户名验证
	
	
	function check_user1(){
		
		//$val = strim($_REQUEST['val']);
	    $val = strim($_REQUEST['val']);//用户名
		//$val1 = strim($_REQUEST['val1']);//手机号
		
		//$user_id=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user WHERE mobile =".$val1." ");//手机号
		
		$user_id1=$GLOBALS['db']->getRow("SELECT id FROM ".DB_PREFIX."user WHERE user_name='".$val."'");//用户名
		if($user_id1){
			$result['status'] = 1;
			ajax_return($result);
		}
		else{
			$result['status'] = 0;
			ajax_return($result);
		}
	}
	//转账日志
	function commit_Log(){
		$shop_id=strim($_REQUEST['serial']);
		$one_uname=strim($_REQUEST['username']);
		$one_mobile=$GLOBALS['db']->getOne("SELECT mobile FROM ".DB_PREFIX."user WHERE user_name='".$one_uname."'");
		$one_money=strim($_REQUEST['moneyNum']);
		$one_time=strim($_REQUEST['time']);
		$two_uname=strim($_REQUEST['peerName']);
		$two_mobile=strim($_REQUEST['peerPhone']);
		$remark=strim($_REQUEST['remark']);
		$state=strim($_REQUEST['state']);
		$money_hand=10;
		$sql=("insert into fanwe_commit_log(shop_id,one_uname,one_mobile,one_money,one_time,two_uname,two_mobile,remark,state,money_hand) values('{$shop_id}','{$one_uname}','{$one_mobile}',{$one_money},'{$one_time}','{$two_uname}','{$two_mobile}','{$remark}',{$state},{$money_hand});");
		$data=mysql_query($sql);
		$result['status'] = $data;
		ajax_return($result);
	}
	//手机号验证	
	function check_mobile(){
			$val = strim($_REQUEST['val']);//用户名
			$val1 = strim($_REQUEST['val1']);//手机号
			
			$user_id=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user WHERE user_name='".$val."'");//用户名//用户名查询ID
			$user_id1=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user WHERE mobile =".$val1." ");//手机号查询ID
			if($user_id==$user_id1){
				$result['status'] = 1;
				ajax_return($result);
			}
			else{			
				$result['status'] = 0;
				ajax_return($result);
			}
	}
	
	//账户余额
	function user_money(){
		$num = strim($_REQUEST['val']);	
		$user_name=strim($_REQUEST['name']);
		$user_money=$GLOBALS['db']->getOne("SELECT money FROM ".DB_PREFIX."user where user_name ='".$user_name."'");
		$result['status'] = $user_money;
		ajax_return($result);
	}
	
	//扣除&添加
	function userMoney(){	
		$num = strim($_REQUEST['money']);	
		$moneyNum=strim($_REQUEST['moneyNum']);
		$moneyNum+=0;
		$user_name=strim($_REQUEST['username']);
		$two_name=strim($_REQUEST['peerName']);
		$user_id=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user WHERE user_name='".$user_name."'");	
		$GLOBALS['db']->getOne("update ".DB_PREFIX."user set money=".$num." where id =".$user_id." ");
		$user_money=$GLOBALS['db']->getOne("SELECT money FROM ".DB_PREFIX."user where id =".$user_id." ");
		$GLOBALS['db']->getOne("update ".DB_PREFIX."user set money=money+".$moneyNum." where user_name ='".$two_name."' ");
		$result['num'] = "update ".DB_PREFIX."user set money=money+".$moneyNum." where user_name ='".$two_name."' ";
		ajax_return($result);
	}
	
	//支付密码
	function pay_password(){
		$pwd = MD5(strim($_REQUEST['val']));
		$userName=strim($_REQUEST['uName']);
		$userid = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name ='".$userName."'");
		$moneyPassword = $GLOBALS['db']->getOne("select paypassword from ".DB_PREFIX."user where user_name = '".$userName." 'and id=".$userid."");
		if($moneyPassword==$pwd){
			$result['status'] = 1;
		}else{
			$result['status'] = 0;
		}
		//$result['status'] =$result;
		ajax_return($result);
	}
	//操作日志
	/*function operation_log(){
		$sql = "select one_time,shop_id,one_money,two_uname,one_uname,remark,state from ".DB_PREFIX."commit_log where one_uname='".$user_info.user_name."' or two_uname='".$user_info.user_name."'";
		$result =mysql_query($sql);
		while($row=mysql_fetch_row($result))
		{
			$arr[] = $row;  
		}
		dump($arr);
		function dump($arr)
		{			
			ajax_return($arr);
			
		}
	}*/
	//身份证号验证
	/*function check_idno(){
		   $val = strim($_REQUEST['idno']);//身份证号
		   
		   $idno=$GLOBALS['db']->getAll("SELECT idno FROM ".DB_PREFIX."user WHERE idno='".$val."'");//用户名
		   
			if($idno){
				$result['status'] = 1;
				ajax_return($result);
			}
			else{
				
				$result['status'] = 0;
				ajax_return($result);
			}
		
		}*/

	//这里会导致多出最后一个的bug=================================
	public function homes()
	{		

		$r = strim($_REQUEST['r']);   //推荐类型
		$param['r'] = $r?$r:'';
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$tag = strim($_REQUEST['tag']);  //标签
		$param['tag'] = $tag;
		$type = intval($_REQUEST['type']);  //类型
		$param['type'] = $type;
		$kw = strim($_REQUEST['k']);    //关键词
		$param['tag'] = $tag;
		$state = intval($_REQUEST['state']);  //状态
		$param['state'] = $state;
		$step = intval($_REQUEST['step']);
		$param['step'] = $step;
		
		//融资金额
		$price = intval($_REQUEST['price']);  
		if($price>0){
			$param['price'] = $price;
			//$GLOBALS['tmpl']->assign("price",$price);
		} 
 		//关注数
		$focus = intval($_REQUEST['focus']);   
		if($focus>0){
	        $param['focus'] = $focus;
			//$GLOBALS['tmpl']->assign("focus",$focus);
		} 
		
		//剩余时间
		$time = intval($_REQUEST['time']);   
		if($time>0){
	        $param['time'] = $time;
			//$GLOBALS['tmpl']->assign("time",$time);
		} 
		//完成比例
		$cp = intval($_REQUEST['cp']); 
		if($cp>0){  
	        $param['cp'] = $cp;
			//$GLOBALS['tmpl']->assign("cp",$cp);
		} 
		
		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;	
		
		$condition = " d.is_delete = 0 and d.is_effect = 1 "; 
		if($r!="")
		{
			if($r=="new")
			{
				$condition.=" and ".NOW_TIME." - d.begin_time < ".(7*24*3600)." and ".NOW_TIME." - d.begin_time > 0 ";  //上线不超过一天
			}
			elseif($r=="rec")
			{
				$condition.="  and d.is_recommend = 1 ";
			}
            elseif($r=="yure")
			{
				$condition.="   and ".NOW_TIME." <  d.begin_time ";   
			}
			elseif($r=="nend")
			{
				$condition.=" and d.end_time - ".NOW_TIME." < ".(7*24*3600)." and d.end_time - ".NOW_TIME." > 0 ";  //三天就要结束
			}
			elseif($r=="classic")
			{
				$condition.=" and d.is_classic = 1 ";
			}
			elseif($r=="limit_price")
			{
				$condition.=" and max(d.limit_price) ";
			}
		}
		
		switch($state)
		{
			//筹资成功
			case 1 : 
				$condition.=" and d.is_success=1  and d.end_time < ".NOW_TIME; 
				$GLOBALS['tmpl']->assign("page_title","筹资成功");
				break;
			//筹资失败
			case 2 : 
				$condition.=" and d.end_time < ".NOW_TIME." and d.end_time!=0  and d.is_success=0  "; 
				$GLOBALS['tmpl']->assign("page_title","筹资失败");
				break;
			//筹资中
			case 3 : 
				$condition.=" and (d.end_time > ".NOW_TIME." or d.end_time=0 ) and d.begin_time < ".NOW_TIME."   ";  
				$GLOBALS['tmpl']->assign("page_title","筹资中");
				break;
			case 4 : 
				$condition.=" and ub.status = 1 ";  
				$GLOBALS['tmpl']->assign("page_title","收益中");
			break;
		}
		
		$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
		
		if(!$cate_list)
		{
			$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate order by sort asc");
			set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
		}
		$cate_result = array();
		$kk = 0 ;
		foreach($cate_list as $k=>$v){
			if($v['pid'] == 0){
				$temp_param = $param;
				$cate_result[$k+1]['id'] = $v['id'];
				$cate_result[$k+1]['name'] = $v['name'];
				$temp_param['id'] = $v['id'];
				$cate_result[$k+1]['url'] = url("deals",$temp_param);
				$kk ++;
			}
		}
		
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		
		$pid = 0;
		//获取父类id
		if($cate_list){
			foreach($cate_list as $k=>$v)
			{
				if($v['id'] ==  $id){
					if($v['pid'] > 0){
						$pid = $v['pid'];
					}
					else{
						$pid = $id;
					}
				}
			}
		}
		
		/*子分类 start*/
		$cate_ids = array();
		$is_has_child = false;
		$temp_cate_ids = array();
		if($cate_list){
			$child_cate_result= array();
			foreach($cate_list as $k=>$v)
			{
				if($v['pid'] == $pid){
					if($v['pid'] > 0){
						$temp_param = $param;
						$child_cate_result[$v['id']]['id'] = $v['id'];
						$child_cate_result[$v['id']]['name'] = $v['name'];
						$temp_param['id'] = $v['id'];
						$child_cate_result[$v['id']]['url'] = url("deals",$temp_param);
							
						if($v['id'] == $id){
							$is_has_child = true;
						}
					}
				}
				if($v['pid'] == $pid || $pid==0){
					$temp_cate_ids[] = $v['id'];
				}
			}
		}
		
		//假如选择了子类 那么使用子类ID  否则使用 父类和其子类
		if($is_has_child){
			$cate_ids[] = $id;
		}
		else{
			$cate_ids[] = $pid;
			$cate_ids = array_merge($cate_ids,$temp_cate_ids);
		}
		
		if(count($cate_ids)>0)
		{
			$condition.= " and d.cate_id in (".implode(",",$cate_ids).")";
		
		}
		
		if($loc!="")
        {
            $condition.=" and (d.province = '".$loc."' or d.city = '".$loc."') ";         
		}
		if($tag!="")
		{
			$unicode_tag = str_to_unicode_string($tag);
			$condition.=" and match(d.tags_match) against('".$unicode_tag."'  IN BOOLEAN MODE) ";
		}
		
		if($kw!="")
		{		
			$kws_div = div_str($kw);
			foreach($kws_div as $k=>$item)
			{
				
				$kws[$k] = str_to_unicode_string($item);
			}
			$ukeyword = implode(" ",$kws);
			$condition.=" and (match(d.name_match) against('".$ukeyword."'  IN BOOLEAN MODE) or match(d.tags_match) against('".$ukeyword."'  IN BOOLEAN MODE)  or name like '%".$kw."%') ";

		}
		
		$condition.=" and d.type=$type ";
		
		
//		if($r=="new")
//		{
//			$orderby ="  d.begin_time desc ";
//		}
//		elseif($r=="rec")
//		{
//			$orderby.="   d.begin_time desc  ";
//		}
//        else 
//		{
//			$orderby ="  sort asc ";
//		}
		
		//========
		if($price>0){
			if($price==1){
				if($type==1){
					$orderby.=" d.invote_money desc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price) desc";
				}
				
				$param_new['price']=2;
 			}elseif($price==2){
 				if($type==1){
					$orderby.=" d.invote_money asc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price) asc";
				}
 				$param_new['price']=1;
 			}
			$url_list['price_url']=url("deals",$param_new);
		 
 		}elseif($focus>0){
			if($focus ==1){
				$orderby.=" d.focus_count desc";
  			}elseif($focus ==2){
				$orderby.=" d.focus_count asc";
  			}
  		}elseif($time>0){
			if($time ==1){
				$orderby.=" d.end_time desc";
 			}elseif($time ==2){
				$orderby.=" d.end_time asc";
  			}
 		}elseif($cp>0){
			if($cp ==1){
				if($type==1){
					$orderby.=" d.invote_money/d.limit_price desc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price)/d.limit_price desc";
				}
				$param_new['cp']=2;
 			}else{
 				if($type==1){
					$orderby.=" d.invote_money/d.limit_price asc";
				}else{
					$orderby.=" (d.support_amount+d.virtual_price)/d.limit_price asc";
				}
				$param_new['cp']=1;
 			}
			$url_list['cp_url']=url("deals",$param_new);
		}else{
 			$orderby ="  d.begin_time desc ";
 		}
// 		var_dump($limit);
//		var_dump($condition);
//		var_dump($orderby);
		$result = gets_deal_list($limit,$condition,$orderby,$deal_type='deal',$type);
		$GLOBALS['tmpl']->assign("deal_list",$result['list']);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/deal_list.html");
		
		if($step*$step_size<$page_size)
		{			
			if($result['rs_count']<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
		
		
	}
	public function dealupdate()
	{

		$id = intval($_REQUEST['id']);
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$id." and is_delete = 0 and (is_effect = 1 or (is_effect = 0 and user_id = ".intval($GLOBALS['user_info']['id'])."))");
		if(!$deal_info)
		{
			ajax_return(array("step"=>0));
		}		
		else 
		{
			$GLOBALS['tmpl']->assign("deal_info",$deal_info);
		}

		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		$log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit);				
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']);
		
		if(!$log_list)
		{
			ajax_return(array("step"=>0));
		}
		if((($page-1)*$page_size+($step-1)*$step_size)+count($log_list)>=$log_count)
		{
			//最后一页
			$log_list[] = array("deal_id"=>$deal_info['id'],
								"create_time"=>$deal_info['begin_time']+1,
								"id"=>0);
		}
		
		$last_time_key = "";
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$online_time = online_date($v['create_time'],$deal_info['begin_time']);
			$log_list[$k]['online_time'] = $online_time['info'];
			if($online_time['key']!=$last_time_key)
			{
				$last_time_key = $log_list[$k]['online_time_key'] = $online_time['key'];				
			}
			$log_list[$k] = cache_log_comment($log_list[$k]);
		}
		
		
		$GLOBALS['tmpl']->assign("log_list",$log_list);		
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/time_line_item.html");
		//$data['html'] = "select * from ".DB_PREFIX."deal_log where deal_id = ".$deal_info['id']." order by create_time desc limit ".$limit;
		
		if($step*$step_size<$page_size)
		{			
			if($log_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
	}
	
	public function login()
	{
		$GLOBALS['tmpl']->display("inc/user_login_pop.html");
	}
	public function region()
	{
		$city_list = load_dynamic_cache("INDEX_CITY_LIST"); 
        if(!$city_list)
		{
			$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal group by province order by sort asc");
			set_dynamic_cache("INDEX_CITY_LIST",$city_list);
		}
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = url("deals",$temp_param);
		}
        	
		$GLOBALS['tmpl']->assign("city_list",$city_list);
		//============region_conf============
		$area_list = $GLOBALS['db']->getAll("select rc.* from ".DB_PREFIX."region_conf as rc where rc.name in (select province from ".DB_PREFIX."deal) or  rc.name in (select city from ".DB_PREFIX."deal) or rc.is_hot=1 ");
		$area=array();
		$hot_area=array();
		foreach($area_list as $k=>$v){
			$temp_param['loc'] = $v['name'];
			$area[strtoupper($v['py'][0])][$v['name']]=array('url'=> url("deals",$temp_param),'name'=>$v['name']);
			if($v['is_hot']){
				$hot_area[]=array('url'=> url("deals",$temp_param),'name'=>$v['name']);
			}
 			
		}
		ksort($area);
 		$area_array=array();
		
	
 		$area_array=array_chunk(array_filter($area),4,true);
		$area_array_num=array();
 		foreach($area_array as $k=>$v){
			foreach($v as $k1=>$v1){
				$area_array_str[$k].=$k1;
			}
		}
		
 		$GLOBALS['tmpl']->assign("area_array",$area_array);
		$GLOBALS['tmpl']->assign("area_array_str",$area_array_str);
		$GLOBALS['tmpl']->assign("hot_area",array_filter($hot_area));
		$GLOBALS['tmpl']->display("inc/region.html");
	}
	
	
	public function homeindex()
	{
		$id = intval($_REQUEST['id']);
		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;		
		
		$condition = " is_delete = 0 and is_effect = 1 and user_id = ".$id." "; 
		
		$deal_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where ".$condition." order by sort asc limit ".$limit);

		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where ".$condition);
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['support_amount']/$v['limit_price']*100,2);
		}
		$GLOBALS['tmpl']->assign("deal_list",$deal_list);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/deal_list.html");
		
		if($step*$step_size<$page_size)
		{			
			if($deal_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
	}
	
	public function homesupport()
	{
		$id = intval($_REQUEST['id']);
		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;		
		
		$sql = "select distinct(d.id) as id,d.* from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_support_log as dsl on d.id = dsl.deal_id ".
			   " where dsl.user_id = ".$id." order by d.sort asc limit ".$limit;
	
		$sql_count = "select count(distinct(d.id)) from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_support_log as dsl on d.id = dsl.deal_id ".
			   " where dsl.user_id = ".$id;
		
		$deal_list = $GLOBALS['db']->getAll($sql);
		$deal_count = $GLOBALS['db']->getOne($sql_count);
		
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['support_amount']/$v['limit_price']*100,2);
		}
		$GLOBALS['tmpl']->assign("deal_list",$deal_list);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/deal_list.html");
		
		if($step*$step_size<$page_size)
		{			
			if($deal_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
	}
	
	public function news()
	{	

		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " d.type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " d.type=1 ";
		}
		
		$log_list = $GLOBALS['db']->getAll("select l.* from ".DB_PREFIX."deal_log as l left join ".DB_PREFIX."deal as d on d.id=l.deal_id  where $condition order by l.create_time desc limit ".$limit);
		$log_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_log as l left join ".DB_PREFIX."deal as d on d.id=l.deal_id where $condition");
		
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$log_list[$k] = cache_log_comment($log_list[$k]);
			$log_list[$k] = cache_log_deal($log_list[$k]);
		}
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/news_item.html");
		
		if($step*$step_size<$page_size)
		{			
			if($log_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
	}
	
	public function newsfav()
	{	

		$page_size = DEALUPDATE_PAGE_SIZE;
		$step_size = DEALUPDATE_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " de.type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " de.type=1 ";
		}
		
		$sql = "select dl.* from ".DB_PREFIX."deal_log as dl left join ".DB_PREFIX."deal_focus_log as dfl on dl.deal_id = dfl.deal_id left join ".DB_PREFIX."deal as de on de.id=dl.deal_id where $condition and dfl.user_id = ".intval($GLOBALS['user_info']['id'])." order by dl.create_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."deal_log as dl left join ".DB_PREFIX."deal_focus_log as dfl on dl.deal_id = dfl.deal_id ".DB_PREFIX."deal as de on de.id=dl.deal_id where $condition and dfl.user_id = ".intval($GLOBALS['user_info']['id']);
		
		$log_list = $GLOBALS['db']->getAll($sql);
		$log_count = $GLOBALS['db']->getOne($sql_count);
		
		foreach($log_list as $k=>$v)
		{
			$log_list[$k]['pass_time'] = pass_date($v['create_time']);
			$log_list[$k] = cache_log_comment($log_list[$k]);
			$log_list[$k] = cache_log_deal($log_list[$k]);
		}
		$GLOBALS['tmpl']->assign('log_list',$log_list);
		
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/news_item.html");
		
		if($step*$step_size<$page_size)
		{			
			if($log_count<=(($page-1)*$page_size+($step-1)*$step_size)+$step_size)
			{
				$data['step'] = 0;
				ajax_return($data);
			}
			else
			{
				$data['step'] = $step+1;
				ajax_return($data);
			}
		}
		else
		{
			$data['step'] = 0;
			ajax_return($data);
		}
	}
	
	
	public function randdeal()
	{
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition = " 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition = " type=0 ";
		}
		elseif (app_conf("INVEST_STATUS")==2)
		{
			$condition = " type=1 ";
		}
		
		$rand_deals = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where $condition and is_delete = 0 and is_effect = 1 and begin_time < ".NOW_TIME." and (end_time >".NOW_TIME." or end_time = 0) order by rand() limit 3");
		$GLOBALS['tmpl']->assign("rand_deals",$rand_deals);
		$GLOBALS['tmpl']->display("inc/rand_deals.html");
	}
	
	public function usermessage()
	{
		if(!$GLOBALS['user_info'])
		{
			$data['status'] = 2;
			ajax_return($data);
		}
		$id = intval($_REQUEST['id']);
		if($id==$GLOBALS['user_info']['id'])
		{
			$data['status'] = 0;
			$data['info'] = "不能给自己发私信";
			ajax_return($data);
		}
		$send_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id." and is_effect = 1");
		if(!$send_user_info)
		{
			$data['status'] = 0;
			$data['info'] = "收信人不存在";
			ajax_return($data);
		}
		else
		{
			$GLOBALS['tmpl']->assign("send_user_info",$send_user_info);
			$data['status'] = 1;
 			$data['html'] = $GLOBALS['tmpl']->fetch("inc/usermessage.html");
 			ajax_return($data);
		}
		
	}
	
	public function close_notify()
	{
		es_cookie::set("hide_user_notify",1);	
	}
	
	public function add_deal_faq()
	{
		$GLOBALS['tmpl']->display("inc/deal_faq_item.html");
	}
	public function check_field()
	{
		$field_name = addslashes(trim($_REQUEST['field_name']));
		$field_data = addslashes(trim($_REQUEST['field_data']));
		require_once APP_ROOT_PATH."system/libs/user.php";
		$res = check_user($field_name,$field_data);
		$result = array("status"=>1,"info"=>'');
		if($res['status'])
		{
			ajax_return($result);
		}
		else
		{
			$error = $res['data'];		
			if(!$error['field_show_name'])
			{
					$error['field_show_name'] = $GLOBALS['lang']['USER_TITLE_'.strtoupper($error['field_name'])];
			}
			if($error['error']==EMPTY_ERROR)
			{
				$error_msg = sprintf("不能为空",$error['field_show_name']);
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_msg = sprintf("格式错误，请重新输入",$error['field_show_name']);
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_msg = sprintf("已存在，请重新输入",$error['field_show_name']);
			}
			$result['status'] = 0;
			$result['info'] = $error_msg;
			ajax_return($result);
		}
	}
	public function send_mobile_verify_code_1(){
		if($GLOBALS['user_info']){
			$data['status'] = 0;
			$data['info'] = "请先登录会员";
			ajax_return($data);
		}
		$this->send_change_mobile_verify_code(0);
	}
	
	public function send_mobile_verify_code($is_register=1)
	{
		
		if(app_conf("SMS_ON")==0)
		{
			$data['status'] = 0;
			$data['info'] = "短信未开启";
			ajax_return($data);		
		}
		//$is_register =intval($_REQUEST['is_register']);
		if(app_conf("USER_VERIFY_STATUS")==1&&$is_register){
			$image_code = strim($_REQUEST['image_code']);
			$verify=es_session::get("register_verify");
			if(!$image_code){
				$data['status'] = 0;
				$data['info'] = "请输入图片验证码";
				ajax_return($data);	
			}
			if($verify!=md5($image_code)){
				$data['status'] = 0;
				$data['info'] = "图片验证码错误".es_session::get("test_verify");
				$data['error_test'] = es_session::get("test_verify");
				ajax_return($data);	
			}
		}
		
		$mobile = addslashes(htmlspecialchars(trim($_REQUEST['mobile'])));
		//is_only 为1的话，表示不允许手机号重复
		$is_only=intval($_REQUEST['is_only']);
		if($mobile == '')
		{
			$data['status'] = 0;
			$data['info'] = "请输入你的手机号";
			ajax_return($data);
		}
		
		if(!check_mobile($mobile))
		{
			$data['status'] = 0;
			$data['info'] = "请填写正确的手机号码";
			ajax_return($data);
		}
		
		if($is_only==1){
			$condition_1=" and mobile='".$mobile."' ";
			if($GLOBALS['user_info']['id']){
				$condition_1.=" and id!=".$GLOBALS['user_info']['id'];
			}
			if($GLOBALS['db']->getOne("select count(*) from  ".DB_PREFIX."user where 1=1 $condition_1 ")>0){
				$data['status'] = 0;
				$data['info'] = "该手机号已经存在";
				ajax_return($data);
			}
		}
			
 		$field_name = addslashes(trim($_REQUEST['mobile']));
		$field_data = $mobile;
		require_once APP_ROOT_PATH."system/libs/user.php";
		$res = check_user($field_name,$field_data);
		
		$result = array("status"=>1,"info"=>'');
		if(!$res['status'])
		{
			$error = $res['data'];		
			if(!$error['field_show_name'])
			{
				$error['field_show_name'] = "手机号码";
			}
			if($error['error']==EMPTY_ERROR)
			{
				$error_msg = sprintf("手机号码不能为空",$error['field_show_name']);
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_msg = sprintf("格式错误，请重新输入",$error['field_show_name']);
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_msg = sprintf("已存在，请重新输入",$error['field_show_name']);
			}
			$result['status'] = 0;
			$result['info'] = $error_msg;
			ajax_return($result);
		}
		
		
		if(!check_ipop_limit(get_client_ip(),"mobile_verify",60,0))
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		delete_mobile_verify_code();
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile = '".$mobile."' and client_ip='".get_client_ip()."' and create_time>=".(get_gmtime()-60)." ORDER BY id DESC") > 0)
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		
		//开始生成手机验证
		$code = rand(100000,999999);
		$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_verify_code",array("verify_code"=>$code,"mobile"=>$mobile,"create_time"=>get_gmtime(),"client_ip"=>get_client_ip()),"INSERT");
	
		send_verify_sms($mobile,$code);
		//$GLOBALS['msg']->manage_msg('SMS_VERIFY',$mobile,array('code'=>$code));
		$data['status'] = 1;
		$data['info'] = "验证码发送成功";
		ajax_return($data);
	}
	public function send_change_mobile_verify_code()
	{
		if(app_conf("SMS_ON")==0)
		{
			$data['status'] = 0;
			$data['info'] = "短信未开启";
			ajax_return($data);		
		}
		$mobile = addslashes(htmlspecialchars(trim($_REQUEST['mobile'])));
		$step=intval($_REQUEST['step']);
		$old_mobile=$GLOBALS["user_info"]['mobile'];
		if($step==1){
			if($old_mobile==$mobile){
				$data['status'] = 0;
				$data['info'] = "你输入的手机号与原先一样";
				ajax_return($data);
			}
			$m_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile='".$mobile."' ");
			if($m_count>0){
				$data['status'] = 0;
				$data['info'] = "你输入的手机号已存在";
				ajax_return($data);
			}
		}elseif($step==2){
			//单纯发送验证短信
			if($mobile==''){
				$mobile=$GLOBALS["user_info"]['mobile'];
			}
		}elseif($step==0){
			
			$m_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile='".$mobile."' ");
			if(!$m_count){
				$data['status'] = 0;
				$data['info'] = "你输入的手机号非会员手机号";
				ajax_return($data);
			}
		}
		
		
		if($mobile == '')
		{
			$data['status'] = 0;
			$data['info'] = "请输入你的手机号";
			ajax_return($data);
		}
		
		if(!check_mobile($mobile))
		{
			$data['status'] = 0;
			$data['info'] = "请填写正确的手机号码";
			ajax_return($data);
		}
		
		$field_name = addslashes(trim($_REQUEST['mobile']));
		$field_data = $mobile;
		require_once APP_ROOT_PATH."system/libs/user.php";
		$res = check_user($field_name,$field_data);
		
		$result = array("status"=>1,"info"=>'');
 		
		if(!check_ipop_limit(get_client_ip(),"mobile_verify_".$step,60,0))
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		delete_mobile_verify_code();
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile = '".$mobile."' and client_ip='".get_client_ip()."' and create_time>=".(get_gmtime()-60)." ORDER BY id DESC") > 0)
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		
		//开始生成手机验证
		$code = rand(100000,999999);
		$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_verify_code",array("verify_code"=>$code,"mobile"=>$mobile,"create_time"=>get_gmtime(),"client_ip"=>get_client_ip()),"INSERT");
	
		send_verify_sms($mobile,$code);
		$data['status'] = 1;
		$data['info'] = "验证码发送成功";
		ajax_return($data);
	}
	//step:2 表示发送会员验证码 1表示绑定验证码 0表示重至密码
	public function send_email_verify_code()
	{
		if(app_conf("MAIL_ON")==0)
		{
			$data['status'] = 0;
			$data['info'] = "邮件未开启";
			ajax_return($data);		
		}
		$email = addslashes(htmlspecialchars(trim($_REQUEST['email'])));
		$step=intval($_REQUEST['step']);
		$old_email=$GLOBALS["user_info"]['email'];
		if($step==1){
			//新注册的邮箱
			if($email==$old_email){
				$data['status'] = 0;
				$data['info'] = "你输入邮件的与原先一样";
				ajax_return($data);
			}
			$m_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='".$email."' ");
			if($m_count>0){
				$data['status'] = 0;
				$data['info'] = "你输入的邮件已存在";
				ajax_return($data);
			}
		}elseif($step==2){
			//单纯发送验证邮件
			if($email==''){
				$email=$GLOBALS["user_info"]['email'];
			}
		}elseif($step==0){
			$m_count=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='".$email."' ");
			if(!$m_count){
				$data['status'] = 0;
				$data['info'] = "您输入的邮件非会员邮件";
				ajax_return($data);
			}
		}
		
		
		if($email == '')
		{
			$data['status'] = 0;
			$data['info'] = "请输入你的邮件";
			ajax_return($data);
		}
		
		if(!check_email($email))
		{
			$data['status'] = 0;
			$data['info'] = "请填写正确的邮件";
			ajax_return($data);
		}
		
		$field_name = addslashes(trim($_REQUEST['email']));
		$field_data = $email;
  		
		if(!check_ipop_limit(get_client_ip(),"mobile_verify_".$step,60,0))
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		delete_mobile_verify_code();
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where email = '".$email."' and client_ip='".get_client_ip()."' and create_time>=".(get_gmtime()-60)." ORDER BY id DESC") > 0)
		{
			$data['status'] = 0;
			$data['info'] = "发送速度太快了";
			ajax_return($data);
		}
		
		//开始生成手机验证
		$code = rand(100000,999999);
		$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_verify_code",array("verify_code"=>$code,"email"=>$email,"create_time"=>get_gmtime(),"client_ip"=>get_client_ip()),"INSERT");
	
		send_verify_email($email,$code);
		$data['status'] = 1;
		$data['info'] = "验证码发送成功";
		ajax_return($data);
	}
	public function send_mobie_pwd_sncode_new(){
		delete_mobile_verify_code();
		if(app_conf("SMS_ON")==0)
		{
			$data['status'] = 0;
			$data['info'] = $GLOBALS['lang']['SMS_OFF'];
			ajax_return($data);
		}
		$mobile = addslashes(htmlspecialchars(trim($_REQUEST['mobile'])));
		
		if($mobile == '')
		{
			$data['status'] = 0;
			$data['info'] = "请输入你的手机号";
			ajax_return($data);
		}
		
		if(!check_mobile($mobile))
		{
			$data['status'] = 0;
			$data['info'] = "请填写正确的手机号码";
			ajax_return($data);
		}
		
		$field_name = addslashes(trim($_REQUEST['mobile']));
		$field_data = $mobile;
		$user_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where mobile='".$field_data."' ");
		
		if($user_id){
			if(!check_ipop_limit(get_client_ip(),"mobile_verify",60,0))
			{
				$data['status'] = 0;
				$data['info'] = "发送速度太快了";
				ajax_return($data);
			}
 			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_verify_code where mobile = '".$mobile."' and client_ip='".get_client_ip()."' and create_time>=".(get_gmtime()-60)." ORDER BY id DESC") > 0)
			{
				$data['status'] = 0;
				$data['info'] = "发送速度太快了";
				ajax_return($data);
			}
				
			
				
			$verify_code = $GLOBALS['db']->getOne("select verify_code from ".DB_PREFIX."mobile_verify_code where mobile = '".$mobile."' and create_time>=".(NOW_TIME-180)." ORDER BY id DESC");
			if(intval($verify_code) == 0)
			{
				//如果数据库中存在验证码，则取数据库中的（上次的 ）；确保连接发送时，前后2条的验证码是一至的.==为了防止延时
				//开始生成手机验证
				$verify_code = rand(100000,999999);
				$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_verify_code",array("verify_code"=>$verify_code,"mobile"=>$mobile,"create_time"=>get_gmtime(),"client_ip"=>get_client_ip()),"INSERT");
			}
			//使用立即发送方式
			send_verify_sms($mobile,$verify_code);
			$data['status'] = 1;
			$data['info'] = "验证码发送成功";
			ajax_return($data);
				
		 
				
		}else{
			$result['status'] = 0;
			$result['info'] = "该手机不存在，请重新输入";
			ajax_return($result);

		}
	}
	public function set_repay_make(){
		$id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and repay_time>0 and user_id = ".intval($GLOBALS['user_info']['id']));
		if(!$order_info)
		{
			showErr("无效的项目支持",1);
		}else{
			if($order_info['repay_make_time']==0){
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$order_info['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
				showSuccess("确认收货成功",1);		
			}
		}
	}
	public function three_seconds_jump(){
		$id=intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("page_title","用户手机绑定");
		$GLOBALS['tmpl']->display("inc/three_seconds_jump.html");
	}
	
	//领投人详细信息
	function leader_detailed_information(){
		$id=intval($_REQUEST['id']);
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		if($id>0){
			//领投信息
			$leader_info=$GLOBALS['db']->getRow("select inv.*,u.user_name,u.identify_name,u.user_level,u.is_investor from  ".DB_PREFIX."investment_list as inv left join ".DB_PREFIX."user as u on u.id=inv.user_id where inv.id=".$id);
     		$leader_info['user_icon'] =$GLOBALS['user_level'][$leader_info['user_level']]['icon'];//用户等级图标
     		$GLOBALS['tmpl']->assign("leader_info",$leader_info);
			$result['html']= $GLOBALS['tmpl']->fetch("inc/leader_detailed_info.html");
			$result['status']=1;
			ajax_return($result);
		}else{
			$result['status']=2;
			$result['info']="系统繁忙，请您稍后重试！";
			ajax_return($result);
		}
		return false;
	}
	//投资人详细信息
	function investor_detailed_information(){
		$id=intval($_REQUEST['id']);
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		if($id>0){
			//投资人信息
			$investor_info=$GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."user where id =$id");
			foreach($investor_info as $k=>$v){
				$investor_info['image']=get_user_avatar($v['id'], "middle");
			}
			$GLOBALS['tmpl']->assign("investor_info",$investor_info);
 			$result['html']= $GLOBALS['tmpl']->fetch("inc/investor_detailed_info.html");
			$result['status']=1;
			$result['user_name']=$investor_info['user_name'];
			ajax_return($result);
		}else{
			$result['status']=2;
			$result['info']="系统繁忙，请您稍后重试！";
			ajax_return($result);
		}
		return false;
	}
	function download(){
		$filename =$_REQUEST['leader_moban'];
		if($filename==null){
			showErr("文件不存在！");
			return false;
		}else{
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".basename($filename));
			readfile($filename);
		}
	}
	//领投信息上传
	function leader_info_save(){
		//list id    serialize(array_filter($history_info['data']));
		$id=intval($_POST['id']);
		$data['leader_help']=strim($_POST['leader_help']);
		$data['leader_for_team']=strim($_POST['leader_for_team']);
		$data['leader_for_project']=strim($_POST['leader_for_project']);
		$data['leader_moban']=serialize($_POST['leader_moban']);
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		if($data['leader_help']==null){
			$result['status']=0;
			$result['info']="其它帮助不能为空！";
			ajax_return($result);
			return false;
		}
		if($data['leader_for_team']==null){
			$result['status']=0;
			$result['status']="团队评价不能为空！";
			ajax_return($result);
			return false;
		}
		if($data['leader_for_project']==null){
			$result['status']=0;
			$result['info']="项目评价不能为空！";
			ajax_return($result);
			return false;
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."investment_list",$data,"UPDATE","id=".$id,"SILENT")>0){
			$result['status']=1;
			$result['info']="操作成功！";
			ajax_return($result);
			return false;
		}else{
			$result['status']=2;
			$result['info']="操作失败！";
			ajax_return($result);
			return false;
		}
 	}
	/*获取会员所有项目列表*/
	public function ajax_get_recommend_project(){
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		if(!$GLOBALS['user_info'])
		{
			$result['status']=0;
			$result['info']="未登入";
			ajax_return($result);
			return false;
		}
		//推荐人id
		$id=intval($GLOBALS['user_info']['id']);
		//被推荐人id
		$user_id=intval($_REQUEST['user_id']);
		$effective_deal_info=get_effective_deal_info($id);
 		if(!$effective_deal_info){
			$result['status']=1;
			$result['info']="请您先创建项目！";
			ajax_return($result);
			return false;
		}else{
			$result['status']=2;
			$result['info']="项目列表！";
			$GLOBALS['tmpl']->assign("recommend_user_id",$id);
			$GLOBALS['tmpl']->assign("user_id",$user_id);
			$GLOBALS['tmpl']->assign("effective_deal_info",$effective_deal_info);
			$result['html'] = $GLOBALS['tmpl']->fetch("inc/ajax_get_recommend_project.html");
			ajax_return($result);
			return false;
		}
 	}
	/*保存推荐内容,数据库是fanwe_recommend*/
	public function ajax_recommend_save(){
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		$memo=strim($_POST['memo']);
		//推荐项目id
		$deal_id=intval($_POST['deal_id']);
		//推荐项目图片
		$deal_image=strim($_POST['deal_image'])!=""?replace_public($_POST['deal_image']):"";
		//推荐项目名字
		$deal_name=strim($_POST['deal_name']);
		//被推荐人id
		$user_id=intval($_POST['user_id']);
		//项目类型 0普通 1股权
		$deal_type=intval($_POST['deal_type']);
		//推荐人id
		$recommend_user_id=intval($_POST['recommend_user_id']);
		$create_time=NOW_TIME;
		if($deal_id==null){
			$result['status']=0;
			$result['info']="请选择推荐项目！";
			ajax_return($result);
			return false;
		}
		if($memo==null){
			$result['status']=0;
			$result['info']="推荐理由不能为空！";
			ajax_return($result);
			return false;
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."recommend",array("memo"=>$memo,"deal_id"=>$deal_id,"user_id"=>$user_id,"recommend_user_id"=>$recommend_user_id,"create_time"=>$create_time,"deal_type"=>$deal_type,"deal_name"=>$deal_name,"deal_image"=>$deal_image),"INSERT")>0){
			$result['status']=1;
			$result['info']="项目推荐成功！";
			ajax_return($result);
			return false;
		}else{
			$result['status']=0;
			$result['info']="系统繁忙,请您稍后重试！";
			ajax_return($result);
			return false;
		}
	}
	/*删除推荐项目*/
	public function ajax_delete_recommend(){
		$result=array('status'=>'','info'=>'','url'=>'','html'=>'');
		$id=intval($_POST['id']);
		if($id>0){
			if($GLOBALS['db']->query("delete from ".DB_PREFIX."recommend where id = ".$id)>0){
				$result['status']=1;
				$result['info']="删除成功！";
				ajax_return($result);
				return false;
			}else{
				$result['status']=0;
				$result['info']="删除失败！";
				ajax_return($result);
				return false;
			}
		}else{
			$result['status']=0;
			$result['info']="系统繁忙,请您稍后重试！";
			ajax_return($result);
			return false;
		}
	}
	/*拒绝理由*/
	public function refuse_reason(){
		$result=array('status'=>1,'info'=>'');
		$deal_id=intval($_POST['deal_id']);
		if($deal_id>0){
			$refuse_reason=$GLOBALS['db']->getOne("select refuse_reason from ".DB_PREFIX."deal where id=$deal_id and is_effect=2 ");
			$result['status']=1;
			$result['info']=$refuse_reason;
			ajax_return($result);
			return false;
		}else{
			$result['status']=0;
			$result['info']="deal_id不存在！";
			ajax_return($result);
			return false;
		}
	}
	/*添加提现银行*/
	public function add_bank(){
		$payment_count=intval($_REQUEST['payment_count']);
		$bank_list=get_bank_list();
		$user_info=$GLOBALS['user_info'];
		if($user_info['identify_name']==''){
			showErr('您的身份认证未完成,请点击确定去实名认证!',1,url("settings#security",array("method"=>"setting-id-box")));
		}
		$GLOBALS['tmpl']->assign('user_info',$GLOBALS['user_info']);
		
		$GLOBALS['tmpl']->caching = true;
		$cache_id  = md5(MODULE_NAME.ACTION_NAME);		
		if (!$GLOBALS['tmpl']->is_cached('inc/account_money_carry_addbank.html', $cache_id))
		{		
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		}
		$GLOBALS['tmpl']->assign("payment_count",$payment_count);
  		$GLOBALS['tmpl']->assign('bank_list',$bank_list);
  		if($payment_count){
  			$requestid = date('YmdHis') . rand(1000000, 9000000);
			$identityid = md5($requestid);
			$userip=get_client_ip();
			$GLOBALS['tmpl']->assign("requestid",$requestid);
			$GLOBALS['tmpl']->assign("identityid",$identityid);
			$GLOBALS['tmpl']->assign("userip",$userip);
			$GLOBALS['tmpl']->assign("addbindbankcard",1);
  			$data['html'] = $GLOBALS['tmpl']->fetch("inc/account_money_carry_addbindbankcard.html");
  		}else{
  			$data['html'] = $GLOBALS['tmpl']->fetch("inc/account_money_carry_addbank.html");
  		}
		$data['status']=1;
		ajax_return($data);
	}
	/**/
	public function save_paypassword(){
		
	}
	//检查验证码是否正确
	function check_verify_code()
	{
		delete_mobile_verify_code();
		$type=intval($_POST['type']);
		$settings_mobile_code=strim($_POST['code']);
		$condition="";
		if($type==1){
			$email=strim($_POST['email']);
			$condition.=" email='".$email."' AND verify_code='".$settings_mobile_code."'";
 		}elseif($type==2){
			
			$mobile=strim($_POST['mobile']);
			$condition.=" mobile=".$mobile." AND verify_code='".$settings_mobile_code."'";
		}
		$sql="SELECT count(*) FROM ".DB_PREFIX."mobile_verify_code WHERE $condition";
		
		//判断验证码是否正确=============================
		if($GLOBALS['db']->getOne($sql)==0){
			$data['status'] = 0;
			$data['info'] = "验证码错误";
			ajax_return($data);
		}else{
			$data['status'] = 1;
			$data['info'] = "验证码正确";
			ajax_return($data);
		}
	}
	
	/*
	 * 获取费率
	 */
	public function get_carry_fee(){
		$pTrdAmt = floatval(strim($_REQUEST['money']));
		//$fee = getCarryFee($pTrdAmt,$GLOBALS['user_info']);
		ajax_return(array('status'=>1,'fee'=>0));
	}
	/*
	 * 验证支付密码
	 */
	 public function check_paypassword(){
	 	$paypassword=strim($_REQUEST['paypassword']);
		if(app_conf("PAYPASS_STATUS")){
		if($paypassword==''){
			showErr("请输入支付密码",1);	
		}
		if(md5($paypassword)!=$GLOBALS['user_info']['paypassword']){
			showErr("支付密码错误",1);	
		}
		}
		showSuccess("设置成功",1);
	 }
	 
	/*会员半年内的投资记录 图表数据*/
	public function get_invest_stroke($user_id)
	{
		$result=array();
		
		if(!$user_id)
			$user_id=intval($GLOBALS['user_info']['id']);
		else
			$user_id=intval($user_id);
		
		$x_axis_labels=array();
		$elements_values=array();
		$m_info=array();
		for($i=5;$i>=0;$i--)
		{
			if($i==5)
			{
				$y_m=to_date(NOW_TIME,"Y-m");
				$start_day=$y_m."-1";
				$now_m=to_date(NOW_TIME,"n");
				$now_year=to_date(NOW_TIME,"Y");
				if($now_m ==12)
				{
					$next_m=1;
					$next_year=$now_year+1;
					$next_m_oneday=$next_year."-".$next_m."-1";
				}
				else
				{
					$next_m=$now_m+1;
					$next_m_oneday=$now_year."-".$next_m."-1";
				}
	
				$m_info['start']=to_timespan($start_day,'Y-m-d');
				$m_info['end']=to_timespan($next_m_oneday,"Y-n-d")-1;
				$m_info['next_start']=$m_info['start'];
				$m_info['all_end']=$m_info['end'];
				$x_axis_labels[$i]=to_date(NOW_TIME,"Y-m");
			}
			else
			{
				$m_info['end']=$m_info['next_start']-1;
				$y_m=to_date($m_info['end'],"Y-m");
				$m_info['start']=to_timespan($y_m."-1",'Y-m-d');
				$m_info['next_start']=$m_info['start'];
				if($i==1)
					$m_info['all_start']=$m_info['start'];
					
				$x_axis_labels[$i]=$y_m;
			}
			
			$m_price=floatval($GLOBALS['db']->getOne("select sum(price) from ".DB_PREFIX."deal_support_log where user_id=".$user_id." and create_time > ".$m_info['start']." and create_time < ".$m_info['end']." "));
			$elements_values[$i]=array("top"=>$m_price,"tip"=>$y_m."月<br>投资".format_price($m_price));
		
		}
		
		ksort($elements_values);
		ksort($x_axis_labels);
		$price_all=$GLOBALS['db']->getOne("select sum(price) from ".DB_PREFIX."deal_support_log where user_id=".$user_id." and create_time >".$m_info['all_start']." and create_time < ".$m_info['all_end']."");
		if($price_all >100)
		{
			$max=ceil($price_all/100)*100;
			$max +=ceil($max * 0.1/100)*100;
		}
		elseif($price_all<100 && $price_all>0)
			$max=ceil($price_all/100)*100+200;
		else
			$max=1000;
			
		$result['bg_colour']	= "#ffffff";
		$result["x_axis"]=array(
			   "stroke"=>"1",
			   "tick_height"=>"10",
		   	   "colour"=>"#000",
			   "grid_colour"=>"#000",
			   "labels"=>array(
				    "labels"=>$x_axis_labels
			   )
		);
		$result["elements"]=array(
			array(
					//"type"=>"bar_glass",
					"type"=>"bar",
					"alpha"=>0.8,
				    "colour"=>"#3d9eeb",
					"values"=>$elements_values
				),
		);
		
		$result["y_axis"]=array(
			"stroke"=>"1",
			"tick_length"=>"3",
			"colour"=>"#000",
    		"grid_colour"=>"#000",
			"max"=>$max
		);
		$result["title"]=array(
			"text"=>"半年内总投资额".format_price($price_all),
    		"style"=>"{font-size: 14px; color:#333; font-family: 'Microsoft Yahei','\5FAE\8F6F\96C5\9ED1',Arial,'Hiragino Sans GB','\5B8B\4F53'; text-align: right; padding:10px 0px 10px 0px;}"
		);
		//print_r($result);
		ajax_return($result);
	}
	
	public function weixin_login()
	{
		$session_id=es_session::id();
		$verify = rand(100000, 999999);
		
		$url=get_domain().APP_ROOT."/wap/index.php?session_id=".$session_id."&sess_verify=".$verify;
		es_session::set("sess_verify", $verify);
		$GLOBALS['tmpl']->assign("url",$url);
		$GLOBALS['tmpl']->display("inc/weixin_login.html");
	}
	
	public function do_weixin_login()
	{
		$status=0;
		$user_info=es_session::get("user_info");
		if($user_info){
			$status=1;
		}
		ajax_return($status);
	}
	
	public function do_invest_seccess()
	{
		$retrun=array('status'=>0,'info'=>"");
		$deal_id=intval($_REQUEST['id']);
		$user_info=es_session::get("user_info");
		if($user_info)
		{
			$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".intval($user_info['id'])." and  end_time<".NOW_TIME." and is_success=1 and invest_status=0  and type in(1,4)");
			if($deal_info)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set invest_status=1 where id=".$deal_id." and user_id=".intval($user_info['id'])." and  end_time<".NOW_TIME." and is_success=1 and invest_status=0  and in(1,4)");
				$retrun['status']=1;
				$retrun['info']="融资成功";
			}else
			{
				$retrun['info']="操作失败";
			}
		}
		else
		{
			$retrun['info']="请先登录";
		}
		
		ajax_return($retrun);
	}
	
	public function do_invest_failure()
	{
		$retrun=array('status'=>0,'info'=>"操作失败");
		$deal_id=intval($_REQUEST['id']);
		$user_info=es_session::get("user_info");
		if($user_info)
		{
			$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id=".intval($user_info['id'])." and  end_time<".NOW_TIME." and is_success=1 and invest_status=0 and in(1,4)");
			if($deal_info)
			{//有相应项目
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set invest_status=2,is_success=0 where id=".$deal_id." and user_id=".intval($user_info['id'])." and  end_time<".NOW_TIME." and is_success=1 and invest_status=0  and in(1,4)");
				if($GLOBALS['db']->affected_rows()){
					$order_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where deal_id=".$deal_id." and is_refund=0 and order_status = 3");
					foreach($order_list as $k=>$v)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1,is_success=0 where id = ".$v['id']);
						if($GLOBALS['db']->affected_rows()>0)
						{	
							modify_account(array("money"=>($v['online_pay']+$v['credit_pay'])),$v['user_id'],$v['deal_name']."退款");
							//退回积分
							if($v['score'] >0)
			 				{
								$log_info=$v['deal_name']."退款，退回".$v['score']."积分";
								modify_account(array("score"=>$v['score']),$v['user_id'],$log_info);
			 				}
							
							//扣掉购买时送的积分和信用值
							$sp_multiple=unserialize($v['sp_multiple']);
							if($v['score_multiple']>0)
							{
								$score=intval($v['total_price']*$sp_multiple['score_multiple']);
								$log_info=$v['deal_name']."退款，扣掉".$score."积分";
								modify_account(array("score"=>"-".$score),$v['user_id'],$log_info);
							}	
							if($sp_multiple['point_multiple']>0)
							{
								$point=intval($v['total_price']*$sp_multiple['point_multiple']);
								$log_info=$v['deal_name']."退款，扣掉".$point."信用值";
								modify_account(array("point"=>"-".$point),$v['user_id'],$log_info);
							}
						
						}
						
					}
		 			$retrun['status']=1;
					$retrun['info']="操作成功";
		 		}
			
			}
			//end有相应项目
		}
		else
		{
			$retrun['info']="请先登录";
		}
		
		ajax_return($retrun);
	}
	
	//得到幸运号 
	public function do_get_lottery_sn(){
		$retrun=array('status'=>0,'info'=>"",'url'=>'','html'=>'');
		if(!$GLOBALS['user_info'])
		{	
			$retrun['status']=-1;
			$retrun['info']="";
			ajax_return($retrun);
		}
		$user_id=intval($GLOBALS['user_info']['id']);
		$deal_id=intval($_REQUEST['id']);
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id= ".$user_id."");
		if(!$deal_info)
		{	
			$retrun['status']=0;
			$retrun['info']="没有找到抽奖项目";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['is_success'] !=1)
		{
			$retrun['status']=0;
			$retrun['info']="项目未成功";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['lottery_draw_time'] >0)
		{
			$retrun['status']=0;
			$retrun['info']="已抽过奖了";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		$winner_retrun=load_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
		$GLOBALS['tmpl']->assign("winner_list",$winner_retrun['winner_list']);
		$GLOBALS['tmpl']->assign("deal_id",$deal_id);
		$html=$GLOBALS['tmpl']->fetch("lottery_luckyer.html");
		$retrun['status']=1;
		$retrun['info']="成功";
		$retrun['html']=$html;
		ajax_return($retrun);
		
	}
	
	//验证幸运号
	public function lottery_sn_check(){
		$retrun=array('status'=>0,'info'=>"",'url'=>'');
		if(!$GLOBALS['user_info'])
		{	
			$retrun['status']=-1;
			$retrun['info']="";
			ajax_return($retrun);
		}
		$user_id=intval($GLOBALS['user_info']['id']);
		$deal_id=intval($_REQUEST['deal_id']);
		$number=strim($_REQUEST['number']);
		$lottery_sn=strim($_REQUEST['lottery_sn']);
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id= ".$user_id."");
		if(!$deal_info)
		{	
			$retrun['status']=0;
			$retrun['info']="没有找到抽奖项目";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['is_success'] !=1)
		{
			$retrun['status']=0;
			$retrun['info']="项目未成功";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['lottery_draw_time'] >0)
		{
			$retrun['status']=0;
			$retrun['info']="项目已抽过奖了";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		$lottery_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_lottery where deal_id=".$deal_id." and lottery_sn = '".$lottery_sn."' ");
		if($lottery_info)
		{
			if($lottery_info['is_winner']==1)
			{
				$retrun['status']=2;
				$retrun['info']=$lottery_info['lottery_sn']."已是幸运号,请修改";
				ajax_return($retrun);
			}elseif($lottery_info['is_winner']==2)
			{
				$retrun['status']=2;
				$retrun['info']=$lottery_info['lottery_sn']."已抽过奖";
				ajax_return($retrun);
			}
			elseif($lottery_info['is_winner']==3)
			{
				$retrun['status']=2;
				$retrun['info']=$lottery_info['lottery_sn']."订单已退款,已无效,请修改";
				ajax_return($retrun);
			}elseif($lottery_info['is_winner']==0){
				
				$winner_retrun=load_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
				$winner_list=$winner_retrun['winner_list'];
				$section=$winner_list[$number]['section'];
				//list($item_id,$lottery_sn_num)=explode('_',$lottery_sn);
				$sn_array=split_lottery_sn($lottery_sn);
				$lottery_sn_num=$sn_array['sn_number'];
				if($section['start_sn']<=$lottery_sn_num && $lottery_sn_num<=$section['last_sn'])
				{
					$retrun['status']=1;
					$retrun['info']="正确";
					$retrun['user_name']=$lottery_info['user_name'];
					ajax_return($retrun);
				}
				else
				{
					$retrun['status']=2;
					$retrun['info']="不是本号段号吗";
					ajax_return($retrun);
				}
			}else{
				$retrun['status']=2;
				$retrun['info']=$lottery_info['lottery_sn']."无效,请修改";
				ajax_return($retrun);
			}
			
		}
		else{
			$retrun['status']=2;
			$retrun['info']="不是正确的抽奖号";
			ajax_return($retrun);
		}
		
	}
	
	//确定幸运号
	public function do_lottery_luckyer(){
		$retrun=array('status'=>0,'info'=>"",'url'=>'');
		if(!$GLOBALS['user_info'])
		{	
			$retrun['status']=-1;
			$retrun['info']="";
			ajax_return($retrun);
		}
		
		$user_id=intval($GLOBALS['user_info']['id']);
		$lottery_num=$_REQUEST['lottery_num'];
		$deal_id=intval($_REQUEST['deal_id']);
		
		$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$deal_id." and user_id= ".$user_id."");
		if(!$deal_info)
		{	
			$retrun['status']=0;
			$retrun['info']="没有找到抽奖项目";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['is_success'] !=1)
		{
			$retrun['status']=0;
			$retrun['info']="项目未成功";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		if($deal_info['lottery_draw_time'] >0)
		{
			$retrun['status']=0;
			$retrun['info']="项目已抽过奖了";
			$retrun['url']=url("account#project");
			ajax_return($retrun);
		}
		
		$lottery_list=$GLOBALS['db']->getAll("select lot.*,ord.mobile,ord.deal_name,u.mobile as user_mobile from ".DB_PREFIX."deal_order_lottery as lot " .
				"left join ".DB_PREFIX."deal_order as ord on ord.id = lot.order_id " .
				"left join ".DB_PREFIX."user as u on u.id = lot.user_id " .
				"where lot.deal_id=".$deal_id." and ord.order_status =3 and lot.lottery_sn in('".implode("','",$lottery_num)."') order by lot.id asc");
		
		if(!$lottery_list)
		{
			$retrun['status']=0;
			$retrun['info']="没有找到相应抽奖号";
			$retrun['url']=url("account#lottery",array('id'=>$deal_id));
			ajax_return($retrun);
		}
		
		//判断号码 有效性
		foreach($lottery_list as $k=>$v)
		{
			if($v['is_winner']==0)
			{}
			elseif($v['is_winner']==1)
			{
				$retrun['status']=0;
				$retrun['info']=$v['lottery_sn']."已是幸运号,请修改";
				ajax_return($retrun);
			}elseif($v['is_winner']==2)
			{
				$retrun['status']=0;
				$retrun['info']=$v['lottery_sn']."已抽过奖";
				ajax_return($retrun);
			}
			elseif($v['is_winner']==3)
			{
				$retrun['status']=0;
				$retrun['info']=$v['lottery_sn']."订单已退款,已无效,请修改";
				ajax_return($retrun);
			}else{
				$retrun['status']=0;
				$retrun['info']=$v['lottery_sn']."已无效,请修改";
				ajax_return($retrun);
			}
			
		}
		
		$winner_retrun=load_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
		$winner_list=$winner_retrun['winner_list'];
		
		$winner_list2=$winner_list;
		if(count($lottery_list) != count($winner_list))
		{
			//删除幸运号列表缓存
			rm_auto_cache('lottery_luckyers',array('deal_id'=>$deal_id));
			$retrun['status']=0;
			$retrun['info']="抽奖号个数不对";
			$retrun['url']=url("account#lottery",array('id'=>$deal_id));
			ajax_return($retrun);
		}
		
		//判断所属区间是否都有 抽奖号
		foreach($lottery_list as $k=>$v)
		{
			//list($v_item, $v_sn)=explode('_',$v['lottery_sn']);
			$sn_array=split_lottery_sn($v['lottery_sn']);
			$v_sn=$sn_array['sn_number'];
			$in_section=0;
			foreach($winner_list2 as $kk=>$vv)
			{
				$vv_item=explode('_',$kk);
				$vv_item_id=$vv_item['0'];
				if($v['deal_item_id'] == $vv_item_id)
				{
					$section=$vv['section'];
					if($v_sn >= $section['start_sn'] && $v_sn <= $section['last_sn'])
					{
						$in_section=1;
						unset($winner_list2[$kk]);
						break;
					}
				}
			}

			if($in_section ==0)
			{
				$retrun['status']=0;
				$retrun['info']=$v['lottery_sn']."不在对应的区间里";
				ajax_return($retrun);
			}
		}
		
		//标记抽奖号为幸运号及后续处理
		$handle_retrun=handle_luckyer_lotter_sn($lottery_num,$lottery_list,$deal_id,$user_id);
		if($handle_retrun['status'] ==1)
		{
			$retrun['status']=1;
			$retrun['info']="成功";
			$retrun['url']='';
		}else{
			$retrun['status']=0;
			$retrun['info']="抽奖失败";
			$retrun['url']=url("account#lottery",array('id'=>$deal_id));
		}
		ajax_return($retrun);
	}

	public  function  get_payment_status(){
		$id = $_GET['order_id'];
		$order_status = $GLOBALS['db']->getOne("select order_status  from ".DB_PREFIX."deal_order where id=".$id);
 		$data = array('status'=>1,'order_status'=>$order_status);
		ajax_return($data);
	}
}
?>
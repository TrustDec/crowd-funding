<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'app/Lib/page.php';
class homeModule
{
	public function index()
	{		
        $GLOBALS['tmpl']->assign("page_title","主页");
        
		$id = intval($_REQUEST['id']);
		$GLOBALS['tmpl']->assign("u_id",$id);
		$type='other';
		if(!$id){
			$id=$GLOBALS['user_info']['id'];
			$type='home';
		}
		 
		$home_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id." and is_effect = 1");
		if(!$home_user_info)
		{
			app_redirect(url_wap("index"));	
		}
		
		$home_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$home_user_info['id']); // 用户微博
		$home_user_info['user_icon']=$GLOBALS['user_level'][$home_user_info['user_level']]['icon']; // 用户等级
		$home_user_info['cate_name'] =unserialize($home_user_info["cate_name"]); // 所在行业领域
		$GLOBALS['tmpl']->assign("home_user_info",$home_user_info);
		$page_size = DEAL_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;	
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		$condition = " d.is_delete = 0 and d.is_effect = 1 "; 
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition.=" and 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition.=" and d.type=0 ";
		}
		elseif(app_conf("INVEST_STATUS")==2)
		{
			$condition.=" and d.type=1 ";
		}
		
		$GLOBALS['tmpl']->assign('deal_type','home');
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal as d where ".$condition." and d.user_id = ".intval($home_user_info['id']));
		/*（home模块）准备虚拟数据 start*/
			$deal_list = array();
			if($deal_count > 0){
				$now_time = get_gmtime();
				$deal_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal as d where ".$condition."and d.user_id = ".intval($home_user_info['id'])." order by d.sort asc limit ".$limit);
				$deal_ids = array();
				foreach($deal_list as $k=>$v)
				{
					$deal_list[$k]['remain_days'] = floor(($v['end_time'] - NOW_TIME)/(24*3600));
					if($v['begin_time'] > $now_time){
						$deal_list[$k]['left_days'] = intval(($now_time - $v['create_time']) / 24 / 3600);
						$deal_list[$k]['left_begin_day'] = intval(($v['begin_time']  - $now_time));
					}
					$deal_ids[] =  $v['id'];
				}
				//获取当前项目列表下的所有子项目
				$temp_virtual_person_list = $GLOBALS['db']->getAll("select deal_id,virtual_person,price from ".DB_PREFIX."deal_item where deal_id in(".implode(",",$deal_ids).") ");
				$virtual_person_list  = array();
				//重新组装一个以项目ID为KEY的 统计所有的虚拟人数和虚拟价格
				foreach($temp_virtual_person_list as $k=>$v){
					$virtual_person_list[$v['deal_id']]['total_virtual_person'] += $v['virtual_person'];
					$virtual_person_list[$v['deal_id']]['total_virtual_price'] += $v['price'] * $v['virtual_person'];
				}
				unset($temp_virtual_person_list);
				//将获取到的虚拟人数和虚拟价格拿到项目列表里面进行统计
				foreach($deal_list as $k=>$v)
				{
					if($v['type']==1)
					{
						$deal_list[$k]['virtual_person']=$deal_list[$k]['invote_num'];
						$deal_list[$k]['support_count'] =$deal_list[$k]['invote_num'];
						$deal_list[$k]['support_amount'] =$deal_list[$k]['invote_money'];
						$deal_list[$k]['percent'] = round(($deal_list[$k]['support_amount'])/$v['limit_price']*100,2);
						$deal_list[$k]['limit_price_w']=round(($deal_list[$k]['limit_price'])/10000);
						$deal_list[$k]['invote_mini_money_w']=round(($deal_list[$k]['invote_mini_money'])/10000);
					}else
					{
						$deal_list[$k]['virtual_person']=$virtual_person_list[$v['id']]['total_virtual_person'];
						$deal_list[$k]['percent'] = round(($v['support_amount']+$virtual_person_list[$v['id']]['total_virtual_price'])/$v['limit_price']*100,2);
						$deal_list[$k]['support_count'] += $deal_list[$k]['virtual_person'];
						$deal_list[$k]['support_amount'] += $virtual_person_list[$v['id']]['total_virtual_price'];
					}
					
				}
			}
		/*（home模块）准备虚拟数据 end*/
		//var_dump($deal_list);
// 		$deal_invest_result = get_deal_list($limit,'type=1');
// 		$deal_list['list']=$deal_invest_result['list'];
		$GLOBALS['tmpl']->assign("deal_list",$deal_list);
		$GLOBALS['tmpl']->assign("deal_count",$deal_count);
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		
		
		/*支持的项目开始*/
		$sqlsupport = " ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_support_log as dsl on d.id = dsl.deal_id ".
			   " where ".$condition." and dsl.user_id = ".$home_user_info['id'];

		$deal_support_count = $GLOBALS['db']->getOne("select count(distinct(d.id)) from ".$sqlsupport);
		
			$deal_list = array();
			if($deal_support_count > 0){
				$now_time = get_gmtime();
				$deal_list = $GLOBALS['db']->getAll("select distinct(d.id) as id,d.* from ".$sqlsupport." order by d.sort asc ");
				
			}
		$GLOBALS['tmpl']->assign("deal_support_list",$deal_list);
		$GLOBALS['tmpl']->assign("deal_support_count",$deal_support_count);
		/*支持的项目结束*/
		
		/*关注的项目开始*/
		$app_sql = " ".DB_PREFIX."deal_focus_log as dfl left join ".DB_PREFIX."deal as d on d.id = dfl.deal_id where ".$condition." and dfl.user_id = ".intval($home_user_info['id']);
		
		$deal_focus_list = $GLOBALS['db']->getAll("select distinct(d.id) as id,d.*,dfl.id as fid from ".$app_sql." order by d.sort asc ");
		$deal_focus_count = $GLOBALS['db']->getOne("select count(distinct(d.user_id)) from ".$app_sql." and d.user_id<>".intval($home_user_info['id']));
		$GLOBALS['tmpl']->assign('deal_focus_list',$deal_focus_list);	
		$GLOBALS['tmpl']->assign("deal_focus_count",$deal_focus_count);
		//关注的项目人信息
		$deal_focus_user=$GLOBALS['db']->getAll("select d.*,dfl.id as fid from ".$app_sql." and d.user_id<>".intval($home_user_info['id'])." group by d.user_id");
		$GLOBALS['tmpl']->assign("deal_focus_user",$deal_focus_user);
		/*关注的项目结束*/
		
		/*粉丝开始*/
		$sql_focused=" ".DB_PREFIX."deal_focus_log as dfl left join ".DB_PREFIX."deal as d on d.id = dfl.deal_id left join ".DB_PREFIX."user as u on dfl.user_id=u.id and d.user_id where ".$condition." and d.user_id=".intval($home_user_info['id'])." and dfl.user_id<>".intval($home_user_info['id']);
		$deal_focused_list=$GLOBALS['db']->getAll("select u.* from ".$sql_focused." group by dfl.user_id");
	
		$deal_focused_count=$GLOBALS['db']->getOne("select count(distinct(dfl.user_id)) from ".$sql_focused);
		$GLOBALS['tmpl']->assign('deal_focused_count',$deal_focused_count);	
		$GLOBALS['tmpl']->assign('deal_focused_list',$deal_focused_list);	
		/*粉丝结束*/
				
		$GLOBALS['tmpl']->assign("seo_title",$home_user_info['user_name']);
		//关注城市
		$gz_region=unserialize($home_user_info['gz_region']);
		$GLOBALS['tmpl']->assign("gz_region",$gz_region);
		if($type=='home'){
			//会员半年内的投资记录url
	        $GLOBALS['tmpl']->assign("invest_stroke_url", urlencode(url_wap("ajax#get_invest_stroke",array('fhash'=>HASH_KEY()))));
			$GLOBALS['tmpl']->display("home_index.html");
		}else{
			 if($home_user_info['is_investor']==2){
			 	$now_time = NOW_TIME;
				//投资机构
				//机构成立时间
				$company_create_time =to_date ($home_user_info['company_create_time'], 'Y-m-d' ); 
				$GLOBALS['tmpl']->assign("company_create_time",$company_create_time);
				//机构成员
				$company_list =$GLOBALS['db']->getAll("select i.*,u.user_name as user_name,u.is_investor as is_investor from ".DB_PREFIX."finance_company_team as i left join ".DB_PREFIX."user as u on i.user_id = u.id  where i.company_id =".$id." and i.type =4 and i.status =1 order by i.create_time desc");
				$company_count =$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_team as i left join ".DB_PREFIX."user as u on i.user_id = u.id  where i.company_id =".$id." and i.type =4 and i.status =1 order by i.create_time desc");
				$GLOBALS['tmpl']->assign("company_count",$company_count);
				$GLOBALS['tmpl']->assign("company_list",$company_list);
				//投资案例（融资案例）
				$finance_list = $GLOBALS['db']->getAll("select distinct(fc.id),fc.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = d.company_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc LIMIT 0,6");
				foreach($finance_list as $k=>$v)
				{
					$finance_list[$k]['deal_list']= $GLOBALS['db']->getAll("select d.*,sum(do.total_price) as total_price from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 and company_id =".$v['id']." GROUP BY d.id order by d.invest_phase asc");
					foreach($finance_list[$k]['deal_list'] as $kk=>$vv)
					{
						$finance_list[$k]['deal_list'][$kk]['total_price']=$vv['total_price']/10000;
					}
				}
				$finance_count = $GLOBALS['db']->getOne("select count(distinct(fc.id)) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = d.company_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
				$GLOBALS['tmpl']->assign("finance_count",$finance_count);
				$GLOBALS['tmpl']->assign("finance_list",$finance_list);
				//投资案例（融资案例）分类
				$finance_cate_list = $GLOBALS['db']->getAll("select distinct(dc.id),dc.name as cate_name from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."deal_cate as dc on dc.id = d.cate_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by d.invest_phase asc");
				$GLOBALS['tmpl']->assign("finance_cate_list",$finance_cate_list);
				//股权案例
				$equity_list = $GLOBALS['db']->getAll("select distinct(d.id) as id,d.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 1 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc LIMIT 0,8");
				foreach($equity_list as $k=>$v)
				{
					$equity_list[$k]['remain_days'] = ceil(($v['end_time'] - $now_time)/(24*3600));
					if($v['begin_time'] > $now_time){
						$equity_list[$k]['left_days'] = ceil(($v['begin_time'] - $now_time) / 24 / 3600);
					}
					$equity_list[$k]['num_days'] = ceil(($v['end_time'] - $v['begin_time'])/(24*3600));
					$deal_ids[] =  $v['id'];
					if($v['begin_time'] > $now_time){
						$equity_list[$k]['left_begin_days'] = intval(($v['begin_time']  - $now_time) / 24 / 3600);
						$equity_list[$k]['left_begin_day'] = intval(($v['begin_time']  - $now_time));
					}
					if($v['begin_time'] > $now_time){
							$equity_list[$k]['status']= '0';                                 
					}
					elseif($v['end_time'] < $now_time && $v['end_time']>0){
						if($equity_list[$k]['percent'] >=100){
							$equity_list[$k]['status']= '1';  
						}
						else{
								$equity_list[$k]['status']= '2'; 
						}
					} 
					else{
							if ($v['end_time'] > 0) {
								$equity_list[$k]['status']= '3'; 
							}
							else
							$equity_list[$k]['status']= '4'; 
					}
					$equity_list[$k]['virtual_person']=$equity_list[$k]['invote_num'];
					$equity_list[$k]['support_count'] =$equity_list[$k]['invote_num'];
					$equity_list[$k]['support_amount'] =$equity_list[$k]['invote_money'];
					$equity_list[$k]['percent'] = round(($equity_list[$k]['support_amount'])/$v['limit_price']*100,2);
					$equity_list[$k]['limit_price_w']=($equity_list[$k]['limit_price'])/10000;
					$equity_list[$k]['invote_mini_money_w']=number_format(($equity_list[$k]['invote_mini_money'])/10000,2);				
					$equity_list[$k]['bonus_count']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bonus where status = 1 and deal_id =".$v['id']);
				}
				$equity_count = $GLOBALS['db']->getOne("select count(distinct(d.id)) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 1 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
				$GLOBALS['tmpl']->assign("equity_count",$equity_count);
				$GLOBALS['tmpl']->assign("equity_list",$equity_list);
				//股权案例分类
				$equity_cate_list = $GLOBALS['db']->getAll("select distinct(dc.id),dc.name as cate_name from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."deal_cate as dc on dc.id = d.cate_id where do.type = 1 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
				$GLOBALS['tmpl']->assign("equity_cate_list",$equity_cate_list);
				//产品案例
				$product_list = $GLOBALS['db']->getAll("select distinct(d.id),d.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 0 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc LIMIT 0,8");
				foreach($product_list as $k=>$v)
				{
					$product_list[$k]['remain_days'] = ceil(($v['end_time'] - $now_time)/(24*3600));
					if($v['begin_time'] > $now_time){
						$product_list[$k]['left_days'] = ceil(($v['begin_time'] - $now_time) / 24 / 3600);
					}
					$product_list[$k]['num_days'] = ceil(($v['end_time'] - $v['begin_time'])/(24*3600));
				
					if($v['begin_time'] > $now_time){
						$product_list[$k]['left_begin_days'] = intval(($v['begin_time']  - $now_time) / 24 / 3600);
						$product_list[$k]['left_begin_day'] = intval(($v['begin_time']  - $now_time));
					}
					if($v['begin_time'] > $now_time){
							$product_list[$k]['status']= '0';                                 
					}
					elseif($v['end_time'] < $now_time && $v['end_time']>0){
						if($product_list[$k]['percent'] >=100){
							$product_list[$k]['status']= '1';  
						}
						else{
								$product_list[$k]['status']= '2'; 
						}
					} 
					else{
							if ($v['end_time'] > 0) {
								$product_list[$k]['status']= '3'; 
							}
							else
							$product_list[$k]['status']= '4'; 
					}	
					$product_list[$k]['virtual_person']=$product_list[$k]['virtual_num'];
					$product_list[$k]['support_count'] =$product_list[$k]['virtual_num']+$product_list[$k]['support_count'];
					$product_list[$k]['support_amount'] =$product_list[$k]['virtual_price']+$product_list[$k]['support_amount'];
					$product_list[$k]['percent'] = round(($product_list[$k]['support_amount'])/$v['limit_price']*100,2);
				}
				$product_count = $GLOBALS['db']->getOne("select count(distinct(d.id)) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 0 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
				$GLOBALS['tmpl']->assign("product_count",$product_count);
				$GLOBALS['tmpl']->assign("product_list",$product_list);
				//产品案例分类
				$product_cate_list = $GLOBALS['db']->getAll("select distinct(dc.id),dc.name as cate_name from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."deal_cate as dc on dc.id = d.cate_id where do.type = 0 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
				$GLOBALS['tmpl']->assign("product_cate_list",$product_cate_list);
				
				$GLOBALS['tmpl']->display("home_organize.html");
			}else{
				//投资人 普通用户
				
				//工作经历
				$finance_company_group = $GLOBALS['db']->getAll("select fc.*,fcy.company_name as company_name,fcy.company_create_time as company_create_time,fcy.company_logo as company_logo,fcy.user_id as fc_user_id from ".DB_PREFIX."finance_company_team as fc LEFT JOIN ".DB_PREFIX."finance_company as fcy on fcy.id = fc.company_id where fc.user_id = ".$id." and fc.status = 1 GROUP BY fc.company_id order by fc.id asc");
				$GLOBALS['tmpl']->assign('finance_company_group',$finance_company_group);
				//关注的公司
				$finance_focus = $GLOBALS['db']->getAll("select fc.* from ".DB_PREFIX."finance_company_focus as fcf LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = fcf.company_id where fcf.user_id = ".$id);
				$finance_focus_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."finance_company_focus as fcf LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = fcf.company_id where fcf.user_id = ".$id);
				$GLOBALS['tmpl']->assign('finance_focus',$finance_focus);
				$GLOBALS['tmpl']->assign('finance_focus_count',$finance_focus_count);
				
				$GLOBALS['tmpl']->display("home_other.html");
			}
 		}
			
	}
	//机构投资列表
	public function organize_list(){
		$GLOBALS['tmpl']->assign("page_title","融资列表");
		$id = intval($_REQUEST['id']);
		$cate_id = intval($_REQUEST['cate_id']);
		$cate_id = $cate_id?$cate_id:'';
		$GLOBALS['tmpl']->assign("cate_id",$cate_id);
		$page_size = DEAL_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;	
		if($cate_id){
			$finance_list = $GLOBALS['db']->getAll("select distinct(fc.id),fc.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = d.company_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 and d.cate_id =".$cate_id." order by do.id desc  limit ".$limit);
		}
		else{
			$finance_list = $GLOBALS['db']->getAll("select distinct(fc.id),fc.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = d.company_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc  limit ".$limit);
			
		}
		
		foreach($finance_list as $k=>$v)
		{
			$finance_list[$k]['deal_list']= $GLOBALS['db']->getAll("select d.*,sum(do.total_price) as total_price from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 and company_id =".$v['id']." GROUP BY d.id order by d.invest_phase asc");
			$finance_list[$k]['deal_count']= $GLOBALS['db']->getAll("select count(*) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 and company_id =".$v['id']." GROUP BY d.id ");
			foreach($finance_list[$k]['deal_list'] as $kk=>$vv)
			{
				$finance_list[$k]['deal_list'][$kk]['total_price']=$vv['total_price']/10000;
			}
		}
		$finance_count = $GLOBALS['db']->getOne("select count(distinct(fc.id)) from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."finance_company as fc on fc.id = d.company_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
		$GLOBALS['tmpl']->assign("finance_count",$finance_count);
		$page = new Page($finance_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);	
		$GLOBALS['tmpl']->assign("finance_list",$finance_list);
		//投资案例（融资案例）分类
		$finance_cate_list = $GLOBALS['db']->getAll("select distinct(dc.id),dc.name as cate_name from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal as d on do.deal_id = d.id LEFT JOIN ".DB_PREFIX."deal_cate as dc on dc.id = d.cate_id where do.type = 5 and do.user_id = ".$id." and do.is_success = 1 order by do.id desc");
		$GLOBALS['tmpl']->assign("finance_cate_list",$finance_cate_list);
		
		$GLOBALS['tmpl']->display("home_organize_list.html");
	}
	
	public function support()
	{	
                    
		$GLOBALS['tmpl']->assign("page_title","支持的项目");

		$id = intval($_REQUEST['id']);
		$type='other';
		if(!$id){
			$id=$GLOBALS['user_info']['id'];
			$type='home';
		}
		$home_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id." and is_effect = 1");
		if(!$home_user_info)
		{
			app_redirect(url_wap("index"));	
		}
		
		$home_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$home_user_info['id']);
		$home_user_info['user_icon']=$GLOBALS['user_level'][$home_user_info['user_level']]['icon']; // 用户等级
		$home_user_info['cate_name'] =unserialize($home_user_info["cate_name"]); // 所在行业领域
		
		$GLOBALS['tmpl']->assign("home_user_info",$home_user_info);
		
		$page_size = DEAL_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;	
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition=" 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition=" d.type=0 ";
		}
		elseif(app_conf("INVEST_STATUS")==2)
		{
			$condition=" d.type=1 ";
		}
		
		$sql = "select distinct(d.id) as id,d.* from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_support_log as dsl on d.id = dsl.deal_id ".
			   " where $condition and dsl.user_id = ".$home_user_info['id']." order by d.sort asc limit ".$limit;
	
		$sql_count = "select count(distinct(d.id)) from ".DB_PREFIX."deal as d left join ".DB_PREFIX."deal_support_log as dsl on d.id = dsl.deal_id ".
			   " where $condition and dsl.user_id = ".$home_user_info['id'];
		//得到当前页面项目信息
	
		$deal_count = $GLOBALS['db']->getOne($sql_count);
		/*（home模块）准备虚拟数据 start*/
			$deal_list = array();
			if($deal_count > 0){
				$now_time = get_gmtime();
				$deal_list = $GLOBALS['db']->getAll($sql);
				
				$deal_ids = array();
				foreach($deal_list as $k=>$v)
				{
					$deal_list[$k]['remain_days'] = floor(($v['end_time'] - NOW_TIME)/(24*3600));
					if($v['begin_time'] > $now_time){
						$deal_list[$k]['left_days'] = intval(($now_time - $v['create_time']) / 24 / 3600);
					}
					$deal_ids[] =  $v['id'];
				}
				//获取当前项目列表下的所有子项目
				$temp_virtual_person_list = $GLOBALS['db']->getAll("select deal_id,virtual_person,price from ".DB_PREFIX."deal_item where deal_id in(".implode(",",$deal_ids).") ");
				$virtual_person_list  = array();
				//重新组装一个以项目ID为KEY的 统计所有的虚拟人数和虚拟价格
				foreach($temp_virtual_person_list as $k=>$v){
					$virtual_person_list[$v['deal_id']]['total_virtual_person'] += $v['virtual_person'];
					$virtual_person_list[$v['deal_id']]['total_virtual_price'] += $v['price'] * $v['virtual_person'];
				}
				unset($temp_virtual_person_list);
				//将获取到的虚拟人数和虚拟价格拿到项目列表里面进行统计
				foreach($deal_list as $k=>$v)
				{
					if($v['type']==1)
					{
						$deal_list[$k]['virtual_person']=$deal_list[$k]['invote_num'];
						$deal_list[$k]['support_count'] =$deal_list[$k]['invote_num'];
						$deal_list[$k]['support_amount'] =$deal_list[$k]['invote_money'];
						$deal_list[$k]['percent'] = round(($deal_list[$k]['support_amount'])/$v['limit_price']*100,2);
						$deal_list[$k]['limit_price_w']=round(($deal_list[$k]['limit_price'])/10000);
						$deal_list[$k]['invote_mini_money_w']=round(($deal_list[$k]['invote_mini_money'])/10000);
					}
					else
					{
						$deal_list[$k]['virtual_person']=$virtual_person_list[$v['id']]['total_virtual_person'];
						$deal_list[$k]['percent'] = round(($v['support_amount']+$virtual_person_list[$v['id']]['total_virtual_price'])/$v['limit_price']*100,2);
						$deal_list[$k]['support_count'] += $deal_list[$k]['virtual_person'];
						$deal_list[$k]['support_amount'] += $virtual_person_list[$v['id']]['total_virtual_price'];
					}
				}
			}
		/*（home模块）准备虚拟数据 end*/
		$GLOBALS['tmpl']->assign("deal_list",$deal_list);
		$GLOBALS['tmpl']->assign("deal_count",$deal_count);
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);		
		
 		
		if($type=='home'){
			$GLOBALS['tmpl']->display("home_support.html");
		}else{
			$GLOBALS['tmpl']->display("home_other_support.html");
		}
	}
	public function focus()
	{	
                    
		$GLOBALS['tmpl']->assign("page_title","关注的项目");

		$id = intval($_REQUEST['id']);
		$type='other';
		if(!$id){
			$id=$GLOBALS['user_info']['id'];
			$type='home';
		}
		$home_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id." and is_effect = 1");
		if(!$home_user_info)
		{
			app_redirect(url_wap("index"));	
		}
		
		$home_user_info['weibo_list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_weibo where user_id = ".$home_user_info['id']);
		$home_user_info['user_icon']=$GLOBALS['user_level'][$home_user_info['user_level']]['icon']; // 用户等级
		$home_user_info['cate_name'] =unserialize($home_user_info["cate_name"]); // 所在行业领域
		$GLOBALS['tmpl']->assign("home_user_info",$home_user_info);
		
		$page_size = DEAL_PAGE_SIZE;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;	
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		if(app_conf("INVEST_STATUS")==0)
		{
			$condition=" 1=1 ";
		}
		elseif (app_conf("INVEST_STATUS")==1)
		{
			$condition=" d.type=0 ";
		}
		elseif(app_conf("INVEST_STATUS")==2)
		{
			$condition=" d.type=1 ";
		}
		
		$app_sql = " ".DB_PREFIX."deal_focus_log as dfl left join ".DB_PREFIX."deal as d on d.id = dfl.deal_id where $condition and dfl.user_id = ".intval($home_user_info['id']).
				   " and d.is_effect = 1 and d.is_delete = 0  ";
		
		$deal_list = $GLOBALS['db']->getAll("select d.*,dfl.id as fid from ".$app_sql."  limit ".$limit);
		
		$deal_count = $GLOBALS['db']->getOne("select count(*) from ".$app_sql);
		foreach($deal_list as $k=>$v)
		{
			$deal_list[$k]['remain_days'] = ceil(($v['end_time'] - NOW_TIME)/(24*3600));
			$deal_list[$k]['percent'] = round($v['support_amount']/$v['limit_price']*100,2);
			if($v['type']== 0){
				
				$deal_list[$k]['support_amount']= $deal_list[$k]['support_amount']+ $deal_list[$k]['virtual_price'];
				$deal_list[$k]['percent'] = round($deal_list[$k]['support_amount']/$v['limit_price']*100,2);
				$deal_list[$k]['support_count']= $deal_list[$k]['support_count']+ $deal_list[$k]['virtual_num'];
			}
			if($v['type']== 1){
				$deal_list[$k]['percent']= round($v['invote_money']/$v['limit_price']*100,2);
				$deal_list[$k]['invote_mini_money_w']=number_format(($deal_list[$k]['invote_mini_money'])/10000,2);
			}
		}
		
		$page = new Page($deal_count,$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);

		$GLOBALS['tmpl']->assign('deal_list',$deal_list);	

		$GLOBALS['tmpl']->assign("deal_count",$deal_count);
 		
		if($type=='home'){
			$GLOBALS['tmpl']->display("home_focus.html");
		}else{
			$GLOBALS['tmpl']->display("home_other_focus.html");
		}
	}
	public function deal_list($input_type=0)
	{	
		$u_id= intval($_REQUEST['u_id']);
		if(intval($input_type)){
			$_REQUEST['type'] = intval($input_type);
		}	
		if(strim($_REQUEST['type'])==1 && app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		if(app_conf("INVEST_STATUS")==2 && strim($_REQUEST['type'])==null)
		{
			showErr("回报众筹已经关闭");
		}
		if(app_conf("IS_SELFLESS")==0 && strim($_REQUEST['type'])==3)
		{
			showErr("公益众筹已经关闭");
		}
        
        $GLOBALS['tmpl']->assign("page_title","最新动态");
        
         $param = array();//参数集合
           
         //数据来源参数
		$r = strim($_REQUEST['r']);   //推荐类型
        $param['r'] = $r?$r:'';
		$GLOBALS['tmpl']->assign("p_r",$r);
                
		$id = intval($_REQUEST['id']);  //分类id
		$param['id'] = $id;
		$GLOBALS['tmpl']->assign("p_id",$id);
		
		$loc = strim($_REQUEST['loc']);  //地区
		$param['loc'] = $loc;
		$GLOBALS['tmpl']->assign("p_loc",$loc);
        
        $state = intval($_REQUEST['state']);  //状态
        $param['state'] = $state;
		$GLOBALS['tmpl']->assign("p_state",$state);
        
        
		$tag = strim($_REQUEST['tag']);  //标签
		$param['tag'] = $tag;
		$GLOBALS['tmpl']->assign("p_tag",$tag);
                
		$kw = strim($_REQUEST['k']);    //关键词
		$param['k'] = $kw;
		$GLOBALS['tmpl']->assign("p_k",$kw);
		          
   		$type = intval($_REQUEST['type']);   //推荐类型
   		
        $param['type'] = $type;
		$GLOBALS['tmpl']->assign("p_type",$type);
		$param_new=$param;
		 
		//融资金额
		$price = intval($_REQUEST['price']);  
		if($price>0){
			$param['price'] = $price;
			$GLOBALS['tmpl']->assign("price",$price);
		} 
 		//关注数
		$focus = intval($_REQUEST['focus']);   
		if($focus>0){
	        $param['focus'] = $focus;
			$GLOBALS['tmpl']->assign("focus",$focus);
		} 
		
		//剩余时间
		$time = intval($_REQUEST['time']);   
		if($time>0){
	        $param['time'] = $time;
			$GLOBALS['tmpl']->assign("time",$time);
		} 
		//完成比例
		$cp = intval($_REQUEST['cp']); 
		if($cp>0){  
	        $param['cp'] = $cp;
			$GLOBALS['tmpl']->assign("cp",$cp);
		} 
		
		if(intval($_REQUEST['redirect'])==1)
		{
  			app_redirect(url_wap("home#deal_list",$param));
		}
		 
 		$cate_list = load_dynamic_cache("INDEX_CATE_LIST");
		
		if(!$cate_list)
		{
			$cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate where is_delete=0 order by sort asc");
			set_dynamic_cache("INDEX_CATE_LIST",$cate_list);
		}
		$cate_result = array();
		foreach($cate_list as $k=>$v){
			if($v['pid'] == 0){
				$temp_param = $param;
				$cate_result[$k+1]['id'] = $v['id'];
				$cate_result[$k+1]['name'] = $v['name'];
				$temp_param['id'] = $v['id'];
				$cate_result[$k+1]['url'] = url_wap("home#deal_list",$temp_param);
			}
		}
		
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		
		$pid = $id;
		//获取父类id
		
		if($cate_list){
			$pid = $this->get_child($cate_list,$pid);
		}
		/*子分类 start*/
		$cate_ids = array();
		$is_child = false;
		$temp_cate_ids = array();
		
		if($cate_list){
			$child_cate_result= array();
			foreach($cate_list as $k=>$v)
			{
				if($v['pid'] == $pid){
					if($v['id'] > 0){
						$temp_param = $param;
						$child_cate_result[$v['id']]['id'] = $v['id'];
						$child_cate_result[$v['id']]['name'] = $v['name'];
						$temp_param['id'] = $v['id'];
						$child_cate_result[$v['id']]['url'] = url_wap("home#deal_list",$temp_param);
						 if($id==$v['id']){
						 	$is_child = true;
						 }
						
					}
				}
				if($v['pid'] == $pid || $pid==0){
					$temp_cate_ids[] = $v['id'];
				}
			}		
		}
		
		//假如选择了子类 那么使用子类ID  否则使用 父类和其子类
		if($is_child){
			$cate_ids[] = $id;
		}
		else{
			$cate_ids[] = $pid;
			$cate_ids = array_merge($cate_ids,$temp_cate_ids);
		}
 		$cate_ids=array_filter($cate_ids);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
		/*子分类 end*/
       $city_list = load_dynamic_cache("INDEX_CITY_LIST"); 
       
       if($type ==1){
	       	if(!$city_list)
			{
				$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal where type=1 and is_effect =1 group by province  order by sort asc");
			//	set_dynamic_cache("INDEX_CITY_LIST",$city_list);
			}
			
       }
       if($type ==0||$type ==3){
       		if(!$city_list)
			{
				$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal where type=".$type." and is_effect =1 group by province  order by sort asc");
			//	set_dynamic_cache("INDEX_CITY_LIST",$city_list);
			}
       }
        
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = url_wap("home#deal_list",$temp_param);
		}
        	
		$GLOBALS['tmpl']->assign("city_list",$city_list);
		
		//============region_conf============
		$area_list = $GLOBALS['db']->getAll("select rc.* from ".DB_PREFIX."region_conf as rc where rc.name in (select province from ".DB_PREFIX."deal) or  rc.name in (select city from ".DB_PREFIX."deal) or rc.is_hot=1 ");
		$area=array();
		$hot_area=array();
		foreach($area_list as $k=>$v){
			$temp_param['loc'] = $v['name'];
			$area[strtoupper($v['py'][0])][$v['name']]=array('url'=> url_wap("home#deal_list",$temp_param),'name'=>$v['name']);
			if($v['is_hot']){
				$hot_area[]=array('url'=> url_wap("home#deal_list",$temp_param),'name'=>$v['name']);
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
		
		//=================region_conf==============
		if($type==1){
			$state_list = array(
				1=>array("name"=>"筹资成功"),
				2=>array("name"=>"筹资失败"),
				3=>array("name"=>"融资中"),
				4=>array("name"=>"收益中"),
			);
		}else{
			$state_list = array(
				1=>array("name"=>"筹资成功"),
				2=>array("name"=>"筹资失败"),
				3=>array("name"=>"筹资中"),
			);
		}
		
		
		foreach($state_list as $k=>$v){
			$temp_param = $param;
			$temp_param['state'] = $k;
			$state_list[$k]['url'] = url_wap("home#deal_list",$temp_param);
		}
		$GLOBALS['tmpl']->assign("state_list",$state_list);

		$page_size = DEAL_PAGE_SIZE;
		$step_size = DEAL_STEP_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		$condition = " d.is_delete = 0 and d.is_effect = 1 "; 
		if($r!="")
		{
			if($r=="new")
			{
				$condition.=" and ".NOW_TIME." - d.begin_time < ".(7*24*3600)." and ".NOW_TIME." - d.begin_time > 0 ";  //上线不超过一天
				$GLOBALS['tmpl']->assign("page_title","最新上线");
			}
			elseif($r=="rec")
			{
				$condition.=" and d.is_recommend = 1 ";
				$GLOBALS['tmpl']->assign("page_title","推荐项目");
			}
            elseif($r=="yure")
			{
				$condition.="   and ".NOW_TIME." <  d.begin_time ";   
				$GLOBALS['tmpl']->assign("page_title","正在预热");
			}
			elseif($r=="nend")
			{
				$condition.=" and d.end_time - ".NOW_TIME." < ".(7*24*3600)." and d.end_time - ".NOW_TIME." > 0 ";  //三天就要结束
				$GLOBALS['tmpl']->assign("page_title","即将结束");
			}
			elseif($r=="classic")
			{
				$condition.=" and d.is_classic = 1 ";
				$GLOBALS['tmpl']->assign("page_title","经典项目");
				$GLOBALS['tmpl']->assign("is_classic",true);
			}
			elseif($r=="limit_price")
			{
				$condition.=" and max(d.limit_price) ";
				$GLOBALS['tmpl']->assign("page_title","最高目标金额");
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
		if(count($cate_ids)>0)
		{
			$condition.= " and d.cate_id in (".implode(",",$cate_ids).")";
			$GLOBALS['tmpl']->assign("page_title",$cate_result[$id]['name']);
                        
		}
		if($loc!="")
        {
            $condition.=" and (d.province = '".$loc."' or d.city = '".$loc."') ";
			$GLOBALS['tmpl']->assign("page_title",$loc);            
		}
		if($type!=="")
		{
			$type=intval($type);
            $condition.=" and d.type=$type ";
			$GLOBALS['tmpl']->assign("page_title",$loc);
		}
		
		if($tag!="")
		{
			$unicode_tag = str_to_unicode_string($tag);
			$condition.=" and match(d.tags_match) against('".$unicode_tag."'  IN BOOLEAN MODE) ";
			$GLOBALS['tmpl']->assign("page_title",$tag);
		}
		
		
		if($kw!="")
		{		
			$kws_div = div_str($kw);
			foreach($kws_div as $k=>$item)
			{
				
				$kws[$k] = str_to_unicode_string($item);
			}
			$ukeyword = implode(" ",$kws);
			$condition.=" and (match(d.name_match) against('".$ukeyword."'  IN BOOLEAN MODE) or match(d.tags_match) against('".$ukeyword."'  IN BOOLEAN MODE)  or d.name like '%".$kw."%') ";

			$GLOBALS['tmpl']->assign("page_title",$kw);
		}
		

		$temp_param_price=array_merge($param_new,array('price'=>1));
		$temp_param_focus=array_merge($param_new,array('focus'=>1));
		$temp_param_time=array_merge($param_new,array('time'=>1));
		$temp_param_cp=array_merge($param_new,array('cp'=>1));
		$url_list=array(
 			'price_url'=>url_wap("home#deal_list",$temp_param_price),
			'focus_url'=>url_wap("home#deal_list",$temp_param_focus),
			'time_url'=>url_wap("home#deal_list",$temp_param_time),
			'cp_url'=>url_wap("home#deal_list",$temp_param_cp),
		);
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
			$url_list['price_url']=url_wap("home#deal_list",$param_new);
		 
 		}elseif($focus>0){
			if($focus ==1){
				$orderby.=" d.focus_count desc";
				$param_new['focus']=2;
 			}elseif($focus ==2){
				$orderby.=" d.focus_count asc";
				$param_new['focus']=1;
 			}
			$url_list['focus_url']=url_wap("home#deal_list",$param_new);
 		}elseif($time>0){
			if($time ==1){
				$orderby.=" d.end_time desc";
				$param_new['time']=2;
				
			}elseif($time ==2){
				$orderby.=" d.end_time asc";
				$param_new['time']=1;
 			}
			$url_list['time_url']=url_wap("home#deal_list",$param_new);
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
			$url_list['cp_url']=url_wap("home#deal_list",$param_new);
		}else{
			$orderby ="  d.begin_time desc ";
		}
		$GLOBALS['tmpl']->assign("url_list",$url_list);
		

  		$result = gets_deal_list($limit,$condition,$orderby,$deal_type='deal',$type,$u_id);
 		if($type==1){
 			$GLOBALS['tmpl']->assign("deal_list",$result['list']);
 			
		}else{
 			$GLOBALS['tmpl']->assign("deal_list",$result['list']);
		}
		
		$GLOBALS['tmpl']->assign("deal_count",$result['rs_count']);
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 		
		$p  =  $page->para_show("deals#index",$param);
		$GLOBALS['tmpl']->assign('pages',$p);	
		 if($type==1){
			$GLOBALS['tmpl']->assign('deal_type','gq_type');
		}
		else{
			$GLOBALS['tmpl']->assign('deal_type','product_type');
		}	
		 
 		if($GLOBALS['tmpl']->_var['page_title']==''){
 			$page_title='';
 			if($type==1){
 				foreach($GLOBALS['nav_list'] as $k=>$v){
 					if($v['u_module']=='deals'&&$v['u_action']=='index'&&$v['u_param']=='type=1'){
 						$page_title=$v['name'];
 					}
  				}
  				$page_title=$page_title?$page_title:'股权项目';
 			}else{
 				foreach($GLOBALS['nav_list'] as $k=>$v){
 					if($type ==3){
 						if($v['u_module']=='deals'&&$v['u_action']=='selfless'&&$v['u_param']==''){
	 						$page_title=$v['name'];
	 					}
 					}else{
 						if($v['u_module']=='deals'&&$v['u_action']=='index'&&$v['u_param']==''){
	 						$page_title=$v['name'];
	 					}
 					}
	 					
 							
 				}
				 
				if($type==3){
					$page_title=$page_title?$page_title:'公益项目';
				}else{
					$page_title=$page_title?$page_title:'回报项目';
				}
 				
 			}
			
 			$GLOBALS['tmpl']->assign("page_title",$page_title);
 		}
 		
		$GLOBALS['tmpl']->display("homes_index.html");
	}
	public function get_child($cate_list,$pid){
 			foreach($cate_list as $k=>$v)
			{
				if($v['id'] ==  $pid){
					if($v['pid'] > 0){
						$pid =$this->get_child($cate_list,$v['pid']) ;
						if($pid==$v['pid']){
							return $pid;
						}
					}
					else{
						return $pid;
					}
				}
			}
	}
}
?>
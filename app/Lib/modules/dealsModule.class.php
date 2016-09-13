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
class dealsModule extends BaseModule
{
	public function selfless(){
		$this->index(3);
	}
	public function stock(){
		$this->index(1);
	}
	public function house(){
		if(app_conf("IS_HOUSE") ==1	)
		{
			$this->index(2);
		}
		else
		{
			showErr("房产众筹已关闭",0);
		}
		
	}
	
	public function index($input_type=0)
	{
		if(intval($input_type)){
			$_REQUEST['type'] = intval($input_type);
		}
		if(strim($_REQUEST['type'])==1 && app_conf("INVEST_STATUS")==1)
		{
			showErr(app_conf("GQ_NAME")."已经关闭");
		}
		if(app_conf("INVEST_STATUS")==2 && strim($_REQUEST['type'])==null)
		{
			showErr("产品众筹已经关闭");
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
  			app_redirect(url("deals",$param));
		}
		
		//分类
		if($type != 0)//买房众筹
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
			$cate_result=$nav_cate_array['deal_cate_big'];
			foreach($cate_result as $k=>$v)
			{
				$temp_param = $param;
				$temp_param['id'] = $v['id'];
				$cate_result[$k]['url'] = deal_type_url($temp_param,$type);
			}
			
			if($id >0)
			{
				if($nav_cate_all[$id]['pid'] >0)
				{//当前小分类
					$pid=$nav_cate_all[$id]['pid'];
					$cate_ids['id']=$id;
				}
				else
				{//当前分类是大分类
					$pid=$id;
					if($nav_cate_all[$id]['sub_list'])
					{
						$cate_ids=array_map('array_shift',$nav_cate_all[$id]['sub_list']);
					}
					$cate_ids[]=$id;
				}

				$child_cate_result=$nav_cate_all[$pid]['sub_list'];
				foreach($child_cate_result as $k=>$v)
				{
					$temp_param = $param;
					$temp_param['id'] = $v['id'];
					$child_cate_result[$k]['url'] = deal_type_url($temp_param,$type);
				}
			
			}
			
		}else
		{
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
					$cate_result[$k+1]['url'] = deal_type_url($temp_param,$type);
				}
			}
			
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
							$child_cate_result[$v['id']]['url'] = deal_type_url($temp_param,$type);
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
			/*子分类 end*/
		}
 		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
       $city_list = load_dynamic_cache("INDEX_CITY_LIST"); 
       
       if($type ==1){
	       	if(!$city_list)
			{
				$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal where type=1 and is_effect =1 and is_delete=0 group by province  order by sort asc");
			//	set_dynamic_cache("INDEX_CITY_LIST",$city_list);
			}
			
       }
       if($type ==0||$type ==3||$type ==2){
       		if(!$city_list)
			{
				$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal where type=".$type." and is_effect =1 and is_delete=0 group by province  order by sort asc");
			//	set_dynamic_cache("INDEX_CITY_LIST",$city_list);
			}
       }
        
		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = deal_type_url($temp_param,$type);
		}
        	
		$GLOBALS['tmpl']->assign("city_list",$city_list);
		
		//============region_conf============
		$area_list = $GLOBALS['db']->getAll("select rc.* from ".DB_PREFIX."region_conf as rc where rc.name in (select province from ".DB_PREFIX."deal) or  rc.name in (select city from ".DB_PREFIX."deal) or rc.is_hot=1 ");
		$area=array();
		$hot_area=array();
		foreach($area_list as $k=>$v){
			$temp_param['loc'] = $v['name'];
			$area[strtoupper($v['py'][0])][$v['name']]=array('url'=> deal_type_url($temp_param,$type),'name'=>$v['name']);
			if($v['is_hot']){
				$hot_area[]=array('url'=> deal_type_url($temp_param,$type),'name'=>$v['name']);
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
			$state_list[$k]['url'] = url("deals",$temp_param);
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
 			'price_url'=>deal_type_url($temp_param_price,$type),
			'focus_url'=>deal_type_url($temp_param_focus,$type),
			'time_url'=>deal_type_url($temp_param_time,$type),
			'cp_url'=>deal_type_url($temp_param_cp,$type),
		);
 		//========
 		$orderby = " d.is_top DESC,d.sort asc,d.id desc";
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
			$url_list['price_url']=deal_type_url($param_new,$type);
		 
 		}elseif($focus>0){
			if($focus ==1){
				$orderby.=" d.focus_count desc";
				$param_new['focus']=2;
 			}elseif($focus ==2){
				$orderby.=" d.focus_count asc";
				$param_new['focus']=1;
 			}
			$url_list['focus_url']=deal_type_url($param_new,$type);
 		}elseif($time>0){
			if($time ==1){
				$orderby.=" d.end_time desc";
				$param_new['time']=2;
				
			}elseif($time ==2){
				$orderby.=" d.end_time asc";
				$param_new['time']=1;
 			}
			$url_list['time_url']=deal_type_url($param_new,$type);
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
			$url_list['cp_url']=deal_type_url($param_new,$type);
		}else{
			$orderby =" d.is_top DESC,d.sort asc,d.id desc, d.begin_time desc ";
		}
		$GLOBALS['tmpl']->assign("url_list",$url_list);
		
		//print_r($orderby);exit;
 		
  		$result = get_deal_list($limit,$condition,$orderby);
 		if($type==1){
 			$GLOBALS['tmpl']->assign("deal_list_invest",$result['list']);
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
		elseif($type==2){
			$GLOBALS['tmpl']->assign('deal_type','house_type');
		}
		elseif($type==3){
			$GLOBALS['tmpl']->assign('deal_type','gy_type');
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
					$page_title=$page_title?$page_title:'产品项目';
				}
 				
 			}
			
 			$GLOBALS['tmpl']->assign("page_title",$page_title);
 		}
 		
 		
		$GLOBALS['tmpl']->display("deals_index.html");
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
<?php
require APP_ROOT_PATH.'wap/app/page.php';
class dealsModule{
	 function __construct() {
        
		$GLOBALS['tmpl']->assign('now',NOW_TIME);
     }
	public function selfless(){
		$this->index(3);
	}
	public function house(){
		if(app_conf("IS_HOUSE") ==1)
			$this->index(2);
		else
			showErr("房产众筹已关闭!");
	}
	public function stock(){
		$this->index(1);
	}
	
	public function index($input_type=0)
	{
		$GLOBALS['tmpl']->assign('is_pull_to_refresh',1);
		$GLOBALS['tmpl']->assign('is_loop',1);
		if(intval($input_type)){
			$_REQUEST['type'] = intval($input_type);
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
                
		if(intval($_REQUEST['redirect'])==1)
		{
			$param = array();
			if($r!="")
			{
				$param = array_merge($param,array("r"=>$r));
			}
			if($id>0)
			{
				$param = array_merge($param,array("id"=>$id));
			}	
            if($loc!="")
			{
				$param = array_merge($param,array("loc"=>$loc));
			}
            if($state!="")
			{
				$param = array_merge($param,array("state"=>$state));
			}           
			if($tag!="")
			{
				$param = array_merge($param,array("tag"=>$tag));
			}
			if($kw!="")
			{
				$param = array_merge($param,array("k"=>$kw));
			}
			if($type!="")
			{
				$param = array_merge($param,array("type"=>$type));
			}
			
			app_redirect(url_wap("deals",$param));
		}
		
		$image_list = load_dynamic_cache("INDEX_IMAGE_LIST");
		if($image_list===false)
		{
			$image_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."index_image order by sort asc");
			set_dynamic_cache("INDEX_IMAGE_LIST",$image_list);
		}
		$GLOBALS['tmpl']->assign("image_list",$image_list);
		
		//分类
		if($type != 0)
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
			
			$house_cate_all=$nav_cate_array['deal_cate_all'];
			$cate_result=$nav_cate_array['deal_cate_big'];
			foreach($cate_result as $k=>$v)
			{
				$temp_param = $param;
				$temp_param['id'] = $v['id'];
				$cate_result[$k]['url'] = url_wap("deals",$temp_param);
			}
			
			if($id >0)
			{
				if($house_cate_all[$id]['pid'] >0)
				{//当前小分类
					$pid=$house_cate_all[$id]['pid'];
					$cate_ids['id']=$id;
				}
				else
				{//当前分类是大分类
					$pid=$id;
					if($house_cate_all[$id]['sub_list'])
					{
						$cate_ids=array_map('array_shift',$house_cate_all[$id]['sub_list']);
					}
					$cate_ids[]=$id;
				}

				$child_cate_result=$house_cate_all[$pid]['sub_list'];
				foreach($child_cate_result as $k=>$v)
				{
					$temp_param = $param;
					$temp_param['id'] = $v['id'];
					$child_cate_result[$k]['url'] = url_wap("deals",$temp_param);
				}
				
				$GLOBALS['tmpl']->assign("cate_name", $house_cate_all[$id]['name']);
			
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
	 		foreach($cate_list as $k=>$v){
				if($v['pid'] == 0){
					$temp_param = $param;
					$cate_result[$v['id']]['id'] = $v['id'];
					$cate_result[$v['id']]['name'] = $v['name'];
					$temp_param['id'] = $v['id'];
					$cate_result[$v['id']]['url'] = url_wap("deals",$temp_param);
				}else{
					if($v['pid']>0){
					$temp_param['id'] = $v['id'];
					$cate_result[$v['pid']]['child'][]=array('id'=>$v['id'],'name'=>$v['name'],'url'=>url_wap("deals",$temp_param));
					}
				}
				if($v['id']==$id){
	 				$GLOBALS['tmpl']->assign("cate_name", $v['name']);
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
							$child_cate_result[$v['id']]['url'] = url_wap("deals",$temp_param);
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
		}
		$GLOBALS['tmpl']->assign("cate_list",$cate_result);
		$GLOBALS['tmpl']->assign("child_cate_list",$child_cate_result);
		$GLOBALS['tmpl']->assign("pid",$pid);
		
		/*子分类 end*/
       $city_list = load_dynamic_cache("INDEX_CITY_LIST_".$type); 
       	if(!$city_list)
		{
			$city_list = $GLOBALS['db']->getAll("select province from ".DB_PREFIX."deal where type=".intval($type)." and is_effect =1 group by province  order by sort asc");
		    set_dynamic_cache("INDEX_CITY_LIST_".$type,$city_list);
		}
		
 		foreach($city_list as $k=>$v){
			$temp_param = $param;
			$temp_param['loc'] = $v['province'];
			$city_list[$k]['url'] = url_wap("deals",$temp_param);
			if($v['id']){
				$child= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid=".$v['id']);
				if($child){
					foreach($child as $k1=>$v1){
						$temp_param['loc'] = $v1['name'];
 						$child[$k1]['url']=url_wap("deals",$temp_param);
					}
					$city_list[$k]['child']=$child;
				}
			}
			
		}
		
 		$GLOBALS['tmpl']->assign("city_list",$city_list);
		//=================region_conf==============
		
		$state_list = array(
			1=>array("name"=>"筹资成功"),
			2=>array("name"=>"筹资失败"),
			3=>array("name"=>"筹资中"),
		);
		foreach($state_list as $k=>$v){
			$temp_param = $param;
			$temp_param['state'] = $k;
			$state_list[$k]['url'] = url_wap("deals",$temp_param);
		}
		if($state==0){
			$GLOBALS['tmpl']->assign("state_name","所有项目");
		}else{
			$GLOBALS['tmpl']->assign("state_name",$state_list[$state]['name']);
		}
		$GLOBALS['tmpl']->assign("state_list",$state_list);

		$page_size = PAGE_SIZE;
		$step_size = PAGE_SIZE;
		
		$step = intval($_REQUEST['step']);
		if($step==0)$step = 1;
		$page = intval($_REQUEST['p']);
		if($page==0)$page = 1;		
		$limit = (($page-1)*$page_size+($step-1)*$step_size).",".$step_size	;
		$limit = ($page-1)*$page_size.",".$page_size;
		//$limit = '0,2';
		
		$GLOBALS['tmpl']->assign("current_page",$page);
		
		$condition = " d.is_delete = 0 and d.is_effect = 1 "; 
		if($r!="")
		{if($r=="new")
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
		}
		if(count($cate_ids)>0)
		{
			$condition.= " and d.cate_id in (".implode(",",$cate_ids).")";
			$GLOBALS['tmpl']->assign("page_title",$cate_result[$id]['name']);
                        
		}
		if($loc!="")
         {
            $condition.=" and (d.province = '".$loc."' or city = '".$loc."') ";
			$GLOBALS['tmpl']->assign("page_title",$loc);            
		}
		if($tag!="")
		{
			$unicode_tag = str_to_unicode_string($tag);
			$condition.=" and match(d.tags_match) against('".$unicode_tag."'  IN BOOLEAN MODE) ";
			$GLOBALS['tmpl']->assign("page_title",$tag);
		}
		if($type!=="")
        {
            $condition.=" and type=$type ";
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
		
		
		$result = get_deal_list($limit,$condition);

		foreach($result['list'] as $k=>$v){}
		$GLOBALS['tmpl']->assign("deal_list",$result['list']);
		 
		$GLOBALS['tmpl']->assign("deal_count",intval($result['rs_count']));
		$page = new Page($result['rs_count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);		
	
		$param_ajax=$param;
		$param_ajax['p']=$page+1;
		$page_ajax_url=url_wap("ajax#deals",$param_ajax);
		$GLOBALS['tmpl']->assign("page_ajax_url",$page_ajax_url);
		$GLOBALS['tmpl']->assign("page_count",ceil(intval($result['rs_count'])/$page_size));
		
		
		$GLOBALS['tmpl']->assign("usermessage_url",url_wap("ajax#usermessage",array("id"=>intval($GLOBALS['user_info']['id']))));
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
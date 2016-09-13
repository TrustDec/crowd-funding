<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/shop_lip.php';
require APP_ROOT_PATH.'wap/app/page.php';
require APP_ROOT_PATH.'app/Lib/score_goods_func.php';
class score_mallModule
{
	public function index()
	{	
		$GLOBALS['tmpl']->assign('is_pull_to_refresh',1);
		$param=array();		
		$cates = $param['cates']=intval($_REQUEST['cates']);
		$GLOBALS['tmpl']->assign("cates",$cates);
		
		$integral = $param['integral']= intval($_REQUEST['integral']);
		$GLOBALS['tmpl']->assign("integral",$integral);
		$GLOBALS['tmpl']->assign("integral_str",$integral."_integral");
	
		$sort = $param['sort']= intval($_REQUEST['sort']);   //1.最新  2.热门  3.积分
		$GLOBALS['tmpl']->assign("sort",$sort);
		$GLOBALS['tmpl']->assign("sort_str",$sort."_sort");
		//商品类别
		$cates_cache =load_auto_cache("score_cates");
		$cates_blist=$cates_cache['cates_blist'];
		$cates_sublist=$cates_cache['cates_sublist'];
		//print_r($cates_cache);
		//大分类
		$cate_list=array();
		$param_cate=$param;
		$param_cate['cates']=0;
		$cate_list[0]['id']=0;
		$cate_list[0]['name']="不限";
		$cate_list[0]['url'] = url_wap("score_mall#index",$param_cate);
		foreach($cates_blist as $k=>$v)
		{
			$cate_list[$k]=$v;
			$param_cate['cates']=$v['id'];
			$cate_list[$k]['url']=url_wap("score_mall#index",$param_cate);
			if($cate_list[$k]['sub_list'])
			{	
				foreach($cate_list[$k]['sub_list'] as $kk=>$vv)
				{
					$param_cate['cates']=$vv['id'];
					$cate_list[$k]['sub_list'][$kk]['url']=url_wap("score_mall#index",$param_cate);
				}
			}
		}
		
		//小分类
		$cate_sublist=array();
		$cate_pid=0;
		if($cates >0 && $cates_blist[$cates]['id']==$cates)
		{ 
			$cate_pid=$cates;
			$cate_sublist=$cate_list[$cates]['sub_list'];
		}elseif($cates >0 && $cates_sublist[$cates]['id']==$cates)
		{
			$cate_pid=intval($cates_sublist[$cates]['pid']);
			$cate_sublist=$cate_list[$cate_pid]['sub_list'];
		}
		
		$GLOBALS['tmpl']->assign('cate_list',$cate_list);
		$GLOBALS['tmpl']->assign('cate_sublist',$cate_sublist);
		$GLOBALS['tmpl']->assign('cate_pid',$cate_pid);
		
		//积分范围
		$integral_url = array(
				array(
						"name" => "不限",
				),
				array(
						"name" => "500积分以下",
				),
				array(
						"name" => "500-1000积分",
				),
				array(
						"name" => "1000-3000积分",
				),
				array(
						"name" => "3000-5000积分",
				),
				array(
						"name" => "5000积分以上",
				),
		);
		$param_integral=$param;
		foreach($integral_url as $k=>$v){
			$integral_url[$k]['integral'] = intval($k)."_integral";
			$param_integral['integral'] = $k;
			$integral_url[$k]['url'] = url_wap("score_mall#index",$param_integral);
		}
		$GLOBALS['tmpl']->assign('integral_url',$integral_url);
		
		//排序
		$sort_info = array(
				array(
						"name" => "默认排序",
				),
				array(
						"name" => "最新",
				),
				array(
						"name" => "热门",
				),
				array(
						"name" => "积分",
				),
		);
		$param_sort=$param;
		foreach($sort_info as $k=>$v){
			$sort_info[$k]['sort'] = intval($k)."_sort";
			$param_sort['sort'] = $k;
			$sort_info[$k]['url'] = url_wap("score_mall#index",$param_sort);
		}
		$GLOBALS['tmpl']->assign('sort_info',$sort_info);
	
		
		//输出积分商品列表
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$page_size=10;
		$limit = (($page-1)*$page_size).",".$page_size;

		$condition = " 1=1";
	    if($sort == 1){
			$orderby .= " is_new desc,sort desc";
		}elseif($sort == 2)
		{
			$orderby .= " is_hot desc,sort desc";
		}elseif ($sort == 3)
		{
			$orderby = " score desc,sort desc";
		}
		
		if($cates>0){
			
			if($cate_pid >0 && $cate_pid ==$cates) 
			{
				$cur_cate_ids=$cates_blist[$cates]['cur_cate_ids'];
				$condition .= " AND cate_id in(".implode(',',$cur_cate_ids).") ";
				
			}else{
				$condition .= " AND cate_id = ".$cates."";
			}
			
		}
		
		if($integral==0){
			$condition .= "";
		}elseif ($integral==1){
			$condition .= " AND score  <= 500";
		}elseif ($integral==2){
			$condition .= " AND score  between 500 and 1000";
		}elseif ($integral==3){
			$condition .= " AND score  between 1000 and 3000";
		}elseif ($integral==4){
			$condition .= " AND score  between 3000 and 5000";
		}else{
			$condition .= " AND score  >= 5000";
		}
		
		$result = get_goods_list($limit,$condition,$orderby);
		$GLOBALS['tmpl']->assign("goods_list",$result['list']);
		
		$page_pram = "";
		foreach($param as $k=>$v){
			$page_pram .="&".$k."=".$v;
		}
		$page = new Page($result['count'],$page_size,$page_pram);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign("param",$param);

		
		$GLOBALS['tmpl']->display("score/score_mall_index.html");
	}
	
}
?>
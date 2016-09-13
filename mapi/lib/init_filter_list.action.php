<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 初始化筛选列表
 */
class init_filter_list
{
	public function index()
	{
		$article_cates_list = $this->getArticleCatesList ();
		$state_list = $this->getStateList ();
		$equity_state_list = $this->getEquityStateList ();
		$cate_list = $this->getCateList ();
		$cate_list_all = $this->getCateList (1);
		$data = responseSuccessInfo ( "", 0, "初始化筛选列表" );
		$data ['article_cates_list'] = $article_cates_list;
		$data ['state_list'] = $state_list;
		$data ['equity_state_list'] = $equity_state_list;
		$data ['cate_list'] = $cate_list;
		$data ['cate_list_all'] = $cate_list_all;
		$data ['location_list_0'] = get_location_list(1);//产品众筹区域
		$data ['location_list_1'] = get_location_list(2);//股权众筹区域
		output ( $data );
	}
	// 文章分类栏
	public function getArticleCatesList()
	{
		$article_cates_list = $GLOBALS ['db']->getAllCached ( "SELECT id,title from " . DB_PREFIX . "article_cate where is_effect=1 and is_delete=0 and type_id=0" );
		return $article_cates_list;
	}
	// 普通众筹状态
	public function getStateList()
	{
		$state_list = array (
				0 => array (
						"name" => "所有项目" 
				),
				1 => array (
						"name" => "筹资成功" 
				),
				2 => array (
						"name" => "筹资失败" 
				),
				3 => array (
						"name" => "筹资中" 
				) 
		);
		return $state_list;
	}
	// 股权众筹状态
	public function getEquityStateList()
	{
		$equity_list = array (
				0 => array (
						"name" => "所有项目",
						"state" => "0" 
				),
				1 => array (
						"name" => "筹资成功",
						"state" => "1" 
				),
				2 => array (
						"name" => "筹资失败",
						"state" => "2" 
				),
				3 => array (
						
						"name" => "融资中",
						"state" => "3" 
				) 
		);
		return $equity_list;
	}
	// 众筹分类列表
	public function getCateList($is_child)
	{
		$cate_list = load_dynamic_cache ( "INDEX_CATE_LIST" );
		if (! $cate_list)
		{
			$cate_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_cate where pid=0 order by sort asc" );
			set_dynamic_cache ( "INDEX_CATE_LIST", $cate_list );
		}
		
		if($is_child ==1)
		{
			$cate_child_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_cate where pid<>0 order by sort asc" );
			
			foreach($cate_child_list  as $k=>$v)
			{
				$cate_child[$v['pid']]['child'][]=$v;
			}
		}
		
		$cate_new_list = array ();
		$cate_new_list [0] ['id'] = '0';
		$cate_new_list [0] ['name'] = "全部分类";
		if($is_child ==1)
		{
			$cate_new_list [0] ['child'] = array();
			$cate_new_list [0] ['child'][0]['id']=0;
			$cate_new_list [0] ['child'][0]['name']="全部";
		}
			
		foreach ( $cate_list as $k => $v )
		{
			$cate_new_list [$k + 1]['id'] = $v ['id'];
			$cate_new_list [$k + 1]['name'] = $v ['name'];
			if($is_child ==1 && $cate_child)
			{
				$cate_new_list [$k+1]['child'] = array();
				$cate_new_list [$k+1]['child'][0]['id'] = $v['id'];
				$cate_new_list [$k+1]['child'][0]['name'] = "全部";
				foreach($cate_child[$v['id']]['child'] as $kk=>$vv)
				{
					$cate_new_list [$k+1]['child'][$kk+1]['id']=$vv['id'];
					$cate_new_list [$k+1]['child'][$kk+1]['name']=$vv['name'];
				}
				
				
			}
		}
		return $cate_new_list;
	}
}

?>
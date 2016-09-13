<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
/**
 * 获取积分商城商品列表
 */
function get_goods_list($limit="", $where='',$orderby = '')
{
	$count_sql = "select count(*) from ".DB_PREFIX."goods where is_effect =1  ";
	$sql = "select * from ".DB_PREFIX."goods where is_effect =1 ";
	
	if($where != '')
	{
		$sql.=" and ".$where;
		$count_sql.=" and ".$where;
	}

	if($orderby=='')
		$sql.=" order by is_recommend desc,sort desc,id desc";
	else
		$sql.=" order by ".$orderby;

	if($limit!=""){
		$sql .=" limit ".$limit;
	}
	
	$goods_count = $GLOBALS['db']->getOne($count_sql);
	if($goods_count > 0){
		$goods = $GLOBALS['db']->getAll($sql);
		foreach($goods as $k=>$v)
		{
			$goods[$k]['url']=url("score_good_show#index",array('id'=>$v['id']));
			$goods[$k]['total_buy']=$v['buy_number']+$v['invented_number'];
		}
	}
	else{
		$goods = array();
	}
	return array('list'=>$goods,'count'=>$goods_count);
}

/**
 * 查询某件商品信息及用户可购买数量
 * @param 商品编号 $id
 */
function get_goods_info($id)
{
	$id = intval($id);
	$sql = "SELECT g.*,gc.name as cate_name from ".DB_PREFIX."goods as g left join ".DB_PREFIX."goods_cate as gc on g.cate_id = gc.id where g.id = ".$id." and g.is_effect=1" ;

	$goods = $GLOBALS['db']->getRow($sql);
	
	$goods['total_buy'] = $goods['invented_number'] + $goods['buy_number'];
	$stock=$goods['max_bought'] - $goods['buy_number'];
	$goods['stock'] = $stock?$stock:0;
	
	return $goods;
}

/**
 * 获得属性
 * */
 function get_goods_attr($goods_type_id,$goods_id)
 {
 	//规格属性选择
 	$goods_type_id=intval($goods_type_id);
 	$goods_id=intval($goods_id);
	$good_attr = $GLOBALS['db']->getAll("select id,name from ".DB_PREFIX."goods_type_attr where goods_type_id = ".$goods_type_id);
	$good_attr_date=array();
	foreach($good_attr as $k=>$v)
	{
		$good_attr_date[$v['id']] = $v;
		$attr_list = $GLOBALS['db']->getAll("select id,name,score from ".DB_PREFIX."goods_attr where goods_id = ".$goods_id." and goods_type_attr_id = ".$v['id']);
		if(!$attr_list)
			unset($good_attr_date[$v['id']]);
		else{
			foreach($attr_list as $kk=>$vv)
			{
				$good_attr_date[$v['id']]['attr_list'][$vv['id']]=$vv;
			}
		}
		
	}
	$return['good_attr'] = $good_attr_date;
	
	//开始输出库存json
	$attr_stock_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr_stock where goods_id = ".$goods_id,false);
	$attr_stock_data = array();
	foreach($attr_stock_list as $row)
	{
		$row['attr_cfg'] = unserialize($row['attr_cfg']);
		$attr_stock_data[$row['attr_key']] = $row;
	}
	//print_r($attr_stock_data);
	$return['attr_stock_data'] = $attr_stock_data;
	$return['goods_attr_stock_json'] = json_encode($attr_stock_data);
	
	return $return;
 }
 
/**
 * 判断库存
 * */
function check_attr_stock($goods,$number,$attr)
{
	$return=array(
		'status'=>0,
		'info'=>''
	);
	if($goods['goods_type_id'] >0 )
	{
		$good_attr=$goods['good_attr'];
		if($good_attr)
		{
			if(!$attr){
				$return['info']='请选择属性!';
				return $return;
			}
			//判断是否全部属性已选择
			$no_str='';
			$view_attr=array();
			$attr_score=0;
			foreach($good_attr as $k=>$v)
			{
				$cur_sub_ids=array_map("array_shift",$good_attr[$k]['attr_list']);
				$intersect=array_intersect($attr,$cur_sub_ids);
				sort($intersect);
				if(!$intersect)
					$no_str .="请选择".$v['name']."<br />";
				else{
					$view_attr[$v['id']]['id']=$v['id'];
					$view_attr[$v['id']]['name']=$v['name'];
					$view_attr[$v['id']]['attr_val']=$good_attr[$k]['attr_list'][$intersect[0]]['name'];
					$view_attr[$v['id']]['attr_id']=$good_attr[$k]['attr_list'][$intersect[0]]['id'];
					$attr_score +=$v['score'];
				}
			}
			if($no_str !='')
			{
				$return['info']=$no_str;
				return $return;
			}
			
			//判断属性库存
			sort($attr);
			$buy_attr_str=explode('_',$attr);
			if($goods['attr_stock_data'])
			{
				$goods_attr_stock=$goods['attr_stock_data'];
				
				$cur_attr_stock=$goods_attr_stock[$buy_attr_str];
				if($cur_attr_stock)
				{
					$cur_attr_stock['stock_cfg']-$cur_attr_stock['buy_count'];
					if($number > intval($cur_attr_stock['stock_cfg']-$cur_attr_stock['buy_count']))
					{
						$return['info']="该商品属性库存不足，无法兑换，请重新选择";
						return $return;
					}
				}
			}
			
		}else{
			//判断正常库存
			if($goods['max_bought'] >0 )
			{
				if( $number > intval($goods['max_bought']-$goods['buy_number']))
				{
					$return['info']="该商品属性库存不足，无法兑换，请重新选择";
					return $return;
				}
			}
		}
		
	}
	elseif($goods['max_bought'] >0 )
	{	// 判断正常库存
		if( $number > intval($goods['max_bought']-$goods['buy_number']))
		{
			$return['info']="该商品属性库存不足，无法兑换，请重新选择";
			return $return;
		}
	}
	
	$return['view_attr']=$view_attr;
	$return['attr_score']=$attr_score;
	
	$return['info']="该商品属性库存不足，无法兑换，请重新选择";
	$return['status']=1;
	return $return;
}
?>
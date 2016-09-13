<?php
// +----------------------------------------------------------------------
// | easethink 方维借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class GoodsAction extends CommonAction{
	public function index()
	{	
		$name=$param['name']=strim($_REQUEST['name']);
		$cate_id=$param['cate_id']=intval($_REQUEST['cate_id']);
		$this->assign("param",$param);
		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if($name != '')
			$map['name']=array("like","%".$name."%");
			
		if($cate_id >0)
		{
			require_once APP_ROOT_PATH."system/utils/child.php";
			$child = new Child("goods_cate");
			$cate_ids = $child->getChildIds($cate_id);
			$cate_ids[] = $cate_id;
			$map['cate_id'] = array("in",$cate_ids);
		}else
		{
			unset($map['cate_id']);
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = D ("Goods");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$list = $this->get("list");
		foreach($list as $k=>$v)
		{
			$list[$k]['cate_name'] = M("Goods_cate")->where("id=".$list[$k]['cate_id'])->getField("name");  
			if ($list[$k]['is_delivery']){$list[$k]['is_delivery_format'] = "是"; }else{$list[$k]['is_delivery_format'] = "否";}
			if ($list[$k]['is_hot']){$list[$k]['is_hot_format'] = "是"; }else{$list[$k]['is_hot_format'] = "否";}
			if ($list[$k]['is_new']){$list[$k]['is_new_format'] = "是"; }else{$list[$k]['is_new_format'] = "否";}
			if ($list[$k]['is_recommend']){$list[$k]['is_recommend_format'] = "是"; }else{$list[$k]['is_recommend_format'] = "否";}
		}
		//dump($result);exit;
		$this->assign("list",$list);
		
		//商品分类
		$cate_tree = M("GoodsCate")->where(' is_delete= 0 and is_effect=1 ')->findAll();
		$cate_tree = D("GoodsCate")->toFormatTree($cate_tree,'name');
		$this->assign("cate_tree",$cate_tree);
	
		$this->display ();
		return;
	}
	
	public function goods_cate()
	{
		$condition['is_delete'] = 0;
		$condition['pid'] = 0;
		$this->assign("default_map",$condition);
	
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
			$map = array_merge($map,$this->get("default_map"));
	
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$list = $this->get("list");
		$result = array();
		$row = 0;
		foreach($list as $k=>$v)
		{
			$v['level'] = -1;
			$v['name'] = $v['name'];
			$result[$row] = $v;
			$row++;
			$sub_cate = M(MODULE_NAME)->where(array("id"=>array("in",D(MODULE_NAME)->getChildIds($v['id'])),'is_delete'=>0))->findAll();
			$sub_cate = D(MODULE_NAME)->toFormatTree($sub_cate,'name');
			foreach($sub_cate as $kk=>$vv)
			{
				$vv['name']	=	$vv['title_show'];
				$result[$row] = $vv;
				$row++;
			}
		}
		//dump($result);exit;
		$this->assign("list",$result);
		$this->display ();
		return;
	}
	

	
	public function edit()
	{
		$id = intval($_REQUEST ['id']);
		
		$condition['id'] = $id;
		$vo = M("Goods")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		//商品分类
		$cate_tree = M("GoodsCate")->where(' is_delete= 0 and is_effect=1 ')->findAll();
		$cate_tree = D("GoodsCate")->toFormatTree($cate_tree,'name');
		$this->assign("cate_tree",$cate_tree);
		
		//商品类型
		$goods_type_list = M("GoodsType")->where(' is_effect=1 ')->findAll();
		$this->assign ( 'goods_type_list', $goods_type_list );
	
		//输出规格库存的配置
		$attr_stock = M("GoodsAttrStock")->where("goods_id=".intval($vo['id']))->order("id asc")->findAll();
	
		$attr_cfg_json = "{";
		$attr_stock_json = "{";
		
		foreach($attr_stock as $k=>$v)
		{
			$attr_cfg_json.=$k.":"."{";
			$attr_stock_json.=$k.":"."{";
			foreach($v as $key=>$vvv)
			{
				if($key!='attr_cfg')
					$attr_stock_json.="\"".$key."\":"."\"".$vvv."\",";
			}
			$attr_stock_json = substr($attr_stock_json,0,-1);
			$attr_stock_json.="},";
				
			$attr_cfg_data = unserialize($v['attr_cfg']);
			foreach($attr_cfg_data as $attr_id=>$vv)
			{
				$attr_cfg_json.=$attr_id.":"."\"".$vv."\",";
			}
			$attr_cfg_json = substr($attr_cfg_json,0,-1);
			$attr_cfg_json.="},";
		}
		if($attr_stock)
		{
			$attr_cfg_json = substr($attr_cfg_json,0,-1);
			$attr_stock_json = substr($attr_stock_json,0,-1);
		}
		
		$attr_cfg_json .= "}";
		$attr_stock_json .= "}";
		
		$this->assign("attr_cfg_json",$attr_cfg_json);
		$this->assign("attr_stock_json",$attr_stock_json);
		//goods_type_attr
		$this->display ();
		
	}
	
	public function attr_html()
	{
		$goods_type_id = intval($_REQUEST['goods_type_id']);
		$goods_id = intval($_REQUEST['goods_id']);
		
		if( $goods_id>0 && M("Goods")->where("id=".$goods_id)->getField("goods_type_id")==$goods_type_id)
		{
			
			$goods_type_attr = M()->query("select a.name as attr_name,a.is_checked as is_checked,a.score,b.*
					from ".conf("DB_PREFIX")."goods_attr as a
					left join ".conf("DB_PREFIX")."goods_type_attr as b on a.goods_type_attr_id = b.id
					where a.goods_id=".$goods_id." order by b.id asc");
			
			$goods_type_attr_id = 0;
			if($goods_type_attr)
			{
				
				foreach($goods_type_attr as $k=>$v)
				{
					$goods_type_attr[$k]['attr_list'] = preg_split("/[ ,]/i",$v['preset_value']);
					if($goods_type_attr_id!=$v['id'])
					{
						$goods_type_attr[$k]['is_first'] = 1;
					}
					else
					{
						$goods_type_attr[$k]['is_first'] = 0;
					}
					$goods_type_attr_id = $v['id'];
				}	
			}
			else 
			{
				$goods_type_attr = M("GoodsTypeAttr")->where("goods_type_id=".$goods_type_id)->findAll();
				foreach($goods_type_attr as $k=>$v)
				{
					$goods_type_attr[$k]['is_first'] = 1;
				}
			}
		}
		else
		{
			$goods_type_attr = M("GoodsTypeAttr")->where("goods_type_id=".$goods_type_id)->findAll();
			foreach($goods_type_attr as $k=>$v)
			{
				$goods_type_attr[$k]['attr_list'] = preg_split("/[ ,]/i",$v['preset_value']);
				$goods_type_attr[$k]['is_first'] = 1;
			}	
		}
		
		$this->assign("goods_type_attr",$goods_type_attr);
		$this->display();
	}
	
	
	public function update()
	{
		$data = M("Goods")->create ();
		$data['name']=strim($data['name']);
		$data['sub_name']=strim($data['sub_name']);
		
		$goods_info=M("Goods")->where("id=".intval($data['id']))->find();
		if(!$goods_info)
			$this->error("未找到商品",0);
	
		// 更新数据
		$list=M("Goods")->save ($data);
		if (false !== $list) {
			M("GoodsAttr")->where("goods_id=".$data['id'])->delete();
			M("GoodsAttrStock")->where("goods_id=".$data['id'])->delete();
			
			//有属性时
			if($data['goods_type_id'] > 0){
				$goods_attr = $_REQUEST['goods_attr'];
				$goods_attr_score = $_REQUEST['goods_attr_score'];
				$goods_attr_stock_hd = $_REQUEST['goods_attr_stock_hd'];
				
				foreach($goods_attr as $goods_type_attr_id=>$arr)
				{
					foreach($arr as $k=>$v)
					{
						if($v!='')
						{
							$deal_attr_item['goods_id'] = $data['id'];
							$deal_attr_item['goods_type_attr_id'] = $goods_type_attr_id;
							$deal_attr_item['name'] = $v;
							$deal_attr_item['score'] = $goods_attr_score[$goods_type_attr_id][$k];
							$deal_attr_item['is_checked'] = intval($goods_attr_stock_hd[$goods_type_attr_id][$k]);
							M("GoodsAttr")->add($deal_attr_item);
						}
					}
				}
				
				//开始创建属性库存
				$stock_cfg = $_REQUEST['stock_cfg_num'];
				$attr_cfg = $_REQUEST['stock_attr'];
				$attr_str = $_REQUEST['stock_cfg'];
				foreach($stock_cfg as $row=>$v)
				{
					$stock_data = array();
					$stock_data['goods_id'] = $data['id'];
					$stock_data['stock_cfg'] = $v;
					$stock_data['attr_str'] = $attr_str[$row];
					$attr_cfg_data = array();
					foreach($attr_cfg as $attr_id=>$cfg)
					{
						$attr_cfg_data[$attr_id] = $cfg[$row];
					}
					$stock_data['attr_cfg'] = serialize($attr_cfg_data);
					
					M("GoodsAttrStock")->add($stock_data);
				}
				
				syn_attr_stock_key($data['id']);//同步库存索引的key
			}
			
			//成功提示
			save_log($data['name'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($data['name'].L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0);
		}
		
	
	}
	

	public function delete(){
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			$goods_info=M("Goods")->where ( $condition )->findAll();
			$info='';
			foreach($goods_info as $k=>$v)
			{	
				if($v['is_delivery'] ==1  && M("GoodsOrder")->where("goods_id=".$v['id']." and delivery_status = 0")->count()>0)
				{
					$this->error ('编号：'.$v['id'].',"'.$v['name'].'"有未发货的订单，不能删除');
				}
				$info .= $v['name'].",";
			}
			//删除的验证
			$list = M("Goods")->where ( $condition )->delete();
			if ($list!==false) {
				save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
				$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
			} else {
				save_log($info.l("FOREVER_DELETE_FAILED"),0);
				$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
			}
		} else {
			$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function add() {
		
		//商品分类
		$cate_tree = M("GoodsCate")->where(' is_delete= 0 and is_effect=1 ')->findAll();
		$cate_tree = D("GoodsCate")->toFormatTree($cate_tree,'name');
		$this->assign("cate_tree",$cate_tree);
		
		//商品类型
		$goods_type_list = M("GoodsType")->where(' is_effect=1 ')->findAll();
		$this->assign ( 'goods_type_list', $goods_type_list );
		
		//排序
		$this->assign("new_sort", M("Goods")->max("sort")+1);
		
		$this->display ();
	}
	
	public function insert()
	{
		
		$data = M("Goods")->create ();
		
		// 更新数据
		
		$list = M("Goods")->add ($data); 
		$goods_id = $list;
		
		if (false !== $list){
		
			if($data['goods_type_id'] > 0){
				//开始处理属性
				$deal_attr = $_REQUEST['goods_attr'];
				$goods_attr_score = $_REQUEST['goods_attr_score'];	
				$goods_attr_stock_hd = $_REQUEST['goods_attr_stock_hd'];			
				
				foreach($deal_attr as $goods_type_attr_id=>$arr)
				{
					foreach($arr as $k=>$v)
					{
						if($v!='')
						{
							$deal_attr_item['goods_id'] = $list;
							$deal_attr_item['goods_type_attr_id'] = $goods_type_attr_id;
							$deal_attr_item['name'] = $v;
							$deal_attr_item['score'] = $goods_attr_score[$goods_type_attr_id][$k];
							$deal_attr_item['is_checked'] = intval($goods_attr_stock_hd[$goods_type_attr_id][$k]);
							M("GoodsAttr")->add($deal_attr_item);
						}
					}
				}
				
				//开始创建属性库存
				$stock_cfg = $_REQUEST['stock_cfg_num']; //库存数量
				$attr_cfg = $_REQUEST['stock_attr']; 	//库存属性
				$attr_str = $_REQUEST['stock_cfg'];
				foreach($stock_cfg as $row=>$v)
				{
					$stock_data = array();
					$stock_data['goods_id'] = $list;
					$stock_data['stock_cfg'] = $v;
					$stock_data['attr_str'] = $attr_str[$row];
					$attr_cfg_data = array();
					foreach($attr_cfg as $attr_id=>$cfg)
					{
						$attr_cfg_data[$attr_id] = $cfg[$row];
					}
					$stock_data['attr_cfg'] = serialize($attr_cfg_data);
					M("GoodsAttrStock")->add($stock_data);
				}
				
				M("GoodsAttr")->add ($data);
			}
			syn_attr_stock_key($data['id']);//同步库存索引的key
			
			//错误提示
			$dbErr = M()->getDbError();
			$this->success ("添加成功");
		}else{
			$this->error(L("INSERT_FAILED"));
		}
	}
	
	public function set_effect()
	{	
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M("Goods")->where("id=".$id)->getField("name");
		$c_is_effect = M("Goods")->where("id=".$id)->getField("is_effect");  //当前状态
		
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M("Goods")->where("id =".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);

		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1);
	}
	
	
}
?>
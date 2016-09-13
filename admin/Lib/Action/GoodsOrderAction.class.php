<?php
// +----------------------------------------------------------------------
// | easethink 方维借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class GoodsOrderAction extends CommonAction{
	public function index()
	{	
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		
		if(!isset($_REQUEST['order_status']))
			$_REQUEST['order_status']=-1;
		if(!isset($_REQUEST['delivery_status']))
			$_REQUEST['delivery_status']=-1;
		
		$order_sn=$param['order_sn']=strim($_REQUEST['order_sn']);
		$goods_id=$param['goods_id']=intval($_REQUEST['goods_id']);
		$goods_name=$param['goods_name']=strim($_REQUEST['goods_name']);
		$user_name=$param['user_name']=strim($_REQUEST['user_name']);
		$order_status=$param['order_status']=intval($_REQUEST['order_status']);
		$delivery_status=$param['delivery_status']=intval($_REQUEST['delivery_status']);
		$begin_time=$param['begin_time']=strim($_REQUEST['begin_time']);
		$end_time=$param['end_time']=strim($_REQUEST['end_time']);
		
		if($_REQUEST['goods_id'] =='')
			$param['goods_id']='';
			
		$this->assign('param',$param);
		
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
			
		if($order_sn !='')
		{
			$map['order_sn'] = $order_sn;
		}
		if($goods_id >0)
		{
			$map['goods_id'] = $goods_id;
		}
		if($goods_name !='')
		{
			$map['goods_name'] = array('like','%'.strim($goods_name).'%');
			
		}
		if($user_name !='')
		{
			$map['user_name'] = array('like','%'.strim($user_name).'%');
			
		}
		
		if(isset($_REQUEST['order_status']) && $order_status >=0)
		{
			$map['order_status'] = $order_status;
		}
		
		if(isset($_REQUEST['delivery_status']) && $delivery_status >=0)
		{
			$map['delivery_status'] = $delivery_status;
		}
		$begin_time_span=to_timespan($begin_time);
		$end_time_span=to_timespan($end_time);
		if($begin_time !='' && $end_time == '')
		{
			$map['_string']="ex_time >".$begin_time_span."";
		}
		elseif($begin_time !='' && $end_time != '')
		{
			$map['_string']="ex_time >=".$begin_time_span." and ex_time <=".$end_time_span."";
		}
		elseif($begin_time =='' && $end_time != '')
		{
			$map['_string']="ex_time <=".$begin_time_span."";
		}
	
		if($order_status == -1)
			unset($map['order_status']);
		if($delivery_status == -1)
			unset($map['delivery_status']);
			
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = D ("GoodsOrder");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$list = $this->get("list");
		
		$result = array();
		$row = 0;
		foreach($list as $k=>$v)
		{
			if($list[$k]['is_delivery'] == 0)
			{	$list[$k]['is_delivery_format'] = "否";}
			else{
				$list[$k]['is_delivery_format'] = "是";
			}
			if($list[$k]['order_status'] == 0){
				$list[$k]['order_status_format'] = "未兑换";
			}elseif($list[$k]['order_status'] == 1){
				$list[$k]['order_status_format'] = "已兑换";
			}elseif($list[$k]['order_status'] == 2){
				$list[$k]['order_status_format'] = "无库存，积分已退回";
			}elseif($list[$k]['order_status'] == 3){
				$list[$k]['order_status_format'] = "已退积分";
			}elseif($list[$k]['order_status'] == 4){
				$list[$k]['order_status_format'] = "已取消";
			}elseif($list[$k]['order_status'] == 5){
				$list[$k]['order_status_format'] = "已无效";
			}
			
			if($list[$k]['delivery_status'] == 0)
				$list[$k]['delivery_status_format'] = "未发货";
			elseif($list[$k]['delivery_status'] == 1)
				$list[$k]['delivery_status_format'] = "已发货";
			else
				$list[$k]['delivery_status_format'] = "无需发货";
				
			$list[$k]['user_name'] = $user_info =  M("User")->where("id=".$v['user_id']." ")->getField("user_name");
		}
		
		$this->assign("list",$list);
		
		$this->display ();
		return;
	}
	
	
	public function view_order()
	{
		$id = intval($_REQUEST ['id']);
		$list = M("GoodsOrder")->where("id =".$id)->find();
	
		if($list['is_delivery'] == 0)
		{	$list['is_delivery_format'] = "否";}
		else{
			$list['is_delivery_format'] = "是";
		}
		if($list['order_status'] == 0){
			$list['order_status_format'] = "未兑换";
		}elseif($list['order_status'] == 1){
			$list['order_status_format'] = "已兑换";
		}elseif($list['order_status'] == 2){
			$list['order_status_format'] = "无库存，积分已退回";
		}elseif($list['order_status'] == 3){
			$list['order_status_format'] = "已退积分";
		}elseif($list['order_status'] == 4){
			$list['order_status_format'] = "已取消";
		}elseif($list['order_status'] == 5){
			$lis['order_status_format'] = "已无效";
		}
		if($list['delivery_status'] == 0)
			$list['delivery_status_format'] = "未发货";
		elseif($list['delivery_status'] == 1)
			$list['delivery_status_format'] = "已发货";
		else
			$list['delivery_status_format'] = "无需发货";
		$list['format_create_time'] = to_date( $list['create_time'],"Y-m-d H:i:s");
		$list['format_ex_time'] = to_date( $list['ex_time'],"Y-m-d H:i:s");
		$list['format_delivery_time'] = to_date( $list['delivery_time'],"Y-m-d H:i:s");
		$list['user_name'] =$user_info =  M("User")->where("id=".$list['user_id']." ")->getField("user_name");
		
		$list['attr_format'] = unserialize($list['attr']);
		foreach($list['attr_format'] as $kk=>$vv){
			$attr_str .= $GLOBALS['db']->getOne("select name from ".DB_PREFIX."goods_type_attr where id =".$kk );
			$attr_str .=":";
			$attr_str .= $GLOBALS['db']->getOne("select name from ".DB_PREFIX."goods_attr where id =".$vv );
			$attr_str .="  ";
		}
		$list['attr_str'] = $attr_str;
		
		$this->assign ( 'list', $list );
		
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $list['delivery_province'])
				$region_lv2[$k]['selected']=1;
		}
		$this->assign("region_lv2",$region_lv2);
		
		$region_pid = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."region_conf where region_level = 2 and name='".$list['delivery_province']."'");
		$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 3 and pid=".intval($region_pid)." order by py asc");  //二级地址
		$this->assign("region_lv3",$region_lv3);
		
		$this->display ();
	}
	
	public function do_delivery()
	{
		
		
//		if($data['delivery_sn']=="" && $data['is_delivery'] == 1){
//			$this->error("请填写快递单号",0,);
//		}
//		if($data['delivery_addr']=="" && $data['is_delivery'] == 1){
//			$this->error("配送地址不能为空",0);
//		}
		$data['id']= intval($_REQUEST ['id']);
		$list = M("GoodsOrder")->where("id =".$data['id'])->find();
		
		if($list['delivery_status'] == 1)
			$this->error("货已经发了",0);
		if($list['delivery_status'] == 2)
			$this->error("无需发货",0);
		if($list['order_status'] != 1)
			$this->error("发货失败了",0);
			
		$data['delivery_sn']= strim($_REQUEST ['delivery_sn']);
		$data['delivery_express']= strim($_REQUEST ['delivery_express']);
		$data['delivery_addr']= strim($_REQUEST ['delivery_addr']);
		$data['delivery_name']= strim($_REQUEST ['delivery_name']);
		$data['delivery_tel']= strim($_REQUEST ['delivery_tel']);
		$data['delivery_zip']= strim($_REQUEST ['delivery_zip']);
		$data['delivery_province']= strim($_REQUEST ['province']);
		$data['delivery_city']= strim($_REQUEST ['city']);
		$now_time=get_gmtime();
		$data['delivery_status'] = 1;
		$data['delivery_time'] = $now_time;
		$data['delivery_date'] =  to_date($now_time,"Y-m-d");
		
		
		
		// 更新数据
		$list=M("GoodsOrder")->save($data);
		//echo M("GoodsOrder")->getLastSql();exit;
		if (false !== $list) {
			//成功提示
			save_log("发货成功",1);
			$this->success("发货成功");
		} else {
			//错误提示
			$this->error("发货失败");
		}
	}
	
	public function cancel_order()
	{
		
		$order_id = intval($_REQUEST['id']);
		$order_info = M("GoodsOrder")->getById($order_id);
		$this->assign("order_info",$order_info);
		$this->display();

	}
	//取消订单
	public function cancel_order_do()
	{
		$order_id = intval($_REQUEST ['id']);
		$order_info = M("GoodsOrder")->getById($order_id);
		if(!$order_info)
			$this->error("未找到订单",0);
		
		if($order_info['delivery_status'] ==1)
		{
			$this->error("订单已发货，不能取消",0);
		}
		
		if($order_info['order_status'] >=2)
			$this->error("订单已取消了",0);
			
		$o_order_status=$order_info['order_status'];
		$data['id'] = $order_id;
		$data['admin_memo']=strim($_REQUEST['admin_memo']);
		if($order_info['order_status'] ==0)
		{
			$data['order_status'] = 4;
		}
		elseif($order_info['order_status'] ==1)
		{
			$data['order_status'] = 3;
		}
		
		// 更新数据
		$list=M("GoodsOrder")->save($data);
		if($list)
		{
			if($o_order_status ==1)
			{
				$msg = $order_info['goods_name']."积分兑换订单取消，退还积分";
				require_once APP_ROOT_PATH."system/libs/user.php";
				modify_account(array('score'=>$order_info['pay_score']),$order_info['user_id'],$msg);
				$GLOBALS['db']->query("update ".DB_PREFIX."goods set buy_number= case when buy_number < 1 then 0 else buy_number - ".$order_info['number']." end  where id=".intval($order_info['goods_id']));
				if($order_info['attr'] != '')
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."goods_attr_stock set buy_count = case when buy_count < 1 then 0 else buy_count - ".$order_info['number']." end where attr_str ='".$order_info['attr']."' and goods_id=".intval($order_info['goods_id']));
				}
			}
			//成功提示
			save_log("积分兑换订单(sn:".$order_info['order_sn'].")取消成功",1);
			$this->success("取消成功");
		}else
		{
			$this->error("取消失败",0);
		}
	}
	
	public function del_order()
	{
		//删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = intval($_REQUEST ['id']);
		if (isset ( $id )) {
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			//删除的验证
			
			$rel_data = M("GoodsOrder")->where($condition)->findAll();
			foreach($rel_data as $data)
			{
				if($data['order_status'] ==1)
					$this->error ("有已兑换的订单，不能删除",$ajax);
					             
				$info[] = $data['order_sn'];
				
			}
			if($info) $info = implode(",",$info);
			$list = M("GoodsOrder")->where ( $condition )->delete();
				
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
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
		$page_size=10;
		$limit = (($page - 1)*$page_size.",".$page_size);
	
		//定义条件
		$map='';
		
		
		$list = M("GoodsOrder")
		->where($map)
		->limit($limit)->findAll();
		
		foreach($list as $k=>$v)
		{
			if($list[$k]['is_delivery'] == 0)
			{	$list[$k]['is_delivery_format'] = "否";}
			else{
				$list[$k]['is_delivery_format'] = "是";
			}
			if($list[$k]['order_status'] == 0){
				$list[$k]['order_status_format'] = "未兑换";
			}elseif($list[$k]['order_status'] == 1){
				$list[$k]['order_status_format'] = "已兑换";
			}elseif($list[$k]['order_status'] == 2){
				$list[$k]['order_status_format'] = "无库存，积分已退回";
			}elseif($list[$k]['order_status'] == 3){
				$list[$k]['order_status_format'] = "已退积分";
			}elseif($list[$k]['order_status'] == 4){
				$list[$k]['order_status_format'] = "已取消";
			}elseif($list[$k]['order_status'] == 5){
				$list[$k]['order_status_format'] = "已无效";
			}
			
			if($list[$k]['delivery_status'] == 0)
				$list[$k]['delivery_status_format'] = "未发货";
			elseif($list[$k]['delivery_status'] == 1)
				$list[$k]['delivery_status_format'] = "已发货";
			else
				$list[$k]['delivery_status_format'] = "无需发货";
		}
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
				
			$order_list = array('id'=>'""','order_sn'=>'""','user_name'=>'""','goods_name'=>'""','number'=>'""','total_score'=>'""','ex_time'=>'""','delivery_time'=>'""','order_status_format'=>'""','delivery_status_format'=>'""');
			if($page == 1)
				$content = iconv("utf-8","gbk","编号,订单号,会员名,商品名称,数量,所需积分,兑换时间,发货时间,订单状态,发货状态");
	
			if($page==1)
				$content = $content . "\n";
	
			foreach($list as $k=>$v)
			{
				$order_list = array();
				$order_list['id'] = iconv('utf-8','gbk','"' . $v['id'] . '"');
				$order_list['order_sn'] = iconv('utf-8','gbk','"' . $v['order_sn'] . '"');
				$order_list['user_name'] = iconv('utf-8','gbk','"' . $v['user_name'] . '"');
				$order_list['goods_name'] = iconv('utf-8','gbk','"' . $v['goods_name'] . '"');
				$order_list['number'] = iconv('utf-8','gbk','"' . $v['number'] . '"');
				$order_list['total_score'] = iconv('utf-8','gbk','"' . $v['total_score'] . '"');
				$order_list['ex_time'] = iconv('utf-8','gbk','"' . to_date($v['ex_time']) . '"');
				$order_list['delivery_time'] = iconv('utf-8','gbk','"' . to_date($v['delivery_time']) . '"');
				$order_list['order_status_format'] = iconv('utf-8','gbk','"' . $v['order_status_format'] . '"');
				$order_list['delivery_status_format'] = iconv('utf-8','gbk','"' . $v['delivery_status_format'] . '"');
	
				
				$content .= implode(",", $order_list) . "\n";
			}
				
				
			header("Content-Disposition: attachment; filename=order_list.csv");
			echo $content;
		}
		else
		{
			if($page==1)
				$this->error(L("NO_RESULT"));
		}
	
	}
	
}
?>
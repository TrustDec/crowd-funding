<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealOrderAction extends CommonAction{
	public function index()
	{
		if($_REQUEST['type']=='NULL'){
			unset($_REQUEST['type']);
		}
		if($_REQUEST['order_status']=='NULL'){
			unset($_REQUEST['order_status']);
		}
		if($_REQUEST['is_refund']=='NULL'){
			unset($_REQUEST['is_refund']);
		}
		$order_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where repay_make_time=0 and repay_time>0  ");
		foreach($order_list as $k=>$v){
				$left_date=intval(app_conf("REPAY_MAKE"))?7:intval(app_conf("REPAY_MAKE"));
				$repay_make_date=$v['repay_time']+$left_date*24*3600;
				if($repay_make_date<=get_gmtime()){
 					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$v['id'] );
				}
 		}
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		if(trim($_REQUEST['deal_name'])!='')
		{
			$map['deal_name'] = array('like','%'.trim($_REQUEST['deal_name']).'%');
		}
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$offline_pay=M('Payment')->where('class_name = "Offlinepay"')->find();
		if($offline_pay)
		{
			$list = $this->get("list");
			foreach($list as $k=>$v)
			{
				if($v['payment_id']==$offline_pay['id'])
				{
					$list[$k]['online_pay']=0;
					$list[$k]['offlinepay_money']=format_price($v['total_price']);
				}else
				{
					$list[$k]['offlinepay_money']=0;
				}
			}
			$this->assign("list",$list);
		}
		
		$this->display ();
		return;
	}
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = "[".$data['deal_name'].$data['deal_price']."支持人:".$data['user_name']."状态:".$data['order_status']."]";						
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();		
						
				if ($list!==false) {
					$deal_id=$GLOBALS['db']->getOne("select deal_id from  ".DB_PREFIX."deal_order where id=$id");
					syn_deal($deal_id);
					
					$list = M("DealOrderLottery")->where ( "order_id in (".explode(',',$id).")" )->delete();
					//删除支持记录
					$investor_condition=array ('order_id' => array ('in', explode ( ',', $id ) ) );
					$list = M("InvestmentList")->where ($investor_condition)->delete();
					
 					
					save_log($info."成功删除",1);
					$this->success ("成功删除",$ajax);
				} else {
					save_log($info."删除出错",0);					
					$this->error ("删除出错",$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function view()
	{
		$order_info = M("DealOrder")->getById(intval($_REQUEST['id']));
		if(!$order_info)$this->error("没有该项目的支持");
		
		
		$payment_notice_list = M("PaymentNotice")->where("order_id=".$order_info['id']." and is_paid = 1")->findAll();
		$this->assign("payment_notice_list",$payment_notice_list);
		
		//抽奖订单
		if($order_info['type'] ==3)
		{
			$lottery_return=get_order_lottery($order_info['id']);
			$order_info['lottery_list']=$lottery_return['lottery_list'];
			$order_info['lottery_luckyer_list']=$lottery_return['lottery_luckyer_list'];
		}
		
		$offline_pay=M('Payment')->where('class_name = "Offlinepay"')->find();
		if($offline_pay && $order_info['payment_id'] == $offline_pay['id'])
		{
			
			$order_info['offlinepay_money']=format_price($order_info['total_price']);
			$order_info['online_pay']=0;
		}
		else
		{
			$order_info['offlinepay_money']=0;
		}
		$deal_list = M("Deal")->getById($order_info['deal_id']);
		$this->assign("order_info",$order_info);
		$this->assign("deal_list",$deal_list);
		$this->assign("offline_pay",$offline_pay);
		$this->assign("back_list",u("DealOrder/get_pay_list",array("deal_id"=>$order_info['deal_id'])));		
		$this->display();
	}
	
	public function refund()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->getById($id);
		if($order_info)
		{
			$count_pay_log = M("DealPayLog")->where("deal_id=".intval($order_info['deal_id']))->count();
			if($count_pay_log >0)
				$this->error("筹款已发，不能退款");
			
			//已是抽奖订单，不能删除
			if($order_info['type'] ==3 && $order_info['is_winner']==1)
			{
				$this->error("本订单已被抽为幸运订单了，不能退款");
			}
				
			if($order_info['is_refund']==0 && $order_info['order_status'] ==3)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$id." and is_refund = 0 and order_status=3");
				if($GLOBALS['db']->affected_rows()>0)
				{
					require_once APP_ROOT_PATH."system/libs/user.php";									
					modify_account(array("money"=>($order_info['online_pay']+$order_info['credit_pay'])),$order_info['user_id'],$order_info['deal_name']."退款");
					//退回积分
					if($order_info['score'] >0)
	 				{
						$log_info=$order_info['deal_name']."退款，退回".$order_info['score']."积分";
						modify_account(array("score"=>$order_info['score']),$order_info['user_id'],$log_info);
	 				}
					
					//扣掉购买时送的积分和信用值
					$sp_multiple=unserialize($order_info['sp_multiple']);
					if($sp_multiple['score_multiple']>0)
					{
						$score=intval($order_info['total_price']*$sp_multiple['score_multiple']);
						$log_info=$order_info['deal_name']."退款，扣掉".$score."积分";
						modify_account(array("score"=>"-".$score),$order_info['user_id'],$log_info);
					}	
					if($sp_multiple['point_multiple']>0)
					{
						$point=intval($order_info['total_price']*$sp_multiple['point_multiple']);
						$log_info=$order_info['deal_name']."退款，扣掉".$point."信用值";
						modify_account(array("point"=>"-".$point),$order_info['user_id'],$log_info);
					}
					
					//抽奖订单 把抽奖号标为已退款
					if($order_info['type'] ==3)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=3 where order_id=".$id."");
					}
					
					syn_deal($order_info['deal_id']);
					
					$deal_item['support_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$order_info['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($order_info['deal_item_id'])));
					$deal_item['support_amount'] = floatval($GLOBALS['db']->getOne("select sum(deal_price) from ".DB_PREFIX."deal_order where deal_id = ".$order_info['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($order_info['deal_item_id'])));
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item", $deal_item, $mode = 'UPDATE', "id=".intval($order_info['deal_item_id']), $querymode = 'SILENT');	
				}
				$this->success("成功退款到会员余额");
			}elseif($order_info['is_refund']==0 && $order_info['order_status'] ==0)
			{
				$this->error("订单未付款");
			}
			else
			{
				$this->error("已经退款");
			}
		}
		else
		{
			$this->error("没有该项目的支持");
		}
	}
	
	public function incharge()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->getById($id);
		if($order_info)
		{
			if($order_info['order_status']==0)
			{
				if($order_info['payment_id'] >0)
				{
					$payment_info = M("Payment")->getById($order_info['payment_id']);
				}
				
				$result = pay_order($order_info['id']);				
				$money = $result['money'];
				$payment_notice['create_time'] = get_gmtime();
				$payment_notice['user_id'] = $order_info['user_id'];
				$payment_notice['money'] = $money;
				$payment_notice['bank_id'] = "";
				$payment_notice['order_id'] = $order_info['id'];
				$payment_notice['memo'] = "管理员收款";
				$payment_notice['deal_id'] = $order_info['deal_id'];
				$payment_notice['deal_item_id'] = $order_info['deal_item_id'];
				$payment_notice['deal_name'] = $order_info['deal_name'];
				
				if($payment_info['class_name'] =='Offlinepay')//线下支持
				{
					$payment_notice['payment_id'] = $order_info['payment_id'];
				}else
				{
					$payment_notice['payment_id'] = 0;
				}
				
				do{
					$payment_notice['notice_sn'] = to_date(get_gmtime(),"Ymd").rand(100,999);
					$GLOBALS['db']->autoExecute(DB_PREFIX."payment_notice",$payment_notice,"INSERT","","SILENT");
					$notice_id = $GLOBALS['db']->insert_id();
				}while($notice_id==0);
				
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice['notice_sn'],"");	
				$this->success("收款完成");
			}
			else
			{
				$this->error("已经付过款");
			}
		}
		else
		{
			$this->error("没有该项目的支持");
		}
	}
	
	//导出电子表
	public function export_csv($page = 1)
	{
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$where = " 1=1 ";
		//定义条件
		if(trim($_REQUEST['deal_id'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_id = ".intval($_REQUEST['deal_id']);
		}
		if(trim($_REQUEST['deal_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.deal_name = ".intval($_REQUEST['deal_name']);
		}
		if(trim($_REQUEST['user_name'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.user_name = ".intval($_REQUEST['user_name']);
		}
		if(trim($_REQUEST['type'])!='')
		{
			$where.= " and ".DB_PREFIX."deal_order.type = ".intval($_REQUEST['type']);
		}
		$list = M("DealOrder")
				->where($where)
				->field(DB_PREFIX.'deal_order.*')
				->limit($limit)->findAll();
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'user_name'=>'""', 'deal_name'=>'""', 'total_price'=>'""','zip'=>'""','mobile'=>'""','province'=>'""','consignee'=>'""','order_status'=>'""','id'=>'""','create_time'=>'""','pay_time'=>'""','city'=>'""','address'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","参与者,项目名称,应付总额,邮编,手机,配送地址,收货人,支付状态,订单号,下单时间,支付时间");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$order_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$order_value['deal_name'] = '"' . iconv('utf-8','gbk',$v['deal_name']) . '"';
				$order_value['total_price'] = '"' . iconv('utf-8','gbk',$v['total_price']) . '"';
				$order_value['zip'] = '"' . iconv('utf-8','gbk',$v['zip']) . '"';
				$order_value['mobile'] = '"' . iconv('utf-8','gbk',$v['mobile']) . '"';
				$order_value['province'] = '"' . iconv('utf-8','gbk',$v['province']) . '"'. iconv('utf-8','gbk',$v['city']) . iconv('utf-8','gbk',$v['address']);
				$order_value['consignee'] = '"' . iconv('utf-8','gbk',$v['consignee']). '"' ;
				if($v['order_status']==0){
					$v['order_status']='未支付';
				}elseif ($v['order_status']==1){
					$v['order_status']='已支付(过期)';
				}elseif ($v['order_status']==2){
					$v['order_status']='已支付(无库存)';
				}elseif ($v['order_status']==3){
					$v['order_status']='支付成功';
				}
				$order_value['order_status'] = '"' . iconv('utf-8','gbk',$v['order_status']). '"' ;
				$order_value['id'] = '"' . iconv('utf-8','gbk',$v['id']). '"' ;
				$order_value['create_time'] = '"' . iconv('utf-8','gbk',to_date($v['create_time'])). '"' ;
				$order_value['pay_time'] = '"' . iconv('utf-8','gbk',to_date($v['pay_time'])). '"' ;
				
				$content .= implode(",", $order_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=order_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
	
	
	public function get_pay_list()
	{
		$deal_id=$_REQUEST['deal_id'];
		if($deal_id>0){
			$deal_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=$deal_id ");
			$this->assign("deal_info",$deal_info);
			$order_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where repay_make_time=0 and repay_time>0 and deal_id=$deal_id  ");
			
		}else{
			$order_list = $GLOBALS['db']->getAll("select dorder.* from ".DB_PREFIX."deal_order as dorder left join ".DB_PREFIX."deal as d on d.id=dorder.deal_id where d.type=1 and dorder.repay_make_time=0 and dorder.repay_time>0 ");
			echo "select dorder.* from ".DB_PREFIX."deal_order as dorder left join ".DB_PREFIX."deal as d on d.id=dorder.deal_id where d.type=1 and dorder.repay_make_time=0 and dorder.repay_time>0 ";exit;
		}
		foreach($order_list as $k=>$v){
				$left_date=intval(app_conf("REPAY_MAKE"))?7:intval(app_conf("REPAY_MAKE"));
				$repay_make_date=$v['repay_time']+$left_date*24*3600;
				if($repay_make_date<=get_gmtime()){
 					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set repay_make_time =  ".get_gmtime()." where id = ".$v['id'] );
				}
 		}
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数		
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		if($map['order_status']=='NULL'){
			unset($map['order_status']);
		}
		if(trim($_REQUEST['deal_name'])!='')
		{
			$map['deal_name'] = array('like','%'.trim($_REQUEST['deal_name']).'%');
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$offline_pay=M('Payment')->where('class_name = "Offlinepay"')->find();
		if($offline_pay)
		{
			$list = $this->get("list");
			foreach($list as $k=>$v)
			{
				if($v['payment_id']==$offline_pay['id'])
				{
					$list[$k]['online_pay']=0;
					$list[$k]['offlinepay_money']=format_price($v['total_price']);
				}else
				{
					$list[$k]['offlinepay_money']=0;
				}
			}
			$this->assign("list",$list);
		}
		
		$this->display ();
		return;
	}
	
}
?>
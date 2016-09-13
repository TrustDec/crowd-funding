<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class StockTransferAction extends CommonAction
{
	//股权转让
	public function index()
	{
		if(trim($_REQUEST['title'])!='')
		{
			$condition['deal_name'] = array('like','%'.trim($_REQUEST['title']).'%');
		}
		$condition['status'] = 0;
		
		$this->assign("default_map",$condition);
		parent::index();		
	}
	
	//待审批
	public function submit_index()
	{
		$map['status'] = 0;
		if(trim($_REQUEST['deal_name'])!='')
		{
			$map['deal_name'] = array('like','%'.trim($_REQUEST['deal_name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);	
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'stock_transfer.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
	
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
	
		$this->display ();
	}
	//审批界面
	public function edit_investor()
	{
		$id = intval($_REQUEST ['id']);
		$vo = M("StockTransfer")->where("id=".$id)->find();
		$this->assign ( 'vo', $vo );
		
		$this->display ();
	}
	
	//审批
	public function shelves()
	{
	
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		$data['status'] = intval($_REQUEST ['status']);
		
		//print_r($data['status']);exit();
		if ($data['status']==1) {
			$now_time=get_gmtime();
			$day = intval($_REQUEST ['day']);
			$data['is_edit'] = 0;
			$data['begin_time'] = $now_time;
			$data['end_time'] = $now_time+$day*86400;
			
			/*
			//锁定investment_list1 已转移至发起时候
			 
			$id= intval($_REQUEST ['id']);
			$StockTransfer = M("StockTransfer")->where("id=".$id)->find();
				
			$idata = M("InvestmentList")->where("id=".$StockTransfer['invest_id'])->find();
			$idata['money']=intval($idata['money'])-intval($StockTransfer['stock_value']);
			$idata['num']=intval($idata['num'])-intval($StockTransfer['num']);
			
			if(intval($idata['num'])<0){
				//
			}
			
			M("InvestmentList")->save ($idata);
				
			//添加$investment_list2
			$investment_list['type'] = 4;
			$investment_list['money'] = $StockTransfer['stock_value'];
			$investment_list['status'] = 1;
			$investment_list['user_id'] = $StockTransfer['user_id'];
			$investment_list['deal_id'] = $idata['deal_id'];
			$investment_list['create_time'] = $now_time;
			$investment_list['investor_money_status'] = 1;
			$investment_list['order_id'] = 0;
			$investment_list['num'] = $StockTransfer['num'];
			$investment_list['stock_transfer_value'] = $StockTransfer['price'];
				
			$Investment=M("InvestmentList")->add ($investment_list);
				
			$data['invest_id_2']=$Investment;
			*/
		}elseif($data['status']==2){
			$data['is_edit'] = 1;			
		}		
		
		$list=M(MODULE_NAME)->save ($data);
	
		if (false !== $list) {
			//成功提示						
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			clear_auto_cache("contract_cache");
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	//已审批项目
	public function submit_stock_transfer()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$map['status'] = 1;
		if(trim($_REQUEST['deal_name'])!='')
		{
			$map['deal_name'] = array('like','%'.trim($_REQUEST['deal_name']).'%');
		}
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'stock_transfer.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
	
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
	
		$this->display ();
	}
	
	//股权转让取消
	public function stock_transfer_cancel()
	{
		require_once APP_ROOT_PATH."system/libs/user.php";
		B('FilterString');
		//锁定交易
		$id= intval($_REQUEST['id']);
		
		$StockTransfer = M("StockTransfer")->where("id=".$id)->find();
		
		
		if(intval($StockTransfer['status'])!=1) {
			//错误提示 不是通过状态
			
			$this->error(L("STOCK_TRANSFER_CANCEL_ERROR_0"),0,$log_info.L("STOCK_TRANSFER_CANCEL_ERROR_0"));
			
		}elseif (intval($StockTransfer['is_success'])==1){
			//错误提示 ，已经成功
			$this->error(L("STOCK_TRANSFER_CANCEL_ERROR_1"),0,$log_info.L("STOCK_TRANSFER_CANCEL_ERROR_1"));
			
		}else {
			
			
			if (intval($StockTransfer['deal_order_id'])>0) {
				//乙方已付款
				$deal_order = M("DealOrder")->where("id=".$StockTransfer['deal_order_id'])->find();
				//退款
				$deal_order['is_success']=0;
				$deal_order['share_status']=0;
				
				$deal_order['order_status'] =1;
				$deal_order['is_refund'] =1;
				modify_account(array("money"=>($deal_order['online_pay']+$deal_order['credit_pay'])),$deal_order['user_id'],$deal_order['deal_name']."交易取消，转存入会员帐户");
				//退回积分
				if($deal_order['score'] >0)
				{
					$log_score=$deal_order['deal_name']."交易取消，退回".$deal_order['score']."积分";
					modify_account(array("score"=>$deal_order['score']),$deal_order['user_id'],$log_score);
				}
							
				M("DealOrder")->save ($deal_order);
				
					
			}else{
				//乙方未付款
				
					
			}
			
			//股份还原
			if($StockTransfer['invest_id_2']>0){
				//处理数据
					
				$investment_list_2 = M("InvestmentList")->where("id=".$StockTransfer['invest_id_2'])->find();
					
				$investment_list_1 = M("InvestmentList")->where("id=".$StockTransfer['invest_id'])->find();

			
				if(intval($investment_list_2['type'])==4){
			
					$investment_list_1['money'] = $investment_list_1['money'] + $investment_list_2['money'];
					$investment_list_1['num'] = $investment_list_1['num'] + $investment_list_2['num'];
			
					M(InvestmentList)->save ($investment_list_1);
						
					$investment_list_2['type']=6;
					M(InvestmentList)->save ($investment_list_2);
						
						
					//同步项目状态
					syn_deal($investment_list_1['deal_id']);
				}
					
			}
				
			$StockTransfer['status']=3;
			$StockTransfer['is_success']=0;
			$list=M(MODULE_NAME)->save ($StockTransfer);
			
			if (false !== $list) {
				//成功提示
			
				save_log($log_info.L("UPDATE_SUCCESS"),1);
				clear_auto_cache("contract_cache");
				$this->success(L("UPDATE_SUCCESS"));
			} else {
				//错误提示
				save_log($log_info.L("UPDATE_FAILED"),0);
				$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
			}
			
		}
		
		
	}
}
?>
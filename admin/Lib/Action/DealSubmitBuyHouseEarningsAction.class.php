<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealSubmitBuyHouseEarningsAction extends CommonAction{

	public function submit_buy_house_earnings(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select
					ub.id as 收益编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 收益年度,
					ub.number as 收益期数,
					ub.money as 收益金额,
					ub.return_cycle as 收益周期,
					ub.average_annualized_return as 平均年收益率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0
					and d.is_effect = 1 and d.is_success = 1
					and  ub.status = 0 and ub.type = 2 and d.type = 2 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";
		}


		$model = D('Deal');
		$voList = $this->_Sql_list($model, $sql_str);
		$this->display();
	}
//项目分期详细
	public function edit_user_bonus()
	{
		$id = intval($_REQUEST['id']);
		$condition['id'] = $id;
		$vo = M(UserBonus)->where($condition)->find();
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );

		$user_bonus_list = M("UserBonusList")->where("user_bonus_id=".$vo['id'])->order("id asc")->findAll();
		$this->assign("user_bonus_list",$user_bonus_list);

		$this->display();
	}
	public function update_user_bonus()
	{
		B('FilterString');
		$data = M("UserBonus")->create();
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);

		$list=M("UserBonus")->save ($data);
		if (false !== $list) {
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			$this->error(L("UPDATE_FAILED"));
		}
	}
	public function del_user_bonus()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			$condition1 = array ('user_bonus_id' => array ('in', explode ( ',', $id ) ) );
			$rel_data = M("UserBonus")->where($condition)->findAll();
			foreach($rel_data as $data)
			{
				$deal_id = $data['deal_id'];
				$info[] = format_price($data['price']);
			}
			if($info) $info = implode(",",$info);
			$info = "项目ID".$deal_id.":".$info;

			$list = M("UserBonus")->where ( $condition )->delete();
			$list1 = M("UserBonusList")->where ( $condition1 )->delete();
			if ($list!==false && $list1!==false) {

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
	public function submit_user_bonus(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select
					ub.id as 分红编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 分红年度,
					ub.number as 分红期数,
					ub.money as 分红金额,
					ub.average_monthly_returns as 平均月回报率,
					ub.average_annualized_return as 平均年回报率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0
					and d.is_effect = 1 and d.is_success = 1
					and (d.stock_type = 1 or (d.stock_type = 3 and  d.share_fee_descripe != '')) and  ub.status = 0 and ub.type = 0 and d.type = 1 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";
		}


		$model = D();
		$voList = $this->_Sql_list($model, $sql_str);
		//print_r($voList);exit;
		$this->display();
	}
}
?>
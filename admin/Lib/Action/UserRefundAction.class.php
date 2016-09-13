<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class UserRefundAction extends CommonAction{

	/**
	 * 提现审核记录
     */
	public function index()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		$map['_string'] = 'is_pay = 0 or is_pay = 2 ';
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
		$this->display ();
		return;
	}

	public function refund_allow(){
		$id=intval($_REQUEST['id']);
		$status=intval($_REQUEST['status']);
		$refund_data=M("UserRefund")->getById($id);
		$info=array();
		if($status){
			$info['do']='允许';
		}else{
			$info['do']='不允许';
		}
		$this->assign("info",$info);
		$this->assign("refund_data",$refund_data);
		$this->assign("status",$status);
		$this->display ();
	}
	public function refund_go_allow(){
		$id=intval($_REQUEST['id']);
		$status=intval($_REQUEST['status']);
		$refund_data = M("UserRefund")->getById($id);
		if($refund_data)
		{
			if($refund_data['is_pay']==1)
			{
				$this->error("已经允许提现");
			}

			$reply = strim($_REQUEST['reply']);
			if($status==1){
				$refund_data['is_pay'] = 1;
				$info="允许操作成功";

			}else{
				$refund_data['is_pay'] = 2;
				$info="未允许操作成功";
			}
			$refund_data['reply']=$reply;
			$bank_data = M("UserBank")->getById($refund_data['user_bank_id']);
			if(is_investment_pass()&&$bank_data['type']==1){
				if(check_refund_type($refund_data['user_bank_id'],$refund_data['user_id'])){
					M("UserRefund")->save($refund_data);
					$GLOBALS['msg']->manage_msg('MSG_MONEY_CARRY_RESULT',$refund_data['user_id'],$refund_data);
					$this->success($info);
				}else{
					$this->error("用户申请的银行卡，未绑定易宝投资通，请重新提交!");
				}
			}else{
				M("UserRefund")->save($refund_data);
				$GLOBALS['msg']->manage_msg('MSG_MONEY_CARRY_RESULT',$refund_data['user_id'],$refund_data);
				$this->success($info);
			}
		}else{
			$this->error("没有提现数据");
		}
		
	}

	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				$list = M(MODULE_NAME)->where ( $condition )->delete();		
				
				foreach($rel_data as $data)
				{
					$info[] = "[id:".$data['id'].",money:".$data['money']."]";						
				}
				if($info) $info = implode(",",$info);
				
				if ($list!==false) {
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
	//导出电子表
	public function export_csv($page = 1)
	{
		$pagesize = 10;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
		
		$where = " 1=1 ";
		//定义条件
		if(trim($_REQUEST['user_id'])!='')
		{
			$where.= " and ur.user_id = ".intval($_REQUEST['user_id']);
		}
		if(trim($_REQUEST['is_pay'])!='')
		{
			$where.= " and ur.is_pay = ".intval($_REQUEST['is_pay']);
		}
		$sql ="select u.user_name as user_name,u.id as user_id,u.email as email,u.ex_real_name as ex_real_name,u.ex_account_bank as ex_account_bank,u.ex_account_info as ex_account_info,u.ex_contact as ex_contact,u.mobile as mobile, ur.money as money,ur.user_bank_id from ".DB_PREFIX."user as u LEFT JOIN ".DB_PREFIX."user_refund as ur on ur.user_id = u.id where ".$where." limit ".$limit;
		$list=$GLOBALS['db']->getAll($sql);
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$refund_value = array( 'user_name'=>'""', 'email'=>'""', 'bank_info'=>'""','mobile'=>'""','money'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","会员名,邮箱,银行账户,手机,提现金额");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$refund_value['user_name'] = '"' . iconv('utf-8','gbk',$list[$k]['user_name']) . '"';
				$refundr_value['email'] = '"' . iconv('utf-8','gbk',$list[$k]['email']) . '"';
//				$refund_value['ex_real_name'] = '"' . iconv('utf-8','gbk',$list[$k]['ex_real_name']) . '"';
//				$refund_value['ex_account_bank'] = '"' . iconv('utf-8','gbk',$list[$k]['ex_account_bank']) . '"';
//				$refund_value['ex_account_info'] = '"' . iconv('utf-8','gbk',$list[$k]['ex_account_info']) . '"';
//				$refund_value['ex_contact'] = '"' . iconv('utf-8','gbk',$list[$k]['ex_contact']) . '"';
				$refund_value['bank_info'] =  '"' . iconv('utf-8','gbk',get_carray_info($list[$k]['user_bank_id'],$list[$k]['user_id'])) . '"';
				$refund_value['mobile'] = '"' . iconv('utf-8','gbk',$list[$k]['mobile']) . '"';
				$refund_value['money'] = '"' . iconv('utf-8','gbk',$list[$k]['money']) . '"';
				$content .= implode(",", $refund_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=refund_list.csv");
	    	echo $content ;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
}
?>
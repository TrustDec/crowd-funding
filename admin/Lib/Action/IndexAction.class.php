<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class IndexAction extends AuthAction{
	//首页
    public function index(){
		$this->display();
    }
    

    //框架头
	public function top()
	{
		//$navs = M("RoleNav")->where("is_effect=1 and is_delete=0")->order("sort asc")->findAll();
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));

		$role_id = intval($adm_session['role_id']);
 		$navs= get_admin_nav($role_id,$adm_session['adm_name']);
		$this->assign("navs",$navs);

		$this->assign("admin",$adm_session);
		$this->display();
	}
	//框架左侧
	public function left()
	{

		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$adm_id = intval($adm_session['adm_id']);
		$role_id = intval($adm_session['role_id']);
		$navs= get_admin_nav($role_id,$adm_session['adm_name']);
		$nav_key = strim($_REQUEST['key']);
		
 		$nav_group = $navs[$nav_key]['groups'];

 		$this->assign("menus",$nav_group);
		$this->display();
	}
	//默认框架主区域
	public function main()
	{
 		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$adm_id = intval($adm_session['adm_id']);
		$role_id = intval($adm_session['role_id']);
		$navs= get_admin_nav($role_id,$adm_session['adm_name']);

		$this->assign("navs",$navs);
		$info=array();
		//注册待验证
		$info['user_num']=$GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."user where is_effect=0 ");
		$info['project_none_num']=$GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."deal where is_effect in(0,2) and is_delete=0 ");
		$info['user_invest_num']=$GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."user where (is_investor=1 or is_investor=2) and investor_status!=1 ");
 		//待审核提醒
		$info['user_refund_num']=$GLOBALS['db']->getOne("select count(*)  from ".DB_PREFIX."user_refund where is_pay=0 ");
 		//支付成功
 		$info['deal_order']=$GLOBALS['db']->getRow("select count(*) as num,sum(total_price) as money  from ".DB_PREFIX."deal_order  where  order_status=3 ");
 	 	$info['deal_order']['money']=floatval($info['deal_order']['money']);
 		$this->assign("info",$info);
 		$this->display();
	}	
	//底部
	public function footer()
	{
		$this->display();
	}
	
	//修改管理员密码
	public function change_password()
	{
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$this->assign("adm_data",$adm_session);
		$this->display();
	}
	public function do_change_password()
	{
		$adm_id = intval($_REQUEST['adm_id']);
		if(!check_empty($_REQUEST['adm_password']))
		{
			$this->error(L("ADM_PASSWORD_EMPTY_TIP"));
		}
		if(!check_empty($_REQUEST['adm_new_password']))
		{
			$this->error(L("ADM_NEW_PASSWORD_EMPTY_TIP"));
		}
		if($_REQUEST['adm_confirm_password']!=$_REQUEST['adm_new_password'])
		{
			$this->error(L("ADM_NEW_PASSWORD_NOT_MATCH_TIP"));
		}		
		if(M("Admin")->where("id=".$adm_id)->getField("adm_password")!=md5($_REQUEST['adm_password']))
		{
			$this->error(L("ADM_PASSWORD_ERROR"));
		}
		M("Admin")->where("id=".$adm_id)->setField("adm_password",md5($_REQUEST['adm_new_password']));
		save_log(M("Admin")->where("id=".$adm_id)->getField("adm_name").L("CHANGE_SUCCESS"),1);
		$this->success(L("CHANGE_SUCCESS"));
		
		
	}
	
	public function reset_sending()
	{
		$field = trim($_REQUEST['field']);
		if($field=='DEAL_MSG_LOCK'||$field=='PROMOTE_MSG_LOCK'||$field=='APNS_MSG_LOCK')
		{
			M("Conf")->where("name='".$field."'")->setField("value",'0');
			$this->success(L("RESET_SUCCESS"),1);
		}
		else
		{
			$this->error(L("INVALID_OPERATION"),1);
		}
	}
	/*
	 * 网站数据统计
	 */
	public function statistics(){		
		$user_count=M("user")->count();		
		$is_investor=M("user")->where("is_investor=1")->count();	//投资人	
		$user_level=M("user")->where("user_level=0")->count(); 		//普通用户
		$institution=M("user")->where("is_investor=2")->count();	//投资机构			
		$is_identify=M("user")->where("is_investor=1 and identify_positive_image is not null")->count();	//认证			
		
		$money_diy=floatval(M("user_log")->where("log_admin_id=0 and money>0")->sum(money)); //会员充值		
		$money_diy=$money_diy?$money_diy:0;
		
		$money_admin=floatval(M("user_log")->where("log_admin_id=1 and money>0")->sum(money)); //管理员充值
		$user_refund=floatval(M("user_refund")->sum(money)); //会员提现	
		$user_refund=$user_refund?$user_refund:0;
		
		$user_money=M("user")->sum(money);		 //用户总金额
		$user_support=M("deal")->sum(support_amount); //项目总金额
		$web_amount=$user_money+$user_support;//网站余额
		
		$three_rechange=M("yeepay_recharge")->where("code=1")->sum(amount); //第三方充值	
		$three_yeepay_withdraw=M("yeepay_withdraw")->where("code=1")->sum(amount);  	//第三方提现  		
		$three_balance=$three_rechange-$three_yeepay_withdraw;//第三方余额
		
		$no_effect=M("deal")->where("is_effect=0")->count(); //未审核
		$is_effect=M("deal")->where("is_effect=1")->count(); //审核
		$is_effect_forbid=M("deal")->where("is_effect=2")->count(); //未通过
		
		$is_success=M("deal")->where("is_success=1")->count(); //成功
		$no_success=M("deal")->where("is_success=0")->count(); //成功
		
		$stock_is_success=M("deal")->where("is_success=1 and type=1")->count(); //股权成功
		$stock_no_success=M("deal")->where("is_success=0 and type=1")->count(); //成功
		
		$now_time=time();
     	$no_begain=M("deal")->where("begin_time>$now_time")->count();   	//预热项目
     	
        $success_amount=floatval(M("deal")->where("is_success=1")->sum(support_amount));	 //成功筹款
		
       //已发放筹款    
        $deal_pay_log= floatval($GLOBALS['db']->getOne("SELECT sum(p.money) from ".DB_PREFIX."deal as d RIGHT JOIN ".DB_PREFIX."deal_pay_log as p on d.id=p.deal_id "));
     
        //待发放筹款
        $deal_pay_support_amount= $GLOBALS['db']->getOne("SELECT sum(d.support_amount) from ".DB_PREFIX."deal as d RIGHT JOIN ".DB_PREFIX."deal_pay_log as p on d.id=p.deal_id ");
        $no_deal_pay_log=floatval($success_amount-$deal_pay_support_amount);   	
        
     	//网站收益佣金
         $commission=  floatval($GLOBALS['db']->getOne("select sum(if(pay_radio >0,support_amount*pay_radio,support_amount*".app_conf("PAY_RADIO").")) from ".DB_PREFIX."deal  where is_effect = 1 and is_delete=0 "));
        
        //诚意金 
        $margator_money= floatval($GLOBALS['db']->getOne("select sum((select sum(mortgage_money) from ".DB_PREFIX."user))"));
     
        $this->assign("margator_money",$margator_money);
        $this->assign("commission",$commission);
        $this->assign("no_deal_pay_log",$no_deal_pay_log);
        $this->assign("deal_pay_log",$deal_pay_log);
        $this->assign("success_amount",$success_amount);
		$this->assign("stock_is_success",$stock_is_success);   // 融资成功
		$this->assign("stock_no_success",$stock_no_success);  // 融资失败		
		$this->assign("no_begain",$no_begain);   		
		$this->assign("is_effect_forbid",$is_effect_forbid);
		$this->assign("is_success",$is_success);
		$this->assign("no_success",$no_success);
		$this->assign("is_effect",$is_effect);
		$this->assign("no_effect",$no_effect);
		$this->assign("three_balance",$three_balance);
		$this->assign("three_yeepay_withdraw",$three_yeepay_withdraw);
		$this->assign("three_rechange",$three_rechange);
		$this->assign("web_amount",$web_amount);
		$this->assign("user_refund",$user_refund);
		$this->assign("money_admin",$money_admin);
		$this->assign("money_diy",$money_diy);		
		$this->assign("is_identify",$is_identify);
		$this->assign("institution",$institution);
		$this->assign("user_level",$user_level);
		$this->assign("user_count",$user_count);		
		$this->assign("is_investor",$is_investor);		
		$this->display();
	}
}
?>
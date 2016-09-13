<?php

class UserFreezeAction extends CommonAction{

   public function index()
	{
		$now=get_gmtime();
		
		if($_REQUEST['status']=='NULL'){
			unset($_REQUEST['status']);
		}
		if(trim($_REQUEST['status'])!='')
		{
			$map[DB_PREFIX.'money_freeze.status'] = intval($_REQUEST['status']);
		}
		$map['status']= array('neq',3);;
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D (MoneyFreeze);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		//print_r($this->_list ( $model, $map ));exit;
		$this->display ();
	}
	public function edit_dsffreezer()
	{
		$id = intval($_REQUEST ['id']);
		$now=get_gmtime();
		$dsffreezer =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."money_freeze where id = ".$id);
		$deal_name =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$dsffreezer['deal_id']);
		$user_xinxi =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$dsffreezer['platformUserNo']);
		$log_info =$user_xinxi['name'];
		$GLOBALS['db']->query("update ".DB_PREFIX."money_freeze set status=2,create_time =$now where id=".$id);
		require_once APP_ROOT_PATH."system/libs/user.php";
		if($GLOBALS['db']->affected_rows()){
 			modify_account(array('money'=>$dsffreezer['amount']),$dsffreezer['platformUserNo'],'冻结资金解冻-冻结号码：'.$id);
		}
		syn_mortgate($dsffreezer['platformUserNo']);
		 
		//$GLOBALS['db']->query("update ".DB_PREFIX."user set money=$money,score=$score,point=$point where id=".$dsffreezer['platformUserNo']);
		
		//save_log($log_info.L("INSERT_SUCCESS"),1);
		$this->success(L("INSERT_SUCCESS"));
	}
	
}
?>
<?php
class UserInvestorAction extends CommonAction{
	public function index(){
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		$map['_string']=" (is_investor=1 or is_investor=2) and investor_status!=1 ";
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if(trim($_REQUEST['user_name'])!='')
		{
			$map[DB_PREFIX.'user.user_name'] = array('like','%'.trim($_REQUEST['user_name']).'%');
		}
		if(trim($_REQUEST['email'])!='')
		{
			$map[DB_PREFIX.'user.email'] = array('like','%'.trim($_REQUEST['email']).'%');
		}
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ('User');
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	public function show_content(){
		$id=intval($_REQUEST['id']);
		$status=intval($_REQUEST['status']);
		
		$user=M("user")->getById($id);
		if($status==1){
			$user['do_info']='审核通过';
		}elseif($status==3){
			$user['do_info']='审核';
			$show_bnt=3;
		}else{
			$user['do_info']='审核不通过';
		}
		$user['is_investor_name']=get_investor($user['is_investor']);
		$user['investor_status_name']=get_investor_status($user['investor_status']);
 		$this->assign('user',$user);
		$this->assign('status',$status);
		$this->assign('show_bnt',$show_bnt);
		$this->display();
 	}
 	public function investor_go_allow(){
 		$id=intval($_REQUEST['id']);
 		$status=intval($_REQUEST['investor_status']);
 		if($_REQUEST['investor_send_info']){
 			$investor_send_info=strim($_REQUEST['investor_send_info']);
 		} 
 		$user_data = M("User")->getById($id);
 		if($user_data){
 			$user_data['investor_status']=$status;
 			if($investor_send_info){
 				$user_data['investor_send_info']=$investor_send_info;	
 			}else{
 				$user_data['investor_send_info']='';
 			}
 			
 			M("User")->save($user_data);
 			send_investor_status($user_data);
  			$this->success("操作成功");
 		}else{
 			$this->error("没有该会员信息");
 		}
 	}
 	
 	
}
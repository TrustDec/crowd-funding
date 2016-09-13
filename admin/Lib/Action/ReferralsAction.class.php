<?php
	class ReferralsAction extends CommonAction{
	/**
	 * 邀请返利列表
	 */
		public function index(){
			$user_id = intval($_REQUEST['user_id']);
			$user_name = strim($_REQUEST['user_name']);
			$rel_user_name = strim($_REQUEST['rel_user_name']);
			if($user_id)
				$map['user_id']=$user_id;
			if($user_name)
				$map['user_name'] = array('like','%'.$user_name.'%');
			if($rel_user_name)
				$map['rel_user_name'] = array('like','%'.$rel_user_name.'%');
			
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			
			$name=$this->getActionName();
			$model = D ($name);
			
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}

			$this->assign ( 'vo', $vo );
			$this->assign ( 'user_name', $user_name );
			$this->assign ( 'user_id', $user_id );
			$this->assign ( 'rel_user_name', $rel_user_name );
			$this->display ();
		}
	/**
	 * 邀请返利删除
	 */
		public function delete(){
			//彻底删除指定记录
			$ajax = intval($_REQUEST['ajax']);
			$id = strim($_REQUEST ['id']);
			$user_id = intval($_REQUEST ['user_id']);
			$userinfo = M("User")->getById($user_id);
			if (isset ( $id )) {
					$condition = array ('id' => array ('in', explode ( ',', $id ) ));			
					$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
					foreach($rel_data as $data)
					{
						$info[] = $data['user_name']."推荐".$data['rel_user_name']."的邀请奖励";		
					}
					if($info)
					{
						$info = implode(",",$info)."记录";
					}
					$list = M(MODULE_NAME)->where ( $condition )->delete();
					
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
		
	/**
	 * 邀请返利导出电子表
	 */		
	public function export_csv($page = 1)
	{
		$pagesize = 500;
		set_time_limit(0);
		$limit = (($page - 1)*intval($pagesize)).",".(intval($pagesize));
	//	$limit=((0).",".(10));
		//echo $limit;exit;
		$where = " 1=1 ";
		$user_id=intval($_REQUEST['user_id']);
		$user_name=trim($_REQUEST['user_name']);
		$rel_user_name=trim($_REQUEST['rel_user_name']);
		//定义条件
		if($user_id>0)
		{
			$where.= " and r.user_id=".$user_id."";
		}
		if($user_name!='')
		{
			$where.= " and r.user_name like '%".$user_name."%'";
		}
		if($rel_user_name!='')
		{
			$where.= " and r.rel_user_name like '%".$rel_user_name."%'";
		}
		
		$list=$GLOBALS['db']->getAll("select r.*,u.create_time as register_time from ".DB_PREFIX."referrals as r left join ".DB_PREFIX."user as u on u.id=r.user_id where ".$where." order by r.id desc limit ".$limit);
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$order_value = array( 'user_name'=>'""', 'rel_user_name'=>'""', 'register_time'=>'""','pay_time'=>'""','create_time'=>'""','score'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","推荐人,被推荐人,注册时间,返利发放时间,返利创建时间,邀请奖励（积分）");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				$referrals_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$referrals_value['rel_user_name'] = '"' . iconv('utf-8','gbk',$v['rel_user_name']) . '"';
				$referrals_value['register_time'] = '"' . iconv('utf-8','gbk',to_date($v['register_time'])) . '"';
				$referrals_value['pay_time'] = '"' . iconv('utf-8','gbk',to_date($v['pay_time'])) . '"';
				$referrals_value['create_time'] = '"' . iconv('utf-8','gbk',to_date($v['create_time'])) . '"';
				$referrals_value['score'] = '"' . iconv('utf-8','gbk',$v['score']). '"' ;
				$content .= implode(",", $referrals_value) . "\n";
			}	
			
			//
			header("Content-Disposition: attachment; filename=referrals_list.csv");
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
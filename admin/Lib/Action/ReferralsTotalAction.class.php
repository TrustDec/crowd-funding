<?php
	class ReferralsTotalAction extends CommonAction{
	/**
	 * 邀请统计列表查看详情
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
	 * 邀请统计列表
	 */
		public function referrals_count(){
			
			$user_name=trim($_REQUEST ['user_name']);
			$ref_count=intval($_REQUEST ['ref_count'])?intval($_REQUEST ['ref_count']):'';
			
			$where=" 1= 1";
			if($user_name !='')
				$where .=" and user_name like '%".$user_name."%' ";
			
			if($ref_count >0)
				$where .=" and ref_count =".$ref_count."";
		
			if(intval($_REQUEST['action_id'])!='')
			{
				$action_id= intval($_REQUEST['action_id']);
			}
			$this->assign('action_id',$action_id);
		
			//$count_list=$GLOBALS['db']->getAll("select re.id,re.user_name,re.ref_count from (select u.id,u.user_name,(select count(a.id) from ".DB_PREFIX."user as a where a.pid=u.id) as ref_count from ".DB_PREFIX."user as u where u.id in (select b.pid from ".DB_PREFIX."user as b where b.pid!=0 and b.is_effect=1 group by b.pid)) as re where ".$where);
			$count_list=$GLOBALS['db']->getOne(" SELECT count(re.id) from (SELECT u.id,u.user_name,count(b.pid) as ref_count FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."user AS b ON b.pid = u.id WHERE b.pid >0  GROUP BY b.pid) as re WHERE ".$where);
			
			if($count_list)
			{	
				//排序字段 默认为主键名
				if (isset ( $_REQUEST ['_order'] )) {
					$order = $_REQUEST ['_order'];
				} else {
					$order = ! empty ( $sortBy ) ? $sortBy : "id";
				}
				//排序方式默认按照倒序排列
				//接受 sost参数 0 表示倒序 非0都 表示正序
				if (isset ( $_REQUEST ['_sort'] )) {
					$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
				} else {
					$sort = $asc ? 'asc' : 'desc';
				}
				
				import( "@.ORG.Page" );
				//创建分页对象
				if (! empty ( $_REQUEST ['listRows'] )) {
					$listRows = $_REQUEST ['listRows'];
				} else {
					$listRows = '';
				}
				$p = new Page ( $count_list, $listRows );
				//分页查询数据
				$referrals_count_list=$GLOBALS['db']->getAll(" SELECT re.id,re.user_name,re.ref_count from (SELECT u.id,u.user_name,count(b.pid) as ref_count FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."user AS b ON b.pid = u.id WHERE b.pid >0  GROUP BY b.pid) as re WHERE ".$where." order by ".$order." ".$sort." limit ".$p->firstRow.",".$p->listRows);
				
				//分页显示
				$page = $p->show ();
				//列表排序显示
				$sortImg = $sort; //排序图标
				$sortAlt = $sort == 'desc' ? L('SORT_ASC') : L('SORT_DESC'); //排序提示
				$sort = $sort == 'desc' ? 1 : 0; //排序方式
				$assign_array = array();
			}
			
			$this->assign ( 'list', $referrals_count_list );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
			$this->assign ( "user_name", $user_name );
			$this->assign ( "ref_count", $ref_count );
			$this->display ();
		}
	/**
	 * 邀请统计删除
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
	 * 邀请统计详情列表导出电子表
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
				header("Content-Disposition: attachment; filename=referralstotal_list.csv");
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
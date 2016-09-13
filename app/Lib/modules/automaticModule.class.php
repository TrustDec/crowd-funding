<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

//自动检测
class automaticModule extends BaseModule
{
	public function __construct(){
		parent::__construct();
		 
	}
	public function index(){
		 set_time_limit(0);
		 ob_end_flush();
		 
		 $this->finance_do_company_create();
		 
 		 $this->flush_sleep();
		 
		 echo 2;
		 $this->flush_sleep();
	}
	//lym ....
	
	public function lym_test(){
		set_time_limit(0);
		 ob_end_flush();
		 
		$this->finance_do_company_create();
		 
		$this->flush_sleep();
	}
	//finance start
	public function finance_do_company_create()
	{
		 
 		//引入要检测的模块	
		echo "检测创建公司<br />";
		$input_array=array("user_id"=>8989464,"company_name"=>"北京公司".rand(),"company_status"=>0,"company_brief"=>"一句话简介"
		,"company_website"=>"http://www.fanwe.com","company_begin_time"=>"1443138623","company_level"=>1,
		"company_job"=>"ceo","company_logo"=>"http://zc3.fanwe.com/public/attachment/201504/01/09/551b4d6771e5a.jpg","company_business_card"=>"http://zc3.fanwe.com/public/attachment/201504/01/09/551b4d6771e5a.jpg"
		);
 		$this->auto_module("finance", "do_company_create", $input_array);
		echo "创建公司 检测成功";
	
		 
		 
		//ob_end_flush();  
	}
	
 	//finance end
	
	
	//vote start
	public function vote_index(){
		//id为问卷id，从后台问卷调查列表的编号中获得
		echo "检测问卷展示<br />";
		$input_array=array("id"=>5);
 		$this->auto_module("vote", "index", $input_array);
		echo "问卷展示  检测成功";	
	}
	public function vote_dovote(){
		//id为问卷id，从后台问卷调查列表的编号中获得
		//例如36是问题id,对应的数组是问题回答，数据可从后台根据问卷vote_id获得
		//自定义答案，$answers需要加上其他，$input_array需要加上other_问题id,例如37
		echo "检测问卷提交<br />";
		$answers=array("36"=>array("0"=>""),"37"=>array("0"=>"三方斯蒂芬斯蒂芬","1"=>"其他"),"38"=>array("0"=>"给对方水电费违法所得税"),"39"=>array("0"=>"鼎折覆餗发送房顶上"),"40"=>array("0"=>"和法国华人特发生","1"=>"其他:据了解快乐鬼地方"));
		$input_array=array("mobile"=>"","eamil"=>"","vote_id"=>5,"vote_other"=>"其他","name"=>$answers,"other_37"=>"给对方个梵蒂冈");
		
 		$this->auto_module("vote", "dovote", $input_array);
		echo "问卷提交  检测成功";	
	}
	//vote end
	public function flush_sleep(){
		ob_flush();  
		flush();  
		sleep(1);
	}
	//检测 融资功能
	public function auto_module($class,$act2,$data){
		//创建公司
		//$class = "Finance";
		if(file_exists(APP_ROOT_PATH.'app/Lib/modules/'.$class.'Module.class.php'))
		{	
			require_once APP_ROOT_PATH.'app/Lib/modules/'.$class.'Module.class.php';
 			$class=$class.'Module';
 		 	if(class_exists($class))
			{
		 		$obj = new $class;		
				 
				if(method_exists($obj,$act2))
				{
 					$is_debug = true;
					$obj->$act2($is_debug,$data);
				}
				else
				{
					 echo '类存在，函数不存在';
 				}
			}
			else
			{
				echo '类不存在';
			}
		}else{
			echo $class.'Module.class.php 文件不存在';
		}
	}
	
}
?>
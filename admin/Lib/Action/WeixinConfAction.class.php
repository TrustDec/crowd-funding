<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class WeixinConfAction extends CommonAction{
	public function index()
	{
		$config = M("WeixinConf")->where("is_conf=1")->order("sort asc")->findAll();
		foreach($config as $k=>$v){
 			if($v['type']==4){
				$config[$k]['value_scope']=explode(',',$v['value_scope']);
 			}else{
 				$config[$k]['value_scope']='';
 			}
		}
		$this->assign("config",$config);
		$this->display();
	}
	
	public function update()
	{
		 
		foreach($_POST as $k=>$v)
		{
			M("WeixinConf")->where("name='".$k."'")->setField("value",$v);
		}
		$this->success("保存成功");
	}
 }
?>
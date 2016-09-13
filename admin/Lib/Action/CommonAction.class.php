<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class CommonAction extends AuthAction{
	public function index() {		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
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
	
	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param string $name 数据对象名称
     +----------------------------------------------------------
	 * @return HashMap
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty ( $name )) {
			$name = $this->getActionName();
		}
		//$name=$this->getActionName();
		$model = D ( $name );
		$map = array ();
		foreach ( $model->getDbFields () as $key => $val ) {
			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				$map [$val] = $_REQUEST [$val];
			}
		}
		return $map;

	}

	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
     +----------------------------------------------------------
	 * @return void
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( 'id' );
		
		if ($count > 0) {
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page ( $count, $listRows );
			//分页查询数据

			$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll ( );
			
//			echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			//分页显示

			$page = $p->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
			$this->assign ( "nowPage",$p->nowPage);
		}
		return;
	}
	
	
	/**
	 * 上传图片的通公基础方法
	 *
	 * @return array
	 */
	protected function uploadImage()
	{		
		if(conf("WATER_MARK")!="")
		$water_mark = get_real_path().conf("WATER_MARK");  //水印
		else
		$water_mark = "";
	    $alpha = conf("WATER_ALPHA");   //水印透明
	    $place = conf("WATER_POSITION");  //水印位置
	    
		$upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = conf('MAX_IMAGE_SIZE') ;  /* 配置于config */
        //设置上传文件类型
		
        $upload->allowExts  =  explode(',',conf('ALLOW_IMAGE_EXT')); /* 配置于config */        
       
        $dir_name = to_date(get_gmtime(),"Ym");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
	        
	    $dir_name = $dir_name."/".to_date(get_gmtime(),"d");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
	     
	    $dir_name = $dir_name."/".to_date(get_gmtime(),"H");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
        
        
        
       	$save_rec_Path = "/public/attachment/".$dir_name."/origin/";  //上传时先存放原图          	      
        $savePath = APP_ROOT_PATH."public/attachment/".$dir_name."/origin/"; //绝对路径
		if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/")) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/");
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name."/origin/", 0777);
	    }        
        $domain_path = get_domain().APP_ROOT.$save_rec_Path;
			
		$upload->saveRule = "uniqid";   //唯一
		$upload->savePath = $savePath;
        if($upload->upload())
        {
        	$uploadList = $upload->getUploadFileInfo();    
         	foreach($uploadList as $k=>$fileItem)
        	{        			
        			$file_name = $fileItem['savepath'].$fileItem['savename'];  //上图原图的地址
        			//水印图
        			$big_save_path = str_replace("origin/","",$savePath);  //大图存放图径
					$big_file_name = str_replace("origin/","",$file_name);	
					
//					Image::thumb($file_name,$big_file_name,'',$big_width,$big_height);
					@file_put_contents($big_file_name,@file_get_contents($file_name));					
        			if(file_exists($water_mark))
	        		{
	        			Image::water($big_file_name,$water_mark,$big_file_name,$alpha,$place);	
	        		}	        		        			
        			$big_save_rec_Path = str_replace("origin/","",$save_rec_Path);  //上传的图存放的相对路径
        			$uploadList[$k]['recpath'] = $save_rec_Path;
        			$uploadList[$k]['bigrecpath'] = $big_save_rec_Path;        			
//        			if(app_conf("PUBLIC_DOMAIN_ROOT")!='')
//        			{
//	        			$origin_syn_url = app_conf("PUBLIC_DOMAIN_ROOT")."/es_file.php?username=".app_conf("IMAGE_USERNAME")."&password=".app_conf("IMAGE_PASSWORD")."&file=".get_domain().APP_ROOT."/public/attachment/".$dir_name."/origin/".$fileItem['savename']."&path=attachment/".$dir_name."/origin/&name=".$fileItem['savename']."&act=0";
//	        			$big_syn_url = app_conf("PUBLIC_DOMAIN_ROOT")."/es_file.php?username=".app_conf("IMAGE_USERNAME")."&password=".app_conf("IMAGE_PASSWORD")."&file=".get_domain().APP_ROOT."/public/attachment/".$dir_name."/".$fileItem['savename']."&path=attachment/".$dir_name."/&name=".$fileItem['savename']."&act=0";
//	        			@file_get_contents($origin_syn_url);
//	        			@file_get_contents($big_syn_url);
//        			}
        	} 
        	return array("status"=>1,'data'=>$uploadList,'info'=>L("UPLOAD_SUCCESS"));
        }
        else 
        {
        	return array("status"=>0,'data'=>null,'info'=>$upload->getErrorMsg());
        }
	}
	
	
	/**
	 * 上传文件公共基础方法
	 *
	 * @return array
	 */
	protected function uploadFile()
	{	    
		$upload = new UploadFile();
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'flash' => array('swf', 'flv'),
			'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
			'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'txt', 'zip', 'rar','pdf'),
		);
		
        //设置上传文件大小
        $upload->maxSize  = conf('MAX_IMAGE_SIZE') ;  /* 配置于config */
        //设置上传文件类型
		if(!empty($ext_arr[$_REQUEST['dir']]))
		{
			$upload->allowExts =$ext_arr[$_REQUEST['dir']];
		}else{
			$upload->allowExts  =  explode(',',conf('ALLOW_IMAGE_EXT')); /* 配置于config */        
		}
        
       
		$dir_name = to_date(get_gmtime(),"Ym");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
	        
	    $dir_name = $dir_name."/".to_date(get_gmtime(),"d");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
	     
	    $dir_name = $dir_name."/".to_date(get_gmtime(),"H");
	    if (!is_dir(APP_ROOT_PATH."public/attachment/".$dir_name)) { 
	             @mkdir(APP_ROOT_PATH."public/attachment/".$dir_name);
	             @chmod(APP_ROOT_PATH."public/attachment/".$dir_name, 0777);
	        }
        
        
        
       	$save_rec_Path = "/public/attachment/".$dir_name."/";  //上传时先存放原图          	      
        $savePath = APP_ROOT_PATH."public/attachment/".$dir_name."/"; //绝对路径
        $domain_path = get_domain().APP_ROOT.$save_rec_Path;
        
			
		$upload->saveRule = "uniqid";   //唯一
		$upload->savePath = $savePath;
		 
        if($upload->upload())
        {
        	$uploadList = $upload->getUploadFileInfo();   
        	foreach($uploadList as $k=>$fileItem)
        	{
      			$uploadList[$k]['recpath'] = $save_rec_Path;
      			if(app_conf("PUBLIC_DOMAIN_ROOT")!='')
      			{
	      			$syn_url = app_conf("PUBLIC_DOMAIN_ROOT")."/es_file.php?username=".app_conf("IMAGE_USERNAME")."&password=".app_conf("IMAGE_PASSWORD")."&file=".$domain_path.$fileItem['savename']."&path=attachment/".$dir_name."/&name=".$fileItem['savename']."&act=0";
	      			@file_get_contents($syn_url);
      			}
        	} 	
        	return array("status"=>1,'data'=>$uploadList,'info'=>L("UPLOAD_SUCCESS"));
        }
        else 
        {
        	return array("status"=>0,'data'=>null,'info'=>$upload->getErrorMsg());
        }
	}
	
	public function _before_update()
	{
		$uname = $_REQUEST['uname'];
		if($uname&&trim($uname)!='')
		{
			$rs = M(MODULE_NAME)->where("uname='".$uname."' and id <> ".intval($_REQUEST['id']))->count();
			if($rs > 0)
			{
				$this->error(l("UNAME_EXISTS"));
			}
		}
		$py = $_REQUEST['py'];
		if($py&&trim($py)!='')
		{
			$rs = M(MODULE_NAME)->where("py='".$py."' and id <> ".intval($_REQUEST['id']))->count();
			if($rs > 0)
			{
				$this->error(l("UNAME_EXISTS"));
			}
		}
	}
	
	public function _before_insert()
	{
		$uname = $_REQUEST['uname'];
		if($uname&&trim($uname)!='')
		{
			$rs = M(MODULE_NAME)->where("uname='".$uname."' and id <> ".intval($_REQUEST['id']))->count();
			if($rs > 0)
			{
				$this->error(l("UNAME_EXISTS"));
			}
		}
		$py = $_REQUEST['py'];
		if($py&&trim($py)!='')
		{
			$rs = M(MODULE_NAME)->where("py='".$py."' and id <> ".intval($_REQUEST['id']))->count();
			if($rs > 0)
			{
				$this->error(l("UNAME_EXISTS"));
			}
		}
	}
	
	
	public function toogle_status()
	{
		
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$field = $_REQUEST['field'];
		$info = $id."_".$field;
		if(MODULE_NAME=='Finance'){
			$c_is_effect = M(FinanceCompany)->where("id=".$id)->getField($field);  //当前状态
		}else{
			$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField($field);  //当前状态
		}
		
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		if(MODULE_NAME=='Finance'){
			M(FinanceCompany)->where("id=".$id)->setField($field,$n_is_effect);	
		}else{
			M(MODULE_NAME)->where("id=".$id)->setField($field,$n_is_effect);	
		}
		
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param string $sql_str Sql语句 不含排序字段的SQL语句
	 * @param string $parameter 分页跳转的时候保证查询条件
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	function _Sql_list($model, $sql_str, $parameter='', $sortBy = '', $asc = false)
	{
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = $sortBy;
		}
	
		if ($sortBy == 'nosort'){
			unset($order);
		}
	
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
	
		//取得满足条件的记录数
		$sql_tmp = 'select count(*) as tpcount from ('.$sql_str.') as a';
		//dump($sql_tmp);
		$rs = $model->query($sql_tmp, false);
		//dump($rs);
	
		$count = intval($rs[0]['tpcount']);
		//dump($count);
		if($count>0) {
			//创建分页对象
			if(!empty($_REQUEST['listRows'])) {
				$listRows  =  $_REQUEST['listRows'];
			}else {
				$listRows  =  '';
			}
	
			import ( "@.ORG.Page" );
			$p  = new Page($count,$listRows);
			//分页跳转的时候保证查询条件
			//dump($parameter);
			if ((!empty($parameter)) && (substr($parameter,1,1) <> '&')){
				//add by chenfq 2010-06-19 添加分页条件连接缺少 & 问题
				$parameter = '&'.$parameter;
			}
			$p->parameter = $parameter;
	
			//排序
			if (!empty($order))
				$sql_str .= ' ORDER BY '.$order.' '.$sort;
	
			//分页查询数据
			$sql_str .= ' LIMIT '.$p->firstRow.','.$p->listRows;
	
			//dump($sql_str);
			$voList = $model->query($sql_str, false);
			//dump($voList);
			//分页显示
			$page       = $p->show();
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? L('SORT_ASC') : L('SORT_DESC'); //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
	
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign('list', $voList);
			$this->assign("page", $page);
		}
		//Cookie::set ( '_currentUrl_', U($this->getActionName()."/index") );
		return $voList;
	}
	public function get_jx_json($info,$type='') {
		$year = array_keys($info);
		$price = array_values($info);
	
		$chart = new open_flash_chart();
		$chart->set_bg_colour( '#FFFFFF' );//flash背景颜色
	
		//$title = new title( 'UK Petrol price (pence) per Litre' );
		//$title->set_style( "{font-size: 20px; color: #A2ACBA; text-align: center;}" );
		//$chart->set_title( $title );
		$d = new hollow_dot();
		$d->size(3)->halo_size(0)->colour('#8f8fbd');
	
		$area = new area();
		$area->set_width( 2);
		$area->set_default_dot_style( $d );
		$area->set_fill_colour( '#eaf6ff' );
		$area->set_fill_alpha( 0.4 );
		$area->set_colour('#8f8fbd');
		//$area->set_values($price);
	
	
		$area->set_values($price);
		$chart->add_element($area);
	
	
		$num=intval(count($year>=7?$year:7)/7);
	
		if($num==0){
			$num=intval(count($year));
		}
	
		$num=$num>0?$num:1;
	
		$x_labels = new x_axis_labels();
		$x_labels->set_steps($num);
		$x_labels->set_size(12);
		$x_labels->set_colour('#000000');
		$x_labels->set_labels($year);
		if(count($year)>0){
			$x_labels->set_vertical();
		}
	
		//		// 插入数据
		$x = new x_axis();
	
		$x->set_colour('#000000');
		$x->set_grid_colour('#dadada');
		$x->set_offset(false);
		$x->set_steps($num);
	
	
		// Add the X Axis Labels to the X Axis
		//$x->set_labels($x_labels);
		$x->set_labels_from_array( $year );
		$chart->set_x_axis($x);
	
		$y = new y_axis();
		$y->labels = null;
		$max = $this->get_the_right_y(max($price));
		$max=$max>0?$max:1;
		$y->set_range(0, ($max/5+1)*5, ($max/5+1));
		//		if ($max > 20 && $max <= 100) {
		//
		//			$y->set_range(0, $max, 10);
		//		}elseif($max >= 10&&$max<=20){
		//			$y->set_range(0, $max, 5);
		//		}
		//		else {
		//			$y->set_range(0, $max);
		//		}
		$y->set_colour('#000000');
		$y->set_grid_colour('#dadada');
		if($type=='percent'){
			$y->set_label_text("       #val#%");
		}else{
			$y->set_label_text("       #val#");
		}
	
		$chart->add_y_axis($y);
		$info = $chart->toPrettyString();
		return $info;
	}
	
	public function get_jx_json_bar($info,$type='') {
		$year =array_keys($info);
	
		$price = array_values($info);
	
		$chart = new open_flash_chart();
		$chart->set_bg_colour( '#FFFFFF' );//flash背景颜色
	
		$x_labels = new x_axis_labels();
		$x_labels->set_steps(1);
		$x_labels->set_size(12);
		$x_labels->set_colour('#000000');
		if(count($year)>0){
			$x_labels->set_vertical();
		}
		$x_labels->set_labels($year);
		//		// 插入数据
		$x = new x_axis();
	
		$x->set_colour('#000000');
		$x->set_grid_colour('#dadada');
		$x->set_offset(true);
		$x->set_steps(1);
	
		// Add the X Axis Labels to the X Axis
		$x->set_labels($x_labels);
		$x->set_offset(true);
		$chart->set_x_axis($x);
	
		//		$bar = new bar_filled( '#74b1e0', '#9dc7e8' );
		//		$bar->set_values( $price );
		$price_array=array();
		foreach($price as $k=>$v){
			$price_array[$k]=new bar_value($v);
			$price_array[$k]->set_colour('#74b1e0');
			if($type=='percent'){
				//$y->set_label_text("#val#%");
				$price_array[$k]->set_tooltip( $year[$k].'<br>'.''.number_format($v).'%' );
			}else{
				$price_array[$k]->set_tooltip( $year[$k].'<br>'.''.number_format($v) );
			}
				
		}
		$bar = new bar_glass();
		$bar->set_values( $price_array );
		$chart->add_element($bar);
		//
		// LOOK:
		//
		//$x_legend = new x_legend( '1983 to 2008' );
		//$x_legend->set_style( '{font-size: 20px; color: #778877}' );
		//$chart->set_x_legend( $x_legend );
	
		//
		// remove this when the Y Axis is smarter
		//
		$y = new y_axis();
		$max = $this->get_the_right_y(max($price));
		$max=$max>0?$max:1;
		$y->set_range(0, ($max/5+1)*5, ($max/5+1));
		//		if ($max > 20 && $max <= 100) {
		//
		//			$y->set_range(0, $max, 10);
		//		}elseif($max >= 10&&$max<=20){
		//			$y->set_range(0, $max, 5);
		//		}
		//		else {
		//			$y->set_range(0, $max);
		//		}
	
		$y->set_colour('#000000');
		$y->set_grid_colour('#dadada');
		if($type=='percent'){
			$y->set_label_text("       #val#%");
		}else{
			$y->set_label_text("       #val#");
		}
		$chart->add_y_axis($y);
		$info = $chart->toPrettyString();
		return $info;
	}
	public function check_day($start_time,$end_time){
		$start_time=strtotime($start_time);
		$end_time=strtotime($end_time);
		$left_time=$end_time-$start_time;
		if($left_time/3600>1){
			return true;
		}else{
			return false;
		}
	}
	public function get_value($value, $field,$y_field,$x_type='',$y_type='') {
		$info = array ();
		if($y_type=='percent'){
				
			foreach ($value as $k => $v) {
				if(!empty($v[$y_field])){
	
						
					if($x_type=='hour_time'){
						$v[$y_field]=substr($v[$y_field],0,5);
					}elseif($x_type=='day_time'){
						$v[$y_field]=substr($v[$y_field],0,10).' ';
					}
						
					if (isset ($info[$v[$y_field]])) {
						$info[$v[$y_field]] = ($v[$field]) +( $info[$v[$y_field]]);
					} else {
						$info[$v[$y_field]] = ($v[$field]);
					}
						
					//$info[$v[$y_field]] = ($info[$v['sdate']] > 0) ? $info[$v['sdate']] : 0;
					$info[$v[$y_field]] = ($info[$v[$y_field]] > 0) ? $info[$v[$y_field]] : '';
				}
			}
				
			foreach($info as $k=>$v){
				$info[$k]=$v*100;
				$info[$k]=$info[$k];
			}
				
		}else{
			foreach ($value as $k => $v) {
				if(!empty($v[$y_field])){
	
					if($x_type=='hour_time'){
						$v[$y_field]=substr($v[$y_field],0,5);
					}elseif($x_type=='day_time'){
						$v[$y_field]=substr($v[$y_field],0,10).' ';
					}
						
					if (isset ($info[$v[$y_field]])) {
						$info[$v[$y_field]] = intval($v[$field]) +intval( $info[$v[$y_field]]);
					} else {
						$info[$v[$y_field]] = intval($v[$field]);
					}
					//$info[$v[$y_field]] = ($info[$v['sdate']] > 0) ? $info[$v['sdate']] : 0;
					$info[$v[$y_field]] = ($info[$v[$y_field]] > 0) ? $info[$v[$y_field]] : 0;
				}
					
			}
		}
	
		return $info;
	}
	
	
	public function get_jx_json_all($value,$array){
		$data=array();
		foreach($array as $k=>$v){
			foreach($v as $k1=>$v1){
				if(isset($v1[3])&&!empty($v1[3])){
					$type=$v1[3];
				}else{
					$type='';
				}
				if(isset($v1[4])&&!empty($v1[4])){
					$type_y=$v1[4];
				}else{
					$type_y='';
				}
	
				$data[$k][$k1]['line'] = $this->get_jx_json($this->get_value($value, $v1[0],$v1[1],$type,$type_y),$type_y);
				$data[$k][$k1]['bar'] = $this->get_jx_json_bar($this->get_value($value, $v1[0],$v1[1],$type,$type_y),$type_y);
				$data[$k][$k1]['title']=$v1[2];
			}
	
		}
	
		return $data;
	}
	public function get_the_right_y($max){
		//$max=35;
		$max=intval($max);
	
		if($max>5&&$max<10){
			return 10;
		}else{
	
			$num=intval($max)/4;
	
			$num=($num*10-$num*10%10)/10;
			$num=($num*100-$num*100%100)/100;
			$num=$num>=1?$num:1;
	
		}
	
	
	
		return intval($num*5);
	}
}
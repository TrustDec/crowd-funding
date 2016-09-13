<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------


//系统安装
class IndexAction extends Action{
	private function getRealPath()
	{
		return  APP_ROOT_PATH;
	}
	private $install_lock;
	public function __construct()
	{
		parent::__construct();		
		$this->install_lock = $this->getRealPath()."/public/install.lock";
		if(file_exists($this->install_lock))
		{
			$this->assign("jumpUrl",__ROOT__."/m.php");
			$this->error("系统已经安装");
		}
	}
	
    public function index(){
		{
			$rs = $this->checkEnv();  //检测系统环境
			$this->assign("result",$rs);
			$this->display();//输出检测结果
		}
    }
    
    public function database()
    {
    	//系统安装
		if(file_exists($this->install_lock))
		{
			
			$this->assign("jumpUrl",__ROOT__."/m.php");
			$this->error("系统已经安装");
		}
		else 
		{
			$rs = $this->checkEnv();  //检测系统环境
			if($rs['status'])
			{
				$this->display();
			}
			else 
			{
				$this->assign("result",$rs);
				$this->display("index");//输出检测结果
			}
		}   	
    }
    
    public function install()
    {
    	$return_rs = array(
    		'msg'=>'安装成功',
    		'status'=>true,
    	);  //用于返回的数据
    	
    	$db_config['DB_HOST'] = $_REQUEST['DB_HOST'];
    	$db_config['DB_NAME'] = $_REQUEST['DB_NAME'];
    	$db_config['DB_USER'] = $_REQUEST['DB_USER'];
    	$db_config['DB_PWD'] = $_REQUEST['DB_PWD'];
    	$db_config['DB_PORT'] = $_REQUEST['DB_PORT'];
    	$db_config['DB_PREFIX'] = $_REQUEST['DB_PREFIX'];
    	$demo_data = intval($_REQUEST['DEMO_DATA']);

		$connect = @mysql_connect($db_config['DB_HOST'].":".$db_config['DB_PORT'],$db_config['DB_USER'],$db_config['DB_PWD']);
    	if(mysql_error()=="")
    	{
    		$rs = mysql_select_db($db_config['DB_NAME'],$connect);
    		if($rs)
    		{
    			$return_rs['status'] = true;
    		}
    		else 
    		{
    			$db_rs = mysql_query("create database ".$db_config['DB_NAME']." DEFAULT CHARACTER SET utf8");
    			if($db_rs)
    			{
       				$return_rs['status'] = true;
    			}
    			else 
    			{
    				$return_rs['msg'] = "创建数据库失败";
    				$return_rs['status'] = false;
    			}
    		}
    	}
    	else 
    	{
    			$return_rs['msg'] = "连接数据库失败";
    			$return_rs['status'] = false;
    	}
    	
    	if($return_rs['status'])
    	{
	    	//开始将$db_config写入配置
	    	$db_config_str 	 = 	"<?php\r\n";
	    	$db_config_str	.=	"return array(\r\n";
	    	foreach($db_config as $key=>$v)
	    	{
	    		$db_config_str.="'".$key."'=>'".$v."',\r\n";
	    	}
	    	$db_config_str.=");\r\n";
	    	$db_config_str.="?>";
	    	@file_put_contents($this->getRealPath()."/public/db_config.php",$db_config_str);
    		
    		//开始执行安装脚本
    		$msg = $this->restore($this->getRealPath()."/install/install_demo.sql",$db_config);
			
			//如果不要演示数据，执行清空SQL
			if($demo_data==0)
			{
				$msg = $this->restore($this->getRealPath()."/install/truncate.sql",$db_config);
			}
			 
			
			 
			if(INVEST_TYPE>0){
			 	mysql_query("update  ".$db_config['DB_PREFIX']."conf set value=".INVEST_TYPE."  where name='INVEST_STATUS' ");
			}
 			if($msg == "")
			{
				@file_put_contents($this->install_lock,"");	
    			$this->success($return_rs['msg'],1);
			}
			else 
			{
				$this->error($msg,1);
			}
    	}
    	else 
    	{
    		$this->error($return_rs['msg'],1);
    	}   	

    }
    
    private function checkEnv()
    {
    	$rs['status'] = 1;
    	$rs['msg'] = "检测成功";
		 
    	if(substr(PHP_VERSION, 0, 1) < 5)
    	{
    		$rs['php_env'] = "本系统需要php5.0以上环境";
    		$rs['status'] = 0;
    		$rs['msg'] = "检测失败";
    	}
    	else 
    	{
    		if(substr(PHP_VERSION, 0, 3)!=5.3){
    			$rs['php_env'] = "本系统需要php5.3环境";
	    		$rs['status'] = 0;
	    		$rs['msg'] = "检测失败";
    		}else{
    			$rs['php_env'] = "php版本号：".PHP_VERSION;
    		}
    	}   

    	if(extension_loaded('gd'))
    	{
    		$rs['gd_info'] = "通过验证";
    	}
    	else 
    	{
    		$rs['gd_info'] = "本系统需要GD函数库的支持";
    		$rs['status'] = 0;
    		$rs['msg'] = "检测失败";
    	}
    	
    	if(function_exists("mb_strlen")){
    		$rs['mb_info'] = "通过验证";
    	}
    	else {
    		$rs['mb_info'] = "需要开启MB_STRING函数库";
    		$rs['status'] = 0;
    		$rs['msg'] = "检测失败";
    	} 

    	$dirs = C("DIRS_CHECK");
    	foreach($dirs as $dir)
    	{
     		if($this->file_mode_info($this->getRealPath().$dir)<2)
    		{
    			//目录不可写
    			$rs[$dir]=array();
    			$rs[$dir]['msg'] = '不可写';
    			$rs['status'] = 0;
    			$rs['msg'] = "检测失败";
 				if(is_dir($this->getRealPath().$dir)){
					$rs[$dir]['file_type'] = 'dir';
				}else{
					$rs[$dir]['file_type'] = 'file';
				}
    		}
    		else 
    		{
    			$rs[$dir]['msg'] = '可写';
				if(is_dir($this->getRealPath().$dir)){
					$rs[$dir]['file_type'] = 'dir';
				}else{
					$rs[$dir]['file_type'] = 'file';
				}
    		}
    	}
     	return $rs;
    }
    
	/**
	 * 文件或目录权限检查函数
	 *
	 * @access          private
	 * @param           string  $file_path   文件路径
	 * @param           bool    $rename_prv  是否在检查修改权限时检查执行rename()函数的权限
	 *
	 * @return          int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
	 *                          返回值在二进制计数法中，四位由高到低分别代表
	 *                          可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
	 */
	private function file_mode_info($file_path)
	{
	    /* 如果不存在，则不可读、不可写、不可改 */
	    if (!file_exists($file_path))
	    {
	        return false;
	    }
	
	    $mark = 0;
	
	    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
	    {
	        /* 测试文件 */
	        $test_file = $file_path . '/cf_test.txt';
	
	        /* 如果是目录 */
	        if (is_dir($file_path))
	        {
	            /* 检查目录是否可读 */
	            $dir = @opendir($file_path);
	            if ($dir === false)
	            {
	                return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
	            }
	            if (@readdir($dir) !== false)
	            {
	                $mark ^= 1; //目录可读 001，目录不可读 000
	            }
	            @closedir($dir);
	
	            /* 检查目录是否可写 */
	            $fp = @fopen($test_file, 'wb');
	            if ($fp === false)
	            {
	                return $mark; //如果目录中的文件创建失败，返回不可写。
	            }
	            if (@fwrite($fp, 'directory access testing.') !== false)
	            {
	                $mark ^= 2; //目录可写可读011，目录可写不可读 010
	            }
	            @fclose($fp);
	
	            @unlink($test_file);
	
	            /* 检查目录是否可修改 */
	            $fp = @fopen($test_file, 'ab+');
	            if ($fp === false)
	            {
	                return $mark;
	            }
	            if (@fwrite($fp, "modify test.\r\n") !== false)
	            {
	                $mark ^= 4;
	            }
	            @fclose($fp);
	
	            /* 检查目录下是否有执行rename()函数的权限 */
	            if (@rename($test_file, $test_file) !== false)
	            {
	                $mark ^= 8;
	            }
	            @unlink($test_file);
	        }
	        /* 如果是文件 */
	        elseif (is_file($file_path))
	        {
	            /* 以读方式打开 */
	            $fp = @fopen($file_path, 'rb');
	            if ($fp)
	            {
	                $mark ^= 1; //可读 001
	            }
	            @fclose($fp);
	
	            /* 试着修改文件 */
	            $fp = @fopen($file_path, 'ab+');
	            if ($fp && @fwrite($fp, '') !== false)
	            {
	                $mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
	            }
	            @fclose($fp);
	
	            /* 检查目录下是否有执行rename()函数的权限 */
	            if (@rename($test_file, $test_file) !== false)
	            {
	                $mark ^= 8;
	            }
	        }
	    }
	    else
	    {
	        if (@is_readable($file_path))
	        {
	            $mark ^= 1;
	        }
	
	        if (@is_writable($file_path))
	        {
	            $mark ^= 14;
	        }
	    }
	
	    return $mark;
	}
	
   /**
     * 执行SQL脚本文件
     *
     * @param array $filelist
     * @return string
     */
    private function restore($file,$db_config)
    {
			set_time_limit(0);
			$db = Db::getInstance(array('dbms'=>'mysql','hostname'=>$db_config['DB_HOST'],'username'=>$db_config['DB_USER'],'password'=>$db_config['DB_PWD'],'hostport'=>$db_config['DB_PORT'],'database'=>$db_config['DB_NAME']));
    		$sql = file_get_contents($file);
    		$sql = $this->remove_comment($sql);
    		$sql = trim($sql);
 
    		$sql = str_replace("\r", '', $sql);
       		$segmentSql = explode(";\n", $sql);
       		foreach($segmentSql as $k=>$itemSql)
       		{
       			
       			$itemSql = str_replace("%DB_PREFIX%",$db_config['DB_PREFIX'],$itemSql);
       			$db->query($itemSql);
       		}  
       		
       		//开始写入配置文件
			$sys_configs = $db->query("select name,value from ".$db_config["DB_PREFIX"]."conf");
			$config_str = "<?php\n";
			$config_str .= "return array(\n";
			foreach($sys_configs as $k=>$v)
			{
				$config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
			}
			$config_str.=");\n ?>";
			@file_put_contents($this->getRealPath()."/public/sys_config.php",$config_str);
			
    		return "";
    }
    
    

    /**
     * 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
     *
     * @access  public
     * @param   string      $sql        SQL查询串
     * @return  string      返回已过滤掉注释的SQL查询串。
     */
    private function remove_comment($sql)
    {
        /* 删除SQL行注释，行注释不匹配换行符 */
        $sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);

        /* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
        //$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
        $sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);

        return $sql;
    }
    
    
}
?>
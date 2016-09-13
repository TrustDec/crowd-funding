<?php

class Log_
{
	// 打印log
	function  log_result($word) 
	{
		$log_names = date("Y-m-d",time()).".log";
	    $fp = fopen("./log/".$log_names,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H:%M:%S",time())."--".$word."\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}
}

?>
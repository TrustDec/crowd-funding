<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------


class words
{
	/**  
	 * 文本分词
	 * @param string $text 需要分词的文本
	 * @param int $num 返回分词数量
	 * @return array
	 */
	public static function segment($text,$num = 10)
	{
		$list = array();
		if(empty($text))
			return $list;
		
		//检测是否已安装php_scws扩展
		if(function_exists("scws_open"))
		{
			$sh = scws_open();
			scws_set_charset($sh,'utf8');
			scws_set_dict($sh,APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			scws_set_rule($sh,APP_ROOT_PATH.'system/scws/rules.utf8.ini');
			scws_set_ignore($sh,true);
			scws_send_text($sh, $text);
			$words = scws_get_tops($sh, $num);
			scws_close($sh);
		}
		else
		{
			require_once APP_ROOT_PATH.'system/scws/pscws4.class.php';
			$pscws = new PSCWS4();
			$pscws->set_dict(APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			$pscws->set_rule(APP_ROOT_PATH.'system/scws/rules.utf8.ini');
			$pscws->set_ignore(true);
			$pscws->send_text($text);
			$words = $pscws->get_tops($num);
			$pscws->close();
		}
		
		foreach($words as $word)
		{
			$list[] = $word['word'];
		}
		
		return $list;
	}
	
	public static function segments($arr,$num = 10)
	{
		$list = array();
		if(empty($text))
			return $list;
		
		$words = array();
		
		//检测是否已安装php_scws扩展
		if(function_exists("scws_open"))
		{
			$sh = scws_open();
			scws_set_charset($sh,'utf8');
			scws_set_dict($sh,APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			scws_set_rule($sh,APP_ROOT_PATH.'system/scws/rules.utf8.ini');
			scws_set_ignore($sh,true);
			foreach($arr as $key => $text)
			{
				scws_send_text($sh, $text);
				$words[] = scws_get_tops($sh, $num);
			}
			scws_close($sh);
		}
		else
		{
			require_once APP_ROOT_PATH.'system/scws/pscws4.class.php';
			$pscws = new PSCWS4();
			$pscws->set_dict(APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			$pscws->set_rule(APP_ROOT_PATH.'system/scws/rules.utf8.ini');
			$pscws->set_ignore(true);
			foreach($arr as $key => $text)
			{
				$pscws->send_text($text);
				$words[] = $pscws->get_tops($num);
			}
			$pscws->close();
		}
		
		for($i = 0;$i < $num; $i++)
		{
			foreach($words as $item)
			{
				if(isset($item[$i]))
				{
					$word = $item[$i]['word'];
					if(isset($list[$word]))
						$list[$word]++;
					else
						$list[$word] = 1;
				}
			}
		}
		
		$list = array_slice($list,0,$num);
		return array_keys($list);
	}
	
	
	/**  
	 * 文本分词
	 * @param string $text 需要分词的文本
	 * @return array
	 */
	public static function segmentAll($text) {
		$list = array ();
		if(empty($text)){
			return $list;
		}
		//检测是否已安装php_scws扩展
		if (function_exists("scws_open")){
			$sh = scws_open();
			scws_set_charset($sh, 'utf8');
			scws_set_dict($sh, APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			scws_set_rule($sh, APP_ROOT_PATH.'system/rules.utf8.ini');
			scws_set_ignore($sh, true);
			scws_send_text($sh, $text);
			while ($words = scws_get_result($sh)){
				foreach ($words as $word){
					$list[] = $word['word'];
				}
			}
			scws_close($sh);
		}else{
			require_once APP_ROOT_PATH.'system/scws/pscws4.class.php';
			$pscws = new PSCWS4();
			$pscws->set_dict(APP_ROOT_PATH.'system/scws/dict.utf8.xdb');
			$pscws->set_rule(APP_ROOT_PATH.'system/scws/rules.utf8.ini');
			$pscws->set_ignore(true);
			$pscws->send_text($text);
			while ($words = $pscws->get_result()){
				foreach ($words as $word){
					$list[] = $word['word'];
				}
			}
			$pscws->close();
		}
		return $list;
	}
	
	/**
	* utf8字符串分隔为unicode字符串
	* @param string $str 要转换的字符串
	* @param string $pre
	* @return string
	*/
	public static function segmentToUnicode($str, $pre = ''){
		$str = strtolower($str);
		$arr = array ();
		$temps = self::segmentAll($str);
		foreach ($temps as $word) {
			$temp = $pre;
			$str_len = mb_strlen($word, 'UTF-8');
			for ($i = 0; $i < $str_len; $i++){
				$s = mb_substr($word, $i, 1, 'UTF-8');
				if ($s != ' ' && $s != '　'){
					$temp .= 'ux'.self::utf8ToUnicode($s);
				}
			}
			$arr[] = $temp;
		}
		$str = self::clearSymbol($str);
		$str_len = mb_strlen($str, 'UTF-8');
		for ($i = 0; $i < $str_len; $i++){
			$s = mb_substr($str, $i, 1, 'UTF-8');
			if ($s != ' ' && $s != '　'){
				$arr[] = $pre.'ux'.self::utf8ToUnicode($s);
			}
		}
		$arr = array_unique($arr);
		return implode(' ', $arr);
	}
	
	public static function strToUnicode($str, $depart = ''){
		$str = self::clearSymbol(strtolower($str));
		$arr = array();
		$str_len = mb_strlen($str,'utf-8');
		for($i = 0;$i < $str_len;$i++){
			$s = mb_substr($str,$i,1,'utf-8');
			if($s != ' ' && $s != '　'){
				$arr[] = $depart.'ux'.self::utf8ToUnicode($s);
			}
		}
		return implode(' ',$arr);
	}
	
	/**
	 * 清除符号
	 * @param string $str 要清除符号的字符串
	 * @return string
	 */
	public static function clearSymbol($str){
		static $symbols = null;
		if($symbols === null){
			$symbols = file_get_contents(APP_ROOT_PATH.'system/table/symbol.table');
			$symbols = explode("\r\n",$symbols);
		}
		return str_replace($symbols,'',$str);
	}
	
	/**
	 * utf8字符转Unicode字符
	 * @param string $char 要转换的单字符
	 * @return void
	 */
	public static function utf8ToUnicode($char){
		switch(strlen($char)){
			case 1:
				return ord($char);
			case 2:
				$n = (ord($char[0]) & 0x3f) << 6;
				$n += ord($char[1]) & 0x3f;
				return $n;
			case 3:
				$n = (ord($char[0]) & 0x1f) << 12;
				$n += (ord($char[1]) & 0x3f) << 6;
				$n += ord($char[2]) & 0x3f;
				return $n;
			case 4:
				$n = (ord($char[0]) & 0x0f) << 18;
				$n += (ord($char[1]) & 0x3f) << 12;
				$n += (ord($char[2]) & 0x3f) << 6;
				$n += ord($char[3]) & 0x3f;
				return $n;
		}
	}
}
?>
<?php
class CIpLocation{
	var $fp;
	/** 
	 * 第一条ip索引的偏移地址
	 */
	var $firstip;
	/** 
	 * 最后一条ip索引的偏移地址
	 */
	var $lastip;
	
	/** 
	 * 总ip数
	 */
	var $totalip;
	
	/**  
	 * 构造函数
	 * @return void
	 */  
	function IpLocation()
	{
		$datfile = APP_ROOT_PATH."system/wechat/ip.table";
		$this->fp=fopen($datfile,'rb');   //二制方式打开
		$this->firstip = $this->get4b(); //第一条ip索引的绝对偏移地址
		$this->lastip = $this->get4b();  //最后一条ip索引的绝对偏移地址
		$this->totalip =($this->lastip - $this->firstip)/7 ; //ip总数 索引区是定长的7个字节,在此要除以7,
		register_shutdown_function(array($this,"closefp"));  //为了兼容php5以下版本,本类没有用析构函数,自动关闭ip库.
	}
	
	/**  
	 * 关闭ip库
	 * @return void
	 */
	function closefp()
	{
		fclose($this->fp);
	}
	
	/**  
	 * 读取4个字节并将解压成long的长模式
	 * @return long
	 */
	function get4b()
	{
		$str=unpack("V",fread($this->fp,4));
		return $str[1];
	}
	
	/**  
	 * 读取重定向了的偏移地址
	 * @return long
	 */
	function getoffset()
	{
		$str=unpack("V",fread($this->fp,3).chr(0));
		return $str[1];
	}
	
	/**  
	 * 读取ip的详细地址信息
	 * @return string
	 */
	function getstr()
	{
		$split=fread($this->fp,1);
		while (ord($split)!=0)
		{
			$str .=$split;
			$split=fread($this->fp,1);
		}
		return $str;
	}
	
	/**  
	 * 将ip通过ip2long转成ipv4的互联网地址,再将他压缩成big-endian字节序
	 * 用来和索引区内的ip地址做比较
	 * @param array $ip ip地址
	 * @return string
	 */
	function iptoint($ip)
	{
		return pack("N",intval(ip2long($ip)));
	}

	/**  
	 * 获取地址信息
	 * @return string
	 */
	function readaddress()
	{
		$now_offset=ftell($this->fp); //得到当前的指针位址
		$flag=$this->getflag();
		switch (ord($flag))
		{
			case 0:
				$address="";
			break;
			
			case 1:
			case 2:
				fseek($this->fp,$this->getoffset());
				$address=$this->getstr();
			break;
			
			default:
				fseek($this->fp,$now_offset);
				$address=$this->getstr();
			break;
		}
		return $address;
	}
	
	/**  
	 * 获取标志1或2,用来确定地址是否重定向了
	 * @return string
	 */
	function getflag()
	{
		return fread($this->fp,1);
	}
	
	/**  
	 * 用二分查找法在索引区内搜索ip
	 * @param array $ip ip地址
	 * @return int
	 */
	function searchip($ip)
	{
		$ip=gethostbyname($ip);     //将域名转成ip
		$ip_offset["ip"]=$ip;
		$ip=$this->iptoint($ip);    //将ip转换成长整型
		$firstip=0;                 //搜索的上边界
		$lastip=$this->totalip;     //搜索的下边界
		$ipoffset=$this->lastip;    //初始化为最后一条ip地址的偏移地址
		while ($firstip <= $lastip)
		{
			$i=floor(($firstip + $lastip) / 2);          //计算近似中间记录 floor函数记算给定浮点数小的最大整数,说白了就是四舍五也舍
			fseek($this->fp,$this->firstip + $i * 7);    //定位指针到中间记录
			$startip=strrev(fread($this->fp,4));         //读取当前索引区内的开始ip地址,并将其little-endian的字节序转换成big-endian的字节序
			if ($ip < $startip)
			{
				$lastip=$i - 1;
			}
			else
			{
				fseek($this->fp,$this->getoffset());
				$endip=strrev(fread($this->fp,4));
				if ($ip > $endip)
				{
					$firstip=$i + 1;
				}
				else
				{
					$ip_offset["offset"]=$this->firstip + $i * 7;
					break;
				}
			}
		}
		return $ip_offset;
	}
	
	/**  
	 * 获取ip地址详细信息
	 * @param array $ip ip地址
	 * @return array
	 */
	function getAddress($ip)
	{
		$ip_offset=$this->searchip($ip);  //获取ip 在索引区内的绝对编移地址
		$ipoffset=$ip_offset["offset"];
		$address["ip"]=$ip_offset["ip"];
		fseek($this->fp,$ipoffset);      //定位到索引区
		$address["startip"]=long2ip($this->get4b()); //索引区内的开始ip 地址
		$address_offset=$this->getoffset();            //获取索引区内ip在ip记录区内的偏移地址
		fseek($this->fp,$address_offset);            //定位到记录区内
		$address["endip"]=long2ip($this->get4b());   //记录区内的结束ip 地址
		$flag=$this->getflag();                      //读取标志字节
		switch (ord($flag))
		{
			case 1:  //地区1地区2都重定向
				$address_offset=$this->getoffset();   //读取重定向地址
				fseek($this->fp,$address_offset);     //定位指针到重定向的地址
				$flag=$this->getflag();               //读取标志字节
				switch (ord($flag))
				{
					case 2:  //地区1又一次重定向,
						fseek($this->fp,$this->getoffset());
						$address["area1"]=$this->getstr();
						fseek($this->fp,$address_offset+4);      //跳4个字节
						$address["area2"]=$this->readaddress();  //地区2有可能重定向,有可能没有
					break;
					
					default: //地区1,地区2都没有重定向
						fseek($this->fp,$address_offset);        //定位指针到重定向的地址
						$address["area1"]=$this->getstr();
						$address["area2"]=$this->readaddress();
					break;
				}
			break;
			
			case 2: //地区1重定向 地区2没有重定向
				$address1_offset=$this->getoffset();   //读取重定向地址
				fseek($this->fp,$address1_offset);  
				$address["area1"]=$this->getstr();
				fseek($this->fp,$address_offset+8);
				$address["area2"]=$this->readaddress();
			break;
			
			default: //地区1地区2都没有重定向
				fseek($this->fp,$address_offset+4);
				$address["area1"]=$this->getstr();
				$address["area2"]=$this->readaddress();
			break;
		}
		
		//*过滤一些无用数据
		if (strpos($address["area1"],"CZ88.NET")!=false)
		{
			$address["area1"]="未知";
		}
		
		if (strpos($address["area2"],"CZ88.NET")!=false)
		{
			$address["area2"]=" ";
		}
		
		foreach($address as $k=>$item)
		{
			if(!$this->is_utf8($address[$k]))
			{
				$address[$k] = iconv('gbk','utf-8',$item);
			}
		}
		return $address;
	}
	
	/**  
	 * 获取是否utf8字符串
	 * @param array $string 验证的字符串
	 * @return bool
	 */
	function is_utf8($string)
	{
		return preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
			)*$%xs', $string);
	}
}
?>
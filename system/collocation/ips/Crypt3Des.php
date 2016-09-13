<?php

/* 3DES算法类 */
class Crypt3Des {
	// 3DES密钥
	var $key = 'ICHuQplJ0YR9l7XeVNKi6FMn';
	// 3DES向量
	var $iv = '2EDxsEfp';
	
	function __construct(){
		
		$collocation_item = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."collocation where class_name='Ips'");
		$collocation_cfg = unserialize($collocation_item['config']);
		
		$this->key = $collocation_cfg['3des_key'];
		$this->iv = $collocation_cfg['3des_iv'];			
		/*
		if(trim(app_conf("IPS_3DES_KEY")) != ""){
			$this->key = trim(app_conf("IPS_3DES_KEY"));
		}
		if(trim(app_conf("IPS_3DES_IV")) != ""){
			$this->iv = trim(app_conf("IPS_3DES_IV"));
		}*/
	}
	
	function Crypt3Des() {
		if (! extension_loaded ( 'mcrypt' )) {
			echo '请安装并加载mcrypt扩展!';
			exit ();
		}
	}
	function DESEncrypt($data) {
		return base64_encode ( $this->encode ( $data ) ); // 加密
	}
	function DESDecrypt($data) {
		return $this->decode ( base64_decode ( $data ) ); // 解密
	}
	function encode($text) {
		return $this->encrypt ( $this->key, $this->iv, $text );
	}
	function decode($text) {
		return $this->decrypt ( $this->key, $this->iv, $text );
	}
	function encrypt($key, $iv, $text) {
		$key_add = 24 - strlen ( $key );
		$key .= substr ( $key, 0, $key_add );
		$text = $this->pad ( $text );
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '' );
		mcrypt_generic_init ( $td, $key, $iv );
		$encrypt_text = mcrypt_generic ( $td, $text );
		mcrypt_generic_deinit ( $td );
		mcrypt_module_close ( $td );
		
		return $encrypt_text;
	}
	function decrypt($key, $iv, $text) {
		$key_add = 24 - strlen ( $key );
		$key .= substr ( $key, 0, $key_add );
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '' );
		mcrypt_generic_init ( $td, $key, $iv );
		$text = mdecrypt_generic ( $td, $text );
		mcrypt_generic_deinit ( $td );
		mcrypt_module_close ( $td );
		
		return $this->unpad ( $text );
	}
	function pad($text) {
		$text_add = strlen ( $text ) % 8;
		for($i = $text_add; $i < 8; $i ++) {
			$text .= chr ( 8 - $text_add );
		}
		
		return $text;
	}
	function unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text )) {
			return false;
		}
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad) {
			return false;
		}
		
		return substr ( $text, 0, - 1 * $pad );
	}
}

?>
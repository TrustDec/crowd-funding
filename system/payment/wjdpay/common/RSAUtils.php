<?php

class RSAUtils {
	
	public static function encryptByPrivateKey($data) {
		$pi_key =  openssl_pkey_get_private(file_get_contents(APP_ROOT_PATH.'system/payment/jdpay/config/seller_rsa_private_key.pem'));//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
		$encrypted="";	
		openssl_private_encrypt($data,$encrypted,$pi_key,OPENSSL_PKCS1_PADDING);//私钥加密
		$encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
		return $encrypted;
	}
	
	public static function decryptByPublicKey($data) {
		$pu_key =  openssl_pkey_get_public(file_get_contents(APP_ROOT_PATH.'system/payment/jdpay/config/wy_rsa_public_key_production.pem'));//这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
		$decrypted = "";
		$data = base64_decode($data);
		openssl_public_decrypt($data,$decrypted,$pu_key);//公钥解密
        return $decrypted;
	}
	
}

//$sha256SourceSignString = hash('sha256','currency=CNY&failCallbackUrl=http://www.baidu.com&merchantNum=22294531&merchantRemark=生产环境-测试商户号&notifyUrl=http://www.jd.com&successCallbackUrl=http://www.jd.com&tradeAmount=1&tradeDescription=交易描述&tradeName=交易名称&tradeNum=222945311409580692475&tradeTime=2014-09-01 22:11:32');
//$private_key = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAI+OdmbDy8RtY3IyTPg9r5sQhe1uKtssLsURxyrpa0GMIimSCrFDJ6GAnn/JxU4mLzwVPeGVI/sY9PN1/QONoCLBQ9E1FfZvTvppKPKP7BWt1cQjrPh7hIFurA9q/AJ3pJsW3CMomWaCVwejR4Nz8jD3jo68InF3yCaJ9OU3FLdTAgMBAAECgYBp4ElE650KZx8UJzMLVvt/4wTTow/qi8CGyeDZrkPTmRXNEQ/fwsak32aGmvpw88qchpIYINXjqHloYhnUGA0E07mIRRbILSkLQlCajgVtWe9oh7nASFGpBdW/jKFrBpFldozGGhSRtehHIzni1V10ooNEnZpnEkprCA7WijNmuQJBAO1+r8NkGu2VXuV5Fjb4vG7mMpFciOZz+muUQK/DN4O9yBbBeXONUkFhXSz/aJBPwpZKlxXCGsnFsuYEeVu9No8CQQCavfxh05ll0/BVd110KHrVYAo0y3ME7fIR/NNn8SEipSo7XS0zcQS3i58phsw/VDmYs/7ZLdLUB5UKxNLgU3T9AkEAytu6cBBSu/spmqLKKdxev+9a5DUBLq+ECF4Svs7l3V6+yUkrX1soFnZ+6w+ilhm64TsHQGuTDCQVQkoyCv1c2wJAfka1s3tCvhcjFAuxhr4V5xRVn9m6xfYLSeSA/FyJBsWz3ffekBEVoVbeDrxC5xcrXVLdkItVddOuK7iMwaU5XQJAWpT+huR3L9JPR6I7b481sat0YuCIU6rLekhAN+jezKKSyGRh869MDasqCE8Eqp3HMsSVDy7rR/wJVMWtgroyMw==';
//echo $encrypted = RSAUtils::encryptByPrivateKey($sha256SourceSignString);
//$public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCqd1dbvahVELmzmGrz7D6wIB8nS8nS4';
//echo RSAUtils::decryptByPublicKey($encrypted);
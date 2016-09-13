<?php
require '../system/system_init.php';
require_once('../system/payment/wjdpay/config/config.php');//京东支付配置文件
require_once("../system/payment/wjdpay/common/DesUtils.php");
require_once("../system/payment/wjdpay/common/ConfigUtil.php");
require_once("lib/log_.php");

/**
 * 接收异步通知控制器
 *
 * @author wylitu
 *        
 */
class WebAsynNotificationCtrl{

	public function xml_to_array($xml){
		$array = (array)(simplexml_load_string ($xml));
		foreach ($array as $key => $item){
			$array[$key] = $this->struct_to_array ((array)$item);
		}
		return $array;
	}
	public function struct_to_array($item){
		if (!is_string($item)) {
			$item = (array)$item;
			foreach($item as $key => $val){
				$item [$key] = $this->struct_to_array ($val);
			}
		}
		return $item;
	}	
	/**
	 * 签名
	 */
	public function generateSign($data, $md5Key) {
		$sb = $data ['VERSION'] [0] . $data ['MERCHANT'] [0] . $data ['TERMINAL'] [0] . $data ['DATA'] [0] . $md5Key;
		return md5 ( $sb );
	}
	public function execute($md5Key, $desKey,$resp) {
		$log_ = new Log_();
		if (null == $resp) {
			return;
		}
		// 解析XML
		$params = $this->xml_to_array ( base64_decode ( $resp ) );
		$ownSign = $this->generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );
		
		// 验签成功，业务处理
		// 对Data数据进行解密
		$des = new DesUtils (); // （秘钥向量，混淆向量）
		$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
		$params ['data'] = $decryptArr;
		if ($params ['SIGN'] [0] == $ownSign) {
			$xml_array=$this->xml_to_array($decryptArr);
			if($xml_array !=''&&$xml_array['RETURN']['CODE'] =='0000'){
					$payment_notice_sn = $xml_array['TRADE']['ID'];
					$outer_notice_sn = $xml_array['TRADE']['DATE'];
					$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");		
					require_once APP_ROOT_PATH."system/libs/cart.php";
					$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
					/*$log_->log_result("付款单号:".$payment_notice_sn."\n");
					$log_->log_result("项目名称".$xml_array['TRADE']['NOTE']."\n");
					$log_->log_result("支付状态".$xml_array['RETURN']['DESC']."\n\r");*/
				return '200';
			}
		} else {
			return;
		}
		return ;
	}
}

 $MD5_KEY = ConfigUtil::get_val_by_key ( "md5Key" );
 $DES_KEY = ConfigUtil::get_val_by_key ( "desKey" );
 $w = new WebAsynNotificationCtrl();
 $w->execute($MD5_KEY,$DES_KEY,$_POST["resp"]);

?>





















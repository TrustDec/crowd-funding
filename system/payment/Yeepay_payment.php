<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'易宝支付',
	'yeepay_account'	=>	'商户编号',
	'yeepay_key'	=>	'商户密钥',
);
$config = array(
	'yeepay_account'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户编号
	'yeepay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户密钥 
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Yeepay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    
    $module['reg_url'] = 'http://www.yeepay.com/';
    
    return $module;
}

// 易宝支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Yeepay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{		
		
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		//$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

		
		$data_return_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=response&class_name=Yeepay';
		
		
        $data_merchant_id =  trim($payment_info['config']['yeepay_account']);
        $data_order_id    = $payment_notice['notice_sn'];
        $data_amount      = $money;
        $message_type     = 'Buy';
        $data_cur         = 'CNY';
        if($payment_notice['deal_id']==0 && $payment_notice['deal_item_id']==0 && $payment_notice['deal_name']==''){
        	$product_id   = "充值";
        }else{
        	$product_id       = $payment_notice['deal_name'];
        }
        $product_cat      = '';
        $product_desc     = '';
        $address_flag     = '0';

		
        $data_pay_key     = trim($payment_info['config']['yeepay_key']);
        $data_pay_account = trim($payment_info['config']['yeepay_account']);
        $mct_properties   = $payment_notice['notice_sn'];
        $def_url = $message_type . $data_merchant_id . $data_order_id . $data_amount . $data_cur . $product_id . $product_cat
                             . $product_desc . $data_return_url . $address_flag . $mct_properties ;
        $MD5KEY = $this->HmacMd5($def_url, $data_pay_key);

        $code  = "\n<form action='https://www.yeepay.com/app-merchant-proxy/node' method='post' id='jumplink' style='text-align:center;' accept-charset='gbk'>\n";
        $code .= "<input type='hidden' name='p0_Cmd' value='".$message_type."'>\n";
        $code .= "<input type='hidden' name='p1_MerId' value='".$data_merchant_id."'>\n";
        $code .= "<input type='hidden' name='p2_Order' value='".$data_order_id."'>\n";
        $code .= "<input type='hidden' name='p3_Amt' value='".$data_amount."'>\n";
        $code .= "<input type='hidden' name='p4_Cur' value='".$data_cur."'>\n";
        $code .= "<input type='hidden' name='p5_Pid' value='".$product_id."'>\n";
        $code .= "<input type='hidden' name='p6_Pcat' value='".$product_cat."'>\n";
        $code .= "<input type='hidden' name='p7_Pdesc' value='".$product_desc."'>\n";
        $code .= "<input type='hidden' name='p8_Url' value='".$data_return_url."'>\n";
        $code .= "<input type='hidden' name='p9_SAF' value='".$address_flag."'>\n";
        $code .= "<input type='hidden' name='pa_MP' value='".$mct_properties."'>\n";
        $code .= "<input type='hidden' name='pd_FrpId' value=''>\n";
        $code .= "<input type='hidden' name='pd_NeedResponse' value='1'>\n";
        $code .= "<input type='hidden' name='hmac' value='".$MD5KEY."'>\n";
		
		if(!empty($payment_info['logo']))
			$code .= "<input type='image' src='".APP_ROOT.$payment_info['logo']."' style='border:solid 1px #ccc;margin-bottom:10px'><div class='blank'></div>";
			
        $code .= "<input type='submit' class='paybutton' value='前往易宝在线支付'>";
		
        $code .= "正在连接支付接口...</form>\n";
		$code.="<script type='text/javascript'>document.getElementById('jumplink').submit();</script>";

		//$code.="<div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
		
        return $code;

	}
	
	public function response($request)
	{

		//file_put_contents("./system/payment/log/yeepay_".strftime("%Y%m%d%H%M%S",time()).".txt",print_r($request,true));
		
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Yeepay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */        
    	$merchant_id    = $payment['config']['yeepay_account'];       // 获取商户编号
        $merchant_key   = $payment['config']['yeepay_key'];           // 获取秘钥

        $message_type   = trim($request['r0_Cmd']);
        $succeed        = trim($request['r1_Code']);   // 获取交易结果,1成功,-1失败
        $trxId          = trim($request['r2_TrxId']);  //易宝的交易流水号
        
        $amount         = trim($request['r3_Amt']);    // 获取订单金额
        $cur            = trim($request['r4_Cur']);    // 获取订单货币单位
        $product_id     = trim($request['r5_Pid']);    // 获取产品ID
        $orderid        = trim($request['r6_Order']);  // 获取订单ID
        $userId         = trim($request['r7_Uid']);    // 获取产品ID
        $merchant_param = trim($request['r8_MP']);     // 获取商户私有参数
        $bType          = trim($request['r9_BType']);  // 获取订单ID

        $mac            = trim($request['hmac']);      // 获取安全加密串

        ///生成加密串,注意顺序
        $ScrtStr  = $merchant_id . $message_type . $succeed . $trxId . $amount . $cur . $product_id .
                      $orderid . $userId . $merchant_param . $bType;
        $mymac    = $this->HmacMd5($ScrtStr, $merchant_key);
    	
    	
		$payment_notice_sn = $orderid;
    	$money = $amount;
    	$outer_notice_sn = $trxId;
    	
		if (strtoupper($mac) == strtoupper($mymac))
		{					
			if($succeed=="1")	
			{
								
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice_sn,$outer_notice_sn);	

				$is_paid = intval($GLOBALS['db']->getOne("select is_paid from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'"));
				if ($is_paid == 1){
					if($bType=="2"){echo "success";	exit;}
					app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['id']))); //支付成功
				}else{
					app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
				}
			}
			else
			{
				showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
			}
		}else{
		    showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
		}   
	}
	
	public function notify($request)
	{
		return false;
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Yeepay'");
		if($payment_item)
		{
			$html = "<div style='float:left;'>".
					"<input type='radio' name='payment' value='".$payment_item['id']."' />&nbsp;".
					$payment_item['name'].
					"：</div>";
			if($payment_item['logo']!='')
			{
				$html .= "<div style='float:left; padding-left:10px;'><img src='".APP_ROOT.$payment_item['logo']."' /></div>";
			}
			$html .= "<div style='float:left; padding-left:10px;'>".nl2br($payment_item['description'])."</div>";
			return $html;
		}
		else
		{
			return '';
		}
	}
		
	
	private function HmacMd5($data,$key)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing(NOTE: Hacked means written)
		
		//需要配置环境支持iconv，否则中文参数不能正常处理
//		$key = iconv("GB2312","UTF-8",$key);
//		$data = iconv("GB2312","UTF-8",$data);
		
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		
		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
}
?>
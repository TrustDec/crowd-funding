<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'财付通支付',
	'tencentpay_id'	=>	'商户ID',
	'tencentpay_key'	=>	'商户密钥',
	'tencentpay_sign'	=>	'自定义签名',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'GO_TO_PAY'	=>	'前往财付通支付',
);
$config = array(
	'tencentpay_id'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户ID
	'tencentpay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户密钥
	'tencentpay_sign'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //自定义签名
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Tenpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 余额支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Tenpay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		require APP_ROOT_PATH."system/payment/Tenpay/classes/RequestHandler.class.php";
		
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
		$subject = $payment_notice['deal_name']==""?"充值".format_price($payment_notice['money']):$payment_notice['deal_name'];
		
		$data_return_url = get_domain().APP_ROOT.'/index.php?ctl=payment&act=response&class_name=Tenpay';
		$data_notify_url = get_domain().APP_ROOT.'/index.php?ctl=payment&act=notify&class_name=Tenpay';

        $cmd_no = '1';

        /* 获得订单的流水号，补零到10位 */
        $sp_billno = $payment_notice_id;

        $spbill_create_ip =  $_SERVER['REMOTE_ADDR'];
        
        /* 交易日期 */
        $today = to_date($payment_notice['create_time'],'YmdHis');


        /* 将商户号+年月日+流水号 */
        $out_trade_no = $payment_notice['notice_sn'];

        /* 银行类型:支持纯网关和财付通 */
        $bank_type = '0';


        $desc = $subject;
        $attach = $payment_info['config']['tencentpay_sign'];

		
        /* 返回的路径 */
        $return_url = $data_return_url;

        /* 总金额 */
        $total_fee = $money*100;

        /* 货币类型 */
        $fee_type = '1';

        /* 重写自定义签名 */
        //$payment['magic_string'] = abs(crc32($payment['magic_string']));

        /* 数字签名 */
        /*$sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment_info['config']['tencentpay_id'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $sp_billno .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $attach . "&spbill_create_ip=" . $spbill_create_ip ."&key=" . $payment_info['config']['tencentpay_key'];
        $sign = strtoupper(md5($sign_text));*/

        $reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($payment_info['config']['tencentpay_key']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
		
		//----------------------------------------
		//设置支付参数 
		//----------------------------------------
		$reqHandler->setParameter("partner", $payment_info['config']['tencentpay_id']);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee);  //总金额
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $data_notify_url);
		$reqHandler->setParameter("body", $desc);
		$reqHandler->setParameter("bank_type", $bank_type);  	  //银行类型，默认为财付通
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", get_client_ip());//客户端IP
		$reqHandler->setParameter("fee_type", $fee_type);               //币种
		$reqHandler->setParameter("subject",$desc);          //商品名称，（中介交易时必填）
		
		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号
		
		//业务可选参数
		$reqHandler->setParameter("attach", $attach);             	  //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", "");        	  //商品费用
		$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
		$reqHandler->setParameter("time_start", $today);  //订单生成时间
		$reqHandler->setParameter("time_expire", "");             //订单失效时间
		$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		$reqHandler->setParameter("goods_tag", "");               //商品标记
		$reqHandler->setParameter("trade_mode",$cmd_no);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter("transport_desc","");              //物流说明
		$reqHandler->setParameter("trans_type","1");              //交易类型
		$reqHandler->setParameter("agentid","");                  //平台ID
		$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter("seller_id","");                //卖家的商户号
		
		
		
		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();
		if($_REQUEST['v']==1){
			$debugInfo = $reqHandler->getDebugInfo();
			echo "<br/>" . $reqUrl . "<br/>";
			echo "<br/>" . $debugInfo . "<br/>";
		}
		

		
		$payLinks = '<form style="text-align:center;" id="jumplink" action="'.$reqHandler->getGateUrl().'" target="_self" style="margin:0px;padding:0px" method="post" >';
		$params = $reqHandler->getAllParameters();
		foreach($params as $k => $v) {
			$payLinks.="<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
		}

        $payLinks .= "正在连接支付接口...</form>";
      	$payLinks.='<script type="text/javascript">document.getElementById("jumplink").submit();</script>';
        return $payLinks;
	}
	
	public function response($request)
	{
		unset($_POST['city']);
		unset($_GET['city']);
		
		
		unset($_POST['ctl']);
		unset($_GET['ctl']);
		
		unset($_POST['act']);
		unset($_GET['act']);
		
		unset($_POST['class_name']);
		unset($_GET['class_name']);
		require (APP_ROOT_PATH."system/payment/Tenpay/classes/ResponseHandler.class.php");
		require (APP_ROOT_PATH."system/payment/Tenpay/classes/function.php");
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Tenpay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        $resHandler = new ResponseHandler();
		$resHandler->setKey($payment['config']['tencentpay_key']);

        //判断签名
		if($resHandler->isTenpaySign())
		{
			//通知id
			$notify_id = $resHandler->getParameter("notify_id");
			//商户订单号
			$out_trade_no = $resHandler->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");
						
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."' ");
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['notice_sn'],$transaction_id);						
			showSuccess($rs['info'],0,$rs['jump'],1);
		}else{
		    showErr("支付失败",0,url("index"),1);
		}   
	}
	
	public function notify($request)
	{
		unset($_POST['city']);
		unset($_GET['city']);
		
		
		unset($_POST['ctl']);
		unset($_GET['ctl']);
		
		unset($_POST['act']);
		unset($_GET['act']);
		
		unset($_POST['class_name']);
		unset($_GET['class_name']);
		require (APP_ROOT_PATH."system/payment/Tenpay/classes/ResponseHandler.class.php");
		require (APP_ROOT_PATH."system/payment/Tenpay/classes/function.php");
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Tenpay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        $resHandler = new ResponseHandler();
		$resHandler->setKey($payment['config']['tencentpay_key']);

        //判断签名
		if($resHandler->isTenpaySign())
		{
			//通知id
			$notify_id = $resHandler->getParameter("notify_id");
			//商户订单号
			$out_trade_no = $resHandler->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");
						
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."' ");
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['notice_sn'],$transaction_id);						
			echo "success";
		}else{
		    echo "fail";
		}  
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Tenpay'");
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
}
?>
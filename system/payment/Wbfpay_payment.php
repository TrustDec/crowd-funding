<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'宝付支付(WAP版本)',
	'MemberID'	=>	'商户号',
	'TerminalID'	=>	'终端号',
	'Md5key'	=>	'密钥',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'GO_TO_PAY'	=>	'前往宝付在线支付',
	
	'wbfpay_gateway'	=>	'支持的银行',

	'wbfpay_gateway_4020001'=>'招商银行(借)',
	'wbfpay_gateway_4020003'=>'建设银行(借)',
	'wbfpay_gateway_4020020'=>'交通银行(借)',
	'wbfpay_gateway_4020026'=>'中国银行(借)',
	'wbfpay_gateway_4020080'=>'银联在线(借)',		
	
	'wbfpay_gateway_4030001'=>'招商银行(贷)',
	'wbfpay_gateway_4030003'=>'建设银行(贷)',
	'wbfpay_gateway_4030020'=>'交通银行(贷)',
	'wbfpay_gateway_4030026'=>'中国银行(贷)',
	'wbfpay_gateway_4030080'=>'银联在线(贷)',		
			

	'4020001'=>'招商银行(借)',
	'4020003'=>'建设银行(借)',
	'4020020'=>'交通银行(借)',
	'4020026'=>'中国银行(借)',
	'4020080'=>'银联在线(借)',		
	
	'4030001'=>'招商银行(贷)',
	'4030003'=>'建设银行(贷)',
	'4030020'=>'交通银行(贷)',
	'4030026'=>'中国银行(贷)',
	'4030080'=>'银联在线(贷)',		
	
);
$config = array(
	'MemberID'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户编号
	'TerminalID'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //终端号
	'Md5key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //密钥
	'wbfpay_gateway'	=>	array(
		'INPUT_TYPE'	=>	'3',
		'VALUES'	=>	array(				
				'4020001',//招商银行(借)
				'4020003',//建设银行(借)
				'4020020',//交通银行(借)
				'4020026',//中国银行(借)
				'4020080',//银联在线(借)		
				
				'4030001',//招商银行(贷)
				'4030003',//建设银行(贷)
				'4030020',//交通银行(贷)
				'4030026',//中国银行(贷)
				'4030080',//银联在线(贷)	
			)
	), //可选的银行网关
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Wbfpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];

   /* 支付方式：1：在线支付；0：线下支付;2:手机wap;3:手机sdk */
    $module['online_pay'] = '2';
    
    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 宝付支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');

class Wbfpay_payment implements payment {

	public function get_payment_code($payment_notice_id) 
	{
	
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = intval(round($payment_notice['money'],2)*100);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

		$config_ini = array(
		'InterfaceVersion' =>"4.0",//接口版本号Int ,现接口版本为 4.0
		'PageUrl' =>get_domain().APP_ROOT.'/wbfpay_web/wmerchant_url.php',//页面通知地址String,需 URL 编码，长度不超过 255 位
		'ReturnUrl' =>get_domain().APP_ROOT.'/wbfpay_web/return_url.php',//服务器通知地址String,需 URL 编码，长度不超过 255 位
		'NoticeType' =>"1",//通知方式 Int,1：服务器通知和页面通知。支付成功后，自动重定向到“页面通知地址”0：只发服务器端通知，不跳转
		'KeyType' =>"1",//加密类型 是 Int,1：md5;KeyType=1
		);
		
		$param = array();
		$param["MemberID"] = $payment_info['config']["MemberID"];//商户号
		$param["TerminalID"] = $payment_info['config']["TerminalID"];//终端号
		$param["InterfaceVersion"] = $config_ini['InterfaceVersion'];//接口版本
		$param["PayID"] =$payment_notice['bank_id'];//支付 ID		
		$param["merchantRemark"] =app_conf("SITE_NAME");//商户备注 string(64)
		$param["TransID"]=$payment_notice['notice_sn'];//流水号
		$param["TradeDate"]=date("Ymdhis",$payment_notice['create_time']);
		$param["OrderMoney"]=$money;//订单金额
		$param["Amount"]=1;//商品数量
		$param["Username"]=$payment_notice['username'];//支付用户名

		$param["PageUrl"]=$config_ini['PageUrl'];//通知商户页面端地址
		$param["ReturnUrl"]=$config_ini['ReturnUrl'];//服务器底层通知地址
		$param["NoticeType"]=$config_ini['NoticeType'];//通知类型	
		$param["Md5key"]=$payment_info['config']["Md5key"];;//md5密钥（KEY）abcdefg
		$param["MARK"] = "|";
		//MD5签名格式
		$param["Signature"]=md5($param["MemberID"].$param["MARK"].$param["PayID"].$param["MARK"].$param["TradeDate"].$param["MARK"].$param["TransID"].$param["MARK"].$param["OrderMoney"].$param["MARK"].$param["PageUrl"].$param["MARK"].$param["ReturnUrl"].$param["MARK"].$param["NoticeType"].$param["MARK"].$param["Md5key"]);
		//return($param["MemberID"].$param["MARK"].$param["PayID"].$param["MARK"].$param["TradeDate"].$param["MARK"].$param["TransID"].$param["MARK"].$param["OrderMoney"].$param["MARK"].$param["PageUrl"].$param["MARK"].$param["ReturnUrl"].$param["MARK"].$param["NoticeType"].$param["MARK"].$param["Md5key"]); 
		//return($param["Signature"]); 
		$param["payUrl"]="https://tgw.baofoo.com/wapmobile";//借贷混合
		$param["InterfaceVersion"] = $config_ini['InterfaceVersion'];
		$param["KeyType"] =$config_ini['KeyType'];//通知类型;
		$_SESSION['OrderMoney']=$param["OrderMoney"]; //设置提交金额的Session
				
		if($payment_notice['is_mortgate'] >0)//表诚意金
		{
			$param["ProductName"] = '诚意金支付';//交易描述String(1024)
			$param["AdditionalInfo"] = '诚意金支付';//交易名称String（256）
		}elseif($payment_notice['is_mortgate'] ==0 && $payment_notice['deal_id'] ==0){//表充值
			$param["ProductName"] = '充值支付';//交易描述String(1024)
			$param["AdditionalInfo"] = '充值支付';//交易名称String（256）
		}
		else
		{
			$param["ProductName"]=$payment_notice['deal_name'];//产品名称(交易描述String(1024))
			$param["AdditionalInfo"]= msubstr($payment_notice['deal_name'],0,256);//订单附加消息(交易名称String（256）)
		}

		$payLinks ="<form name='form1' method='post' action='".$param['payUrl']."' id='payForm'>";
        $payLinks .="<input type='hidden' name='MemberID' value='".$param['MemberID']."' />";
		$payLinks .="<input type='hidden' name='TerminalID' value='".$param['TerminalID']."'/>";
		$payLinks .="<input type='hidden' name='InterfaceVersion' value='".$param['InterfaceVersion']."'/>";
		$payLinks .="<input type='hidden' name='KeyType' value='".$param['KeyType']."'/>";
        $payLinks .="<input type='hidden' name='PayID' value='".$param['PayID']."' />";
        $payLinks .="<input type='hidden' name='TradeDate' value='".$param['TradeDate']."' />";
        $payLinks .="<input type='hidden' name='TransID' value='".$param['TransID']."' />";
        $payLinks .="<input type='hidden' name='OrderMoney' value='".$param['OrderMoney']."' />";
        $payLinks .="<input type='hidden' name='ProductName' value='".$param['ProductName']."' />";
        $payLinks .="<input type='hidden' name='Amount' value='".$param['Amount']."' />";
        $payLinks .="<input type='hidden' name='Username' value='".$param['Username']."' />";
        $payLinks .="<input type='hidden' name='AdditionalInfo' value='".$param['AdditionalInfo']."' />";
        $payLinks .="<input type='hidden' name='PageUrl' value='".$param['PageUrl']."' />";
        $payLinks .="<input type='hidden' name='ReturnUrl' value='".$param['ReturnUrl']."' />";
        $payLinks .="<input type='hidden' name='Signature' value='".$param['Signature']."' />";
		$payLinks .="<input type='hidden' name='NoticeType' value='".$param['NoticeType']."' />";
		$payLinks .="</form>";
		$payLinks.='<script type="text/javascript">document.getElementById("payForm").submit();</script>';
        return($payLinks);
	}
	public function response($request)
	{
		
	}
	
	public function notify($request)
	{
		
	}
	
	public function get_display_code()
	{
		
	}
	public function get_pay_id()
	{
		global $payment_lang;
		$payment_info = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Wbfpay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		return $payment_info['config']['wbfpay_gateway'];	
	}
}

?>
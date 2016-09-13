<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'宝付支付',
	'MemberID'	=>	'商户号',
	'TerminalID'	=>	'终端号',
	'Md5key'	=>	'密钥',
	
	'bfpay_gateway'	=>	'支持的银行',

	'bfpay_gateway_3001'=>'招商银行(借)',
	'bfpay_gateway_3002'=>'中国工商银行（借）',
	'bfpay_gateway_3003'=>'中国建设银行（借）',
	'bfpay_gateway_3004'=>'上海浦东发展银行（借）',
	'bfpay_gateway_3005'=>'中国农业银行（借）',	
	'bfpay_gateway_3006'=>'中国民生银行（借）',	
	'bfpay_gateway_3009'=>'兴业银行（借）',
	'bfpay_gateway_3020'=>'中国交通银行（借）',
	'bfpay_gateway_3022'=>'中国光大银行（借）',
	'bfpay_gateway_3026'=>'中国银行（借）',

	'bfpay_gateway_3032'=>'北京银行（借）',	
	'bfpay_gateway_3035'=>'平安银行（借）',
	'bfpay_gateway_3036'=>'广发银行|CGB（借）',
	'bfpay_gateway_3037'=>'上海农商银行（借） ',
	'bfpay_gateway_3038'=>'中国邮政储蓄银行（借）',
	'bfpay_gateway_3039'=>'中信银行（借）',	
	'bfpay_gateway_3050'=>'华夏银行（借）',
	'bfpay_gateway_3059'=>'上海银行（借）',
	'bfpay_gateway_3060'=>'北京农商银行（借）',
	'bfpay_gateway_3080001'=>'银联无卡支付（借）',

	'3001'=>'招商银行(借)',
	'3002'=>'中国工商银行（借）',
	'3003'=>'中国建设银行（借）',
	'3004'=>'上海浦东发展银行（借）',
	'3005'=>'中国农业银行（借）',	
	'3006'=>'中国民生银行（借）',	
	'3009'=>'兴业银行（借）',
	'3020'=>'中国交通银行（借）',
	'3022'=>'中国光大银行（借）',
	'3026'=>'中国银行（借）',

	'3032'=>'北京银行（借）',	
	'3035'=>'平安银行（借）',
	'3036'=>'广发银行|CGB（借）',
	'3037'=>'上海农商银行（借） ',
	'3038'=>'中国邮政储蓄银行（借）',
	'3039'=>'中信银行（借）',	
	'3050'=>'华夏银行（借）',
	'3059'=>'上海银行（借）',
	'3060'=>'北京农商银行（借）',
	'3080001'=>'银联无卡支付（借）',	
	
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
	'bfpay_gateway'	=>	array(
		'INPUT_TYPE'	=>	'3',
		'VALUES'	=>	array(				
				'3001',//招商银行(借)',
				'3002',//中国工商银行（借）',
				'3003',//中国建设银行（借）',
				'3004',//上海浦东发展银行（借）',
				'3005',//中国农业银行（借）',	
				'3006',//中国民生银行（借）',	
				'3009',//兴业银行（借）',
				'3020',//中国交通银行（借）',
				'3022',//中国光大银行（借）',
				'3026',//中国银行（借）',
			
				'3032',//北京银行（借）',	
				'3035',//平安银行（借）',
				'3036',//广发银行|CGB（借）',
				'3037',//上海农商银行（借） ',
				'3038',//中国邮政储蓄银行（借）',
				'3039',//中信银行（借）',	
				'3050',//华夏银行（借）',
				'3059',//上海银行（借）',
				'3060',//北京农商银行（借）',
				'3080001',//银联无卡支付（借）',	
			)
	), //可选的银行网关
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Bfpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];

   /* 支付方式：1：在线支付；0：线下支付;2:手机wap;3:手机sdk */
    $module['online_pay'] = '1';
    
    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 宝付支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');

class Bfpay_payment implements payment {

	public function get_payment_code($payment_notice_id) 
	{
	
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = intval(round($payment_notice['money'],2)*100);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

		$config_ini = array();
		include(APP_ROOT_PATH.'system/payment/Bfpay/config.php');//宝付支付配置文件
		
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
		$param["payUrl"]="https://tgw.baofoo.com/payindex";
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
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Bfpay'");
		if($payment_item)
		{
			$payment_cfg = unserialize($payment_item['config']);
			$html="<div>宝付银行直连支付</div>";
			$html .= "<style type='text/css'>.bfbank_types{float:left; display:block; background:url(".get_domain().APP_ROOT."/system/payment/Bfpay/banklogo.gif); font-size:0px; width:150px; height:10px; text-align:left; padding:15px 0px;}";
	        $html .=".bk_type3001{background-position:15px -444px; }";  //招行
	        $html .=".bk_type3002{background-position:15px -404px; }";  //工行
	        $html .=".bk_type3003{background-position:15px -84px; }"; //建行
	        $html .=".bk_type3004{background-position:15px -364px; }"; //上海浦东发展银行
	        $html .=".bk_type3005{background-position:15px -44px; }"; //农行
	        $html .=".bk_type3006{background-position:15px -164px; }"; //民生银行
	        $html .=".bk_type3009{background-position:15px -484px; }"; //兴业银行
	        $html .=".bk_type3020{background-position:15px -204px; }"; //交通银行
	        $html .=".bk_type3022{background-position:15px -124px; }"; //光大银行
	        $html .=".bk_type3026{background-position:15px -939px; }"; //中国银行
	        $html .=".bk_type3032{background-position:15px -610px; }"; //北京银行
	        $html .=".bk_type3035{background-position:15px -903px; }"; //平安银行
	        $html .=".bk_type3036{background-position:15px -244px; }"; //广发银行|CGB
	        $html .=".bk_type3037{background-position:15px -610px; }"; //上海农商银行（借）
	        $html .=".bk_type3038{background-position:15px -524px; }"; //中国邮政储蓄银行
	        $html .=".bk_type3039{background-position:15px -284px; }"; //中信银行
	        $html .=".bk_type3050{background-position:15px -610px; }"; //华夏银行（借）
	        $html .=".bk_type3059{background-position:15px -610px; }"; //上海银行（借）
	        $html .=".bk_type3060{background-position:15px -610px; }"; //北京农商银行（借）
	        $html .=".bk_type3080001{background-position:15px -610px; }"; //银联无卡支付（借）
	        $html .="</style>";
        	$html .="<script type='text/javascript'>function set_bank(bank_id)";
			$html .="{";
			$html .="$(\"input[name='bank_id']\").val(bank_id);";
			$html .="}</script>";
			foreach ($payment_cfg['bfpay_gateway'] AS $key=>$val)
	        {
	            $html  .= "<label class='alibank_types bk_type".$key."'><input type='radio' name='payment' value='".$payment_item['id']."' rel='".$key."' onclick='set_bank(\"".$key."\")' /></label>";
	        }
	        $html .= "<input type='hidden' name='bank_id' />";
			return $html;
		}
		else
		{
			return '';
		}
	}
}

?>
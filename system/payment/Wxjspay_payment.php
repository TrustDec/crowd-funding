<?php
// +----------------------------------------------------------------------
// | EaseTHINK 易想团购系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.easethink.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'微信支付(PC扫码支付)',
	'appid'	=>	'微信公众号ID',
	'appsecret'=>'微信公众号SECRT',
	'mchid'	=>	'微信支付MCHID',
  //	'partnerid'	=>	'商户ID',
	//'partnerkey'	=>	'商户key',
	'key'	=>	'商户支付密钥Key/api秘钥',
	//'sslcert'=>'apiclient_cert证书路径',
	//'sslkey'=>'apiclient_key证书路径',
);
$config = array(
	'appid'=>array(
		'INPUT_TYPE'=>'0',
	),//微信公众号ID
	'appsecret'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //微信公众号SECRT
	'mchid'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //微信支付MCHID

	'key'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户支付密钥Key
//	'sslcert'	=>	array(
//		'INPUT_TYPE'	=>	'0',
//	), //apiclient_cert证书路径
//	'sslkey'	=>	array(
//		'INPUT_TYPE'	=>	'0',
//	), //apiclient_key证书路径

);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Wxjspay';

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

// 支付宝手机支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Wxjspay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		
		$payment_notice = $GLOBALS['db']->getRow("select pn.*,dorder.num,dorder.order_status from ".DB_PREFIX."payment_notice as pn left join ".DB_PREFIX."deal_order as dorder on pn.order_id = dorder.id   where pn.id = ".$payment_notice_id);
		$order_sn = $payment_notice['notice_sn'];
 		$money = round($payment_notice['money'],2)*100;
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
		$wx_config=$payment_info['config'];
		$sql = "select * ".
			"from ".DB_PREFIX."deal ".
			"where id =". intval($payment_notice['deal_id']);
		$deal =$GLOBALS['db']->getRow($sql);
		$data_info = $deal;
		$data_info['status'] = 1;
		$data_info['order_id'] = $payment_notice['order_id'];
		$data_info['num'] = $payment_notice['num'];
		$data_info['money'] = $payment_notice['money'];
		$data_info['order_status'] =  $payment_notice['order_status'];
		$data_info['ajaxurl'] = url("ajax#get_payment_status",array('order_id'=>$data_info['order_id']));
		$title_name = $deal['name'];
		include(APP_ROOT_PATH."system/payment/Wxjspay/WxPayPubHelper.php");
		$data = array();
		$data['appid'] = $wx_config['appid'];
		$data['mch_id'] = $wx_config['mchid'];
		//$data['nonce_str'] = '';
		//$data['sign'] = '';
		$data['body'] = $title_name;
		$data['out_trade_no'] = $order_sn;
		$data['total_fee'] = $money;
		require_once APP_ROOT_PATH.'system/extend/ip.php';
		$iplocation = new iplocate();
		$data['spbill_create_ip'] = $iplocation->getIP();
		$notify_url = get_domain().APP_ROOT."/wxpay_web/notify_url.php?order_id=".intval($payment_notice['order_id'])."&out_trade_no=".$order_sn;//."&out_trade_no={$data.walipay.out_trade_no}";
		$data['notify_url'] = $notify_url;
		$data_info['notify_url'] =  $notify_url;
		$data['trade_type'] = 'NATIVE';
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->update_config($wx_config['appid'],$wx_config['appsecret'],$wx_config['mchid'],$wx_config['partnerid'],$wx_config['partnerkey'],$wx_config['key'],$wx_config['sslcert'],$wx_config['sslkey']);
		$unifiedOrder->setParameter("input_charset", "GBK");
		foreach($data as $k=>$v){
			if($v){
				$unifiedOrder->setParameter($k,$v);
			}
		}
		$result = $unifiedOrder->getResult();

		if($result['return_code']=='SUCCESS'){
			if($result['result_code']=='SUCCESS'){
 				$data_info['code_url'] = $result['code_url'];
			}else{
				$error_info = '' ;
				switch($result['err_code']){
					case 'NOAUTH':
						$error_info = $result['err_code_des'].',原因：请商户前往申请此接口权限';
						break;
					case 'NOTENOUGH':
						$error_info = $result['err_code_des'].',原因：用户帐号余额不足，请用户充值或更换支付卡后再支付';
						break;
					case 'ORDERPAID':
						$error_info = $result['err_code_des'].',原因：商户订单已支付，无需更多操作';
						break;
					case 'ORDERCLOSED':
						$error_info = $result['err_code_des'].',原因：当前订单已关闭，请重新下单';
						break;
					case 'SYSTEMERROR':
						$error_info = $result['err_code_des'].',原因：系统异常，请用相同参数重新调用单';
						break;
					case 'APPID_NOT_EXIST':
						$error_info = $result['err_code_des'].',原因：请检查APPID是否正确';
						break;
					case 'MCHID_NOT_EXIST':
						$error_info = $result['err_code_des'].',原因：请检查MCHID是否正确';
						break;
					case 'APPID_MCHID_NOT_MATCH':
						$error_info = $result['err_code_des'].',原因：请确认appid和mch_id是否匹配';
						break;
					case 'LACK_PARAMS':
						$error_info = $result['err_code_des'].',原因：请检查参数是否齐全';
						break;
					case 'OUT_TRADE_NO_USED':
						$error_info = $result['err_code_des'].',原因：请核实商户订单号是否重复提交';
						break;
					case 'SIGNERROR':
						$error_info = $result['err_code_des'].',原因：请检查签名参数和方法是否都符合签名算法要求';
						break;
					case 'XML_FORMAT_ERROR':
						$error_info = $result['err_code_des'].',原因：请检查XML参数格式是否正确';
						break;
					case 'REQUIRE_POST_METHOD':
						$error_info = $result['err_code_des'].',原因：请检查请求参数是否通过post方法提交';
						break;
					case 'POST_DATA_EMPTY':
						$error_info = $result['err_code_des'].',原因：请检查post数据是否为空';
						break;
					case 'NOT_UTF8':
						$error_info = $result['err_code_des'].',原因：请使用NOT_UTF8编码格式';
						break;
				}
				$data_info['status']=0;
				$data_info['info']=$error_info;

			}
		}else{
			$data_info['status']=0;
			$data_info['info']=$result['return_msg'];
		}
		$GLOBALS['tmpl']->assign('data',$data_info);
		$GLOBALS['tmpl']->display("wx_payment.html");
	}
	
	public function response($request)
	{	
							
	}
	
	public function notify($request){
		
	}

	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wxjspay'");
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
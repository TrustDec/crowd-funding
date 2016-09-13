<?php
	//header ( 'Content-type:text/html;charset=utf-8' );
	require_once('./config_wap.php');//银联wap配置文件
	
	$payment_notice_id=intval($_REQUEST['payment_notice_id']);
	
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);		
	$money = round($payment_notice['money'],2)*100;
	$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
	$payment_info['config'] = unserialize($payment_info['config']);
	
    $parameter = array(
        'version' => '5.0.0',				//版本号
		'encoding' => 'utf-8',				//编码方式
		'certId' => getSignCertId(),			//证书ID
		'txnType' => '01',				//交易类型	
		'txnSubType' => '01',				//交易子类
		'bizType' => '000201',				//业务类型
		'frontUrl' =>  SDK_FRONT_NOTIFY_URL,  		//前台通知地址
		'backUrl' => SDK_BACK_NOTIFY_URL,		//后台通知地址	
		'signMethod' => '01',		//签名方法
		'channelType' => '08',		//渠道类型，07-PC，08-手机
		'accessType' => '0',		//接入类型
		'merId' => $payment_info['config']['merId'], //商户代码，请改自己的商户号
		'orderId' => $payment_notice['notice_sn'],	//商户订单号
		'txnTime' => to_date($payment_notice['create_time'],'YmdHis'),	//订单发送时间
		'txnAmt' => $money,		//交易金额，单位分
		'currencyCode' => '156',	//交易币种
		//'defaultPayType' => '0001',	//默认支付方式	
		'reqReserved' =>$payment_notice_id, //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
    );
   
    //签名
	sign($parameter);
	// 前台请求地址
	$front_uri = SDK_FRONT_TRANS_URL;
    $payLinks = create_html ( $parameter, $front_uri );
    /**
    $log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
	$log->LogInfo ( "前台请求地址为>" . $front_uri );
	$log->LogInfo ( "-------前台交易自动提交表单>--begin----" );
	$log->LogInfo ( $payLinks );
	$log->LogInfo ( "-------前台交易自动提交表单>--end-------" );
	$log->LogInfo ( "============处理前台请求 结束===========" );
	*/
	header ( 'Content-type:text/html;charset=utf-8' );
    echo $payLinks;
?>
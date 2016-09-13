<?php
	//header ( 'Content-type:text/html;charset=utf-8' );
	require_once('./func/common.php');
	require '../system/system_init.php';
	$payment_notice_id=intval($_REQUEST['payment_notice_id']);
	
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
	$money = round($payment_notice['money'],2);
	$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
	$payment_info['config'] = unserialize($payment_info['config']);
	
    $noticeUrl =get_domain().APP_ROOT.'/return_url.php?from='.$_REQUEST['from'];//SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=notify&class_name=Wsumapay';
	$returnUrl=get_domain().APP_ROOT.'/notice_url.php?from='.$_REQUEST['from']; //SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=response&class_name=Wsumapay';

	$requestType='IFZ1000';//请求类型
	$requestId=$payment_notice['notice_sn'];//商户订单号
	$totalBizType=trim($payment_info['config']['biz_type_code']);//业务类型
	$merchantCode=trim($payment_info['config']['merchant_code']);//商户编号
	$totalPrice=$money;//交易总价格
	$passThrough='';//透传信息备注
	$goodsDesc='goodsdescription';//商品描述
	$userIdIdentity=$payment_notice['notice_sn'];//第三方用户标识
	$rePayTimeOut=0;//重新支付有效期
	$encode='GBK';
	
	$productId=$payment_notice_id;//产品编码
	$productName='Online Payment';//产品名称
	$fund = $money;//产品定价（精确到分，不可为负）
	$merAcct=trim($payment_info['config']['merchant_code']);//供应商编码，一般为商户代码
	$bizType=trim($payment_info['config']['biz_type_code']);//产品业务类型
	$productNumber=1;//产品订购数量
	
	$signature='';
	$signature.=$requestType;
	$signature.=$requestId;
	$signature.=$totalBizType;
	$signature.=$merchantCode;
	$signature.=$totalPrice;
	$signature.=$passThrough;
	$signature.=$goodsDesc;
	$signature.=$userIdIdentity;
	$signature.=$rePayTimeOut;
	$signature.=$noticeUrl;
	$signature.=$encode;
	$signature.=$noticeUrl;
	$signature.=$returnUrl;
	$signature=HmacMd5($signature,trim($payment_info['config']['merchant_key']));
	$def_url .= '<div style="text-align:center"><form name="form2" style="text-align:center;" method="post" action="https://mapi.sumapay.com/quickpaymentMobile/merchant.do" id="jumplink" onsubmit="document.charset=\'gbk\';">';
    $def_url .= "<input type='hidden' name='requestType' id='requestType'  value='" . $requestType . "' />";        
    $def_url .= "<input type='hidden' name='requestId' id='requestId'  value='" . $requestId . "' />";
    $def_url .= "<input type='hidden' name='totalBizType' id='totalBizType'  value='" . $totalBizType . "' />";
    $def_url .= "<input type='hidden' name='merchantCode' id='merchantCode'  value='" . $merchantCode . "' />";  
    $def_url .= "<input type='hidden' name='totalPrice' id='totalPrice'  value='" . $totalPrice . "' />";
    $def_url .= "<input type='hidden' name='passThrough' id='passThrough'  value='" . $passThrough . "' />";
    $def_url .= "<input type='hidden' name='goodsDesc' id='goodsDesc'  value='" . $goodsDesc . "' />";        
    $def_url .= "<input type='hidden' name='userIdIdentity' id='userIdIdentity'  value='" . $userIdIdentity . "' />";   
    $def_url .= "<input type='hidden' name='rePayTimeOut' id='rePayTimeOut'  value='" . $rePayTimeOut . "' />";       
    $def_url .= "<input type='hidden' name='noticeUrl' id='noticeUrl'  value='" . $noticeUrl . "' />";
    $def_url .= "<input type='hidden' name='encode' id='encode'  value='" . $encode . "' />"; 
    $def_url .= "<input type='hidden' name='successReturnUrl' id='successReturnUrl'  value='" . $noticeUrl . "' />";
    $def_url .= "<input type='hidden' name='failReturnUrl' id='failReturnUrl'  value='" . $returnUrl. "' />";  
    $def_url .= "<input type='hidden' name='signature' id='signature'  value='" . $signature. "' />"; 
          
    $def_url .= "<input type='hidden' name='productId' id='productId'  value='" . $productId . "' />";
    $def_url .= "<input type='hidden' name='productName' id='productName'  value='" . $productName . "' />";        
    $def_url .= "<input type='hidden' name='fund' id='fund'  value='" . $fund . "' />";       
    $def_url .= "<input type='hidden' name='merAcct' id='merAcct'  value='" . $merAcct . "' />";
    $def_url .= "<input type='hidden' name='bizType' id='bizType'  value='" . $bizType . "' />";
    $def_url .= "<input type='hidden' name='productNumber' id='productNumber'  value='" . $productNumber . "' />";
    $def_url .= "</form></div></br>";
	$def_url .="<script type='text/javascript'>document.forms['jumplink'].submit();</script>";
	//$def_url .= "</body></html>";
	
    echo $def_url;
?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>宝付在线交易</title>

<?php
require_once '../system/system_init.php';

$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where class_name='Wbfpay'");
$payment_info['config'] = unserialize($payment_info['config']);

$MemberID=$_REQUEST['MemberID'];//商户号
$TerminalID =$_REQUEST['TerminalID'];//商户终端号
$TransID =$_REQUEST['TransID'];//商户流水号
$Result=$_REQUEST['Result'];//支付结果
$ResultDesc=$_REQUEST['ResultDesc'];//支付结果描述
$FactMoney=$_REQUEST['FactMoney'];//实际成功金额
$AdditionalInfo=$_REQUEST['AdditionalInfo'];//订单附加消息
$SuccTime=$_REQUEST['SuccTime'];//支付完成时间
$Md5Sign=$_REQUEST['Md5Sign'];//md5签名
$Md5key = $payment_info['config']["Md5key"]; ///////////md5密钥（KEY）
$MARK = "~|~";
//MD5签名格式
$WaitSign=md5('MemberID='.$MemberID.$MARK.'TerminalID='.$TerminalID.$MARK.'TransID='.$TransID.$MARK.'Result='.$Result.$MARK.'ResultDesc='.$ResultDesc.$MARK.'FactMoney='.$FactMoney.$MARK.'AdditionalInfo='.$AdditionalInfo.$MARK.'SuccTime='.$SuccTime.$MARK.'Md5Sign='.$Md5key);

if(isset($_SESSION['OrderMoney'])){
	$OrderMoney =intval(round($_SESSION['OrderMoney'],2)*100);//获取提交金额的Session
}
if($Md5Sign == $WaitSign){
	//校验通过开始处理订单
	if($OrderMoney == $FactMoney){
		//卡面金额与用户提交金额一致
		echo("<script>alert('支付成功');</script>");//全部正确了输出OK
	}else{
		echo("<script>alert('实际成交金额与您提交的订单金额不一致，请接收到支付结果后仔细核对实际成交金额，以免造成订单金额处理差错。');</script>");	//实际成交金额与商户提交的订单金额不一致
	}
}else{
	echo("<script>alert('Md5CheckFail');</script>");//MD5校验失败，订单信息不显示
	$TransID=$WaitSign;
	$ResultDesc="";
	$FactMoney="";
	$AdditionalInfo="";
	$SuccTime="";
}

?>
<?php
require_once("log_.php");
$log_ = new Log_();
$log_->log_result("商户号:".$MemberID."\n");
$log_->log_result("商户终端号".$TerminalID."\n");
$log_->log_result("商户流水号".$TransID."\n\r");
$log_->log_result("支付结果".$Result."\n");
$log_->log_result("支付结果描述".$ResultDesc."\n\r");
$log_->log_result("实际成功金额".$FactMoney."\n");
$log_->log_result("订单附加消息".$AdditionalInfo."\n\r");
$log_->log_result("支付完成时间".$SuccTime."\n");
$log_->log_result("md5签名".$Md5Sign."\n\r");
$log_->log_result("md5密钥（KEY）".$Md5key."\n");
$log_->log_result("WaitSign".$WaitSign."\n\r");
?>
</head>

<body>
<?php

$param=$_REQUEST;

if($Result){
	$payment_notice_sn = $param['TransID'];
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");	
	require_once APP_ROOT_PATH."system/libs/cart.php";
	$rs = payment_paid($payment_notice_sn,$outer_notice_sn);

	if ($rs)
	{	
		$url = APP_ROOT."/../index.php?ctl=account";
		app_redirect($url);
	}
	else
	{
		echo "支付失败<br />请点左上角<b>返回</b>按钮";
	}
}else{
		echo "支付失败<br />请点左上角<b>返回</b>按钮";
}   

?>

</body>
</html>

<?php
require_once '../system/system_init.php';

$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where class_name='Wbfpay'");
$payment_info['config'] = unserialize($payment_info['config']);

$MemberID=$_REQUEST['MemberID'];//商户号
$TerminalID =$_REQUEST['TerminalID'];//商户终端号
$TransID =$_REQUEST['TransID'];//流水号
$Result=$_REQUEST['Result'];//支付结果
$ResultDesc=$_REQUEST['ResultDesc'];//支付结果描述
$FactMoney=$_REQUEST['FactMoney'];//实际成功金额
$AdditionalInfo=$_REQUEST['AdditionalInfo'];//订单附加消息
$SuccTime=$_REQUEST['SuccTime'];//支付完成时间
$Md5Sign=$_REQUEST['Md5Sign'];//md5签名
$Md5key = $payment_info['config']["Md5key"];
$MARK = "~|~";
//MD5签名格式
$WaitSign=md5('MemberID='.$MemberID.$MARK.'TerminalID='.$TerminalID.$MARK.'TransID='.$TransID.$MARK.'Result='.$Result.$MARK.'ResultDesc='.$ResultDesc.$MARK.'FactMoney='.$FactMoney.$MARK.'AdditionalInfo='.$AdditionalInfo.$MARK.'SuccTime='.$SuccTime.$MARK.'Md5Sign='.$Md5key);
if ($Md5Sign == $WaitSign) {
	//校验通过开始处理订单		
	echo ("ok");//全部正确了输出OK
	
	$param=$_REQUEST;
	if($Result){
		$payment_notice_sn = $param['TransID'];
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");	
		require_once APP_ROOT_PATH."system/libs/cart.php";
		$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
		if ($rs)
		{	
			$url = APP_ROOT."/../wap/index.php?ctl=cart&act=pay_result&id=".$payment_notice['id'];
			app_redirect($url);
		}
	}   
} else {
	echo("Md5CheckFail'");//MD5校验失败
   //处理想处理的事情
} 

?>
<?php
require_once("log_.php");
$log_ = new Log_a();
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
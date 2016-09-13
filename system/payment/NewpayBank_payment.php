<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'牛付银行直连支付',
	'partner_id'	=>	'商户ID',
	'partner_key'		=>	'商户秘钥',
	'credit_type'		=>	'支付类型',
	'GO_TO_PAY'	=>	'前往牛付在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	aaaaa
);
$config = array(
	'partner_id'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户ID
	'partner_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户秘钥: 
	
	
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'NewpayBank';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    //$module['reg_url'] = 'https://b.alipay.com/order/slaverIndex.htm?customer_external_id=C4393319516691172112&market_type=from_agent_contract&pro_codes=61F99645EC0DC4380ADE569DD132AD7A';
    $module['reg_url'] = '';
    return $module;
}

// 牛付直连支付模型aa
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class NewpayBank_payment implements payment {
	
	public function get_name($bank_id)
	{
		return $this->payment_lang['newpay_gateway_'.$bank_id];
	}
	//发送数据，创建订单
	public function get_payment_code($payment_notice_id)
	{
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

		$subject = $payment_notice['deal_name']==""?"充值".format_price($payment_notice['money']):$payment_notice['deal_name'];
		$data_return_url = get_domain().APP_ROOT.'/index.php?ctl=payment&act=response&class_name=NewpayBank';
		$data_notify_url = get_domain().APP_ROOT.'/index.php?ctl=payment&act=notify&class_name=NewpayBank';
		//$goods=iconv('utf-8','gbk',base64_encode($subject));
		$goods=base64_encode(iconv('utf-8','gbk',$subject));
		$expTime=date("YmdHis",strtotime("+3 day"));

		$parameter = array(		
			'version'         => 'v1',
            'partnerId'       => $payment_info['config']['partner_id'],
            'orderId'         => $payment_notice['notice_sn'],
            'goods'           => $goods,
            'amount'          => $payment_notice['money'],
            'expTime'         => $expTime,
            'notifyUrl'       => $data_notify_url,
            'pageUrl'         => $data_return_url,
            'reserve'         => $payment_notice['notice_sn'],
            'extendInfo'      => '',
            'payMode'         => '01',
            'bankId'          => $payment_notice['bank_id'],
            'creditType'      => '2',
        );
        
        //ksort($parameter);
        //reset($parameter);

        //$param = '';
        $sign  = '';
        foreach ($parameter AS $key => $val)
        {
        	$sign  .= "$key=$val&";        	
        }
        $sign  = substr($sign, 0, -1)."&key=". $payment_info['config']['partner_key'];
        
        $sign_md5 = md5($sign);
		//$sign_md51=iconv('gbk','utf-8',$sign_md5);
		
		$payLinks ='<form action="https://pay.newpaypay.com/center/proxy/partner/v1/pay.jsp"  id="jumplink" method="post">';
		$payLinks.='<input type="hidden" name="version" value="'.$parameter['version'].'" />';
		$payLinks.='<input type="hidden" name="partnerId" value="'.$parameter['partnerId'].'" />';
		$payLinks.='<input type="hidden" name="orderId" value="'.$parameter['orderId'].'" />';
		$payLinks.='<input type="hidden" name="goods" value="'.$parameter['goods'].'" />';
		$payLinks.='<input type="hidden" name="amount" value="'.$parameter['amount'].'" /> ';
		$payLinks.='<input type="hidden" name="expTime" value="'.$expTime.'" /> ';
		$payLinks.='<input type="hidden" name="notifyUrl" value="'.$parameter['notifyUrl'].'" />';
		$payLinks.='<input type="hidden" name="pageUrl" value="'.$parameter['pageUrl'].'" />';
		$payLinks.='<input type="hidden" name="reserve" value="'.$parameter['reserve'].'" /> ';
		$payLinks.='<input type="hidden" name="extendInfo" value="" /> ';
		$payLinks.='<input type="hidden" name="payMode" value="'.$parameter['payMode'].'" />';
		$payLinks.='<input type="hidden" name="bankId" value="'.$parameter['bankId'].'" />';
		$payLinks.='<input type="hidden" name="creditType" value="'.$parameter['creditType'].'" />';
		$payLinks.='<input type="hidden" name="sign" value="'.$sign_md5.'" />';
		$payLinks.=sprintf($this->payment_lang['GO_TO_PAY'],$this->get_name($payment_notice['bank_id'])).'</form>';
		//print_r($payLinks);exit;
		$payLinks.='<script type="text/javascript">document.getElementById("jumplink").submit();</script>';
        return $payLinks;
       
	}
	public function response($request)
	{
		
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='NewpayBank'");  
    	$payment['config'] = unserialize($payment['config']);
    	
        /* 检查数字签名是否正确 */
        //ksort($request);
       	//reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' &&  $key != 'code' && $key!='class_name' && $key!='act' && $key!='ctl' && $key!='md5' && $key!='bankJournal' && $key!='bankOrderId')
            {
              $sign .= "$key=$val&";
            }
        }
       $sign  = substr($sign, 0, -1)."&key=". $payment['config']['partner_key'];
        
		
		if (md5($sign) != $request['md5'])
        {
            showErr("md5验证失败");
        }
		
        $payment_notice_sn = $request['orderId'];
        
    	$money = $request['amount'];
		
    	$outer_notice_sn = $request['traceId'];
		
		if ($request['result'] == 'S'){
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice_sn,$outer_notice_sn);
 			showSuccess($rs['info'],0,$rs['jump'],0);
		}else{
		    showErr("支付失败",0,url("index"),1);
		}   
	}
	
	public function notify($request)
	{
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='NewpayBank'");  
    	$payment['config'] = unserialize($payment['config']);
    	
        /* 检查数字签名是否正确 */
        //ksort($request);
        //reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' &&  $key != 'code' && $key!='class_name' && $key!='act' && $key!='ctl' && $key!='md5' && $key!='bankJournal' && $key!='bankOrderId')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign  = substr($sign, 0, -1)."&key=". $payment['config']['partner_key'];

		if (md5($sign) != $request['md5'])
        {
            echo "fail";
        }
		
        $payment_notice_sn = $request['orderId'];
        
    	$money = $request['amount'];
		$outer_notice_sn = $request['traceId'];
		if ($request['result'] == 'S'){

			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice_sn,$outer_notice_sn);							
			echo "success";
		}else{
		   echo "fail";
		}   
	}
	
	public function get_display_code()
	{		
			$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='NewpayBank'");  
	    	$payment['config'] = unserialize($payment['config']);
			$partnerId=$payment['config']['partner_id'];
			$key=$payment['config']['partner_key'];
			$param='partnerId='.$partnerId.'&channel=WEBPAY&key='.$key.'&rstType=json';
	        $sign=md5($param);
	 		$install_info = file_get_contents('https://pay.newpaypay.com/servlet/InitBankLogoServlet?partnerId='.$partnerId.'&channel=WEBPAY&sign='.$sign.'&rstType=json');
  	 			
	 		$banks = str_replace('null(','',$install_info);
	 		$banks = str_replace(')','',$banks);
	 		$banks = trim($banks);
	 		$banks=iconv('gbk','utf-8',$banks);
	        $banks=json_decode($banks); 
	        $banks= object_array($banks);
	    
	        $banks_msg=$banks['msg'];
			if($payment)
			{
				$payment_cfg = unserialize($payment['config']);
				$html="<div>牛付银行直连支付</div>";
				
				$html .= "<style type='text/css'>.niufubank_types{float:left; display:inline; width:130px; font-size:0; text-align:left; padding:10px 0px; margin:0 5px;}.niufubank_types .niufubank_radio{float:left;margin-top:7px}.niufubank_types img{width:110px;height:27px;overflow:hidden}";
		        $html .="</style>";
	        	$html .="<script type='text/javascript'>function set_bank(bank_id)";
				$html .="{";
				$html .="$(\"input[name='bank_id']\").val(bank_id);";
				$html .="}</script>";
				foreach ($banks_msg AS $key=>$val)
		        {
		           $html  .= "<label class='niufubank_types'><input class='niufubank_radio' type='radio' name='payment' value='".$payment['id']."' rel='".$val['BANKID']."' onclick='set_bank(\"".$val['BANKID']."\")' /><img src=".$val['LOGOSRC']." /></label>";
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
	function object_array($array) {  
	    if(is_object($array)) {  
	        $array = (array)$array;  
	     } if(is_array($array)) {  
	         foreach($array as $key=>$value) {  
	             $array[$key] = object_array($value);  
	             }  
	     }  
	     return $array;  
	}
?>
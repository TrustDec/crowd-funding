<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

interface collocation{	

	/**
	 * 创建新帐户
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function CreateNewAcct($user_id,$user_type);
	
	/**
	 * 标的登记 及 流标
	 * @param int $deal_id
	 * @param int $pOperationType 标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻
	 * @param int $status; 0:新增; 1:标的正常结束; 2:流标结束
	 * @param string $status_msg 主要是status_msg=2时记录的，流标原因
	 */
	function RegisterSubject($deal_id,$pOperationType,$status, $status_msg);
	
	/**
	 * 登记债权人
	 * @param int $user_id  用户ID
	 * @param int $deal_id  标的ID
	 * @param float $pAuthAmt 投资金额
	 * @return string
	 */
	function RegisterCreditor($order_id,$t_user_id);
		
	/**
	 * 登记债权转让
	 * @param int $transfer_id  转让id
	 * @param int $t_user_id  受让用户ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RegisterCretansfer($transfer_id,$t_user_id);
	
			/**
	 * 账户余额查询(WS) 
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $ws_url
	 * @return
	 * 			pMerCode 6 “平台”账号 否 由IPS颁发的商户号
				pErrCode 4 返回状态 否 0000成功； 9999失败；
				pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
				pIpsAcctNo 30 IPS账户号 否 查询时提交
				pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数
				pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数
				pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数
	 */
	function QueryForAccBalance($user_id,$user_type);
		
	/**
	 * 解冻保证金
	 * @param int $deal_id 标的号
	 * @param int $pUnfreezenType 解冻类型 否 1#解冻借款方；2#解冻担保方
	 * @param float $money 解冻金额;默认为0时，则解冻所有未解冻的金额
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function GuaranteeUnfreeze($deal_id,$pUnfreezenType, $money);	
	
	/**
	 * 充值
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 充值金额
	 * @param string $pTrdBnkCode 银行编号
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function DoDpTrade($user_id,$user_type,$pTrdAmt,$pTrdBnkCode);
	
	/**
	 * 绑定银行卡
	 * @param unknown_type $user_id
	 */
	function BindBankCard($user_id);
	
	/**
	 * 用户提现
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_user.id
	 * @param float $pTrdAmt 提现金额
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function DoDwTrade($user_id,$user_type,$pTrdAmt);
		
	/**
	 * 商户端获取银行列表查询(WS) 
	 * @param int $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $ws_url
	 * @return  
	 * 		  pMerCode 6 “平台”账号 否 由IPS颁发的商户号 pErrCode 4 返回状态 否 0000成功； 9999失败；
	 * 		  pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因 
	 * 		  pBankList 银行名称|银行卡别名|银行卡编号#银行名称|银行卡别名|银行卡编号
	 * 		  BankList[] = array('name'=>银行名称,'sub_name'=>银行卡别名,'id'=>银行卡编号);
	 */
	function GetBankList();
	
	/**
	 * 登记担保方
	 * @param int $deal_id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function RegisterGuarantor($deal_id);	
	
	/**
	 * 还款
	 * @param deal $deal  标的数据
	 * @param array $repaylist  还款列表
	 * @param int $deal_repay_id  还款计划ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	function RepaymentNewTrade($deal, $repaylist, $deal_repay_id);
		
	/**
	 * 转帐
	 * @param int $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	 * @param int $deal_id  标的id
	 * @param string $ref_data 逗号分割的,代偿，代偿还款列表; 债权转让: id; 结算担保收益:金额，如果为0,则取fanwe_deal.guarantor_pro_fit_amt ;
	 * @return string
	 */
	function Transfer($pTransferType, $deal_id, $ref_data);	

	//响应支付
	function response($request,$class_act);
	
	//响应通知
	function notify($request,$class_act);
	/**
	 * 资金冻结
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 充值金额
	 * @param string $pTrdBnkCode 银行编号
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $post_url
	 * @return string
	 */
	function SincerityGoldFreeze($user_id,$user_type,$pTrdAmt,$deal_id,$from,$pTrdBnkCode);
	
		/**
	 * 解冻诚意金
	 * @param $platformNo 商户编号
	 * @param $freezeRequestNo 冻结时的请求流水号
	 * @return string
	 */
	function SincerityGoldUnfreeze($user_id,$user_type,$freezeRequestNo,$deal_id,$pTrdBnkCode);
}
?>
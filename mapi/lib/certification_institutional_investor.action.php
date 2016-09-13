<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 认证机构投资者接口
 */
class certification_institutional_investor
{
	public function index()
	{
		$is_investor = 2; // 2表示机构投资者
		                  
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$identify_business_name = strim ( $GLOBALS ['request'] ['identify_business_name'] ); // 机构名称
		$identify_name = strim ( $GLOBALS ['request'] ['identify_name'] ); // 身份证名称
		$identify_number = strim ( $GLOBALS ['request'] ['identify_number'] ); // 身份证号码
		
		$is_tg=is_tg();
		if($is_tg)
		{
			$bankLicense=strim($GLOBALS ['request']['bankLicense']);
			$orgNo=strim($GLOBALS ['request']['orgNo']);
			$taxNo=strim($GLOBALS ['request']['taxNo']);
			$businessLicense=strim($GLOBALS ['request']['businessLicense']);
			$contact=strim($GLOBALS ['request']['contact']);
			$memberClassType=strim($GLOBALS ['request']['memberClassType']);
			   
			// 验证参数
			if($bankLicense==''){
				$data = responseErrorInfo ( "开户银行许可证不能为空" );
				output ( $data );
			}        
			if($orgNo==''){
				$data = responseErrorInfo ( "组织机构代码不能为空" );
				output ( $data );
			}
			if($taxNo==''){
				$data = responseErrorInfo ( "税务登记号不能为空" );
				output ( $data );
			}
			if($businessLicense==''){
				$data = responseErrorInfo ( "营业执照编号不能为空" );
				output ( $data );
			}  
			if($contact==''){
				$data = responseErrorInfo ( "企业联系人不能为空!" );
				output ( $data );
			}
		}
		
		// 验证参数                                                         
		if (! $this->verifyRequestParams ( $identify_business_name, $identify_name, $identify_number ))
		{
			return;
		}
		
		$user = user_check ( $email, $password );
		$user_id = intval ( $user ['id'] );
		if ($user_id <= 0)
		{
			$data = responseNoLoginParams ();
			output ( $data );
		}
		
		
		
		$result = detectStateAudit ( $user );
		if ($result ['investor_status'] != 1) // 1 表示通过审核
		{
			if ($result ['response_code'] == 0)
			{
				// 审核中的状态的情况不让程序继续执行
				output ( $result );
			}
			
			// identify_business_licence 营业执照
			// identify_business_code 组织机构代码证
			// identify_business_tax 税务登记证
			// identify_positive_image身份证正面
			// identify_nagative_image身份证背面
			$dir = createImageDirectory ();
			
			if (isset ( $_FILES ['identify_business_licence'] ))
			{
				$identify_business_licence_result = save_image_upload ( $_FILES, "identify_business_licence", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_business_licence_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_business_licence_result ['message'] );
					output ( $data );
				} else
				{
					$identify_business_licence_url = $identify_business_licence_result ['identify_business_licence'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['identify_business_code'] ))
			{
				$identify_business_code_result = save_image_upload ( $_FILES, "identify_business_code", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_business_code_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_business_code_result ['message'] );
					output ( $data );
				} else
				{
					$identify_business_code_url = $identify_business_code_result ['identify_business_code'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['identify_business_tax'] ))
			{
				$identify_business_tax_result = save_image_upload ( $_FILES, "identify_business_tax", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_business_tax_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_business_tax_result ['message'] );
					output ( $data );
				} else
				{
					$identify_business_tax_url = $identify_business_tax_result ['identify_business_tax'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['identify_positive_image'] ))
			{
				$identify_positive_image_result = save_image_upload ( $_FILES, "identify_positive_image", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_positive_image_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_positive_image_result ['message'] );
					output ( $data );
				} else
				{
					$identify_positive_image_url = $identify_positive_image_result ['identify_positive_image'] ['url'];
				}
			}
			
			if (isset ( $_FILES ['identify_nagative_image'] ))
			{
				$identify_business_licence_result = save_image_upload ( $_FILES, "identify_nagative_image", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_business_licence_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_business_licence_result ['message'] );
					output ( $data );
				} else
				{
					$identify_nagative_image_url = $identify_business_licence_result ['identify_nagative_image'] ['url'];
				}
			}
			
			$dbObject = array ();
			if ($result ['investor_status'] == 2) // 当审核未通过时，回置为0
			{
				$dbObject ['investor_status'] = 0;
			}
			$dbObject ['is_investor'] = $is_investor;
			$dbObject ['identify_business_name'] = $identify_business_name;
			$dbObject ['identify_business_licence'] = $identify_business_licence_url;
			$dbObject ['identify_business_code'] = $identify_business_code_url;
			$dbObject ['identify_business_tax'] = $identify_business_tax_url;
			
			$dbObject ['identify_name'] = $identify_name; // 身份证名称
			$dbObject ['identify_number'] = $identify_number; // 身份证号码
			$dbObject ['identify_positive_image'] = $identify_positive_image_url; // 身份证正面照片
			$dbObject ['identify_nagative_image'] = $identify_nagative_image_url;
			
			if($is_tg)
			{
				$dbObject['bankLicense'] = $bankLicense;//开户银行许可证
				$dbObject['orgNo'] = $orgNo;//组织机构代码
				$dbObject['businessLicense'] = $businessLicense;//营业执照编号
				$dbObject['contact'] = $contact;//企业联系人
				$dbObject['taxNo'] = $taxNo;//税务登记号
				$dbObject['memberClassType'] = $memberClassType;//公司类型 值：ENTERPRISE（企业用户） GUARANTEE_CORP（担保公司
			}
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", $dbObject, 'UPDATE', 'id = ' . $user_id ); // 身份证背面照片
			
			$data = responseSuccessInfo ( "提交成功" );
			output ( $data );
		} else
		{
			$data = responseErrorInfo ( "该用户已经通过审核" );
			output ( $data );
		}
	}
	private function verifyRequestParams($identify_business_name, $identify_name, $identify_number)
	{
		if ($identify_business_name == '')
		{
			$data = responseErrorInfo ( "机构名称不能为空" );
			output ( $data );
		}
		
		if ($identify_name == '')
		{
			$data = responseErrorInfo ( "身份证名称不能为空" );
			output ( $data );
		}
		
		if (! isCreditNo ( $identify_number ))
		{
			$data = responseErrorInfo ( "请输入正确的身份证号码" );
			output ( $data );
		}
		
		if (! isset ( $_FILES ['identify_positive_image'] ))
		{
			$data = responseErrorInfo ( "请上传身份证正面照片" );
			output ( $data );
		}
		
		if (! isset ( $_FILES ['identify_nagative_image'] ))
		{
			$data = responseErrorInfo ( "请上传身份证背面照片" );
			output ( $data );
		}
		
		if (! isset ( $_FILES ['identify_business_licence'] ))
		{
			$data = responseErrorInfo ( "请上传营业执照" );
			output ( $data );
		}
		
		if (! isset ( $_FILES ['identify_business_code'] ))
		{
			$data = responseErrorInfo ( "请上传组织机构代码证" );
			output ( $data );
		}
		
		if (! isset ( $_FILES ['identify_business_tax'] ))
		{
			$data = responseErrorInfo ( "请上传税务登记证" );
			output ( $data );
		}
		return true;
	}
}

?>
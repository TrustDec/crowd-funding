<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 认证个人投资者接口
 */
class certification_of_investors
{
	public function index()
	{
		$is_investor = 1; // 1表示个人投资者
		                  
		// 获取参数
		$email = strim ( $GLOBALS ['request'] ['email'] );
		$password = strim ( $GLOBALS ['request'] ['pwd'] );
		$ex_real_name = strim ( $GLOBALS ['request'] ['ex_real_name'] ); // 真实姓名
		$identify_number = strim ( $GLOBALS ['request'] ['identify_number'] ); // 身份证号码
		                                                                       
		// 验证参数
		if ($this->verifyParams ( $ex_real_name, $identify_number ))
		{
			output ( $this->verifyParams ( $ex_real_name, $identify_number ) );
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
			
			// identify_positive_image身份证正面
			// identify_nagative_image身份证反面
			$dir = createImageDirectory ();
			
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
				$identify_nagative_image_result = save_image_upload ( $_FILES, "identify_nagative_image", "attachment/" . $dir, $whs = array (
						'thumb' => array (
								205,
								160,
								1,
								0 
						) 
				), 0, 1 );
				
				if (intval ( $identify_nagative_image_result ['error'] ) != 0)
				{
					$data = responseErrorInfo ( $identify_nagative_image_result ['message'] );
					output ( $data );
				} else
				{
					$identify_nagative_image_url = $identify_nagative_image_result ['identify_nagative_image'] ['url'];
				}
			}
			
			$dbObject = array ();
			if ($result ['investor_status'] == 2) // 当审核未通过时，回置为0
			{
				$dbObject ['investor_status'] = 0;
			}
			$dbObject ['is_investor'] = $is_investor;
			$dbObject ['identify_name'] = $ex_real_name;
			$dbObject ['identify_number'] = $identify_number;
			$dbObject ['identify_positive_image'] = $identify_positive_image_url;
			$dbObject ['identify_nagative_image'] = $identify_nagative_image_url;
			
			$dbObject ['identify_business_name'] = null;
			$dbObject ['identify_business_licence'] = null;
			$dbObject ['identify_business_code'] = null;
			$dbObject ['identify_business_tax'] = null;
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", $dbObject, 'UPDATE', 'id = ' . $user_id );
			
			$data = responseSuccessInfo ( "提交成功" );
			output ( $data );
		} else
		{
			$data = responseErrorInfo ( "该用户认证个人投资者资料已经通过审核" );
			output ( $data );
		}
	}
	// 验证参数
	private function verifyParams($ex_real_name, $identify_number)
	{
		if ($ex_real_name == '')
		{
			$data = responseErrorInfo ( "亲！请填写您的真实姓名" );
		} else if ($identify_number == '')
		{
			$data = responseErrorInfo ( "亲！请输入身份证" );
		} else if (! isCreditNo ( $identify_number ))
		{
			$data = responseErrorInfo ( "亲！请输入正确的身份证" );
		} else if (! isset ( $_FILES ['identify_positive_image'] ))
		{
			$data = responseErrorInfo ( "请上传身份证正面" );
		} else if (! isset ( $_FILES ['identify_nagative_image'] ))
		{
			$data = responseErrorInfo ( "请上传身份证反面" );
		}
		return $data;
	}
}

?>
<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class weixin
{
	//微信APPID
   	var $app_id="";
    //微信秘钥
    var $app_secret="";
    //跳转链接
    var $redirect_url="";
    //传递的方式
    var $scope="";
    var $state=1;
    //用户同意授权，获取code
    var $code="";
    //授权通过后获取access_token  openid
    var $access_token="";
    var $openid="";
    
    var $platform = "";
    var $component_appid = "";
    var $component_access_token = "";
    var $is_platform = 0;
    function __construct($app_id="",$app_secret="",$redirect_url="",$scope="snsapi_userinfo",$state=1)
    {
        $this->app_id=$app_id;
        $this->app_secret=$app_secret;
        $this->redirect_url=urlencode($redirect_url);
        $this->scope=$scope;
        $this->state=$state;
        $weixin_conf = load_auto_cache("weixin_conf");
        if($weixin_conf['platform_status']){
        	$this->is_platform = 1;
        	$this->component_appid = $weixin_conf['platform_appid'];
        	$this->component_access_token =  $weixin_conf['platform_component_access_token'];
	        $this->option = array(
	 			'platform_token'=>$weixin_conf['platform_token'], //填写你设定的token
	 			'platform_encodingAesKey'=>$weixin_conf['platform_encodingAesKey'], //填写加密用的EncodingAESKey
	 			'platform_appid'=>$weixin_conf['platform_appid'], //填写高级调用功能的app id
	 			'platform_appsecret'=>$weixin_conf['platform_appsecret'], //填写高级调用功能的密钥
	 			
	 			'platform_component_verify_ticket'=>$weixin_conf['platform_component_verify_ticket'], //第三方通知
	 			'platform_component_access_token'=>$weixin_conf['platform_component_access_token'], //第三方平台令牌
	 			'platform_pre_auth_code'=>$weixin_conf['platform_pre_auth_code'], //第三方平台预授权码
	 			
	 			'platform_component_access_token_expire'=>$weixin_conf['platform_component_access_token_expire'], 
	 			'platform_pre_auth_code_expire'=>$weixin_conf['platform_pre_auth_code_expire'], 
	  			
	 			'logcallback'=>'log_result',
	 			'debug'=>true,
	 		);
	 		$this->platform = new PlatformWechat($this->option);
	 		$new_token = $this->platform->check_platform_access_token();
	 		if($new_token!=$this->component_access_token){
	 			$this->component_access_token = $new_token;
	 		}
        }
    }
    public function scope_get_code($app_id){
    	 if($this->is_platform){
    	 	     //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE&component_appid=component_appid#wechat_redirect
    		 $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$app_id."&redirect_uri=".($this->redirect_url)."&response_type=code&scope=".$this->scope."&state=".$this->state."&component_appid=".$this->component_appid."#wechat_redirect";
    	 }else{
    	 	$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->app_id."&redirect_uri=".($this->redirect_url)."&response_type=code&scope=".$this->scope."&state=".$this->state."#wechat_redirect";
    	 }
    	
    	 return $url;
    }
    public function scope_get_userinfo($code,$appid=""){
    	require_once APP_ROOT_PATH."system/utils/transport.php";
    	$tran = new transport();
    	$this->code=$code;
    	if($this->is_platform){
    					  //https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=APPID&code=CODE&grant_type=authorization_code&component_appid=COMPONENT_APPID&component_access_token=COMPONENT_ACCESS_TOKEN
    		$get_token_url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=".$appid."&code=".$this->code."&grant_type=authorization_code&component_appid=".$this->component_appid."&component_access_token=".$this->component_access_token;
    	
    	}else{
    		$get_token_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->app_id."&secret=".$this->app_secret."&code=".$this->code."&grant_type=authorization_code";
	       
    	}
		$token_info=$this->https_request($get_token_url);
     	$token_info=json_decode($token_info['body'],true);
     	$this->access_token=$token_info['access_token'];
    	$this->openid=$token_info['openid'];
     	$get_userinfo="https://api.weixin.qq.com/sns/userinfo?access_token=".$this->access_token."&openid=".$this->openid."&lang=zh_CN";
    	$user_info=$this->https_request($get_userinfo);
     	$user_info=json_decode($user_info['body'],true);
    	return $user_info;
    }
    public function https_request($url){
    	$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		$http_response = curl_exec($curl);
		 if (curl_errno($curl) != 0)
        {
            return false;
        }

        $separator = '/\r\n\r\n|\n\n|\r\r/';
        list($http_header, $http_body) = preg_split($separator, $http_response, 2);

        $http_response = array('header' => $http_header,//肯定有值
                               'body'   => $http_body); //可能为空
		curl_close($curl);
		return $http_response;
    }
}

?>
<?php
require APP_ROOT_PATH.'app/Lib/shop_lip.php';

class referralModule{
	public function index()
	{
 		if(!$GLOBALS['user_info']){
			if($GLOBALS['is_app']!='IOS'&&$GLOBALS['is_app']!='ANDROID'){
				app_redirect(url_wap("user#login"));
			}else{
 				$email = strim($_REQUEST['email']);//用户名或邮箱
				$pwd = strim($_REQUEST['pwd']);//密码
 				$user_info = user_check($email,$pwd);
  				if(!$user_info){
					app_redirect(url_wap("user#app_login"));
				}
			}
 		}
		
		$user_id=intval($GLOBALS['user_info']['id']);
		//我邀请的好友
		$sql="select r.*,u.create_time as register_time from ".DB_PREFIX."referrals as r  left join ".DB_PREFIX."user as u on u.id=r.user_id where user_id=".$user_id." order by id desc ";
		$referrals_list=$GLOBALS['db']->getAll($sql);
		$GLOBALS['tmpl']->assign('referrals_list',$referrals_list);
		
		//邀请连接
		$referrals_url = get_domain().APP_ROOT."/";
		if($GLOBALS['user_info'])
		$referrals_url .= "?ref=".base64_encode(intval($user_id));

		//用户邀请二维码生成
		$invest_image_dir =APP_ROOT_PATH."public/images/invest_image";
		if (!is_dir($invest_image_dir)) {
			@mkdir($invest_image_dir, 0777);
		}
		$qrcode_dir=APP_ROOT_PATH."public/images/invest_image/invite_qrcode_".$user_id.".png";
		$qrcode_dir_logo=APP_ROOT_PATH."public/images/invest_image/invite_qrcode_logo_".$user_id.".png";
		if(!is_file($qrcode_dir)||!is_file($qrcode_dir_logo)){
			get_qrcode_png($referrals_url,$qrcode_dir,$qrcode_dir_logo);
		}
		$user_qrcode=APP_ROOT."/public/images/invest_image/invite_qrcode_logo_".$user_id.".png";
		$GLOBALS['tmpl']->assign('qrcode_url',$user_qrcode);
		
		//app地址
		$app_url=SITE_DOMAIN.url_wap("app_download");
		$GLOBALS['tmpl']->assign('app_url',$app_url);
		$GLOBALS['tmpl']->assign('referral',1);
		
		$GLOBALS['tmpl']->assign("url",$referrals_url);
		$GLOBALS['tmpl']->assign("page_title",'邀请好友');
		$GLOBALS['tmpl']->display("referrals_index.html");
	}
}

?>
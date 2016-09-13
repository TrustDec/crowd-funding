<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹我的项目
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------
// require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_save_userinfo {
	public function index() {
		require_once APP_ROOT_PATH . "system/libs/user.php";
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		                                          
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0) {
			// $root['user_login_status'] = 1;
			// $root['response_code'] = 1;
			
			if (! check_ipop_limit ( get_client_ip (), "setting_save_index", 5 )) {
				$root ['show_err'] = "提交太频繁";
				output ( $root );
			}
			
			require_once APP_ROOT_PATH . "system/libs/user.php";
			
			$user_data = array ();
			$user_data ['province'] = strim ( $_REQUEST ['province'] );
			$user_data ['city'] = strim ( $_REQUEST ['city'] );
			$user_data ['sex'] = intval ( $_REQUEST ['sex'] );
			$user_data ['intro'] = strim ( $_REQUEST ['intro'] );
			$user_data ['job'] = strim ( $GLOBALS['request']['job'] );
			//cate_name $user_data['cate_name'] =addslashes(serialize($_POST['cates']));
			$cates = strim ($GLOBALS ['request'] ['cates']);
			
			if($cates !='')
			{	
				$cate_name= $GLOBALS['db']->getAll("select id,name from ".DB_PREFIX."deal_cate where id in(".$cates.")");
				
				$cate_name_array=array();
				foreach($cate_name as $k=>$v){
					$cate_name_array[$v['id']]=$v['name'];
				}
				$user_data ['cate_name']=addslashes(serialize($cate_name_array));
				
			}
			else
			{
				$user_data ['cate_name'] ='';
			}
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", $user_data, "UPDATE", "id=" . intval ( $GLOBALS ['user_info'] ['id'] ) );
			$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "user_weibo where user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
			$weibo_data = array ();
			$weibo_data ['user_id'] = intval ( $GLOBALS ['user_info'] ['id'] );
			$weibo_data ['weibo_url'] = strim ( $_REQUEST ['weibo_url'] );
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_weibo", $weibo_data );
			// $root['image'] =$GLOBALS['m_config']['page_size'];
			// showSuccess("资料保存成功",$ajax,url('settings#index'));
			// file_put_contents(APP_ROOT_PATH."public/test.txt",
			// print_r($_FILES, 1));
			if (isset ( $_FILES ['image_1'] )) {
				// 开始上传
				// 创建avatar临时目录
				if (! is_dir ( APP_ROOT_PATH . "public/avatar" )) {
					@mkdir ( APP_ROOT_PATH . "public/avatar" );
					@chmod ( APP_ROOT_PATH . "public/avatar", 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/temp" )) {
					@mkdir ( APP_ROOT_PATH . "public/avatar/temp" );
					@chmod ( APP_ROOT_PATH . "public/avatar/temp", 0777 );
				}
				
				$img_result = save_image_upload ( $_FILES, "image_1", "avatar/temp", $whs = array (
						'small' => array (
								48,
								48,
								1,
								0 
						),
						'middle' => array (
								120,
								120,
								1,
								0 
						),
						'big' => array (
								200,
								200,
								1,
								0 
						) 
				) );
				
				// 开始移动图片到相应位置
				$id = intval ( $user ['id'] );
				$uid = sprintf ( "%09d", $id );
				$dir1 = substr ( $uid, 0, 3 );
				$dir2 = substr ( $uid, 3, 2 );
				$dir3 = substr ( $uid, 5, 2 );
				$path = $dir1 . '/' . $dir2 . '/' . $dir3;
				
				// 创建相应的目录
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 )) {
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1, 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 )) {
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2, 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3 )) {
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3, 0777 );
				}
				
				$id = str_pad ( $id, 2, "0", STR_PAD_LEFT );
				$id = substr ( $id, - 2 );
				$avatar_file_big = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_big.jpg";
				$avatar_file_middle = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_middle.jpg";
				$avatar_file_small = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_small.jpg";
				// file_put_contents(APP_ROOT_PATH."public/test1.txt",
				// print_r($avatar_file_small, 1));
				
				@file_put_contents ( $avatar_file_big, file_get_contents ( $img_result ['image_1'] ['thumb'] ['big'] ['path'] ) );
				@file_put_contents ( $avatar_file_middle, file_get_contents ( $img_result ['image_1'] ['thumb'] ['middle'] ['path'] ) );
				@file_put_contents ( $avatar_file_small, file_get_contents ( $img_result ['image_1'] ['thumb'] ['small'] ['path'] ) );
				@unlink ( $img_result ['image_1'] ['thumb'] ['big'] ['path'] );
				@unlink ( $img_result ['image_1'] ['thumb'] ['middle'] ['path'] );
				@unlink ( $img_result ['image_1'] ['thumb'] ['small'] ['path'] );
				@unlink ( $img_result ['image_1'] ['path'] );
				// end 上传
			}
			// $root['user_avatar'] =
			// get_abs_img_root(get_muser_avatar($user['id'],"big"));
			
			// $root['info'] = "资料保存成功";
		}
		// else{
		// $root['response_code'] = 0;
		// $root['show_err'] ="未登录";
		// $root['user_login_status'] = 0;
		// }
		
		$uc_center = uc_center ( $email, $pwd );
		$uc_center ['info'] = "资料保存成功";
		output ( $uc_center );
	}
}
?>
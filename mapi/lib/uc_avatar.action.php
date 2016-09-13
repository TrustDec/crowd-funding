<?php
class uc_avatar
{
	public function index()
	{
		require_once APP_ROOT_PATH . "system/libs/user.php";
		$root = array ();
		
		$email = strim ( $GLOBALS ['request'] ['email'] ); // 用户名或邮箱
		$pwd = strim ( $GLOBALS ['request'] ['pwd'] ); // 密码
		$GLOBALS ['user_info'] = $user_data = es_session::get ( 'user_info' );
		// 检查用户,用户密码
		$user = user_check ( $email, $pwd );
		$user_id = intval ( $user ['id'] );
		if ($user_id > 0)
		{
			if (isset ( $_FILES ['image_1'] ))
			{
				// 开始上传
				// 创建avatar临时目录
				if (! is_dir ( APP_ROOT_PATH . "public/avatar" ))
				{
					@mkdir ( APP_ROOT_PATH . "public/avatar" );
					@chmod ( APP_ROOT_PATH . "public/avatar", 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/temp" ))
				{
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
				$id = intval ( $user_data ['id'] );
				$uid = sprintf ( "%09d", $id );
				$dir1 = substr ( $uid, 0, 3 );
				$dir2 = substr ( $uid, 3, 2 );
				$dir3 = substr ( $uid, 5, 2 );
				$path = $dir1 . '/' . $dir2 . '/' . $dir3;
				
				// 创建相应的目录
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 ))
				{
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1, 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 ))
				{
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2, 0777 );
				}
				if (! is_dir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3 ))
				{
					@mkdir ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3 );
					@chmod ( APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3, 0777 );
				}
				
				$id = str_pad ( $id, 2, "0", STR_PAD_LEFT );
				$id = substr ( $id, - 2 );
				$avatar_file_big = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_big.jpg";
				$avatar_file_middle = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_middle.jpg";
				$avatar_file_small = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "virtual_avatar_small.jpg";
				
				@file_put_contents ( $avatar_file_big, file_get_contents ( $img_result ['image_1'] ['thumb'] ['big'] ['path'] ) );
				@file_put_contents ( $avatar_file_middle, file_get_contents ( $img_result ['image_1'] ['thumb'] ['middle'] ['path'] ) );
				@file_put_contents ( $avatar_file_small, file_get_contents ( $img_result ['image_1'] ['thumb'] ['small'] ['path'] ) );
				@unlink ( $img_result ['image_1'] ['thumb'] ['big'] ['path'] );
				@unlink ( $img_result ['image_1'] ['thumb'] ['middle'] ['path'] );
				@unlink ( $img_result ['image_1'] ['thumb'] ['small'] ['path'] );
				@unlink ( $img_result ['image_1'] ['path'] );
				// end 上传
			}
			$root ['response_code'] = 1;
			$root ['user_avatar'] = get_abs_img_root ( get_muser_avatar ( $user_data ['id'], "big" ) );
		} else
		{
			$root ['response_code'] = 0;
			$root ['show_err'] = "未登录";
			$root ['user_login_status'] = 0;
		}
		output ( $root );
	}
}
?>
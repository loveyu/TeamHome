<?php
/**
 *	用户中心用户激活、验证状态修改
 *
 *	@file	user/user_manage_status.php
 *
 */
require_once('../init.php');
send_header();
if(!$zxuser->auto_login() || !is_admin())forbid_page();//非管理员禁止
require_once('init_user.php');//用户中心初始化
switch(GET('type')){
	case 'active':
		$s = $user->change_active_status(GET('id'));
	break;
	case 'verify':
		$s = $user->change_verify_status(GET('id'));
	break;
	default:$s = "未知操作";
}
get_close_page($s,false);
?>
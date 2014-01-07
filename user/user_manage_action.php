<?php
/**
 *	用户中心修改请求操作
 *
 *	@file	user/setting_action.php
 *
 */
require_once('../init.php');
send_header();
if(!$zxuser->auto_login() || !is_admin())forbid_page();//判断必须为超级管理员
load("user_action");
$user = new User_action();
switch(is_post()?POST('action'):GET('action')){
	case 'edit_info':
		$s = $user->admin_edit_user_info(POST('name'),POST('sex'),POST('tel'),POST('mtel'),POST('qq'),POST('class'),POST('grade'),POST('faculty'),POST('subject'),POST('group1'),POST('group2'),htmlspecialchars(POST('describe'),ENT_QUOTES),htmlspecialchars(POST('sign'),ENT_QUOTES),htmlspecialchars(POST('specialty'),ENT_QUOTES),POST('id'));
	break;
	default:$s = '未知操作';
}
get_close_page($s,false);
?>
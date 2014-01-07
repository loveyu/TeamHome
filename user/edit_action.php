<?php
/**
 *	用户编辑个人信息操作请求
 *
 *	@file	user/edit_action.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())forbid_page();
require_once('init_user.php');//用户中心初始化
send_header();
if(is_post()){
	switch(POST('type')){
		case 'active':{
			if(!iPOST('pwd') || !$user->check_pwd(md5(POST('pwd')))){$s = '密码不正确';break;}
			$s = $user->user_active_username(POST('username'));
		}break;
		case 'edit_one':{
			if(!$zxuser->is_active()){$s = '请先激活账户';break;}
			$s = $user->user_edit_user_info(POST('name'),POST('sex'),POST('tel'),POST('mtel'),POST('qq'),POST('class'),POST('grade'),POST('faculty'),POST('subject'),POST('group1'),POST('group2'),htmlspecialchars(POST('describe'),ENT_QUOTES),htmlspecialchars(POST('sign'),ENT_QUOTES),htmlspecialchars(POST('specialty'),ENT_QUOTES));
		}break;
		default:$s = '未知操作';
	}
}else $s = "只允许POST操作";
get_close_page($s,false);
?>
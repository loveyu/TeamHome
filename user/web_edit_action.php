<?php
/**
 *	个人主页管理操作请求页面
 *
 *	@file	user/web_edit_action.php
 *
 */
require_once('../init.php');
send_header();
if(!$zxuser->auto_login() || !$zxuser->is_active())forbid_page();//禁止非正常请求
load('file_action');
$file = new File_action();
switch(is_post()?POST('type'):GET('type')){
	case 'edit_file':
		$s = $file->edit_user_file(POST('file'),TT(POST('content')));
	break;
	case 'rename':
		$s = $file->rename_user_file(POST('path'),POST('new'));
	break;
	case 'delete':
		$s = $file->delete_user_content(GET('path'));
	break;
	case 'creat_page':
		$s = $file->creat_user_type_file('page',POST('name'));
	break;
	case 'creat_js':
		$s = $file->creat_user_type_file('js',POST('name'));
	break;	
	case 'creat_css':
		$s = $file->creat_user_type_file('css',POST('name'));
	break;		
	default:die("未知操作");
}
if($s=='OK')$s = '操作成功';
get_close_page($s,false);
?>
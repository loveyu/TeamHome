<?php
/**
 *	分类的操作请求
 *
 *	@file	user/category_action.php
 *
 */
require_once('../init.php');
send_header();
if(!$zxuser->auto_login() || !is_admin())forbid_page();
switch(is_post()?POST('action'):GET('action')){
	case 'new':
		$s = $zxcat->new_category(POST('name'),POST('alias'),POST('type'),POST('description'));
	break;
	case 'edit':
		$s = $zxcat->edit_category(POST('id'),POST('name'),POST('alias'),POST('description'));
	break;
	case 'delete':
		$s = $zxcat->delete_category(POST('id'),POST('move'));
	break;
	default:$s = '未知操作';
}
get_close_page($s,false);
?>
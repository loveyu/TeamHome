<?php
/**
 *	用户分组操作请求
 *
 *	@file	user/group_action.php
 *
 */
require_once('../init.php');
send_header();
if(!$zxuser->auto_login() || !is_admin())die("Forbidden");
if(!is_post())die('只允许POST操作');
load('group');
$group = new Group();
switch(POST('action')){
	case 'new':
		if( POST('name')=='')die("表单有误");
		$s = $group->creat_new_group(POST());
	break;
	case 'edit':
		if(!is_numeric(POST('id')) || POST('id')<1 || POST('name')=='')die('表单有误');
		$s = $group->edit_group(POST());	
	break;
	case 'delete':
		if(!is_numeric(POST('id')) || POST('id')<1)die('表单有误');
		$s = $group->delete_group(POST('id'),POST('move_user'),POST('move_group'));
	break;
	default:$s = '未知操作';
}
get_close_page($s,false);
?>
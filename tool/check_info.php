<?php
	/**
	 * 注册信息检查
	 * 存在输出true,不存在输出false,错误输出error
	 *
	 * @file tool/check_info.php
	 */

	require_once("../init.php");
	header("Cache-Control: no-cache");
	if(GET('type') == '' || GET('value') == '')
		die('error');
	switch(GET('type')){
		case 'email':
		{
			sGET("value", strtolower(SS(GET('value')))); //转换为小写
			if(!is_mail(GET('value')))
				die('error');
			if($zxdb->count_sql("user", 'user="' . GET('value') . '"') > 0)
				die('true');
			else die('false');
		}
			break;
		case 'username':
		{
			if(!$zxuser->is_true_username(SS(GET('value'))))
				die('error');
			if($zxdb->count_sql("user", 'username="' . SS(GET('value')) . '"') > 0)
				die('true');
			else die('false');
		}
			break;
		default:
			die('error');
	}
?>
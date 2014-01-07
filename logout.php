<?php
/**
 *	注销用户操作
 *
 *	@file	logout.php
 *
 */
require_once("init.php");
if(!$zxuser->auto_login())redirect(site_url('login.php'), 'refresh');
$zxuser->logout();
$zxuser->jump();
?>
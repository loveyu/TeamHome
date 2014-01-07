<?php
/**
 *	用户中心创建新用户
 *
 *	@file	user/user_manage_new.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('manage','new');

if(!is_admin())forbid_page();//非管理员禁止
$err='';
if(is_post()){
	if(!is_mail(POST('user')))$err="这是一个错误的邮箱";
	load('register');
	$r = new Register();
	$err = $r->admin_register(POST('user'));
}
get_admin_header(array(
	'title' => '新建用户',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="user_manage">
<h3>创建新用户</h3>
<?php
if($err){
	if($err!='OK')echo "<p class=\"notice\">$err</p>\n";
	else echo "<p class=\"success\">成功创建账户，密码已经发送到邮箱",POST('user'),"</p>\n";
}
?>
<form class="new_user" action="user_manage_new.php" method="post">
<label>邮箱:<input name="user" value="" type="text" /></label>
<button class="submit" type="submit">创建</button>
</form>
</div>
<?php get_admin_footer();?>
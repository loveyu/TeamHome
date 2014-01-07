<?php
/**
 *	用户修改密码
 *
 *	@file	user/edit_pwd.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();

require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('user','pwd');

$err='';
if(is_post() && POST('type')=='editpwd'){
	//$err=//验证码检测
	$err=$user->edit_pwd(POST());
}
if($err=='OK')redirect(site_url('login.php?notice='.urlencode("你的密码已修改，请重新登录")), 'refresh');
get_admin_header(array(
	'title' => '修改个人密码',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript"),
		array('src'=>site_url('js/user_center_chage_pwd_verify.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="user_center">
<h3>修改密码</h3>
<form class="edit_pwd" id="user_change_pwd" action="edit_pwd.php" method="post">
<?php if(!empty($err))echo '<p class="error">',$err,'</p>';?>
<div>
<ul>

    <li><label for="old_pwd">原始密码:</label><br /><input id="old_pwd" name="pwd" type="password" value="" /><span id="old_info"></span></li>
    <li><label for="new_pwd">新密码:</label><br /><input id="new_pwd" name="new" type="password" value="" /><span id="new_info"></span></li>
    <li><label for="confirm_pwd">确认新密码:</label><br /><input id="confirm_pwd" name="confirm" type="password" value="" /><span id="confirm_info"></span></li>
    <li><button type="submit">确认修改密码</button></li>
    <li class="notice"><p>密码长度必须大于8位</p></li>

</ul>
</div>
<input name="type" type="hidden" value="editpwd" />
</form>
</div>
<?php get_admin_footer();?>
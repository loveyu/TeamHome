<?php
/**
 *	设置中心超级管理员密码输入页面
 *
 *	@file	user/setting_super.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
if($zxsys->is_super_admin())redirect(site_url('user/setting.php','refresh'));
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('setting','super');

$zxtmp->add_admin_menu(//增加一个菜单
	'setting-center',
	array(),
	array(
		array('url'=>'#','name'=>'超级权限','id'=>'setting_super','power'=>array(0,1))
	)
);

get_admin_header(array(
	'title' => '超级用户授权',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));

?>
<div id="setting_center">
<h3>超级用户授权</h3>
<form class="super" action="setting_action.php" method="post">
<label>输入超级账户密码:<input name="pwd" value="" type="password" /></label>
<input name="action" value="super" type="hidden" />
<input type="hidden" name="redirect" value="<?php
echo (GET('redirect')!='')?urldecode(GET('redirect')):$_SERVER['HTTP_REFERER'];
?>" />
<button class="submit" type="submit">确定</button>
</form>
</div>
<?php get_admin_footer();?>
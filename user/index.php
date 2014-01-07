<?php
/**
 *	用户中心首页
 *
 *	@file	user/index.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('user');

$user->check_user_path();
get_admin_header(array(
	'title' => '个人用户中心',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css'),
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
run_plugin('admin_index_body');
?>
<div id="user_center">
<table class="main_table">
<tr><td class="td1">&nbsp;</td><td class="td2">&nbsp;</td></tr>
<tr><td class="td3">&nbsp;</td><td class="td4">

<table class="sub_table">
<tr><th>用户ID</th><td><?php echo get_user_id();?></td><td width="60%" rowspan="6">
&nbsp;
</td></tr>
<tr><th>用户名</th><td><?php echo get_user_name();?></td></tr>
<tr><th>邮箱</th><td><?php echo get_user_email();?></td></tr>
<tr><th>权限</th><td><?php $power_list = $zxuser->get_power_group();echo $power_list[get_power()];unset($power_list)?></td></tr>
<tr><th>头像</th><td><img src="<?php echo get_avata($user->user_info['id']);?>" height="64" width="64" alt="img" /></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
</table>
</td></tr>
</table>
</div>


<?php get_admin_footer();?>
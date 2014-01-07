<?php
/**
 *	个人主页页面综合信息查看
 *
 *	@file	user/web.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web');

get_admin_header(array(
	'title' => '个人主页',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="web_center">
<h3>个人主页预览</h3>
<div class="index">
	<p>在新窗口打开:<a href="<?php echo site_url(get_config('user_path').get_user_name())?>" target="_blank"><?php echo site_url(get_config('user_path').get_user_name())?></a></p>
	<iframe src="<?php echo site_url(get_config('user_path').get_user_name())?>"></iframe>
</div>
</div>
<?php get_admin_footer();?>
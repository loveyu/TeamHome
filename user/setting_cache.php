<?php
/**
 *	缓存设置
 *
 *	@file	user/setting_cache.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('setting','cache');

get_admin_header(array(
	'title' => '清除缓存设置',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="setting_center">
<h3>缓存设置</h3>
<a class="cache" href="?cache=clean">清除缓存</a>
<?php
if(GET('cache')=='clean'){
	global $zxcache;
	$zxcache->clean();
	echo "<p class=\"notice\">缓存已清除</p>";
}
?>
</div>
<?php get_admin_footer();?>
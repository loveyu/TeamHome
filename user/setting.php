<?php
/**
 *	设置中心
 *
 *	@file	user/setting.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
if(!$zxsys->is_super_admin())$zxsys->go_super_verify();//验证超级账户
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('setting');

$sys_style = $zxsys->get_setting('site_style');

get_admin_header(array(
	'title' => '站点设置',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="setting_center">
<h3>系统设置</h3>
<form action="setting_action.php" method="post">
<table>
<tr><th>站点名称</th><td><input name="site_name" value="<?php echo $zxsys->get_setting("site_name")?>" type="text" /></td></tr>
<tr><th>站点描述</th><td><input name="site_description" value="<?php echo $zxsys->get_setting("site_description")?>" type="text" /></td></tr>
<tr><th>网站地址</th><td><input name="site_url" value="<?php echo $zxsys->get_setting("site_url")?>" type="text" /></td></tr>
<tr><th>允许注册</th><td><select name="site_register"><option value="1"<?php if($zxsys->get_setting('site_register')==1)echo ' selected'?>>是</option><option value="0"<?php if($zxsys->get_setting('site_register')==0)echo ' selected'?>>否</option></select></td></tr>
<tr><th>网站样式</th><td><select name="site_style"><?php if(!in_array($sys_style,$zxsys->get_style_list()))$sys_style = 'default';foreach($zxsys->get_style_list() as $v){?>
<option value="<?php echo $v?>"<?php if($sys_style==$v)echo ' selected'?>><?php echo $v?></option>
<?php }?></select></td></tr>
<tr><th>管理员邮箱</th><td><input name="admin_email" value="<?php echo $zxsys->get_setting("admin_email")?>" type="text" /></td></tr>
<tr><th>用户每页数量</th><td><input name="user_manage_one_page_number" value="<?php echo $zxsys->get_setting("user_manage_one_page_number")?>" type="text" /></td></tr>
<tr><th>新闻每页数量</th><td><input name="news_one_page_number" value="<?php echo $zxsys->get_setting("news_one_page_number")?>" type="text" /></td></tr>
<tr><th>登录有效期</th><td><input name="site_cookie_time" value="<?php echo $zxsys->get_setting("site_cookie_time")?>" type="text" /><span>小时</span></td></tr>
<tr valign="top"><th>加密kEY</th><td><input name="site_key" value="<?php echo $zxsys->get_setting("site_key")?>" type="text" /><span>更改后所有登录失效</span></td></tr>
<tr><td class="submit" colspan="2"><button type="submit">更新</button></td></tr>
</table>

<input name="action" value="setting" type="hidden" />
</form>
</div>
<?php get_admin_footer();?>
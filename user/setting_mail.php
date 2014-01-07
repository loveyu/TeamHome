<?php
/**
 *	设置中心邮箱设置
 *
 *	@file	user/setting_mail.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
if(!$zxsys->is_super_admin())$zxsys->go_super_verify();//验证超级账户
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('setting','mail');

load('mail');
$mail = new Mail();
$mail_config_list = $mail->get_config_list();

get_admin_header(array(
	'title' => '邮件设置',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));

?>
<div id="setting_center">
<h3>邮箱设置</h3>
<form action="setting_action.php" method="post">
<table>
<tr><th>邮件UA</th><td><input name="useragent" value="<?php echo $mail_config_list['useragent']?>" type="text" /></td></tr>
<tr><th>发信类型</th><td><select name="type"><option value="smtp"<?php if($mail_config_list['type']=='smtp')echo ' selected'?>>SMTP信箱</option><option value="mail"<?php if($mail_config_list['type']=='mail')echo ' selected'?>>直接发送</option></select></td></tr>
<tr><th>SMTP服务器</th><td><input name="smtp_host" value="<?php echo $mail_config_list['smtp_host']?>" type="text" /></td></tr>
<tr><th>SMTP端口</th><td><input name="smtp_port" value="<?php echo $mail_config_list['smtp_port']?>" type="text" /></td></tr>
<tr><th>SMTP用户</th><td><input name="smtp_user" value="<?php echo $mail_config_list['smtp_user']?>" type="text" /></td></tr>
<tr><th>SMTP密码</th><td><input name="smtp_pass" value="******" type="text" /></td></tr>
<tr><th>SMTP验证</th><td><select name="smtp_auth"><option value="true"<?php if($mail_config_list['smtp_auth']=='true')echo ' selected'?>>开启</option><option value="false"<?php if($mail_config_list['smtp_auth']=='false')echo ' selected'?>>关闭</option></select></td></tr>
<tr><th>SMTP调试</th><td><select name="smtp_debug"><option value="1"<?php if($mail_config_list['smtp_debug']=='1')echo ' selected'?>>开启</option><option value="0"<?php if($mail_config_list['smtp_debug']=='0')echo ' selected'?>>关闭</option></select></td></tr>
<tr><th>邮箱地址</th><td><input name="from_email" value="<?php echo $mail_config_list['from_email']?>" type="text" /></td></tr>
<tr><th>邮箱名</th><td><input name="from_name" value="<?php echo $mail_config_list['from_name']?>" type="text" /></td></tr>
<tr><td colspan="2" class="submit"><button type="submit">更新</button></td></tr>
</table>
<input name="action" value="mail" type="hidden" />

</form>
</div>
<?php get_admin_footer();?>
<?php
/**
 *	插件管理
 *
 *	@file	user/plugin.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();//非管理员禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('plugin');
$sql_plugin = $zxplugin->m_get_sql_list();
$tmp_plugin = $sql_plugin;
get_admin_header(array(
	'title' => '插件管理',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$i=0;
?>
<div id="plugin_center">
<h3>网站插件管理</h3>

<div class="list">
<table>
<tr><th>插件名</th><th colspan="2">操作</th></tr>
<?php foreach($zxplugin->m_get_file_list() as $name){?>
	<tr class="tr_<?php echo $i%2;?>"><td><?php echo $name?><?php if(isset($sql_plugin[$name]))echo "&nbsp;&nbsp;启动类型:",$sql_plugin[$name];?></td>
<?php if(!isset($sql_plugin[$name])){?>
	<td><form method="get" action="plugin_action.php">类型:<select name="type"><?php
foreach($zxplugin->plugin_type() as $v)echo "<option value=\"$v\">$v</option>\n";
?></select>
<input name="name" value="<?php echo $name?>" type="hidden" />
<input name="action" value="active" type="hidden" />
<button type="submit">启用</button>
</form></td>
<?php }else{
unset($tmp_plugin[$name]);
?>
	<td><a href="plugin_action.php?name=<?php echo $name?>&action=stop">停用</a></td>
<?php }?>
	<td><a href="plugin_action.php?name=<?php echo $name?>&action=delete">删除</a></td></tr>
<?php }?>
</table>
</div>
<?php if(!empty($tmp_plugin)){$zxplugin->clear_invalid_plugin($tmp_plugin);?>
<div class="notice">
	<p>已清除&nbsp;<?php echo count($tmp_plugin);?>&nbsp;个无效插件</p>
</div>
<?php }?>
</div>
<?php get_admin_footer();?>
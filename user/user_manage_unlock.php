<?php
/**
 *	用户中心用户锁定操作页面
 *
 *	@file	user/user_manage_unlock.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login() || !is_admin())forbid_page();//非管理员禁止访
require_once('init_user.php');//用户中心初始化操作
$zxtmp->set_page_id('manage','unlock');

if(is_post()){
	switch(POST('type')){
		case 'unlock':
			$s = $user->unlock_user(POST('id'));
		break;
		case 'lock':
			$s = $user->lock_user(POST('id'));
		break;
		default:$s = "未知操作";
	}
	get_close_page($s,false);
}

get_admin_header(array(
	'title' => '解锁与锁定用户操作',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$id = GET('id');
if(!is_numeric($id) || $id<1)$id='';
?>
<div id="user_manage">
<h3>用户锁定</h3>
<form class="lock" action="user_manage_unlock.php" method="post">
<ul>
<li><label>操作ID:<input name="id" value="<?php echo $id?>" type="text" /></label></li>
<li><label>操作类型:<select name="type">
	<option value="lock"<?php if(GET('type')=='lock')echo ' selected="selected"';?>>锁定用户</option>
	<option value="unlock"<?php if(GET('type')=='unlock')echo ' selected="selected"';?>>解锁</option>
</select></label></li>
<li><button class="submit" type="submit">确定操作</button></li>
</ul>
</form>
</div>

<?php get_admin_footer();?>
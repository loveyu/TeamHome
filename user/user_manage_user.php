<?php
/**
 *	用户中心用户邮箱及用户名修改
 *
 *	@file	user/user_manage_user.php
 *
 */
require_once("../init.php");
send_header();
if(!$zxuser->auto_login() || !is_admin())forbid_page();//非管理员禁止
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('manage','name');

$status = array();
if(is_post()){
	if(!$user->is_user_id(POST('id')))$err = '该ID不存在';
	else{
		if(POST('username')!='')$status['username'] = $user->edit_username(POST('id'),POST('username'));
		if(POST('email')!='')$status['email'] = $user->edit_email(POST('id'),POST('email'));
	}
	sGET('id',POST('id'));
}
get_admin_header(array(
	'title' => '修改邮箱及昵称信息',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));?>
<div id="user_manage">
<h3>修改用户邮箱及用户名</h3>
<?php if(!is_numeric(GET('id')) || GET('id')<1 || !($info = $user->get_one_info(GET('id'))) || !isset($info['id'])){ //一条件为赋值语句
if(isset($info))echo '<p class="notice">该用户不存在</p>';
?>
<form class="user" action="" method="get">
<label>输入ID:<input name="id" value="" type="text" /></label>
<button class="submit" type="submit">提交</button>
</form>
<?php }else{?>
<form class="user" action="user_manage_user.php" method="post">
<input name="id" value="<?php echo $info['id']?>" type="hidden"/>
<ul>
<li>当前邮箱:<?php echo $info['user']?></li>
<li>当前用户名:<?php echo $info['username']?></li>
<li><label>修改邮箱:<input name="email" value="<?php echo POST('email')?>" type="text" /><?php if(isset($status['email']))echo "<span>",$status['email'],"</span>";?></label></li>
<li><label>修改用户名:<input name="username" value="<?php echo POST('username')?>" type="text" /><?php if(isset($status['username']))echo "<span>",$status['username'],"</span>";?></label></li>
<li><button class="submit" type="submit">修改</button></li>
</ul>
</form>
<?php }?>
</div>
<?php get_admin_footer();?>
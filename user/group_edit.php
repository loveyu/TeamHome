<?php
/**
 *	编辑用户分组
 *
 *	@file	user/group_edit.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();//非管理员禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('group','edit');
load("group");
$group = new Group();
$group->get_all_group();
$group->parent_info;
get_admin_header(array(
	'title' => '编辑用户分组',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="group_center">
<h3>编辑用户组</h3>
<?php if(isset($group->group[GET('id')])){?>
<form action="group_action.php" method="post">
<input name="id" value="<?php echo GET('id')?>" type="hidden" />
<input type="hidden" value="edit" name="action" />
<table class="new">
<tr><th>编辑分组分组:</th><td><input name="name" value="<?php echo $group->group[GET('id')]['name']?>" type="text" /></td></tr>
<tr><th>组长:</th><td><input name="lader" value="<?php echo $group->group[GET('id')]['lader']?>" type="text" /><?php echo $group->group[GET('id')]['lader']==0?"无":$group->lader[$group->group[GET('id')]['lader']]['name']?></td></tr>
<tr><th>上级分组:</th><td><select name="parent">
<option value="0">无</option>
<?php foreach($group->group as $v)if($v['parent']==0){?>
<option value="<?php echo $v['id']?>" <?php if($v['id']==$group->group[GET('id')]['parent'])echo "selected";?>><?php echo $v['name']?></option>
<?php }?>
</select></td></tr>
<tr valign="top"><th>描述:</th><td><textarea name="des"><?php echo $group->group[GET('id')]['des']?></textarea></td></tr>
<tr><td colspan="2"><button class="submit" type="submit">编辑分组</button></td></tr>
</table>
</form>
<?php }else{?>
<form action="group_edit.php" method="get" class="new">
<label>操作ID:<input name="id" value="" type="text" /></label><button class="submit" type="submit">提交</button>
</form>
<?php }?>
</div>
<?php get_admin_footer();?>
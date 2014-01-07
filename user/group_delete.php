<?php
/**
 *	删除一个用户分组
 *
 *	@file	user/group_delete.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();//非管理员禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('group','delete');
load("group");
$group = new Group();
$group->get_all_group();
$group->parent_info;
get_admin_header(array(
	'title' => '删除用户分组',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="group_center">
<h3>删除分组</h3>
<?php if(isset($group->group[GET('id')])){?>
<form action="group_action.php" method="post">
<ul class="delete">
<li>要删除的分组:<input name="id" value="<?php echo GET('id')?>" type="hidden" /><?php echo $group->group[GET('id')]['name']?>(<?php echo $group->group[GET('id')]['id']?>)</li>
<?php if($group->group[GET('id')]['parent']==0){?><li>移动用户分组到:<select name="move_user">
<?php foreach($group->group as $v)if($v['parent']==0 && $v['id']!=GET('id')){?>
<option value="<?php echo $v['id']?>" ><?php echo $v['name']?></option>
<?php }?>
</select></li>
<li>二级分组移动到:<select name="move_group">
<?php foreach($group->group as $v)if($v['parent']==0 && $v['id']!=GET('id')){?>
<option value="<?php echo $v['id']?>" ><?php echo $v['name']?></option>
<?php }?>
</select></li>
<?php }?>
<li><button class="submit" type="submit">删除分组</button></li>
</ul>
<input type="hidden" name="action" value="delete" />
</form>
<?php }else{?>
<form action="group_delete.php" method="get" class="new">
<label>操作ID:<input name="id" value="" type="text" /></label><button class="submit" type="submit">提交</button>
</form>
<?php }?>
</div>
<?php get_admin_footer();?>
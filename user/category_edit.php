<?php
/**
 *	编辑一个分类
 *
 *	@file	user/category_edit.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('category','edit');
$zxtmp->add_admin_menu(//增加一个菜单
	'category-center',
	array(),
	array(
		array('url'=>'#','name'=>'编辑分类','id'=>'category_edit','power'=>array(0))
	)
);
$cat = $zxcat;
$info=$cat->get_one_category(GET('id'));

get_admin_header(array(
	'title' => '编辑分类',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="category_center">
<h3>编辑分类</h3>
<?php if(empty($info)){?>
<p class="no_found">该分类不存在</p>
<?php }else{?>
<form class="new" action="category_action.php" method="post">
<table>
<tr><th>分类名称:</th><td><input name="name" value="<?php echo $info['name']?>" type="text" /></td></tr>
<tr><th>别名:</th><td><input name="alias" value="<?php echo $info['alias']?>" type="text" /><span>必须为英文及数字</span></td></tr>
<tr valign="top"><th>描述:</th><td><textarea name="description"><?php echo $info['description']?></textarea></td></tr>
<tr><td colspan="2"><button class="submit" type="submit">编辑</button></td></tr>
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="id" value="<?php echo $info['id']?>" />
</table>
</form>
<?php }?>
</div>

<?php get_admin_footer();?>
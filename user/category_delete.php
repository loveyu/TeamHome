<?php
/**
 *	删除一个分类
 *
 *	@file	user/category_delete.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('category','delete');
$zxtmp->add_admin_menu(//增加一个菜单
	'category-center',
	array(),
	array(
		array('url'=>'#','name'=>'删除分类','id'=>'category_delete','power'=>array(0))
	)
);
$cat = $zxcat;
$info=$cat->get_one_category(GET('id'));

if(!empty($info))$cat_info = $cat->get_category($info['type']);

get_admin_header(array(
	'title' => '删除分类',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="category_center">
<h3>删除某一分类</h3>
<?php if(empty($info)){?>
<p class="no_found">该分类不存在</p>
<?php }else{?>
<form class="new" action="category_action.php" method="post">
<table>
<tr><th>分类名称：</th><td><?php echo $info['name']?></td></tr>
<tr><th>别名：</th><td><?php echo $info['alias']?></td></tr>
<tr><th>描述：</th><td><?php echo $info['description']?></td></tr>
<?php if(count($cat_info)>1){?>
<input type="hidden" name="action" value="delete" />
<tr><th>移动分类到：</th><td><select name="move"><?php foreach($cat_info as $v)if($v['id']!=$info['id']){?>
<option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
<?php }?></select></td></tr>
<input type="hidden" name="id" value="<?php echo $info['id']?>" />
<tr><td colspan="2"><button class="submit" type="submit">删除</button></td></tr>
</table>
</form>
<?php }else{ ?>
<p class="no_found">该分类为唯一分类，无法删除</p>
<?php }?>

<?php }?>
</div>
<?php get_admin_footer();?>
<?php
	/**
	 *    分类中心
	 * @file    user/category.php
	 */
	require_once("../init.php");
	if(!$zxuser->auto_login())
		$zxuser->redirect_to_login();
	if(!is_admin())
		forbid_page();
	require_once('init_user.php'); //用户中心初始化
	$zxtmp->set_page_id('category');

	$cat = $zxcat;

	$cat_list = $cat->get_all_category();

	get_admin_header(array('title' => '分类管理', 'link' => array(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css')), 'script' => array(array('src' => site_url('js/admin.js'), 'type' => "text/javascript"))));
	$i = 0;
?>
<div id="category_center">
	<h3>所有分类</h3>
	<table class="list">
		<thead>
		<tr>
			<th>ID</th>
			<th>名称</th>
			<th>别名</th>
			<th>描述</th>
			<th>类型</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		<?php if(is_array($cat_list))
			foreach($cat_list as $v){ ?>
			<tr class="tr_<?php echo (++$i) % 2;?>">
				<td><?php echo $v['id']?></td>
				<td><?php echo $v['name']?></td>
				<td><?php echo $v['alias']?></td>
				<td><?php echo $v['description']?></td>
				<td><?php echo $cat->get_allow_type($v['type'])?></td>
				<td>
					<a href="category_edit.php?id=<?php echo $v['id']?>">编辑</a>&nbsp;<a href="category_delete.php?id=<?php echo $v['id']?>">删除</a>
				</td>
			</tr>
				<?php
			}if($i == 0)
			echo '<tr class="no_category"><td colspan="6">没有找到任何分类</td></tr>';?>
		</tbody>
	</table>
</div>
<?php get_admin_footer(); ?>
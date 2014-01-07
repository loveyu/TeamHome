<?php
/**
 *	新闻中心
 *
 *	@file	user/news.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(get_power()>1)forbid_page();//非管理组禁止访
require_once('init_user.php');//用户中心初始化

$zxtmp->set_page_id('news');
load('news');
$news  = new News();

if(!iGET('num'))sGET('num',15);

$list = $news->get_category_post(GET('cat'),GET('type'),GET('page'),GET('num'));

get_admin_header(array(
	'title' => '新闻中心',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$i=0;
?>
<div id="new_center">
<h3>新闻管理</h3>
<table class="list">
<tr><th>ID</th><th>标题</th><th>作者</th><th>时间</th><th>分类</th><th>类型</th><th>允许评论</th><?php if(is_admin())echo '<th>发布者</th>'?><th>操作</th></tr>
<?php foreach($list as $v){?>
<tr class="tr_<?php echo (++$i)%2;?>">
<td><?php echo $v['id']?></td><td><a href="<?php echo site_url('view.php?id='.$v['id'])?>"><?php echo $v['title']?></a></td><td><?php echo $v['author']?></td><td><?php echo $v['time']?></td><td><?php echo $news->get_category_list($v['category'])?></td><td><?php echo $v['type']?></td>
<td><?php echo $v['comment']?></td>
<?php if(is_admin())echo "<td>",$v['user'],"</td>";?>
<td><a href="news_edit.php?id=<?php echo $v['id']?>">编辑</a><?php if(is_admin()){?><a href="news_post_edit.php?action=delete&id=<?php echo $v['id']?>">&nbsp;删除</a><?php }?></td>
</tr>
<?php }if($i==0)echo '<tr class="no_category"><td colspan="',is_admin()?'9':'8','">没有发现内容</td></tr>';?>
</table>
</div>
<?php out_Navigation_list($news->page_info['all_page'],GET('page'),"news.php?page=%%","list","now","nav_list"); ?>

<?php get_admin_footer();?>
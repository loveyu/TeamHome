<?php
/**
 *	个人主页页面管理
 *
 *	@file	user/web_manage_page.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web','page');
$user->check_user_path();//检查用户文件夹是否齐全
load('file_action');
$file = new File_action();

$list = $file->get_user_type_file_list('page');

get_admin_header(array(
	'title' => '个人页面管理',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$i=0;
?>
<div id="web_center">
<h3>页面管理</h3>
<form class="creat" action="web_edit_action.php" method="post">
<ul>
<li>创建一个新的页面</li>
<li><label>名称:<input name="name" type="text" value="" />.html</label></li>
<li><button type="submit">创建</button></li>
</ul>
<input name="type" value="creat_page" type="hidden" />
</form>
<table class="file_m">
<tr><th>文件名</th><th>大小</th><th>修改日期</th><th>操作</th></tr>
<?php foreach($list as $v){?>
<tr class="tr_<?php echo ++$i%2?>"><td><a href="<?php echo site_url(get_config('user_path').get_user_name().'/'.$v['name']);?>" target="_blank"><?php echo $v['name']?></a></td><td><?php echo $file->size($v['size'])?></td><td><?php echo date("Ymd H:i:s",$v['time']);?></td><td><a href="web_edit.php?file=<?php echo $v['name']?>">编辑</a>&nbsp;<a href="web_rename.php?file=<?php echo $v['name']?>">重命名</a>&nbsp;<a href="web_edit_action.php?type=delete&path=<?php echo $v['name']?>">删除</a></td></tr>
<?php }?>
<?php if($i==0)echo "<tr class=\"no_file\"><td colspan=\"4\">没有文件被创建</td></tr>\n";?>
</table>
</div>
<?php get_admin_footer();?>
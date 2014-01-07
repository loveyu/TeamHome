<?php
/**
 *	个人主页文件管理
 *
 *	@file	user/web_manage.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web','file');
$user->check_user_path();//检查用户文件夹是否齐全

load('file_action');
$file = new File_action();

if(GET('dir')=='')sGET('dir','/');//修正dir路径不正确的问题
else if(substr(GET('dir'),-1,1)!='/')sGET('dir',GET('dir').'/');

$list = $file->get_user_dir_list(GET('dir'));

get_admin_header(array(
	'title' => '个人文件管理',
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
<h3>文件管理</h3>
<table class="file_m">
<tr><th>文件名</th><th>编辑</th><th>删除</th></tr>
<tr class="last_path"><td colspan="3"><a href="?dir=<?php echo str_replace('\\','/',dirname(GET('dir')))?>" title="返回上级目录">上级目录</a></td></tr>

<?php foreach($list['dir'] as $v){?>
<tr class="tr_<?php echo ++$i%2?>"><td>[D]&nbsp;<a title="打开文件夹" href="?dir=<?php echo GET('dir').$v?>"><?php echo $v;?></a></td><td></td><td><a href="web_edit_action.php?type=delete&path=<?php echo GET('dir').'/'.$v?>">删除</a></td></tr>
<?php }
foreach($list['file'] as $v){?>
<tr class="tr_<?php echo ++$i%2?>"><td>[F]&nbsp;<a title="浏览器中打开文件" href="<?php echo site_url(get_config('user_path').get_user_name().GET('dir').$v)?>" target="_blank"><?php echo $v;?></a></td><td><?php if($file->is_text($v))echo '<a href="web_edit.php?file=',GET('dir'),$v,'">编辑</a>&nbsp;';?><a href="web_rename.php?file=<?php echo GET('dir').$v?>">重命名</a></td><td><a href="web_edit_action.php?type=delete&path=<?php echo GET('dir').$v?>">删除</a></td></tr>
<?php }?>
</table>

</div>
<?php get_admin_footer();?>
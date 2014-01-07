<?php
/**
 *	个人主页文件命令
 *
 *	@file	user/web_rename.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web','rename');

load('file_action');
$file = new File_action();
$filename = '';
if($file->is_user_file(GET('file'))){
	$filename = basename(GET('file'));
}

$zxtmp->add_admin_menu(//增加一个菜单
	'web-center',
	array(),
	array(
		array('url'=>'#','name'=>'文件重命名','id'=>'web_rename','power'=>array(0,1,2))
	)
);
get_admin_header(array(
	'title' => $filename?'重命名 '.$filename:'重命名未知文件',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="web_center">
<h3>文件重命名</h3>
<?php if($filename){?>
<form action="web_edit_action.php" method="post">
<ul class="creat rename">
<li>文件路径:<input name="path" value="<?php echo GET('file')?>" type="text" /><p><?php if(!$filename)echo '该文件不存在或文件为目录';?><p></li>
<li>新文件名:<input name="new" value="<?php if($filename)echo substr($filename,0,strlen($filename)-1-strlen(pathinfo($filename, PATHINFO_EXTENSION)));?>" />.<?php echo pathinfo($filename, PATHINFO_EXTENSION)?><p>(文件名只允许数字,字母,和 _ )</p></li>
<input type="hidden" name="type" value="rename" />
<li><button type="submit">修改</button></li>
</ul>
</form>
<?php }else{?>
<p class="edit_error">没有找到要重命名的文件</p>
<?php }?>
</div>
<?php get_admin_footer();?>

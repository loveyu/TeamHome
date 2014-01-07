<?php
/**
 *	文件附件管理
 *
 *	@file	user/file_manage.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('file','style');

load('file_action');
$file = new File_action();
load('kindeditor',true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文件

$theme = GET('theme');
$tfile = GET('file');

if(!in_array($theme,$zxsys->get_style_list())){
	$theme = $zxsys->get_setting('site_style');
	$tfile = "style";
}
if(!$file->is_style_file($theme,$tfile)){
	$tfile = "style";
}
$style_content = $file->get_style_file_content($theme,$tfile);

get_admin_header(array(
	'title' => '网站样式文件管理',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="file_center">
<h3>网站样式文件管理</h3>
<form class="edit" action="file_style_action.php" method="post"><!-- 编辑框 -->
<p class="file_info">当前文件:<span><?php echo $tfile?>.css</span>主题:<select id="theme_select" name="theme"><?php foreach($zxsys->get_style_list() as $v){?>
<option value="<?php echo $v?>"<?php if($theme==$v)echo ' selected'?>><?php echo $v?></option>
<?php }?></select>&nbsp;&nbsp;
<button class="submit" type="submit">保存</button></p>
<p class="file_info">
<?php
foreach($file->get_style_theme_css_list($theme) as $v){
	echo "<a href=\"file_style.php?theme=$theme&file=",substr($v['name'],0,-4),"\">",$v['name'],"</a>&nbsp;&nbsp;";
}
?>
</p>
<textarea name="content" style="width:99.9%;height:400px;visibility:hidden;"><?php echo htmlspecialchars($style_content,ENT_QUOTES);?></textarea>
<input type="hidden" name="file" value="<?php echo $tfile?>" />
<input type="hidden" name="type" value="edit_file" />
</form>
<p class="notice">如果当文件保存之后乱码，请将网页中的编码修改为`UTF-8`,因为文件统一保存为UTF-8编码。</p>
<script>
	$("#theme_select").change(function(){
			window.location=SITE_URL+"user/file_style.php?theme="+$("#theme_select").val();
	});
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			resizeType : 1,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			designMode : false,
			items : ['fullscreen']
		});
	});
</script>
</div>

<?php get_admin_footer();?>
<?php
/**
 *	个人主页页面编辑页面
 *
 *	@file	user/web_edit.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!$zxuser->is_active())redirect_active();
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web','edit');
$zxtmp->add_admin_menu(//增加一个菜单
	'web-center',
	array(),
	array(
		array('url'=>'#','name'=>'编辑页面','id'=>'web_edit','power'=>array(0,1,2))
	)
);
load('file_action');
$file = new File_action();
load('kindeditor',true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文件

if($file->is_user_file(GET('file'),true)){
	$file_content = $file->get_user_file(GET('file'));
	if(GET('charset')!='' && GET('charset')!='UTF-8')//编码转换
		$file_content = mb_convert_encoding($file_content,"UTF-8",GET('charset'));
}

get_admin_header(array(
	'title' => '编辑页面',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<div id="web_center">
<h3>编辑文件</h3>
<?php if(isset($file_content)){?>
<form class="edit_charset" action="web_edit.php" method="get"><!-- 编码切换 -->
<input type="hidden" name="file" value="<?php if(isset($file_content))echo GET('file')?>" />
<select name="charset"><option value="UTF-8"<?php if(GET('charset')=='UTF-8')echo ' selected="selected"';?>>UTF-8</option><option value="GB2312"<?php if(GET('charset')=='GB2312')echo ' selected="selected"';?>>GB2312</option><option value="GBK"<?php if(GET('charset')=='GBK')echo ' selected="selected"';?>>GBK</option></select><button type="submit">切换编码</button>
</form>

<form class="edit" action="web_edit_action.php" method="post"><!-- 编辑框 -->
<p class="file_info">当前文件:<span><?php echo basename(GET('file'))?></span>路径:<span><?php echo GET('file')?></span><button class="submit" type="submit">保存</button></p>
<textarea name="content" style="width:99.9%;height:400px;visibility:hidden;"><?php if(isset($file_content))echo htmlspecialchars($file_content,ENT_QUOTES);?></textarea>
<input type="hidden" name="file" value="<?php if(isset($file_content))echo GET('file')?>" />
<input type="hidden" name="type" value="edit_file" />
</form>
<p class="notice">如果当文件保存之后乱码，请将网页中的编码修改为`UTF-8`,因为文件统一保存为UTF-8编码。</p>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true,
			urlType : 'domain',
			designMode : false, 
		});
	});
</script>
<?php }else{?>
<p class="edit_error">文件不存在</p>
<?php }?>
</div>
<?php get_admin_footer();?>
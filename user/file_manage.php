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
$zxtmp->set_page_id('file');

load('file_action');
$file = new File_action();
load('kindeditor',true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文件

if(GET('dir')=='')sGET('dir','/');//修正dir路径不正确的问题
else if(substr(GET('dir'),-1,1)!='/')sGET('dir',GET('dir').'/');

$list = $file->get_attach_dir_list(GET('dir'));

get_admin_header(array(
	'title' => '网站文件管理',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$i=0;
?>
<div id="file_center">
<h3>网站文件管理</h3>
<form class="creat"><input type="button" id="uploadButton" value="上传文件" /></form>
<form class="creat" action="file_action.php" method="post">
<input class="input" name="name" value="" type="text" />
<button type="submit">创建文件夹</button>
<input name="type" value="new_dir" type="hidden" /> 
<input name="path" value="<?php echo GET('dir');?>" type="hidden" />
</form>
<form class="creat" action="file_action.php" method="post">
<input name="path" value="<?php echo GET('dir')?>" type="hidden" />
<input class="input" name="name" value="" type="text" />.
<select name="ext">
<?php foreach($file->text_array() as $v)echo '<option value="',$v,'">',$v,'</option>';?>
</select>
<input name="type" type="hidden" value="creat" />
<button type="submit">创建文件</button>
</form>
<div class="clear"></div>
<table class="file_m">
<tr><th>文件名</th><th>编辑</th><th>删除</th></tr>
<tr class="last_path"><td colspan="3"><a href="?dir=<?php echo str_replace('\\','/',dirname(GET('dir')))?>">上级目录</a></td></tr>

<?php foreach($list['dir'] as $v){?>
<tr class="tr_<?php echo ++$i%2?>"><td>[D]&nbsp;<a href="?dir=<?php echo urlencode(GET('dir').$v)?>"><?php echo $v;?></a></td><td></td><td><a href="file_action.php?type=delete&path=<?php echo GET('dir').'/'.$v?>">删除</a></td></tr>
<?php }
foreach($list['file'] as $v){?>
<tr class="tr_<?php echo ++$i%2?>"><td>[F]&nbsp;<a href="<?php echo site_url(str_replace("//",'/',get_config('attach_path').GET('dir').$v))?>" target="_blank"><?php echo $v;?></a></td><td><?php if($file->is_text($v))echo '<a href="file_edit.php?file=',urlencode(GET('dir').$v),'">编辑</a>&nbsp;';?><a href="file_rename.php?file=<?php echo urlencode(GET('dir').$v)?>&">重命名</a></td><td><a href="file_action.php?path=<?php echo urlencode(GET('dir').$v)?>&type=delete">删除</a></td></tr>
<?php }if($i==0)echo "<tr class=\"no_file\"><td colspan=\"3\">没有文件或文件夹被创建</td></tr>\n";?>
</table>

</div>
<script>
	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'imgFile',
			url : '<?php echo site_url()?>kindeditor/php/attach_up.php?dir=file&path=<?php echo urlencode(GET('dir'))?>',
			afterUpload : function(data) {
				if (data.error === 0) {
					location.reload();
				} else {
					alert(data.message);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
</script>

<?php get_admin_footer();?>
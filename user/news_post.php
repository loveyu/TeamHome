<?php
/**
 *	新闻中心发新新闻
 *
 *	@file	user/news_post.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(get_power()>1)forbid_page();//非管理组禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('news','post');

load('news');
$news = new News();

load('kindeditor',true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文

//加载关于时间的选择器
add_jquery("themes/base/jquery-ui.css");
add_jquery("jquery-ui.min.js");
add_jquery("jquery-ui-timepicker-addon.js");

get_admin_header(array(
	'title' => '发布新闻中心',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript"),
	)
));
?>
<div id="new_center">
<h3>新的内容</h3>
<form method="post" action="news_post_edit.php">
<div class="title">
<label>标题:<input name="title" value="" type="text" /></label>
</div>

<div class="type">
类型:<select name="category">
<?php foreach($news->get_category_list() as $v1_id => $v1){?>
<option value="<?php echo $v1_id?>"><?php echo $v1?></option>
<?php }?>
</select>
<label>时间:<input id="post_time" name="time" value="" type="text" /></label>
<label>发布者:<input name="author" value="<?php echo $user->get_true_name()?>" type="text" /></label>
<label>允许评论:<select name="comment"><option value="false">禁止</option><option value="true">允许</option></select></label>
<label>状态:<select name="type"><option value="publish">发布</option><option value="draft">草稿</option></select></label>
<button type="submit" class="submit">发布文章</button>
</div>
<textarea name="content" style="width:99.8%;height:600px;visibility:hidden;"></textarea>
<input name="action" value="post" type="hidden" />
</form>
</div>
<script>
<!--
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true,
			urlType : 'absolute',
			designMode : true, //显示模式,是否以可视化显示
			uploadJson : '<?php echo site_url('/kindeditor/php/news_file_upload.php')?>',
			fileManagerJson : '<?php echo site_url('/kindeditor/php/news_file_manage.php')?>',
			 
		});
	});
	$(function() {
		$( "#post_time" ).datetimepicker({
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
			dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
			dateFormat:"yy-mm-dd",
		});
    });
//-->
</script>
<?php get_admin_footer();?>
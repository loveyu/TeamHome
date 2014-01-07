<?php
/**
 *	新闻中心新闻编辑
 *
 *	@file	user/news_edit.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(get_power()>1)forbid_page();//非管理组禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('news','edit');
$zxtmp->add_admin_menu(//增加一个菜单
	'news-center',
	array(),
	array(
		array('url'=>'#','name'=>'编辑新闻','id'=>'news_edit','power'=>array(0,1))
	)
);

load('news');
$news  = new News();
$info = $news->get_news(GET('id'));

if(!empty($info)){//数据存在时加载编辑器
	load('kindeditor',true);//加载文本编辑器函数库
	add_kindeditor();//引入文本编辑器头文
	
	//加载关于时间的选择器
	add_jquery("themes/base/jquery-ui.css");
	add_jquery("jquery-ui.min.js");
	add_jquery("jquery-ui-timepicker-addon.js");
}

get_admin_header(array(
	'title' => '发布新闻中心',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
?>
<?php if(!empty($info)){//数据存在时输出编辑框?>
<div id="new_center">
<h3>新的内容</h3>
<form method="post" action="news_post_edit.php">
<div class="title">
<label>标题:<input name="title" value="<?php echo $info['title']?>" type="text" /></label>
</div>

<div class="type">
<label>类型:<select name="category">
<?php foreach($news->get_category_list() as $v1_id => $v1){?>
<option value="<?php echo $v1_id?>"<?php if($info['category']==$v1_id)echo ' selected'?>><?php echo $v1?></option>
<?php }?>
</select></label>
<label>时间:<input name="time" id="post_time" value="<?php echo $info['time']?>" type="text" /></label>
<label>发布者:<input name="author" value="<?php echo $info['author']?>" type="text" /></label>
<label>允许评论:<select name="comment"><option value="false"<?php if($info['comment']=='false')echo ' selected';?>>禁止</option><option value="true"<?php if($info['comment']=='true')echo ' selected';?>>允许</option></select>
<label>状态:<select name="type"><option value="publish"<?php if($info['type']=='publish')echo ' selected';?>>发布</option><option value="draft"<?php if($info['type']=='draft')echo ' selected';?>>草稿</option><option value="hidden"<?php if($info['type']=='hidden')echo ' selected';?>>隐藏</option></select></label>
<button type="submit" class="submit">更新内容</button>
</div>
<textarea name="content" style="width:99.8%;height:600px;visibility:hidden;"><?php echo htmlspecialchars($info['content'])?></textarea>
<input name="action" value="edit" type="hidden" />
<input name="id" value="<?php echo $info['id']?>" type="hidden" />
</form>
<script>
<!--
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true,
			urlType : 'absolute',
			designMode : false, //显示模式,是否以可视化显示
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
</div>
<?php }else{?>
<p style="color:red;">文章不存在</p>
<?php }get_admin_footer();?>
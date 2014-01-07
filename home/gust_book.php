<?php
	load('news');
	$news = new News();
	//$list = Theme_get_news_list($id,$news);
	set_page_id('gust_book');
	get_header(array('title' => '留言咨询'));
?>
<div class="content_resize">
	<div class="mainbar">
		<div class="article">
			<h2>留言中心</h2>

			<p>如果想了解更多的信息或者对我们的工作有所建议，请在下方的留言板中予以回复 </p>
		</div>
	</div>
	<?php PHome_load('sidebar',array("news"=>$news))?>
	<div class="clr"></div>
</div>
<?php get_footer(); ?>
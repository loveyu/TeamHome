<?php
	load('news');
	$news = new News();
	if(isset($id)){
		$post = $news->get_one_post_news($id, '', 'publish');
		if(empty($post))
			PHome_load(404);
	}
	else{
		PHome_load(404);
	}
	global $zxtmp, $zxcat;
	$post_cat = $zxcat->get_one_category($post['category']);

	$zxtmp->set_page_id($post_cat['alias']);
	get_header(array('title' => $post['title']));
?>
<div class="content_resize">
	<div class="mainbar">
		<div class="article">
			<h2><span><?php echo $post['title']?></span></h2>
			<p class="tag">编辑者：<span><?php echo $post['author']?></span> | 分类:<span><a href="<?php echo site_url()?>cat/<?php echo $post_cat['id']?>" ><?=$post_cat['name']?></a></span></p>
			<?php echo $post['content'];?>
			<p class="tag">发布时间:<span><?php echo Theme_format_time($post['time'])?></span> | 作者:<span><?=$post['author']?></span></p>
		</div>
		<?php if($post['comment'] == 'true'){ ?><?php } ?>
	</div>
	<?php PHome_load('sidebar',array("news"=>$news))?>
	<div class="clr"></div>
</div>
<?php get_footer(); ?>
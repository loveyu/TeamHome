<?php
	load('news');
	$news = new News();
	set_page_id('index');
	get_header();
	$list = Theme_get_news($news, 1);
	$cat_list = Theme_get_cat_names($list, "category");
?>
<div class="content_resize">
	<div class="mainbar home">
		<div class="article">
			<h2><span>计算机系介绍</span></h2>
			<?php echo get_sys()->get_setting('team_about')?>
		</div>
		<?php foreach($list as $v){ ?>
		<div class="article">
			<h2><span><?php echo $v['title']?></span>（新闻动态）</h2>

			<p class="tag">编辑者：<span><?php echo $v['author']?></span> | 分类标签：<a href="<?php echo site_url()?>cat/<?php echo $v["category"]?>"><?php echo $cat_list[$v['category']]['name']?></a></p>
			<?php $img = Theme_get_content_first_img($v['content'], null);if($img): ?>
			<img src="<?=$img?>" alt=""/>
			<?php endif;?>
			<?php echo Theme_get_text_content($v['content'], 300)?>

			<p class="tag"><a href="<?php echo site_url(), $v['id']?>.html" class="obg">显示更多</a> | <span><?php echo Theme_format_time($v['time'])?></span></p>
		</div>
		<?php }?>
	</div>
	<?php PHome_load('sidebar',array("news"=>$news))?>
	<div class="clr"></div>
</div>
<?php get_footer(); ?>
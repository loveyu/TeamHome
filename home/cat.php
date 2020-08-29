<?php

load('news');
$news = new News();
if (!isset($id) || !in_array($id, array_flip($news->get_category_list()))) {
    PHome_load(404);
}
$list = $news->get_category_post($id, 'publish', GET('p'), 0, 'title,time,id,author,category,content');
$cat = $news->get_category_list($id);
get_header(['title' => '文章分类 - '.$cat]);
?>
  <div class="content_resize">
    <div class="mainbar">
      <h2>文章存档：<?= $cat ?></h2>
        <?php foreach ($list as $v) { ?>
          <div class="article">
            <h2><a href="<?php echo site_url(), $v['id'] ?>.html"><span><?php echo $v['title'] ?></span></a></h2>
            <p class="tag">编辑者：<span><?php echo $v['author'] ?></span> | 分类:<span><a
                  href="<?php echo site_url() ?>cat/<?php echo $v['category'] ?>"><?php echo $cat ?></a></span></p>
              <?php echo Theme_get_text_content($v['content'], 150) ?>
            <p class="tag">发布时间:<span><?php echo Theme_format_time($v['time']) ?></span></p>
          </div>
        <?php } ?>
        <?php out_Navigation_list($news->page_info['all_page'], GET('p'),
            site_url()."cat/$id?p=%%".((GET('id')) ? '&id='.GET('id') : ''), "list", "now", "nav_list", 'info_nav'); ?>
    </div>
      <?php PHome_load('sidebar', ["news" => $news]) ?>
    <div class="clr"></div>
  </div>
<?php get_footer(); ?>
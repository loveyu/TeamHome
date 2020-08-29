<div class="sidebar">
  <div class="gadget">
    <h2 class="star">分类列表</h2>
    <ul class="sb_menu">
        <?php
        if (isset($news) && is_object($news)) {
            foreach ($news->get_category_list() as $id => $name) {
                echo "\t\t\t<li><a href=\"".site_url()."cat/$id\">$name</a></li>\n";
            }
        } ?>
    </ul>
  </div>
  <div class="gadget">
    <h2 class="star"><span>新闻动态</span></h2>
    <ul class="ex_menu">
        <?php
        if (isset($news) && is_object($news)) {
            $list = Theme_get_news_list_no_content($news);
            foreach ($list as $v) {
                ?>
              <li><a href="<?php echo site_url(), $v['id'] ?>.html" title="<?php echo $v['title'] ?>"><?php
                      echo $v['title'] ?></a><br/>编辑者：<?php echo $v['author'] ?> &nbsp;|&nbsp;
                上传时间：<?php echo Theme_format_time($v['time']) ?></li>
                <?php
            }
        } ?>
    </ul>
  </div>
</div>
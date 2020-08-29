<?php

load('news');
$news = new News();
//$list = Theme_get_news_list($id,$news);
set_page_id('teacher');
get_header(['title' => '师资状况']);
?>
  <div class="content_resize">
    <div class="mainbar">
      <div class="article">
        <div class="article">
          <h2>师资队伍</h2>

          <p>测试内容：教授，副教授 研究生，博士，各多少名</p>

          <p>老师个人介绍</p>
        </div>
        <div class="article">
          <h2>取得成就</h2>

          <p>
            测试内容：系里共获得的奖项
          </p>
        </div>
      </div>
    </div>
      <?php PHome_load('sidebar', ["news" => $news]) ?>
    <div class="clr"></div>
  </div>
<?php get_footer(); ?>
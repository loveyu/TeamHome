<?php

/**
 *  分类中心
 *
 * @file  user/category.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
if (!is_admin()) {
    forbid_page();
}
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('closed');

$cat = $zxcat;

$cat_list = $cat->get_all_category();

get_admin_header([
    'title'  => '页面被关闭',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$i = 0;
?>
  <div id="closed">
    <h2>之前访问的页面被关闭</h2>
  </div>
<?php get_admin_footer(); ?>
<?php

/**
 *  插件页面
 *
 * @file  user/plugin_page.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
if (!is_admin()) {
    forbid_page();
}//非管理员禁止访
require_once('init_user.php');//用户中心初始化
$plugin_status = false;
$plugin_status = $zxplugin->check_plugin_page(GET('id'), GET('p'));
if ($plugin_status) {
    $zxtmp->set_page_id(GET('m'), GET('p'));
} else {
    $zxtmp->add_admin_menu(//增加一个菜单
        'plugin-center',
        [],
        [
            ['url' => '#', 'name' => '插件页面不存在', 'id' => 'plugin_404', 'power' => [0]],
        ]
    );
    $zxtmp->set_page_id('plugin_404');
}
get_admin_header([
    'title'  => $plugin_status ? $zxtmp->get_admin_menu_title(GET('m'), GET('p')) : "插件页面不存在",
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
if (!$plugin_status) {
    ?>
  <div id="plugin_center">
    <div class="notice">
      <p class="error">插件页面不存在</p>
    </div>
  </div>
<?php } else {
    load_plugin_page(GET('id'), GET('p'));

    ?>
<?php }
get_admin_footer(); ?>
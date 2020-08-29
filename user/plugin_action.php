<?php

/**
 *  插件管理
 *
 * @file  user/plugin_action.php
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
$zxtmp->set_page_id('plugin', 'action');
$sql_plugin = $zxplugin->m_get_sql_list();
$zxtmp->add_admin_menu(//增加一个菜单
    'plugin-center',
    [],
    [
        ['url' => '#', 'name' => '插件操作', 'id' => 'plugin_action', 'power' => [0]],
    ]
);
get_admin_header([
    'title'  => '插件管理操作',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$i = 0;
?>
  <div id="plugin_center">
    <h3>插件管理操作</h3>

      <?php if (GET('action')) { ?>
        <div class="notice">
            <?php
            $s = '';
            switch (GET('action')) {
                case 'active':
                    $s = $zxplugin->m_active(GET('name'), GET('type'));
                    break;
                case 'stop':
                    $s = $zxplugin->m_stop(GET('name'));
                    break;
                case 'delete':
                    $s = $zxplugin->m_delete(GET('name'));
                    break;
            }
            if ($s == "OK") {
                echo "<p>操作成功</p>";
            } else {
                if ($s == '') {
                    echo "<p class=\"error\">未知操作</p>";
                } else {
                    echo "<p class=\"error\">", $s, "</p>";
                }
            }
            ?>
        </div>
      <?php } ?>


  </div>
<?php get_admin_footer(); ?>
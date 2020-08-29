<?php

/**
 *  用户中心用户密码重置页面
 *
 * @file  user/user_manage_restpwd.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
send_header();
if (!is_admin()) {
    forbid_page();
}//非管理员禁止访问
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('manage', 'restpwd');

$err = '';
if (is_post()) {
    if (is_numeric(POST('id')) && POST('id') > 0) {
        $err = $user->rest_password(POST('id'), 'unlock');
    } else {
        $err = '表单有误';
    }
    if ($err == 'OK') {
        redirect(site_url('user/user_manage_restpwd.php?type=OK&id='.POST('id')), 'refresh');
    }
}
get_admin_header([
    'title'  => '用户密码重置',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="user_manage">
    <h3>重置用户密码</h3>
      <?php if ($err) {
          echo "<p class=\"notice\">$err</p>\n";
      } ?>
    <form class="rest_pwd" action="user_manage_restpwd.php" method="post">
      <label>重置密码账户ID：<input name="id" value="<?php echo GET('id') ?>" type="text"/></label>
      <button class="submit" type="submit">密码重置</button>
    </form>
  </div>
<?php get_admin_footer(); ?>
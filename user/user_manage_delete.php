<?php

/**
 *  用户中心删除用户操作
 *
 * @file  user/user_manage_delete.php
 *
 */
require_once("../init.php");
send_header();
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}//非管理员禁止访
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('manage', 'delete');

if (is_post()) {
    switch (POST('type')) {
        case 'del':
            $s = $user->delete_user(POST('id'));
            break;
        case 'lock':
            $s = $user->lock_user(POST('id'));
            break;
        default:
            $s = "未知操作";
    }
    get_close_page($s, false);
}

get_admin_header([
    'title'  => '删除或锁定用户操作',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$id = GET('id');
if (!is_numeric($id) || $id < 1) {
    $id = '';
}
?>
  <div id="user_manage">
    <h3>删除用户</h3>
    <form class="delete" action="user_manage_delete.php" method="post">
      <ul>
        <li><label>被删除用户删除ID:<input name="id" value="<?php echo $id ?>" type="text"/></label></li>
        <li><label>你的操作类型:<select name="type">
              <option value="lock"<?php if (GET('type') == 'lock') {
                  echo ' selected="selected"';
              } ?>>仅锁定用户
              </option>
              <option value="del"<?php if (GET('type') == 'del') {
                  echo ' selected="selected"';
              } ?>>删除全部数据
              </option>
            </select></label></li>
        <li>
          <button class="submit" type="submit">确定操作</button>
        </li>
    </form>
  </div>

<?php get_admin_footer(); ?>
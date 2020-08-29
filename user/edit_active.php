<?php

/**
 *  用户激活
 *
 * @file  user/edit_active.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('user', 'active');


$info = $user->get_info();//获取用户个人信息
get_admin_header([
    'title'  => '激活账户',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
        ['src' => site_url('js/user_center_active_verify.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="user_center">
    <h3>用户激活</h3>
    <form action="edit_action.php" method="post" id="user_center_active_form">
      <table class="active">
        <tr valign="top">
          <th>输入用户名</th>
          <td>
            <input name="username" id="user_center_active_username" value="<?php echo $info['username'] ?>"
                   type="text"/>
            <p id="user_center_active_info" class="active_info">输入仅包含数字和字母的用户名</p>
          </td>
        </tr>
        <tr valign="top">
          <th>提示信息</th>
          <td>
              <?php if ($zxuser->is_active()) {
                  echo "<p class=\"has_active\">你已经激活该账户，不允许再激活</p>";
              } ?>
              <?php if (!$zxuser->is_active()) {
                  echo "<p class=\"no_active\">当前账户未激活，验证此项后激活,必须为数字和字母</p>\n";
              } ?>
              <?php if (!empty($info['username']) && !$zxuser->is_active()) {
                  echo '<p class="warning">用户似乎激活过，但由于某种原因激活被取消，如果需要激活，请联系管理员</p>';
              } ?>
          </td>
        </tr>
        <tr>
          <th>确认密码</th>
          <td><input name="pwd" type="password" value=""/>
            <button type="submit" class="submit">确认激活</button>
          </td>
        </tr>
      </table>
      <input name="type" value="active" type="hidden">
    </form>
  </div>
<?php get_admin_footer(); ?>
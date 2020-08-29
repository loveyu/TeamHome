<?php

/**
 *  用户中心修改用户权限页面
 *
 * @file  user/user_manage_power.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}//非管理员禁止访
require_once('init_user.php');//用户中心初始化操作
$zxtmp->set_page_id('manage', 'power');

$err = '';
if (is_post() && iPOST('power')) {
    $err = $user->edit_power(POST('id'), POST('power'));
    if ($err == 'OK') {
        redirect(site_url('user/user_manage_power.php?status=OK&id=').POST('id'));
    }
}

get_admin_header([
    'title'  => '修改用户权限',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);

$id = GET('id') ? GET('id') : POST('id');
if (!is_numeric($id) || $id < 1) {
    $id = '';
}

if ($id != '') {
    $info = $user->get_one_info($id, "power,name");
}
//print_r($info);
if (isset($info['power'])) {
    $power = $info['power'];
} else {
    $power = POST('power');
}
?>
  <div id="user_manage">
    <h3>修改用户权限</h3>
      <?php
      if ($err) {
          echo '<p class="notice">', $err, '</p>';
      }
      if (GET('status') == 'OK') {
          echo '<p class="notice">权限已修改</p>';
      }
      ?>
    <form class="power" action="user_manage_power.php" method="post">
      <ul>
        <li><label>操作ID:<input name="id" value="<?php echo $id ?>" type="text"/><?php if (isset($info['name'])) {
                    echo $info['name'];
                } ?></label></li>
        <li><label>权限等级:<select name="power">
                    <?php foreach ($zxuser->get_power_group() as $id => $v) { ?>
                      <option
                        value="<?php echo $id ?>"<?php if ($power == $id) echo " selected" ?>><?php echo $v ?></option>
                    <?php } ?>
            </select></label></li>
        <li>
          <button class="submit" type="submit">确定操作</button>
        </li>
      </ul>
    </form>
  </div>

<?php get_admin_footer(); ?>
<?php

/**
 *  用户中心管理用户头像
 *
 * @file  user/user_manage_avata.php
 *
 */
require_once("../init.php");
send_header();
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}//非管理员禁止访
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('manage', 'avata');

$err = '';
if (is_post()) {
    load('file_action');
    $file = new File_action();
    switch (POST('type')) {
        case 'del':
            $err = $file->delete_avata(POST('id'));
            break;
        case 'edit':
            $err = $file->edit_avata(POST('id'), FILES('avata'));
            break;
        default:
            $err = '未知操作';
    }
    sGET('id', POST('id'));
}
get_admin_header([
    'title'  => '修改用户头像',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="user_manage">
    <h3>编辑用户头像</h3>
      <?php if ($err) {
          echo "<p class=\"notice\">$err</p>";
      } ?>
    <form class="edit_avata" action="user_manage_avata.php" method="post" enctype="multipart/form-data">
      <ul>
        <li><img src="<?php echo get_avata(GET('id')); ?>" height="64" width="64" alt="img"/></li>
        <li><label>操作类型:<select name="type">
              <option value="del"<?php if (POST('type') == 'del') echo ' selected="selected"' ?>>删除头像</option>
              <option value="edit"<?php if (POST('type') == 'edit') echo ' selected="selected"' ?>>编辑头像</option>
            </select></label></li>
        <li><label>用户ID:<input type="text" name="id" value="<?php echo GET('id'); ?>"></label></li>
        <li><label>选择头像<input type="file" name="avata"/><span>图片将默认压缩到64*64</span></label>
        <li>
          <button class="submit" type="submit">修改</button>
        </li>
      </ul>
    </form>
  </div>
<?php get_admin_footer(); ?>
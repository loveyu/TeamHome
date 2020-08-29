<?php

/**
 *  链接设置页面
 *
 * @file  user/link_setting.php
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
$zxtmp->set_page_id('link', 'setting');

load('link');
$link = new Link();
$cat = $link->get_category();//获取所有分类

get_admin_header([
    'title'  => '链接设置',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="link_center">
    <h3>主要链接分类设置</h3>
    <form action="link_action.php" method="post">
      <table class="creat">
        <tr>
          <th>导航栏所属分类</th>
          <td><select name="nav">
              <option value="0">无</option><?php foreach ($cat as $id => $v) { ?>
                <option
                  value="<?php echo $id ?>"<?php if ($zxsys->get_setting('nav_link') == $id) echo ' selected' ?>><?php echo $v ?></option>
                  <?php } ?></select></td>
        </tr>
        <tr>
          <th>友情链接所属分类</th>
          <td><select name="friend">
              <option value="0">无</option><?php foreach ($cat as $id => $v) { ?>
                <option
                  value="<?php echo $id ?>"<?php if ($zxsys->get_setting('friend_link') == $id) echo ' selected' ?>><?php echo $v ?></option>
                  <?php } ?></select></td>
        </tr>

        <input name="action" value="setting" type="hidden"/>
        <tr>
          <td colspan="2">
            <button class="submit" type="submit">更新</button>
          </td>
        </tr>
      </table>
    </form>
  </div>
<?php get_admin_footer(); ?>
<?php

/**
 *  新建链接
 *
 * @file  user/link_new.php
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
$zxtmp->set_page_id('link');

load('link');
$link = new Link();
$cat = $link->get_category();//获取所有分类

get_admin_header([
    'title'  => '新建链接',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="link_center">
    <h3>新建链接</h3>
      <?php if (empty($cat)) {
          echo '<p style="color:red;">请先建立分类</p>';
      } else { ?>
        <form action="link_action.php" method="post">
          <table class="creat">
            <tr>
              <th>链接名称:</th>
              <td><input name="name" value="" type="text"/></td>
            </tr>
            <tr>
              <th>URL:</th>
              <td><input name="url" value="" type="text"/></td>
            </tr>
            <tr>
              <th>分类:</th>
              <td><select name="category"><?php foreach ($cat as $id => $v) { ?>
                    <option value="<?php echo $id ?>"><?php echo $v ?></option>
                      <?php } ?></select></td>
            </tr>
            <tr>
              <th>图像:</th>
              <td><input name="image" value="" type="text"/></td>
            </tr>
            <tr>
              <th>页面标记:</th>
              <td><input name="flag" value="" type="text"/></td>
            </tr>
            <tr>
              <th>页面序号:</th>
              <td><input name="no" value="" type="text"/></td>
            </tr>
            <tr valign="top">
              <th>描述:</th>
              <td><textarea name="description"></textarea></td>
            </tr>
            <input type="hidden" name="action" value="new"/>
            <tr>
              <td colspan="2">
                <button class="submit" type="submit">创建</button>
              </td>
            </tr>
          </table>
        </form>
      <?php } ?>
  </div>
<?php get_admin_footer(); ?>
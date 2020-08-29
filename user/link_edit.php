<?php

/**
 *  编辑链接
 *
 * @file  user/link_edit.php
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
$zxtmp->set_page_id('link', 'edit');
$zxtmp->add_admin_menu(//增加一个菜单
    'link-center',
    [],
    [
        ['url' => '#', 'name' => '编辑链接', 'id' => 'link_edit', 'power' => [0]],
    ]
);
load('link');
$link = new Link();
$cat = $link->get_category();//获取所有分类
$link_info = $link->get_one_link(GET('id'));

get_admin_header([
    'title'  => '编辑链接',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="link_center">
    <h3>编辑链接</h3>
      <?php if (empty($link_info)) { ?>
        <p style="color:red;">该链接不存在</p>
      <?php } else { ?>
        <form action="link_action.php" method="post">
          <table class="creat">
            <tr>
              <th>链接名称:</th>
              <td><input name="name" value="<?php echo $link_info['name'] ?>" type="text"/></td>
            </tr>
            <tr>
              <th>URL:</th>
              <td><input name="url" value="<?php echo $link_info['url'] ?>" type="text"/></td>
            </tr>
            <tr>
              <th>分类:</th>
              <td><select name="category"><?php foreach ($cat as $id => $v) { ?>
                    <option
                      value="<?php echo $id ?>"<?php if ($link_info['category'] == $id) echo ' selected' ?>><?php echo $v ?></option>
                      <?php } ?></select></td>
            </tr>
            <tr>
              <th>图像:</th>
              <td><input name="image" value="<?php echo $link_info['image'] ?>" type="text"/></td>
            </tr>
            <tr>
              <th>页面标记:</th>
              <td><input name="flag" value="<?php echo $link_info['flag'] ?>" type="text"/></td>
            </tr>
            <tr>
              <th>页面序号:</th>
              <td><input name="no" value="<?php echo $link_info['no'] ?>" type="text"/></td>
            </tr>
            <tr valign="top">
              <th>描述:</th>
              <td><textarea name="description"><?php echo $link_info['description'] ?></textarea></td>
            </tr>
            <input type="hidden" name="action" value="edit"/>
            <input type="hidden" name="id" value="<?php echo $link_info['id'] ?>"/>
            <tr>
              <td colspan="2">
                <button type="submit" class="submit">编辑</button>
              </td>
            </tr>
          </table>
        </form>
      <?php } ?>
  </div>
<?php get_admin_footer(); ?>
<?php

/**
 *  创建一个新的分类
 *
 * @file  user/category_new.php
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
$zxtmp->set_page_id('category', 'new');
$zxtmp->add_admin_menu(//增加一个菜单
    'category-center',
    [],
    [
        ['url' => '#', 'name' => '创建新分类', 'id' => 'category_new', 'power' => [0]],
    ]
);
$cat = $zxcat;
$cat_allow_type = $cat->get_allow_type();
get_admin_header([
    'title'  => '创建新分类',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="category_center">
    <h3>创建分类</h3>
      <?php if (!in_array(GET('type'), array_flip($cat_allow_type))) { ?>
        <p class="no_found">分类类型错误</p>
      <?php } else { ?>
        <form class="new" action="category_action.php" method="post">
            <?php echo "<p class=\"title\">创建<span>", $cat_allow_type[GET('type')], "</span>分类</p>" ?>
          <table>
            <label>
              <tr>
                <th>分类名称:</th>
                <td><input name="name" value="" type="text"/></td>
              </tr>
            </label>
            <label>
              <tr>
                <th>别名:</th>
                <td><input name="alias" value="" type="text"/><span>必须为英文及数字</span></td>
              </tr>
            </label>
            <label>
              <tr valign="top">
                <th>描述:</th>
                <td><textarea name="description"></textarea></td>
              </tr>
            </label>
            <tr>
              <td colspan="2">
                <button class="submit" type="submit">创建</button>
              </td>
            </tr>
          </table>
          <input type="hidden" name="type" value="<?php echo GET('type') ?>"/>
          <input type="hidden" name="action" value="new"/>
        </form>
      <?php } ?>
  </div>
<?php get_admin_footer(); ?>
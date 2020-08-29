<?php

/**
 *  创建一个新的用户分组
 *
 * @file  user/group_new.php
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
$zxtmp->set_page_id('group', 'new');
load("group");
$group = new Group();
$group->get_all_group();
$group->parent_info;
get_admin_header([
    'title'  => '新建用户分组',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="group_center">
    <h3>新建分组</h3>
    <form action="group_action.php" method="post">
      <table class="new">
        <tr>
          <th>组名:</th>
          <td><input name="name" value="" type="text"/></td>
        </tr>
        <tr>
          <th>组长ID:</th>
          <td><input name="lader" value="" type="text"/></td>
        </tr>
        <tr>
          <th>上级分组:</th>
          <td><select name="parent">
              <option value="0">无</option>
                  <?php foreach ($group->group as $v) {
                      if ($v['parent'] == 0) { ?>
                        <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                      <?php }
                  } ?>
            </select></td>
        </tr>
        <tr>
          <th>描述:</th>
          <td><textarea name="des"></textarea></td>
        </tr>
        <input type="hidden" value="new" name="action"/>
        <tr>
          <td colspan="2">
            <button class="submit" type="submit">创建分组</button>
          </td>
        </tr>
      </table>
    </form>
  </div>
<?php get_admin_footer(); ?>
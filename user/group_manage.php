<?php

/**
 *  用户分组管理
 *
 * @file  user/group_manage.php
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
$zxtmp->set_page_id('group');
load("group");
$group = new Group();
$group->get_all_group();
$group->parent_info;
get_admin_header([
    'title'  => '分组管理',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$i = 0;
?>
  <div id="group_center">
    <h3>分组管理</h3>
    <table class="list">
      <thead>
      <tr>
        <th>ID</th>
        <th>组名</th>
        <th>描述</th>
        <th>组长</th>
        <th>上级组</th>
        <th>操作</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($group->group as $v) { ?>
        <tr class="tr_<?php echo (++$i) % 2; ?>">
          <td><?php echo $v['id'] ?></td>
          <td><?php echo $v['name'] ?></td>
          <td><?php echo $v['des'] ?></td>
          <td><?php echo $v['lader'] == 0 ? "无" : (isset($group->lader[$v['lader']]) ? ($group->lader[$v['lader']]['name']) : "未知") ?></td>
          <td><?php
              echo ($v['parent']) ? $group->group[$v['parent']]['name'] : '无' ?></td>
          <td><a href="<?php echo "group_edit.php?id=".$v['id'] ?>">编辑</a> | <a
              href="<?php echo "group_delete.php?id=".$v['id'] ?>">删除</a></td>
        </tr>
      <?php }
      if ($i == 0) {
          echo '<tr class="no_category"><td colspan="6">没有找到任何分组</td></tr>';
      } ?>
      </tbody>
    </table>
  </div>
<?php get_admin_footer(); ?>
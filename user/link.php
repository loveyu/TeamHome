<?php

/**
 *  链接中心，包含全部链接
 *
 * @file  user/link.php
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
$link_list = $link->get_all_link();

get_admin_header([
    'title'  => '链接管理',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$i = 0;
?>
  <div id="link_center">
    <table class="list">
      <thead>
      <tr>
        <th>ID</th>
        <th>名称</th>
        <th>地址</th>
        <th>描述</th>
        <th>图像</th>
        <th>标记</th>
        <th>序号</th>
        <th>类型</th>
        <th>操作</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($link_list as $v) { ?>
        <tr class="tr_<?php echo (++$i) % 2; ?>">
          <td><?php echo $v['id'] ?></td>
          <td><?php echo $v['name'] ?></td>
          <td><?php echo $v['url'] ?></td>
          <td><?php echo $v['description'] ?></td>
          <td><?php if (!empty($v['image'])) { ?><a href="<?php echo $v['image'] ?>" title="查看图片">查看图片</a><?php } ?>
          </td>
          <td><?php echo $v['flag'] ?></td>
          <td><?php echo $v['no'] ?></td>
          <td><?php echo $link->get_category($v['category']) ?></td>
          <td>
            <a href="link_edit.php?id=<?php echo $v['id'] ?>">编辑</a>&nbsp;<a
              href="link_action.php?action=delete&id=<?php echo $v['id'] ?>">删除</a>
          </td>
        </tr>
      <?php }
      if ($i == 0) {
          echo '<tr class="no_category"><td colspan="9">没有发现链接</td></tr>';
      } ?>
      </tbody>
    </table>
  </div>
<?php get_admin_footer(); ?>
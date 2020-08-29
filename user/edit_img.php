<?php

/**
 *  用户编辑个人头像
 *
 * @file  user/edit_img.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
if (!$zxuser->is_active()) {
    redirect_active();
}

require_once('init_user.php');//用户中心初始化操作
$zxtmp->set_page_id('user', 'img');

$err = '';
if (is_post()) {
    if (iPOST('id') && POST('id') == get_user_id() && iFILES('avata')) {
        load("file_action");
        $file = new File_action();
        $err = $file->edit_avata(POST('id'), FILES('avata'));
        if ($err == 'OK') {
            redirect(site_url("user/edit_img.php?up=OK"), 'refresh');
        }
    } else {
        $err = '提交的数据有误';
    }
}
get_admin_header([
    'title'  => '修改个人形象',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="user_center">
    <h3>修改头像</h3>
    <div class="edit_img">
        <?php if (!empty($err)) {
            echo "<p class=\"error\">$err</p>";
        } ?>
        <?php if (GET('up') == 'OK') echo "<p class=\"ok\">头像修改成功！如果未显示，请尝试<span class=\"reload\" onclick=\"location.reload();\">刷新</span>来清除缓存！</p>" ?>
      <form action="edit_img.php" method="post" enctype="multipart/form-data">
        <ul>
          <li><img src="<?php echo get_avata($user->user_info['id']); ?>" height="64" width="64" alt="img"/>
            <p>当前头像</p></li>
          <li><input type="file" name="avata"/>
            <p>图片将默认压缩到64*64,仅允许上传`jpg`,`gif`,`png`格式</p></li>
          <li>
            <button type="submit">上传图片</button>
          </li>
        </ul>
        <input type="hidden" name="id" value="<?php echo get_user_id(); ?>">
      </form>
    </div>
  </div>
<?php get_admin_footer(); ?>
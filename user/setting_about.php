<?php

/**
 *  设置网站关于信息
 *
 * @file  user/setting_mail.php
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
$zxtmp->set_page_id('setting', 'about');

load('kindeditor', true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文
$about_info =
    [
        'team_about'        => $zxsys->get_setting('team_about'),
        'team_proclamation' => $zxsys->get_setting('team_proclamation'),
    ];

get_admin_header([
    'title'  => '邮件设置',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="setting_center">
    <h3>首页信息设置</h3>
    <form action="setting_action.php" method="post">
      <div class="team_about">
        <p>团队简介:</p>
        <textarea name="team_about"
                  style="width:99.8%;height:200px;visibility:hidden;"><?php echo htmlspecialchars($about_info['team_about']) ?></textarea>
      </div>
      <div class="team_about">
        <p>公告栏:</p>
        <textarea name="team_proclamation"
                  style="width:99.8%;height:200px;visibility:hidden;"><?php echo htmlspecialchars($about_info['team_proclamation']) ?></textarea>
      </div>
      <input name="action" value="about" type="hidden"/>
      <button class="about_submit" type="submit">更新信息</button>
    </form>
  </div>
  <script>
    var editor
    KindEditor.ready(function (K) {
      editor = K.create('textarea', {
        resizeType: 1,
        allowPreviewEmoticons: false,
        allowImageUpload: false,
        designMode: false,
        items: [
          'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
          'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
          'insertunorderedlist', '|', 'emoticons', 'image', 'link']
      })
    })
  </script>
<?php get_admin_footer(); ?>
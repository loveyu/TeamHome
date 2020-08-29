<?php

/**
 *  个人主页图片管理
 *
 * @file  user/web_manage_img.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
if (!$zxuser->is_active()) {
    redirect_active();
}
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('web', 'img');
load('file_action');
$file = new File_action();

$list = $file->get_user_type_file_list('img');//获取图片列表

load('kindeditor', true);//加载文本编辑器函数库
add_kindeditor();//引入文本编辑器头文件

get_admin_header([
    'title'  => '个人图片管理',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
$i = 0;
?>
  <div id="web_center">
    <h3>图片管理</h3>
    <p><input type="button" id="J_selectImage" value="批量上传图片"/></p>
    <table class="file_m">
      <tr>
        <th>文件名</th>
        <th>大小</th>
        <th>修改日期</th>
        <th>操作</th>
      </tr>
        <?php foreach ($list as $v) { ?>
          <tr class="tr_<?php echo ++$i % 2 ?>">
            <td><a href="<?php echo site_url(get_config('user_path').get_user_name().'/img/'.$v['name']) ?>"
                   target="_blank"><?php echo $v['name'] ?></a></td>
            <td><?php echo $file->size($v['size']) ?></td>
            <td><?php echo date("Ymd H:i:s", $v['time']); ?></td>
            <td><a href="web_rename.php?file=img/<?php echo $v['name'] ?>">重命名</a>&nbsp;<a
                href="web_edit_action.php?type=delete&path=img/<?php echo $v['name'] ?>">删除</a></td>
          </tr>
        <?php } ?>
        <?php if ($i == 0) {
            echo "<tr class=\"no_file\"><td colspan=\"4\">没有图片被创建</td></tr>\n";
        } ?>
    </table>

    <script>
      KindEditor.ready(function (K) {
        var editor = K.editor({
          allowFileManager: true,
          uploadJson: '<?php echo site_url('/kindeditor/php/web_img_upload.php')?>',
          urlType: 'domain',
        })
        K('#J_selectImage').click(function () {
          editor.loadPlugin('multiimage', function () {
            editor.plugin.multiImageDialog({
              clickFn: function (urlList) {
                location.reload()
              }
            })
          })
        })
      })
    </script>
  </div>
<?php get_admin_footer(); ?>
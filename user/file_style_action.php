<?php

/**
 *    样式文件管理操作请求页面
 *
 * @file    user/file_style_action.php
 *
 */
require_once('../init.php');
send_header();
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}//禁止非正常请求
load('file_action');
$file = new File_action();
switch (is_post() ? POST('type') : GET('type')) {
    case 'edit_file':
        $s = $file->edit_style_file(POST('file'), POST('theme'), TT(POST('content')));
        break;
    default:
        die("未知操作");
}
if ($s == 'OK') {
    $s = '操作成功';
}
get_close_page($s, false);
die($s);
?>
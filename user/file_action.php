<?php

/**
 *    文件管理操作请求页面
 *
 * @file    user/file_action.php
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
        $s = $file->edit_attach_file(POST('file'), TT(POST('content')));
        break;
    case 'rename':
        $s = $file->rename_attach_file(POST('path'), POST('new'));
        break;
    case 'delete':
        $s = $file->delete_attach_content(GET('path'));
        break;
    case 'new_dir':
        $s = $file->new_attach_dir(POST('path'), POST('name'));
        break;
    case 'creat':
        $s = $file->creat_attach_file(POST('name'), POST('ext'), POST('path'));
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
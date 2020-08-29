<?php

/**
 *    链接操作请求页面
 *
 * @file    user/link_action.php
 *
 */
require_once('../init.php');
send_header();
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}
load('link');
$link = new Link();
switch (is_post() ? POST('action') : GET('action')) {
    case 'new':
        $s =
            $link->new_link(POST('name'), POST('url'), POST('category'), POST('image'), POST('flag'), POST('no'),
                POST('description'));
        break;
    case 'delete':
        $s = $link->delete_link(GET('id'));
        break;
    case 'edit':
        $s =
            $link->edit_link(POST('id'), POST('name'), POST('url'), POST('category'), POST('image'), POST('flag'),
                POST('no'), POST('description'));
        break;
    case 'setting':
        $s = $link->up_setting(POST('nav'), POST('friend'));
        break;
    default:
        $s = '未知操作';
}
get_close_page($s, false);
?>
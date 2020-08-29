<?php

/**
 *    新闻中心请求操作页面
 *
 * @file    user/news_post_edit.php
 *
 */
require_once('../init.php');
if (!$zxuser->auto_login() || get_power() > 1) {
    forbid_page();
}//非管理组禁止
load('news');
$news = new News();
switch (is_post() ? POST('action') : GET('action')) {
    case 'post':
        $s =
            $news->new_post(POST('title'), POST('author'), POST('content'), POST('category'), POST('comment'),
                POST('type'), POST('time'));
        break;
    case 'edit':
        $s =
            $news->edit_post(POST('id'), POST('title'), POST('author'), POST('content'), POST('comment'),
                POST('category'), POST('type'), POST('time'));
        break;
    case 'delete':
        $s = $news->delete(GET('id'));
        break;
    default:
        $s = '未知操作';
}
get_close_page($s, false);
?>
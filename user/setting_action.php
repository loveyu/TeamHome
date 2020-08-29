<?php

/**
 *    设置中心修改请求操作
 *
 * @file    user/setting_action.php
 *
 */
require_once('../init.php');
send_header();
if (!$zxuser->auto_login() || !is_admin()) {
    forbid_page();
}//判断必须为超级管理员

switch (is_post() ? POST('action') : GET('action')) {
    case 'setting':
        if (!$zxsys->is_super_admin()) {
            $s = '需要超级管理员权限';
        } else {
            $s =
                $zxsys->up_more_setting(POST('site_name'), POST('site_description'), POST('site_url'),
                    POST('site_register'), POST('site_style'), POST('admin_email'), POST('user_manage_one_page_number'),
                    POST('news_one_page_number'), POST('site_cookie_time'), POST('site_key'));
        }
        break;
    case 'mail':
        if (!$zxsys->is_super_admin()) {
            $s = '需要超级管理员权限';
        } else {
            $s =
                $zxsys->up_mail_setting(POST('useragent'), POST('type'), POST('smtp_host'), POST('smtp_port'),
                    POST('smtp_user'), POST('smtp_pass'), POST('smtp_auth'), POST('smtp_debug'), POST('from_email'),
                    POST('from_name'));
        }
        break;
    case 'super':
        $s = $zxsys->super_admin_verify(POST('pwd'));
        if ($s == 'OK') {
            $redirect = POST('redirect');
            if (!is_url($redirect)) {
                $redirect = site_url('user/setting.php');
            }
            redirect($redirect);
        }
        break;
    case 'about':
        $s = $zxsys->edit_about_info(POST('team_about'), POST('team_proclamation'));
        break;
    default:
        $s = '未知操作';
}
get_close_page($s, false);
?>
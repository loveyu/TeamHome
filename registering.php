<?php
/**
 *    注册提交页
 *
 * @file    registering.php
 *
 */

require_once("init.php");
get_function();
load('session');
load('register');
send_header();
$session = new Session();
if (!is_mail(POST('email'))) {
    get_close_page("邮箱有误", false);
}

if (!$session->email_check("email_register", POST('vcode'), POST('email'))) {
    get_close_page("验证码有误", false);
}

$session->delete("email_register");

$r = new Register();
$s = $r->register(POST('email'));
if ($s == 'OK') {
    redirect(site_url("login.php?notice=".urlencode("你已注册成功,密码已发送到你的邮箱")."&redirect=".urlencode(site_url("user/"))),
        "refresh");
} else {
    redirect(site_url("register.php?err=".urlencode($s)), "refresh");
}
?>
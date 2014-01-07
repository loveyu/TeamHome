<?php
	/**
	 *    部分明文邮件发送请求
	 * @file    tool/send_mail.php
	 */
	require_once('../init.php');
	header("Cache-Control: no-cache");
	if(!iGET('type') || !is_mail(GET('email')))
		die("error");

	sGET("email", strtolower(GET('email'))); //转换大小写

	load('mail');
	load('session');
	$mail = new Mail();
	$session = new Session();
	switch(GET('type')){
		case "register":
		{
			//注册验证
			if($mail->is_user_mail(GET('email')))
				die('error');
			$key = rand_new_key(10);
			$session->set("email_register", $key . GET('email'));
			$mail->add(GET('email'));
			$mail->title("注册验证码 - " . site_title());
			$mail->html("你的注册验证码为:&nbsp;&nbsp;" . $key);
		}
			break;
		case "vcode":
		{
			//账户真实性验证
			if(!$mail->is_user_mail(GET('email')))
				die('error');
			$key = rand_new_key(10);
			$session->set('v_email', $key . GET('email'));
			$mail->add(GET('email'));
			$mail->title("账户验证码 - " . site_title());
			$mail->html("你的验证码为:&nbsp;&nbsp;" . $key);
		}
			break;
		case "vncode":
		{
			//验证新邮箱
			if($mail->is_user_mail(GET('email')))
				die('error');
			$key = rand_new_key(10);
			$session->set("n_email", $key . GET('email'));
			$mail->add(GET('email'));
			$mail->title("新邮箱账户验证码 - " . site_title());
			$mail->html("你的验证码为:&nbsp;&nbsp;" . $key);
		}
			break;
		default:
			die("false");
	}
	if($mail->send())
		die("true");
	else die("false");
?>
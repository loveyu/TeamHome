<?php
	/**
	 *    登陆页
	 * @file    login.php
	 */

	require_once("init.php");
	get_function();
	load("news");
	$news = new News();
	if(is_post()){
		if(iPOST('redirect_to'))
			$zxuser->set_from(POST('redirect_to'));
		if($zxuser->logining(POST('username'), POST('password'), (POST('keep') == 1) ? true : false, false)){
			$zxuser->jump();
		}
	}
	else{
		$zxuser->auto_login();
	}
	get_header(array('title' => '登录', 'link' => array(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'style.css')), 'script' => array(array('type' => "text/javascript", "src" => site_url() . "js/login_verify.js"))));
?>
<div class="content_resize">
	<div class="mainbar">

		<div id="login">
			<h2>用户登录</h2>

			<form id="loginForm" action="<?php echo site_url("login.php");?>" method="post">
				<div class="div_notice">
					<?php echo $zxuser->show_errors("\t\t\t<p class=\"post_error\">", "</p>");?>
					<?php if(is_login())
					echo "\t\t\t<p class=\"notice\">已登录，重新登录将导致当前登录失效</p>\n";?>
					<?php if(iGET('notice'))
					echo "\t\t\t<p class=\"notice\">", GET('notice'), "</p>\n";?>
				</div>
				<div>
					<label for="username">邮&nbsp;&nbsp;箱</label><br/>
					<input id="username" name="username" value="<?php echo POST('username')?>" type="text" class="input"/>
					<span id="usernameInfo">你的邮箱账号</span>
				</div>
				<div>
					<label for="password">密&nbsp;&nbsp;码</label><br/>
					<input id="password" name="password" type="password" class="input"/>
					<span id="passwordInfo">你正确的密码</span>
				</div>
				<div>
					<label for="keep">保持登录</label>
					<input id="keep" name="keep" type="checkbox" value="1"/>
					<span id="keepInfo">记住你的账户一段时间</span>
				</div>
				<div>
					<input id="send" type="submit" class="input" value="登录"/>
				</div>
				<input type="hidden" name="redirect_to" value="<?php echo (GET('redirect') != '') ? urldecode(GET('redirect')) : $_SERVER['HTTP_REFERER'];?>"/>
			</form>
		</div>
	</div>
	<?php PHome_load('sidebar', array("news" => $news))?>
	<div class="clr"></div>
</div>
<?php get_footer(); ?>
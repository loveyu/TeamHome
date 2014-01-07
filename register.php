<?php
	/**
	 *    注册页面
	 * @file    register.php
	 */

	require_once("init.php");

	get_function();
	load('news');
	$news = new News();
	$zxuser->auto_login();
	add_jquery("jquery-1.8.2.min.js", 'first');
	get_header(array('title' => '用户注册', 'link' => array(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'style.css')), 'script' => array(array('type' => "text/javascript", "src" => site_url() . "js/register_verify.js"))));
?>
<div class="content_resize">
	<div class="mainbar">

		<div id="register">
			<h2>用户注册</h2>
			<?php if(!allow_register()){
			echo "<h3 class=\"close\">注册被关闭</h3>";
		}
		elseif(is_login())
			echo "<h3 class=\"hasReg\">你已注册</h3>";
		else{
			?>
			<?php if(GET('notice') != '')
				echo "<p class=\"notice\">" . GET('notice') . "</p>"; ?>
			<?php if(GET('err') != '')
				echo "<p class=\"err\">" . GET('err') . "</p>"; ?>
			<form name="register_table" id="RegisterForm" action="<?php echo site_url("registering.php");?>" method="post">
				<div>
					<label for="email">邮&nbsp;&nbsp;箱</label><br/>
					<input id="email" name="email" type="text" class="input"/>
					<span id="emailInfo">你的邮箱账号</span>
				</div>
				<div>
					<label for="vcode">邮箱验证码</label><br/>
					<input id="vcode" name="vcode" type="text" class="input"/>
					<span><button type="button" id="vcodeInfo">获取邮箱验证码</button></span>
				</div>
				<div>
					<input value="注册" type="submit" id="send"/>
				</div>
			</form>
			<?php }?>
		</div>
	</div>
	<?php PHome_load('sidebar', array("news" => $news))?>
	<div class="clr"></div>
</div>
<?php get_footer(); ?>
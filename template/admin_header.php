<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');
run_plugin('admin_header');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php zx_title();?></title>
<?php add_jquery("jquery-1.8.2.min.js",'first');?>
<?php zx_header();?>
</head>

<body id="zhixing">
<div id="container">

<div id="left_nv1"><!-- 导航栏 -->
<div id="left_nv2">
<ul>
<?php out_admin_menu();?>
</ul>
</div>
</div><!-- 导航链接 -->


<div id="zxcontent"><!-- 右边栏 -->
<div id="header"><!-- 顶部 -->
	<div class="logo">
		<h1><a href="<?php echo site_url()?>"><span><?php echo site_title()?></span></a></h1>
	</div>

	<div class="login">
		<p><?php if(is_login())echo "<a href=\"",site_url("user"),"\" title=\"用户中心\">",get_user_name(),"</a>";else echo "<a href=\"",site_url("register.php"),"\">注册</a>";?> || <?php out_login_link()?></p>
	</div>
	<div class="clear"></div>
</div><!-- 顶部链接 -->

<div id="admin_body">

<?php run_plugin('admin_body_header');?>

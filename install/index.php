<?php
if(isset($_GET['act']) && $_GET['act']=='finish'){
	if(!file_exists("lock"))file_put_contents("lock","");
}
if(file_exists("lock"))die("function locked");
require('../init.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>系统安装程序</title>
</head>
<body>
<a href="move_link.php">移动链接地址</a>
<a href="make_password.php">生成密码</a>
<a href="index.php?act=finish">锁定安装程序</a>
</body>
</html>
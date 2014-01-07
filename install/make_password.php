<?php
	if(file_exists("lock"))
		die("function locked");
	require('../init.php');
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>系统链接信息替换</title>
	<style type="text/css">
		label {
			display: block;
		}
	</style>
</head>
<body>
<div>
	<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($zxdb->update("user",array("password"=>make_password(md5($_POST['pwd']))),"id = ".$_POST['id']))
			echo "OK<br />";
		else
			echo "ERROR<br />";
	}
	?>
	<form action="" method="POST">
		ID:<input name="id" type="text" /><br />
		PWD:<input name="pwd" type="password" /><br />
		<input type="submit" value="Submit" />
	</form>
</div>
</body>
</html>
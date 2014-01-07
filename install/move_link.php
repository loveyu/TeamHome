<?php
if(file_exists("lock"))die("function locked");
require('../init.php');
$zxuser->auto_login();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>系统链接信息替换</title>
<style type="text/css">
label{
	display:block;
}
</style>
</head>
<body>
<div>
<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
	load('link');
	$link = new Link();
	$list = $link->get_all_link();
	$change = array();
	if(POST('url')!='' && POST('url_r')!='' && POST('url')!=POST('url_r')){
		foreach($list as $id=>$v){
			if(''==$v['url'] || strpos($v['url'],POST('url'))===false)continue;
			$list[$id]['url'] = str_replace(POST('url'),POST('url_r'),$v['url']);
			if(!in_array($id,$change))array_push($change,$id);
		}
	}
	if(POST('img')!='' && POST('img_r')!='' && POST('img')!=POST('img_r')){
		foreach($list as $id=>$v){
			if(''==$v['image'] || strpos($v['image'],POST('img'))===false)continue;
			$list[$id]['image'] = str_replace(POST('img'),POST('img_r'),$v['image']);
			if(!in_array($id,$change))array_push($change,$id);
		}
	}
	if(count($change)>0)echo "<ul>\n";
	foreach($change as $id){
		echo "<li>",$id,"=>",$link->edit_link($list[$id]['id'],$list[$id]['name'],$list[$id]['url'],$list[$id]['category'],$list[$id]['image'],$list[$id]['flag'],$list[$id]['no'],$list[$id]['description']),"</li>\n";
	}
	if(count($change)>0)echo "</ul>\n";
	else echo "没有数据被替换";
}
?>
<form action="" method="post">
<label>网址替换:<input name="url" type="text" value="" /></label>
<label>替换数据:<input name="url_r" type="text" value="" /></label>
<label>图片替换:<input name="img" type="text" value="" /></label>
<label>替换数据:<input name="img_r" type="text" value="" /></label>
<button type="submit">替换</button>
</form>

</div>
<a href="index.php">返回首页</a>
<a href="index.php?act=finish">锁定安装程序</a>
</body>
</html>
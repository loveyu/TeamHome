<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>操作提示</title>
</head>
<body>
<script type="text/javascript">
<?php if(isset($is_new_windows) && $is_new_windows){?>
alert("<?php if(isset($s))echo $s?>");
window.opener=null;
window.open("","_self");
window.close();
<?php }else{?>
alert("<?php if(isset($s))echo $s,"\\n\\n请后退刷新"?>");
history.go(-1);
<?php }?>
</script>
</body>
</html>
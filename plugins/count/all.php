<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');
$list = PCount_get_serach_select();
?>
<style type="text/css">
	#PCOUNT_STYLE{padding:5px;}
	#PCOUNT_STYLE table{width:100%;}
	#PCOUNT_STYLE table td{border:#999 solid 1px;COLOR:#60F;text-align:left;padding:4px;}
	#PCOUNT_STYLE table td:hover{background-color:#C6F;color:#000;}
	#PCOUNT_STYLE table th{border:#999 solid 1px;background-color:#E1FBF3;COLOR:#903;padding:5px;text-align:left;}
	#PCOUNT_STYLE table .tr_1{background-color:#DCECBF;}
	#PCOUNT_STYLE table .tr_0{background-color:#EFEDBC;}
	#PCOUNT_STYLE p.notice{padding:5px;background-color:#CFECF1;color:#F00;text-align:left;margin:5px 0;}
</style>
<div id="PCOUNT_STYLE">
<p class="notice">&nbsp;&nbsp;点击数据复制&nbsp;>></p>
<table>
<tr><th>ID</th><th>USER</th><th>PAGE</th><th>IP</th><th>TIME</th><th>UA</th><th>FROM</th><th>TYPE</th></tr>
<?php
$i=1;
$site_l = strlen(site_url())-1;
foreach($list as $v){?>
	<tr class="tr_<?php echo $i++%2;?>"><td><?php echo $v['id']?></td><td><?php echo $v['user']?></td><td title="<?php echo $v['url']?>"><?php echo substr($v['url'],$site_l,$site_l+20)?></td><td><?php echo $v['ip']?></td><td><?php echo $v['time']?></td><td title="<?php echo $v['ua']?>"><?php echo substr($v['ua'],0,20)?></td><td title="<?php echo $v['from']?>"><?php echo substr($v['from'],$site_l,$site_l+20)?></td><td><?php echo $v['type']?></td></tr>
<?php }?>
</table>
<script type="text/javascript">
<!--
	$("#PCOUNT_STYLE table td").click(function(){
		if($(this).attr("title"))prompt("请手动复制数据：\n"+$(this).attr("title"),$(this).attr("title"));
		else prompt("请手动复制数据：\n"+$(this).text(),$(this).text());
	});
//-->
</script>
</div>
<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');
	global $zxsys;
	$flage = false;
	if(is_post()){
		$config = POST('list');
		foreach($config as $id => $v){
			if(empty($v['query']) || empty($v['file'])){
				unset($config[$id]);
				continue;
			}
			$tmp = array();
			if(isset($v['param'])){
				foreach($v['param'] as $v2){
					if(empty($v2['name']) || is_numeric(substr($v2['name'], 0, 1)))
						continue;
					if(isset($v2['is_array']) && "check" == $v2['is_array'])
						$tmp[$v2['name']] = explode(",", $v2['value']);
					else $tmp[$v2['name']] = $v2['value'];
				}
			}
			$config[$id]['param'] = $tmp;
		}
		$config = json_encode($config);
		$flage = $zxsys->up_setting("PHome", $config);
	}
	else{
		$config = $zxsys->get_setting('PHome');
	}
	$config = json_decode($config);
?>
<style type="text/css">
	#PHome_setting {
		text-align: left;
	}

	#PHome_setting label {
		display: block;
	}

	#PHome_setting .list {
		margin: 10px;
		width: 300px;
		padding-left: 5px;
		float: left;
		border: #999 solid 1px;
	}

	#PHome_setting h2 {
		font-size: 17px;
		color: #939;
		border: #999 solid 1px;
		margin: 10px 0;
		padding: 10px;
	}

	#PHome_setting p.update {
		font-size: 15px;
		color: #C00;
		border: #999 dotted 3px;
		margin: 5px 0;
		padding: 5px;
	}

	#PHome_setting .param {
		margin: 5px;
		padding-left: 5px;
		border-left: #333 dotted 1px;
	}

	#PHome_setting .param2 {
		border-bottom: #333 solid 1px;
		margin: 5px 0px;
		padding-bottom: 5px;
	}

	#PHome_setting .submit {
		margin: 20px;
		background-color: #B7DF8A;
		font-size: 15px;
		padding: 10px 30px;
		border: #666 solid 1px;
	}

	#PHome_setting .add_rule {
		width: 300px;
		float: left;
		margin: 10px;
		text-align: center;
		line-height: 100px;
		color: #939;
		border: #999 solid 1px;
	}

	#PHome_setting .add_rule p {
		text-align: left;
		color: #F30;
		line-height: 25px;
		margin: 10px;
	}
</style>
<script type="text/javascript">
	<!--
	function PHome_add_rule() {
		length = $("input[name$='query]']").length;
		$("#PHome_setting .new_list").append('<div class="list"><label>地址参数：<input name="list[' + length + '][query]" type="text" value="" /></label><label>文件参数：<input name="list[' + length + '][file]" type="text" value="" /></label><label>页面参数列表:</label><div class="param"><button onclick="PHome_add_param(this,' + length + ');" type="button">增加参数列表</button></div>');
	}
	function PHome_add_param(elem, i) {
		parent = $(elem).parent();
		list = parent.children(".param2");
		parent.html('<div class="param2"><label>变量名:<input name="list[' + i + '][param][' + list.length + '][name]" type="text" value="" /></label><label>变量值:<input name="list[' + i + '][param][' + list.length + '][value]" type="text" value="" /></label><label>是否为数组:<input name="list[' + i + '][param][' + list.length + '][is_array]" type="checkbox" value="check" /></label></div>' + parent.html());
	}
	//-->
</script>
<div id="PHome_setting">
	<h2>修改站点伪静态设置</h2>
	<?php
	if($flage)
		echo "<p class=\"update\">数据已更新</p>";
	?>
	<form action="" method="post">
		<?php $i = 0;foreach($config as $v){ ?>
		<div class="list">
			<label>地址参数：<input name="list[<?=$i?>][query]" type="text" value="<?=$v->query?>"/></label>
			<label>文件参数：<input name="list[<?=$i?>][file]" type="text" value="<?=$v->file?>"/></label>
			<label>页面参数列表:</label>

			<div class="param">
				<?php $j = 0;foreach($v->param as $name => $value){ ?>
				<div class="param2">
					<label>变量名:<input name="list[<?=$i?>][param][<?=$j?>][name]" type="text" value="<?=$name?>"/></label>
					<label>变量值:<input name="list[<?=$i?>][param][<?=$j?>][value]" type="text" value="<?=(is_array($value)) ? implode(",", $value) : $value?>"/></label>
					<label>是否为数组:<input name="list[<?=$i?>][param][<?=$j?>][is_array]" type="checkbox" value="check" <?=(is_array($value) ? 'checked="checked" ' : "")?>/></label>
				</div>
				<?php $j++;
			}?>
				<button onclick="PHome_add_param(this,<?=$i?>);" type="button">增加参数列表</button>
			</div>
		</div>
		<?php $i++;
	}?>
		<div class="new_list"></div>
		<button class="add_rule" type="button" onclick="PHome_add_rule();">点击增加数据规则</button>
		<div class="add_rule"><p>提示：如果数据为数组请勾选，存入的数组以英文逗号隔开不要保留空格什么的。如果规则和文件为空，该条记录会忽略。</p></div>
		<div class="clear"></div>
		<button type="submit" class="submit">更新数据</button>
	</form>
</div>
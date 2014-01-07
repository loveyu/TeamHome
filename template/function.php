<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');

/**
 *	网站首页需呀加载的函数
 *	
 *
 *	@file		template/function.php
 *	@author		胡志宇
 *	@version	1.0
 *
 */
//-----------------------------------------------

global $zxplugin;
$zxplugin->load_plugin('page');//加载页面插件
run_plugin('page_init');//运行页面插件
global $zxuser;
$zxuser->auto_login();//进行登录判断

function Theme_get_news(&$news,$num=5){
	return $news->get_category_post('','publish',1,$num,'id,title,time,author,content,category','',true);
}
function Theme_get_news_list_no_content(&$news,$num = 5){
	return $news->get_category_post('','publish',1,$num,'id,title,time,author,category','',true);
}

function Theme_get_news_list($id,&$news){
	if(!is_array($id) || empty($id))return array();
	$rt = array();
	$cat = $news->get_category_list();
	foreach($id as $v){
		if(!isset($cat[$v]))continue;
		$rt[$v] = array();
		$rt[$v]['id'] = $v;
		$rt[$v]['name'] = $cat[$v];
		$rt[$v]['data'] = $news->get_category_post($v,'publish',1,5,'id,title,time,author','',true);
	}
	return $rt;
}
function Theme_get_text_content($content,$length=200){
	if($length==0)$content = strip_tags($content);
	else $content = cut_str(strip_tags($content),$length);
	$rt = array();
	foreach(explode("\n",$content) as $v){
		if(strlen($v)>1){
			$rt[] = "<p>$v</p>";
		}
	}
	return implode("\n",$rt)."\n";
}
function Theme_get_content_first_img($content,$default=''){
	preg_match_all('/<img (.*)src="(.*)" (.*)\/>/U',$content,$match,PREG_PATTERN_ORDER);
	if(isset($match[2][0])){
		return $match[2][0];
	}else{
		return $default;
	}
}
function Theme_get_cat_names($list,$v){
	$arr = array();
	foreach(array_keys($list) as $h){
		$arr[] = $list[$h][$v];
	}
	global $zxcat;
	return $zxcat->get_category_list($arr);
}

function Theme_format_time($time,$str = "Y年m月d日"){
	if(is_numeric($time))
		return date($str,$time);
	else return date($str,strtotime($time));
}

?>
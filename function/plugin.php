<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 插件操作及加载函数
 * 运行初期加载
 * @file function/plugin.php
 * @author  胡志宇
 * @version 1.0
 */
//-----------------------------------------------

/**
 * 将插件函数注册到相应的钩子中
 * @param  callback  $fuc  回调函数
 * @param  string  $type  钩子名称
 */
function add_plugin($fuc, $type)
{
    global $zxplugin;
    $zxplugin->add_call($fuc, $type);
}

/**
 * 运行插件
 * @param  string  $type  运行对应的插件，即钩子
 */
function run_plugin($type)
{
    global $zxplugin;
    $zxplugin->call($type);
}

/**
 * 加载插件页面
 * @param  string  $name  插件名
 * @param  string  $page  页面文件名
 */
function load_plugin_page($name, $page)
{
    include(PLUGIN_PATH."$name/$page.php");
}

?>
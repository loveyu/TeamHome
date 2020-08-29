<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * Kind文本编辑器加载模块
 * 被调用时进行加载
 *
 * @file function/kindeditor.php
 * @author  胡志宇
 * @version 1.0
 */

//------------------------------------------------------------------

/**
 * 在页面中添加kind文本编辑器
 *
 * @param  null
 * @return null
 */
function add_kindeditor()
{
    add_kindeditor_header();
    add_kindeditor_footer();
}

/**
 * Kind文本编辑器的头文件
 *
 * @param  null
 * @return null
 */
function add_kindeditor_header()
{
    global $zxtmp;
    $zxtmp->add_header_other('	<link rel="stylesheet" type="text/css" href="'.site_url("kindeditor/themes/default/default.css")."\"/>\n");
    $zxtmp->add_header_other('	<script type="text/javascript" src="'.site_url("kindeditor/kindeditor-min.js")."\"></script>\n");
    $zxtmp->add_header_other('	<script type="text/javascript" src="'.site_url("kindeditor/lang/zh_CN.js")."\"></script>\n");
}

/**
 * Kind文本编辑器的底部文件
 *
 * @param  null
 * @return null
 */
function add_kindeditor_footer()
{
    //global $zxtmp;
    //$zxtmp->add_footer("");
}

?>
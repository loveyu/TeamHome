<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}
run_plugin('page_header'); //运行页面插件
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php
      zx_title();
      ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php add_jquery("jquery-1.8.2.min.js", 'first'); ?>
    <?php zx_header(); ?>
  <link href="<?php echo get_style_path(); ?>style.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="<?php echo site_url(); ?>js/home/cufon-yui.js"></script>
  <script type="text/javascript" src="<?php echo site_url(); ?>js/home/cuf_run.js"></script>
  <script type="text/javascript" src="<?php echo site_url(); ?>js/home/arial.js"></script>
</head>
<body>
<div class="main">

  <div class="header">
    <div class="header_resize">
      <div class="menu_nav">
        <ul>
            <?php out_nav_menu("", "active", "\t\t\t\t", '', '', ''); ?>
        </ul>
        <div class="clr"></div>
      </div>
      <div class="searchform">
        <form id="formsearch" name="formsearch" method="post" action="">
          <input name="button_search" src="<?php echo get_style_path(); ?>images/search_btn.gif" class="button_search"
                 type="image"/>
          <span><input name="editbox_search" class="editbox_search" id="editbox_search" maxlength="80" value="Search"
                       type="text"/></span>
        </form>
      </div>
      <div class="clr"></div>
    </div>
  </div>

  <div class="content">



<?php

/**
 *    插件API页面
 *
 * @file    user/plugin_api.php
 *
 */
require_once("../init.php");
$plugin_status = false;
$plugin_status = $zxplugin->check_plugin_page(GET('id'), GET('p'));
if ($plugin_status) {
    load_plugin_page(GET('id'), GET('p'));
}
?>
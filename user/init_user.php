<?php

/**
 *    初始化用户详细信息
 *
 * @file    user/init_user.php
 *
 */
load('user_action');//加载用户操作类
$user = new User_action();//实例化用户操作类
$zxtmp->user = &$user;//将用户数据赋给模板操作
require_once(TEMPLATE_PATH."admin_function.php");    //初始化后台模板函数
$zxplugin->load_plugin('admin');                    //加载后台加载类型的插件
run_plugin('admin_init');                            //加载初始化插件函数
?>
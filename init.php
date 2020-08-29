<?php

/**
 *    初始化操作
 *
 * @file    init.php
 *
 */
require_once("config.php");                    //加载配置文件
require_once(FUNCTION_PATH."system.php");    //加载系统函数
require_once(FUNCTION_PATH."template.php");    //加载模板函数
require_once(FUNCTION_PATH."request.php");    //加载请求过滤函数
require_once(FUNCTION_PATH."plugin.php");    //插件函数
require_once(CLASS_PATH."mysql.php");        //加载数据库操作类
require_once(CLASS_PATH."system.php");        //加载系统类
require_once(CLASS_PATH."template.php");    //加载模板类
require_once(CLASS_PATH."user.php");        //加载用户类
require_once(CLASS_PATH."plugin.php");        //加载用户
require_once(CLASS_PATH."category.php");    //加载用户
require_once(CLASS_PATH."cache.php");        //加载缓存机制
$zxcache = new Cache();
filter_form_parameter();                    //过滤表单参数
$zxdb = new Mysql();                    //实例化数据库操作
$zxsys = new System();                    //实例化系统操作类
domain_redirect();                            //域名跳转
$zxcat = new Category();                    //实例化分类，无数据库操作
$zxplugin = new Plugin();                    //实例化插件类
$zxtmp = new Template();                    //实例化模板操作类
$zxuser = new User();                        //实例化用户类
$zxplugin->load_plugin('init');                //加载初始化加载类型的插件
run_plugin('init');                            //加载初始化插件函数
?>
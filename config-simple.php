<?php
	/**
	 *    配置文件
	 * @file config.php
	 */

	//所有路径请都以斜杠结束
	$RUN_TIME_BEGIN = microtime(); //设置页面计时期起点

	define('ROOT_PATH', dirname(__FILE__) . "/"); //网站根目录绝对路径
	define('CLASS_PATH', ROOT_PATH . 'class/'); //类文件路径
	define('TEMPLATE_PATH', ROOT_PATH . 'template/'); //网站模板路径
	define('FUNCTION_PATH', ROOT_PATH . "function/"); //网站函数模块路径
	define('PLUGIN_PATH', ROOT_PATH . "plugins/"); //网站插件目录
	define('CACHE_PATH', ROOT_PATH . "cache/"); //网站缓存目录

	$config['char_set'] = 'utf-8'; //网页编码
	$config['cookie_prefix'] = "zx_"; //COOKIE前缀
	$config['cookie_domain'] = "127.0.0.1"; //COOKIE域名
	$config['debug'] = true; //开启调试模式
	$config['user_path'] = 'show/'; //用户文件夹
	$config['close_page'] = array( //要关闭的后台页面
		'web' //个人web主页
	);
	$config['attach_path'] = 'attached/'; //附件目录
	$config['cache_status'] = false; //是否开启缓存，开启后仅在指定页面有效，未开启所有页面无效，前提是缓存不存在
	$config['cache_time'] = 86400; //缓存有效期，秒
	$config['cache_comp'] = false; //缓存压缩

	$config['db']['hostname'] = '127.0.0.1'; //数据库路径
	$config['db']['username'] = 'root'; //数据库用户名
	$config['db']['password'] = '123456'; //数据库用户密码
	$config['db']['database'] = 'xxxb'; //数据库名
	$config['db']['dbprefix'] = 'zx_'; //数据库表前缀
	$config['db']['db_debug'] = true; //开启数据库调试
	$config['db']['char_set'] = 'utf8'; //数据库编码

	date_default_timezone_set('PRC'); //设置时区，PRC为北京时间

?>
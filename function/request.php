<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');
	/**
	 * 服务器请求操作及过滤
	 * 运行初时加载
	 * @file function/request.php
	 * @author  胡志宇
	 * @version 1.0
	 */
	//------------------------------------------------

	/**
	 * 判断是否开启转义，设置部分需要系统变量
	 * @param null
	 * @return null
	 */
	function filter_form_parameter(){
		if(!isset($_SERVER['HTTP_REFERER']))
			$_SERVER['HTTP_REFERER'] = '';
		if(!isset($_SERVER['HTTP_USER_AGENT']))
			$_SERVER['HTTP_USER_AGENT'] = '';

	}

	/**
	 * 跳转页面到当前指定域名页,初始化之后调用
	 * @param null
	 * @return null
	 */
	function domain_redirect(){
		$s = parse_url(site_url());
		if(isset($s['port']))
			$s['host'] = $s['host'] . ":" . $s['port'];
		if($_SERVER['HTTP_HOST'] != $s['host']){
			Cache::close();
			redirect(str_replace($_SERVER['HTTP_HOST'], $s['host'], now_url()));
		}
	}

	/**
	 * 根据系统转义文字数据
	 * @param string $s
	 * @return string
	 */
	function ST($s){
		if(get_magic_quotes_gpc()){
			return $s;
		}
		else{
			return addslashes($s);
		}
	}

	/**
	 * 根据系统数据库转义数据
	 * @param string $s
	 * @return string
	 */
	function SS($s){
		if(get_magic_quotes_gpc()){
			return $s;
		}
		else{
			return mysqli_real_escape_string($GLOBALS['mysqli_link'],$s);
		}
	}

	/**
	 * 还原PHP开启自动转义后的文本数据
	 */
	function TT($s){
		if(get_magic_quotes_gpc()){
			return stripslashes($s);
		}
		else{
			return $s;
		}
	}

	/**
	 * 设Cookies
	 *
	 * @param string  $name  名称
	 * @param string  $value 值
	 * @param integer $time  有效期
	 * @return null
	 */
	function set_cookies($name, $value, $time = ''){
		if($time == '' || $time < 0)
			$time = 0;
		else $time += time();
		setcookie(get_config('cookie_prefix') . $name, $value, $time, '/', get_config('cookie_domain'), '');
	}

	/**
	 * 获取Cookie
	 *
	 * @param string $name 名称
	 * @return string 不存在时返回空
	 */
	function get_cookies($name){
		if(isset($_COOKIE[get_config('cookie_prefix') . $name]))
			return $_COOKIE[get_config('cookie_prefix') . $name];
		else return '';
	}

	/**
	 * 删除一个Cookie
	 *
	 * @param string $name Cookie名称
	 * @return null
	 */
	function delete_cookie($name){
		setcookie(get_config('cookie_prefix') . $name, "", 0, "/", get_config('cookie_domain'), '');
	}

	/**
	 * 获取GET请求的值
	 *
	 * @param string $s 为空时返回全部，不存在返回空
	 * @return string
	 */
	function GET($s = ''){
		if($s == '')
			return $_GET;
		if(isset($_GET[$s]))
			return $_GET[$s];
		else return '';
	}

	/**
	 * 判断是否为POST请求
	 *
	 * @param null
	 * @return bool
	 */
	function is_post(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	/**
	 * 获取POST请求的值
	 *
	 * @param string $s 为空时返回全部，不存在返回空
	 * @return string
	 */
	function POST($s = ''){
		if($s == '')
			return $_POST;
		if(isset($_POST[$s]))
			return $_POST[$s];
		else return '';
	}

	/**
	 * 判断是否设置某个POST请求
	 *
	 * @param string $s
	 * @return bool
	 */
	function iPOST($s){
		return isset($_POST[$s]);
	}

	/**
	 * 注销某个POST变量
	 *
	 * @param string $s
	 * @return null
	 */
	function uPOST($s){
		if(isset($_POST[$s]))
			unset($_POST[$s]);
	}

	/**
	 * 判断是否设置某个GET请求
	 *
	 * @param string $s
	 * @return bool
	 */
	function iGET($s){
		return isset($_GET[$s]);
	}

	/**
	 * 注销某个GET变量
	 *
	 * @param string $s
	 * @return null
	 */
	function uGET($s){
		if(isset($_GET[$s]))
			unset($_GET[$s]);
	}

	/**
	 * 设置一个GET变量
	 *
	 * @param string $n 变量名
	 * @param string $v 变量值
	 * @return null
	 */
	function sGET($n, $v){
		$_GET[$n] = $v;
	}

	/**
	 * 设置一个POST变量
	 *
	 * @param string $n 变量名
	 * @param string $v 变量值
	 * @return null
	 */
	function sPOST($n, $v){
		$_POST[$n] = $v;
	}

	/**
	 * 获取POST文件
	 *
	 * @param string $s 表单名，为空返回全部，不存在返回空
	 * @return array
	 */
	function FILES($s = ''){
		if($s == '')
			return $_FILES;
		if(isset($_FILES[$s]))
			return $_FILES[$s];
		else return '';
	}

	/**
	 * 判断POST文件是否存在
	 *
	 * @param string $s 表单名
	 * @return bool
	 */
	function iFILES($s){
		return isset($_FILES[$s]);
	}

?>

<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 系统操作类
	 * 在运行初自动加载
	 * @file function/system.php
	 * @author  胡志宇
	 * @version 1.0
	 */
	//-------------------------------------------------------------

	/**
	 * 发送网页头文件，仅一次
	 * @param string $type    页面类型
	 * @param string $charset 网页编码
	 * @return null
	 */
	function send_header($type = '', $charset = ''){
		if(isset($GLOBALS['send_header']) && $GLOBALS['send_header'])
			return;
		global $config;
		if(!$type || !$charset){
			header("content-Type: text/html; charset=" . $config['char_set']);
		}
		else{
			header("content-Type: $type; charset=$charset");
		}
		$GLOBALS['send_header'] = true;
	}

	/**
	 * 加载一个模块
	 * @param string $name    文件名前缀
	 * @param bool   $is_func 是否为函数模块，默认为类模块
	 * @return bool
	 */
	function load($name = "", $is_func = false){
		//加载函数或类
		if(empty($name))
			return false;
		if($is_func)
			require_once(FUNCTION_PATH . $name . ".php");
		else require_once(CLASS_PATH . $name . ".php");
		return true;
	}

	/**
	 * 获取配置文件中的内容
	 * @param string $s 配置别名，留空返回数组，不存在返回空
	 * @return string
	 */
	function get_config($s = ''){
		global $config;
		if($s == '')
			return $config;
		if(!isset($config[$s]))
			return '';
		else return $config[$s];
	}

	/**
	 * 获取数据库操作的引用
	 * @param null
	 * @return object
	 */
	function get_db(){
		global $zxdb;
		return $zxdb;
	}

	/**
	 * 获取模板操作的引用
	 * @param null
	 * @return object
	 */
	function get_tmp(){
		global $zxtmp;
		return $zxtmp;
	}

	/**
	 * 获取系统操作的引用
	 * @param null
	 * @return object
	 */
	function get_sys(){
		global $zxsys;
		return $zxsys;
	}

	/**
	 * 生成密码
	 * @param string $string md5密码值
	 * @return string hash
	 */
	function make_password($string){
		//字符串必须是md5值
		$l = strlen($string);
		if($l < 32)
			$string = md5($string);
		else if($l > 32)
			$string = substr($string, 0, 32);
		$string = md5(substr($string, 0, 11)) . sha1(substr($string, 11));
		return sha1($string);
	}

	/**
	 * 字符串转换为bool类型
	 *
	 * @param string &$s 传引用修改$s的值
	 * @return null
	 */
	function string_to_bool(&$s){
		if(strtolower($s) == 'true')
			$s = true;
		else $s = false;
	}

	/**
	 * 返回当前页面URL
	 *
	 * @param null
	 * @return string url
	 */
	function now_url(){
		if(isset($_SERVER['URL']))
			return $_SERVER['URL'];
		return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * 生成随机字符串
	 * @param integer $length 字符长度，默认32个字符
	 * @return string
	 */
	function rand_new_key($length = 32){
		//随机生成字符串
		$pattern = '0123456789qwertyuiopasdfghjklzxcvbnmMNBVCXZLKJHGFDSAPOIUYTREWQ';
		$key = null;
		$len = strlen($pattern);
		for($i = 0; $i < $length; $i++){
			$key .= $pattern{mt_rand(0, $len - 1)};
		}
		return $key;
	}

	/**
	 * 加密函数
	 * @param string $encrypt 要加密的数据
	 * @param string $key     加密字符串,默认为空
	 * @return string
	 */
	function encrypt($encrypt, $key = ''){
		global $zxsys;
        $key = md5($key.$zxsys->get_setting("site_key"));
        $aesKey = substr($key, 0, 16);
        $aesIv = substr($key, 16, 16);

        $passEncrypt = (string) openssl_encrypt($encrypt, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $aesIv);

        return base64_encode($passEncrypt);
	}

	/**
	 * 解密函数
	 * @param string $decrypt 要解密的数据
	 * @param string $key     解密字符串,默认为空
	 * @return string
	 */
	function decrypt($decrypt, $key = ''){
		global $zxsys;
		$key = md5($key . $zxsys->get_setting("site_key"));
		$decoded = base64_decode($decrypt);
		$aesKey = substr($key,0,16);
		$aesIv = substr($key,16,16);

        return (string)openssl_decrypt($decoded, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $aesIv);
	}

	/**
	 * 跳转到来路页面
	 * 来路不存在输出错误信息，否则跳转
	 * @param string $s 输出字符串
	 * @return null
	 */
	function redirect_form($s = ''){
		if(empty($_SERVER['HTTP_REFERER']))
			die($s);
		rediretct($_SERVER['HTTP_REFERER'], 'refresh');
	}

	/**
	 * 跳转函数
	 * @param string $uri                网址
	 * @param string $method             跳转方式 location,refresh
	 * @param        $http_response_code 状态代码
	 * @return null
	 */
	function redirect($uri = '', $method = 'location', $http_response_code = 302){
		if(!preg_match('#^https?://#i', $uri)){
			$uri = site_url($uri);
		}
		switch($method){
			case 'refresh' :
				header("Refresh:0;url=" . $uri);
				break;
			default :
				header("Location: " . $uri, true, $http_response_code);
				break;
		}
		exit;
	}

	/**
	 * 检测是否为正确的邮箱
	 * @param string $field 邮箱地址
	 * @return bool
	 */
	function is_mail($field){
		if(filter_var($field, FILTER_VALIDATE_EMAIL))
			return true;
		else return false;
	}

	/**
	 * 检测是否为正确的URL
	 *
	 * @param string $field 网址
	 * @return bool
	 */
	function is_url($field){
		if(filter_var($field, FILTER_VALIDATE_URL))
			return true;
		else return false;
	}

	/**
	 * 判断是否为管理员账户
	 * @param null
	 * @return bool
	 */
	function is_admin(){
		global $zxuser;
		if($zxuser->get_power() == 0)
			return true;
		else return false;
	}

	/**
	 * 获取用户权限
	 * 通过user类
	 *
	 * @param null
	 * @return integer
	 */
	function get_power(){
		global $zxuser;
		return $zxuser->get_power();
	}

	/**
	 * 检测时间是否合法
	 * @param integer $year 年
	 * @param integer $mon  月
	 * @param integer $day  日
	 * @param integer $h    时
	 * @param integer $m    分
	 * @param integer $s    秒
	 * @return null
	 */
	function check_time($year, $mon = 1, $day = 1, $h = 0, $m = 0, $s = 0){
		//检测时间是否合法
		if(!checkdate($mon, $day, $year))
			return false;
		//不考虑润秒的稀奇时间
		if($h < 0 || $h > 23 || $m < 0 || $m > 59 || $s < 0 || $s > 59)
			return false;
		return true;
	}

	/**
	 * 检测是否开放注册
	 * @param null
	 * @return bool
	 */
	function allow_register(){
		global $zxsys;
		if(!$zxsys->get_setting("site_register"))
			return false;
		else return true;
	}

	/**
	 * 尽量获取真实IP
	 *
	 * @return string
	 */
	function get_ip(){
		if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR");
		else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return ($ip);
	}

	/**
	 * 获取浏览器UA
	 *
	 * @return string
	 **/
	function get_ua(){
		if(!isset($_SERVER['HTTP_USER_AGENT']))
			$_SERVER['HTTP_USER_AGENT'] = '';
		return $_SERVER['HTTP_USER_AGENT'];
	}

?>
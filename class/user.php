<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');
	/**
	 * 用户中心类
	 * 仅涉及到用户登录及基本信息判断，在系统最初加载
	 * @file class/user.php
	 * @author  胡志宇
	 * @version 1.0
	 */
	class User{

		/**
		 * 提交的登录信息数组
		 */
		private $login_info;

		/**
		 * 错误信息数组
		 */
		private $errors;

		/**
		 * 获取到的登录信息COOKIE数组
		 */
		private $login_cookies;

		/**
		 * 判断是否已登录，默认构造false
		 */
		public $is_login;

		/**
		 * 已登录用户的简要信息
		 */
		public $user_info;

		/**
		 * 用户登录来路地址，默认构造跳转用户中心
		 */
		public $from_url;


		/**
		 * 默认构造方法
		 */
		function __construct(){
			$this->is_login = false;
			$this->login_info = array();
			$this->errors = array();
			$this->login_cookies = array();
			$this->user_info = array();
			$this->from_url = site_url("user/");
		}

		/**
		 * 设置登录来路地址，并检测地址真实性，过滤特殊地址
		 * @param string $url 包含HTTP的网址
		 * @access public
		 * @return null
		 */
		public function set_from($url){
			if(!empty($url) && filter_var($url, FILTER_VALIDATE_URL)){
				$s = parse_url($url);

				if(isset($s['host']) && $s['host'] == $_SERVER['HTTP_HOST']){
					if(isset($s['path']))
						$p = basename($s['path']);
					if($p == "logging.php" || $p == 'login.php' || $p == 'logout.php')
						return;
					$this->from_url = $url;
				}
			}
		}

		/**
		 * POST登录框，登录调用函数，允许使用一个数组参数或一串参数
		 * 登录成功后返回true,失败后返回false
		 *
		 * @param string $user     如果参数为数组则包含相应的字段列表，为字符串时为邮箱名
		 * @param string $password POST密码值
		 * @param string $keep     是否保持登录状态，保持登录为 1
		 * @param bool   $md5      判断密码是否提交的MD5值，默认为false
		 * @access public
		 * @return bool
		 */
		public function logining($user = '', $password = '', $keep = '', $md5 = false){
			if(is_array($user)){
				//判断为数组
				if(!isset($user['user']) || !isset($user['password']) || !isset($user['keep']) || !isset($user['md5'])){
					$this->login_info = array();
				}
				else{
					$this->login_info['user'] = $user['user'];
					$this->login_info['keep'] = $user['keep'];
					$this->login_info['password'] = $user['password'];
					$this->login_info['md5'] = $user['md5'];
				}
			}
			else if(!isset($user) || !isset($password) || !isset($keep) || !isset($md5)){
				$this->login_info = array();
			}
			else{
				$this->login_info['user'] = $user;
				$this->login_info['password'] = $password;
				$this->login_info['keep'] = $keep;
				$this->login_info['md5'] = $md5;
			}

			$this->login_info['user'] = strtolower($this->login_info['user']);

			//将密码装换为md5数据
			if(!$this->login_info['md5'])
				$this->login_info['password'] = md5($this->login_info['password']);

			$err = $this->check_account(); //检查存储的登录信息正确性

			if($err != 'OK'){
				array_push($this->errors, $err); //添加一个错误信息
				return false;
			}
			if(!$this->make_cookie() || !$this->set_login_cookies()){
				array_push($this->errors, "无法设置Cookie以确保登录"); //添加一个错误信息
				return false;
			}
			$this->is_login = true;
			run_plugin('login');
			run_plugin('post_login');
			return true;
		}

		/**
		 * 检查当前账户是否允许登录登录失败是返回错误的登录信息
		 * @param null
		 * @access private
		 * @return string
		 **/
		private function check_account(){
			//失败返回错误信息
			//成功返回 OK
			global $zxdb;
			if(isset($this->login_info['user'])){
				//post登录
				$s = $zxdb->select("user", "id,user,username,password,active,lock", "user=\"" . SS($this->login_info['user']) . "\" LIMIT 0 , 30");
				if(!isset($s[0]['user']) || $s[0]['user'] != $this->login_info['user'])
					return '用户名不存在';
				if($s[0]['password'] != make_password($this->login_info['password']))
					return '密码错误';
				string_to_bool($s[0]['lock']);
				string_to_bool($s[0]['active']);
				if($s[0]['lock'])
					return '账户被锁定';
				$this->user_info = $s[0];
				unset($s);
				$this->login_info['id'] = $this->user_info['id'];

			}
			else if(isset($this->login_info['id'])){
				//cookie登录
				$s = $zxdb->select("user", "id,user,username,password,active,lock,power", "`id`=" . SS($this->login_info['id']) . " LIMIT 0 , 30");
				if(!isset($s[0]['id']) || $s[0]['id'] != $this->login_info['id'])
					return '用户 ID 不存在';
				if($s[0]['password'] != make_password($this->login_info['password']))
					return '密码错误';
				string_to_bool($s[0]['lock']);
				string_to_bool($s[0]['active']);
				if($s[0]['lock'])
					return '账户被锁定';
				$this->user_info = $s[0];
				unset($s);
				$this->login_info['user'] = $this->user_info['user'];
			}
			else return "用户名或ID错误";

			return 'OK';
		}

		/**
		 * 设置账户登录成功后Cookies,返回给浏览器
		 *
		 * @param null
		 * @access private
		 * @return bool
		 **/
		private function set_login_cookies(){
			if(!isset($this->login_cookies['browser']))
				return false;

			if($this->login_info['keep']){
				global $zxsys;
				set_cookies("lg", $this->login_cookies['browser'], $zxsys->get_setting("site_cookie_time") * 3600);
			}
			else{
				set_cookies("lg", $this->login_cookies['browser']);
			}
			return true;
		}

		/**
		 * 通过加密方式生成Cookie存储到变量中
		 *
		 * @param null
		 * @access private
		 * @return bool
		 **/
		private function make_cookie(){
			if(!isset($this->login_info['id']))
				return false;
			$this->login_cookies['browser'] = encrypt($this->login_info['id'] . "\t" . $this->login_info['password'], $_SERVER['HTTP_USER_AGENT']);
			return true;
		}

		/**
		 * 进行自动Cookies检测登录，如果登录成功更改登录状态，失败保留返回状态
		 *
		 * @param null
		 * @access public
		 * @return bool
		 **/
		public function auto_login(){
			if($this->is_login)
				return true;
			if(!$this->get_login_cookie())
				return false;

			$err = $this->check_account();
			if($err != 'OK'){
				//array_push($this->errors,$err);//添加自动登录错误信息
				return false;
			}
			$this->is_login = true;

			/**
			 * 此处可添加登录成功后的其他操作
			 */
			run_plugin('login');
			run_plugin('auto_login');

			return true;
		}

		/**
		 * 获取Cookies中的登录信息，并对其进行解密
		 *
		 * @param null
		 * @access private
		 * @return bool
		 **/
		private function get_login_cookie(){
			$cookie = get_cookies("lg");
			$cookie = explode("\t", decrypt($cookie, $_SERVER['HTTP_USER_AGENT']));
			if(!isset($cookie[0]) || !isset($cookie[1]) || !is_numeric($cookie[0]))
				return false;
			$this->login_info['id'] = $cookie[0];
			$this->login_info['password'] = $cookie[1];
			return true;
		}

		/**
		 * 调用该函数后跳转到登录页面，重定向函数将结束该页面代码
		 * 并且会自动产生登录后的跳转路径
		 * @param null
		 * @access public
		 * @return null
		 **/
		public function redirect_to_login(){
			redirect(site_url() . 'login.php?redirect=' . urlencode(now_url()), 'refresh');
		}

		/**
		 * 进行登录成功后的跳转
		 * @param null
		 * @access public
		 * @return null
		 **/
		public function jump(){
			redirect($this->from_url, 'refresh');
		}

		/**
		 * 返回全部错误信息
		 * @param string $before 每条信息前的字符
		 * @param string $end    每条信息后的字符
		 * @access public
		 * @return string
		 **/
		public function show_errors($before = '', $end = ''){
			$tmp = '';
			foreach($this->errors as $v)
				$tmp .= $before . ' ' . $v . ' ' . $end . "\n";
			return $tmp;

		}

		/**
		 * 获取当前用户的某项信息
		 * @param $type 返回用户信息中的$type字段，当该字段设置为空时，返回全部信息，不存在返回空
		 * @access public
		 * @return string
		 **/
		public function get_user($type = ''){
			if(!$type == '')
				return $this->user_info;
			if(!isset($this->user_info[$type]))
				return '';
			return $this->user_info[$type];
		}

		/**
		 * 获取当前用户的权限等级,默认返回2级
		 * @param null
		 * @access public
		 * @return integer
		 **/
		public function get_power(){
			//获取权限
			if(!isset($this->user_info['power']))
				return 2;
			else return $this->user_info['power'];
		}

		/**
		 * 用户注销调用函数，并且判断来路以便注销后返回
		 * @param null
		 * @access public
		 * @return null
		 **/
		public function logout(){
			delete_cookie('lg'); //登录信息COOKIE
			delete_cookie('super_admin'); //超级管理员登录信息
			/**
			 * 如设置其他登录信息，需在此继续清除
			 */
			run_plugin('logout');
			$this->set_from($_SERVER['HTTP_REFERER']);
		}

		/**
		 * 根据用户的登录信息判断用户是否为激活账户
		 * 数据库字段为 active
		 *
		 * @param null
		 * @access public
		 * @return bool
		 **/
		public function is_active(){
			if(isset($this->user_info['active']) && $this->user_info['active'])
				return true;
			else return false;
		}

		/**
		 * 用户名正确性检查，需符合仅有字母和数字的要求
		 * @param string $s 用户名
		 * @access public
		 * @return bool
		 **/
		public function is_true_username($s){
			if(strlen($s) < 2)
				return false;
			for($i = 0; $i < strlen($s); $i++){
				$l = ord($s{$i});
				if(!(($l > 47 && $l < 58) || ($l > 64 && $l < 91) || ($l > 96 && $l < 123)))
					return false;
			}
			return true;
		}

		/**
		 * 获取用户的权限分组
		 * 以权限由低到高排列
		 * @param null
		 * @access public
		 * @return null
		 **/
		public function get_power_group(){
			return array(2 => '用户', 1 => '管理组', 0 => '管理员');
		}
	}

?>
<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * Session 操作类
	 *
	 * @file  class/session.php
	 * @author   胡志宇
	 * @version  1.0
	 */

	class Session{

		/**
		 * 默认构造器
		 * @param null
		 * @access public
		 * @return null
		 */
		function __construct(){
			session_start();
		}

		/**
		 * 设置一个Session
		 *
		 * @param string $name  session名称
		 * @param string $value 该字段的值
		 * @access public
		 * @return bool
		 */
		public function set($name, $value){
			if($name == '')
				return false;
			$_SESSION[$name] = $value;
			return true;
		}

		/**
		 * 获取一个Session的值
		 *
		 * @param string $name Session字段名
		 * @access public
		 * @return string
		 */
		public function get($name){
			if(!isset($_SESSION[$name]))
				return false;
			return $_SESSION[$name];
		}

		public function delete($name){
			unset($_SESSION[$name]);
		}

		public function get_name(){
			return session_name();
		}

		public function get_id(){
			return session_id();
		}

		/**
		 * 检查SESSION中的$name的值是否为$value
		 *
		 * @param string $name
		 * @prarm $string $value
		 * @access public
		 * @return bool
		 */
		public function check($name, $value){
			if(!isset($_SESSION[$name]))
				return false;
			if($value == '')
				return false;
			if($_SESSION[$name] == $value)
				return true;
			else return false;
		}

		/**
		 * 检测email Session
		 *
		 * @param string $name  Session字段名
		 * @param string $value Session值，非真实值，缺email
		 * @param string $email Session中的email
		 * @access public
		 * @return bool
		 */
		public function email_check($name, $value, $email){
			if(!isset($_SESSION[$name]))
				return false;
			if($value == '')
				return false;
			return $_SESSION[$name] == $value . $email;
		}
	}

?>
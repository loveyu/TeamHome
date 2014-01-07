<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 邮件操作类
	 * 根据PHPmailer拓展出来的邮件操作
	 *
	 * @file class/mail.php
	 * @author  胡志宇
	 * @version 1.0
	 */

	class Mail{
		/**
		 * PHPmailer类实例化
		 */
		private $mail;

		/**
		 * 配置信息数组
		 */
		private $config;

		/**
		 * 邮箱地址是否已添加
		 */
		private $add_status;

		/**
		 * 邮件信息是否已添加
		 */
		private $text_status;

		/**
		 * 邮件主题是否已添加
		 */
		private $subject_status;

		/**
		 * 默认构造器
		 * @param null
		 * @access public
		 * @retrun null
		 */
		function __construct(){
			require_once(CLASS_PATH . "phpmailer.php");
			$this->mail = new PHPMailer(true);
			$this->mail->PluginDir = CLASS_PATH;
			$this->set_config();
			$this->add_status = false;
			$this->text_status = false;
			$this->subject_status = false;
		}

		/**
		 * 读取数据库中的配置信息，并设置
		 * @param null
		 * @access public
		 * @retrun null
		 */
		private function set_config(){
			$this->get_config();
			if($this->config['type'] == 'smtp'){
				$this->mail->IsSMTP();
				$this->mail->Host = $this->config['smtp_host'];
				$this->mail->Username = $this->config['smtp_user'];
				$this->mail->Password = $this->config['smtp_pass'];
				$this->mail->Port = $this->config['smtp_port'];
				if($this->config['smtp_auth'] == 'true' || $this->config['smtp_auth'] == 'TRUE')
					$this->mail->SMTPAuth = true;
				$this->mail->SMTPDebug = $this->config['smtp_debug'];
				;
			}
			$this->mail->CharSet = "UTF-8";
			$this->mail->XMailer = $this->config['useragent'];
			$this->mail->AddReplyTo($this->config['from_email'], $this->config['from_name']); //回复地址
			$this->mail->SetFrom($this->config['from_email'], $this->config['from_name']);
		}

		/**
		 * 增加一个邮箱地址
		 * @param string $email 邮箱地址
		 * @param string $user  邮箱用户名称呼，留空自动截取
		 * @access public
		 * @retrun bool
		 */
		public function add($email, $user = ''){
			if(!is_mail($email))
				return false;
			if(empty($user))
				$user = $this->mail_to_user($email);
			$this->mail->AddAddress($email, $user);
			$this->add_status = true;
			return true;
		}

		/**
		 * 设置邮件文本内容
		 * @param string $html 邮件内容,html格式
		 * @access public
		 * @retrun book
		 */
		public function html($html){
			if($html == '')
				return false;
			$this->mail->MsgHTML($html);
			$this->text_status = true;
			return true;
		}

		/**
		 * 设置邮件标题
		 * @param null
		 * @access public
		 * @retrun null
		 */
		public function title($title){
			if($title == '')
				return false;
			$this->mail->Subject = $title;
			$this->subject_status = true;
		}

		/**
		 * 发送邮件，先检测状态信息
		 * @param null
		 * @access public
		 * @retrun bool
		 */
		public function send(){
			if(!$this->add_status || !$this->text_status || !$this->subject_status)
				return false;
			$flag = false;
			try{
				if($this->mail->Send())
					$flag = true;
			} catch(phpmailerException $e){
				//echo $e->errorMessage(); //Pretty error messages from PHPMailer
			} catch(Exception $e){
				//echo $e->getMessage(); //Boring error messages from anything else!
			}
			return $flag;
		}

		/**
		 * 从数据库读取邮件配置信息，该条非自动加载
		 * @param null
		 * @access public
		 * @retrun null
		 */
		private function get_config(){
			global $zxdb;
			$t = explode(';', $zxdb->select_once("setting", "value", "name='mail_config'")->value);
			$this->config = array();
			foreach($t as $v){
				$n = strpos($v, ':');
				$this->config[substr($v, 0, $n)] = substr($v, $n + 1);
			}
		}

		/**
		 * 从邮箱中分离用户名
		 * @param null
		 * @access public
		 * @retrun string
		 */
		private function mail_to_user($email){
			$t = explode('@', $email);
			return $t[0];
		}

		/**
		 * 获取配置文件列表，用于邮件设置的读取与比较
		 * @param null
		 * @access public
		 * @retrun array
		 */
		public function get_config_list(){
			return $this->config;
		}

		/**
		 * 判断是否为用户的邮箱，不对参数进行检测
		 * @param string $email 邮箱
		 * @access public
		 * @retrun bool
		 */
		public function is_user_mail($email){
			global $zxdb;
			if($zxdb->sql_check("user", "user=\"" . SS($email) . "\""))
				return true;
			else return false;
		}

	}

?>
<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 用户管理及信息编辑操作类
	 * 涉及用户中心操作时加载到 user/init_user.php
	 *
	 * @file class/user_action.php
	 * @author  胡志宇
	 * @version 1.0
	 */

	class User_action{
		/**
		 * 从$zxuser全局变量获取的用户信息
		 */
		private $zx_info;
		/**
		 * 登陆成功后获取的全部用户信息
		 */
		public $user_info;
		/**
		 * 数据库操作类引用
		 */
		private $db;
		/**
		 * 用户中心页面信息
		 */
		public $page_info;

		/**
		 * 默认构造器
		 * @param null
		 * @access public
		 * @return null
		 */
		function __construct(){
			global $zxuser, $zxdb;
			$this->zx_info = $zxuser->user_info;
			$this->user_info = array();
			$this->db = & $zxdb;
			if(is_login())
				$this->get_info();
		}

		/**
		 * 删除某一个用户
		 * @param integer $id 用户ID
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function delete_user($id){
			if(!is_admin())
				return '非管理员操作';
			if(!is_numeric($id) || $id < 1)
				return 'ID格式错误';
			if($this->check_id($id))
				return '管理员不允许操作删除自己的账户';
			$info = $this->get_one_info($id, "id,username");
			if(empty($info))
				return '非用户ID';
			if(!$this->db->delete("user", "`id`=" . SS($id)))
				return '删除账户失败';

			//---删除包含该用户的其他信息
			$this->db->update("group", array('lader' => 0), "lader=" . $id); //删除分组组长信息

			if(!empty($info) && !empty($info['username']) && $info['username'] != 'NULL'){
				load("file_action");
				$file = new File_action();
				if($file->delete_user_path($info['username']))
					$this->up_admin_action_record("删除用户,ID:" . $id . ",文件夹删除出现错误", get_user_id());
			}

			//----------------------
			$this->up_admin_action_record("删除用户,ID:" . $id, get_user_id()); //添加管理员用户操作记录
			return 'OK';
		}

		/**
		 * 取得用户的真实姓名
		 * @param null
		 * @access public
		 * @return string
		 */
		public function get_true_name(){
			//获取用户的真实姓名
			return $this->user_info['name'];
		}

		/**
		 * 锁定用户
		 * @param integer $id 用户ID
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function lock_user($id){
			//锁定用户
			if(!is_admin())
				return '非管理员操作';
			if(!is_numeric($id) || $id < 1)
				return 'ID格式错误';
			if($this->check_id($id))
				return '管理员不允许操作锁定自己的账户';
			if(!$this->is_user_id($id))
				return '非用户ID';
			if(!$this->db->update("user", array('lock' => 'true'), "`id`=" . $id))
				return '锁定账户失败';

			$this->up_admin_action_record("锁定用户,ID:" . $id, get_user_id());
			return 'OK';
		}

		/**
		 * 解锁用户
		 * @param integer $id 用户ID
		 * @access public
		 * @return 错误信息，成功返回OK
		 */
		public function unlock_user($id){
			//解锁用户
			if(!is_admin())
				return '非管理员操作';
			if(!is_numeric($id) || $id < 1)
				return 'ID格式错误';
			if($this->check_id($id))
				return '管理员不允许操作锁定自己的账户';
			if(!$this->is_user_id($id))
				return '非用户ID';
			if(!$this->db->update("user", array('lock' => 'false'), "`id`=" . $id))
				return '解锁账户失败';

			$this->up_admin_action_record("解锁用户,ID:" . $id, get_user_id());
			return 'OK';
		}

		/**
		 * 获取当前用户全部信息
		 * @param null
		 * @access public
		 * @return array
		 */
		public function get_info(){
			//获取信息
			if(!empty($this->user_info))
				return $this->user_info;
			$this->user_info = $this->db->select("user", "*", 'id=' . $this->zx_info['id']);
			$this->user_info = $this->user_info[0];
			foreach($this->user_info as $id => $v){
				if($v == 'NULL')
					$this->user_info[$id] = '';
			}
			return $this->user_info;
		}

		/**
		 * 获取一个用户的部分信息列表
		 * @param integer $id   用户ID
		 * @param string  $info 数据库中的字段名 `name`,`id`,`user`
		 * @access public
		 * @return array
		 */
		public function get_one_info($id, $info = "*"){
			if(!is_numeric($id) || $id < 1)
				return array();
			$s = $this->db->select("user", SS($info), 'id=' . $id);
			if(isset($s[0]))
				$s = $s[0];
			if(empty($s))
				return array();
			foreach($s as $id => $v){
				if($v == 'NULL')
					$s[$id] = '';
			}
			return $s;
		}

		/**
		 * 生成用户中心的页面信息
		 * @param null
		 * @access private
		 * @return null
		 */
		private function make_page_info(){
			global $zxsys;
			$this->page_info = array();
			$this->page_info['number'] = $this->db->count_sql("user");
			$this->page_info['one_page'] = $zxsys->get_setting("user_manage_one_page_number");
			$this->page_info['remainder'] = $this->page_info['number'] % $this->page_info['one_page'];
			$this->page_info['page'] = ($this->page_info['number'] - $this->page_info['remainder']) / $this->page_info['one_page'];
			if($this->page_info['remainder'] > 0)
				$this->page_info['page']++; //余数不为0，页面数加1
		}

		/**
		 * 获取用户列表
		 * @param integer $page 当前第几页
		 * @access public
		 * @return array
		 */
		public function get_user_list($page){
			$this->make_page_info();
			if(!is_numeric($page) || $page < 1)
				$page = 1;
			if($page > $this->page_info['page'])
				return array();
			return $this->db->select("user", "*", "1 LIMIT " . ($page - 1) * $this->page_info['one_page'] . ", " . $this->page_info['one_page']);
		}

		/**
		 * 随机获取一部分有效用户信息
		 * @param array   $field 字段数组
		 * @param integer $num   数量
		 * @access public
		 * @return array
		 */
		public function get_rand_user_list($field = array(), $num = 20){
			if(empty($field))
				$field = array('id', 'username');
			if(!is_numeric($num) || $num < 1)
				$num = 20;
			foreach($field as $id => $v)
				$field[$id] = SS($v);
			return $this->db->select("user", implode(',', $field), "lock='false' ORDER BY RAND() LIMIT $num");
		}

		/**
		 * 获取一个用户的信息
		 * @param integer $id    用户ID
		 * @param string  $while 字段名 `name`,`id`
		 * @access private
		 * @return object
		 */
		private function get_one_user_info($id, $while = '*'){
			//获取一个用户数据，返回为对象
			return $this->db->select_once("user", SS($while), "`id`=" . $id);
		}

		/**
		 * 获取用户的权限
		 * @param null
		 * @access public
		 * @return integer
		 */
		public function get_power(){
			//获取权限
			if(!isset($this->user_info['power']))
				return 2;
			else return $this->user_info['power'];
		}

		/**
		 * 编辑用户权限
		 * @param integer $id    用户ID
		 * @param integer $power 用户权限
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function edit_power($id, $power){
			//修改用户权限
			if(!$this->is_true_id($id))
				return 'ID不正确';
			if(!$this->is_user_id($id))
				return '不存在该ID';
			if(!in_array($power, array(0, 1, 2)))
				return '该权限不存在';
			if(!is_admin())
				return '非管理员操作';
			if(!$this->db->update("user", array('power' => $power), "`id`=" . $id))
				return '更新权限失败';
			return 'OK';
		}

		/**
		 * 编辑用户信息
		 * @param integer $id 用户ID
		 * @param array   $s  用户更新的信息
		 * @access public
		 * @return bool
		 */
		public function edit_user($id, $s = array()){
			//编辑用户
			if(empty($s))
				return false;
			$ns = array();
			foreach($s as $n => $v)
				$ns[SS($n)] = SS($v);
			return $this->db->update("user", $ns, "id=$id");
		}

		/**
		 * 编辑用户的用户名
		 * @param integer $id 用户ID
		 * @param string  $s  新的用户名
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function edit_username($id, $s){
			if(!$this->is_true_username($s))
				return "用户名无效";
			$ui = $this->get_one_user_info($id, "username"); //该构造返回一个对象
			if($ui->username == $s)
				return '用户名相同，无法更改';
			if($ui->username != '' && $ui->username != 'NULL'){
				//
				if(!$this->edit_user_path($s, $ui->username))
					return "用户文件路径修改失败，修改失败,请联系管理员";
			}
			if($this->edit_user($id, array('username' => $s)))
				return 'OK';
			else return '更新用户名失败，但用户文件已修改，请联系管理员';
		}

		/**
		 * 编辑用户邮箱
		 * @param integer $id    用户ID
		 * @param string  $email 邮箱格式
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function edit_email($id, $email){
			if(!is_mail($email))
				return '邮箱格式错误';
			if($this->is_user_mail($email))
				return '邮箱已存在，或相同，无法修改';
			if($this->edit_user($id, array('user' => $email)))
				return 'OK';
			else return '更新用户名失败，请联系管理员';
		}

		/**
		 * 重置用户密码
		 * @param integer $id   用户ID
		 * @param string  $type 用户类型unlock,lock,all
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function rest_password($id, $type){
			//密码重置,$type为类型，unlock 为非锁定用户,lock为锁定用户,all为全部,其他同all
			if(!is_admin())
				return '非管理员账户';
			$ui = $this->get_one_user_info($id, "lock,user,username"); //该构造返回一个对象
			if(!isset($ui->lock))
				return '用户不存在';
			switch(strtolower($type)){
				case 'lock':
				{
					if(strtolower($ui->lock) == 'false')
						return '当前无法重置被锁定的用户密码';
				}
					break;
				case 'unlock':
				{
					if(strtolower($ui->lock) == 'true')
						return '当前无法重置非锁定的用户密码';
				}
					break;
			}
			$pwd = rand_new_key(10);
			if(!$this->change_password($id, md5($pwd)))
				return '修改密码失败';
			if(!$this->rest_password_mail($pwd, $ui->user, $ui->username))
				return '密码已修改,但邮件发送失败，请手动操作数据库';
			$this->up_admin_action_record("重置用户密码,用户ID:" . $id, get_user_id());
			return 'OK';
		}

		/**
		 * 发送密码到邮箱
		 * @param integer $pwd   用户密码，原文
		 * @param string  $email 邮箱格式
		 * @param string  $user  邮箱用户名
		 * @access private
		 * @return bool
		 */
		private function rest_password_mail($pwd, $email, $user){
			load('mail');
			$mail = new Mail();
			$mail->add($email, $user);
			$mail->title("你的新网站密码 - " . site_title());
			$mail->html("重置的密码为:&nbsp;&nbsp;" . $pwd . "<br />请定期修改密码！");
			return $mail->send();
		}

		/**
		 * 修改密码的值
		 * @param integer $id       用户ID
		 * @param string  $password md5格式
		 * @access private
		 * @return bool
		 */
		private function change_password($id, $password){
			//md5密码值 修改密码
			return $this->db->update("user", array('password' => make_password($password)), "`id`=" . $id);
		}

		/**
		 * 修改密码操作
		 * @param array $s 密码信息 pwd,new,confirm
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function edit_pwd($s = array()){
			//修改密码
			if(!isset($s['pwd']) || !isset($s['new']) || !isset($s['confirm']))
				return '表单有误';
			if($s['new'] != $s['confirm']){
				if($this->pwd_is_pass($s['new']))
					return '两次密码不一致';
				else return '两次密码不一致且密码不符合规则';
			}
			if(!$this->pwd_is_pass($s['new']))
				return '密码不符合规则';
			if(!$this->check_pwd(md5($s['pwd'])))
				return '原密码错误';
			if($this->change_password($this->user_info['id'], md5($s['new'])))
				return 'OK';
			else return '密码修改失败，请联系管理员';
		}

		/**
		 * 改变账户激活状态
		 * @param integer $id 用户ID
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function change_active_status($id){
			//改变账户激活状态
			if(!$this->is_true_id($id))
				return 'ID有误';
			if(!is_admin())
				return '非管理员登录';
			$s = $this->get_one_user_info($id, 'active');
			if(!isset($s->active))
				return '查找数据失败';
			if($s->active == 'true')
				$data = array('active' => 'false');
			else $data = array('active' => 'true');
			if($this->db->update('user', $data, "`id`=" . $id))
				return 'OK';
			else return '修改账户激活状态失败';
		}

		/**
		 * 更改用户验证状态
		 * @param integer $id 用户ID
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function change_verify_status($id){
			//改变账户激活状态
			if(!$this->is_true_id($id))
				return 'ID有误';
			if(!is_admin())
				return '非管理员登录';
			$s = $this->get_one_user_info($id, 'verify');
			if(!isset($s->verify))
				return '查找数据失败';
			if($s->verify == 'true')
				$data = array('verify' => 'false');
			else $data = array('verify' => 'true');
			if($this->db->update('user', $data, "`id`=" . $id))
				return 'OK';
			else return '修改账户验证状态失败';
		}

		/**
		 * 验证密码是否符合规范
		 * @param string $s 密码明文值
		 * @access pravite
		 * @return bool
		 */
		private function pwd_is_pass($s){
			if(strlen($s) < 8)
				return false;
			return true;
		}

		/**
		 * 检测用户文件夹路径是否存在
		 * @param null
		 * @access public
		 * @return null
		 */
		public function check_user_path(){
			$path = ROOT_PATH . get_config("user_path") . $this->user_info['username'];
			if(!is_dir($path)){
				mkdir($path);
				mkdir($path . "/css");
				mkdir($path . "/js");
				mkdir($path . "/img");
				mkdir($path . "/attached");
			}
			else{
				if(!is_dir($path . "/css"))
					mkdir($path . "/css");
				if(!is_dir($path . "/js"))
					mkdir($path . "/js");
				if(!is_dir($path . "/img"))
					mkdir($path . "/img");
				if(!is_dir($path . "/attached"))
					mkdir($path . "/attached");
			}
		}

		/**
		 * 修改用户文件夹名,修改用户名时调用
		 * @param string $new 新的文件夹名
		 * @param string $old 以前的文件夹名
		 * @access private
		 * @return boo;
		 */
		private function edit_user_path($new, $old){
			//当原目录不存在时默认返回true
			$path = ROOT_PATH . get_config("user_path");
			if(!is_dir($path . $old))
				return true;
			return @rename($path . $old, $path . $new);
		}

		/**
		 * 判断密码正确性
		 * @param string $s md5密码值
		 * @access public
		 * @return bool
		 */
		public function check_pwd($s){
			//检测密码是否正确 md5值
			if(make_password($s) == $this->user_info['password'])
				return true;
			else return false;
		}

		/**
		 * 检测用户名的存在性
		 * @param string $s 用户名
		 * @access public
		 * @return bool
		 */
		public function check_username($s){
			if($this->db->count_sql("user", 'username="' . SS($s) . '"') > 0)
				return true;
			else return false;
		}

		/**
		 * 判断是否为一个符合规范的用户名
		 * @param string $s 用户名
		 * @access public
		 * @return bool
		 */
		public function is_true_username($s){
			if($s == 'NULL')
				return false;
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
		 * 判断是否为一个正确的id
		 *
		 * @param integer $id 用户ID
		 * @access public
		 * @return bool
		 */
		public function is_true_id($id){
			return (is_numeric($id)) && ($id > 0);
		}

		/**
		 * 检测当前用户ID是否和提交的ID一致
		 *
		 * @param integer $id 用户ID
		 * @access public
		 * @return bool
		 */
		public function check_id($id){
			return $id == $this->user_info['id'];
		}

		/**
		 * 生成两数组中均存在且不同的项，返回新的值
		 * @param array $one 新的数组
		 * @param array $old 旧的数组，留空时使用用户信息替代
		 * @access public
		 * @return array
		 */
		public function check_edit_post($one, $old = array()){
			//返回两者均存在的数组且值不同的$one项
			if(!isset($old) || empty($old))
				$old = $this->user_info;
			$rt = array();
			if(!is_array($one))
				return $rt;
			foreach($one as $id => $v){
				if(isset($old[$id]) && $old[$id] != $v)
					$rt[$id] = $v;
			}
			return $rt;
		}

		/**
		 * 检测POST提交的用户编辑信息是否合法
		 *
		 * @param array $s POST数组
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function check_post($s){
			//检测表单是否合法
			$forbid = array('password', 'lock', 'active', 'power', 'avata', 'verify');
			foreach($s as $id => $v){
				if(in_array($id, $forbid))
					return '存在异常值';
			}
			return 'OK';
		}

		/**
		 * 判断是否存在该邮箱
		 * @param string $email 邮箱
		 * @access public
		 * @return bool
		 */
		public function is_user_mail($email){
			if($this->db->sql_check("user", "user=\"" . SS($email) . "\""))
				return true;
			else return false;
		}

		/**
		 * 判断是否存在该用户ID
		 *
		 * @param integer $id 用户ID
		 * @access public
		 * @return bool
		 */
		public function is_user_id($id){
			if(empty($id) || !is_numeric($id))
				return false;
			return $this->db->sql_check("user", "id=\"" . $id . "\"");
		}

		/**
		 * 更新一个管理员操作记录
		 * @param string  $c  记录内容
		 * @param integer $id 管理员ID
		 * @access public
		 * @return bool
		 */
		private function up_admin_action_record($c, $id){
			// 0 为超级管理员
			if($c == '')
				return false;
			if(!is_numeric($id) || $id < 0)
				return false;
			return $this->db->insert("admin", array('content' => $c, 'admin_id' => $id));
		}

		/**
		 * 管理员修改用户信息
		 * @param string $name
		 * @param string $tel
		 * @param string $mtel
		 * @param string $qq
		 * @param string $class
		 * @param string $grade
		 * @param string $faculty
		 * @param string $subject
		 * @param string $group1
		 * @param string $group2
		 * @param string $describe
		 * @param string $sign
		 * @param string $specialty
		 * @param string $action
		 * @param string $id 用户ID
		 * @access public
		 * @return string
		 */
		public function admin_edit_user_info($name, $sex, $tel, $mtel, $qq, $class, $grade, $faculty, $subject, $group1, $group2, $describe, $sign, $specialty, $id){
			if(!$this->is_true_id($id))
				return '用户ID有误';
			if(!$this->is_user_id($id))
				return '该用户不存在';
			$edit_info = array('name' => $name, 'sex' => $sex, 'tel' => $tel, 'mtel' => $mtel, 'qq' => $qq, 'class' => $class, 'grade' => $grade, 'faculty' => $faculty, 'subject' => $subject, 'group1' => $group1, 'group2' => $group2, 'describe' => $describe, 'sign' => $sign, 'specialty' => $specialty);
			if($this->check_post($edit_info) != 'OK')
				return '存在异常值';
			$true_edit = $this->check_edit_post($edit_info, $this->get_one_info($id, "name,tel,mtel,qq,class,grade,faculty,subject,group1,group2,describe,sign,specialty"));
			if(empty($true_edit))
				return '数据无需修改';
			$s = $this->check_user_info($true_edit);
			if($s != 'OK')
				return $s;
			if(!$this->edit_user($id, $true_edit))
				return '更新数据失败';
			else return 'OK';
		}

		/**
		 * 检查要修改的数据信息
		 * @param array $s 信息数组
		 * @access public
		 * @return string
		 */
		public function check_user_info($s){
			if(isset($s['name']) && $s['name'] != '' && strlen($s['name']) > 127)
				return '数组过长';
			if(isset($s['sex']) && $s['sex'] != '' && !in_array($s['sex'], array('男', '女')))
				return '性别有误';
			if(isset($s['tel']) && $s['tel'] != '' && (!is_numeric($s['tel']) || $s['tel'] < 1))
				return '电话号码有误';
			if(isset($s['mtel']) && $s['mtel'] != '' && (!is_numeric($s['mtel']) || $s['mtel'] < 1))
				return '短号有误';
			if(isset($s['qq']) && $s['qq'] != '' && (!is_numeric($s['qq']) || $s['qq'] < 1 || strlen($s['qq']) < 5))
				return 'QQ号有误';
			if(isset($s['class']) && $s['class'] != '' && (!is_numeric($s['class']) || $s['class'] < 1))
				return '班级有误';
			if(isset($s['grade']) && $s['grade'] != '' && (!is_numeric($s['grade']) || $s['grade'] < 2003))
				return '年级有误';
			return 'OK';
		}

		/**
		 * 激活用户
		 * @param string $username 用户名
		 * @access public
		 * @return string 错误信息
		 */
		public function user_active_username($username){
			global $zxuser;
			if($zxuser->is_active())
				return '你不需要激活';
			if(!empty($this->user_info['username']))
				return '请联系管理员激活';
			if(!$this->is_true_username($username))
				return '这个用户名有误';
			if($this->check_username($username))
				return '该用户名已经存在';
			if(is_dir(ROOT_PATH . get_config('usre_path') . $username))
				return '用户名似乎存在过，但存在异常，如果要使用，请联系管理员';
			if(!$this->db->update('user', array('username' => SS($username), 'active' => 'true'), "`id`=" . SS($this->user_info['id'])))
				return '更新数据失败';
			return 'OK';
		}

		/**
		 * 修改用户信息
		 * @param string $name
		 * @param string $sex
		 * @param string $tel
		 * @param string $mtel
		 * @param string $qq
		 * @param string $class
		 * @param string $grade
		 * @param string $faculty
		 * @param string $subject
		 * @param string $group1
		 * @param string $group2
		 * @param string $describe
		 * @param string $sign
		 * @param string $specialty
		 * @param string $action
		 * @access public
		 * @return string
		 */
		public function user_edit_user_info($name, $sex, $tel, $mtel, $qq, $class, $grade, $faculty, $subject, $group1, $group2, $describe, $sign, $specialty){
			$edit_info = array('name' => $name, 'sex' => $sex, 'tel' => $tel, 'mtel' => $mtel, 'qq' => $qq, 'class' => $class, 'grade' => $grade, 'faculty' => $faculty, 'subject' => $subject, 'group1' => $group1, 'group2' => $group2, 'describe' => $describe, 'sign' => $sign, 'specialty' => $specialty);
			if($this->check_post($edit_info) != 'OK')
				return '存在异常值';
			$true_edit = $this->check_edit_post($edit_info);
			if(empty($true_edit))
				return '数据无需修改';
			$s = $this->check_user_info($true_edit);
			if($s != 'OK')
				return $s;
			if(!$this->edit_user($this->user_info['id'], $true_edit))
				return '更新数据失败';
			else return 'OK';
		}
	}

?>
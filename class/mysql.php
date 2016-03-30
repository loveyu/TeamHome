<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 *    mysql类
	 *
	 *    对MYSQL数据库的操作,该类将在系统最初进行加载
	 *
	 * @file        class/mysql.php
	 * @author        胡志宇
	 * @version       1.0
	 *
	 */

	class Mysql{
		/**
		 *    数据查询次数统计
		 */
		private $query_count = 0;

		/**
		 *    配置文件信息
		 */
		private $config;

		/**
		 *    数据库链接信息
		 */
		private $con;

		/**
		 *    查询错误时是否发送头文件
		 */
		private $send_head = false;

		/**
		 *    配置问件的数据库配置
		 *
		 *    <code>
		 *    $db['hostname'] = 'localhost';
		 *    $db['username'] = 'root';
		 *    $db['password'] = '123456';
		 *    $db['database'] = 'zx';
		 *    $db['dbprefix'] = 'zx_';
		 *    $db['db_debug'] = true;
		 *    $db['char_set'] = 'utf8';
		 *    </code>
		 */

		/**
		 *    默认构造器
		 *    连接接数据库
		 *
		 * @param    null
		 * @access    public
		 * @retrun    null
		 */
		function __construct(){
			$this->set_config();
			$this->con = mysqli_connect($this->config['hostname'], $this->config['username'], $this->config['password']);
			if(!$this->con)
				$this->debug("数据库连接错误", true);
			mysqli_query($this->con,'SET NAMES ' . $this->config['char_set']);
			mysqli_query($this->con,'SET CHARACTER_SET_CLIENT=' . $this->config['char_set']);
			mysqli_query($this->con,'SET CHARACTER_SET_RESULTS=' . $this->config['char_set']);
			if(!mysqli_select_db($this->con, $this->config['database']))
				$this->debug("无法选择数据库，或数据库 " . $this->config['database'] . " 不存在:", true);
		}

		/**
		 *    读取配置文件中的数据库配置到类配置文件中
		 *
		 * @param    null
		 * @access    private
		 * @retrun    null
		 */
		private function set_config(){
			global $config;
			$this->config = array();
			$this->config = $config['db'];
		}

		/**
		 *    获取数据库配置文件
		 *
		 * @param    string    $name    配置名称
		 * @access    public
		 * @return     $name
		 */
		public function get_config($name){
			if(isset($this->config[$name]))
				return $this->config[$name];
			else return null;
		}

		/**
		 *    获取数据查询次数
		 *
		 * @param    null
		 * @access    public
		 * @retrun    integer
		 */
		public function get_count(){
			return $this->query_count;
		}

		/**
		 *    执行一条数据库语句
		 *
		 * @param    string    $sql
		 * @access    public
		 * @retrun    bool
		 */
		public function query($sql){
			$this->query_count++;
			//echo $sql."\n";
			return mysqli_query($this->con,$sql);
		}

		/**
		 *    选择数据返回二维数组
		 *
		 * @param    string    $table    数据库表名
		 * @param    string    $list     获取的字段列表 默认为 *, 例子： id,name,value
		 * @param    string    $where    循环条件，SQL语句WHERE之后的内容
		 * @access    public
		 * @retrun    array[][]
		 */
		public function select($table, $list = '*', $where = '1'){
			if($list != '*'){
				$list = str_replace(' ', '', $list);
				$list = str_replace('`', '', $list);
				$arr = explode(",", $list);
				foreach($arr as $n => $v){
					$arr[$n] = "`" . $v . "`";
				}
				$list = implode(",", $arr);
			}
			$sql = "SELECT " . $list . " FROM `" . $this->config['dbprefix'] . $table . "` WHERE " . $where;
			return $this->sql_to_arr($sql);
		}

		/**
		 *    使用IN条件获取数据,该操作会在数据中转义数据
		 *
		 * @param    string    $table         表名
		 * @param    string    $list          获取的字段列表 默认为 *, 例子： `id`,`name`,`value`
		 * @param    string    $name          选择条件的字段名
		 * @param    array     $value         符合条件的值列表
		 * @param    string    $is_numeric    $name字段是否为数字
		 * @access    public
		 * @retrun    array[][]
		 */
		public function select_where_in($table, $list = '*', $name, $value, $is_number){
			if(empty($value))
				return array();
			foreach($value as $id => $v){
				if($v == '')
					unset($value[$id]);
				else $value[$id] = SS($v);
			}
			if(!$is_number){
				foreach($value as $id => $v)
					$value[$id] = "'$v'";
			}
			return $this->select($table, $list, SS($name) . " IN (" . implode(', ', $value) . ")");
		}

		/**
		 *    获取数据中的第一条数据对象
		 *
		 * @param    string    $table    数据库表名
		 * @param    string    $list     获取的字段列表 默认为 *, 例子： `id`,`name`,`value`
		 * @param    string    $where    循环条件，SQL语句WHERE之后的内容
		 * @access    public
		 * @retrun    object
		 */
		public function select_once($table, $list = '*', $where = '1'){
			//该方法返回一个对象
			if($list != '*'){
				$list = str_replace(' ', '', $list);
				$list = str_replace('`', '', $list);
				$arr = explode(",", $list);
				foreach($arr as $n => $v){
					$arr[$n] = "`" . $v . "`";
				}
				$list = implode(",", $arr);
			}
			$sql = "SELECT " . $list . " FROM `" . $this->config['dbprefix'] . $table . "` WHERE " . $where;
			$result = $this->query($sql);
			if(!$result)
				$this->debug("数据选择失败", true);
			$r = mysqli_fetch_object($result);
			mysqli_free_result($result);
			return $r;
		}

		/**
		 *    更新数据库信息
		 *
		 * @param    string    $table         数据库表名
		 * @param    array     $name_value    字段名及值，分别对应字段和值
		 * @param    string    $where         循环条件，SQL语句WHERE之后的内容
		 * @access    public
		 * @retrun    bool
		 */
		public function update($table, $name_value, $while = 1){
			$setStr = null;
			foreach($name_value as $name => $value){
				$setStr .= "`" . $name . "`='" . $value . "',";
			}
			$setStr = substr($setStr, 0, -1);
			$sql = "UPDATE `" . $this->config['dbprefix'] . $table . "` SET $setStr WHERE " . $while;
			if(!$this->query($sql)){
				$this->debug("更新数据失败");
				return false;
			}
			return true;
		}

		/**
		 *    删除数据库信息
		 *
		 * @param    string    $table    数据库表名
		 * @param    string    $where    循环条件，SQL语句WHERE之后的内容
		 * @access    public
		 * @retrun    bool
		 */
		public function delete($table, $where){
			$sql = "DELETE FROM `" . $this->config['dbprefix'] . $table . "` WHERE " . $where;
			return $this->query($sql);
		}

		/**
		 *    插入数据库信息，并且转义要插入的数据
		 *
		 * @param    string    $table    数据库表名
		 * @param    array     $v        插入的内容数组,对应字段及内容
		 * @access    public
		 * @retrun    bool
		 */
		public function insert($table, $v){
			$sql_name = '';
			$sql_value = '';
			foreach($v as $name => $value){
				$sql_name .= "`" . SS($name) . "`,";
				$sql_value .= "\"" . SS($value) . "\",";
			}
			$sql_name = substr($sql_name, 0, -1);
			$sql_value = substr($sql_value, 0, -1);
			$sql = "INSERT INTO `" . $this->config['dbprefix'] . $table . "` ($sql_name) VALUES ($sql_value)";
			if($this->query($sql))
				return true;
			else{
				$this->debug("插入数据失败");
				return false;
			}
		}

		/**
		 *    调试输出工具
		 *
		 * @param    string    $title    错误信息标题
		 * @param    array     $close    是否结束程序执行，默认否
		 * @access    private
		 * @retrun    null
		 */
		private function debug($title = '', $close = false){
			if(!$this->send_head) //该参数限定不会重复发送错误信息头文件
			{
				global $config;
				header("content-Type: text/html; charset=" . $config['char_set']);
				$this->send_head = true;
			}
			if($this->config['db_debug'])
				echo "<div id=\"database_error\">", $title ? "<h2>$title</h2>" : "", "<p>", mysql_error(), "</p></div>";
			if($close)
				exit;
		}

		/**
		 *    输出数据库出错信息
		 *
		 * @acess    public
		 * @return string
		 */
		public function out_error(){
			return mysql_error();
		}

		/**
		 *    将SQL语句执行后的内容转换为二维数组
		 *
		 * @param    string    $sql    数据库语句
		 * @access    private
		 * @retrun    array[][]
		 */
		private function sql_to_arr($sql){
			$return = array();
			$result = $this->query($sql);
			if(!$result)
				$this->debug("数据选择失败", true);
			while($row = mysqli_fetch_assoc($result))
				array_push($return, $row);
			mysqli_free_result($result);
			return $return;
		}

		/**
		 *    统计数据的条数
		 *
		 * @param    string    $table    数据库表名
		 * @param    string    $where    循环条件，SQL语句WHERE之后的内
		 * @access    public
		 * @retrun    integer
		 */
		public function count_sql($table, $while = '1'){
			$sql = "select count(*) from `" . $this->config['dbprefix'] . $table . "` WHERE " . $while;
			$result = $this->query($sql);
			$row = mysqli_fetch_array($result);
			return $row[0];
		}

		/**
		 *    判断该数据表是否存在
		 *
		 * @param    string    $table
		 * @access    public
		 * @return    bool
		 */
		public function check_table($table){
			$sql = "SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_NAME='" . $this->config['dbprefix'] . "$table' AND TABLE_SCHEMA='" . $this->config['database'] . "'";
			$rt = $this->sql_to_arr($sql);
			return $rt[0]['count'] > 0;
		}

		/**
		 *    使用count_sql方法判断数据是否存在
		 *
		 * @param    string    $table    数据库表名
		 * @param    string    $where    循环条件，SQL语句WHERE之后的内
		 * @access    public
		 * @retrun    bool
		 */
		public function sql_check($table, $while){
			//存在返回 true
			$num = $this->count_sql($table, $while);
			return $num > 0;
		}

		/**
		 *    获取一个日期归档
		 *
		 * @param    string    $table     表名
		 * @param    string    $filed     日期字段名
		 * @param    string    $format    格式化信息
		 * @access    public
		 * @return array    包含date和count的数组
		 */
		public function get_date_archive($table, $filed, $format = '%Y%m'){
			$sql = "SELECT DATE_FORMAT(  `$filed` ,  '$format' ) AS  `date` , COUNT( * ) AS `count` FROM  `" . $this->config['dbprefix'] . $table . "` GROUP BY DATE_FORMAT(  `$filed` ,  '$format' ) ";
			return $this->sql_to_arr($sql);
		}
	}

?>

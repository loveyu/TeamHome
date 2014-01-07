<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 链接类
	 * 包含导航链接和友情链接等全部链接
	 * @file class/link.php
	 * @author  胡志宇
	 * @version 1.0
	 */

	class Link{
		/**
		 * 全部链接信息
		 */
		private $all_link;

		/**
		 * 获取到得链接分类信息
		 */
		private $category;

		/**
		 * 链接分类信息，摘要，id=>name
		 */
		private $category_simple;

		/**
		 * 数据库类操作引用
		 */
		private $db;

		/**
		 * 默认构造器
		 * @param null
		 * @access public
		 * @return null
		 */
		function __construct(){
			global $zxdb;
			$this->db = & $zxdb;
			$this->all_link = array();
			$this->category = array();
			$this->category_simple = array();
		}

		/**
		 * 获取某一个链接的信息
		 * @param null
		 * @access public
		 * @return array
		 */
		public function get_one_link($id){
			if(!is_numeric($id) || $id < 1)
				return array();
			if(isset($this->all_link[$id]))
				return $this->all_link[$id];
			$s = $this->db->select("link", "*", "`id`=" . $id);
			if(isset($s[0]))
				return $s[0];
			else return array();
		}

		/**
		 * 获取某一个去全部链接信息
		 * @param null
		 * @access public
		 * @return array
		 */
		public function get_all_link(){
			if(empty($this->all_link)){
				$s = $this->db->select("link");
				foreach($s as $v){
					$this->all_link[$v['id']] = $v;
				}
			}
			return $this->all_link;
		}

		/**
		 * 获取某一个分类得链接
		 * @param string $cat 链接分类名
		 * @access public
		 * @return array
		 */
		public function get_category_link($cat){
			if(!is_numeric($cat) || $cat < 0)
				return array();
			$r = array();
			if(!empty($this->all_link)){
				foreach($this->all_link as $v)
					if($v['category'] == $cat)
						$r[$v['id']] = $v;
			}
			else{
				$s = $this->db->select("link", "*", "category=" . SS($cat));
				foreach($s as $v)
					$r[$v['id']] = $v;
			}
			return $r;
		}

		/**
		 * 获取某一个分类信息
		 * @param integer $s 分类id，为空返回全部数组
		 * @access public
		 * @return string 为空返回数组
		 */
		public function get_category($s = ''){
			if(empty($this->category)){
				global $zxcat;
				$this->category = $zxcat->get_category('link');
				foreach($this->category as $v)
					$this->category_simple[$v['id']] = $v['name'];
			}
			if(isset($this->category_simple[$s]))
				return $this->category_simple[$s];
			return $this->category_simple;
		}

		/**
		 * 创建一个新的链接
		 * @param string $name        链接名称
		 * @param string $url         链接地址
		 * @param string $category    链接所属分类
		 * @param string $image       链接图像地址
		 * @param string $flag        链接标记，page_id[main]
		 * @param string $no          NO. 排序序号
		 * @param string $description 描述
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function new_link($name, $url, $category, $image, $flag, $no, $description = ''){
			if(!is_admin())
				return '非管理员操作';
			if(empty($name))
				return '名称不能为空';
			if(!is_url($url))
				return '这不是一个正确的URL';
			if(!in_array($category, array_flip($this->get_category())))
				return '该分组不存在';
			if(!empty($image))
				if(!is_url($image))
					return '如果填写图像地址，请正确填写';
			if(empty($flag))
				return '标记不能为空';
			if(!is_numeric($no))
				return '序号必须是数字';
			if(!$this->db->insert("link", array("name" => $name, 'url' => $url, 'image' => $image, 'category' => $category, 'description' => $description, 'no' => $no, 'flag' => $flag)))
				return '创建链接失败';
			return 'OK';
		}

		/**
		 * 编辑一个链接
		 * @param integer $id          编辑链接的id
		 * @param string  $name        链接名称
		 * @param string  $url         链接地址
		 * @param string  $category    链接所属分类
		 * @param string  $image       链接图像地址
		 * @param string  $flag        链接标记，page_id[main]
		 * @param string  $no          NO. 排序序号
		 * @param string  $description 描述
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function edit_link($id, $name, $url, $category, $image, $flag, $no, $description = ''){
			if(!is_admin())
				return '非管理员操作';
			if(!is_numeric($id) || $id < 1)
				return '该ID有误';
			if(empty($name))
				return '名称不能为空';
			if(!is_url($url))
				return '这不是一个正确的URL';
			if(!in_array($category, array_flip($this->get_category())))
				return '该分组不存在';
			if(!empty($image))
				if(!is_url($image))
					return '如果填写图像地址，请正确填写';
			if(empty($flag))
				return '标记不能为空';
			if(!is_numeric($no))
				return '序号必须是数字';
			if(!$this->db->sql_check("link", "`id`=" . $id))
				return '该链接不存在，请检查';
			if(!$this->db->update("link", array("name" => SS($name), 'url' => SS($url), 'image' => SS($image), 'category' => SS($category), 'description' => SS($description), 'no' => $no, 'flag' => SS($flag)), "`id`=" . $id))
				return '编辑链接失败';
			return 'OK';
		}

		/**
		 * 删除一个链接
		 * @param integer $id 链接ID
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function delete_link($id){
			if(!is_admin())
				return '非管理员操作';
			if(!is_numeric($id) || $id < 1)
				return 'ID有误';
			if(!$this->db->sql_check("link", "`id`=" . $id))
				return '该链接不存在，无法删除';
			if(!$this->db->delete("link", "`id`=" . $id))
				return '删除失败';
			return 'OK';
		}

		/**
		 * 更新一下系统设置，设置导航栏和友情链接的链接分类设置
		 * @param integer $nav    导航栏分类
		 * @param integer $friend 友情链接分类
		 * @access public
		 * @return string 错误信息,成功返回OK
		 */
		public function up_setting($nav, $friend){
			if(!is_admin())
				return '非管理员操作';
			$cat = $this->get_category();
			if(!in_array($nav, array_flip($cat)))
				return '该导航分类不存在';
			if(!in_array($friend, array_flip($cat)))
				return '该友情链接分类不存在';
			global $zxsys;
			if($zxsys->get_setting('nav_link') != $nav)
				if(!$zxsys->up_setting('nav_link', $nav))
					return '导航分类更新失败';
			if($zxsys->get_setting('friend_link') != $friend)
				if(!$zxsys->up_setting('friend_link', $friend))
					return '友情链接分类更新失败';
			return 'OK';
		}
	}

?>
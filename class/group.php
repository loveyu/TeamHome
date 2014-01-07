<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 用户分组类
	 * 用户所属的分组操作
	 * @file class/group.php
	 * @author  胡志宇
	 * @version 1.0
	 */

	class Group{
		/**
		 * 所有分组信息
		 */
		public $group;
		/**
		 * 分类父目录信息
		 */
		public $parent_info;
		/**
		 * 组长信息
		 */
		public $lader;
		/**
		 * 数据库类引用
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
			$this->group = array();
			$this->parent_info = array();
			$this->lader = array();
		}

		/**
		 * 获取所有的用户分组存入类变量，且返回
		 * @param null
		 * @access public
		 * @return array
		 */
		public function get_all_group(){
			if(!empty($this->group))
				return $this->group;
			$s = $this->db->select("group");
			foreach($s as $v){
				$this->group[$v['id']] = $v;
			}
			$this->make_parent_info();
			$this->get_group_lader();
			return $this->group;
		}

		/**
		 * 生成父类信息
		 * @param null
		 * @access private
		 * @return null
		 */
		private function make_parent_info(){
			foreach($this->group as $v)
				if($v['parent'] == 0)
					$this->parent_info[$v['id']] = array();
			foreach($this->group as $v)
				if($v['parent'] != 0)
					array_push($this->parent_info[$v['parent']], $v['id']);
		}

		/**
		 * 从数据库获取组长信息
		 * @param null
		 * @access private
		 * @return null
		 */
		private function get_group_lader(){
			$lader_id = array();
			foreach($this->group as $v)
				$lader_id[$v['lader']] = $v['lader'];
			$s = $this->db->select_where_in("user", "name,id,username", "id", $lader_id, true);
			foreach($s as $v){
				$this->lader[$v['id']] = $v;
			}
		}

		/**
		 * 获取某一分类的详细信息
		 * @param integer $id 分组ID
		 * @access public
		 * @return array
		 */
		public function get_one_group($id){
			if(!is_numeric($id))
				return array();
			$s = $this->db->select("group", "*", "`id`=" . $id);
			if(isset($s[0]))
				return $s[0];
			else return array();
		}

		/**
		 * 创建一个新的分组
		 * @param array $info 新的分组信息 name,parent,des,lader parent顶级为0
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function creat_new_group($info){
			if(!is_admin())
				return '必须为管理员账户操作';
			if(!isset($info['name']) || $info['name'] == '')
				return '分组名不能为空';
			if(!isset($info['parent']) || $info['parent'] < 0)
				$info['parent'] = 0;
			if(!isset($info['des']))
				$info['des'] = '';
			if(!isset($info['lader']) || !is_numeric($info['lader']) || $info['lader'] < 0)
				$info['lader'] = 0;
			if($info['lader'] != 0 && !$this->db->sql_check("user", "id='" . SS($info['lader']) . "'"))
				return '该组长不存在';
			if($this->db->sql_check("group", "name='" . SS($info['name']) . "'"))
				return '该分组名已存在';
			if($info['parent'] && !$this->db->sql_check("group", "parent=0 AND id=" . SS($info['parent'])))
				return '上级分组必须为顶级分组';
			if($this->db->insert("group", array('name' => $info['name'], 'parent' => $info['parent'], 'des' => $info['des'], 'lader' => $info['lader'])))
				return 'OK';
			else return '创建分组失败';
		}

		/**
		 * 编辑分组信息
		 * @param array $info 要编辑的分组信息 id,name,parent,des,lader
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function edit_group($info){
			if(!is_admin())
				return '必须为管理员账户操作';
			if(!isset($info['id']) || !is_numeric($info['id']))
				return '缺少必要的操作参数';
			if(!isset($info['name']) || $info['name'] == '')
				return '分组名不能为空';
			if(!isset($info['parent']) || $info['parent'] < 0)
				$info['parent'] = 0;
			if(!isset($info['des']))
				$info['des'] = '';
			if(!isset($info['lader']) || !is_numeric($info['lader']) || $info['lader'] < 0)
				$info['lader'] = 0;
			$s = $this->get_one_group($info['id']);
			if(empty($s))
				return '不存在该分组';
			$rt = array();
			foreach($info as $id => $v){
				if(isset($s[$id]) && $s[$id] != $v)
					$rt[SS($id)] = SS($v);
			}
			if(empty($rt))
				return '数据无改动，无法编辑';
			if($this->db->update("group", $rt, "`id`=" . $info['id']))
				return 'OK';
			else return '更新数据失败';
		}

		/**
		 * 删除某一用户组
		 * @param integer $id         要删除的分组ID
		 * @param integer $move_user  被删除分组的用户移动到何处
		 * @param integer $move_group 被删除分组的子分类移动到哪
		 * @access public
		 * @return string 错误信息，成功返回OK
		 */
		public function delete_group($id, $move_user, $move_group){
			if(!is_admin())
				return '必须为管理员账户操作';
			if(!is_numeric($id) || $id < 1)
				return '缺少必要的操作参数';
			if($id == $move_user || $id == $move_group)
				return '移动分组或移动用户分组必须与要删除分组不同';
			$s = $this->get_one_group($id);
			if(empty($s))
				return '删除的分组不存在';
			$user_group = array();
			$user_group['group2'] = 0;
			$group_move = array();
			if($s['parent'] == 0){
				if(!is_numeric($move_user) || $move_user < 1)
					return '用户移动分组表单有误';
				if(!is_numeric($move_group) || $move_group < 1)
					return '二级分组移动分组表单有误';
				$user_group['group1'] = SS($move_user);
				$group_move['parent'] = $move_group;
				$s2 = $this->get_one_group($move_user);
				if(empty($s2))
					return '移动的用户分组不存在';
				if($s2['parent'] != 0)
					return '移动的用户分组非顶级分组';
				unset($s2);
				$s2 = $this->get_one_group($move_group);
				if(empty($s2))
					return '二级分组移动的顶级分组不存在';
				if($s2['parent'] != 0)
					return '二级分组移动的顶级分组非顶级分组';
			}
			if(!empty($group_move))
				if(!$this->db->update("group", $group_move, "parent=" . SS($id)))
					return '移动二级分组的顶级分组错误';
			if($s['parent'] == 0){
				if(!$this->db->update("user", $user_group, "group1=" . SS($id)))
					return '移动用户的分组失败,顶级分组';
			}
			else{
				if(!$this->db->update("user", $user_group, "group2=" . SS($id)))
					return '移动用户分组失败';
			}
			if(!$this->db->delete("group", "`id`=" . SS($id)))
				return '删除分组失败';
			echo 'OK';
		}
	}

?>
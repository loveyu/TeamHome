<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 新闻及链接分类操作类
 * 在涉及到分类时进行调用
 * @file class/categoey.php
 * @author  胡志宇
 * @version 1.0
 */
class Category
{
    /**
     * 全部分类
     */
    private $all_cat;
    private $is_all;

    /**
     * 数据库操作引用
     */
    private $db;

    /**
     * 所选取的部分分类
     */
    private $cat;

    /**
     * 默认构造器
     * @param  null
     * @access public
     * @return null
     */
    function __construct()
    {
        global $zxdb;
        $this->db = &$zxdb;
        $this->is_all = false;
        $this->cat = [];
    }

    /**
     * 获取某一分类的分类列表
     * @param  string  $type  分类类型
     * @access public
     * @return array
     */
    public function get_category($type = '')
    {
        if (empty($type)) {
            return $this->get_all_category();
        }
        if (!isset($this->cat[$type])) {
            if (empty($this->all_cat) || !$this->is_all) {
                $s = $this->db->select("category", "*", "type='".SS($type)."'");
                foreach ($s as $v) {
                    $this->cat[$type][$v['id']] = $v;
                }
            } else {
                foreach ($this->all_cat as $v) {
                    if ($v['type'] == $type) {
                        $this->cat[$type][$v['id']] = $v;
                    }
                }
            }
        }

        return isset($this->cat[$type]) ? $this->cat[$type] : null;
    }

    /**
     * 根据ID获取一个分类的详细信息
     *
     * @param  integer  $id  分类ID
     * @access public
     * @return array
     */
    public function get_one_category($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return [];
        }
        if (isset($this->all_cat[$id])) {
            return $this->all_cat[$id];
        }
        $s = $this->db->select("category", "*", "`id`=".$id);
        if (isset($s[0])) {
            $this->all_cat[$s[0]['id']] = $s[0];

            return $s[0];
        } else {
            return [];
        }
    }

    public function get_category_list($ids)
    {
        if (is_array($ids)) {
            $s = $this->db->select("category", "*", "`id` in (".implode(", ", $ids).")");
            if (isset($s[0])) {
                foreach ($s as $v) {
                    $this->all_cat[$v['id']] = $v;
                }
            }
        }

        return $this->all_cat;
    }

    /**
     * 获取所有的分类信息
     * @param  null
     * @access public
     * @return array
     */
    public function get_all_category()
    {
        $this->is_all = true;
        $s = $this->db->select('category');
        foreach ($s as $v) {
            $this->all_cat[$v['id']] = $v;
        }

        return $this->all_cat;
    }

    /**
     * 新建一个分类名
     * @param  string  $name  名称
     * @param  string  $alias  别名 英文和数字
     * @param  string  $type  分类的类型
     * @param  string  $description  该分类的描述
     * @access public
     * @return string 错误信息，成功返回OK
     */
    public function new_category($name, $alias, $type, $description)
    {
        if (!is_admin()) {
            return '非管理员权限';
        }
        if (empty($name)) {
            return '分类名不能为空';
        }
        if (empty($alias)) {
            return '别名不能为空';
        }
        if (!$this->check_alias($alias)) {
            return '别名格式有误';
        }
        if (!in_array($type, array_flip($this->get_allow_type()))) {
            return '不允许创建该种类的分类';
        }
        if ($this->db->sql_check("category",
            "((`name`='".SS($name)."' OR `alias`='".SS($alias)."') AND `type`='".SS($type)."')")) {
            return '该分类名或者别名已存在';
        }
        if (!$this->db->insert("category",
            ['name' => $name, 'alias' => $alias, 'type' => $type, 'description' => $description])) {
            return '创建分类失败';
        }

        return 'OK';
    }

    /**
     * 编辑分类
     * @param  integer  $id  分类ID
     * @param  string  $name  名称
     * @param  string  $alias  别名 英文和数字
     * @param  string  $type  分类的类型
     * @param  string  $description  该分类的描述
     * @access public
     * @return string 错误信息，成功返回OK
     */
    public function edit_category($id, $name, $alias, $description)
    {
        if (!is_admin()) {
            return '非管理员权限';
        }
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        if (empty($name)) {
            return '分类名不能为空';
        }
        if (empty($alias)) {
            return '别名不能为空';
        }
        if (!$this->check_alias($alias)) {
            return '别名格式有误';
        }
        $s = $this->db->select_once('category', 'id,name,alias,type', "`id`=".$id);
        if (!$s || !isset($s->id) || $s->id != $id) {
            return '该ID不存在分类';
        }
        if ($this->db->sql_check("category",
            "((`name`='".SS($name)."' OR `alias`='".SS($alias)."') AND `type`='".SS($s->type)."' AND `id`<>$id)")) {
            return '该分类名或者别名已存在';
        }
        if (!$this->db->update("category",
            ['name' => SS($name), 'alias' => SS($alias), 'description' => SS($description)], "`id`=".$id)) {
            return '更新分类失败';
        }

        return 'OK';
    }

    /**
     * 获取允许的分类类型
     * @param  string  $t  分类类型
     * @access public
     * @return string $t不存在返回全部，存在返回相应的值
     */
    public function get_allow_type($t = '')
    {
        //英文引索同时为数据表名
        $s = ['news' => '新闻', 'link' => '链接'];
        if (isset($s[$t])) {
            return $s[$t];
        }

        return $s;
    }

    /**
     * 删除一个分类
     * @param  integer  $id  要删除的分类ID
     * @param  interger  $move  要移动到得分类
     * @access public
     * @return string 错误信息，成功返回OK
     */
    public function delete_category($id, $move)
    {
        if (!is_admin()) {
            return '非管理员权限';
        }
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        if (!is_numeric($move) || $move < 1 || $id == $move) {
            return '要移动的分类ID有误';
        }
        $id_info = $this->get_one_category($id);
        if (!isset($id_info['id']) || $id_info['id'] != $id) {
            return '要删除的分类ID不存在';
        }
        $move_info = $this->get_one_category($move);
        if (!isset($move_info['id']) || $move_info['id'] != $move) {
            return '要移动的分类ID不存在';
        }
        if ($move_info['type'] != $id_info['type']) {
            return '两分类类型不一致';
        }
        if (!$this->db->update($id_info['type'], ['category' => SS($move)], "category=".$id)) {
            return '移动分组失败';
        }
        if (!$this->db->delete("category", "`id`=".$id)) {
            return '删除数据出现错误';
        }

        return 'OK';
    }

    /**
     * 检查别名是否正确
     * @param  string  $s  别名，只允许字母，数字
     * @access private
     * @return bool
     */
    private function check_alias($s)
    {
        if (strlen($s) < 1) {
            return false;
        }
        for ($i = 0; $i < strlen($s); $i++) {
            $l = ord($s{$i});
            if (!(($l > 47 && $l < 58) || ($l > 64 && $l < 91) || ($l > 96 && $l < 123))) {
                return false;
            }
        }

        return true;
    }
}

?>
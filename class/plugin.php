<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 *  插件操作类
 *  系统插件操作类及加载类，在系统最初加载
 * @file    class/plugin.php
 * @author     胡志宇
 * @version    1.0
 */
class Plugin
{
    /**
     * @var array
     */
    private $call_list;
    /**
     * @var array
     */
    private $plugin_list;

    /**
     * 构造方法，对插件进行初始化
     */
    function __construct()
    {
        $this->call_list = [];
        foreach ($this->call_type() as $v) {
            $this->call_list[$v] = [];
        }
        $this->plugin_list = [];
        foreach ($this->plugin_type() as $v) {
            $this->plugin_list[$v] = [];
        }
        $this->get_plugin_list();
    }

    /**
     * @param  string  $type  加载一个插件
     */
    public function load_plugin($type)
    {
        if (isset($this->plugin_list[$type])) {
            foreach ($this->plugin_list[$type] as $name) {
                $this->load($name);
            }
        }
    }

    /**
     * 获取插件列表
     */
    private function get_plugin_list()
    {
        global $zxdb;
        $db = $zxdb->select("plugin");
        foreach ($db as $v) {
            $this->add_plugin($v['name'], $v['type']);
        }
    }

    /**
     * @param  string  $name  插件名称
     * @param  string  $type  插件启动类型
     */
    public function add_plugin($name, $type)
    {
        if (isset($this->plugin_list[$type])) {
            array_push($this->plugin_list[$type], $name);
        }
    }

    /**
     * 增加会掉函数
     * @param  callback  $name  函数名
     * @param  string  $type  钩子名
     */
    public function add_call($name, $type)
    {
        if (in_array($type, $this->call_type())) {
            array_push($this->call_list[$type], $name);
        }
    }

    /**
     * 回调某一函数
     * @param  string  $type  钩子名
     */
    public function call($type)
    {
        if (in_array($type, $this->call_type())) {
            foreach ($this->call_list[$type] as $v) {
                $v();
            }
        }
    }

    /**
     * 检测插件页面
     * @param  string  $name  插件名
     * @param  string  $page  插件页面
     * @return bool
     */
    public function check_plugin_page($name, $page)
    {
        global $zxdb;
        if (!$zxdb->sql_check("plugin", "name='".SS($name)."'")) {
            return false;
        }
        if (!is_file(PLUGIN_PATH."$name/$page.php")) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return array
     */
    public function call_type()
    {
        return [
            'init', 'admin_init', 'admin_header', 'admin_footer', 'admin_body_header', 'admin_body_footer',
            'admin_index_body', 'page_init', 'page_header', 'page_footer', 'logout', 'login', 'post_login',
            'auto_login', 'register', 'register_ok',
        ];
    }

    /**
     * @return array
     */
    public function plugin_type()
    {
        return ['init', 'page', 'admin'];
    }

    /**
     * @param $name
     * @return bool
     */
    private function load($name)
    {
        if (!$this->check_plugin($name)) {
            return $this->add_error_log($name);
        }
        require_once(PLUGIN_PATH.$name."/function.php");
    }

    /**
     * @param $name
     * @return bool
     */
    private function check_plugin($name)
    {
        if (!is_file(PLUGIN_PATH.$name."/function.php")) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    private function add_error_log($name)
    {
        //错误记录
        return false;
    }

    /**
     * @return array
     */
    public function m_get_file_list()
    {
        $handle = opendir(PLUGIN_PATH); //打开指定文件夹
        $rt = [];
        if (!$handle) {
            return $rt;
        }
        while ($file = readdir($handle)) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (is_dir(PLUGIN_PATH.$file)) {
                $rt[] = $file;
            }
        }
        closedir($handle); //关闭打开的文件夹

        return $rt;
    }

    /**
     * @return array
     */
    public function m_get_sql_list()
    {
        $rt = [];
        foreach ($this->plugin_list as $name => $value) {
            foreach ($value as $v) {
                $rt[$v] = $name;
            }
        }

        return $rt;
    }

    /**
     * @param $name
     * @param $type
     * @return string
     */
    public function m_active($name, $type)
    {
        global $zxdb;
        if (!in_array($type, $this->plugin_type())) {
            return "未知插件类型";
        }
        if ($zxdb->sql_check("plugin", "name='".SS($name)."'")) {
            return "插件已激活";
        }
        $this->load($name);
        if (function_exists("plugin_$name"."_active")) {
            $f = "plugin_$name"."_active";
            if (!$f()) {
                return '插件激活函数执行失败';
            }
        }
        if ($zxdb->insert("plugin", ["name" => $name, 'type' => $type])) {
            return "OK";
        }

        return "插件无法成功激活";
    }

    /**
     * @param $name
     * @return string
     */
    public function m_stop($name)
    {
        global $zxdb;
        if (!$zxdb->sql_check("plugin", "name='".SS($name)."'")) {
            return "插件不存在，无法停用";
        }
        if ($zxdb->delete("plugin", "name='".SS($name)."'")) {
            if (function_exists("plugin_$name"."_stop")) {
                $f = "plugin_$name"."_stop";
                $f();
            }

            return "OK";
        }

        return "插件无法删除";
    }

    /**
     * @param $name
     * @return string
     */
    public function m_delete($name)
    {
        global $zxdb;
        if ($zxdb->sql_check("plugin", "name='".SS($name)."'")) {
            if (!$zxdb->delete("plugin", "name='".SS($name)."'")) {
                return "插件无法删除";
            }
        }
        load('file_action');
        $file = new File_action();
        if (function_exists("plugin_$name"."_delete")) {
            $f = "plugin_$name"."_delete";
            $f();
        }

        return $file->delete_path(PLUGIN_PATH.$name);
    }

    /**
     * @param $array
     */
    public function clear_invalid_plugin($array)
    {
        $list = [];
        foreach ($array as $name => $value) {
            $list[] = '"'.SS($name).'"';
        }
        $list = implode($list, ",");
        get_db()->delete("plugin", "name in ( $list )");
    }
}

?>
<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 系统操作类
 * 该类将在最初进行加载
 * @file class/system.php
 * @author  胡志宇
 * @version 1.0
 */
class System
{

    /**
     * 数据库操作类引用
     */
    private $db;

    /**
     * 从数据库获取的设置
     */
    public $setting;

    /**
     * 获取的样式列表
     */
    public $style_list;


    /**
     * 程序添加的变量
     **/
    public $varl;

    /**
     * 默认构造器
     */
    function __construct()
    {
        global $zxdb;
        $this->db = &$zxdb;
        $this->setting = [];
        $this->style_list = [];
        $this->varl = [];
        $this->get_setting();
    }

    /**
     * 获取自动加载的系统设置
     * @param  string  $r  存在该设置时返回值，不存在返回空
     * @access public
     * @return string
     */
    public function get_setting($r = '')
    {
        if (empty($this->setting)) {
            $s = $this->db->select("setting", "id,name,value", "`load`=1");
            foreach ($s as $v) {
                $this->setting[$v['name']] = $v['value'];
            }
        }
        if (empty($r)) {
            return '';
        }
        if (isset($this->setting[$r])) {
            return $this->setting[$r];
        } else {
            return $this->get_other_config_setting($r);
        }
    }

    /**
     * 获取系统设置的值，并添加到系统设置中，非自动加载
     * @param  string  $name
     * @access private
     * @return string
     */
    private function get_other_config_setting($name)
    {
        $rt = $this->db->select("setting", "*", "`name` = '".SS($name)."'");
        if (!count($rt)) {
            return '';
        }
        $this->setting[$rt[0]['name']] = $rt[0]['value'];

        return $rt[0]['value'];
    }

    /**
     * 获取样式列表到变量
     * @param  null
     * @access public
     * @return array
     */
    public function get_style_list()
    {
        //根据文件目录判断样式列表
        if (empty($this->style_list)) {
            //加载文件操作函数，并读取文件夹列表
            load('file_action');
            $file = new File_action();
            $this->style_list = $file->get_style_list();
        }

        return $this->style_list;
    }

    /**
     * 更新设置页面提交的系统设置
     * @param  string  $site_name  网站名称
     * @param  string  $site_url  网站地址，http://www.example.com/ 斜杠结尾
     * @param  string  $site_register  是否开启站点注册 0,1
     * @param  string  $site_style  样式列表，存在的文件夹
     * @param  string  $admin_email  admin@example.com
     * @param  integer  $user_manage_one_page_number  用户管理中心的每页显示数量
     * @param  integer  $news_one_page_number  新闻每页数量
     * @param  integer  $site_cookie_time  Cookie有效期
     * @param  string  $site_key  加密字符串
     * @access public
     * @return string 返回错误信息，成功返回 OK
     */
    public function up_more_setting(
        $site_name,
        $site_description,
        $site_url,
        $site_register,
        $site_style,
        $admin_email,
        $user_manage_one_page_number,
        $news_one_page_number,
        $site_cookie_time,
        $site_key
    ) {
        if (!$this->is_super_admin()) {
            return '需要使用超级管理员';
        }
        if (empty($site_name)) {
            return '站点名称不能为空';
        }
        if (!is_url($site_url) || substr($site_url, -1, 1) != '/') {
            return '请填写正确的网址,并以斜杠结尾';
        }
        if (!in_array($site_register, [0, 1])) {
            return '开启注册项值不正确';
        }
        if (!is_dir(ROOT_PATH.'style/'.$site_style)) {
            return '该网址样式不存在，请核对';
        }
        if (!is_mail($admin_email)) {
            return '管理员邮箱有误';
        }
        if (!is_numeric($user_manage_one_page_number) || $user_manage_one_page_number < 1) {
            return '用户中心每页显示数量有误';
        }
        if (!is_numeric($news_one_page_number) || $news_one_page_number < 1) {
            return '新闻每页显示数量有误';
        }
        if (!is_numeric($site_cookie_time) || $site_cookie_time < 0) {
            return 'cookies 时间有误';
        }
        if (empty($site_key)) {
            return '加密字符串不能为空';
        }
        $up_arr =
            [
                'site_name'                   => $site_name, 'site_description' => TT($site_description),
                'site_url'                    => $site_url, 'site_register' => $site_register,
                'site_style'                  => $site_style, 'admin_email' => $admin_email,
                'user_manage_one_page_number' => $user_manage_one_page_number,
                'news_one_page_number'        => $news_one_page_number, 'site_cookie_time' => $site_cookie_time,
                'site_key'                    => $site_key,
            ];
        $up_arr = $this->check_up_setting($up_arr);
        if (empty($up_arr)) {
            return '数据无改动';
        }
        foreach ($up_arr as $name => $value) {
            if (!$this->up_setting($name, $value)) {
                return "更新$name as $value 失败";
            }
        }

        return 'OK';
    }

    /**
     * 更新邮箱设置选项
     * @param  string  $useragent  邮件发送显示UA，邮件头标示
     * @param  string  $type  发信类型，smpt,mail
     * @param  string  $smtp_host  SMPT地址
     * @param  string  $smtp_port  SMPT端口
     * @param  string  $smtp_user  SMTP用户名
     * @param  string  $smtp_pass  SMTP用户密码
     * @param  string  $smtp_auth  SMTP开启验证 true,false
     * @param  string  $smtp_debug  SMTP调试 1,0
     * @param  string  $from_email  发信人地址
     * @param  string  $from_name  发信人姓名
     * @access public
     * @return string 返回错误信息，成功返回 OK
     */
    public function up_mail_setting(
        $useragent,
        $type,
        $smtp_host,
        $smtp_port,
        $smtp_user,
        $smtp_pass,
        $smtp_auth,
        $smtp_debug,
        $from_email,
        $from_name
    ) {
        if (!$this->is_super_admin()) {
            return '需要使用超级管理员';
        }
        load('mail');
        $mail = new Mail();
        $mail_config_list = $mail->get_config_list();
        if (empty($useragent)) {
            return '邮件UA不能为空';
        }
        if (!in_array($type, ['mail', 'smtp'])) {
            return '邮件类型有误';
        }
        if (empty($smtp_host)) {
            return 'SMTP地址不能为空';
        }
        if (!is_numeric($smtp_port)) {
            return '端口必须为数字';
        }
        if (empty($smtp_user)) {
            return '用户不能为空';
        }
        if (empty($smtp_pass)) {
            return '密码不能为空';
        }
        if ($smtp_pass == '******') {
            $smtp_pass = $mail_config_list['smtp_pass'];
        }
        if (!in_array($smtp_auth, ['true', 'false'])) {
            return '开启验证有误';
        }
        if (!in_array($smtp_debug, ['1', '0'])) {
            return '开启调试信息选项有误';
        }
        if (!is_mail($from_email)) {
            return '必须填写一个正确的邮箱';
        }
        if (empty($from_name)) {
            return '填写正确的邮箱名称呼';
        }
        $s =
            "useragent:$useragent;type:$type;smtp_host:$smtp_host;smtp_port:$smtp_port;smtp_user:$smtp_user;smtp_pass:$smtp_pass;smtp_auth:$smtp_auth;smtp_debug:$smtp_debug;from_email:$from_email;from_name:$from_name";
        if (!$this->up_setting('mail_config', $s)) {
            return '更新数据失败';
        }

        return 'OK';
    }

    /**
     * 增加一个系统设置
     * @param  string  $name
     * @param  string  $value
     * @param  int  $auto  0,1
     * @access public
     * @return bool
     */
    public function add_setting($name, $value, $auto)
    {
        if ($auto != 1) {
            $auto = '0';
        }
        if ($this->db->insert("setting", ["name" => $name, "value" => $value, "load" => $auto])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新项系统设置
     * @param $name  字段名称
     * @param $value 更新字段的值
     * @access public
     * @return bool
     */
    public function up_setting($name, $value, $auto = -1)
    {
        if ($auto != -1) {
            if (!$this->db->update("setting", ['value' => SS($value), 'load' => SS($auto)], "name='".SS($name)."'")) {
                return false;
            } else {
                return true;
            }
        }
        if (!$this->db->update("setting", ['value' => SS($value)], "`name`='".SS($name)."'")) {
            return false;
        }

        return true;
    }

    /**
     * 检测要更新的数据，返回需要更新的项
     * @param  array  $new  新数据数组
     * @access public
     * @return array 没有更新数据时为空
     */
    private function check_up_setting($new)
    {
        //检测要更新的数据，返回要更新的数据
        $r = [];
        foreach ($new as $id => $v) {
            if (isset($this->setting[$id]) && $this->setting[$id] != $v) {
                $r[$id] = $v;
            }
        }

        return $r;
    }

    /**
     * 判断更新数据权限，判断是否为超级管理员
     * @param  null
     * @access public
     * @return bool
     */
    public function is_super_admin()
    {
        //判断是否为超级管理员
        if (!is_admin()) {
            return false;
        }

        return get_cookies('super_admin') == $this->get_setting('super_password');
    }

    /**
     * 跳转到超级管理员验证页面，程序终止
     * @param  null
     * @access public
     * @return null
     */
    public function go_super_verify()
    {
        //跳转到超级管理员验证页面
        redirect(site_url().'user/setting_super.php?redirect='.urlencode(now_url()), 'refresh');
    }

    /**
     * 通过密码验证是否为超级管理员
     * @param  string  $pwd  非MD5密码值
     * @access public
     * @return string 返回错误信息，成功返回OK
     */
    public function super_admin_verify($pwd)
    {
        //验证超级管理员
        $post_pwd = make_password(md5($pwd));
        if ($post_pwd == $this->get_setting('super_password')) {
            set_cookies("super_admin", $post_pwd);

            return 'OK';
        } else {
            return '密码错误';
        }
    }

    /**
     * 获取其他的系统设置
     * @param  array  $arr  字段数组
     * @access public
     * @return array 返回数组
     */
    public function get_other_setting($arr)
    {
        foreach ($arr as $id => $v) {
            $arr[$id] = "`name`='".SS($v)."'";
        }
        $s = $this->db->select("setting", "*", implode(' OR ', $arr));
        $rt = [];
        foreach ($s as $v) {
            $rt[$v['name']] = $v['value'];
        }

        return $rt;
    }

    /**
     * 编辑网站关于信息
     * @param  string  $team_about  关于信息
     * @param  string  $team_proclamation  公告信息
     * @access public
     * @return string 错误信息
     */
    public function edit_about_info($team_about, $team_proclamation)
    {
        $s = $this->get_other_setting(['team_about', 'team_proclamation']);
        $flag = false;
        if ($s['team_about'] != $team_about) {
            $flag = true;
            if (!$this->up_setting('team_about', $team_about)) {
                return '更新关于信息失败';
            }
        }
        if ($s['team_proclamation'] != $team_proclamation) {
            $flag = true;
            if (!$this->up_setting('team_proclamation', $team_proclamation)) {
                return '更新公告信息失败';
            }
        }
        if (!$flag) {
            return '数据无修改';
        }

        return 'OK';
    }

    /**
     * 增加一个系统变量
     * @param  string  $name  定义的字符串
     * @param  object  $value  变量的值
     * @acess public
     * @return boolean
     **/
    public function add_system_val($name, &$value)
    {
        if (isset($this->varl[$name])) {
            return false;
        } else {
            $this->varl[$name] = $value;

            return true;
        }
    }
}

?>
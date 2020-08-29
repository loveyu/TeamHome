<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 用户注册类
 * @file class/register.php
 * @author  胡志宇
 * @version 1.0
 */
class Register
{

    /**
     * 临时存储的明文密码，随机生成
     */
    private $pwaasord;

    /**
     * 临时存储的邮箱账户
     */
    private $email;

    /**
     * 默认构造器
     * @param  null
     * @access public
     * @return null
     */
    public function __construct()
    {
        load('mail');
    }

    /**
     * 注册操作
     * @param  string  $email  邮箱地址
     * @access public
     * @return string 错误信息，成功返回 OK
     */
    public function register($email)
    {
        global $zxsys;
        if (!$zxsys->get_setting("site_register")) {
            return "注册已关闭";
        }
        global $zxdb;
        run_plugin('register');
        $this->email = strtolower($email);
        $st = '';
        $st = $this->check_email();
        if ($st != 'OK') {
            return $st;
        }
        $this->pwaasord = rand_new_key(10);
        $st = $zxdb->insert("user", ['user' => $email, 'password' => make_password(md5($this->pwaasord))]);
        if (!$st) {
            return "注册失败";
        }
        if ($this->send_mail()) {
            $this->send_admin_email(true);
            run_plugin('register_ok');

            return 'OK';
        } else {
            $this->send_admin_email(false);

            return '注册失败，且用户已生成，请联系管理员';
        }
    }

    /**
     * 管理员操作添加一个账号
     * @param  string  $email  邮箱地址
     * @access public
     * @return string 错误信息，成功返回 OK
     */
    public function admin_register($email)
    {
        if (!is_admin()) {
            return '非管理员操作';
        }
        run_plugin('register');
        $this->email = $email;
        $st = $this->check_email();
        if ($st != 'OK') {
            return $st;
        }
        $this->pwaasord = rand_new_key(10);
        global $zxdb;
        $st = $zxdb->insert("user", ['user' => $email, 'password' => make_password(md5($this->pwaasord))]);
        if (!$st) {
            return "注册失败";
        }
        if (!$this->send_mail()) {
            return '无法发送密码到用户邮箱';
        }
        run_plugin('register_ok');

        return 'OK';
    }

    /**
     * 检测邮箱地址
     * @param  string  $email  邮箱地址
     * @access private
     * @return string 错误信息，成功返回 OK
     */
    private function check_email()
    {
        if (!is_mail($this->email)) {
            return '邮箱有误';
        }
        global $zxdb;
        if (!$zxdb->sql_check("user", "`user`=\"".SS($this->email)."\"")) {
            return 'OK';
        } else {
            return '用户已存在';
        }
    }

    /**
     * 发送注册信息，包含密码原文
     * @param  string  $email  邮箱地址
     * @access public
     * @return bool
     */
    private function send_mail()
    {
        $mail = new Mail();
        $mail->add($this->email);
        $mail->title("你的网站密码 - ".site_title());
        $mail->html("密码为:&nbsp;&nbsp;".$this->pwaasord."<br />请及时修改密码！<br /><a href=\"".site_url()."\">".site_url()."</a>");

        return $mail->send();
    }

    /**
     * 发送注册成功管理员提醒
     * @param  string  $email  邮箱地址
     * @access public
     * @return bool
     */
    private function send_admin_email($type = true)
    {
        global $zxsys;
        $admin = $zxsys->get_setting("admin_email");
        if (!is_mail($admin)) {
            return false;
        }
        $mail = new Mail();
        $mail->add($admin);
        $mail->title("新用户注册 - ".site_title());
        $mail->html("邮箱:&nbsp;&nbsp;".$this->email."<br />".($type ? "注册成功" : "注册失败"));

        return $mail->send();
    }
}

?>
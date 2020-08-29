<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}
add_plugin("PCount_add_admin_menu", "admin_init");
add_plugin('PCount_count_user_action', 'auto_login');
add_plugin('PCount_post_login', 'post_login');
add_plugin("PCount_logout", 'logout');
function PCount_count_user_action()
{
    if (get_cookies("login_view")) {
        get_db()->insert("count", [
            'url' => now_url(), 'user' => get_user_id(), 'from' => $_SERVER['HTTP_REFERER'], 'ip' => get_ip(),
            'ua'  => $_SERVER['HTTP_USER_AGENT'],
        ]);
    } else {
        set_cookies("login_view", 1);
        get_db()->insert("count", [
            'url' => now_url(), 'user' => get_user_id(), 'from' => $_SERVER['HTTP_REFERER'], 'ip' => get_ip(),
            'ua'  => $_SERVER['HTTP_USER_AGENT'], 'type' => 'auto_login',
        ]);
    }
}

function PCount_post_login()
{
    get_db()->insert("count", [
        'url' => now_url(), 'user' => get_user_id(), 'from' => $_SERVER['HTTP_REFERER'], 'ip' => get_ip(),
        'ua'  => $_SERVER['HTTP_USER_AGENT'], 'type' => 'post_login',
    ]);
    set_cookies("login_view", 1);
}

function PCount_logout()
{
    get_db()->insert("count", [
        'url' => now_url(), 'user' => get_user_id(), 'from' => $_SERVER['HTTP_REFERER'], 'ip' => get_ip(),
        'ua'  => $_SERVER['HTTP_USER_AGENT'], 'type' => 'logout',
    ]);
    delete_cookie("login_view");
}

function plugin_count_active()
{
    $db = get_db();
    global $zxsys;
    echo "<p>插件激活中......</p>";
    echo "<p>检测要创建的数据库.......</p>";
    if ($db->check_table("count")) {
        if ($zxsys->get_setting("PCount") == "count") {
            echo "<p>插件将再次被激活，数据表已存在</p>";

            return true;
        }
        echo "<p class=\"error\">该数据库已存在，无法激活插件</p>";

        return false;
    }
    $sql = "CREATE TABLE IF NOT EXISTS ".$db->get_config('dbprefix')."count (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL,
  `ua` varchar(256) NOT NULL,
  `from` varchar(256) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(10) NOT NULL DEFAULT 'view',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
    if ($db->query($sql)) {
        echo "<p>创建数据表 '".$db->get_config('dbprefix')."count' 成功</p>";
        if ($zxsys->add_setting("PCount", "count", '1')) {
            echo "<p>更新系统设置成功</p>";
        } else {
            echo "<p class=\"error\">无法更新系统设置</p>";

            return false;
        }

        return true;
    } else {
        echo "<p class=\"error\">创建数据表 '".$db->get_config('dbprefix')."count' 失败</p>";
        echo "<p class=\"error\">错误信息：".$db->out_error()."</p>";

        return false;
    }
}

function plugin_count_stop()
{
    echo "<p>插件停用中....</p>";
}

function plugin_count_delete()
{
    echo "<p>插件删除中......</p>";
}

function PCount_add_admin_menu()
{
    global $zxtmp;
    $zxtmp->add_admin_menu(
        'count',
        ['url' => 'plugin_page.php?m=count&id=count&p=page', 'name' => '统计中心', 'power' => [0], 'id' => 'count'],
        [
            ['url' => 'plugin_page.php?m=count&id=count&p=all', 'name' => '全部数据', 'id' => 'count_all', 'power' => [0]],
        ]
    );
}

function PCount_get_serach_select()
{
    $where = [];
    $where_sql = "";
    $tmp = '';
    if (GET('user')) {
        $tmp = explode(',', GET('user'));
        foreach ($tmp as $n => $v) {
            if (!is_numeric($v)) {
                unset($tmp[$n]);
            }
        }
        array_push($where, "( user in (".implode($tmp, ",").") )");
    }
    if (GET('page')) {
        $tmp = explode(',', GET('page'));
        foreach ($tmp as $n => $v) {
            $tmp[$n] = " ( url like '%$v%' ) ";
        }
        array_push($where, "(".implode($tmp, " OR ").")");
    }
    if (GET('time')) {
        $tmp = explode(',', GET('time'));
        foreach ($tmp as $n => $v) {
            $tmp[$n] = " ( time like '%$v%' ) ";
        }
        array_push($where, "(".implode($tmp, " OR ").")");
    }
    if (GET('ua')) {
        $tmp = explode(',', GET('ua'));
        foreach ($tmp as $n => $v) {
            $tmp[$n] = " ( ua like '%$v%' ) ";
        }
        array_push($where, "(".implode($tmp, " OR ").")");
    }
    if (GET('ip')) {
        $tmp = explode(',', GET('ip'));
        foreach ($tmp as $n => $v) {
            $tmp[$n] = " ( ip like '%$v%' ) ";
        }
        array_push($where, "(".implode($tmp, " OR ").")");
    }
    if (GET('from')) {
        $tmp = explode(',', GET('from'));
        foreach ($tmp as $n => $v) {
            $tmp[$n] = " ( form like '%$v%' ) ";
        }
        array_push($where, "(".implode($tmp, " OR ").")");
    }
    if (GET('type')) {
        array_push($where, "( type = '".GET('type')."' )");
    }
    if (empty($where)) {
        $where_sql = "1";
    } else {
        $where_sql = implode($where, " AND ");
    }

    return get_db()->select("count", "*", $where_sql);
}

?>
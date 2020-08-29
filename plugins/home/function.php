<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}
add_plugin('PHome_make_path_info', 'page_init');
add_plugin("PHome_add_admin_menu", "admin_init");
require_once("core.php");

function PHome_make_path_info()
{
    global $zxsys;
    $PHome_core = new HomeCore();
    if (!$zxsys->add_system_val("PHome", $PHome_core)) {
        die("home plugin init error!");
    }
    $zxsys->varl['PHome']->init();
}

function PHome_add_admin_menu()
{
    global $zxtmp;
    $zxtmp->add_admin_menu('setting-center', [], [
        [
            'url' => 'plugin_page.php?m=setting&id=home&p=setting', 'name' => 'URL伪静态设置', 'id' => 'setting_PHome',
            'power' => [0],
        ],
    ]);
}

//首页调用函数
function PHome_index()
{
    global $zxsys;
    $zxsys->varl['PHome']->show_page();
}

function PHome_load($PHome_name, $PHome_param = [])
{
    foreach ($PHome_param as $PHome_name_id => $PHome_name_value) {
        $$PHome_name_id = $PHome_name_value;
    }
    if (!is_file(ROOT_PATH."home/".$PHome_name.".php")) {
        require_once(ROOT_PATH."home/index.php");
    } else {
        require_once(ROOT_PATH."home/".$PHome_name.".php");
    }
}

function PHome_is_home()
{
    global $zxsys;

    return $zxsys->varl['PHome']->is_home;
}

function PHome_is_404()
{
    global $zxsys;

    return $zxsys->varl['PHome']->is_404;
}

?>
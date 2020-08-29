<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

class HomeCore
{
    private $path_info;
    private $setting;
    private $page;
    public $is_home;
    public $is_404;

    function __construct()
    {
    }

    public function init()
    {
        $this->is_home = false;
        $this->is_404 = false;
        $this->get_setting();
        $this->make_path_info();
    }

    private function make_path_info()
    {
        if (!isset($_SERVER['PATH_INFO'])) {
            $i = strpos($_SERVER['REQUEST_URI'], '?');
            if ($i === false) {
                $this->path_info = $_SERVER['REQUEST_URI'];
            } else {
                $this->path_info = substr($_SERVER['REQUEST_URI'], 0, $i);
            }
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            $j = strpos($this->path_info, $dir);
            if ($j === 0) {
                $this->path_info = substr($this->path_info, strlen($dir));
            }
        } else {
            $this->path_info = $_SERVER['PATH_INFO'];
        }
        $this->path_info = trim($this->path_info);
        if (substr($this->path_info, 0, 1) == '/') {
            $this->path_info = substr($this->path_info, 1);
        }
        $this->check_query();
    }

    private function get_setting()
    {
        global $zxsys;
        $this->setting = json_decode($zxsys->get_setting('PHome'));
    }

    public function show_page()
    {
        if ($this->is_home) {
            PHome_load("index");

            return;
        }
        if ($this->is_404) {
            PHome_load("404");

            return;
        }
        if (!empty($this->page)) {
            PHome_load($this->page->file, $this->page->param);
        }
    }

    private function check_query()
    {
        if ("" == $this->path_info) {
            $this->is_home = true;

            return;
        }
        foreach ($this->setting as $value) {
            if ($value->query == $this->path_info) {
                $this->page = $value;

                return;
            }
            if (is_int(stripos($value->query, "{number}"))) {
                preg_match_all("|".str_replace("{number}", "([0-9_]+)", $value->query.".html")."|U",
                    $this->path_info.".html", $out, PREG_PATTERN_ORDER);
                if (isset($out[0][0]) && isset($out[1][0]) && is_numeric($out[1][0]) && str_replace("{number}",
                        $out[1][0], $value->query.".html") == $this->path_info.".html") {
                    foreach ($value->param as $n => $v) {
                        if ("{number}" == $v) {
                            $value->param->$n = $out[1][0];
                        }
                    }
                    $this->page = $value;

                    return;
                }
            }
        }
        $this->is_404 = true;
    }
}
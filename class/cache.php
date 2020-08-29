<?php

class Cache
{
    private $status = false;
    static $close = false;

    function __construct()
    {
        global $__CACHE;
        if (get_config('cache_status') && isset($__CACHE) && 'START' == $__CACHE) {
            $this->status = true;
        }
        if ($this->status) {
            $this->start();
        }
    }

    private function start()
    {
        $this->check_file();
        ob_start('Cache::callback');
    }

    public function stop()
    {
        if (!$this->status) {
            return;
        }
        ob_end_flush();
        exit;
    }

    public function clean()
    {
        $list = glob(CACHE_PATH."*");
        foreach ($list as $f) {
            unlink($f);
        }
    }

    static function close()
    {
        Cache::$close = true;
    }

    private function check_file()
    {
        $file = CACHE_PATH.md5(now_url());
        if (is_file($file) && filemtime($file) + get_config('cache_time') > time()) {
            $content = file_get_contents($file);
            send_header();
            header('Content-Length: '.filesize($file));
            die($content);
        }
    }

    static function callback($content)
    {
        if (get_config('cache_comp')) {
            $content = Cache::compress_html($content);
        }
        Cache::make_cache_file($content);

        return $content;
    }

    static function make_cache_file($content)
    {
        if (Cache::$close) {
            return;
        }
        $file = CACHE_PATH.md5(now_url());
        file_put_contents($file, $content);
    }

    static function compress_html($string)
    {
        $string = str_replace("\r\n", '', $string); //清除换行符
        $string = str_replace("\n", '', $string); //清除换行符

        return str_replace("\t", '', $string); //清除制表符
    }
}

?>
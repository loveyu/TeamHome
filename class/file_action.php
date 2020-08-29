<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 文件操作类
 * 在涉及到文件操作时调用
 * @file class/file_action.php
 * @author  胡志宇
 * @version 1.0
 */
class File_action
{

    /**
     * 返回样式列表下的列表数组
     * @param  null
     * @access public
     * @return array
     */
    public function get_style_list()
    {
        $root = ROOT_PATH."style/";
        $handle = opendir($root); //打开指定文件夹
        $rt = [];
        if (!$handle) {
            return $rt;
        }
        while ($file = readdir($handle)) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (is_dir($root.$file)) {
                $rt[] = $file;
            }
        }
        closedir($handle); //关闭打开的文件夹

        return $rt;
    }

    /**
     * 编辑头像
     * @param  integer  $id  用户ID
     * @param  FILE  $file  POST提交的头像文件
     * @access public
     * @return string 错误信息，成功返回 OK
     */
    public function edit_avata($id, $file)
    {
        //头像操作
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        if (!$this->check_file($file)) {
            return '上传的头像有问题';
        }
        $type = $this->check_file_type($file);
        if ($type[0] != 'image') {
            return '上传的文件不是图片文件';
        }
        $avata_path = ROOT_PATH."avata/".get_user_id().".png";
        load('image');
        $image = new Image();
        $image->img_to_png($file['tmp_name'], $avata_path, 64, 64);

        return 'OK';
    }

    /**
     * 判断文件的样式
     * @param  FILE  $file  POST提交的file文件
     * @access private
     * @return bool
     */
    private function check_file($file)
    {
        return $file['size'] > 0;
    }

    /**
     * 删除一个目录
     * @param  string  $dir  要删除的目录的绝对目录
     * @access public
     * @return string 返回错误信息，成功返回OK
     */
    public function delete_path($dir)
    {
        if (is_file($dir)) {
            if (!unlink($dir)) {
                return '删除文件'.basename($dir)."失败";
            } else {
                return 'OK';
            }
        }
        $handle = opendir($dir); //打开指定文件夹
        if (!$handle) {
            return '打开目录'.basename($dir)."失败";
        }
        while ($file = readdir($handle)) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (is_dir("$dir/$file")) {
                $this->delete_path("$dir/$file");
            } else {
                if (!unlink("$dir/$file")) {
                    return '删除文件'.$file.'失败';
                }
            }
        }
        closedir($handle); //关闭打开的文件夹
        if (!rmdir("$dir/$file")) {
            return '删除目录'.$file.'失败';
        }

        return 'OK';
    }

    /**
     * 删除用户文件夹
     * @param  string  $username  用户名
     * @access private
     * @return bool
     */
    public function delete_user_path($username)
    {
        $path = ROOT_PATH.get_config('user_path');

        return $this->delete_path($path.$username) == 'OK';
    }

    /**
     * 获取路径下面的文件信息
     * @param  string  $path  要获取的绝对路径
     * @param  string  $extension_filter  允许的文件类型列表
     * @access private
     * @return array
     */
    private function get_path_file_info($path, $extension_filter = [])
    {
        $arr = [];
        $handle = opendir($path); //打开指定文件夹
        if (!$handle) {
            return $arr;
        }
        while ($file = readdir($handle)) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (is_file("$path/$file")) {
                if (!empty($extension_filter)) {
                    if (!in_array(pathinfo($file, PATHINFO_EXTENSION), $extension_filter)) {
                        continue;
                    }
                }
                $arr[$file] = [];
                $arr[$file]['name'] = $file;
                $arr[$file]['time'] = filemtime("$path/$file");
                $arr[$file]['size'] = filesize("$path/$file");
            }
        }
        closedir($handle); //关闭打开的文件

        return $arr;
    }

    /**
     * 返回切割文件类型
     * text/html 切割为 text,html
     *
     * @param  FILE  $file  一个POST提交的文件
     * @access private
     * @return array
     */
    private function check_file_type($file)
    {
        return explode("/", $file['type']);
    }

    /**
     * 移动上传的文件
     * @param  string  $path  移动到的地址
     * @param  string  $tmp  上传的文件路径
     * @access private
     * @return bool
     */
    private function move_file($path, $tmp)
    {
        if (file_exists($path)) {
            return false;
        }

        return move_uploaded_file($tmp, $path);
    }

    /**
     * 删除用户的头像
     * @param  integer  $id  用户ID
     * @access public
     * @return string 返回错误信息,成功为OK
     */
    public function delete_avata($id)
    {
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        $avata_path = ROOT_PATH."avata/".$id.".png";
        if (file_exists($avata_path) && is_file($avata_path)) {
            if (!unlink($avata_path)) {
                return '无法删除头像';
            }
        } else {
            return '头像不存在';
        }

        return 'OK';
    }

    /**
     * 获取用户主页文件下的文件列表
     * 包含文件及目录
     * @param  string  $path  选择中的文件路径
     * @access public
     * @return array
     */
    public function get_user_dir_list($path = '')
    {
        //获取文件及目录列表
        $path = str_replace('..', '', $path);
        $path = ROOT_PATH.get_config('user_path').get_user_name().$path;
        $list = ['dir' => [], 'file' => []];
        $handle = opendir($path);
        if (!$handle) {
            return $list;
        }
        $file = readdir($handle);
        while ($file) {
            if ($file != "." && $file != "..") {
                if (is_dir($path.$file)) {
                    $list['dir'][] = $file;
                }
                if (is_file($path.$file)) {
                    $list['file'][] = $file;
                }
            }
            $file = readdir($handle);
        }
        closedir($handle);

        return $list;
    }

    /**
     * 判断是否为突破
     * @param  string  $s  文件名或者是文件路径
     * @access public
     * @return bool
     */
    public function is_img($s)
    {
        $s = strtolower(pathinfo($s, PATHINFO_EXTENSION));

        return in_array($s, ['jpg', 'bmp', 'png', 'jpeg', 'gif']);
    }

    /**
     * 判断是否为文本
     * @param  string  $s  判断是否为文本
     * @access public
     * @return array
     */
    public function is_text($s)
    {
        $s = strtolower(pathinfo($s, PATHINFO_EXTENSION));

        return in_array($s, $this->text_array());
    }

    /**
     * 返回文本后缀数组
     * @param  null
     * @access public
     * @return array
     */
    public function text_array()
    {
        return ['txt', 'js', 'css', 'html', 'htm', 'xml'];
    }

    /**
     * 获取用户目录下的一个文本文件内容
     * @param  string  $path  相对于用户文件夹的路径
     * @access public
     * @return string
     */
    public function get_user_file($path)
    {
        if (!$this->is_text($path)) {
            return '';
        }
        $path = str_replace('..', '', $path);
        if (!$this->is_user_file($path, true)) {
            return '';
        }
        $path = ROOT_PATH.get_config('user_path').get_user_name()."/".$path;

        return file_get_contents($path);
    }

    /**
     * 判断是否为用户的文件
     * @param  string  $path  文件相对于用户文件的目录
     * @param  bool  $text  是否判断为文本
     * @access public
     * @return bool
     */
    public function is_user_file($path, $text = false)
    {
        //判断是否为文本，$text参数为true
        $path = str_replace('..', '', $path);
        if ($text && !$this->is_text($path)) {
            return false;
        }
        $path = ROOT_PATH.get_config('user_path').get_user_name()."/".$path;

        return is_file($path);
    }

    /**
     * 判断是否为一个正确的文件名
     * 仅仅允许宝航字母数字和下划线
     * @param  string  $s  文件名
     * @access public
     * @return bool
     */
    public function is_true_file_name($s)
    {
        //仅允许数字, 字母, _
        if (strlen($s) < 1) {
            return false;
        }
        for ($i = 0; $i < strlen($s); $i++) {
            $l = ord($s{$i});
            if (!(($l > 47 && $l < 58) || ($l > 64 && $l < 91) || ($l > 96 && $l < 123) || $l == 95)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 编辑用户的文本文件
     * @param  string  $path  文件相对于用户文件的路径
     * @param  string  $content  新的文件内容
     * @access public
     * @return string 错误信息，成功返回OK
     */
    public function edit_user_file($path, $content)
    {
        if (!$this->is_user_file($path, true)) {
            return '请检查文件路径';
        }
        $path = str_replace('..', '', $path);
        $path = ROOT_PATH.get_config('user_path').get_user_name()."/".$path;
        file_put_contents($path, $content);

        return 'OK';
    }

    /**
     * 重命名用户的文件
     * @param  string  $name  文件相对于用户的路径
     * @param  string  $new_name  新的文件名，不包含拓展名
     * @access public
     * @return strng 返回错误信息，成功返回OK
     */
    public function rename_user_file($path, $new_name)
    {
        if (!$this->is_user_file($path)) {
            return '该文件不存在或是文件夹，或者不存在';
        }
        if (!$this->is_true_file_name($new_name)) {
            return '新文件名不合法';
        }
        $dir = ROOT_PATH.get_config('user_path').get_user_name()."/";
        if (rename($dir.$path, $dir.dirname($path).'/'.$new_name.".".pathinfo($path, PATHINFO_EXTENSION))) {
            return 'OK';
        } else {
            return '重命名失败';
        }
    }

    /**
     * 删除用户内容
     * @param  string  $path  相对于用户文件的路径
     * @access public
     * @return string 返回错误信息，成功返回OK
     */
    public function delete_user_content($path)
    {
        $path = str_replace('..', '', $path);
        $dir = ROOT_PATH.get_config('user_path').get_user_name()."/";
        if (!file_exists($dir.$path)) {
            return '文件或目录不存在';
        }

        return $this->delete_path($dir.$path);
    }

    /**
     * 获取用户的某一类文件,返回一类数组
     * @param  string  $type  文件类型 page,img,css,js
     * @access public
     * @return array
     */
    public function get_user_type_file_list($type)
    {
        $dir = ROOT_PATH.get_config('user_path').get_user_name()."/";
        switch ($type) {
            case 'page':
                return $this->get_path_file_info($dir, ['html', 'html']);
                break;
            case 'img':
                return $this->get_path_file_info($dir.'img/', ['bmp', 'jpg', 'jpeg', 'gif', 'png']);
                break;
            case 'css':
                return $this->get_path_file_info($dir.'css/', ['css']);
                break;
            case 'js':
                return $this->get_path_file_info($dir.'js/', ['js']);
                break;
        }

        return [];
    }

    /**
     * 创建用户文件
     * @param  string  $type  文件类型 page,js,css
     * @param  string  $name  文件名,不包含拓展名
     * @access public
     * @return array
     */
    public function creat_user_type_file($type, $name)
    {
        if (!$this->is_true_file_name($name)) {
            return '文件名只能包含数字字母和下划线';
        }
        $dir = ROOT_PATH.get_config('user_path').get_user_name()."/";
        switch ($type) {
            case 'page':
                if (file_exists($dir.$name.'.html')) {
                    return '文件已存在';
                } else {
                    file_put_contents($dir.$name.'.html', "");
                }

                return 'OK';
                break;
            case 'js':
                if (file_exists($dir.'js/'.$name.'.js')) {
                    return '文件已存在';
                } else {
                    file_put_contents($dir.'js/'.$name.'.js', "");
                }

                return 'OK';
                break;
            case 'css':
                if (file_exists($dir.'css/'.$name.'.css')) {
                    return '文件已存在';
                } else {
                    file_put_contents($dir.'css/'.$name.'.css', "");
                }

                return 'OK';
                break;
        }

        return '未知操作';
    }

    /**
     * 文件大小的计算
     * @param  integer  $size  文件大小
     * @access public
     * @return float
     */
    public function size($size)
    {
        $newsize = 0;
        if ($size == 0) {
            $newsize = 0;
        } else {
            if ($size < 1024 && $size > 0) {
                $newsize = $size."B";
            } else {
                if ($size >= 1024 && $size < 1048576) {
                    $newsize = round($size / (1024), 2)."KB";
                } else {
                    if ($size >= 1048576 && $size < 1073741824) {
                        $newsize = round($size / (1024 * 1024), 2)."MB";
                    } else {
                        if ($size >= 1073741824 && $size < 1099511627776) {
                            $newsize = round($size / (1024 * 1024 * 1024), 2)."GB";
                        } else {
                            if ($size >= 1099511627776) {
                                $newsize = round($size / (1024 * 1024 * 1024 * 1024), 2)."TB";
                            }
                        }
                    }
                }
            }
        }

        return $newsize;
    }

    /**
     * 获取附件目录下的文件列表
     * @param  string  $path  选择中的文件路径
     * @access public
     * @return array
     */
    public function get_attach_dir_list($path = '')
    {
        //获取文件及目录列表
        $path = str_replace('..', '', $path);
        $path = ROOT_PATH."attached/".$path;
        $list = ['dir' => [], 'file' => []];
        $handle = opendir($path);
        if (!$handle) {
            return $list;
        }
        $file = readdir($handle);
        while ($file) {
            if ($file != "." && $file != "..") {
                if (is_dir($path.$file)) {
                    $list['dir'][] = $file;
                }
                if (is_file($path.$file)) {
                    $list['file'][] = $file;
                }
            }
            $file = readdir($handle);
        }
        closedir($handle);

        return $list;
    }

    /**
     * 判断是否为附件的文件
     * @param  string  $path  相对于附件的路径
     * @param  bool  $text  是否判断为文本
     * @access public
     * @return bool
     */
    public function is_attach_file($path, $text = false)
    {
        //判断是否为文本，$text参数为true
        $path = str_replace('..', '', $path);
        if ($text && !$this->is_text($path)) {
            return false;
        }
        $path = ROOT_PATH.get_config('attach_path').$path;

        return is_file($path);
    }

    /**
     * 重命名附件文件
     * @param  string  $name  相对于附件的路径
     * @param  string  $new_name  新的文件名，不包含拓展名
     * @access public
     * @return strng 返回错误信息，成功返回OK
     */
    public function rename_attach_file($path, $new_name)
    {
        if (!$this->is_attach_file($path)) {
            return '该文件不存在或是文件夹，或者不存在';
        }
        if (!$this->is_true_file_name($new_name)) {
            return '新文件名不合法';
        }
        $dir = ROOT_PATH.get_config('attach_path');
        if (rename($dir.$path, $dir.dirname($path).'/'.$new_name.".".pathinfo($path, PATHINFO_EXTENSION))) {
            return 'OK';
        } else {
            return '重命名失败';
        }
    }

    /**
     * 获取附件目录的一个文本文件内容
     * @param  string  $path  相对于附件的路径
     * @access public
     * @return string
     */
    public function get_attach_file($path)
    {
        if (!$this->is_text($path)) {
            return '';
        }
        $path = str_replace('..', '', $path);
        if (!$this->is_attach_file($path, true)) {
            return '';
        }
        $path = ROOT_PATH.get_config('attach_path').$path;

        return file_get_contents($path);
    }

    /**
     * 编辑附件文本文件
     * @param  string  $path  相对于附件的路径
     * @param  string  $content  新的文件内容
     * @access public
     * @return string 错误信息，成功返回OK
     */
    public function edit_attach_file($path, $content)
    {
        if (!$this->is_attach_file($path, true)) {
            return '请检查文件路径';
        }
        $path = str_replace('..', '', $path);
        $path = ROOT_PATH.get_config('attach_path').$path;
        file_put_contents($path, $content);

        return 'OK';
    }

    /**
     * 删除附件内容
     * @param  string  $path  相对于附件的路径
     * @access public
     * @return string 返回错误信息，成功返回OK
     */
    public function delete_attach_content($path)
    {
        $path = str_replace('..', '', $path);
        $dir = ROOT_PATH.get_config('attach_path');
        if (!file_exists($dir.$path)) {
            return '文件或目录不存在';
        }

        return $this->delete_path($dir.$path);
    }

    /**
     * 创建附件文件夹
     * @param  string  $path  相对于附件的路径
     * @param  string  $path  新的文件夹名
     * @access public
     * @return string 返回错误信息，成功返回OK
     */
    public function new_attach_dir($path, $name)
    {
        if (!$this->is_true_file_name($name)) {
            return '文件夹名不合法';
        }
        $path = str_replace('..', '', $path);
        $dir = ROOT_PATH.get_config('attach_path');
        if (!file_exists($dir.$path)) {
            return '当前文件或目录不存在';
        }
        if (file_exists($dir.$path."/".$name)) {
            return '当前文件夹已存在';
        }
        if (mkdir($dir.$path."/".$name)) {
            return 'OK';
        }

        return '文件夹创建失败';
    }

    /**
     * 创建附件文本
     * @param  string  $type  文件类型 page,js,css
     * @param  string  $name  文件名,不包含拓展名
     * @access public
     * @return array
     */
    public function creat_attach_file($name, $ext, $path)
    {
        if (!$this->is_true_file_name($name)) {
            return '文件名只能包含数字字母和下划线';
        }
        if (!in_array($ext, $this->text_array())) {
            return '只能允许'.implode(', ', $this->text_array())."类型";
        }
        $dir = ROOT_PATH.get_config('attach_path').$path;
        if (!is_dir($dir)) {
            return '要创建的文件的文件夹有误';
        }
        if (file_exists("$dir/$name.$ext")) {
            return '该文件已存在';
        }
        file_put_contents("$dir/$name.$ext", "");

        return 'OK';
    }

    /**
     * 判断是否为主题样式
     * @param  string  $theme  主题名
     * @param  string  $file  文件名
     * @access pubic
     * @return bool
     */
    public function is_style_file($theme, $file)
    {
        global $zxsys;
        $file = str_replace('..', '', $file);
        if (!in_array($theme, $zxsys->get_style_list())) {
            return false;
        }

        return is_file(ROOT_PATH."style/$theme/$file.css");
    }

    /**
     * 获取样式文件
     * @param  string  $theme  主题名
     * @param  string  $file  文件名
     * @access pubic
     * @return string
     */
    public function get_style_file_content($theme, $file)
    {
        $file = str_replace('..', '', $file);
        if (!$this->is_style_file($theme, $file)) {
            return "";
        }

        return file_get_contents(ROOT_PATH."style/$theme/$file.css");
    }

    /**
     * 编辑样式文件
     * @param  string  $file  文件名
     * @param  string  $theme  主题名
     * @param  string  $content  内容
     * @access public
     * @return
     */
    public function edit_style_file($file, $theme, $content)
    {
        $file = str_replace('..', '', $file);
        if (!$this->is_style_file($theme, $file)) {
            return "该文件存在路径问题";
        }
        file_put_contents(ROOT_PATH."style/$theme/$file.css", $content);

        return 'OK';
    }

    /**
     * 获取主题样式css文件列表
     *
     * @param  string  $theme
     * @acess public
     * @return array
     */
    public function get_style_theme_css_list($theme)
    {
        global $zxsys;
        if (!in_array($theme, $zxsys->get_style_list())) {
            return [];
        }

        return $this->get_path_file_info(ROOT_PATH."style/$theme/", ['css']);
    }
}

?>
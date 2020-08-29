<?php
/**
 *    附件文件上传
 *
 *    将文件权限设置在主附件目录
 *
 */

require_once '../../init.php';
if (!$zxuser->auto_login() || !is_admin()) {
    exit;
}//权限管理组
require_once 'JSON.php';

$up_path = GET("path");
$up_path = str_replace("..", "/", $up_path);

$php_path = ROOT_PATH.get_config('attach_path').$up_path;
$php_url = site_url().get_config('attach_path').$up_path;

if (!is_dir($php_path)) {
    alert("上传路径有误");
}

//文件保存目录路径
$save_path = $php_path;
//文件保存目录URL
$save_url = $php_url;
//定义允许上传的文件扩展名
$ext_arr = [
    'file' => [
        'doc', 'docx', 'xls', 'xlsx', 'ppt', 'txt', 'zip', 'rar', 'gz', 'bz2', 'css', 'js', 'gif', 'jpg', 'jpeg', 'png',
        'bmp', 'swf', 'swf', 'flv', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'xml',
    ],
];
//最大文件大小
$max_size = 1000000;

$save_path = realpath($save_path).'/';

//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
    switch ($_FILES['imgFile']['error']) {
        case '1':
            $error = '超过php.ini允许的大小。';
            break;
        case '2':
            $error = '超过表单允许的大小。';
            break;
        case '3':
            $error = '图片只有部分被上传。';
            break;
        case '4':
            $error = '请选择图片。';
            break;
        case '6':
            $error = '找不到临时目录。';
            break;
        case '7':
            $error = '写文件到硬盘出错。';
            break;
        case '8':
            $error = 'File upload stopped by extension。';
            break;
        case '999':
        default:
            $error = '未知错误。';
    }
    alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
    //原文件名
    $file_name = $_FILES['imgFile']['name'];
    //服务器上临时文件名
    $tmp_name = $_FILES['imgFile']['tmp_name'];
    //文件大小
    $file_size = $_FILES['imgFile']['size'];
    //检查文件名
    if (!$file_name) {
        alert("请选择文件。");
    }
    //检查目录
    if (@is_dir($save_path) === false) {
        alert("上传目录不存在。");
    }
    //检查目录写权限
    if (@is_writable($save_path) === false) {
        alert("上传目录没有写权限。");
    }
    //检查是否已上传
    if (@is_uploaded_file($tmp_name) === false) {
        alert("上传失败。");
    }

    //检查文件大小
    if ($file_size > $max_size) {
        alert("上传文件大小超过限制。");
    }
    //检查目录名
    $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
    if (empty($ext_arr[$dir_name])) {
        alert("目录名不正确。");
    }
    //获得文件扩展名
    $temp_arr = explode(".", $file_name);
    $file_ext = array_pop($temp_arr);
    $file_ext = trim($file_ext);
    $file_ext = strtolower($file_ext);
    //检查扩展名
    if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
        alert("上传文件扩展名是不允许的扩展名。\n只允许".implode(",", $ext_arr[$dir_name])."格式。");
    }

    $new_file_name = $file_name;//使用原文件名保存

    //移动文件
    $file_path = $save_path.$new_file_name;
    //检查文件是否已存在
    if (file_exists($file_path)) {
        alert("文件 $new_file_name 已存在");
    }

    if (move_uploaded_file($tmp_name, $file_path) === false) {
        alert("上传文件失败。");
    }
    @chmod($file_path, 0644);
    $file_url = $save_url.$new_file_name;

    header('Content-type: text/html; charset=UTF-8');
    $json = new Services_JSON();
    echo $json->encode(['error' => 0, 'url' => $file_url]);
    exit;
}

function alert($msg)
{
    header('Content-type: text/html; charset=UTF-8');
    $json = new Services_JSON();
    echo $json->encode(['error' => 1, 'message' => $msg]);
    exit;
}

?>
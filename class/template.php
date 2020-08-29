<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 网页模板操作
 * 在最初进行加载
 * @file class/template.php
 * @author  胡志宇
 * @version 1.0
 */
class Template
{

    /**
     * 头部信息数组
     */
    public $header;

    /**
     * 底部信息数组
     */
    public $footer;

    /**
     * 后台用户中心菜单数组
     */
    public $admin_menu;

    /**
     * 页面标记数组,main=>,sub
     */
    public $page_id; //页面ID用于页面菜单检测

    /**
     * 主菜单数组/导航栏数组
     */
    public $menu;

    /**
     * 友情链接数组
     */
    public $friend_link;

    /**
     * html底部加载信息
     */
    public $html_end;

    /**
     * 用户信息，通过程序赋值
     */
    public $user;

    /**
     * 默认构造器
     * @param  null
     * @access public
     * @return null
     */
    function __construct()
    {
        $this->header = [];
        $this->header['title'] = '';
        $this->header['link'] = [];
        $this->header['script'] = [];
        $this->header['meta'] = [];
        $this->header['other_first'] = '';
        $this->header['other'] = '';

        $this->footer = '';
        $this->admin_menu = [];
        $this->menu = [];
        $this->friend_link = [];
        $this->page_id = ['main' => '', 'sub' => ''];
        $this->html_end = [];
        $this->add_header_other("\t<script type=\"text/javascript\">var SITE_URL=\"".site_url()."\";</script>\n",
            'first');
    }

    /**
     * 获取菜单列表，进行排序，存储到$this->menu
     *
     * @param  null
     * @access public
     * @return null
     */
    public function get_menu_list()
    {
        global $zxsys;
        $cat = $zxsys->get_setting('nav_link');
        $this->menu = $this->get_link_list($cat);
    }

    /**
     * 获取友情链接列表，进行排序，存储到$this->menu
     *
     * @param  null
     * @access public
     * @return null
     */
    public function get_friend_link_list()
    {
        global $zxsys;
        $cat = $zxsys->get_setting('friend_link');
        $this->friend_link = $this->get_link_list($cat);
    }

    /**
     * 获取链接列表，进行排序，并返回
     * @param  null
     * @access public
     * @return null
     */
    public function get_link_list($id)
    {
        load('link');
        $link = new Link();
        $link_list = $link->get_category_link($id);
        $sort = [];
        foreach ($link_list as $v) {
            $sort[$v['id']] = $v['no'];
        }
        asort($sort);
        $rt = [];
        foreach ($sort as $id => $v) {
            $rt[$id] = $link_list[$id];
        }

        return $rt;
    }


    /**
     * 增加Footer信息
     *
     * @param  string  $s
     * @access public
     * @return null
     */
    public function add_footer($s)
    {
        $this->footer .= $s;
    }

    /**
     * 增加头部的其他信息
     * @param  string  $s  添加到other字段中
     * @param  string  $type  first优先输出
     * @access public
     * @return null
     */
    public function add_header_other($s, $type = 'other')
    {
        if ($type == 'first') {
            $this->header['other_first'] .= $s;
        } else {
            $this->header['other'] .= $s;
        }
    }

    /**
     * 设置页面ID，导航菜单中用到
     *
     * @param  string  $id  必须参数
     * @param  string  $id2  可选
     * @access public
     * @return null
     */
    public function set_page_id($id, $id2 = '')
    {
        $this->page_id['main'] = $id;
        $this->page_id['sub'] = $id2;
        if (in_array($id, get_config("close_page"))) {
            redirect("user/closed.php");
        }
    }

    /**
     * 将数组信息保存到头部字符串中
     * @param  array  $arr  包含title,link,script,meta的三维数组
     * @access public
     * @return null
     */
    public function set_header($arr = [])
    {
        if (isset($arr['title']) && $arr['title'] != null) {
            $this->header['title'] = $arr['title'];
        }
        if (isset($arr['link']) && !empty($arr['link'])) {
            foreach ($arr['link'] as $v) {
                array_push($this->header['link'], $v);
            }
        }
        if (isset($arr['script']) && !empty($arr['script'])) {
            foreach ($arr['script'] as $v) {
                array_push($this->header['script'], $v);
            }
        }
        if (isset($arr['meta']) && !empty($arr['meta'])) {
            foreach ($arr['meta'] as $v) {
                array_push($this->header['meta'], $v);
            }
        }
    }

    /**
     * 增加一个后台用户中心菜单选项
     * @param  string  $id  主菜单ID
     * @param  array  $title  主菜单标题数组array('id'=> '', 'url'=>'','name'=>'','power'=>array());
     * @param  array  $sub_menu  子菜单,可选 array(array('name'=>'','url'=>'','id'=>'','power'=>array())),子菜单ID必须以主菜单ID加子菜单ID形式 sub_id = MainId_SubID
     * @access public
     * @return null
     */
    public function add_admin_menu($id, $title, $sub_menu = [])
    {
        if (isset($this->admin_menu[$id])) {
            foreach ($sub_menu as $v) {
                if (!isset($this->admin_menu[$id]['sub_menu'][$v['id']])) {
                    $this->admin_menu[$id]['sub_menu'][$v['id']] = $v;
                }
            }
        } else {
            $this->admin_menu[$id] = [];
            $this->admin_menu[$id]['title'] = $title;
            $this->admin_menu[$id]['sub_menu'] = [];
            foreach ($sub_menu as $v) {
                $this->admin_menu[$id]['sub_menu'][$v['id']] = $v;
            }
        }
    }

    /**
     * 获取后台菜单的菜单名称
     * @param  string  $id  主菜单ID
     * @param  string  $sub  子菜单ID
     * @access public
     * @return string
     */
    public function get_admin_menu_title($id, $sub = '')
    {
        if (!isset($this->admin_menu[$id])) {
            return '';
        }
        $rt = $this->admin_menu[$id]['title']['name'];
        if (isset($this->admin_menu[$id]['sub_menu'][$id."_".$sub]['name'])) {
            $rt = $this->admin_menu[$id]['sub_menu'][$id."_".$sub]['name']." - $rt";
        }

        return $rt;
    }

    /**
     * 输出用户中心菜单列表
     * @param  null
     * @access public
     * @return null
     */
    public function out_admin_menu()
    {
        foreach ($this->admin_menu as $v) {
            $this->show_one_admin_menu($v['title'], $v['sub_menu']);
        }
    }

    /**
     * 输出一个用户中心菜单，前提$his->user变量已赋值
     *
     * @param  array  $title  标题菜单
     * @param  array  $sub_menu  子菜单
     * @access public
     * @return null
     */
    public function show_one_admin_menu($title, $sub_menu = [])
    {
        //$sub_menu=array(array('name'=>'','url'=>'','id'=>'','power'=>array()))
        //$title=array('id'=> '', 'url'=>'','name'=>'','power'=>array());
        if (!in_array($this->user->get_power(), $title['power'])) {
            return 'No Permissions';
        }
        if (in_array($title['id'], get_config("close_page"))) {
            return "closed";
        }
        if ($this->page_id['main'] == $title['id']) {
            echo "\t<li class=\"menu menu_now\">\n\t\t<div class=\"menu-title\"><span><a href=\"", $title['url'], "\">", $title['name'], "</a></span></div>\n\t\t<div class=\"sub-menu menu_sub_list\" id=\"menu_", $title['id'], "\">\n";
        } else {
            echo "\t<li class=\"menu\">\n\t\t<div class=\"menu-title\"><span><a href=\"", $title['url'], "\">", $title['name'], "</a></span></div>\n\t\t<div class=\"sub-menu\" id=\"menu_", $title['id'], "\">\n";
        }
        //子菜单
        if (!empty($sub_menu)) {
            echo "\t\t\t<ul>\n";
            foreach ($sub_menu as $v) {
                if (!in_array($this->user->get_power(), $v['power'])) {
                    continue;
                }
                if ($this->page_id['main']."_".$this->page_id['sub'] == $v['id']) {
                    echo '<li class="menu_sub_now"><a href="', $v['url'], '">', $v['name'], '</a></li>', "\n";
                } else {
                    echo "\t\t\t\t", '<li><a href="', $v['url'], '">', $v['name'], '</a></li>', "\n";
                }
            }
            echo "\t\t\t</ul>\n";
        }
        echo "\t\t</div>\n\t</li>\n\n";
    }

    /**
     * 输出导航栏菜单
     * @param  string  $class  所有菜单具有的css 样式
     * @param  string  $select_class  当前选中的菜单增加的css 样式
     * @param  string  $begin1  li列表之前的内容
     * @param  string  $begin  a之前的内容
     * @param  string  $end  a之后的内容
     * @param  string  $end1  li之后的内容
     * @access public
     * @return null
     */
    public function out_nav_menu($class, $select_class, $begin1, $begin, $end, $end1)
    {
        if (empty($this->menu)) {
            $this->get_menu_list();
        }
        foreach ($this->menu as $v) {
            echo $begin1, "<li class=\"$class".(($class) ? " " : ""), ($this->page_id['main'] == $v['flag']) ? $select_class : '', "\" id=\"menu_nav_", $v['id'], "\">$begin<a href=\"", $v['url'], "\" title=\"", $v['description'], "\">", $v['name'], "</a>$end</li>$end1\n";
        }
    }

    /**
     * 输出友情链接
     * @param  string  $class  所有菜单具有的css 样式
     * @param  string  $begin1  li列表之前的内容
     * @param  string  $begin  a之前的内容
     * @param  string  $end  a之后的内容
     * @param  string  $end1  li之后的内容
     * @access public
     * @return null
     */
    public function out_friend_link($class, $begin1, $begin, $end, $end1)
    {
        if (empty($this->friend_link)) {
            $this->get_friend_link_list();
        }
        foreach ($this->friend_link as $v) {
            echo $begin1, "<li class=\"$class\" id=\"friend_link_", $v['id'], "\">$begin<a href=\"", $v['url'], "\" title=\"", $v['description'], "\">", $v['name'], "</a>$end</li>$end1\n";
        }
    }
}

?>
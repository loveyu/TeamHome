<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * 新闻操作分类
 * @file class/news.php
 * @author  胡志宇
 * @version 1.0
 */
class News
{
    /**
     * 数据库类操作引用
     */
    private $db;

    /**
     * 当前页面的所有页面数量及状态
     * now_page:当前页面 all_page:所有页面数 one_page:每页显示数量 news_count:新闻总数 remainder:不为0时为，最后一页数量
     */
    public $page_info; //页面信息

    /**
     * 所有分类信息
     */
    public $category;

    /**
     * 简要的分类信息
     */
    public $category_simple;

    /**
     * 默认构造器
     * @param  null
     * @access public
     * @return null
     */
    function __construct()
    {
        global $zxdb;
        $this->db = &$zxdb;
        $this->page_info =
            ['now_page' => 1, 'all_page' => 1, 'one_page' => 0, 'news_count' => 0, 'remainder' => 0]; //页面信息
        $this->category = [];
        $this->category_simple = [];
    }

    /**
     * 发布新的文章
     * @param  string  $title  标题
     * @param  string  $author  作者，文本
     * @param  string  $content  内容，POST自动加斜杠之后的内容
     * @param  string  $category  文章分类
     * @param  string  $type  发布类型,publish:发布,draft:草稿,hidden:隐藏，默认publish
     * @param  string  $time  时间，example 2012-11-11 11:11:44 ，默认当前时间
     * @access public
     * @return string 错误信息,成功返回OK
     */
    public function new_post($title, $author, $content, $category, $comment = 'flase', $type = 'publish', $time = '')
    {
        //发布新的文章
        if (empty($title)) {
            return '标题不能为空';
        }
        if (empty($author)) {
            return '作者不能为空';
        }
        if (empty($content)) {
            return '内容不能为空';
        }
        if (!in_array($comment, ['false', 'true'])) {
            return '该新闻评论状态有误';
        }
        if (!in_array($category, array_flip($this->get_category_list()))) {
            return '分类不存在';
        }
        if (!in_array($type, $this->get_type_list())) {
            return '该状态不存在';
        }
        if (!empty($time)) {
            if (strlen($time) != 19 || !check_time(substr($time, 0, 4), substr($time, 5, 2), substr($time, 8, 2),
                    substr($time, 11, 2), substr($time, 14, 2), substr($time, 17, 2))) {
                return '日期不合法';
            }
            if ($this->db->insert("news", [
                'title' => $title, 'author' => $author, 'content' => $content, 'category' => $category, 'type' => $type,
                'time'  => $time, 'user' => get_user_id(),
            ])) {
                return 'OK';
            } else {
                return '插入数据失败';
            }
        }
        if ($this->db->insert("news", [
            'title'    => $title, 'author' => $author, 'content' => $content, 'comment' => $comment,
            'category' => $category, 'type' => $type, 'user' => get_user_id(),
        ])) {
            return 'OK';
        } else {
            return '插入数据失败';
        }
    }

    /**
     * 编辑文章
     * @param  integer  $id  文章ID
     * @param  string  $title  标题
     * @param  string  $author  作者，文本
     * @param  string  $content  内容，POST自动加斜杠之后的内容
     * @param  string  $category  文章分类
     * @param  string  $type  发布类型,publish:发布,draft:草稿,hidden:隐藏
     * @param  string  $time  时间，example 2012-11-11 11:11:44
     * @access public
     * @return string 错误信息,成功返回OK
     */
    public function edit_post($id, $title, $author, $content, $comment, $category, $type, $time)
    {
        //发布新的文章
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        if (empty($title)) {
            return '标题不能为空';
        }
        if (empty($author)) {
            return '作者不能为空';
        }
        if (empty($content)) {
            return '内容不能为空';
        }
        if (!in_array($comment, ['false', 'true'])) {
            return '该新闻评论状态有误';
        }
        if (!in_array($category, array_flip($this->get_category_list()))) {
            return '分类不存在';
        }
        if (!in_array($type, $this->get_type_list())) {
            return '该状态不存在';
        }
        if (empty($time)) {
            return '必须存在一个发布时间';
        }
        if (strlen($time) != 19 || !check_time(substr($time, 0, 4), substr($time, 5, 2), substr($time, 8, 2),
                substr($time, 11, 2), substr($time, 14, 2), substr($time, 17, 2))) {
            return '日期不合法';
        }
        if (!$this->db->sql_check("news", "`id`=".$id)) {
            return '该文章不存在，无法更新';
        }
        if ($this->db->update("news", [
            'title'    => SS($title), 'author' => SS($author), 'content' => SS($content), 'comment' => SS($comment),
            'category' => SS($category), 'time' => SS($time), 'type' => SS($type),
        ], "`id`=".SS($id))) {
            return 'OK';
        } else {
            return '插入数据失败';
        }
    }

    /**
     * 删除新的文章
     * @param  integer  $id  删除的文章ID
     * @access public
     * @return string 错误信息,成功返回OK
     */
    public function delete($id)
    {
        if (!is_admin()) {
            return '非管理员操作，禁止';
        }
        if (!is_numeric($id) || $id < 1) {
            return 'ID有误';
        }
        if (!$this->db->sql_check("news", "`id`=".$id)) {
            return '该文章不存在，无法删除不存在的数据';
        }
        if ($this->db->delete("news", "`id`=".$id)) {
            return 'OK';
        } else {
            return '文章删除失败';
        }
    }

    /**
     * 获取一条新闻新的内容
     * @param  integer  $id  文章ID
     * @access public
     * @return array 文章全部信息数组
     */
    public function get_news($id)
    {
        //获取一条新闻的内容
        if (is_numeric($id) && $id > 0) {
            $s = $this->db->select('news', "*", "`id`=".$id);
        }
        if (isset($s[0])) {
            return $s[0];
        } else {
            return [];
        }
    }

    /**
     * 获取某一个分类的文章
     * @param  string  $category  某一个分类的ID，留空将获取全部数组
     * @param  string  $type  文章的发布类型 publish,draft,hidden
     * @param  string  $page  当前页面序号
     * @param  string  $num  每页显示的数量
     * @param  string  $field  要显示的字段
     * @param  string  $time  时间匹配方式
     * @param  bool  $is_list  是否为仅仅列表显示
     * @access public
     * @return string 错误信息,成功返回OK
     */
    public function get_category_post(
        $category = '',
        $type = '',
        $page = 1,
        $num = '',
        $field = "id,title,user,author,time,category,type,comment",
        $time = '',
        $is_list = false
    ) {
        //分类参数为空为全部分类，类型参数为空为全部类型,$page为当前页面数,$num为每页数量且留空为系统默认值或数据库查询值
        if (empty($category)) {
            $category = array_flip($this->get_category_list());
        } else {
            if (is_array($category)) {
                //此时为一个数组
            } else {
                if (!in_array($category, array_flip($this->get_category_list()))) {
                    return [];
                } else {
                    $category = [$category];
                }
            }
        }

        if (empty($type)) {
            $type = $this->get_type_list();
        } else {
            if (!in_array($type, $this->get_type_list())) {
                return [];
            } else {
                $type = [$type];
            }
        }

        if ($time != '') {
            $time = " AND time like '".SS($time)."%'";
        } else {
            $time = '';
        }

        //转换数组
        foreach ($category as $id => $v) {
            $category[$id] = "'".SS($v)."'";
        }
        foreach ($type as $id => $v) {
            $type[$id] = "'".SS($v)."'";
        }

        global $zxsys; //系统类全局变量

        if (!$is_list) {
            $this->page_info['news_count'] =
                $this->db->count_sql("news",
                    "(category IN (".implode(',', $category).")) AND (type IN (".implode(',', $type).")) $time");
        } else {
            $this->page_info['news_count'] = $zxsys->get_setting('news_one_page_number');
        }

        //生成页面信息
        if (!is_numeric($num) || empty($num)) {
            $this->page_info['one_page'] = $zxsys->get_setting('news_one_page_number');
        } else {
            $this->page_info['one_page'] = $num;
        }
        $this->page_info['remainder'] = $this->page_info['news_count'] % $this->page_info['one_page'];
        $this->page_info['all_page'] =
            ($this->page_info['news_count'] - $this->page_info['remainder']) / $this->page_info['one_page'];
        if ($this->page_info['remainder'] > 0) {
            $this->page_info['all_page']++;
        } //余数不为0，页面数加1

        if (!is_numeric($page) || $page < 1) {
            $page = 1;
        }
        if ($page > $this->page_info['all_page']) {
            return [];
        } //超出页面数，返回空

        return $this->db->select("news", $field,
            "((category IN (".implode(',', $category).")) AND (type IN (".implode(',',
                $type).")) $time ) ORDER BY time DESC LIMIT ".($page - 1) * $this->page_info['one_page'].", ".$this->page_info['one_page']);
    }

    /**
     * 获取分类信息，或获取某一个分类的名称
     * @param  string  $t  分类ID
     * @access public
     * @return string 存在返回分类名称，否则返回全部数组
     */
    public function get_category_list($t = '')
    {
        if (empty($this->category)) {
            global $zxcat;
            $this->category = $zxcat->get_category('news');
            if (is_array($this->category)) {
                foreach ($this->category as $v) {
                    $this->category_simple[$v['id']] = $v['name'];
                }
            }
        }
        if (isset($this->category_simple[$t])) {
            return $this->category_simple[$t];
        }

        return $this->category_simple;
    }

    /**
     * 获取允许的文章类型列表
     * @param  null
     * @access public
     * @return array
     */
    public function get_type_list()
    {
        return ['publish', 'draft', 'hidden'];
    }

    /**
     * 获取一篇发布的文章
     * 不符合返回空
     * @param  integer  $id  文章ID
     * @param  string  $cat_alias  分类别名
     * @param  string  $type  文章类型
     * @access public
     * @return array
     */
    public function get_one_post_news($id, $cat_alias, $type = 'publish')
    {
        $post = $this->get_news($id);
        if (empty($post)) {
            return [];
        }
        if ($cat_alias != '') {
            $this->get_category_list();
            if ($this->category[$post['category']]['alias'] != $cat_alias) {
                return [];
            }
        }
        if ($post['type'] != $type) {
            return [];
        }

        return $post;
    }

    /**
     * 文章分类别名转为分类ID
     *
     * @param  string  $cat_alias  分类别名
     * @access public
     * @return integer
     */
    public function get_alias_cat_id($cat_alias)
    {
        $this->get_category_list(); //获取新闻分类目录
        foreach ($this->category as $v) {
            if ($v['alias'] == $cat_alias) {
                return $v['id'];
            }
        }

        return 0;
    }

    /**
     * 获取新闻归档列表
     * @param  void
     * @access public
     * @return array
     */
    public function get_archive_list()
    {
        return $this->db->get_date_archive('news', 'time', '%Y%m');
    }
}

?>
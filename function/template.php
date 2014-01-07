<?php if(!defined('ROOT_PATH'))
	exit('No direct script access allowed');

	/**
	 * 界面用户信息获取和界面动态基础内容获取类
	 * 运行初期加载
	 * @file function/template.php
	 * @author  胡志宇
	 * @version 1.0
	 */
	//-----------------------------------------------

	/**
	 * 加载网页头文件
	 * @param array $head 网页需要的头文件信息
	 * @return null
	 */
	function get_header($head = array()){
		global $zxtmp;
		send_header();
		$zxtmp->set_header($head);
		include_once(TEMPLATE_PATH . "header.php");
	}

	/**
	 * 加载网页底部文件,加载后程序结束
	 * @param null
	 * @return null
	 */
	function get_footer(){
		include_once(TEMPLATE_PATH . "footer.php");
		exit;
	}


	/**
	 * 加载模板函数
	 * @param null
	 * @return null
	 */
	function get_function(){
		require_once(TEMPLATE_PATH . "function.php");
	}

	/**
	 * 加载用户中心网页头文件
	 * @param array $head 网页需要的头文件信息
	 * @return null
	 */
	function get_admin_header($head){
		global $zxtmp;
		send_header();
		$zxtmp->set_header($head);
		include_once(TEMPLATE_PATH . "admin_header.php");
	}

	/**
	 * 加载用户中心网页底部文件,加载后程序结束
	 * @param null
	 * @return null
	 */
	function get_admin_footer(){
		include_once(TEMPLATE_PATH . "admin_footer.php");
		exit;
	}

	/**
	 * 加载页面跳转关闭页面
	 * @param string $s              提示信息
	 * @param bool   $is_new_windows 默认true,判断是否为新窗口
	 * @return null
	 */
	function get_close_page($s, $is_new_windows = true){
		include_once(TEMPLATE_PATH . "close_page.php");
		exit;
	}

	/**
	 * 返回一个404错误页
	 * @param null
	 * @return null
	 */
	function get_404_page(){
		header("HTTP/1.0 404 Not Found");
		include_once(TEMPLATE_PATH . "404.php");
		exit;
	}

	/**
	 * 输出用户中心功能菜单
	 * @param null
	 * @return null
	 */
	function out_admin_menu(){
		global $zxtmp;
		$zxtmp->out_admin_menu();
	}

	/**
	 * 输出导航菜单
	 * @param string $class        所有菜单具有的css 样式
	 * @param string $select_class 当前选中的菜单增加的css 样式
	 * @param string $begin1       li列表之前的内容
	 * @param string $begin        a之前的内容
	 * @param string $end          a之后的内容
	 * @param string $end1         li之后的内容
	 * @return null
	 */
	function out_nav_menu($class, $select_class, $begin1, $begin, $end, $end1){
		global $zxtmp;
		$zxtmp->out_nav_menu($class, $select_class, $begin1, $begin, $end, $end1);
	}

	/**
	 * 输出定义的头部信息
	 * @param null
	 * @return null
	 */
	function zx_header(){
		//输出定义的HTML头模块
		global $zxtmp;
		echo $zxtmp->header['other_first']; //优先输出
		if(!empty($zxtmp->header['link']))
			foreach($zxtmp->header['link'] as $v){
				if($v['rel'] == 'stylesheet')
					$v['href'] = get_style_path() . $v['href'];
				echo "\t<link ";
				foreach($v as $name => $value){
					echo $name, '="', $value, '" ';
				}
				echo "/>\n";
			}
		if(!empty($zxtmp->header['meta']))
			foreach($zxtmp->header['meta'] as $v){
				echo "\t<meta ";
				foreach($v as $name => $value){
					echo $name, '="', $value, '" ';
				}
				echo "/>\n";
			}
		if(!empty($zxtmp->header['script']))
			foreach($zxtmp->header['script'] as $v){
				echo "\t<script ";
				foreach($v as $name => $value){
					echo $name, '="', $value, '" ';
				}
				echo "></script>\n";
			}
		echo $zxtmp->header['other'];
	}

	/**
	 * 输出定义的底部信息
	 * @param null
	 * @return null
	 */
	function zx_footer(){
		global $zxtmp;
		echo $zxtmp->footer;
	}

	/**
	 * 获取当前网页样式网址目录
	 * @param null
	 * @return string url
	 */
	function get_style_path(){
		//获取样式目录
		global $zxsys;
		return site_url("style/" . $zxsys->get_setting("site_style") . "/");
	}

	/**
	 * 输出网页标题
	 * @param null
	 * @return null
	 */
	function zx_title(){
		//输出标题
		global $zxtmp;
		if($zxtmp->header['title'] == '')
			echo site_title() . " - " . site_description();
		else echo $zxtmp->header['title'], " - ", site_title();
	}

	/**
	 * 直接输出登陆登出链接
	 * @param null
	 * @return null
	 */
	function out_login_link(){
		//直接数据数据
		if(is_login())
			echo '<a href="', site_url("logout.php"), '">登出</a>';
		else echo '<a href="', site_url("login.php"), '">登入</a>';
	}

	/**
	 * 获取网站名称
	 * @param null
	 * @return string
	 */
	function site_title(){
		global $zxsys;
		return $zxsys->get_setting('site_name');
	}

	/**
	 * 获取网站描述信息
	 * @return string
	 */
	function site_description(){
		global $zxsys;
		return $zxsys->get_setting("site_description");
	}

	/**
	 * 获取用户名头像地址
	 * @param null
	 * @return string url
	 */
	function get_avata($id = 0){
		global $zxsys;
		if($id <= 0 || !is_numeric($id))
			return site_url("avata/default.png");
		if(is_file(ROOT_PATH . "avata/$id.png"))
			return site_url("avata/$id.png");
		else return site_url("avata/default.png");
	}

	/**
	 * 获取网页执行时间
	 * @param null
	 * @return float
	 */
	function get_run_time(){
		global $RUN_TIME_BEGIN;
		list($usec, $sec) = explode(' ', $RUN_TIME_BEGIN);
		$start = ((float)$usec + (float)$sec); //得出开始时间
		list($usec, $sec) = explode(' ', microtime());
		$end = ((float)$usec + (float)$sec); //得出结束时间
		return round(($end - $start), 5); //返回秒数
	}

	/**
	 * 获取数据库执行次数
	 * @param null
	 * @return null
	 */
	function get_sql_query_count(){
		global $zxdb;
		return $zxdb->get_count();
	}

	/**
	 * 获取登陆用户ID
	 *
	 * @param null
	 * @return integer
	 */
	function get_user_id(){
		global $zxuser;
		if(isset($zxuser->user_info['id']))
			return $zxuser->user_info['id'];
		else return 0;
	}

	/**
	 * 获取登陆用户邮箱
	 * @param null
	 * @return string email
	 */
	function get_user_email(){
		global $zxuser;
		if(isset($zxuser->user_info['user']))
			return $zxuser->user_info['user'];
		else
			return '';
	}

	/**
	 * 获取登陆用户的用户名
	 * @param null
	 * @return string
	 */
	function get_user_name(){
		global $zxuser;
		if(isset($zxuser->user_info['username']))
			return $zxuser->user_info['username'];
		else return '';
	}

	/**
	 * 获取用户主页
	 * @param null
	 * @return string
	 */
	function get_user_site_page(){
		if(get_user_name())
			return site_url(get_config('user_path') . get_user_name());
		else return '';
	}

	/**
	 * 判断用户是否登陆
	 * @param null
	 * @return bool
	 */
	function is_login(){
		global $zxuser;
		return $zxuser->is_login;
	}

	/**
	 * 获取用户照片
	 * @param integer $id ID
	 * @return string URL
	 */
	function get_photo($id = '', $user_action = ''){
		$path = site_url() . get_config('user_path');
		$user_name = '';
		if($id == '' || $id == get_user_id()){
			$user_name = get_user_name();
		}
		else{
			if(empty($user_action))
				$user_action = 'NULL';
			else{
				$info = $user_action->get_one_info($id, "usernaem");
				if(empty($info))
					$user_name = 'NULL';
				else $user_name = $info['username'];
			}
		}
		if($user_name == 'NULL' || !is_file(ROOT_PATH . get_config('user_path') . $user_name . '/img/photo.jpg')){
			return site_url() . 'avata/default_photo.jpg';
		}
		else{
			return site_url() . get_config('user_path') . $user_name . '/img/photo.jpg';
		}
	}

	/**
	 * 获取网站URL
	 *
	 * @param string $s 将变量$s添加到网址之后
	 * @return string
	 */
	function site_url($s = ''){
		global $zxsys;
		return ($zxsys->get_setting("site_url")) . $s;
	}

	/**
	 * 加载访问禁止页面
	 * @param null
	 * @return null
	 */
	function forbid_page(){
		die("Firbidden");
	}

	/**
	 * 跳转到激活页面
	 * @param null
	 * @return null
	 */
	function redirect_active(){
		redirect(site_url("user/edit_active.php"), 'refresh');
	}

	/**
	 * 输出友情链接
	 * @param string $class  所有菜单具有的css 样式
	 * @param string $begin1 li列表之前的内容
	 * @param string $begin  a之前的内容
	 * @param string $end    a之后的内容
	 * @param string $end1   li之后的内容
	 * @return null
	 */
	function out_friend_link($class = 'friend-link', $begin1 = '', $begin = '', $end = '', $end1 = ''){
		global $zxtmp;
		$zxtmp->out_friend_link($class, $begin1, $begin, $end, $end1);
	}

	/**
	 * 输出页面列表
	 * @param integer $all_page  所有页面数量
	 * @param integer $now_page  当前页面
	 * @param string  $url       地址,%%为替换符
	 * @param string  $all_class 所有链接的class
	 * @param string  $now_class 当前连接特有的class
	 * @param string  $div_id    div层ID
	 * @param string  $div_class div层class
	 * @return null
	 */
	function out_Navigation_list($all_page, $now_page, $url, $all_class = '', $now_class = '', $div_id = '', $div_class = ''){
		if(!is_numeric($all_page) || $all_page < 2)
			return;
		if(empty($now_page) || $now_page < 1)
			$now_page = 1;
		echo "<div class=\"$div_class\"", !empty($div_id) ? " id=\"$div_id\"" : "", ">\n";
		for($i = 0; $i < $all_page; $i++){
			echo "\t<a href=\"", str_replace("%%", $i + 1, $url), "\" class=\"$all_class", ($i + 1 == $now_page) ? " $now_class" : "", "\">", $i + 1, "</a>\n";
		}
		echo "</div>\n";
	}

	/**
	 * 裁剪字符串
	 * @param string  $sourcestr 原始字符串
	 * @param integer $cutlength 裁剪长度
	 * @return null
	 */
	function cut_str($sourcestr, $cutlength){
		$returnstr = "";
		$i = 0;
		$n = 0;
		$str_length = strlen($sourcestr); //字符串的字节数
		while(($n < $cutlength) and ($i <= $str_length)){
			$temp_str = substr($sourcestr, $i, 1);
			$ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
			if($ascnum >= 224){ //如果ASCII位高与224，
				$returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
				$i = $i + 3; //实际Byte计为3
				$n++; //字串长度计1
			}
			elseif($ascnum >= 192){ //如果ASCII位高与192，
				$returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
				$i = $i + 2; //实际Byte计为2
				$n++; //字串长度计1
			}
			elseif($ascnum >= 65 && $ascnum <= 90){ //如果是大写字母，
				$returnstr = $returnstr . substr($sourcestr, $i, 1);
				$i = $i + 1; //实际的Byte数仍计1个
				$n++; //但考虑整体美观，大写字母计成一个高位字符
			}
			else{ //其他情况下，包括小写字母和半角标点符号，
				$returnstr = $returnstr . substr($sourcestr, $i, 1);
				$i = $i + 1; //实际的Byte数计1个
				$n = $n + 0.5; //小写字母和半角标点等与半个高位字符宽…
			}
		}
		if($returnstr != $sourcestr){
			$returnstr = $returnstr . "..."; //超过长度时在尾处加上省略号
		}
		return $returnstr;
	}

	/**
	 * 设置页面ID，导航菜单中用到
	 *
	 * @param string $id  必须参数
	 * @param string $id2 可选
	 * @return null
	 */
	function set_page_id($id, $id2 = ''){
		global $zxtmp;
		$zxtmp->set_page_id($id, $id2);
	}

	/**
	 * 添加JQuery内容到页面
	 *
	 * @param string $path 文件相对于JQ文件夹路径
	 * @param string $type 文件加载类型,first为首先加载
	 * @return null
	 */
	function add_jquery($path, $type = 'other'){
		global $zxtmp;
		if($type != 'first')
			$type = 'other';
		switch(pathinfo($path, PATHINFO_EXTENSION)){
			case 'css':
				$zxtmp->add_header_other("\t<link rel=\"stylesheet\" href=\"" . site_url() . "jquery/$path\" />\n", $type);
				break;
			case 'js':
				$zxtmp->add_header_other("\t<script type=\"text/javascript\" src=\"" . site_url() . "jquery/$path\"></script>\n", $type);
				break;
		}
	}

?>
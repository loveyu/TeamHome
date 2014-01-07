<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');
/**
 *	用户中心函数调用模块
 *	该文件在调用用户中心的头文件中调用
 *
 *	@file	template/admin_function.php
 *
 */
 
//--------------添加后台菜单--------------------------------
$zxtmp->add_admin_menu(
	'user-center',
	array('url'=>'index.php','name'=>'用户中心','power'=>array(0,1,2),'id'=>'user'),
	array(
		array('url'=>'edit.php','name'=>'编辑个人资料','id'=>'user_edit','power'=>array(0,1,2)),
		array('url'=>'edit_pwd.php','name'=>'修改密码','id'=>'user_pwd','power'=>array(0,1,2)),
		array('url'=>'edit_img.php','name'=>'修改头像','id'=>'user_img','power'=>array(0,1,2)),
		array('url'=>'edit_user.php','name'=>'修改邮箱及昵称','id'=>'user_name','power'=>array(0,1,2))
	)
);
$zxtmp->add_admin_menu(
	'web-center',
	array('url'=>'web.php','name'=>'个人主页','power'=>array(0,1,2),'id'=>'web'),
	array(
		array('url'=>'web_manage.php','name'=>'文件管理','id'=>'web_file','power'=>array(0,1,2)),
		array('url'=>'web_manage_page.php','name'=>'页面管理','id'=>'web_page','power'=>array(0,1,2)),
		array('url'=>'web_manage_css.php','name'=>'样式管理','id'=>'web_css','power'=>array(0,1,2)),
		array('url'=>'web_manage_img.php','name'=>'图片管理','id'=>'web_img','power'=>array(0,1,2)),
		array('url'=>'web_manage_js.php','name'=>'脚本管理','id'=>'web_js','power'=>array(0,1,2))
	)
);
$zxtmp->add_admin_menu(
	'category-center',
	array('url'=>'category.php','name'=>'分类管理','power'=>array(0,1),'id'=>'category'),
	array(
		array('url'=>'category_news.php','name'=>'新闻分类','id'=>'category_news','power'=>array(0)),
		array('url'=>'category_link.php','name'=>'链接分类','id'=>'category_link','power'=>array(0)),
	)
);
$zxtmp->add_admin_menu(
	'news-center',
	array('url'=>'news.php','name'=>'新闻中心','power'=>array(0,1),'id'=>'news'),
	array(
		array('url'=>'news_post.php','name'=>'发布新闻','id'=>'news_post','power'=>array(0,1)),
		array('url'=>'category_news.php','name'=>'新闻分类','id'=>'news_cat','power'=>array(0)),
	)
);
$zxtmp->add_admin_menu(
	'user-manage-center',
	array('url'=>'user_manage.php','name'=>'用户管理','power'=>array(0),'id'=>'manage'),
	array(
		array('url'=>'user_manage_new.php','name'=>'新建用户','id'=>'manage_new','power'=>array(0)),
		array('url'=>'user_manage_restpwd.php','name'=>'密码重置','id'=>'manage_restpwd','power'=>array(0)),
		array('url'=>'user_manage_avata.php','name'=>'编辑用户头像','id'=>'manage_avata','power'=>array(0)),
		array('url'=>'user_manage_edit.php','name'=>'编辑用户信息','id'=>'manage_edit','power'=>array(0)),
		array('url'=>'user_manage_delete.php','name'=>'删除用户','id'=>'manage_delete','power'=>array(0)),
		array('url'=>'user_manage_unlock.php','name'=>'解锁用户','id'=>'manage_unlock','power'=>array(0)),
		array('url'=>'user_manage_user.php','name'=>'修改用户邮箱和名称','id'=>'manage_name','power'=>array(0)),
		array('url'=>'user_manage_power.php','name'=>'修改用户权限','id'=>'manage_power','power'=>array(0))
	)
);

$zxtmp->add_admin_menu(
	'file-center',
	array('url'=>'file_manage.php','name'=>'文件管理','power'=>array(0),'id'=>'file'),
	array(
		array('url'=>'file_style.php','name'=>'样式管理','id'=>'file_style','power'=>array(0)),
	)
);

$zxtmp->add_admin_menu(
	'group-center',
	array('url'=>'group_manage.php','name'=>'分组管理','power'=>array(0),'id'=>'group'),
	array(
		array('url'=>'group_new.php','name'=>'新建分组','id'=>'group_new','power'=>array(0)),
		array('url'=>'group_edit.php','name'=>'编辑分组','id'=>'group_edit','power'=>array(0)),
		array('url'=>'group_delete.php','name'=>'删除分组','id'=>'group_delete','power'=>array(0)),
	)
);
$zxtmp->add_admin_menu(
	'link-center',
	array('url'=>'link.php','name'=>'链接管理','power'=>array(0),'id'=>'link'),
	array(
		array('url'=>'link_new.php','name'=>'新建链接','id'=>'link_new','power'=>array(0)),
		array('url'=>'link_setting.php','name'=>'链接设置','id'=>'link_setting','power'=>array(0)),
		array('url'=>'category_link.php','name'=>'链接分类','id'=>'category_link','power'=>array(0))
	)
);
$zxtmp->add_admin_menu(
	'plugin-center',
	array('url'=>'plugin.php','name'=>'插件管理','power'=>array(0),'id'=>'plugin'),
	array()
);
$zxtmp->add_admin_menu(
	'setting-center',
	array('url'=>'setting.php','name'=>'设置中心','power'=>array(0),'id'=>'setting'),
	array(
		array('url'=>'setting_about.php','name'=>'团队信息','id'=>'setting_about','power'=>array(0)),
		array('url'=>'setting_mail.php','name'=>'邮件设置','id'=>'setting_mail','power'=>array(0)),
		array('url'=>'setting_cache.php','name'=>'缓存设置','id'=>'setting_cache','power'=>array(0))
	)
);
//------------------后台菜单结束-----------------------------------------------


/**
 *	返回学校的专业分组
 *
 *	@param	null
 *	@return array
 */
function get_major_array(){
	return array(
		'信息科学与技术学部'=>array(
			'电子信息工程','通信工程','计算机科学与技术','光电信息工程','软件工程','自动化','生物技术工程' ,'电子科学与技术' ,'计算机应用技术(专)'
		),
		'城市建设工程学部'=>array(
			'艺术设计','建筑学','城市规划与设计','工程管理','环境工程','土木工程','建筑环境与设备工程','给水排水工程','物业管理(专)','房地产经营与估价(专)','建筑工程技术(专)'        
		),
		'机械与电气工程学部'=>array(
			'电气工程及其自动化','机械设计制造及其自动化','热能与动力工程','材料成型及控制工程','船舶与海洋工程','机械设计与制造(专)','应用电子技术(专)'
		),
		'经济管理学部'=>array(
			'金融学','电子商务(本)','公共事业管理(本)','国际经济与贸易','工商管理','会计学','财务管理(专)','公共事业管理(专)','市场营销(专)','电子商务(专)'
		),
		'人文与社会科学学部'=>array(
			'广告学','新闻学(本)','法学','汉语言文学','对外汉语','新闻学(专)','影视广告(专)','文秘(专)','中英文秘书(专)'
		),
		'外语学部'=>array(
			'英语(本)','日语','应用英语(专)','英语(专)'
		)
	);
}
?>
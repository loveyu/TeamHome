<?php
/**
 *	用户管理中心
 *
 *	@file	user/user_manage.php
 *
 */
require_once("../init.php");
if(!$zxuser->auto_login())$zxuser->redirect_to_login();
if(!is_admin())forbid_page();//非管理员禁止访
require_once('init_user.php');//用户中心初始化
$zxtmp->set_page_id('manage');

get_admin_header(array(
	'title' => '用户管理中心',
	'link' => array(
		array('rel'=>'stylesheet','type'=>'text/css','href'=>'user_center.css')
	),
	'script' => array(
		array('src'=>site_url('js/admin.js'), 'type'=>"text/javascript")
	)
));
$i=0;
?>
<div id="user_manage">
<h3>用户列表</h3>
<table class="list">
<thead>
<tr><th>ID</th><th>昵称</th><th>邮箱</th><th>姓名</th><th>权限</th><th>年级</th><th>专业班级</th><th>状态</th><th>操作</th></tr>
</thead>
<tbody>
<?php foreach($user->get_user_list(GET('page')) as $v){?>
<tr class="tr_<?php echo (++$i)%2;?>">
<td><?php echo $v['id']?></td><td><?php echo $v['username']?></td><td><?php echo $v['user']?></td><td><?php echo $v['name']?></td>
<td><?php echo '<a href="user_manage_power.php?id='.$v['id'].'">'.$v['power']?></a></td>
<td><?php echo $v['grade']?></td><td><?php echo $v['subject'],$v['class']?></td><td><?php
if($v['lock']=='true')echo '<a href="user_manage_unlock.php?type=unlock&id='.$v['id'].'" title="解锁账户">锁定</a> ';
else echo '<a href="user_manage_unlock.php?type=lock&id='.$v['id'].'" title="锁定账户">未锁定</a> ';

echo '<a href="user_manage_status.php?type=active&id='.$v['id'].'" title="改变激活状态">'.(($v['active']=='false')?"未激活":"已激活").'</a> ';

echo '<a href="user_manage_status.php?type=verify&id='.$v['id'].'" title="改变激活状态">'.(($v['verify']=='false')?"未验证":"已验证").'</a> ';

 ?></td><td><a href="user_manage_edit.php?id=<?php echo $v['id']?>">编辑</a> <a href="user_manage_delete.php?type=del&id=<?php echo $v['id']?>">删除</a> <a href="user_manage_avata.php?id=<?php echo $v['id']?>">修改头像</a> <a href="user_manage_user.php?&id=<?php echo $v['id']?>">修改昵称与邮箱</a></td></tr>
<?php }?>
<tbody>
</table>
</div>
<?php get_admin_footer();?>
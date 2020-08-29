<?php

/**
 *  用户编辑个人邮箱和用户名
 *
 * @file  user/edit_user.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
if (!$zxuser->is_active()) {
    redirect_active();
}
require_once('init_user.php');//用户中心初始化
load('session');
$session = new Session();
$zxtmp->set_page_id('user', 'name');

$err = '';
$error = [];
if (is_post() && POST('id') == get_user_id()) {
    if ((strlen(POST('username')) > 1 && $user->is_true_username(POST('username')) && POST('username') != get_user_name()) || (is_mail(POST('email')) && get_user_email() != POST('email'))) {
        if (!$session->email_check("v_email", POST('vemail'), get_user_email())) {
            $err = '当前邮箱验证码不正确';
        }
    } else {
        $err = '表单有误，请检查';
    }
    if ($err == '') {
        //存在数据更改且当前邮箱验证通过
        if (strlen(POST('username')) > 1) {
            //更改用户名
            $error[0] = $user->edit_username(get_user_id(), POST('username'));
        }
        if (is_mail(POST('email')) && get_user_email() != POST('email')) {
            //更改邮箱地址
            if (!$session->email_check("n_email", POST('vnemail'), POST('email'))) {
                $err = '新邮箱验证码不正确';
            }
            $error[1] = $user->edit_email(get_user_id(), POST('email'));
        }
        foreach ($error as $v) {
            if ($v != 'OK') {
                $err = 'err';
                break;
            }
        }//判断是否所有操作成功
        if ($err == '') {
            $err = 'OK';
        }
    }
}
if ($err == 'OK') {
    redirect(site_url('user/edit_user.php?type=OK'), 'refresh');
}

get_admin_header([
    'title'  => '修改账号信息',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
        ['src' => site_url('js/user_center_username_email_verify.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <div id="user_center">
    <h3>修改用户邮箱及用户名</h3>
    <form id="edit_user_form" class="eidt_user" action="edit_user.php" method="post">
        <?php if (GET('type') == 'OK') {
            echo '<p class="notice">账户信息修改成功</p>';
        } ?>
        <?php if (!empty($err)) {
            echo '<p class="error_notice">', $err, '</p>';
        } ?>

      <div id="user_v_email">
        <ul class="all">
          <li>当前邮箱:<span><?php echo get_user_email(); ?></span>
            <button id="get_user_email_code" type="button">获取当前邮箱验证码</button>
          </li>
          <li><label for="vemail">填写邮箱验证码:<input id="vemail" name="vemail" value="" type="text"/><span class="require">(必填)</span></label>
          </li>
        </ul>
      </div>
      <div class="user_edit_username">
        <label for="edit_user_name_check_box"><input id="edit_user_name_check_box" value="1"
                                                     type="checkbox"/>修改用户名</label>
        <ul class="edit_user_action edit">
          <li>当前用户名:<span><?php echo get_user_name(); ?></span></li>
          <li><label for="new_username">修改为:<input id="new_username" name="username" value="" type="text"/></label><span
              id="username_info" class="span_notice">不同的用户名</span></li>
        </ul>
      </div>
      <div class="user_edit_email">
        <label for="edit_user_email_check_box"><input id="edit_user_email_check_box" value="1"
                                                      type="checkbox"/>修改邮箱</label>
        <ul class="edit_email_action edit">
          <li><label for="email">修改为:<input id="email" name="email" value="" type="text"/><span id="email_info"
                                                                                                class="span_notice">不同的邮箱</span>
          </li>
          <li>获取新的验证码:
            <button id="get_new_email_code" type="button">发送验证码</button>
          </li>
          <li><label for="vnemail">验证码:<input name="vnemail" id="vnemail" value="" type="text"/></label><span
              id="vnemail_info"></span></li>
        </ul>
      </div>
      <button type="submit" class="submit">修改信息</button>
      <p class="help">当某一栏显示时即可修改该数据的值，必须从邮箱获取相应的验证码才能修改成功！</p>
      <input name="id" value="<?php echo get_user_id(); ?>" type="hidden"/><!-- 用户ID -->
      <input name="oemail" value="<?php echo get_user_email(); ?>" type="hidden"/><!-- 旧的邮箱地址 -->
      <input name="ousername" value="<?php echo get_user_name(); ?>" type="hidden"/><!-- 旧的用户名 -->
    </form>
  </div>
<?php get_admin_footer(); ?>
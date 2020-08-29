<?php

/**
 *  用户编辑个人详细信息
 *
 * @file  user/edit.php
 *
 */
require_once("../init.php");
if (!$zxuser->auto_login()) {
    $zxuser->redirect_to_login();
}
require_once('init_user.php');//用户中心初始化作
$zxtmp->set_page_id('user', 'edit');

load("group");
$group = new Group();
$group->get_all_group();

$major = get_major_array();//获取学校的专业信息

$info = $user->get_info();//获取用户个人信息
get_admin_header([
    'title'  => '编辑个人信息',
    'link'   => [
        ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'user_center.css'],
    ],
    'script' => [
        ['src' => site_url('js/admin.js'), 'type' => "text/javascript"],
    ],
]);
?>
  <script type="text/javascript">

    var major = Array()
    var group = Array()
    <?php
    //生成专业信息
    foreach ($major as $name => $v) {
        echo "major['$name']=new Array();\n";
        $i = 0;
        foreach ($v as $v2) {
            echo "major['$name'][$i]='$v2';\n";
            $i++;
        }
    }
    //生成专业信息
    foreach ($group->parent_info as $id => $n) {
        echo "group[$id]=new Array();\n";
        foreach ($n as $v2) {
            echo "group[$id][$v2] = '".$group->group[$v2]['name']."'\n";
        }
    }
    ?>

    $(document).ready(function () {
      var now
      var now2
      $('#faculty').change(function () {
        $('option:selected', this).each(function (index, element) {
          now = this.value
          select2 = $('[name="subject"]')
          select2.empty()
          select2.append(new Option('----选择专业----', ''))
          $.each(major[now], function (index, value) {
            select2.append(new Option(value, value))
          })
        })
      })
      $('#group1').change(function () {
        $('option:selected', this).each(function (index, element) {
          now2 = this.value
          select2 = $('[name="group2"]')
          select2.empty()
          select2.append(new Option('无', '0'))
          $.each(group[now2], function (index, value) {
            if (value != undefined) select2.append(new Option(value, index))
          })
        })
      })
    })
  </script>
  <div id="user_center">
    <h3>编辑个人信息</h3>
    <form action="edit_action.php" method="post" class="user_edit">
      <table>
        <tr>
          <th>用户名</th>
          <td><?php echo $info['username'] ?>&nbsp;(<?php echo $info['id'] ?>)</td>
        </tr>
        <tr>
          <th>邮箱</th>
          <td><?php echo $info['user'] ?></td>
        </tr>
        <tr valign="top">
          <th>姓名</th>
          <td><input name="name" value="<?php echo $info['name'] ?>" type="text"/>
            <p>请填写你的真实姓名</p></td>
        </tr>
        <tr>
          <th>性别</th>
          <td><select name="sex">
              <option value="">请选择</option>
              <option value="男"<?php if ($info['sex'] == '男') {
                  echo ' selected="selected"';
              } ?>>男
              </option>
              <option value="女"<?php if ($info['sex'] == '女') {
                  echo ' selected="selected"';
              } ?>>女
              </option>
            </select></td>
        </tr>
        <tr>
          <th>电话</th>
          <td><input name="tel" value="<?php echo $info['tel'] ?>" type="text"/></td>
        </tr>
        <tr>
          <th>短号</th>
          <td><input name="mtel" value="<?php echo $info['mtel'] ?>" type="text"/></td>
        </tr>
        <tr>
          <th>QQ</th>
          <td><input name="qq" value="<?php echo $info['qq'] ?>" type="text"/></td>
        </tr>
        <tr>
          <th>班级</th>
          <td><select name="class">
              <option value="">----选择班级----</option>
                  <?php for ($i = 1; $i < 7; $i++) { ?>
                    <option value="<?php echo $i; ?>"<?php if ($info['class'] == $i) {
                        echo ' selected="selected"';
                    } ?>><?php echo $i; ?></option>
                  <?php } ?>
            </select></td>
        </tr>
        <tr>
          <th>年级</th>
          <td><select name="grade">
              <option value="">----选择年级----</option>
                  <?php for ($i = date('Y'); $i > (date('Y') - 5); $i--) { ?>
                    <option value="<?php echo $i; ?>"<?php if ($info['grade'] == $i) {
                        echo ' selected="selected"';
                    } ?>><?php echo $i; ?></option>
                  <?php } ?>
            </select></td>
        </tr>
        <tr>
          <th>学部</th>
          <td>
            <select id="faculty" name="faculty">
              <option value="">----选择学部----</option>
                <?php foreach ($major as $name => $v) { ?>
                  <option value="<?php echo $name ?>"<?php if ($info['faculty'] == $name) {
                      echo ' selected="selected"';
                  } ?>><?php echo $name ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>专业</th>
          <td>
            <select name="subject">
              <option value="">选择专业</option>
                <?php foreach ($major[$info['faculty']] as $v) { ?>
                  <option value="<?php echo $v ?>"<?php if ($info['subject'] == $v) {
                      echo ' selected="selected"';
                  } ?>><?php echo $v ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>方向组</th>
          <td><select name="group1" id="group1">
              <option value="0">无</option>
                  <?php foreach ($group->group as $vg) {
                      if ($vg['parent'] == 0) { ?>
                        <option value="<?php echo $vg['id'] ?>"<?php if ($info['group1'] == $vg['id']) {
                            echo ' selected="selected"';
                        } ?>><?php echo $vg['name'] ?></option>
                      <?php }
                  } ?>
            </select></td>
        </tr>
        <tr>
          <th>小组</th>
          <td><select name="group2">
              <option value="0">无</option>
                  <?php if (isset($group->parent_info[$info['group1']])) {
                      foreach ($group->parent_info[$info['group1']] as $v) { ?>
                        <option value="<?php echo $v ?>"<?php if ($info['group2'] == $v) {
                            echo ' selected="selected"';
                        } ?>><?php echo $group->group[$v]['name'] ?></option>
                      <?php }
                  } ?>
            </select></td>
        </tr>
        <tr valign="top">
          <th>个人简介</th>
          <td><textarea name="describe"><?php echo $info['describe'] ?></textarea></td>
        </tr>
        <tr valign="top">
          <th>签名</th>
          <td><textarea name="sign"><?php echo $info['sign'] ?></textarea></td>
        </tr>
        <tr valign="top">
          <th>特长</th>
          <td><textarea name="specialty"><?php echo $info['specialty'] ?></textarea></td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>
            <button type="submit">保存信息</button>
          </td>
        </tr>
      </table>
      <input name="type" value="edit_one" type="hidden">
    </form>
  </div>
<?php get_admin_footer(); ?>
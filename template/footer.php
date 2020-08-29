</div>

<div class="fbg">
  <div class="fbg_resize">
    <div class="col c1">
      <h2>关于我们</h2>
      <img src="<?php echo get_style_path(); ?>images/white.jpg" width="66" height="66" alt="pix"/>

      <p>测试内容 <a href="#">了解更多</a></p>
    </div>
    <div class="col c2">
      <h2>其他院系</h2>
      <ul class="sb_menu">
          <?php out_friend_link() ?>
      </ul>
    </div>
    <div class="col c3">
      <h2>联系方式</h2>

      <p>欢迎通过以下方式联系我们获取更多信息</p>

      <p><strong>电话号码:</strong> +1234567<br/>
        <strong>地址:</strong> 123 TemplateAccess Rd<br/>
        <strong>E-mail:</strong> <a href="mailto:info@example.com">info@example.com</a></p>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="footer">
  <div class="footer_resize">
    <p class="lf">&copy; Copyright 2013 <a href="<?= site_url() ?>"><?= site_title() ?></a> All Rights Reserved. <span>Query <?= get_sql_query_count() ?>, loading <?= get_run_time() ?> seconds.</span>
    </p>
    <div class="clr"></div>
  </div>
</div>
</div>
<?php run_plugin('page_footer'); ?>
</body>
</html>
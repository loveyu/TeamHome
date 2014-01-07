<?php if(!defined('ROOT_PATH'))exit('No direct script access allowed');?>
<?php run_plugin('admin_body_footer');?>
</div><!-- admin_body -->

<div id="footer">
	<div class="content">
  	  <p>共执行 <?php echo get_sql_query_count()?> 次查询， 页面加载 <?php echo get_run_time()?> 秒。</p>
	</div>
</div><!-- footer -->

</div>
</div>
<!-- 容器结束 -->

</body>
<?php zx_footer();?>

</html>
<?php run_plugin('admin_footer');?>
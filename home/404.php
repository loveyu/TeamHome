<?php
	Cache::close();
	header("HTTP/1.1 404 Not Found");
?>
<?php get_header(array('title' => '404页面')) ?>
<div class="no_found">
	<p class="error">对不起，你要访问的页面不存在！</p>
</div>
<?php get_footer(); ?>
<?php exit; ?>
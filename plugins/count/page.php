<?php if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
} ?>
<style type="text/css">
    #PCOUNT_SEARCH {
        text-align: left;
        background-color: #FAF7ED;
        padding: 10px;
    }

    #PCOUNT_SEARCH h3 {
        font-size: 17px;
        line-height: 40px;
        color: #336;
    }

    #PCOUNT_SEARCH label {
        display: block;
        margin: 4px 0px;
    }

    #PCOUNT_SEARCH input {
        display: block;
        margin: 2px;
    }

    #PCOUNT_SEARCH button {
        padding: 5px 10px;
        margin: 4px;
    }

    #PCOUNT_SEARCH p.notice {
        border: #666 solid 1px;
        background: #D8F5B6;
        font-size: 17px;
        margin: 5px 0;
        padding: 4px;
    }
</style>
<div id="PCOUNT_SEARCH">
  <h3>搜索统计结果</h3>
  <form method="get" action="plugin_page.php">
    <label>用户ID：<input name="user" type="text" value=""/></label>
    <label>页面：<input name="page" type="text" value=""/></label>
    <label>时间：<input name="time" type="text" value=""/></label>
    <label>UA：<input name="ua" type="text" value=""/></label>
    <label>IP：<input name="ip" type="text" value=""/></label>
    <label>来路：<input name="from" type="text" value=""/></label>
    <label>类型：<select name="type">
        <option value="">全部</option>
        <option value="view">常规查看</option>
        <option value="auto_login">自动登录</option>
        <option value="post_login">输入框登录</option>
      </select>
    </label>
    <button type="submit">查询</button>

    <input type="hidden" name="id" value="count"/>
    <input type="hidden" name="p" value="all"/>
    <input type="hidden" name="m" value="count"/>
  </form>
  <p class="notice">除用户ID外，所有数据均为匹配查询，多个数据请用分号隔开</p>
</div>
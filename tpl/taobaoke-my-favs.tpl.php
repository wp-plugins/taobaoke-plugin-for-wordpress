<link rel='stylesheet' href='<?php _e_(taobaoke_css_path() . 'style.css'); ?>' type='text/css' media='all' />
<?php
if (isset($message)) {
   print  "<div style='font-weight:bold;'>$message</div>";
}
?>
<h1>已收藏商品列表 - <a href="?page=taobaoke-my-favs.php&action=autoclean">自动去除已下架商品</a></h1>
<?php
    $fav_table->render();
?>

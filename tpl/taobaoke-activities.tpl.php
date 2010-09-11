<link rel='stylesheet' href='<?php _e_(taobaoke_css_path() . 'style.css'); ?>' type='text/css' media='all' />
<?php
if (isset($message)) {
   print  "<div style='font-weight:bold;'>$message</div>";
}
?>
<h2>热销活动 - 如果你设置了“自动在文章末尾插入淘宝客活动推广”，以下活动将随机出现在你的文章末尾</h2>
<?php
    $taobaoke_activity_table->render();
?>

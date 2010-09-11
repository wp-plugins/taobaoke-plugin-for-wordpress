<h2>以下商品来自于您的 - "收藏列表"</h2>
<?php echo $message;?>
<form method="post">
  <ul>
    <li><input type="radio" checked value=1 name="taobaoke_post_type" id="taobaoke_post_type_1" /><label for="taobaoke_post_type_1">仅标题</label></li>
    <li><input type="radio" value=2 name="taobaoke_post_type" id="taobaoke_post_type_2" /><label for="taobaoke_post_type_2">仅图片(图片居左)</label></li>
    <li><input type="radio" value=3 name="taobaoke_post_type" id="taobaoke_post_type_3" /><label for="taobaoke_post_type_2">仅图片(图片居右)</label></li>
  </ul>
  <br />
  <?php
    $table->render();
  ?>
  <input type="submit" name="taobaoke_post_submit" id="taobaoke-post-submit" value="将选中的商品插入文章" />
</form>

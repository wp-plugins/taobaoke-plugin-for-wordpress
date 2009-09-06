<h2>从你的推广列表中选择商品来推广赚钱</h2>
<?php echo $message;?>
<form method="post">
  <ul>
    <li><input type="radio" value=1 name="taobaoke_post_type" id="taobaoke_post_type_1" /><label for="taobaoke_post_type_1">图片+标题</label></li>
    <li><input checked type="radio" value=2 name="taobaoke_post_type" id="taobaoke_post_type_2" /><label for="taobaoke_post_type_2">仅图片(图片居左)</label></li>
    <li><input checked type="radio" value=3 name="taobaoke_post_type" id="taobaoke_post_type_3" /><label for="taobaoke_post_type_2">仅图片(图片居右)</label></li>
  </ul>
  <br />
  <?php
    $table->render();
  ?>
  <input type="submit" name="taobaoke_post_submit" id="taobaoke-post-submit" value="将选中的商品插入文章" />
</form>

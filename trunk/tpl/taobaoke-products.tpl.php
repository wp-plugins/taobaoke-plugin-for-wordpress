<link rel='stylesheet' href='<?php _e_(taobaoke_css_path() . 'style.css'); ?>' type='text/css' media='all' />
<table width="98%">
  <tr>
    <td align="center">
      <form method="get">
        <input type="text" size="40" name="taobaoke_item_search" id="taobaoke_item_search" />
        <input class="button" style="font-weight:bold" type="submit"  value="淘宝一下" />
        <span style="font-size:10px;font-style:italic;">留空搜索全部...</span>
        <?php if (count($query_string)):?>
          <?php foreach($query_string as $key => $value):
                echo "<input type='hidden' name='{$key}' value='{$value}' />";
          ?>
          <?php endforeach;?>
        <?php endif;?>
      </form>
    </td>
  </tr>
</table>
<?php if ($has_cats):?>
    <h2>选择您想要推广的分类</h2>
    <?php
        $taobaoke_cats_table->render();
    ?>
<?php endif; ?>
<?php if ($has_items): ?>
    <h2>选择您想要推广的商品 - <?php if (!empty($taobaoke_cur_cat)):?><span style="color:blue;">当前分类为：<?php _e_($taobaoke_cur_cat);?></span><?php endif;?></h2>
    <?php
        $taobaoke_item_table->render();
    ?>
<?php endif;?>

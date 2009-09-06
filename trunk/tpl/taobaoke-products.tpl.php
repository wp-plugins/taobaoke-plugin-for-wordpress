<link rel='stylesheet' href='<?php _e_(taobaoke_css_path() . 'style.css'); ?>' type='text/css' media='all' />
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

<link rel='stylesheet' href='<?php _e_(taobaoke_css_path() . 'style.css'); ?>' type='text/css' media='all' />

<div class="metabox-holder">
  <div class="postbox-container" style="width:90%;">
    <div class="meta-box-sortables ui-sortablei" style="min-height:50px;">

      <!-- box begin -->
        <div id="taobaoke-settings" class="postbox">
          <div class="handlediv" title="显示/隐藏"><br /></div>
          <h3 class="hndle"><span>商品搜索</span></h3>
          <div class="inside">
          <!--box content begin-->
<table width="98%">
  <tr>
    <td align="left">
      <form method="get">
        <input type="text" size="40" value="<?php if (!empty($_GET['taobaoke_item_search'])) print $_GET['taobaoke_item_search'];?>" name="taobaoke_item_search" id="taobaoke_item_search" />
        <input class="button" style="font-weight:bold" type="submit"  value="淘宝一下" />
        <span>热词推荐：
        <?php foreach ($hots as $hot): ?>
        <a href="?taobaoke_item_search=<?php print $hot;?>&page=taobaoke-products.php"><?php print $hot;?></a>
        <?php endforeach;?>
        </span>
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
 <!-- box content end -->  
          </div>
        </div>
      <!-- box end-->

    </div>
  </div>
</div>

<div class="metabox-holder">
  <div class="postbox-container" style="width:98%;">
    <div class="meta-box-sortables ui-sortable">

      <!-- box begin -->
        <div id="taobaoke-settings" class="postbox" style="display:block">
          <div class="handlediv" title="显示/隐藏"><br /></div>
          <h3 class="hndle"><span>选择你要推广的分类 - <?php _e_($taobaoke_cur_cat);?></span></h3>
          <div class="inside">
          <!--box content begin-->
     <?php
        $taobaoke_cats_table->render();
     ?>
 <!-- box content end -->  
          </div>
        </div>
      <!-- box end-->

    </div>
  </div>
</div>

<?php if ($has_items): ?>
    <?php
        $taobaoke_item_table->render();
    ?>
<?php endif;?>

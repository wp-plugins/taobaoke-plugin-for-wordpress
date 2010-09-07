<table width="650px">
  <?php if (!empty($message)): ?>
    <tr>
      <td colspan=2 style="font-weight:bold" align="center">
        <?php if (!empty($message)) {_e_($message);} ?>
      </td>
    </tr>
  <?php endif;?>
  <?php if (!empty($shop)): ?>
    <tr>
      <td width="80%">
        <table width="100%">
          <tr align="left">
            <td rowspan="3"><a href="<?php echo $converted_shop['click_url']; ?>" target="_blank"><img style="width:100px;height:100px" src="http://logo.taobao.com/shop-logo/<?php echo $shop['pic_path'];?>"</a></td>
            <td>
              掌柜: <a style="font-weight:bold" href="<?php echo $converted_shop['click_url']; ?>" target="_blank"><?php echo $shop['nick'];?></a>
            </td>
          </tr>
          <tr>
            <td>
              店铺名: <a style="font-weight:bold" href="<?php echo $converted_shop['click_url']; ?>" target="_blank"><?php echo $shop['title'];?></a>
            </td>
          </tr>
        </table>
      </td>
      <td>
        <a class="button-primary" href="<?php echo $shop_fav_url ?>">加入推广列表</a><br /><br />
        <a class="button-primary" href="<?php echo $shop_promote_url;?>">侧边栏推荐</a>
      </td>
    </tr>
    <tr>
      <td colspan=2 style="font-style:italic;text-align:left">
        听听店长怎么说：>>> <br /><?php _e_($shop['bulletin']); ?>
      </td>
    </tr>
  <?php endif;?>
</table>

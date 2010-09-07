<?php if ($table_pagers): ?>
<div class="tablenav">
  <div class="tablenav-pages">
    <?php if ($show_paging): ?>
      <?php foreach ($table_pagers as $pager): ?>
         <?php if (NULL == $pager['link']): ?>
            <span class="page-numbers current"><?php echo $pager['text'];?></span>
         <?php else: ?>
              <a class="page-numbers" href="?<?php echo $pager['link'];?>"><?php echo $pager['text']; ?></a>
         <?php endif ?>
      <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<?php endif ?>

<table id="table_<?php echo $table_index; ?>" style="width:100%;<?php if ($div_column) {echo 'table-layout:fixed';} ?>" class="widefat post fixed <?php echo $table_class; ?>" cellspacing="0">
<?php if ($show_header): ?>
<thead>
  <tr class="nodrop nodrag">
    <?php foreach ($columns as $iterator => $column): ?>
        <?php if ($column['sortable']): ?>
        <th rowspan="<?php echo $column['rowspan'];?>" class="manage-column <?php echo $column['header_class'];?>" style="<?php echo $column['header_style'];?> text-align:center">
          <a href="?<?php echo $column['link'];?>">
            <?php echo $column['header'];?><?php if ($column['order'] == 'ASC'):?>
    <img src="<?php echo taobaoke_img_path() . 'arrow-up.gif';?>" alt="" />
            <?php elseif ($column['order'] == 'DESC'): ?>
    <img src="<?php echo taobaoke_img_path() . "arrow-down.gif";?>" alt="" />
            <?php endif ?>
          </a>
        </th>
        <?php else: ?>
        <th rowspan="<?php echo $column['rowspan'];?>" class="manage-column <?php echo $column['header_class'];?>" style="<?php echo $column['header_style'];?>" colspan="<?php echo $column['colspan'];?>"><?php echo $column['header'];?></th>
        <?php endif ?>
    <?php endforeach ?>
  </tr>
</thead>
<tfoot>
  <tr class="nodrop nodrag">
    <?php foreach ($columns as $iterator => $column): ?>
        <?php if ($column['sortable']): ?>
        <th rowspan="<?php echo $column['rowspan'];?>" class="manage-column <?php echo $column['header_class'];?>" style="<?php echo $column['header_style']; ?>">
          <a href="?<?php echo $column['link'];?>">
            <?php echo $column['header'];?><?php if ($column['order'] == 'ASC'):?>
    <img src="<?php echo taobaoke_img_path() . 'arrow-up.gif';?>" alt="" />
            <?php elseif ($column['order'] == 'DESC'): ?>
    <img src="<?php echo taobaoke_img_path() . "arrow-down.gif";?>" alt="" />
            <?php endif ?>
          </a>
        </th>
        <?php else: ?>
        <th rowspan="<?php echo $column['rowspan'];?>" class="manage-column <?php echo $column['header_class'];?>" style="<?php echo $column['header_style'];?>" colspan="<?php echo $column['colspan'];?>"><?php echo $column['header'];?></th>
        <?php endif ?>
    <?php endforeach ?>
  </tr>
</tfoot>
<?php endif ?>
<?php $row_index = 0; ?>
<?php foreach ($table_rows as $row_iterator => $row): ?>
<?php $row_index++; ?>
<?php if ($row_index % 2): ?>
<tr class="<?php echo $zebra[0]; ?>">
<?php else: ?>
<tr class="<?php echo $zebra[1]; ?>">
<?php endif ?>
    <?php foreach ($row as $column_iterator => $column): ?>
      <td class="<?php echo $table_columns[$column_iterator]['cell_class']; ?>" style="word-break:break-all;<?php echo $table_columns[$column_iterator]['cell_style']; ?>"><?php echo $column; ?></td>
    <?php endforeach ?>
</tr>
<?php endforeach ?>
<?php echo $no_record;?>
</table>
<?php if ($table_pagers): ?>
<div class="tablenav">
  <div class="tablenav-pages">
    <?php if ($show_paging): ?>
      <?php foreach ($table_pagers as $pager): ?>
         <?php if (NULL == $pager['link']): ?>
            <span class="page-numbers current"><?php echo $pager['text'];?></span>
         <?php else: ?>
              <a class="page-numbers" href="?<?php echo $pager['link'];?>"><?php echo $pager['text']; ?></a>
         <?php endif ?>
      <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<?php endif ?>

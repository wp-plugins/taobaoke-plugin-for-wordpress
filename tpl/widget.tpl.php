<ul>
  <li>
    标题：<input type="text" size=30 name="taobaoke_widget_title" id="taobaoke_widget_title" value="<?php _e_($widget_title);?>" />
  </li>
  <li>
    <input type="radio" <?php taobaoke_show_display_type('text'); ?> class="redio" id="taobaoke_promote_format_1" value=0 name="taobaoke_promote_format" title=='文字链形式' />
    <label for="taobaoke_promote_format_1" >文字链形式</label>
  </li>
  <li>
    <input type="radio" <?php taobaoke_show_display_type('pic'); ?> class="redio" id="taobaoke_promote_format_2" value=1 name="taobaoke_promote_format" title=='图文形式' />
    <label for="taobaoke_promote_format_2" >图文形式</label>
    <input type="hidden" name="taobaoke_widget_post" value="taobaoke_widget_post" id="taobaoke_widget_post" />
    <table>
      <tr>
        <td>
          宽度：
        </td>
        <td>
          <input type="text" size=20 name="taobaoke_widget_width" id="taobaoke_widget_width" value="<?php echo taobaoke_show_width(); ?>" /><span style="color:#A9A9A9">每个商品宽度</span>
        </td>
      </tr>
      <tr>
        <td>
          高度：
        </td>
        <td>
          <input type="text" size=20 name="taobaoke_widget_height" id="taobaoke_widget_height" value="<?php echo taobaoke_show_height();?>" /><span style="color:#A9A9A9">每个商品高度</span>
        </td>
      </tr>
      <tr>
        <td>
          选择包含元素：
        </td>
        <td>
          <br />
          <input type="radio" value=all <?php taobaoke_show_which_item('all'); ?> name="taobaoke_widget_show_item" id="taobaoke_widget_show_title" />商品图片+文字标题+价格+查看详情<br />
          <input type="radio" value=pic-title <?php taobaoke_show_which_item('pic-title'); ?> name="taobaoke_widget_show_item" id="taobaoke_widget_show_price" />商品图片+文字标题<br />
          <input type="radio" value=pic <?php taobaoke_show_which_item('pic'); ?> name="taobaoke_widget_show_item" id="taobaoke_widget_show_detail_button" />商品图片
        </td>
      </tr>
      <tr>
        <td>
          选择排版方式：
        </td>
        <td>
          <br />
          <input type="radio" <?php taobaoke_show_display_style('left-right'); ?> value=1 name="taobaoke_widget_display_format" id="taobaoke_widget_display_format_1" />左右排版(图片居左)<br />
          <input type="radio" <?php taobaoke_show_display_style('up-down'); ?> value=0 name="taobaoke_widget_display_format" id="taobaoke_widget_display_format_2" />上下排版(图片居上)
        </td>
      </tr>
      <tr>
        <td>
          标题颜色：
        </td>
        <td>
          <input type="text" value="<?php echo taobaoke_show_color('title'); ?>" size=20 name="taobaoke_widget_title_color" id="taobaoke_widget_title_color" />
        </td>
      </tr>
      <tr>
        <td>
          背景颜色：
        </td>
        <td>
          <input type="text" value="<?php echo taobaoke_show_color('bg'); ?>" size=20 name="taobaoke_widget_bg_color" id="taobaoke_widget_bg_color" />
        </td>
      </tr>
      <tr>
        <td>
          价格颜色：
        </td>
        <td>
          <input type="text" value="<?php echo taobaoke_show_color('price'); ?>" size=20 name="taobaoke_widget_price_color" id="taobaoke_widget_price_color" />
        </td>
      </tr>
      <tr>
        <td>
          边框颜色：
        </td>
        <td>
          <input type="text" size=20 value="<?php echo taobaoke_show_color('border'); ?>" name="taobaoke_widget_border_color" id="taobaoke_widget_border_color" />
          <script type="text/javascript">
            function loadColorPicker() {
              if(document.readyState != null && document.readyState != "complete") {
                  setTimeout(function(){loadColorPicker()}, 1000);
                  return;
              }

              $('#taobaoke_widget_border_color, #taobaoke_widget_bg_color, #taobaoke_widget_title_color, #taobaoke_widget_price_color').ColorPicker({
                  onSubmit: function(hsb, hex, rgb, el) {
                      $(el).val(hex);
                      $(el).ColorPickerHide();
                  },
                  onBeforeShow: function () {
                      $(this).ColorPickerSetColor(this.value);
                  }
              });

            }
            loadColorPicker();
          </script>
        </td>
      </tr>
    </table>
  </li>
</ul>

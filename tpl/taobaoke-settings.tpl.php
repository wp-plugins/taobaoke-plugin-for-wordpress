<?php
if (is_not_empty($taobaoke_message)):
    _e_("<div class='updated fade' id='message'><p>$taobaoke_message</p></div>");
endif;
?>

<h2>淘宝客</h2>

<!-- box wrap begin -->
<div class="metabox-holder">
  <div class="postbox-container" style="width:49%;">
    <div class="meta-box-sortables ui-sortable">

      <!-- box begin -->
        <div id="taobaoke-settings" class="postbox" style="display:block">
          <div class="handlediv" title="显示/隐藏"><br /></div>
          <h3 class="hndle"><span>淘宝客账户设置</span></h3>
          <div class="inside">
          <!--box content begin-->

<form method="post">
  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row">
          <label for="pid">淘宝客的PID</label>
        </th>
        <td>
          <input type="text" value="<?php _e_($taobaoke_pid);?>" name="pid" size="30"/>
          <br/>PID格式：mm_1234567_0_0
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="pid">淘宝客的用户名</label>
        </th>
        <td>
          <input type="text" value="<?php _e_($taobaoke_nickname);?>" name="nickname" size="30"/>
          <br/>你的<a href="http://taoke.alimama.com/" target="_blank">淘宝客</a>的登录用户名
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="appkey">淘宝开放平台的APP KEY</label>
        </th>
        <td>
          <input type="password" value="<?php _e_($taobaoke_appkey); ?>" name="appkey" size="30"/>
          <br/>淘宝开放平台应用服务的APP KEY，可以保持默认.<br/>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="appsecret">淘宝开放平台的APP SECRET</label>
        </th>
        <td>
          <input type="password" value="<?php _e_($taobaoke_appsecret); ?>" name="appsecret" size="40"/>
          <br/>淘宝开放平台应用服务的APP SECRET，可以保持默认.<br/>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit" style="margin-left:20px">
    <!-- <input type="submit" value="保存" name="save" class="button"/>-->
    <input type="submit" value="保存" class="button-primary" name="Taobaoke-Submit"/>
  </p>
</form>

          <!-- box content end -->  
          </div>
        </div>
      <!-- box end-->

    </div>
  </div>

  <div class="postbox-container" style="width:49%;">
    <div class="meta-box-sortables ui-sortable">

      <!-- box begin -->
        <div id="taobaoke-settings" class="postbox" style="display:block">
          <div class="handlediv" title="显示/隐藏"><br /></div>
          <h3 class="hndle"><span>商品推广设置</span></h3>
          <div class="inside">
          <!--box content begin-->
            <form method="post">
               <table class="form-table">
                <tbody>
                 <tr valign="top">
                   <th scope="row">
                    <label for="taobaoke-auto-activity-ad">在文章头智能嵌入<a href="?page=taobaoke-activities.php">淘宝客活动推广</a></label>
                   </th>
                   <td>
                   <input type="checkbox" <?php if ($taobaoke_auto_activity) print 'checked';?> value=1 name="taobaoke-auto-activity-ad" />
                   </td>
                 </tr>
                 <tr valign="top">
                   <th scope="row">
                    <label for="taobaoke-auto-product-ad">自动在文章末尾插入<a href="http://taoke.alimama.com/activity_list.htm" target="_blank">我挑选的商品</a></label>
                   </th>
                   <td>
                   <input type="checkbox" disabled <?php if ($taobaoke_auto_product) print 'unchecked'; ?> value=1 name="taobaoke-auto-product-ad" />
                   </td>
                 </tr>
                 <tr valign="top">
                   <th scope="row">
                    <label for="taobaoke-auto-keywords">自动在文章末尾插入 热销单品</label>
                   </th>
                   <td>
                   <input type="checkbox" <?php if ($taobaoke_auto_hot_products) print 'checked'; ?> value=1 name="taobaoke-auto-hot-products" />
                   </td>
                 </tr>
                 <tr valign="top">
                   <th scope="row">
                    <label for="taobaoke-sidebar-ads-count">侧边栏推广商品展示的数量</label>
                   </th>
                   <td>
                   <input type="text" value=<?php print $taobaoke_sidebar_ad_count; ?> name="taobaoke-sidebar-ads-count" />
                   </td>
                 </tr>
                 <tr>
                   <td colspan="2" style="text-align:left">
                     <p class="submit" style="margin-left:20px">
                       <input type="submit" value="保存" class="button-primary" name="Taobaoke-Auto-Ad"/>
                     </p>
                   </td>
                 </tr>
                </tbody> 
              </table>
            </form>
          <!--box content end-->
          </div>
        </div>
        <!-- box begin -->
        <div id="taobaoke-settings" class="postbox" style="display:block">
          <div class="handlediv" title="显示/隐藏"><br /></div>
          <h3 class="hndle"><span>推广关键词设置</span></h3>
          <div class="inside">
          <!--box content begin-->
            <form method="post">
               <h4>&nbsp;&nbsp;将文章中出现的以下关键词自动替换成淘宝推广关键词(空格分隔)[功能即将发布]：</h4>
               <textarea style="margin-left:10px;margin-bottom:10px;" cols=60 rows=5 name="taobaoke-keywords"><?php print $taobaoke_keywords;?></textarea>
               <p class="submit" style="margin-left:20px">
                 <input type="submit" value="保存" class="button-primary" name="Taobaoke-Auto-Keywords" />
               </p>
            </form>
          <!--box content end-->
          </div>
        </div>
    </div>
  </div>
</div>
<!-- box wrap end-->


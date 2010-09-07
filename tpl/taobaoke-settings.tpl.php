<?php
if (is_not_empty($taobaoke_message)):
    _e_("<div class='updated fade' id='message'><p>$taobaoke_message</p></div>");
endif;
?>

<form method="post">
<h2>淘宝客帐户设置</h2>
<table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row">
          <label for="pid">淘宝客的PID</label>
        </th>
        <td>
          <input type="text" value="<?php _e_($taobaoke_pid);?>" name="pid" size="100"/>
          <br/>PID格式：mm_1234567_0_0
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="pid">淘宝客的用户名</label>
        </th>
        <td>
          <input type="text" value="<?php _e_($taobaoke_nickname);?>" name="nickname" size="100"/>
          <br/>你的<a href="http://taoke.alimama.com/" target="_blank">淘宝客</a>的登录用户名
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="appkey">淘宝开放平台的APP KEY</label>
        </th>
        <td>
          <input type="password" value="<?php _e_($taobaoke_appkey); ?>" name="appkey" size="100"/>
          <br/>这里是您申请的淘宝开放平台应用服务的APP KEY，可以保持默认，使用本plugin申请的APP KEY<br/>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="appsecret">淘宝开放平台的APP SECRET</label>
        </th>
        <td>
          <input type="password" value="<?php _e_($taobaoke_appsecret); ?>" name="appsecret" size="100"/>
          <br/>这里是您申请的淘宝开放平台应用服务的APP SECRET，可以保持默认，使用本plugin申请的APP Secret<br/>
        </td>
      </tr>
    </tbody>
</table>

<p class="submit">
    <!-- <input type="submit" value="保存" name="save" class="button"/>-->
    <input type="submit" value="保存" class="button-primary" name="Taobaoke-Submit"/>
    <a target="_blank"  class="button-primary" href="http://blog.da-fang.com/index.php/淘宝客/">获取可以使用的App Key和App Secret</a>
</p>
</form>

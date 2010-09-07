<form method="post">
<table style="width:100%">
    <?php if (!empty($taobaoke_message)): ?>
    <tr>
        <td colspan=3 align="center">
            <div style="color:blue;font-weight:bold;"><?php _e_($taobaoke_message) ?></div><br />
        </td>
    </tr>
    <?php endif;?>
    <tr>
        <td rowspan=2 style="width:360px">
            <img src="<?php _e_($item_pic); ?>" width=350 valign="middle" />
        </td>
        <td align="right" style="font-weight:bold;width:100px">
            商品名称：
        </td>
        <td>
            <?php _e_($item_title) ?>
        </td>
    </tr>
    <tr>
        <td align="right" style="font-weight:bold">
            推广位置：
        </td>
        <td>
            <input type="checkbox" id="promote_position" checked=true disabled=true name="promote_position" />
            <label for="promote_position">侧边栏</label>
            <br /> <span style="color:#A9A9A9">更多选项开发中，<a href="http://blog.da-fang.com/" target="_blank">招募开发者...</a></span>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <br /><input type="submit" class="button-primary" id="taobaoke_submit_type" name="taobaoke_submit_type" value="提交" />
        </td>
    </tr>
</table>
</form>
<br />
<ul>
    <li>您已经选择推广了<span style="font-weight:bold;font-size:14px;"> <?php _e_($item_count); ?> </span>件商品，这些商品将展示在您的侧边栏</li>
</ul>

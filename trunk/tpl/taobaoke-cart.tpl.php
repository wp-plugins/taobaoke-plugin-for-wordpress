<?php
if (is_not_empty($message)):
    _e_("<br /><div style='font-weight:bold;font-size:16px;padding-left:50px;' id='message'>$message</div>");
endif;
?>
<br />
<table style="width:100%">
    <tr>
        <td style="width:210px;">
            <img style="vertical-align:middle" width=200 height=200 src="<?php _e_($item_pic); ?>" />
        </td>
        <td>
            <span style="font-weight:bold;font-size:14px;overflow:hidden;width:400px"><?php _e_($item_title); ?></span>
        </td>
    </tr>
</table>

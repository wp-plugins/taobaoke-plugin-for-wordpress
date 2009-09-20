<?php
//serialize the html to display in the sidebar
    //param 1: bgcolor
    //param 2: width
    //param 3: border color
    //param 4: click url
    //param 5: img url
    //param 6: click url
    //param 7: title
    //param 8: price color
    //param 9: price
    //param 10: click url
function taobaoke_get_ad_html() {
$html = <<<HTML
<table cellpadding="0" cellspacing="0" bgcolor="#%s" style="width:%dpx;border:1px solid #%s;">
  <tr>
    <td rowspan="2" align="center" style="width:%s;height:%spx">
      <div style="margin:5px auto; width:%spx;height:%spx;">
        <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s" style="width:%spx; margin:0px;padding:0px;height:%spx;overflow:hidden;">
          <img style="margin:0px;border:none;width:%spx;height:%spx;" src="%s">
        </a>
      </div>
      <div class="clearing"></div>
    </td>
    <td colspan="2" >
      <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"   style="height:40px;margin:5px;line-height:20px;color:#%s">
        %s
      </a>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" >
      <span style="font-weight:bold;margin:5px;line-height:30px;color:#%s;">%d 元</span>
    </td>
    <td nowrap="nowrap" width="100px">
      <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"  >
        <img style="margin:0px; pandding:0px;line-height:24px;vertical-align: text-bottom;border:none;"  src="http://img.alimama.cn/images/tbk/cps/fgetccode_btn.gif">
      </a>
    </td>
  </tr>
</table>
HTML;

    return $html;
}

function taobaoke_get_ad_html_pic_title() {
$html = <<<HTML
<table cellpadding="0" cellspacing="0" bgcolor="#%s" style="width:%dpx;border:1px solid #%s;">
  <tr>
    <td rowspan="2" align="center" style="width:%spx;height:%spx">
      <div style="margin:5px auto; width:%spx;height:%spx;">
        <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"   style="width:%spx; margin:0px;padding:0px;height:%spx;overflow:hidden;">
          <img style="margin:0px;border:none;width:%spx;height:%spx;" src="%s">
        </a>
      </div>
      <div class="clearing"></div>
    </td>
    <td colspan="2" >
      <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"   style="height:40px;margin:5px;line-height:20px;color:#%s">
        %s
      </a>
    </td>
  </tr>
</table>
HTML;

    return $html;
}

function taobaoke_get_ad_pic() {
    $html = <<<HTML
<table cellpadding="0" cellspacing="0" bgcolor="#%s" style="width:%dpx;border:1px solid #%s;">
  <tr>
    <td align="center">
      <div style="margin:5px auto; width:%dpx;height:%dpx;">
        <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"   style="width:%dpx; margin:0px;padding:0px;height:%dpx;overflow:hidden;">
          <img style="margin:0px;border:none;width:%dpx;height:%dpx;" src="%s">
        </a>
      </div>
      <div class="clearing"></div>
    </td>
  </tr>
</table>
HTML;

    return $html;
}

function taobaoke_get_ad_raw_text() {
    $html = <<<HTML
    <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" style="line-height:20px" href="%s"  >%s</a>
HTML;

    return $html;
}

function taobaoke_get_post_item_html() {
    //
    $html = <<<HTML
    <a class="taobaoke-status-tracking-by-gotall-net %s" href="%s" target="_blank"><img class="%s size-thumbnail" title="%s" src="%s" alt="%s" width="150" height="150" /></a>
HTML;

    return $html;
}

function taobaoke_get_post_item_html_full() {
    $html = <<<HTML
<table cellpadding="0" cellspacing="0" bgcolor="#%s" style="width:%dpx;border:1px solid #%s;"><tr><td rowspan="2" align="center" style="height:82px"><div style="margin:5px auto; width:80px;height:80px;"><a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s" style="width:80px; margin:0px;padding:0px;height:80px;overflow:hidden;"><img style="margin:0px;border:none;width:80px;height:80px;" src="%s"></a></div><div class="clearing"></div></td><td colspan="2" ><a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s" style="height:40px;margin:5px;line-height:20px;color:#0000FF">%s</a></td></tr><tr><td nowrap="nowrap" ><span style="font-weight:bold;margin:5px;line-height:30px;color:#%s;">%d 元</span></td><td nowrap="nowrap" width="100px"><a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s"  ><img style="margin:0px; pandding:0px;line-height:24px;vertical-align: text-bottom;border:none;"  src="http://img.alimama.cn/images/tbk/cps/fgetccode_btn.gif"></a></td></tr></table>
HTML;

    return $html;
}

function taobaoke_get_shop_sidebar_promote() {
    $html = <<<HTML
    <table cellpadding="0" cellspacing="0" bgcolor="#%s" style="width:%dpx;border: 1px solid #%s;">
      <tr>
        <td rowspan="2" align="center">
          <div style="margin:5px auto; width: %spx;height:%spx;">
            <a target="_blank" href="%s" style="width: %spx; margin:0px;padding:0px;height: %spx; overflow:hidden;">
              <img style="width:%spx;height:%spx;margin:0px;border:none;" src="%s">
            </a>
          </div>
          <div class="clearing"></div>
        </td>
        <td colspan="2" >
          <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s" style="height:40px;width:%dpx;margin:5px;line-height:20px;color:#%s">%s</a>
        </td>
      </tr>
    </table>
HTML;

    return $html;
}

function taobaoke_get_shop_sidebar_promote_text() {
    return <<<HTML
    <a class="taobaoke-status-tracking-by-gotall-net %s" target="_blank" href="%s" style="height:40px;width:%dpx;margin:5px;line-height:20px;color:#%s">%s</a>
HTML;
}

<?php
define('TAO_PATH', dirname(__FILE__) . '/');

define('APP_KEY', '12001725');
define('APP_SECRET', 'd37bfd74179a2505d1e8c47e8c87a638');
define('TOP_URL', 'http://gw.api.taobao.com/router/rest');
//define('TOP_URL', 'http://gw.sandbox.taobao.com/router/rest');
define('TOP_PID', 'mm_13770637_0_0');
define('TOP_NICK', 'wyattfang');

define('TAOBAOKE_CART_TABLE', 'taobaoke_cart');
define('TAOBAOKE_PROMOTE_TABLE', 'taobaoke_promote');

include_once (TAO_PATH . 'libs/functions.php');

//global settings
define('TAOBAOKE_SIDEBAR_TITLE_COLOR', '0000FF');
define('TAOBAOKE_SIDEBAR_BG_COLOR', 'FFFFFF');
define('TAOBAOKE_SIDEBAR_PRICE_COLOR', 'CC0000');
define('TAOBAOKE_SIDEBAR_BORDER_COLOR', 'E6E6E6');
define('TAOBAOKE_SIDEBAR_WIDTH', 250);
define('TAOBAOKE_SIDEBAR_HEIGHT', 90);
?>

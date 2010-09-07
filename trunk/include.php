<?php
define('TAO_PATH', dirname(__FILE__) . '/');

define('APP_KEY', '12004702');
define('APP_SECRET', '08a096729647d3db476bcddfe06dbc97');
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

define('DEBUG', false);

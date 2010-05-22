<?php
define ('TAOBAOKE_HOOK_MENU', 'dispatch_page');

function taobaoke_option_menu() {
    if (function_exists('add_options_page')) {
        //add_options_page('淘宝客设置页面', '淘宝客', 9, 'php/taobaoke-settings.php', 'dispatch_page');
        add_menu_page('淘宝客', '淘宝客', 'use taobaoke', 'taobaoke-settings.php', 'dispatch_page');
        add_submenu_page('taobaoke-settings.php', '淘宝客设置', '淘宝客设置', 'use taobaoke', 'taobaoke-settings.php', TAOBAOKE_HOOK_MENU);
        add_submenu_page('taobaoke-settings.php', '淘宝客商品', '淘宝客商品挑选', 'use taobaoke', 'taobaoke-products.php', TAOBAOKE_HOOK_MENU);
        add_submenu_page('taobaoke-settings.php', '我的商品推广', '我的商品推广', 'use taobaoke', 'taobaoke-my-products.php', TAOBAOKE_HOOK_MENU);
        add_submenu_page('taobaoke-settings.php', '我的商品推广', '我的推广统计', 'use taobaoke', 'taobaoke-my-analyse.php', TAOBAOKE_HOOK_MENU);

        $page = add_submenu_page('taobaoke-settings.php', '<span style="display:none">推广操作</span>', '<span style="display:none">推广操作</span>', 9, 'taobaoke-actions.php', TAOBAOKE_HOOK_MENU);
        add_action('admin_print_styles-' . $page, 'taobaoke_plugin_admin_styles');
        //add_action('admin_print_scripts-' . $page, 'taobaoke_plugin_admin_script');
    }
}

function taobaoke_plugin_admin_styles() {
    wp_register_style('taobaoke_css', taobaoke_css_path() . 'hide-admin.css', null, '1.5');

    wp_enqueue_style('taobaoke_css');
}

function dispatch_page() {
    define('TAOBAOKE_PHP_PATH', 'php/');
    define('TAOBAOKE_TPL_PATH', 'tpl/');

    $page = empty($_GET['page']) ? 'taobaoke-settings.php' : $_GET['page'];

    if (file_exists(TAO_PATH . TAOBAOKE_PHP_PATH . $page)) {
        include_once (TAO_PATH . TAOBAOKE_PHP_PATH . $page);

        $vars = array();
        if (function_exists('display_page')) {
            $vars = display_page();//每个页面都需要定义该函数
            if (!is_not_empty($vars)) {
                $vars = array();
            }
        }
        else {
            echo '页面' . $page . '没有定义函数display_page';
        }
        //print_var($vars);
        $tpl_page = substr($page, 0, strlen($page) - 4) . '.tpl.php';
        if (file_exists(TAO_PATH . TAOBAOKE_TPL_PATH . $tpl_page)) {
            extract($vars);

            include_once (TAO_PATH . TAOBAOKE_TPL_PATH . $tpl_page);
        }
    }
    else {
        echo '文件 ' . TAO_PATH . $page . ' 不存在...';//DOTO
    }
}
?>

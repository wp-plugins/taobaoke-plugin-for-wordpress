<?php
function taobaoke_activate_plugin() {
    var_set('appkey', APP_KEY);
    var_set('appsecret', APP_SECRET);
    var_set('url', TOP_URL);
    var_set('pid', TOP_PID);

    $site_url = get_bloginfo('wpurl');
    $site_name = get_bloginfo('name');
    $admin_email = 'mail@da-fang.com';
    taobaoke_anaylysis(array('type' => 'install', 'site_url' => $site_url, 'site_name' => $site_name, 'admin_email' => $admin_email));
    taobaoke_install_db();

    global $wp_roles;
    $roles = $wp_roles->get_names();
    foreach ($roles as $role_name => $name) {
        $role_object = get_role($role_name);

        $role_object->add_cap('use taobaoke');
    }

    wp_schedule_event(time(), 'daily', 'taobaoke_auto_sync');
}

function taobaoke_install_db() {
    global $wpdb;

    $v = get_option('taobaoke_db_version', '0.1');

    $cart_table_name = $wpdb->prefix . TAOBAOKE_CART_TABLE;
    $promote_table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;
    $keywords_table_name = $wpdb->prefix . TAOBAOKE_AUTO_KEYWORDS;
    $hot_keywords_table_name = $wpdb->prefix . TAOBAOKE_HOT_KEYWORDS;

    if ($v != TAOBAOKE_DB_V) {
        $sql = "CREATE TABLE " . $cart_table_name . " (
        user_id bigint(20) NOT NULL,
        item_id varchar(50) NOT NULL,
        item_title varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_pic varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_price varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_url varchar(1000) character set utf8 collate utf8_unicode_ci NOT NULL,
        add_time datetime NOT NULL default '0000-00-00 00:00:00',
        update_time datetime NOT NULL default '0000-00-00 00:00:00',
        PRIMARY KEY (user_id, item_id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);

        $promote_sql = "CREATE TABLE " . $promote_table_name . " (
        user_id bigint(20) NOT NULL,
        item_id varchar(50) NOT NULL,
        item_title varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_pic varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_price varchar(50) NOT NULL,
        item_url varchar(1000) character set utf8 collate utf8_unicode_ci NOT NULL,
        item_html varchar(3000) character set utf8 collate utf8_unicode_ci NOT NULL,
        promote_type enum('sidebar', 'footer', 'header') NOT NULL DEFAULT 'sidebar',
        add_time datetime NOT NULL default '0000-00-00 00:00:00',
        update_time datetime NOT NULL default '0000-00-00 00:00:00',
        PRIMARY KEY (user_id, item_id, promote_type)
        );";

        dbDelta($promote_sql);

        $auto_keyword_sql = "CREATE TABLE " . $keywords_table_name . " (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        keyword varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
        click_url varchar(10000) character set utf8 collate utf8_unicode_ci NOT NULL,
        add_time datetime NOT NULL default '0000-00-00 00:00:00',
        update_time datetime NOT NULL default '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        );";

        dbDelta($auto_keyword_sql);

        $hot_keyword_sql = "CREATE TABLE " . $hot_keywords_table_name . " (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        keyword varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
        click_url varchar(10000) character set utf8 collate utf8_unicode_ci NOT NULL,
        add_time datetime NOT NULL default '0000-00-00 00:00:00',
        update_time datetime NOT NULL default '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        );";

        dbDelta($hot_keyword_sql);
    }

    update_option('taobaoke_db_version', TAOBAOKE_DB_V);
}

function taobaoke_deactivate_plugin() {
    var_delete('appkey', APP_KEY);
    var_delete('appsecret', APP_SECRET);
    var_delete('url', TOP_URL);
    var_delete('pid', TOP_PID);

    $site_url = get_bloginfo('wpurl');
    $site_name = get_bloginfo('name');
    $admin_email = 'mail@da-fang.com';

    taobaoke_anaylysis(array('type' => 'uninstall', 'site_url' => $site_url, 'site_name' => $site_name, 'admin_email' => $admin_email));

    global $wp_roles;
    $roles = $wp_roles->get_names();
    foreach ($roles as $role_name => $role_desplay_name) {
        $role_object = get_role($role_name);

        $role_object->remove_cap('use taobaoke');
    }

    wp_clear_scheduled_hook('taobaoke_auto_sync');
}
?>

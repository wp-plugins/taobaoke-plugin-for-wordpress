<?php
function taobaoke_activate_plugin() {
    var_set('appkey', APP_KEY);
    var_set('appsecret', APP_SECRET);
    var_set('url', TOP_URL);
    var_set('pid', TOP_PID);

    $site_url = get_bloginfo('wpurl');
    $site_name = get_bloginfo('name');
    $admin_email = get_bloginfo('admin_email');

    taobaoke_anaylysis(array('type' => 'install', 'site_url' => $site_url, 'site_name' => $site_name, 'admin_email' => $admin_email));
    taobaoke_install_db();

    global $wp_roles;
    $roles = $wp_roles->get_names();
    foreach ($roles as $role_name => $name) {
        $role_object = get_role($role_name);

        $role_object->add_cap('use taobaoke');
    }
}

function taobaoke_install_db() {
    global $wpdb;

    $cart_table_name = $wpdb->prefix . TAOBAOKE_CART_TABLE;
    if ($wpdb->get_var("SHOW TABLES LIKE '$cart_table_name'") != $cart_table_name) {
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
    }

    $promote_table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;
    if ($wpdb->get_var("SHOW TABLES LIKE '$promote_table_name'") != $promote_table_name) {
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

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($promote_sql);
    }
}

function taobaoke_deactivate_plugin() {
    var_delete('appkey', APP_KEY);
    var_delete('appsecret', APP_SECRET);
    var_delete('url', TOP_URL);
    var_delete('pid', TOP_PID);

    $site_url = get_bloginfo('wpurl');
    $site_name = get_bloginfo('name');
    $admin_email = get_bloginfo('admin_email');

    taobaoke_anaylysis(array('type' => 'uninstall', 'site_url' => $site_url, 'site_name' => $site_name, 'admin_email' => $admin_email));

    global $wp_roles;
    $roles = $wp_roles->get_names();
    foreach ($roles as $role_name => $role_desplay_name) {
        $role_object = get_role($role_name);

        $role_object->remove_cap('use taobaoke');
    }
}
?>

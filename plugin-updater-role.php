<?php
/*
Plugin Name: Plugin Updater Role
Plugin URI:  https://github.com/frankymelero/wp-plugin-updater-role
Description: This plugin adds the "Plugin Updater" role wich allows the user to only list and update plugins. Additionaly the admin can set up an IP restriction for users with this role.
Version:     0.2
Author:      Franky Melero
Author URI:  https://github.com/frankymelero
License:     GPL2
*/

// Creates a custom role.
function pur_create_plugin_manager_role()
{
    remove_role('plugin_updater');

    add_role(
        'plugin_updater',
        'Plugin Updater',
        array(
            'read' => true,
            'update_plugins' => true,
            'activate_plugins' => true,
            'install_plugins' => false,
            'delete_plugins' => false,
            'edit_plugins' => false,
        )
    );
}
add_action('init', 'pur_create_plugin_manager_role');

// Restricts access.
function pur_restrict_admin_access() {
    $user = wp_get_current_user();

    if (in_array('plugin_updater', (array) $user->roles)) {

        $allowed_pages = [
            'plugins.php',
            'update.php',
        ];

        $current_page = basename($_SERVER['PHP_SELF']);

        if (!in_array($current_page, $allowed_pages)) {
            wp_redirect(admin_url('plugins.php'));
            exit;
        }
    }
}
add_action('admin_init', 'pur_restrict_admin_access');

// Removes activation/deactivation links from the plugins list.
function pur_remove_activation_links($actions, $plugin_file, $plugin_data, $context)
{
    $user = wp_get_current_user();

    if (in_array('plugin_updater', (array) $user->roles)) {
        if (isset($actions['activate'])) {
            unset($actions['activate']);
        }
        if (isset($actions['deactivate'])) {
            unset($actions['deactivate']);
        }
    }

    return $actions;
}
add_filter('plugin_action_links', 'pur_remove_activation_links', 10, 4);

// Blocks plugin activation/deactivation through direct actions
function pur_block_plugin_activation()
{
    $user = wp_get_current_user();

    if (in_array('plugin_updater', (array) $user->roles)) {
        if (isset($_GET['action']) && in_array($_GET['action'], ['activate', 'deactivate'])) {
            wp_die('You are not allowed to activate/deactivate plugins.');
        }
    }
}
add_action('admin_init', 'pur_block_plugin_activation');

// Adds a link in the plugin page
function pur_add_settings_link($links)
{
    if (current_user_can('manage_options')) {
        $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=pur-settings')) . '">Settings</a>';
        array_push($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pur_add_settings_link');

require_once plugin_dir_path(__FILE__) . 'includes/pur-settings.php';

// Adds a submenu  Plugins menu,
function pur_add_plugin_settings_page()
{
    add_submenu_page(
        'plugins.php',
        'Plugin Updater Role Settings',
        'Plugin Updater Role Settings',
        'manage_options',
        'pur-settings',
        'pur_display_ip_settings'
    );
}
add_action('admin_menu', 'pur_add_plugin_settings_page');

//Restricts login to the accounts with Plugin Updater role by IP if configured.
function pur_restrict_login_by_ip($user, $password)
{

    if (in_array('plugin_updater', (array) $user->roles)) {
        $enable_ip_restriction = get_option('pur_enable_ip_restriction', 0);
        $allowed_ip = get_option('pur_ip_address', '');

        if ($enable_ip_restriction) {
            $user_ip = $_SERVER['REMOTE_ADDR'];

            if ($user_ip !== $allowed_ip) {
                return new WP_Error('authentication_failed', __('Your IP address is not allowed to login.'));
            }
        }
    }

    return $user;
}
add_filter('wp_authenticate_user', 'pur_restrict_login_by_ip', 10, 2);

// Blocks Bulk actions for Plugin Updater role.
function pur_block_bulk_actions($actions)
{

    $user = wp_get_current_user();

    $blocked_role = 'plugin_updater';

    if (in_array($blocked_role, $user->roles)) {
        return array();
    }

    return $actions;
}

add_filter('bulk_actions-plugins', 'pur_block_bulk_actions');


/* TODO:
        - Test activation/deactivation of plugins externally.        
*/

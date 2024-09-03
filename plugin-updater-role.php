<?php
/*
Plugin Name: Plugin Updater Role
Plugin URI:  https://github.com/frankymelero/wp-plugin-updater-role
Description: This plugin adds the "Plugin Updater" role which allows only to list and update plugins.
Version:     1.0
Author:      Franky Melero
Author URI:  https://github.com/frankymelero
License:     GPL2
*/

// Create a custom role.
function pmr_create_plugin_manager_role() {
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
add_action('init', 'pmr_create_plugin_manager_role');

// Restrict access to certain admin pages.
function pmr_restrict_admin_access() {
    $user = wp_get_current_user();

    if (in_array('plugin_updater', (array) $user->roles)) {
        $restricted_pages = [
            'tools.php',
            'options-general.php',
            'options-writing.php',
            'options-reading.php',
            'options-discussion.php',
            'options-media.php',
            'options-permalink.php'
        ];

        $current_page = basename($_SERVER['PHP_SELF']);
        if (in_array($current_page, $restricted_pages)) {
            wp_redirect(admin_url());
            exit;
        }
    }
}
add_action('admin_init', 'pmr_restrict_admin_access');

// Remove activation/deactivation links from the plugins list.
function pmr_remove_activation_links($actions, $plugin_file, $plugin_data, $context) {
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
add_filter('plugin_action_links', 'pmr_remove_activation_links', 10, 4);

// Block plugin activation/deactivation through direct actions
function pmr_block_plugin_activation() {
    $user = wp_get_current_user();

    if (in_array('plugin_updater', (array) $user->roles)) {
        if (isset($_GET['action']) && in_array($_GET['action'], ['activate', 'deactivate'])) {
            wp_die('You are not allowed to activate/deactivate plugins.');
        }
    }
}
add_action('admin_init', 'pmr_block_plugin_activation');

// Hide via CSS bulk actions. 

function pmr_hide_elements_css() {
    if (current_user_can('plugin_updater')) {
        echo '<style>
            select[name="action"],
            select[name="action2"],
            .tablenav.top .actions,
            #doaction2 {
                display: none !important;
            }
        </style>';
    }
}
add_action('admin_head', 'pmr_hide_elements_css');

/* TODO:
        - Block actions in bulk from serverside.
        - Test activation/deactivation of plugins externally.
        - Add an interface to add the IP allowed to access with the plugin_updater role.
        - Block login from a different IP added in the pointabove.
*/

?>

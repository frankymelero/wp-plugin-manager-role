<?php

function pur_display_ip_settings() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['submit'])) {
        check_admin_referer('pur_save_ip');

        $enable_ip_restriction = isset($_POST['pur_enable_ip_restriction']) ? 1 : 0;
        $ip_address = sanitize_text_field($_POST['pur_ip_address']);

        update_option('pur_enable_ip_restriction', $enable_ip_restriction);
        update_option('pur_ip_address', $ip_address);

        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    $saved_ip = get_option('pur_ip_address', '');
    $enable_ip_restriction = get_option('pur_enable_ip_restriction', 0);
    ?>

    <div class="wrap">
        <h1>Plugin Updater Settings</h1>
        <p>Allow or disallow IP restriction to the user with role "Plugin Updater".</p>
        <form method="post" action="">
            <?php wp_nonce_field('pur_save_ip'); ?>
       
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="pur_enable_ip_restriction">Enable IP Restriction:</label>
                    </th>
                    <td>
                        <input type="checkbox" id="pur_enable_ip_restriction" name="pur_enable_ip_restriction" value="1" <?php checked(1, $enable_ip_restriction, true); ?> />
                        <label for="pur_enable_ip_restriction">Check to enable IP restriction</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="pur_ip_address">Allowed IP Address:</label>
                    </th>
                    <td>
                        <input type="text" id="pur_ip_address" name="pur_ip_address" value="<?php echo esc_attr($saved_ip); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
?>
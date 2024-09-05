# Plugin Updater Role Plugin

## Description

The **Plugin Updater Role Plugin** adds a custom user role named `Plugin Updater` to your WordPress site. This role is designed to allow users to view and manage plugins but restricts access to plugin activation, deactivation, and installation. Additionally, the plugin supports IP-based restrictions to limit access to specific IP addresses for users with this role.

## Features

- **Custom User Role**: Creates a user role named `Plugin Updater`.
- **Restricted Access**: Limits user access to only the Plugins list and Update Core pages.
- **IP Restriction**: Optionally restricts login access to users with the `Plugin Updater` role based on their IP address.
- **Hide Actions**: Removes activation and deactivation links from the plugins list for the `Plugin Updater` role.
- **Block Direct Actions**: Prevents plugin activation and deactivation through direct URL actions.
- **Settings Page**: Adds a settings page for configuring IP restrictions.

## Installation

1. **Download the Plugin**: Download the plugin ZIP file from the [GitHub repository](https://github.com/frankymelero/wp-plugin-updater-role).
2. **Install via WordPress Admin**:
   - Log in to your WordPress admin dashboard.
   - Go to `Plugins` > `Add New`.
   - Click on `Upload Plugin` and select the downloaded ZIP file.
   - Click `Install Now` and then activate the plugin.

## Configuration

1. **Configure IP Restrictions** (Optional):
   - Navigate to `Plugins` > `Plugin Updater Role Settings` to configure IP restrictions.
   - Enable IP restriction and enter the allowed IP address if required..

2. **Assign the Plugin Updater Role**:
   - Go to `Users` in the WordPress admin dashboard.
   - Edit or create a user and assign them the `Plugin Updater` role.

## Usage

- **Role Capabilities**: Users with the `Plugin Updater` role can access:
  - Plugins list (`plugins.php`)
  - Update Core (`update-core.php`)
  - Plugin Updates (`update.php`)

  They cannot:
  - Activate or deactivate plugins.
  - Install or delete plugins.

- **IP Restriction**: Users with the `Plugin Updater` role will need to log in from an allowed IP address if IP restriction is enabled.

## Frequently Asked Questions (FAQ)

### How do I add multiple IP addresses for restriction?

Currently, the plugin only supports a single IP address for restriction. 

### What happens if a `Plugin Updater` user tries to access restricted pages?

Users will be redirected to the Plugins list page.

### Can I customize the role's capabilities?

The plugin provides predefined capabilities for the `Plugin Updater` role. For further customizations, you may need to use additional plugins or custom code.

## Support

For support or questions, feel free to send me a message through [Linkedin](https://www.linkedin.com/in/fmelerodev/).

## Changelog

**0.2** - Added IP restriction feature and settings page.

**0.1** - Initial release with basic role and access restrictions.

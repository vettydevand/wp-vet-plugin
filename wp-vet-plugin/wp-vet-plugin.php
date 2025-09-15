<?php
/**
 * Plugin Name: WP Vet Plugin
 * Description: A WordPress plugin for managing a veterinary practice, built on top of FullCalendar.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://your-website.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-vet-plugin
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define('WP_VET_PLUGIN_FILE', __FILE__);
define('WP_VET_PLUGIN_DIR', plugin_dir_path(WP_VET_PLUGIN_FILE));
define('WP_VET_PLUGIN_URL', plugin_dir_url(WP_VET_PLUGIN_FILE));

// Include the main plugin class
require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin.php';

// Initialize the plugin
function wp_vet_plugin() {
    return WPVetPlugin::instance();
}

// Run the plugin
wp_vet_plugin();

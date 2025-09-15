<?php
/**
 * Plugin Name:       WP Vet Plugin
 * Description:       A WordPress plugin for managing a veterinary practice, built on top of FullCalendar.
 * Version:           1.1.0
 * Author:            Your Name
 * Author URI:        https://your-website.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-vet-plugin
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants for better management.
define('WP_VET_PLUGIN_VERSION', '1.1.0');
define('WP_VET_PLUGIN_FILE', __FILE__);
define('WP_VET_PLUGIN_DIR', plugin_dir_path(WP_VET_PLUGIN_FILE));
define('WP_VET_PLUGIN_URL', plugin_dir_url(WP_VET_PLUGIN_FILE));

/**
 * The code that runs during plugin activation.
 */
function activate_wp_vet_plugin() {
    require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin-activator.php';
    WPVetPlugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_vet_plugin() {
    require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin-deactivator.php';
    WPVetPlugin_Deactivator::deactivate();
}

// Register activation and deactivation hooks.
register_activation_hook(WP_VET_PLUGIN_FILE, 'activate_wp_vet_plugin');
register_deactivation_hook(WP_VET_PLUGIN_FILE, 'deactivate_wp_vet_plugin');

// Include the main plugin class.
require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, executing the plugin
 * means we just need to initialize the main class.
 */
function run_wp_vet_plugin() {
    $plugin = new WPVetPlugin();
    $plugin->run();
}

// Run the plugin after all other plugins are loaded.
add_action('plugins_loaded', 'run_wp_vet_plugin');

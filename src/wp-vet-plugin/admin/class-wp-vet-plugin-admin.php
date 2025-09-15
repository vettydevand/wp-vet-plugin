<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and registers all hooks for the admin area.
 * It is responsible for enqueuing styles and scripts, creating the options page,
 * and managing plugin settings via the WordPress Settings API.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/admin
 * @author     Gemini
 */
class WPVetPlugin_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name    The name of the plugin.
     * @param    string    $version        The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @param    string    $hook_suffix    The current admin page.
     */
    public function enqueue_styles($hook_suffix) {
        // Admin-specific stylesheets would be enqueued here.
        // Example: wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-vet-plugin-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @param    string    $hook_suffix    The current admin page.
     */
    public function enqueue_scripts($hook_suffix) {
        // Admin-specific JavaScript files would be enqueued here.
        // Example: wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-vet-plugin-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add an options page under the "Settings" menu.
     *
     * @since    1.0.0
     */
    public function add_options_page() {
        add_options_page(
            __('WP Vet Plugin Settings', 'wp-vet-plugin'), // Page Title
            __('WP Vet Plugin', 'wp-vet-plugin'),          // Menu Title
            'manage_options',                            // Capability
            'wp-vet-plugin-settings',                    // Menu Slug
            array($this, 'display_settings_page')       // Callback
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        require_once WP_VET_PLUGIN_DIR . 'admin/partials/wp-vet-plugin-admin-display.php';
    }

    /**
     * Register the plugin settings using the Settings API.
     *
     * This method registers the option group, adds a settings section, and defines
     * the individual fields for the Telegram integration.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register a new setting for the 'wp-vet-plugin-settings' page.
        register_setting(
            'wp_vet_plugin_options_group',      // Option group
            'wp_vet_plugin_options',          // Option name
            array($this, 'sanitize_options') // Sanitize callback
        );

        // Add a new section to a settings page.
        add_settings_section(
            'wp_vet_plugin_telegram_section',              // ID
            __('Telegram Bot Settings', 'wp-vet-plugin'), // Title
            null,                                          // Callback (optional)
            'wp-vet-plugin-settings'                     // Page
        );

        // Add a new field to a section of a settings page.
        add_settings_field(
            'telegram_bot_token',                          // ID
            __('Telegram Bot Token', 'wp-vet-plugin'),     // Title
            array($this, 'render_telegram_token_field'),  // Callback
            'wp-vet-plugin-settings',                    // Page
            'wp_vet_plugin_telegram_section'             // Section
        );

        add_settings_field(
            'telegram_chat_id',                            // ID
            __('Telegram Chat ID', 'wp-vet-plugin'),        // Title
            array($this, 'render_telegram_chat_id_field'),// Callback
            'wp-vet-plugin-settings',                    // Page
            'wp_vet_plugin_telegram_section'             // Section
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @since    1.0.0
     * @param    array    $input    Contains all settings fields as array keys.
     * @return   array    Sanitized array of options.
     */
    public function sanitize_options($input) {
        $new_input = array();
        if (isset($input['telegram_bot_token'])) {
            $new_input['telegram_bot_token'] = sanitize_text_field($input['telegram_bot_token']);
        }
        if (isset($input['telegram_chat_id'])) {
            // Chat ID can be negative (for channels), so we sanitize it as a string.
            $new_input['telegram_chat_id'] = sanitize_text_field($input['telegram_chat_id']);
        }
        return $new_input;
    }

    /**
     * Render the input field for the Telegram Bot Token option.
     *
     * This is a callback function for add_settings_field.
     *
     * @since    1.0.0
     */
    public function render_telegram_token_field() {
        $options = get_option('wp_vet_plugin_options');
        $token = isset($options['telegram_bot_token']) ? $options['telegram_bot_token'] : '';
        printf(
            '<input type="text" name="wp_vet_plugin_options[telegram_bot_token]" value="%s" class="regular-text" />',
            esc_attr($token)
        );
    }

    /**
     * Render the input field for the Telegram Chat ID option.
     *
     * This is a callback function for add_settings_field.
     *
     * @since    1.0.0
     */
    public function render_telegram_chat_id_field() {
        $options = get_option('wp_vet_plugin_options');
        $chat_id = isset($options['telegram_chat_id']) ? $options['telegram_chat_id'] : '';
        printf(
            '<input type="text" name="wp_vet_plugin_options[telegram_chat_id]" value="%s" class="regular-text" />',
            esc_attr($chat_id)
        );
        echo '<p class="description">' . __('Enter the user, group, or channel ID.', 'wp-vet-plugin') . '</p>';
    }
}

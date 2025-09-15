<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the admin area.
 */
class WPVetPlugin_Admin {

    private $plugin_name;
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        // Admin-specific stylesheets would be enqueued here.
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        // Admin-specific JavaScript files would be enqueued here.
    }

    /**
     * Add the options page to the admin menu.
     */
    public function add_options_page() {
        add_options_page(
            __('WP Vet Plugin Settings', 'wp-vet-plugin'),
            __('WP Vet Plugin', 'wp-vet-plugin'),
            'manage_options',
            'wp-vet-plugin',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Render the settings page display.
     */
    public function display_settings_page() {
        require_once WP_VET_PLUGIN_DIR . 'admin/partials/wp-vet-plugin-admin-display.php';
    }

    /**
     * Register the plugin settings.
     */
    public function register_settings() {
        register_setting(
            'wp_vet_plugin_options', // Option group
            'wp_vet_plugin_options', // Option name
            array($this, 'sanitize_options') // Sanitize callback
        );

        add_settings_section(
            'wp_vet_plugin_telegram_section',
            __('Telegram Bot Settings', 'wp-vet-plugin'),
            null,
            'wp_vet_plugin_options' // Page
        );

        add_settings_field(
            'telegram_bot_token',
            __('Telegram Bot Token', 'wp-vet-plugin'),
            array($this, 'render_telegram_token_field'),
            'wp_vet_plugin_options', // Page
            'wp_vet_plugin_telegram_section' // Section
        );

        add_settings_field(
            'telegram_chat_id',
            __('Telegram Chat ID', 'wp-vet-plugin'),
            array($this, 'render_telegram_chat_id_field'),
            'wp_vet_plugin_options', // Page
            'wp_vet_plugin_telegram_section' // Section
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @param array $input Contains all settings fields as array keys
     * @return array
     */
    public function sanitize_options($input) {
        $new_input = array();
        if (isset($input['telegram_bot_token'])) {
            $new_input['telegram_bot_token'] = sanitize_text_field($input['telegram_bot_token']);
        }
        if (isset($input['telegram_chat_id'])) {
            $new_input['telegram_chat_id'] = sanitize_text_field($input['telegram_chat_id']);
        }
        return $new_input;
    }

    /**
     * Render the Telegram Bot Token field.
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
     * Render the Telegram Chat ID field.
     */
    public function render_telegram_chat_id_field() {
        $options = get_option('wp_vet_plugin_options');
        $chat_id = isset($options['telegram_chat_id']) ? $options['telegram_chat_id'] : '';
        printf(
            '<input type="text" name="wp_vet_plugin_options[telegram_chat_id]" value="%s" class="regular-text" />',
            esc_attr($chat_id)
        );
    }
}

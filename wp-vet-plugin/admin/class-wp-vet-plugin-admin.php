<?php

class WPVetPlugin_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('add_meta_boxes', array($this, 'add_appointment_metabox'));
        add_action('save_post_appointment', array($this, 'save_appointment_metabox_data'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_options_page(
            'WP Vet Plugin Settings',
            'WP Vet Plugin',
            'manage_options',
            'wp-vet-plugin-settings',
            array($this, 'display_settings_page')
        );
    }

    public function display_settings_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-vet-plugin-admin-display.php';
    }

    public function register_settings() {
        register_setting('wp_vet_plugin_settings', 'wp_vet_telegram_token', 'sanitize_text_field');
        register_setting('wp_vet_plugin_settings', 'wp_vet_telegram_chat_id', 'sanitize_text_field');

        add_settings_section(
            'wp_vet_plugin_telegram_section',
            'Telegram Bot Settings',
            null,
            'wp_vet_plugin_settings'
        );

        add_settings_field(
            'wp_vet_telegram_token',
            'Telegram Bot Token',
            array($this, 'render_telegram_token_field'),
            'wp_vet_plugin_settings',
            'wp_vet_plugin_telegram_section'
        );

        add_settings_field(
            'wp_vet_telegram_chat_id',
            'Telegram Chat ID',
            array($this, 'render_telegram_chat_id_field'),
            'wp_vet_plugin_settings',
            'wp_vet_plugin_telegram_section'
        );
    }

    public function render_telegram_token_field() {
        $token = get_option('wp_vet_telegram_token');
        echo '<input type="text" name="wp_vet_telegram_token" value="' . esc_attr($token) . '" />';
    }

    public function render_telegram_chat_id_field() {
        $chat_id = get_option('wp_vet_telegram_chat_id');
        echo '<input type="text" name="wp_vet_telegram_chat_id" value="' . esc_attr($chat_id) . '" />';
    }

    public function add_appointment_metabox() {
        add_meta_box(
            'appointment_details',
            'Appointment Details',
            array($this, 'render_appointment_metabox'),
            'appointment',
            'normal',
            'high'
        );
    }

    public function render_appointment_metabox($post) {
        wp_nonce_field('save_appointment_metabox_data', 'appointment_metabox_nonce');

        $appointment_date = get_post_meta($post->ID, 'appointment_date', true);
        if (empty($appointment_date)) {
            $appointment_date = date('Y-m-d');
        }

        echo '<label for="appointment_date">Appointment Date:</label>';
        echo '<input type="date" id="appointment_date" name="appointment_date" value="' . esc_attr($appointment_date) . '" />';
    }

    public function save_appointment_metabox_data($post_id) {
        if (!isset($_POST['appointment_metabox_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['appointment_metabox_nonce'], 'save_appointment_metabox_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['appointment_date'])) {
            $sanitized_date = sanitize_text_field($_POST['appointment_date']);
            update_post_meta($post_id, 'appointment_date', $sanitized_date);
        }

        if (isset($_POST['post_title'])) {
            $sanitized_title = sanitize_text_field($_POST['post_title']);
            $post_data = array(
                'ID' => $post_id,
                'post_title' => $sanitized_title,
            );
            wp_update_post($post_data);
        }
    }
}
